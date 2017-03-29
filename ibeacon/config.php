<?php
	return array(
		
		// Set the link to your openHAB.
		url2openhab => "https://deinurl/",
		
		// Show DeviceID
		// If you don't know your DeviceID - Default: false
		showDeviceID => false,
		
		// Name of database file, please place the file outside the www-root folder but reachable for the webserver.
		// Use a long filename ex: nzzXTzSbUezCXkE5dTE2.sqlite
		dbFile => "nzzXTzSbUezCXkE5dTE2.sqlite",
    // Array with Device IDs. 
		// Ids seperated by comma ("ID1", "ID2", "ID3")
		devices => array(
			'ID1',
			'ID2'
		),
    //Item (Switch) to Change ('ID1' => 'item', 'ID2' => 'item', 'ID3' => 'item')
		itemName => array(
			'ID1' => 'presence_max',
			'ID2' => 'presence_peter',
		),
    // Actions at arrive ('ID1' => 'action', 'ID2' => 'action', 'ID3' => 'action')
		arriveValue => array(
			'ID1' => 'ON',
			'ID2' => 'ON',
		),
    // Actions at leave ('ID1' => 'action', 'ID2' => 'action', 'ID3' => 'action')
		leaveValue => array(
			'ID1' => 'OFF',
			'ID2' => 'OFF',
		),
		curlOptions => array(
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HTTPHEADER => array('Content-Type: text/plain','Accept: application/json'),
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_USERPWD => "username:password",
			CURLOPT_CUSTOMREQUEST => 'PUT',
			CURLOPT_POSTFIELDS => NULL,
		),
	);
?>
