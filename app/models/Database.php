<?php
class Database
{
	private $host = "localhost";
	private $username = "root";
	private $password = "";
	private $dbName = "theonary";
	protected $conn;
	public function connect()
	{
		$this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbName", $this->username, $this->password);

		return $this->conn;
	}
}