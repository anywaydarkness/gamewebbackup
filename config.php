<?php

	error_reporting(E_ALL);

	include_once('db.class.php');
	include_once('security.php');
	
	
	$db = new Db($connInfo);
	
	function printResponse($data, $status = 'success')
	{
		$xml = new DOMDocument();
		
		$root = $xml->appendChild($xml->createElement('root'));
		$root->appendChild($xml->createElement('status', $status));
		
		$response = $root->appendChild($xml->createElement('response'));
		
		arrayToXml($xml, $response, $data);
		
		header("Content-Type: text/plain");
		$xml->formatOutput = true;
		
		die($xml->saveXml());
	}
	
	function test($data, $status = 'success')
	{
		$xml = new DOMDocument();
		
		$root = $xml->appendChild($xml->createElement('root'));
		$root->appendChild($xml->createElement('status', $status));
		
		$response = $root->appendChild($xml->createElement('response'));
		
		arrayToXml($xml, $response, $data);
		
		header("Content-Type: text/plain");
		$xml->formatOutput = true;
		
		die($xml->saveXml());
	}
	
	function arrayToXml($xml, $root, $data)
	{
		foreach($data as $key => $item)
		{
			if(is_array($item))
				arrayToXml($xml, $root->appendChild($xml->createElement($key)), $item);
			else
				$root->appendChild($xml->createElement($key, $item));
		}
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