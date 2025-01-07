<?php

	include_once('security.php');
	include_once('config.php');
	
	$sql = "SELECT * FROM users WHERE id = 1; INSERT INTO house () VALUES ();";
			
	$response = $db->query($sql, array());
	
	echo '<pre>'; print_r($response);

?>