<?php
namespace App\Models;
use App\Models\Database;
use PDO;

class SystemLog
{
	private $db;

	public function __construct()
	{
		$this->db = new Database();
	}

	public function getById($id)
	{
		$sql = "SELECT * FROM " . SYSTEM_LOG_TABLE . " WHERE id = :id LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getAll()
	{
		$sql = "SELECT n.id, n.title, n.content, n.has_been_read, n.created_at, n.created_by,
			u.first_name, u.middle_name, u.last_name
			FROM " . SYSTEM_LOG_TABLE . " n 
			JOIN user u ON n.created_by = u.id
			ORDER BY created_at";
		$query = $this->db->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAllUnread()
	{
		$sql = "SELECT * FROM " . SYSTEM_LOG_TABLE . " WHERE has_been_read IS FALSE ORDER BY created_at";
		$query = $this->db->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function create($title, $content, $created_by)
	{
		$sql = "INSERT INTO " . SYSTEM_LOG_TABLE . " (title, content, created_by)
				  VALUES (:title, :content, :created_by)";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":title", $title);
		$query->bindParam(":content", $content);
		$query->bindParam(":created_by", $created_by);
		return $query->execute();
	}

	public function update($id, $title, $content, $created_by)
	{
		$sql = "UPDATE " . SYSTEM_LOG_TABLE . "
				  SET title = :title, content = :content, created_by = :created_by
				  WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":title", $title);
		$query->bindParam(":content", $content);
		$query->bindParam(":created_by", $created_by);
		return $query->execute();
	}

	public function delete($id)
	{
		$sql = "DELETE FROM " . SYSTEM_LOG_TABLE . " WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}
}