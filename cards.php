<?php

	include_once('config.php');
	
	$data = $_POST;
	
	if(!isset($data['action']) || empty($data['action']))
		printError('invalid data');
	
	switch($data['action'])
	{
		case 'login':
		
			if(!isset($data['cardNumb']) || empty($data['cardNumb']) || 
				!isset($data['cardPass']) || empty($data['cardPass']))
					printError('invalid params');
					
			$sql = 'SELECT id FROM user_cards WHERE card_numb = :cardNumb AND pass = :cardPass';
			$response = $db->query($sql, $data);
			
			if(!$response)
				printError('invalid password');
			else
				printResponse($response);
		
		break;
		
		case 'list':
		
			if(!isset($data['userId']) || empty($data['userId']))
				printError('no player id specified');

			$sql = "SELECT user_cards.id, cards.title, cards.icon_id, user_cards.amount, 
					CONCAT(users.fname,' ',users.lname) AS uname, user_cards.card_numb
					from user_cards join cards on cards.id = user_cards.card_id join users on user_cards.user_id = users.id 
					where user_cards.user_id = :userId order by user_cards.id;";
			
			$response = $db->query($sql, array('userId' => $data['userId']), true);
			
			if(!$response)
				printError('no finded card');
			else
				printListResponse($response, 'card');
				
		
		break;
		
		case 'getBalance':
			
			$sql = 'SELECT amount FROM user_cards WHERE card_numb = :cardNumb';
			$response = $db->query($sql, array( 'cardNumb' => $_POST['cardNumb'] ));
			
			if(!$response)
				response('error', array('info' => 'no card finded'));
			else
				response('success',  array('info' => $response[0]['amount']));
			
		break;
		
		case 'reciving':
		
			$sql = 'UPDATE user_cards SET amount = amount - :amount WHERE card_numb = :cardNumb;';
			$db->query($sql, array('amount' => $_POST['amount'], 'cardNumb' => $_POST['cardNumb']));
			
			$sql = 'UPDATE bank_accounts SET amount = amount + :amount  WHERE acc_type = \'main\' AND user_id = (SELECT user_id FROM user_cards WHERE card_numb = :cardNumb);';
			$db->query($sql, array('amount' => $_POST['amount'], 'cardNumb' => $_POST['cardNumb']));
			
			$sql = 'UPDATE atm SET moneys = moneys - :amount WHERE id = :atmId;';
			$db->query($sql, array('amount' => $_POST['amount'], 'atmId' => $_POST['atmId']));
			
			printResponse(array('message', 'success'));
		
		break;
		
		case 'transfer':
		
			$sql = 'UPDATE user_cards SET amount = amount - :amount WHERE card_numb = :fromCardNumb;';
			$db->query($sql, array('amount' => $_POST['amount'], 'fromCardNumb' => $_POST['fromCardNumb']));
			
			$sql = 'UPDATE user_cards SET amount = amount + :amount WHERE card_numb = :toCardNumb;';
			$db->query($sql, array('amount' => $_POST['amount'], 'toCardNumb' => $_POST['toCardNumb']));
			
			$sql = 'SELECT CONCAT(fname, \' \', lname) AS uname FROM users WHERE id = (SELECT user_id FROM user_cards WHERE card_numb = :toCardNumb)';
			$response = $db->query($sql, array('toCardNumb' => $_POST['toCardNumb']));
			
			printResponse($response);
		
		break;
		
		default:
			printError('invalid action');
		break;
	}

?>