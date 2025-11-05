<?php
session_start();

require_once __DIR__ . "/helpers/GlobalHelper.php";
require_once __DIR__ . "/controllers/PageController.php";

$pageController = new PageController();

define("BASE_URL", "/theonary/");

$request = trim(str_replace(BASE_URL, "", $_SERVER["REQUEST_URI"]), "/");
// Removes .php in the request
$request = preg_replace("/\.php$/", "", $request);

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

// echo password_hash("12345", PASSWORD_DEFAULT);

// echo "Session data:\n<prev>";
// foreach ($_SESSION as $key => $value)
// 	echo "| $key = $value \n";
// echo "</prev>";

// echo "POST data:\n<prev>";
// foreach ($_POST as $key => $value)
// 	echo "| $key = $value \n";
// echo "</prev>";

// echo "GET data:\n<prev>";
// foreach ($_GET as $key => $value)
// 	echo "| $key = $value \n";
// echo "</prev>";

// echo "User data:\n<prev>";
// foreach ($userData as $key => $value)
// 	echo "| $key = $value \n";
// echo "</prev>";

// echo "Request: $request";

switch ($request) {
	case "":
	case ".";
	case "/":
	case "index":
		$pageController->home();
		break;

	// USER
	case "authenticate":
		$pageController->authenticate();
		break;

	case "logout":
		$pageController->logout();
		break;

	case "timein":
		$pageController->timeIn();
		break;

	case "timeout":
		$pageController->timeOut();
		break;

	// ADMIN
	case "dashboard":
		$pageController->dashboard();
		break;

	case "register":
		$pageController->register();
		break;

	case "edit-user":
		if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"]))
			$pageController->editUser($_POST["id"]);
		else {
			http_response_code(405);
			echo "<p>Invalid request.</p>";
		}
		break;

	case "delete-user":
		if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"]))
			$pageController->deleteUser($_POST["id"]);
		else
			http_response_code(405);
		echo "<p>Invalid request.</p>";
		break;

	case "delete-record":
		if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"]))
			$pageController->deleteRecord($_POST["id"]);
		else
			http_response_code(405);
		echo "<p>Invalid request.</p>";
		break;

	// AJAX & PARTIALS
	case "ajax/auth-form":
		include __DIR__ . "/views/partials/home/auth-form.php";
		break;

	case "ajax/auth-button":
		include __DIR__ . "/views/partials/home/auth-button.php";
		break;

	default:
		http_response_code(404);
		echo "<p>Page not found.</p>";
}
