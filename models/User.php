<?php
require_once "Database.php";
class User extends Database
{
	private $id = "";
	private $first_name = "";
	private $last_name = "";
	private $middle_name = "";
	private $username = "";
	private $password = "";
	private $role = "";
	protected function getById($id)
	{
		$sql = "SELECT * FROM user WHERE id = :id LIMIT 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}
	protected function createUser($first_name, $last_name, $middle_name, $username, $password)
	{
		$sql = "INSERT INTO user (first_name, last_name, middle_name, username, password)
				  VALUES (:first_name, :last_name, :middle_name, :username, :password);";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":first_name", $first_name);
		$query->bindParam(":last_name", $last_name);
		$query->bindParam(":middle_name", $middle_name);
		$query->bindParam(":username", $username);
		$query->bindParam(":password", $password);
		return $query->execute();
	}
	public function userExists($username)
	{
		$sql = "SELECT * FROM user WHERE username = :username LIMIT 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":username", $username);
		$query->execute();
		return $query->fetchColumn() > 0;
	}
	public function updateUser($id, $first_name, $last_name, $middle_name, $username, $password)
	{
		$sql = "UPDATE user
				  SET first_name = :first_name, last_name = :last_name, middle_name = :middle_name,
				  username = :username, password = :password WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":first_name", $first_name);
		$query->bindParam(":last_name", $last_name);
		$query->bindParam(":middle_name", $middle_name);
		$query->bindParam(":username", $username);
		$query->bindParam(":password", $password);
		$query->bindParam(":id", $id);
		return $query->execute();
	}
	public function deleteUser($id)
	{
		$sql = "DELETE FROM user
				  WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}
}