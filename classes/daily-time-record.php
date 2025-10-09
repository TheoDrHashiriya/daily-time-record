<?php
require_once "./classes/database.php";
class DailyTimeRecord extends Database
{
	private $table = "daily_time_record";
	public function timeIn($id)
	{
		$sql = "INSERT INTO {$this->table} (user_id, record_date, time_in)
				  VALUES (:id, CURDATE(), NOW());";

		$query = $this->connect()->prepare($sql);

		$query->bindParam(":id", $id);

		return $query->execute();
	}
	public function timeOut($id)
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
	public function getAllRecords()
	{
		$sql = "SELECT dtr.*, CONCAT(u.first_name, ' ', u.last_name) AS user
				  FROM {$this->table} dtr
				  JOIN user u ON dtr.user_id = u.id
				  ORDER BY dtr.record_date DESC;";

		$query = $this->connect()->prepare($sql);

		$query->execute();

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getRecordsByUser($id)
	{
		$sql = "SELECT dtr.*, CONCAT(u.first_name, ' ', u.last_name) AS user
				  FROM {$this->table} dtr
				  WHERE user_id = :id
				  ORDER BY dtr.record_date DESC;";

		$query = $this->connect()->prepare($sql);

		$query->bindParam(":id", $id);

		$query->execute();

		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
}