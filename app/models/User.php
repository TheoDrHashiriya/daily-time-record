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

	public function userNumberMatchesId($user_number, $id)
	{
		$sql = "
			SELECT COUNT(*)
			FROM " . USER_TABLE . "
			WHERE id = :id AND user_number = :user_number
			LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":user_number", $user_number);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetchColumn() > 0;
	}

	public function getById($id)
	{
		$sql = "SELECT * FROM " . USER_TABLE . " WHERE id = :id LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getByUserNumber($user_number)
	{
		$sql = "SELECT * FROM " . USER_TABLE . " WHERE user_number = :user_number LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":user_number", $user_number);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}
	public function getByQrCode($qr_code)
	{
		$sql = "SELECT * FROM " . USER_TABLE . " WHERE qr_code = :qr_code LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":qr_code", $qr_code);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getByUsername($username)
	{
		$sql = "SELECT * FROM " . USER_TABLE . " WHERE username = :username LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":username", $username);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getAll(
		$search = '',
		$fullname = '',
		$user_number = '',
		$role = '',
		$department_name = '',
		$abbreviation = ''
	) {
		$sql = "
			SELECT
				u.*,
				ur.role_name,
				d.department_name,
				uu.first_name AS creator_first_name,
				uu.middle_name AS creator_middle_name,
				uu.last_name AS creator_last_name
			FROM
				" . USER_TABLE . " u
				LEFT JOIN user_role ur ON u.user_role = ur.id
				LEFT JOIN department d ON u.department = d.id
				LEFT JOIN " . USER_TABLE . " uu ON u.created_by = uu.id
			WHERE 1 = 1";

		$params = [];

		if ($search !== '') {
			$sql .= " AND CONCAT_WS(' ', u.first_name, u.middle_name, u.last_name) LIKE :search
							OR u.user_number LIKE :search
							OR ur.role_name LIKE :search
							OR d.department_name LIKE :search
							OR d.abbreviation LIKE :search
			";
			$params[':search'] = "%$search%";
		}
		if ($fullname !== '') {
			$sql .= " AND CONCAT_WS(' ', u.first_name, u.middle_name, u.last_name) LIKE :fullname";
			$params[':fullname'] = "%$fullname%";
		}
		if ($user_number !== '') {
			$sql .= " AND u.user_number LIKE :user_number";
			$params[':user_number'] = "%$user_number%";
		}
		if ($department_name !== '') {
			$sql .= " AND d.department_name = :department_name";
			$params[':department_name'] = $department_name;
		}
		if ($abbreviation !== '') {
			$sql .= " AND d.abbreviation = :abbreviation";
			$params[':abbreviation'] = $abbreviation;
		}

		$sql .= " ORDER BY u.id";
		$query = $this->db->connect()->prepare($sql);
		$query->execute($params);
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function exists($username)
	{
		return !empty($this->getByUsername($username));
	}

	public function create($first_name, $last_name, $middle_name, $username, $hashed_password, $qr_code, $user_role, $department, $created_by)
	{
		$sql = "INSERT INTO " . USER_TABLE . " (first_name, last_name, middle_name, username, hashed_password, qr_code, user_role, department, created_by)
				  VALUES (:first_name, :last_name, :middle_name, :username, :hashed_password, :qr_code, :user_role, :department, :created_by)";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":first_name", $first_name);
		$query->bindParam(":last_name", $last_name);
		$query->bindParam(":middle_name", $middle_name);
		$query->bindParam(":username", $username);
		$query->bindParam(":hashed_password", $hashed_password);
		$query->bindParam(":qr_code", $qr_code);
		$query->bindParam(":user_role", $user_role);
		$query->bindParam(":department", $department);
		$query->bindParam(":created_by", $created_by);
		return $query->execute();
	}

	public function update($id, $first_name, $middle_name, $last_name, $username, $user_role, $department, $created_at, $created_by)
	{
		$sql = "UPDATE " . USER_TABLE . "
				  SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name,
				  username = :username, user_role = :user_role, department = :department, created_at = :created_at, created_by = :created_by
				  WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":first_name", $first_name);
		$query->bindParam(":middle_name", $middle_name);
		$query->bindParam(":last_name", $last_name);
		$query->bindParam(":username", $username);
		$query->bindParam(":user_role", $user_role);
		$query->bindParam(":department", $department);
		$query->bindParam(":created_at", $created_at);
		$query->bindParam(":created_by", $created_by);
		return $query->execute();
	}

	public function updateCreator($id, $created_by)
	{
		$sql = "UPDATE " . USER_TABLE . " SET created_by = :created_by WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":created_by", $created_by);
		return $query->execute();
	}

	public function delete($id)
	{
		$sql = "DELETE FROM " . USER_TABLE . " WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}
}