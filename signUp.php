<?php

	// CHECK PASSWORD TO EMPTY

	include_once('config.php');
	
	$keys = array('name', 'lastname', 'email', 'password', 'sec_question', 'sec_answer');
	
	foreach($keys as $key)
	{
		if(!isset($_POST[$key]) || empty($_POST[$key]))
		{
			response('error', array('info' => 'Invalid params'));
			die();
		}
	}

	$sql = 'INSERT INTO users (fname, lname, email, passwd, sec_quest, sec_answ) 
			VALUES ( :fname, :lname, :email, :passwd, :sec_quest, :sec_answ )';

	$data = array('fname' => $_POST['name'], 'lname' => $_POST['lastname'], 'email' => $_POST['email'],
				  'passwd' => md5($_POST['password']), 'sec_quest' => $_POST['sec_question'], 'sec_answ' => $_POST['sec_answer']);

	$db->query($sql, $data);
	
	$userId = $db->lastInsId();
	$code = md5($userId);
	
	$sql = 'INSERT INTO userwaitconfirms (user_id, code_confirm) VALUES ( :userId, :code )';
	$db->query($sql, array( 'userId' => $userId, 'code' => $code ) );
	
	$db->query('INSERT INTO ');
	
	response('success', array('info' => 'Confirm letter sent to '.$_POST['email'] ));

?>