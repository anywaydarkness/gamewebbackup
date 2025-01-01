<?php

	include_once('config.php');

	$data = $_GET;
	
	if(!isset($data['userId']) || empty($data['userId']))
		response('error', array('info' => 'incorect data'));
	
	$sql = 'SELECT adminInfo.id as level, adminInfo.title, adminInfo.color FROM admins 
			INNER JOIN adminInfo ON adminInfo.id = admins.adminId 
			WHERE admins.userId = :userId';
	
	$response = $db->query($sql, array('userId' => $data['userId']));
	
	if(!$response)
		$response =  array('level' => 0, 'title' => 'User', 'color' => '#FFFFFF'); 
	else
		$response = $response[0];
	
	printResponse('success', $response);

?>