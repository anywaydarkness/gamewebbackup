<?php

	include_once('config.php');
	
	$data = $_POST;
	
	if(!isValidInputData('action', $data))
		printError('invalid action');

	switch($data['action'])
	{
		case 'signIn':
			
			if(!isValidInputData('passwd', $data))
				printError('invalid password');
			
			$sql = "SELECT admins.level_id, CONCAT(users.fname, ' ', users.lname) AS uname, admin_levels.title, admin_levels.color
					FROM admins
					JOIN admin_levels ON admin_levels.id = admins.level_id AND admin_levels.passwd = :passwd
                    JOIN users ON users.id = admins.user_id
					WHERE admins.user_id = :userId;";
					
			$response = $db->query($sql, array('userId' => $data['userId'], 'passwd' => $data['passwd']));
			
			if(!$response)
				printError('Access denied');
			else
				printResponse($response);
			
		break;
	}
	
	printError('invalid action');

?>