<?php
require_once "Database.php";
class EventRecord extends Database
{
	private $table = "event_record";
	private $alias = "er";

	public function hasTimedInToday($user_id)
	{
		$sql = "SELECT *
				  FROM {$this->table}
				  WHERE user_id = :user_id
				  AND event_date = CURDATE()
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
				  FROM {$this->table}
				  WHERE user_id = :user_id
				  AND event_date = CURDATE()
				  AND event_type = 2
				  LIMIT 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		$query->execute();
		return $query->fetch() !== false;
	}

	public function getAll()
	{
		$sql = "SELECT {$this->alias}.*, CONCAT (u.first_name, ' ', u.last_name) AS user
				  FROM {$this->table} {$this->alias}
				  JOIN user u ON {$this->alias}.user_id = u.id
				  ORDER BY {$this->alias}.event_date DESC;";
		$query = $this->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getLast($id)
	{
		$sql = "SELECT *
				  FROM {$this->table} {$this->alias}
				  JOIN user u ON {$this->alias}.user_id = u.id
				  WHERE {$this->alias}.user_id = :id
				  ORDER BY {$this->alias}.event_date DESC 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getByUserId($user_id)
	{
		$sql = "SELECT {$this->alias}.*, CONCAT (u.first_name, ' ', u.last_name) AS user
				  FROM {$this->table} {$this->alias}
				  JOIN user u ON {$this->alias}.user_id = u.id
				  WHERE {$this->alias}.user_id = :id
				  ORDER BY {$this->alias}.event_date DESC;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $user_id);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getByUserIdAndDate($user_id, $event_date)
	{
		$sql = "SELECT {$this->alias}.*, CONCAT (u.first_name, ' ', u.last_name) AS user
				  FROM {$this->table} {$this->alias}
				  JOIN user u ON {$this->alias}.user_id = u.id
				  WHERE {$this->alias}.user_id = :id AND {$this->alias}.event_date = :event_date
				  ORDER BY {$this->alias}.event_date DESC;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $user_id);
		$query->bindParam(":event_date", $event_date);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getRecordUsername($id)
	{
		$sql = "SELECT u.username
				  FROM {$this->table} {$this->alias}
				  JOIN user u ON {$this->alias}.user_id = u.id
				  WHERE {$this->alias}.id = :id
				  ORDER BY {$this->alias}.event_date DESC;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function recordTimeIn($user_id)
	{
		$sql = "INSERT INTO {$this->table} (user_id, event_date, event_time, event_type)
				  VALUES (:user_id, CURDATE(), NOW(), 1);";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		return $query->execute();
	}

	public function recordTimeOut($user_id)
	{
		$sql = "INSERT INTO {$this->table} (user_id, event_date, event_time, event_type)
				  VALUES (:user_id, CURDATE(), NOW(), 2);";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		return $query->execute();
	}

	public function create($user_id, $event_date, $event_time, $event_type)
	{
		$sql = "INSERT INTO {$this->table} (user_id, event_date, event_time, event_type)
				  VALUES (:user_id, :event_date, :event_time, :event_type);";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		$query->bindParam(":event_date", $event_date);
		$query->bindParam(":event_time", $event_time);
		$query->bindParam(":event_type", $event_type);
		return $query->execute();
	}
	// Need to update update()
	public function update($id, $user_id, $event_date, $time_in, $time_out)
	{
		$sql = "UPDATE {$this->table}
				  SET user_id = :user_id, event_date = :event_date, time_in = :time_in, time_out = :time_out
				  WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":user_id", $user_id);
		$query->bindParam(":event_date", $event_date);
		$query->bindParam(":time_in", $time_in);
		$query->bindParam(":time_out", $time_out);
		return $query->execute();
	}

	public function delete($id)
	{
		$sql = "DELETE FROM {$this->table}
				  WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}

	public function deleteAllFromUser($user_id)
	{
		$sql = "DELETE FROM {$this->table}
				  WHERE user_id = :user_id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		return $query->execute();
	}
}