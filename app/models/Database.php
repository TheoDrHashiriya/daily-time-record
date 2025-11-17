<?php
class Database
{
	private $host = DB_HOST;
	private $user = DB_USER;
	private $password = DB_PASS;
	private $dbName = DB_NAME;
	private $dbPort = DB_PORT;
	protected $conn;
	public function connect()
	{
		$this->conn = new PDO(
			"mysql:host=$this->host;dbname=$this->dbName",
			$this->user,
			$this->password
		);
		return $this->conn;
	}
}