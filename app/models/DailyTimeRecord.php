<?php
require_once "Database.php";
class DailyTimeRecord extends Database
{
	private $table = "daily_time_record";

	public function hasTimedInToday($id)
	{
		$sql = "SELECT *
				  FROM {$this->table}
				  WHERE user_id = :id LIMIT 1
				  AND record_date = CURDATE()
				  AND time_in IS NOT NULL;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetchColumn() > 0;
	}

	public function recordTimeIn($id)
	{
		$sql = "INSERT INTO {$this->table} (user_id, record_date, time_in)
				  VALUES (:id, CURDATE(), NOW());";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(param: ":id", $id);
		return $query->execute();
	}

	public function recordTimeOut($id)
	{
		$sql = "UPDATE {$this->table}
				  SET time_out = NOW()
				  WHERE user_id = :id
				  AND record_date = CURDATE()
				  AND time_out IS NULL;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		return $query->execute();
	}

	public function getAll()
	{
		$sql = "SELECT dtr.*, CONCAT(u.first_name, ' ', u.last_name) AS user
				  FROM {$this->table} dtr
				  JOIN user u ON dtr.user_id = u.id
				  ORDER BY dtr.record_date DESC;";
		$query = $this->connect()->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getLast($id){
		$sql = "SELECT *
				  FROM {$this->table} dtr
				  JOIN user u ON dtr.user_id = u.id
				  WHERE dtr.user_id = :id
				  ORDER BY dtr.record_date DESC 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getByUserId($id)
	{
		$sql = "SELECT dtr.*, CONCAT(u.first_name, ' ', u.last_name) AS user
				  FROM {$this->table} dtr
				  JOIN user u ON dtr.user_id = u.id
				  WHERE dtr.user_id = :id
				  ORDER BY dtr.record_date DESC;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getByUserIdAndDate($id, $date)
	{
		$sql = "SELECT dtr.*, CONCAT(u.first_name, ' ', u.last_name) AS user
				  FROM {$this->table} dtr
				  JOIN user u ON dtr.user_id = u.id
				  WHERE dtr.user_id = :id AND dtr.date
				  ORDER BY dtr.record_date DESC;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":date", $date);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
}