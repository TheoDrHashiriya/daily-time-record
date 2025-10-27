<?php
require_once "Database.php";
class User extends Database
{
	private $id = "";
	private $first_name = "";
	private $last_name = "";
	private $middle_name = "";
	private $username = "";
	private $hashed_password = "";
	private $role = "";

	protected function getById($id)
	{
		$sql = "SELECT * FROM user WHERE id = :id LIMIT 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getByUsername($username)
	{
		$sql = "SELECT * FROM user WHERE username = :username LIMIT 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":username", $username);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getAll()
	{
		$sql = "SELECT * FROM user ORDER BY id;";
		$query = $this->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function exists($username)
	{
		return !empty($this->getByUsername($username));
	}

	public function create($first_name, $last_name, $middle_name, $username, $hashed_password, $role)
	{
		$sql = "INSERT INTO user (first_name, last_name, middle_name, username, password, role)
				  VALUES (:first_name, :last_name, :middle_name, :username, :password, :role);";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":first_name", $first_name);
		$query->bindParam(":last_name", $last_name);
		$query->bindParam(":middle_name", $middle_name);
		$query->bindParam(":username", $username);
		$query->bindParam(":password", $hashed_password);
		$query->bindParam(":role", $role);
		return $query->execute();
	}

	public function update($id, $first_name, $last_name, $middle_name, $username, $hashed_password, $role)
	{
		$sql = "UPDATE user
				  SET first_name = :first_name, last_name = :last_name, middle_name = :middle_name,
				  username = :username, password = :password, role = :role
				  WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":first_name", $first_name);
		$query->bindParam(":last_name", $last_name);
		$query->bindParam(":middle_name", $middle_name);
		$query->bindParam(":username", $username);
		$query->bindParam(":password", $hashed_password);
		$query->bindParam(":role", $role);
		return $query->execute();
	}

	public function updateRealName($id, $first_name, $last_name, $middle_name = NULL)
	{
		$sql = "UPDATE user
				  SET first_name = :first_name, last_name = :last_name, middle_name = :middle_name
				  WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":first_name", $first_name);
		$query->bindParam(":last_name", $last_name);
		$query->bindParam(":middle_name", $middle_name);
		return $query->execute();
	}

	public function updateUsername($id, $username)
	{
		$sql = "UPDATE user
				  SET username = :username WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":username", $username);
		return $query->execute();
	}

	public function updatePassword($id, $hashed_password)
	{
		$sql = "UPDATE user
				  SET password = :password WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":password", $hashed_password);
		return $query->execute();
	}

	public function delete($id)
	{
		$sql = "DELETE FROM user WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}
}