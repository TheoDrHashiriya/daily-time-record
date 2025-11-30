<?php
namespace App\Models;
use App\Models\Database;
use PDO;

class UserRole
{
	private $db;

	public function __construct()
	{
		$this->db = new Database();
	}

	public function getAll()
	{
		$sql = "SELECT * FROM user_role ORDER BY id";
		$query = $this->db->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function create($first_name, $last_name, $middle_name, $username, $hashed_password, $user_role)
	{
		$sql = "INSERT INTO user_role (first_name, last_name, middle_name, username, hashed_password, user_role)
				  VALUES (:first_name, :last_name, :middle_name, :username, :hashed_password, :user_role)";
		$query = $this->db->connect()->prepare($sql);
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
		$sql = "UPDATE user_role
				  SET first_name = :first_name, last_name = :last_name, middle_name = :middle_name,
				  username = :username, hashed_password = :hashed_password, user_role = :user_role
				  WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":first_name", $first_name);
		$query->bindParam(":last_name", $last_name);
		$query->bindParam(":middle_name", $middle_name);
		$query->bindParam(":username", $username);
		$query->bindParam(":hashed_password", $hashed_password);
		$query->bindParam(":user_role", $user_role);
		return $query->execute();
	}

	public function delete($id)
	{
		$sql = "DELETE FROM user_role WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}
}