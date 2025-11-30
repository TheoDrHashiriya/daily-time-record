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
				u.*,
				ur.role_name,
				d.department_name,
				uu.first_name AS creator_first_name,
				uu.middle_name AS creator_middle_name,
				uu.last_name AS creator_last_name
			FROM
				user u
				LEFT JOIN user_role ur ON u.user_role = ur.id
				LEFT JOIN department d ON u.department = d.id
				LEFT JOIN user uu ON u.created_by = uu.id
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

	public function update($id, $first_name, $last_name, $middle_name, $username, $user_role, $department, $created_at)
	{
		$sql = "UPDATE user
				  SET first_name = :first_name, last_name = :last_name, middle_name = :middle_name,
				  username = :username, user_role = :user_role, department = :department, created_at = :created_at
				  WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":first_name", $first_name);
		$query->bindParam(":last_name", $last_name);
		$query->bindParam(":middle_name", $middle_name);
		$query->bindParam(":username", $username);
		$query->bindParam(":user_role", $user_role);
		$query->bindParam(":department", $department);
		$query->bindParam(":created_at", $created_at);
		return $query->execute();
	}

	public function updateCreator($id, $created_by)
	{
		$sql = "UPDATE user SET created_by = :created_by WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":created_by", $created_by);
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