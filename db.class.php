<?php 

	class Db
	{
		private $pdo;
		
		public function __construct($connParams)
		{
			$dsn = 'mysql:host='.$connParams['host'].';dbname='.$connParams['name'].';charset=utf8';
			
			$connOpt = array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					  PDO::ATTR_EMULATE_PREPARES => false );
					  
			$this->pdo = new PDO($dsn, $connParams['user'], $connParams['pass'], $connOpt);
		}
		
		public function query($sql, $options, $isFullResponse = false)
		{
			$smt = $this->pdo->prepare($sql);
			$smt->execute($options);
			
			$data = $smt->fetchAll();
			
			if(empty($data))
				return false;
			
			if($isFullResponse)
				return $data;
			
			return count($data) == 1 ? $data[0] : $data;
		}
		
		public function lastInsId()
		{
			return $this->pdo->lastInsertId();
		}
	}

?>