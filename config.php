<?php

	include_once('db.class.php');
	include_once('security.php');
	
	
	$db = new Db($connInfo);
	
	function printResponse($data, $status = 'success')
	{
		$xmlDoc = new DOMDocument();
		$xmlDoc->encoding = 'UTF-8';
		
		$root = $xmlDoc->appendChild($xmlDoc->createElement('root'));
		
		$root->appendChild($xmlDoc->createElement('status', $status));
		
		$infoBl = $root->appendChild($xmlDoc->createElement('response'));

		foreach($data as $key => $val)
			$infoBl->appendChild($xmlDoc->createElement($key, $val));
		
		header("Content-Type: text/plain");
		$xmlDoc->formatOutput = true;
		
		die($xmlDoc->saveXml());
	}
	
	function printListResponse($data, $itemTitle, $status = 'success')
	{
		$xmlDoc = new DOMDocument();
		
		$root = $xmlDoc->appendChild($xmlDoc->createElement('root'));
		$root->appendChild($xmlDoc->createElement('status', $status));
		
		$items = $root->appendChild($xmlDoc->createElement('response'));
		
		if(count($data) > 0)
		{
			foreach($data as $item)
			{
				$el = $items->appendChild($xmlDoc->createElement($itemTitle));
				
				foreach($item as $key => $val)
					$el->appendChild($xmlDoc->createElement($key, $val));
			}
		}
		
		header("Content-Type: text/plain");
		$xmlDoc->formatOutput = true;
		
		die($xmlDoc->saveXml());
	}
	
	function printError($errorMessage)
	{
		printResponse(array('message' => $errorMessage), 'error');
	}
	
	function isValidInputData($input, $storage)
	{
		return isset($storage[$input]) && !empty($storage[$input]);
	}

?>