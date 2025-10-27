<?php
require_once "Database.php";
class DailyTimeRecord extends Database
{
	private $table = "daily_time_record";

	public function hasTimedInToday($id)
	{
		$sql = "SELECT *
				  FROM {$this->table}
				  WHERE user_id = :id
				  AND record_date = CURDATE()
				  AND time_in IS NOT NULL
				  LIMIT 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch() !== false;
	}

	public function hasTimedOutToday($id)
	{
		$sql = "SELECT *
				  FROM {$this->table}
				  WHERE user_id = :id
				  AND record_date = CURDATE()
				  AND time_out IS NOT NULL
				  LIMIT 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch() !== false;
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

	public function getLast($id)
	{
		$sql = "SELECT *
				  FROM {$this->table} dtr
				  JOIN user u ON dtr.user_id = u.id
				  WHERE dtr.user_id = :id
				  ORDER BY dtr.record_date DESC 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function getByUserId($user_id)
	{
		$sql = "SELECT dtr.*, CONCAT(u.first_name, ' ', u.last_name) AS user
				  FROM {$this->table} dtr
				  JOIN user u ON dtr.user_id = u.id
				  WHERE dtr.user_id = :id
				  ORDER BY dtr.record_date DESC;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $user_id);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getByUserIdAndDate($user_id, $date)
	{
		$sql = "SELECT dtr.*, CONCAT(u.first_name, ' ', u.last_name) AS user
				  FROM {$this->table} dtr
				  JOIN user u ON dtr.user_id = u.id
				  WHERE dtr.user_id = :id AND dtr.date = :date
				  ORDER BY dtr.record_date DESC;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $user_id);
		$query->bindParam(":date", $date);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getRecordUsername($id)
	{
		$sql = "SELECT u.username
				  FROM {$this->table} dtr
				  JOIN user u ON dtr.user_id = u.id
				  WHERE dtr.id = :id
				  ORDER BY dtr.record_date DESC;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function hasUnclosedRecord($id)
	{

	}

	public function recordTimeIn($id)
	{
		$sql = "INSERT INTO {$this->table} (user_id, record_date, time_in)
				  VALUES (:id, CURDATE(), NOW());";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
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

	public function create($user_id, $record_date, $time_in, $time_out)
	{
		$sql = "INSERT INTO {$this->table} (user_id, record_date, time_in, time_out)
				  VALUES (:user_id, :record_date, :time_in, :time_out);";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":user_id", $user_id);
		$query->bindParam(":record_date", $record_date);
		$query->bindParam(":time_in", $time_in);
		$query->bindParam(":time_out", $time_out);
		return $query->execute();
	}

	public function update($id, $user_id, $record_date, $time_in, $time_out)
	{
		$sql = "UPDATE {$this->table}
				  SET user_id = :user_id, record_date = :record_date, time_in = :time_in, time_out = :time_out
				  WHERE id = :id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":id", $id);
		$query->bindParam(":user_id", $user_id);
		$query->bindParam(":record_date", $record_date);
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

	public function deleteAllFromUser($userId)
	{
		$sql = "DELETE FROM {$this->table}
				  WHERE user_id = :user_id;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":user_id", $userId);
		return $query->execute();
	}
}