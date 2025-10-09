<?php
require_once "database.php";
class User extends Database
{
	private $id = "";
	private $first_name = "";
	private $last_name = "";
	private $middle_name = "";
	private $username = "";
	private $password = "";
	private $role = "";
	public function userExists($username)
	{
		$sql = "SELECT * FROM user WHERE username = :username LIMIT 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":username", $username);
		$query->execute();

		return $query->fetchColumn() ? true : false;
	}
	public function getUserByUsername($username)
	{
		$sql = "SELECT * FROM user WHERE username = :username LIMIT 1;";
		$query = $this->connect()->prepare($sql);
		$query->bindParam(":username", $username);
		$query->execute();

		return $query->fetch(PDO::FETCH_ASSOC) ?: null;
	}
	public function addUser($first_name, $last_name, $middle_name = "", $username, $password)
	{
		$sql = "INSERT INTO user (first_name, last_name, middle_name, username, password) VALUES (:first_name, :last_name, :middle_name, :username, :password);";

		$query = $this->connect()->prepare($sql);

		$query->bindParam(":first_name", $first_name);
		$query->bindParam(":last_name", $last_name);
		$query->bindParam(":middle_name", $middle_name);
		$query->bindParam(":username", $username);
		$query->bindParam(":password", $password);

		return $query->execute();
	}

	public function viewUser($search = "", $genre = "")
	{
		$sql = "SELECT * FROM daily_time_record WHERE title LIKE CONCAT('%', :search, '%') AND genre LIKE CONCAT('%', :genre, '%') ORDER BY title ASC;";

		$query = $this->connect()->prepare($sql);

		$query->bindParam(":search", $search);
		$query->bindParam(":genre", $genre);

		if ($query->execute()) {
			return $query->fetchAll();
		} else {
			return null;
		}
	}

	public function editUser($id)
	{
		$sql = "UPDATE user SET first_name = :first_name, last_name = :last_name, middle_name = :middle_name, username = :username, password = :password WHERE id = :id;";

		$query = $this->connect()->prepare($sql);

		$query->bindParam(":first_name", $first_name);
		$query->bindParam(":last_name", $last_name);
		$query->bindParam(":middle_name", $middle_name);
		$query->bindParam(":username", $username);
		$query->bindParam(":password", $password);
		$query->bindParam(":id", $id);

		return $query->execute();
	}

	public function deleteUser($bid)
	{
		$sql = "DELETE FROM employee WHERE id = :id;";

		$query = $this->connect()->prepare($sql);

		$query->bindParam(":id", $bid);

		return $query->execute();
	}
}