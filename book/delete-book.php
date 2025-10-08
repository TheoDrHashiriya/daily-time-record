<?php
require_once "../classes/book.php";
$bookObj = new Book();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	if (isset($_GET["id"])) {
		$bid = trim(htmlspecialchars($_GET["id"]));
		$book = $bookObj->fetchBook($bid);

		if (!$book) {
			echo "<a href='view-book.php'>View Books</a>";
			exit("Book not found!");

		} else {
			$bookObj->deleteBook($bid);
			header("Location: view-book.php");
		}
	} else {
		echo "<a href='view-book.php'>View Books</a>";
		exit("Book not found!");
	}
}

