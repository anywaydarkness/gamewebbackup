<?php

	include_once('config.php');
	
	$data = $_POST;
	
	if(!isset($data['action']) || empty($data['action']))
		printError('invalid params');
	
	switch($data['action'])
	{
		case 'get':
		
			$sql = 'SELECT * FROM fuels';
			$response = $db->query($sql, array());
			
			printListResponse($response, 'fuel');
		
		break;
		
		case 'pay':
		
			//$sql = 'UPDATE bank_accounts SET amount = amount - :price WHERE user_id = :userId';
			//$db->query($sql);
			
			//printResponse('success', array('message' => 'success'));
		
		break;
		
		default:
			printError('invalid params');
		break;
	}

?>