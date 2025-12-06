<?php
namespace App\Models;
use App\Models\Database;
use PDO;

class Department
{
	private $db;

	public function __construct()
	{
		$this->db = new Database();
	}

	public function getById($id)
	{
		$sql = "SELECT * FROM department WHERE id = :id LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getAll()
	{
		$sql = "SELECT * FROM department ORDER BY id";
		$query = $this->db->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getByName($department_name)
	{
		$sql = "SELECT * FROM department WHERE department_name = :department_name LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":department_name", $department_name);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getByAbbreviation($abbreviation)
	{
		$sql = "SELECT * FROM department WHERE abbreviation = :abbreviation LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":abbreviation", $abbreviation);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function nameExists($department_name)
	{
		return !empty($this->getByName($department_name));
	}

	public function abbreviationExists($abbreviation)
	{
		return !empty($this->getByAbbreviation($abbreviation));
	}

	public function create($department_name)
	{
		$sql = "INSERT INTO department (department_name)
				  VALUES (:department_name)";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":department_name", $department_name);
		return $query->execute();
	}

	public function update($id, $department_name)
	{
		$sql = "UPDATE department
				  SET department_name = :department_name
				  WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":department_name", $department_name);
		return $query->execute();
	}

	public function isInUse($id)
	{
		$sql = "
		SELECT EXISTS (
			SELECT 1
			FROM user
			WHERE user.department = :id
		) AS in_use
		";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function delete($id)
	{
		$sql = "DELETE FROM department WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}
}