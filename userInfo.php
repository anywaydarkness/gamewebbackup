<?php

	include_once('config.php');
	
	$data = $_POST;
	
	if(!isValidInputData('action', $data) || !isValidInputData('userId', $data))
		printError('incorect data');
	
	switch($data['action'])
	{	
		case 'getBasic':
		
			if(!isValidInputData('userId', $data))
				printError('invalid user id');
		
			$sql = "
			
				SELECT users.id AS uid, CONCAT(users.fname,' ',users.lname) AS uname, user_addinfo.sex, user_addinfo.health, user_addinfo.armour,
				user_addinfo.eat_lvl, user_addinfo.water_lvl, user_addinfo.crap_lvl,
				user_addinfo.uLevel AS uLevel, user_addinfo.level_point as uLevelPoints, user_addinfo.moneys AS money,
				fractions.color AS chatColor, fractions.title AS frcTitle, ranks_frcs.title AS rankTitle,
				fractions.id AS frcId FROM users
				INNER JOIN users_frcs ON users_frcs.userId = users.id 
				INNER JOIN fractions ON users_frcs.frcsId = fractions.id 
                INNER JOIN user_addinfo ON users.id = user_addinfo.user_id
				INNER JOIN ranks_frcs ON fractions.id = ranks_frcs.frcsId AND ranks_frcs.rankId = users_frcs.rankId
				WHERE users.id = :userId";
					
			$response = $db->query($sql, array( 'userId' => $data['userId'] ));
			
			if(!$response)
				printError('user not fond');
			else
				printResponse($response);
		
		break;
		
		case 'getStat':
		
			if(!isValidInputData('userId', $data))
				printError('invalid user id');
		
			$sql = "
			
				SELECT users.id AS uid, CONCAT ( users.fname, ' ', users.lname) AS uname,
				donate.amount as donate, user_addinfo.sex, user_addinfo.health, user_addinfo.armour,
				user_addinfo.uLevel AS uLevel, user_addinfo.level_point as uLevelPoints, user_addinfo.moneys AS money, deposits.amount AS deposit,
				fractions.title AS fracTitle, ranks_frcs.title AS fracRank
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
		
		break;
		
		case 'satisfyHunger':
		
			$sql = "UPDATE user_addinfo SET eat_lvl = :eat, water_lvl = :water, crap_lvl = :crap WHERE id = :userId";
		
			if(!isValidInputData('eat', $data) || !isValidInputData('water', $data) || !isValidInputData('crap', $data))
				printError('no find eat/water/crap values');
		
			$response = $db->query($sql, array('userId' => $data['userId'], 'eat' => $data['eat'], 'water' => $data['water'], 'crap' => $data['crap']));
			
			if(isset($response['error']))
				printError('error');
			else
				printResponse(array());
		
		break;
		
		default:
			printError('invalid action');
		break;
	}

?>