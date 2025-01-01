<?php

	include_once('config.php');
	
	// debug - GET, working - POST
	$data = $_POST;;
	
	if(!isset($data['action']) || empty($data['action']))

	if(!isset($data['action']) || empty($data['action']))
		printError('invalid params');
	
	switch($data['action'])
	{
		case 'info':
		
			if(!isset($data['id']) || empty($data['id']))
				printError('invalid atm id');
		
			$sql = 'SELECT addr, moneys FROM atm WHERE id = :id';
			$response = $db->query($sql, array( 'id' => $data['id'] ));
			
			if(!$response)
				printError('no finded ATM');
			else
				printResponse($response);
		
		break;
		default:
			printError('invalid key request');
		break;
	}

?>