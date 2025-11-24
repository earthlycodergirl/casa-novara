<?php 

class SqlIt{
	public $Sql;
	public $Response;
	private $Host;
	private $DBname;
	private $User;
	private $Pass;
	public $NumResults;
	
	public function __construct($Sql, $type, $vars){
		if($vars == ""){
			$vars = array();
			}
		try{
		$DB = $this->db_connect();
		$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$STH = $DB->prepare($Sql);
			$doit = $STH->execute($vars);
			$this->Result = $doit;
			}
		catch(PDOException $e){
			echo $e->getMessage()."<br>".$Sql;
			file_put_contents('pdoerrors.txt', $e->getMessage(), FILE_APPEND);
			}
		//find function to run
		switch($type){
			case 'select':
				$this->select($STH);
				break;
			}
		}
		
	public function select($query){
			$rows = $query->rowCount();
			$this->NumResults = $rows;
			while($row = $query->fetchObject()){
				$this->Response[] = $row;
			}
		}
		
	//create a separate function for connecting to DB. Private to only this class.
	private function db_connect(){
		/*$this->User = 'root';
		$this->Pass = '';
        $DBH = new PDO("mysql:host=localhost;dbname=budd", $this->User, $this->Pass);*/
        
        $this->User = 'buddsellsrealest_gfadmin';
		$this->Pass = 'P4(xN0*8g(&7';
        $DBH = new PDO("mysql:host=173.205.124.54;dbname=buddsellsrealest_site", $this->User, $this->Pass);
        
		
		
		return $DBH;
		}
	}
	
?>