<?php

	$data = $_POST;
	
	include_once('config.php');
	
	if(!isset($data['action']) || empty($data['action']))
		printError('invalid params');
	
	switch($data['action'])
	{
		case 'create':

			try
			{
				$sql = 'INSERT INTO clubs (title) VALUES (:title);';
				$db->query($sql, array('title' => $data['title']));
				
				$famId = $db->lastInsId();
				
				for($i = 1; $i < 9; $i++)
					$db->query('INSERT INTO club_ranks (rank_id, club_id) VALUES ( :rank_id, :club_id )', array( $i, $famId ));
				
				$db->query('INSERT INTO club_ranks (rank_id, club_id, title) VALUES ( :rank_id, :club_id, :title )', array( 9, $famId, 'Deputy'));
				$db->query('INSERT INTO club_ranks (rank_id, club_id, title) VALUES ( :rank_id, :club_id, :title )', array( 10, $famId, 'Head'));
				
				$db->query('INSERT INTO club_users (user_id, club_id, rank_id) VALUES ( :user_id, :club_id, :rank_id)', array( $data['userId'], $famId, 10));
				
				printResponse(array());
			}
			catch(Exception $ex)
			{
				printError('Family '.$data['title'].' already exists');
			}

			//$db->query('INSERT INTO club_users ( user_id, club_id, rank_id ) VALUES ( ) ', array());
		
		break;
		
		case 'findUserClub':
		
			if(!isset($data['userId']) || empty($data['userId']))
				printError('invalid user id');
			
			$sql = 'SELECT club_users.club_id, club_users.rank_id,	clubs.title, clubs.color, club_ranks.title AS rank_title
					FROM club_users JOIN clubs ON club_users.club_id = clubs.id
					JOIN club_ranks ON club_ranks.rank_id = club_users.rank_id AND club_ranks.club_id = club_users.club_id
					WHERE club_users.user_id = :userId';
					
			$response = $db->query($sql, array('userId' => $data['userId']));
		
			if(!$response)
				printError('The user has not joined any club.');
			else
				printResponse($response);
		
		break;
		
		case 'join':
		
			if( !isset($data['userId']) || empty($data['userId']) ||
				!isset($data['clubId']) || empty($data['clubId']) ||
				!isset($data['rankId']) || empty($data['rankId']))
					printError('invalid params');
		
			$sql = 'INSERT INTO club_users ( user_id, club_id, rank_id ) VALUES ( :userId, :clubId, :rankId )';
			$db->query($sql, array( 'userId' => $data['userId'], 'clubId' => $data['clubId'], 'rankId' => $data['rankId'] ));
			
			printResponse(array());
		
		break;
		
		default:
			printError('invalid action');
		break;
	}

?>