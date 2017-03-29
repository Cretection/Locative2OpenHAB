<?php
	class beacon {
		protected $device		= NULL;
		protected $device_model	= NULL;
		protected $device_type	= NULL;
		protected $id			= NULL;
		protected $timestamp	= NULL;
		protected $trigger		= NULL;
		protected $datetime		= NULL;
		protected $database		= NULL;
		function __construct($config){
			$this->setVars($config);
			$this->setVars($_POST);
			$this->setVars($_GET);
			
			if (isset($this->device)) {
				$this->datetime = date('Y-m-d H:i:s');
				$this->database = new PDO("sqlite:$this->dbFile");
				$this->createDatabase();
				$this->showId();
				$this->verifyDevice($this->device);
			} else {
				echo "No parameters available!";
				exit();
			}
		}
		protected function setVars($param){
			if (!empty($param)) {
				foreach($param as $key => $value) {
					$this->$key = $value;
				}
			}
		}
		protected function showId() {
			if ($this->showDeviceID) {
				$this->updateDatabase("Show ID only");
				echo 'Your Device: '.$this->device.' ';
			}
		}
		protected function verifyDevice($device){
			if (in_array($this->device, $this->devices)) {
				$this->actions();
			} else {
				$this->updateDatabase("Device not allowed");
				echo "Device not allowed";
				exit();
			}
		}
		protected function item(){
			foreach ($this->itemName as $key => $value) {
				if ($key == $this->device){
					return $value;
				}
			}
		}
		protected function actions(){
			$restApi = $this->url2openhab.'rest/items/'.$this->item().'/state';
			switch($this->trigger){
				case "enter":
					$this->updateDatabase("ID: ".$this->id);
					foreach ($this->arriveValue as $key => $value) {
						if ($key == $this->device){
							$this->cURL($restApi,$value);
							echo "$restApi = $value";
						}
					}
					break;
				case "exit":
					$this->updateDatabase("ID: ".$this->id);
					foreach ($this->leaveValue as $key => $value) {
						if ($key == $this->device){
							$this->cURL($restApi,$value);
							echo "$restApi = $value";
						}
					}
					break;
				case "test":
					$this->updateDatabase(NULL);
					echo "Test work!";
					break;
				default:
					$this->updateDatabase("Trigger forbidden");
					echo "Trigger forbidden!";
					exit();
					break;
			}
		}
		protected function cURL($url,$itemValue){
			$ch = curl_init($url);
			$curlOption = $this->curlOptions;
			$curlOption['10015'] .= $itemValue;
			curl_setopt_array($ch, $curlOption);
			if(curl_exec($ch) === false){
				$this->updateDatabase(curl_error($ch));
				echo 'Curl-Fehler: ' . curl_error($ch);
				exit();
			}else{
				curl_exec($ch);
				return true;
			}
			curl_close($ch);
		}
		protected function createDatabase() {
			$this->database->exec("CREATE TABLE IF NOT EXISTS log ('index'	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, 'datetime' TEXT NOT NULL,'device' TEXT NOT NULL,'latitude' TEXT, 'longitude'	TEXT, 'timestamp' TEXT, 'trigger'	TEXT,'error' TEXT);");
		}
		protected function updateDatabase($error) {
			$sql = ('INSERT INTO log (datetime, device, latitude, longitude, timestamp, trigger, error) VALUES (:datetime, :device, :latitude, :longitude, :timestamp, :trigger, :error)');
			$q = $this->database->prepare($sql);
			$a = array (':datetime'=>$this->datetime,
		                ':device'=>$this->device,
		                ':latitude'=>$this->latitude,
		                ':longitude'=>$this->longitude,
		                ':timestamp'=>$this->timestamp,
		                ':trigger'=>$this->trigger,
		                ':error'=>$error);
			if ($q->execute($a)) {
				return true;
		    }else{
			    echo "Database Error!";
			    exit();
		    }
		}
	}
	$config = require 'config.php';
	$beacon = new beacon($config);
?>
