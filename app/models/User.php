<?php
require_once "Database.php";
class User extends Database
{
	private $table = "user";
	protected function getById($id)
	{
		$sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getByUsername($username)
	{
		$sql = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":username", $username);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getAll()
	{
		$sql = "SELECT * FROM {$this->table} ORDER BY id;";
		$query = $this->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function exists($username)
	{
		return !empty($this->getByUsername($username));
	}

	public function create($first_name, $last_name, $middle_name, $username, $hashed_password, $user_role)
	{
		$sql = "INSERT INTO {$this->table} (first_name, last_name, middle_name, username, hashed_password, user_role)
				  VALUES (:first_name, :last_name, :middle_name, :username, :hashed_password, :user_role);";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":first_name", $first_name);
		$query->bindParam(":last_name", $last_name);
		$query->bindParam(":middle_name", $middle_name);
		$query->bindParam(":username", $username);
		$query->bindParam(":hashed_password", $hashed_password);
		$query->bindParam(":user_role", $user_role);
		return $query->execute();
	}

	public function update($id, $first_name, $last_name, $middle_name, $username, $hashed_password, $user_role)
	{
		$sql = "UPDATE {$this->table}
				  SET first_name = :first_name, last_name = :last_name, middle_name = :middle_name,
				  username = :username, hashed_password = :hashed_password, user_role = :user_role
				  WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":first_name", $first_name);
		$query->bindParam(":last_name", $last_name);
		$query->bindParam(":middle_name", $middle_name);
		$query->bindParam(":username", $username);
		$query->bindParam(":hashed_password", $hashed_password);
		$query->bindParam(":user_role", $user_role);
		return $query->execute();
	}

	public function updateRealName($id, $first_name, $last_name, $middle_name = NULL)
	{
		$sql = "UPDATE {$this->table}
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
		$sql = "UPDATE {$this->table}
				  SET username = :username WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":username", $username);
		return $query->execute();
	}

	public function updatePassword($id, $hashed_password)
	{
		$sql = "UPDATE {$this->table}
				  SET hashed_password = :hashed_password WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":hashed_password", $hashed_password);
		return $query->execute();
	}

	public function delete($id)
	{
		$sql = "DELETE FROM {$this->table} WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}
}