<?php

// Check if security.php is already loaded, if not include it
if (!defined('SECURITY_CONFIG_LOADED')) {
    $security_path = dirname(__DIR__) . '/config/security.php';
    if (file_exists($security_path)) {
        require_once $security_path;
    }
}



class SqlIt{

	public $Sql;
	public $Response;
	public $NumResults;
	public $LastID;
	public $Result;
	
	private $Host;
	private $DBname;
	private $User;
	private $Pass;



	public function __construct($Sql, $type, $vars){

		if($vars == ""){
			$vars = array();
		}

		$STH = null; // Initialize to prevent undefined variable
		
		try{
			$DB = $this->db_connect();
			$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$STH = $DB->prepare($Sql);
			$doit = $STH->execute($vars);
			
			$this->LastID = $DB->lastInsertId();
			$this->Result = $doit;
			
			// Only proceed with type-specific operations if query was successful
			if($STH && $doit) {
				switch($type){
					case 'select':
						$this->select($STH);
						break;
				}
			}
		}
		catch(PDOException $e){
			// Don't display errors to users in production
			if(defined('DEBUG_MODE') && DEBUG_MODE) {
				echo $e->getMessage()."<br>".$Sql;
			}
			
			// Log errors securely outside web root
			$log_message = date('Y-m-d H:i:s')." - ERROR: ". $e->getMessage()." - SQL: ".$Sql."\n";
			$log_file = __DIR__ . '/../logs/sql_errors.log';
			
			// Ensure log directory exists and is writable
			if(!is_dir(dirname($log_file))) {
				mkdir(dirname($log_file), 0755, true);
			}
			
			file_put_contents($log_file, $log_message, FILE_APPEND | LOCK_EX);
			
			// Set safe defaults for failed queries
			$this->NumResults = 0;
			$this->Response = array();
			$this->Result = false;
		}
	}



	public function select($query){
		if($query === null) {
			$this->NumResults = 0;
			$this->Response = array();
			return;
		}
		
		$rows = $query->rowCount();
		$this->NumResults = $rows;
		
		$this->Response = array();
		while($row = $query->fetchObject()){
			$this->Response[] = $row;
		}
	}



	//create a separate function for connecting to DB. Private to only this class.

	private function db_connect(){
		// Use configuration constants instead of hardcoded values
		$this->User = DB_USER;
		$this->Pass = DB_PASS;
		$this->Host = DB_HOST;
		$this->DBname = DB_NAME;

    $DBH = new PDO("mysql:host=".$this->Host.";dbname=".$this->DBname, $this->User, $this->Pass);

		return $DBH;

		}

	}



?>

