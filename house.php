<?php

	include_once('config.php');
	
	$data = $_POST;
	
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
		
		case 'getAllHouse':
		case 'houseInfo':

			//if(!isValidInputData('houseId', $data))
			//	printError('invalid house id');

			$sql = "SELECT house.id, owner_id, lock_door, CONCAT(users.fname, ' ', users.lname) AS uname
					FROM house LEFT JOIN  users ON users.id = house.owner_id";
					
			if($data['action'] == 'houseInfo')
				$sql += "WHERE house.id = :houseId";
					
			$response = $db->query($sql, array());
			
			if(!$response)
				printError('no find house');
			else
				printResponse($response);

		break;
		
		case 'getMarkers':
			
			$response = $db->query('SELECT * FROM house_marker', array());
			
			if(!$response)
				printError('No find markers');
			else
				printResponse($response);
			
		break;
		
		case 'createHouse':
		
			if(!isValidInputData('houseCord', $data))
				printError('invalid house cord');
			
			$sql = "START TRANSACTION;
					INSERT INTO house_marker(cord) VALUES (:houseCord);
					INSERT INTO house () VALUES ();
					SELECT LAST_INSERT_ID() AS house_id;
					INSERT INTO house_improv (house_id) VALUES (LAST_INSERT_ID());
					COMMIT;";
			
			$response = $db->query($sql, array('houseCord' => $data['houseCord']));
			printResponse($response);
		
		break;
		
		case 'deleteHouse':
			
			if(!isValidInputData('houseId', $data))
				printError('invalid house id');
			
			$sql = "START TRANSACTION;
					DELETE FROM house WHERE id = :houseId;
					DELETE FROM house_improv WHERE house_id = :houseId;
					DELETE FROM house_marker WHERE house_id = :houseId;
					COMMIT;";
			
			$db->query($sql, array('houseId' => $data['houseId']));
			printResponse(array());
			
		break;
	}
	
	printError('invalid action');
	
?>