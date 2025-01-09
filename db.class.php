<?php 

	class Db
	{
		private $pdo;
		
		public function __construct($connParams)
		{
			$dsn = 'mysql:host='.$connParams['host'].';dbname='.$connParams['name'].';charset=utf8';
			
			$connOpt = array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
							PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
							PDO::ATTR_EMULATE_PREPARES => false,
							PDO::ATTR_EMULATE_PREPARES => 1 );
					  
			$this->pdo = new PDO($dsn, $connParams['user'], $connParams['pass'], $connOpt);
		}
		
		public function query($sql, $options, $isFullResponse = false)
		{
			$smt = $this->pdo->prepare($sql);
				$smt->execute($options);
			
			do
			{
				$data = $smt->fetchAll();
				
				if(!empty($data))
					$response = $data;
			}
			while($smt->nextRowset());
			
			if(empty($response))
				return false;
			
			if($isFullResponse)
				return $response;
			
			return count($response) == 1 ? $response[0] : $response;
		}
		
		public function lastInsId()
		{
			return $this->pdo->lastInsertId();
		}
	}

?>