<?php

	$data = $_POST;
	
	include_once('config.php');
	
	if(!isValidInputData('login', $data) || !isValidInputData('pass', $data))
		printError('invalid data');

	$response = $db->query('SELECT id AS user_id FROM users WHERE email=:login AND passwd=:pass', 
						array('login' => $data['login'], 'pass' => md5($data['pass']) ) );
		
	if(!$response)
		printError('incorect data');
	else
		printResponse($response);

?>