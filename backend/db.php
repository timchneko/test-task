<?php
	
	class Connection {
		private static $_instance;
		private $conn;

   		private function __construct() {
			$dsn = "sqlite:./db.sq3";
			$opt = array(
			    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			);
			$user = $pass = "";
			$this->conn = new PDO($dsn, $user, $pass, $opt);
   		}

   		public static function getInstance() {
			if(!static::$_instance) {
				static::$_instance = new static();
			}
			return static::$_instance;
		}

   		public function getConnection() {
   			return $this->conn;
		}

		private function __clone() { }
	}
?>