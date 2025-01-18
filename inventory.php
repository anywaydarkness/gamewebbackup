<?php

	include_once('config.php');
	
	$data = $_POST;
	
	if(!isValidInputData('action', $data))
		printError('invalid action');

	$gridSize = 13;

	switch($data['action'])
	{
		case 'get':
		
			if(!isValidInputData('userId', $data))
				printError('invalid user id');
		
			$sql = "SELECT items_user.id, items.title, items.icon, items.weight, count, cx, cy 
					FROM items_user 
					JOIN items ON items.id = items_user.id_item
					WHERE id_user = :userId";
			
			$response = $db->query($sql, array('userId' => $data['userId']), true);
			
			if(!$response)
				printError('empty');
			else
				printResponse($response);
		
		break;
		
		case 'getSize':
		
			echo($gridSize); 
			die();
		
		break;
		
		case 'add':
		
			$cords = $db->query('SELECT cx,cy FROM items_user WHERE id_user = :userId', array( 'userId' => $data['userId'] ), true);
			
			$grid = createGrid($gridSize);
			
			if($cords !== false)
				$grid = fillGrid($grid, $cords);
			
			$cords = findEmptyCell($grid);
			
			if($cords['x'] == -1)
				printError('is not free space');
			
			$sql = "START TRANSACTION;
					INSERT INTO items_user ( id_item, id_user, count, cx, cy ) VALUES ( :item, :user, :count, :cx, :cy );
					SELECT LAST_INSERT_ID() as id, title, icon_id AS icon, weight FROM items WHERE id = (SELECT id_item AS id FROM items_user WHERE id = LAST_INSERT_ID());
					COMMIT;";
			
			$response = $db->query($sql, array( 'item' => $data['itemId'], 'user' => $data['userId'], 
									'count' => isset($data['count']) ? $data['count'] : 1, 
									'cx' => $cords['x'], 'cy' => $cords['y'] ));
			
			printResponse($response);
			
		break;
	}
	
	function createGrid($size)
	{
		for($i = 0; $i < $size; $i++)
			for($j = 0; $j < $size; $j++)
				$grid[$i][$j] = false;
			
		return $grid;
	}
	
	function fillGrid($grid, $data)
	{
		$count = count($data);
		
		for($i = 0; $i < $count; $i++)
			$grid[$data[$i]['cy']][$data[$i]['cx']] = true;
		
		return $grid;
	}
	
	function displayGrid($grid)
	{
		$size = count($grid);
		
		for($y = 0; $y < $size; $y++)
		{
			for($x = 0; $x < $size; $x++)
				echo $grid[$y][$x] ? '1' : '0';
			
			echo '<br>';
		}
	}
	
	function findEmptyCell($grid)
	{
		$size = count($grid);
		
		for($y = 0; $y < $size; $y++)
		{
			for($x = 0; $x < $size; $x++)
			{
				if(!$grid[$y][$x])
					return array('x' => $x, 'y' => $y);
			}
		}
		
		return array('x' => -1, 'y' => -1);
	}

?>