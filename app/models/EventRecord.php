<?php
require_once "Database.php";
class EventRecord extends Database
{
	public function hasTimedInToday($user_id)
	{
		$sql = "SELECT *
				  FROM event_record
				  WHERE user_id = :user_id
				  AND DATE(event_time) = CURDATE()
				  AND event_type = 1
				  LIMIT 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		$query->execute();
		return $query->fetch() !== false;
	}

	public function hasTimedOutToday($user_id)
	{
		$sql = "SELECT *
				  FROM event_record
				  WHERE user_id = :user_id
				  AND DATE(event_time) = CURDATE()
				  AND event_type = 2
				  LIMIT 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		$query->execute();
		return $query->fetch() !== false;
	}

	public function getAll()
	{
		$sql = "
			SELECT er.id,
				er.event_time,
				ert.type_name,
				u.id AS user_id,
				CONCAT (u.first_name, ' ', u.last_name) AS user
			FROM event_record er
				JOIN user u ON er.user_id = u.id
				JOIN event_record_type ert ON er.event_type = ert.id
			ORDER BY er.event_time DESC;
			";
		$query = $this->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getLast($id)
	{
		$sql = "
			SELECT *
			FROM event_record er
				JOIN user u ON er.user_id = u.id
			WHERE er.user_id = :id
			ORDER BY er.event_date DESC 1;
			";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getByUserId($user_id)
	{
		$sql = "
			SELECT er.*, CONCAT (u.first_name, ' ', u.last_name) AS user
			FROM event_record er
				JOIN user u ON er.user_id = u.id
			WHERE er.user_id = :id
			ORDER BY er.event_date DESC;
			";
		$query = $this->connect()->prepare($sql);
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
				  ORDER BY er.event_date DESC;";
		$query = $this->connect()->prepare($sql);
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
				  ORDER BY er.event_date DESC;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function recordTimeIn($user_id)
	{
		$sql = "INSERT INTO event_record (user_id, event_time, event_type)
				  VALUES (:user_id, NOW(), 1);";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		return $query->execute();
	}

	public function recordTimeOut($user_id)
	{
		$sql = "INSERT INTO event_record (user_id, event_time, event_type)
				  VALUES (:user_id, NOW(), 2);";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		return $query->execute();
	}

	public function create($user_id, $event_time, $event_type)
	{
		$sql = "INSERT INTO event_record (user_id, event_time, event_type)
				  VALUES (:user_id, :event_time, :event_type);";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		$query->bindParam(":event_time", $event_time);
		$query->bindParam(":event_type", $event_type);
		return $query->execute();
	}
	// Need to update update()
	public function update($id, $user_id, $time_in, $time_out)
	{
		$sql = "UPDATE event_record
				  SET user_id = :user_id, time_in = :time_in, time_out = :time_out
				  WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":user_id", $user_id);
		$query->bindParam(":time_in", $time_in);
		$query->bindParam(":time_out", $time_out);
		return $query->execute();
	}

	public function delete($id)
	{
		$sql = "DELETE FROM event_record
				  WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}

	public function deleteAllFromUser($user_id)
	{
		$sql = "DELETE FROM event_record
				  WHERE user_id = :user_id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		return $query->execute();
	}
}