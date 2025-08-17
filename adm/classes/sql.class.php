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

			$this->LastID = $DB->lastInsertId();

			$this->Result = $doit;

			}

		catch(PDOException $e){

			echo $e->getMessage()."<br>".$Sql;

			$log_message = date('d/M/y g:i a')." \n ". $e->getMessage()." \n".$Sql."\n\n";

			file_put_contents('pdoerrors.txt',$log_message, FILE_APPEND);

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

		$this->User = 'dougierocks_connect';

		$this->Pass = 'BBS;C0JR~Aye';

    $DBH = new PDO("mysql:host=199.250.218.203;dbname=dougierocks_db", $this->User, $this->Pass);

		return $DBH;

		}

	}



?>

