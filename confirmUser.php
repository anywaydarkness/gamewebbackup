<?php

	include_once('config.php');
	
	if(!isset($_POST['codeConfirm']) || empty($_POST['codeConfirm']))
	{
		response('error', array('info', 'invalid params'));
		die();
	}
	
	$response = $db->query('SELECT id FROM userwaitconfirms WHERE code_confirm = :codeConfirm', array('codeConfirm' => $_GET['code']));

	if(!$response)
	{
		response('error', array( 'info' => 'invalid code' ) );
		die();
	}

	$userId = $response[0]['id'];
	
	$db->query('UPDATE users SET state = true WHERE id = :userId', array('userId' => $userId));
	$db->query('DELETE FROM userwaitconfirms WHERE code_confirm = :codeConfirm', array('codeConfirm' => $_GET['code']));
	
	echo 'Account success confirmed!';

?>