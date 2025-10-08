<?php
require_once "../classes/book.php";
$bookObj = new Book();

$book = [];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$book["title"] = trim(htmlspecialchars($_POST["title"]));
	$book["author"] = trim(htmlspecialchars($_POST["author"]));
	$book["genre"] = trim(htmlspecialchars($_POST["genre"]));
	$book["year"] = trim(htmlspecialchars($_POST["year"]));

	if (empty($book["title"])) {
		$errors["title"] = "Book title is required.";
	} elseif ($bookObj->bookExists($book["title"])) {
		$errors["title"] = "Book already exists.";
	}

	if (empty($book["author"])) {
		$errors["author"] = "Book author is required.";
	}

	if (empty($book["genre"])) {
		$errors["genre"] = "Please select a genre.";
	}

	if ($book["year"] === "" || $book["year"] === null) {
		$errors["year"] = "Book publication year is required.";
	} elseif (!is_numeric($book["year"]) || $book["year"] == 0) {
		$errors["year"] = "Book publication year must be a number greater than or equal to zero.";
	} elseif ($book["year"] > date("Y")) {
		$errors["year"] = "Book publication year must be older than current year.";
	}

	if (empty(array_filter($errors))) {
		$bookObj->title = $book["title"];
		$bookObj->author = $book["author"];
		$bookObj->genre = $book["genre"];
		$bookObj->year = $book["year"];

		if ($bookObj->addBook()) {
			header("Location: view-book.php");
		} else {
			echo "Error";
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Add Book</title>
	<link rel="stylesheet" href="../styles/add-edit-book.css">
</head>

<body>
	<div class="box">
		<h1 class="title">Add Book</h1>

		<label for="">Fields with <span>*</span> are required</label>

		<form action="" method="post">
			<div class="row">
				<label for="title">Book Name*:</label>
				<input type="text" name="title" id="title" value="<?= $book["title"] ?? "" ?>">
				<p class="error"><?= $errors["title"] ?? "" ?></p>
			</div>

			<div class="row"><label for="title">Book Author*:</label>
				<input type="text" name="author" id="author" value="<?= $book["author"] ?? "" ?>">
				<p class="error"><?= $errors["author"] ?? "" ?></p>
			</div>

			<div class="row">
				<label for="genre">Category*:</label>
				<select name="genre" id="genre">
					<option value="">---SELECT---</option>
					<option value="History" <?= (isset($book["genre"]) && $book["genre"] == "History") ? "selected" : "" ?>>
						History
					</option>
					<option value="Science" <?= (isset($book["genre"]) && $book["genre"] == "Science") ? "selected" : "" ?>>
						Science
					</option>
					<option value="Fiction" <?= (isset($book["genre"]) && $book["genre"] == "Fiction") ? "selected" : "" ?>>
						Fiction
					</option>
				</select>
				<p class="error"><?= $errors["genre"] ?? "" ?></p>
			</div>

			<div class="row">
				<label for="year">Publication Year*:</label>
				<input type="text" name="year" id="year" value="<?= $book["year"] ?? "" ?>">
				<p class="error"><?= $errors["year"] ?? "" ?></p>
			</div>

			<button type="submit">Submit</button>
		</form>

		<a href="view-book.php"><button>View books</button></a>
	</div>
</body>

</html>