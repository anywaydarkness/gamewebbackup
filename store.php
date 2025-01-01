<?php

	include_once('config.php');
	
	$data = $_POST;
	
	if(!isset($data['action']) || empty($data['action']))
		printError('invalid action');
	
	switch($data['action'])
	{
		case 'getProductInfo':
			
			if(!isset($data['productId']) || empty($data['productId']))
				printError('invalid product id');
			
			$sql = 'SELECT * FROM items WHERE id = :productId';
			$response = $db->query($sql, array( 'productId' => $data['productId']));
			
			if(!$response)
				printError('product not found');
			else
				printResponse($response);
			
		break;
		
		default:
			printError('invalid switch action');
		break;
	}

?>