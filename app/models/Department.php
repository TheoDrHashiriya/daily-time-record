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

	public function getByUserId($user_id)
	{
		$sql = "SELECT d.*
				  FROM department d
				  JOIN user u ON d.id = u.department
				  WHERE u.id = :user_id LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getByUserNumber($user_number)
	{
		$sql = "SELECT * FROM department WHERE user_number = :user_number LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":user_number", $user_number);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getSchedule(int $id)
	{
		$sql = "SELECT
					standard_am_time_in,
					standard_am_time_out,
					standard_pm_time_in,
					standard_pm_time_out
				  FROM department
				  WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC);
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

	public function abbreviationExistsExceptCurrent($id, $abbreviation)
	{
		$sql = "SELECT COUNT(*) FROM department WHERE abbreviation = :abbreviation AND id != :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":abbreviation", $abbreviation);
		$query->execute();
		return $query->fetchColumn() > 0;
	}

	public function nameExistsExceptCurrent($id, $department_name)
	{
		$sql = "SELECT COUNT(*) FROM department WHERE department_name = :department_name AND id != :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":department_name", $department_name);
		$query->execute();
		return $query->fetchColumn() > 0;
	}

	public function create($department_name, $abbreviation, $standard_am_time_in, $standard_am_time_out, $standard_pm_time_in, $standard_pm_time_out)
	{
		$sql = "INSERT INTO department (department_name, abbreviation, standard_am_time_in, standard_am_time_out, standard_pm_time_in, standard_pm_time_out)
				  VALUES (:department_name, :abbreviation, :standard_am_time_in, :standard_am_time_out, :standard_pm_time_in, :standard_pm_time_out)";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":department_name", $department_name);
		$query->bindParam(":abbreviation", $abbreviation);
		$query->bindParam(":standard_am_time_in", $standard_am_time_in);
		$query->bindParam(":standard_am_time_out", $standard_am_time_out);
		$query->bindParam(":standard_pm_time_in", $standard_pm_time_in);
		$query->bindParam(":standard_pm_time_out", $standard_pm_time_out);
		return $query->execute();
	}

	public function update($id, $department_name, $abbreviation, $standard_am_time_in, $standard_am_time_out, $standard_pm_time_in, $standard_pm_time_out)
	{
		$sql = "UPDATE department
				  SET department_name = :department_name, abbreviation = :abbreviation,
				  standard_am_time_in = :standard_am_time_in, standard_am_time_out = :standard_am_time_out,
				  standard_pm_time_in = :standard_pm_time_in, standard_pm_time_out = :standard_pm_time_out
				  WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":department_name", $department_name);
		$query->bindParam(":abbreviation", $abbreviation);
		$query->bindParam(":standard_am_time_in", $standard_am_time_in);
		$query->bindParam(":standard_am_time_out", $standard_am_time_out);
		$query->bindParam(":standard_pm_time_in", $standard_pm_time_in);
		$query->bindParam(":standard_pm_time_out", $standard_pm_time_out);
		return $query->execute();
	}

	public function delete($id)
	{
		$sql = "DELETE FROM department WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}
}