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

	public function delete($id)
	{
		$sql = "DELETE FROM department WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}
}