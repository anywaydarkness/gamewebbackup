<?php

	include_once('config.php');
	
	$data = $_GET;
	
	if(!isValidInputData('action', $data))
		printResponse('invalid action');
	
	switch($data['action'])
	{
		case 'create':
		
			$db->query('INSERT INTO house() VALUES ()', array());
			$houseId = $db->lastInsId();
			
			$db->query('INSERT INTO house_improv (house_id) VALUES (:houseId)', array('houseId' => $houseId) );
			
			printResponse(array());
		
		break;
		
		case 'houseInfo':

			if(!isValidInputData('houseId', $data))
				printError('invalid house id');

			$sql = "SELECT house.id, owner_id, lock_door, CONCAT(users.fname, ' ', users.lname) AS uname
					FROM house LEFT JOIN  users ON users.id = house.owner_id WHERE house.id = :houseId";
					
			$response = $db->query($sql, array('houseId' => $data['houseId']));

			if(!$response)
				printError('no find house');
			else
				printResponse($response);

		break;
	}
	
	printError('invalid action');
	
?>