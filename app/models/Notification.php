<?php
namespace App\Models;
use App\Models\Database;
use PDO;

class Notification
{
	private $db;

	public function __construct()
	{
		$this->db = new Database();
	}

	public function getById($id)
	{
		$sql = "SELECT * FROM notification WHERE id = :id LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getAll()
	{
		$sql = "SELECT * FROM notification ORDER BY created_at";
		$query = $this->db->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAllUnread()
	{
		$sql = "SELECT * FROM notification WHERE has_been_read IS FALSE ORDER BY created_at";
		$query = $this->db->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function create($title, $content, $created_by)
	{
		$sql = "INSERT INTO notification (title, content, created_by)
				  VALUES (:title, :content, :created_by)";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":title", $title);
		$query->bindParam(":content", $content);
		$query->bindParam(":created_by", $created_by);
		return $query->execute();
	}

	public function delete($id)
	{
		$sql = "DELETE FROM notification WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}
}