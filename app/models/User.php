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
			SELECT 1
			FROM user
			WHERE id = :id AND user_number = :user_number
			LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":user_number", $user_number);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetchColumn() > 0;
	}

	public function qrStringExists($qr_string)
	{
		$sql = "SELECT 1
				  FROM qr_code
				  WHERE qr_string = :qr_string
				  LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":qr_string", $qr_string);
		$query->execute();
		return $query->fetchColumn() > 0;
	}

	public function generateQrString($user_id, $qr_string)
	{
		$sql = "INSERT INTO qr_code (user_id, qr_string)
				  VALUES (:user_id, :qr_string)";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		$query->bindParam(":qr_string", $qr_string);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getById($id)
	{
		$sql = "SELECT u.*, qr.qr_string
				  FROM user u
				  LEFT JOIN qr_code qr ON u.id = qr.user_id
				  WHERE u.id = :id LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getByUserNumber($user_number)
	{
		$sql = "SELECT * FROM user WHERE user_number = :user_number LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":user_number", $user_number);
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

	public function getAllByRole($user_role)
	{
		$sql = "SELECT * FROM user WHERE user_role = :user_role";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":user_role", $user_role);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC) ?: null;
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
				uu.last_name AS creator_last_name,
				qr.qr_string
			FROM
				user u
				LEFT JOIN user_role ur ON u.user_role = ur.id
				LEFT JOIN department d ON u.department = d.id
				LEFT JOIN qr_code qr ON u.id = qr.user_id
				LEFT JOIN user uu ON u.created_by = uu.id
			WHERE 1 = 1";

		$params = [];

		if ($search !== '') {
			$sql .= " AND CONCAT_WS(' ', u.first_name, u.middle_name, u.last_name) LIKE :search
							OR u.user_number LIKE :search
							OR ur.role_name LIKE :search
							OR d.department_name LIKE :search
							OR d.abbreviation LIKE :search";
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
			$params[':department_name'] = "%$department_name%";
		}
		if ($abbreviation !== '') {
			$sql .= " AND d.abbreviation = :abbreviation";
			$params[':abbreviation'] = "%$abbreviation%";
		}

		$sql .= " ORDER BY u.id";
		$query = $this->db->connect()->prepare($sql);
		$query->execute($params);
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function userNumberExists($user_number)
	{
		return !empty($this->getByUserNumber($user_number));
	}

	public function usernameExists($username)
	{
		return !empty($this->getByUsername($username));
	}

	public function usernameExistsExceptCurrent($id, $username)
	{
		$sql = "SELECT 1 FROM user WHERE username = :username AND id != :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		$query->bindParam(":username", $username);
		$query->execute();
		return $query->fetchColumn() > 0;
	}

	public function create($first_name, $last_name, $middle_name, $username, $hashed_password, $user_number, $user_role, $department, $created_by)
	{
		$sql = "INSERT INTO user (first_name, last_name, middle_name, username, hashed_password, user_number, user_role, department, created_by)
				  VALUES (:first_name, :last_name, :middle_name, :username, :hashed_password, :user_number, :user_role, :department, :created_by)";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":first_name", $first_name);
		$query->bindParam(":last_name", $last_name);
		$query->bindParam(":middle_name", $middle_name);
		$query->bindParam(":username", $username);
		$query->bindParam(":hashed_password", $hashed_password);
		$query->bindParam(":user_number", $user_number);
		$query->bindParam(":user_role", $user_role);
		$query->bindParam(":department", $department);
		$query->bindParam(":created_by", $created_by);
		$query->execute();
		return $this->db->connect()->lastInsertId();
	}

	public function update($id, $first_name, $middle_name, $last_name, $username, $user_role, $department, $created_at, $created_by = "", $hashed_password = "")
	{
		$sql = "UPDATE user
				  SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name,
				  username = :username, user_role = :user_role, department = :department, created_at = :created_at";

		$params = [
			":id" => $id,
			":first_name" => $first_name,
			":middle_name" => $middle_name,
			":last_name" => $last_name,
			":username" => $username,
			":user_role" => $user_role,
			":department" => $department,
			":created_at" => $created_at,
		];

		if ($created_by !== '') {
			$sql .= ", created_by = :created_by";
			$params[":created_by"] = $created_by;
		}
		if ($hashed_password !== '') {
			$sql .= ", hashed_password = :hashed_password";
			$params[":hashed_password"] = $hashed_password;
		}

		$sql .= " WHERE id = :id";

		$query = $this->db->connect()->prepare($sql);
		return $query->execute($params);
	}

	public function delete($id)
	{
		$sql = "DELETE FROM user WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}
}