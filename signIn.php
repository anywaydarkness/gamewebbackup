<?php

	$data = $_POST;
	
	include_once('config.php');
	
	if(!isset($data['login']) || empty($data['login']) || 
		!isset($data['pass'])  || empty($data['pass']))
	{
		printError('incorect data');
	}

	$response = $db->query('SELECT id AS user_id FROM users WHERE email=:login AND passwd=:pass', 
						array('login' => $data['login'], 'pass' => md5($data['pass']) ) );
		
	if(!$response)
		printError('incorect data');
	else
		printResponse($response);

?>