<?php
require_once "../classes/book.php";
$bookObj = new Book();

$search = $genre = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$search = isset($_GET["search"]) ? trim(htmlspecialchars($_GET["search"])) : "";
	$genre = isset($_GET["genre"]) ? trim(htmlspecialchars($_GET["genre"])) : "";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../styles/view-book.css">
	<title>View Books</title>
</head>

<body>
	<nav class="topnav">
		<div class="nav-left">
			<p class="title">Books</p>
			<a class="button-add" href="add-book.php"><button type="button">Add Book</button></a>
		</div>

		<div class="nav-right">
			<form action="" method="get">
				<label for="search">Search:</label>
				<input type="search" name="search" id="search" value="<?= $search ?>">

				<select name="genre" id="genre">
					<option value="">ALL</option>
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

				<button type="submit">Search</button>
			</form>
		</div>

	</nav>

	<section class="body">
		<table>
			<tr>
				<th>No.</th>
				<th>Book Name</th>
				<th>Book Author</th>
				<th>Genre</th>
				<th>Publication Year</th>
				<th>Actions</th>
			</tr>

			<?php
			$no = 1;
			foreach ($bookObj->viewBook($search, $genre) as $book) {
				$message = "Are you sure you want to delete the book " . $book["title"] . "?";
				?>
				<tr>
					<td><?= $no++ ?></td>
					<td><?= $book["title"] ?></td>
					<td><?= $book["author"] ?></td>
					<td><?= $book["genre"] ?></td>
					<td class="year"><?= $book["year"] ?></td>
					<td>
						<a class="button-edit" href="edit-book.php?id=<?= $book["id"] ?>"><button>Edit</button></a>
						<a class="button-delete" href="delete-book.php?id=<?= $book["id"] ?>"
							onclick="return confirm('<?= $message ?>')"><button>Delete</button></a>
					</td>
				</tr>
			<?php }
			; ?>
		</table>
</body>
</section>

</html>