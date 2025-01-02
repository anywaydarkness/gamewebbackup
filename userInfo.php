<?php

	include_once('config.php');
	
	$data = $_GET;
	
	if(!isset($data['action']) || empty($data['action']) || !isset($data['userId']) || empty($data['userId']))
		printError('incorect data');
	
	switch($data['action'])
	{	
		case 'getBasic':
		
			if(!isValidInputData($data['userId'], $data))
				printError('invalid user id');
		
			$sql = "
			
				SELECT users.id AS uid, CONCAT(users.fname,' ',users.lname) AS uname, bank_accounts.amount, money_types.symb,
				fractions.color AS chatColor, fractions.title AS frcTitle, ranks_frcs.title AS rankTitle,
				fractions.id AS frcId FROM users 
				INNER JOIN bank_accounts ON users.id = bank_accounts.user_id 
				INNER JOIN money_types ON bank_accounts.money_type = money_types.id 
				INNER JOIN users_frcs ON users_frcs.userId = users.id 
				INNER JOIN fractions ON users_frcs.frcsId = fractions.id 
				INNER JOIN ranks_frcs ON fractions.id = ranks_frcs.frcsId AND ranks_frcs.rankId = users_frcs.rankId

				WHERE users.id = :userId AND bank_accounts.acc_type = 'main'";
					
			$response = $db->query($sql, array( 'userId' => $data['userId'] ));
			
			if(!$response)
				printError('user not fond');
			else
				printResponse($response);
		
		break;
		
		case 'getStat':
		
				//print_r($data); die();
		
			if(!isValidInputData('userId', $data))
				printError('invalid user id');
		
			$sql = "
			
				SELECT users.id AS uid, CONCAT ( users.fname, ' ', users.lname) AS uname,
				donate.amount as donate, user_addinfo.sex, user_addinfo.health, user_addinfo.armour,
				user_addinfo.level_point, user_addinfo.moneys, deposits.amount AS deposit,
				fractions.title, ranks_frcs.title as rank_title
				FROM users 
				INNER JOIN donate ON users.id = donate.user_id
				INNER JOIN user_addinfo ON users.id = user_addinfo.user_id
				INNER JOIN deposits ON users.id = deposits.user_id
				INNER JOIN users_frcs ON users.id = users_frcs.userId
				INNER JOIN fractions ON users_frcs.frcsId = fractions.id
				INNER JOIN ranks_frcs ON users_frcs.rankId = ranks_frcs.rankId AND users_frcs.frcsId = ranks_frcs.frcsId
				WHERE users.id = :userId;
			
			";
			
			$response = $db->query($sql, array('userId' => $data['userId']));
			
			if(!$response)
				printError('user not found');
			else
				printResponse($response);
		
		break;
		
		case 'getInfoById':
		
		break;
		
		case 'getUserItems':
		
			if(!isset($data['userId']) || empty($data['userId']))
				printError('invalid user id');
		
			$sql = 'SELECT items.id AS id, items.title, items.icon_id, items.weight, user_items.cord
					FROM user_items INNER JOIN items ON user_items.id_item = items.id WHERE user_items.id_user = :userId';
		
			$response = $db->query($sql, array('userId' => $data['userId']));
			
			printListResponse( (!$response) ? array() : $response , 'item');

			//echo '<pre>'; print_r($response); die();

			/*if(!$response)
				printListResponse()
			else
				generateResponse('success', $result);*/
		
		break;
		
		default:
			printError('invalid action');
		break;
	}

?>