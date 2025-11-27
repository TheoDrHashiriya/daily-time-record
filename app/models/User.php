<?php
namespace App\Models;
use App\Models\Database;
use PDO;

class User
{
	private $db;

	public function __construct()
	{
		$this->db = new Database();
	}

	public function getById($id)
	{
		$sql = "SELECT * FROM user WHERE id = :id LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getByUsername($username)
	{
		$sql = "SELECT * FROM user WHERE username = :username LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":username", $username);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getAll()
	{
		$sql = "
			SELECT
				u.*, ur.role_name
			FROM
				user u
				JOIN user_role ur ON u.user_role = ur.id
			ORDER BY u.id";
		$query = $this->db->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function exists($username)
	{
		return !empty($this->getByUsername($username));
	}

	public function create($first_name, $last_name, $middle_name, $username, $hashed_password, $user_role)
	{
		$sql = "INSERT INTO user (first_name, last_name, middle_name, username, hashed_password, user_role)
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
		$sql = "UPDATE user
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
		$sql = "DELETE FROM user WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}
}