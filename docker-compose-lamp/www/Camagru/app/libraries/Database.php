<?php


// model/pdo class : create, update, delete data from database + suply the data to controller.
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $pdo;
    private $stmt;
    private $error;

    public function __construct(){
      
		$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';port=3306';
		$options = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		);

		try{
			$this->pdo = new PDO($dsn, $this->user, $this->pass);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		}
        catch (PDOException $e) {
			$this->error = $e->getMessage();
			echo 'PDO EXCEPTION : ' . $this->error;
		}
	}


	// 1-Prepare | 2-bindvalues | 3-execute
	public function query($sql){
		$this->stmt = $this->pdo->prepare($sql);
	}

	public function bind($param, $value, $type = NULL){
		if (is_null($type)){//if type param is not passed
			switch(1){			//identify the type of the $value
				case is_int($value) :
					$type = PDO::PARAM_INT;
					break ;
				case is_bool($value) :
					$type = PDO::PARAM_BOOL;
					break ;
				case is_null($value) :
					$type = PDO::PARAM_NULL;
					break ;
				default :
					$type = PDO::PARAM_STR;
			}
		}// bind the values
		$this->stmt->bindValue($param, $value, $type);
	}

	public function execute(){
		return ($this->stmt->execute());
	}

	// executes and gets all query result as an object, use PDO::FETCH_ASSOC for asscociative array
	public function getAllResult(){
		$this->stmt->execute();
		return ($this->stmt->fetchAll());
	}

	public function getSingleResult(){
		$this->stmt->execute();
		return ($this->stmt->fetch());
	}

	public function rowCount(){
		return ($this->stmt->rowCount());
	}
}