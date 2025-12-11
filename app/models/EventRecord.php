<?php
namespace App\Models;
use App\Models\Database;
use PDO;

class EventRecord
{
	private $db;

	public function __construct()
	{
		$this->db = new Database();
	}

	public function hasRecorded($user_id, $event_type)
	{
		$sql = "SELECT *
				  FROM event_record
				  WHERE user_id = :user_id
				  AND DATE(event_time) = CURDATE()
				  AND event_type = :event_type
				  LIMIT 1";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		$query->bindParam(":event_type", $event_type);
		$query->execute();
		return $query->fetch() !== false;
	}

	public function getTotalUnclosed()
	{
		$sql = "
			SELECT COUNT(*) AS total_unclosed
			FROM event_record er
			WHERE er.event_type = 1
				AND NOT EXISTS (
					SELECT 1
					FROM event_record er2
					wHERE er2.user_id = er.user_id
						AND er2.event_type = 2
						AND er2.event_time > er.event_time
				)";
		$query = $this->db->connect()->prepare($sql);
		$query->execute();
		return $query->fetchColumn();
	}

	public function getAll()
	{
		$sql = "
			SELECT er.id,
				er.user_id,
				er.event_time,
				er.event_type,
				ert.type_name,
				u.id AS user_id,
				u.user_number,
				CONCAT (u.first_name, ' ', u.last_name) AS user
			FROM event_record er
				JOIN user u ON er.user_id = u.id
				JOIN event_record_type ert ON er.event_type = ert.id
			ORDER BY er.event_time DESC";
		$query = $this->db->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAllTypes()
	{
		$sql = "SELECT id, type_name FROM event_record_type";
		$query = $this->db->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getByUserId($user_id)
	{
		$sql = "
			SELECT er.*, CONCAT (u.first_name, ' ', u.last_name) AS user
			FROM event_record er
				JOIN user u ON er.user_id = u.id
			WHERE er.user_id = :id
			ORDER BY er.event_date DESC";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $user_id);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getByUserIdAndDate($user_id, $event_date)
	{
		$sql = "SELECT er.*, CONCAT (u.first_name, ' ', u.last_name) AS user
				  FROM event_record er
				  JOIN user u ON er.user_id = u.id
				  WHERE er.user_id = :id AND er.event_date = :event_date
				  ORDER BY er.event_date DESC";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $user_id);
		$query->bindParam(":event_date", $event_date);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getRecordUsername($id)
	{
		$sql = "SELECT u.username
				  FROM event_record er
				  JOIN user u ON er.user_id = u.id
				  WHERE er.id = :id
				  ORDER BY er.event_date DESC";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function record($user_id, $event_type)
	{
		$sql = "INSERT INTO event_record (user_id, event_time, event_type)
				  VALUES (:user_id, NOW(), :event_type)";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		$query->bindParam(":event_type", $event_type);
		return $query->execute();
	}

	public function create($user_id, $event_time, $event_type)
	{
		$sql = "INSERT INTO event_record (user_id, event_time, event_type)
				  VALUES (:user_id, :event_time, :event_type);";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		$query->bindParam(":event_time", $event_time);
		$query->bindParam(":event_type", $event_type);
		return $query->execute();
	}

	public function update($id, $event_time, $event_type, $user_id)
	{
		$sql = "UPDATE event_record
				  SET event_time = :event_time, event_type = :event_type, user_id = :user_id
				  WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":event_time", $event_time);
		$query->bindParam(":event_type", $event_type);
		$query->bindParam(":user_id", $user_id);
		return $query->execute();
	}

	public function delete($id)
	{
		$sql = "DELETE FROM event_record
				  WHERE id = :id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}

	public function deleteAllFromUser($user_id)
	{
		$sql = "DELETE FROM event_record
				  WHERE user_id = :user_id";
		$query = $this->db->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		return $query->execute();
	}
}