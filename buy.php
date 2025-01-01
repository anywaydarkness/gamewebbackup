<?php

	//print_r($_POST);
	
	include_once('config.php');
	
	$userId = $_POST['userId'];
	unset($_POST['userId']);

	foreach($_POST as $item)
	{
		$data = json_decode($item, true);

		$sql = 'INSERT INTO user_items (id_user, id_item, cord) VALUES (:userId, :itemId, :cord)';
		$db->query($sql, array('userId' => $userId, 'itemId' => $data['id'], 'cord' =>  json_encode( array('x' => $data['x'], 'y' => $data['y']) ) ));
		
		$response = $db->query('SELECT price FROM items WHERE id = :itemId', array('itemId' => $data['id']));
		
		$price += $response[0]['price'];
	}
	
	$sql = 'UPDATE bank_accounts SET amount = amount - :amount WHERE user_id = :userId AND acc_type = \'main\';';
	
	$db->query($sql, array( 'amount' => $price, 'userId' => $userId ));
	
	echo $price;

?>