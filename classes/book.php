<?php
// require_once "database.php";
// require_once "../employee/view-employee.php";
class Employee extends Database
{
	public $id = "";
	public $title = "";
	public $author = "";
	public $genre = "";
	public $year = "";

	public function addEmployee()
	{
		$sql = "INSERT INTO employee (title, author, genre, year) VALUE (:title, :author, :genre, :year);";

		$query = $this->connect()->prepare($sql);

		$query->bindParam(":title", $this->title);
		$query->bindParam(":author", $this->author);
		$query->bindParam(":genre", $this->genre);
		$query->bindParam(":year", $this->year);

		return $query->execute();
	}

	public function viewEmployee($search = "", $genre = "")
	{
		$sql = "SELECT * FROM employee WHERE title LIKE CONCAT('%', :search, '%') AND genre LIKE CONCAT('%', :genre, '%') ORDER BY title ASC;";

		$query = $this->connect()->prepare($sql);

		$query->bindParam(":search", $search);
		$query->bindParam(":genre", $genre);

		if ($query->execute()) {
			return $query->fetchAll();
		} else {
			return null;
		}
	}

	public function employeeExists($btitle, $bid = "")
	{
		$sql = "SELECT COUNT(*) AS total FROM employee WHERE title = :title and id <> :id;";

		$query = $this->connect()->prepare($sql);

		$query->bindParam(":title", $btitle);
		$query->bindParam(":id", $bid);

		$record = null;
		if ($query->execute()) {
			$record = $query->fetch(PDO::FETCH_ASSOC);
			return $record["total"] > 0;
		}
		return false;
	}
	public function fetchEmployee($bid)
	{
		$sql = "SELECT * FROM employee WHERE id = :id;";

		$query = $this->connect()->prepare($sql);

		$query->bindParam(":id", $bid);

		if ($query->execute()) {
			return $query->fetch();
		} else {
			return null;
		}
	}

	public function editEmployee($bid)
	{
		$sql = "UPDATE employee SET title = :title, author = :author, genre = :genre, year = :year WHERE id = :id;";

		$query = $this->connect()->prepare($sql);

		$query->bindParam(":title", $this->title);
		$query->bindParam(":author", $this->author);
		$query->bindParam(":genre", $this->genre);
		$query->bindParam(":year", $this->year);
		$query->bindParam(":id", $bid);

		return $query->execute();
	}

	public function deleteEmployee($bid)
	{
		$sql = "DELETE FROM employee WHERE id = :id;";

		$query = $this->connect()->prepare($sql);

		$query->bindParam(":id", $bid);

		return $query->execute();
	}
}
// $obj = new Employee();
// obj->title = "Harry Potter";
// obj->genre = "Fiction";
// obj->year = 1200;
// var_dump($obj->addEmployee());