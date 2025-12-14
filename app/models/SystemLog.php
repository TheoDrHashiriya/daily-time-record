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

	public function getAllTypes()
	{
		$sql = "SELECT id, type_name, is_notification FROM system_log_type";
		$query = $this->db->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getById($id)
	{
		$sql = "SELECT * FROM system_log WHERE id = :id LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}

	public function getAll()
	{
		$sql = "SELECT sl.id, sl.title, sl.content, sl.created_at, sl.created_by,
			u.first_name, u.middle_name, u.last_name
			FROM system_log sl 
			JOIN user u ON sl.created_by = u.id
			ORDER BY created_at";
		$query = $this->db->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function markAllAsRead($user_id){
		$sql = "
				INSERT INTO notification_read (user_id, system_log, read_at)
				SELECT :user_id, sl.id, NOW()
				FROM system_log sl
				JOIN system_log_type slt ON sl.system_log_type = slt.id
				LEFT JOIN notification_read nr
					ON nr.system_log = sl.id
					AND nr.user_id = :user_id
				WHERE slt.is_notification IS TRUE
					AND nr.id IS NULL";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		return $query->execute();;
	}

	public function getAllUnread($user_id)
	{
		$sql = "
				SELECT sl.*
				FROM system_log sl
				JOIN system_log_type slt
					ON sl.system_log_type = slt.id
				LEFT JOIN notification_read nr
					ON nr.system_log = sl.id
					AND nr.user_id = :user_id
				WHERE slt.is_notification IS TRUE
					AND nr.id IS NULL
				ORDER BY sl.created_at DESC";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function create($title, $content, $created_by, $system_log_type)
	{
		$sql = "INSERT INTO system_log (title, content, created_by, system_log_type)
				  VALUES (:title, :content, :created_by, :system_log_type)";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":title", $title);
		$query->bindParam(":content", $content);
		$query->bindParam(":created_by", $created_by);
		$query->bindParam(":system_log_type", $system_log_type);
		return $query->execute();
	}

	public function update($id, $title, $content, $created_by)
	{
		$sql = "UPDATE system_log
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
		$sql = "DELETE FROM system_log WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}
}