<?php
session_start();

echo "Session data:\n<prev>";
foreach ($_SESSION as $key => $value)
	echo "| $key = $value |\n";
echo "</prev>";

require_once "controllers/DailyTimeRecordController.php";
require_once "controllers/UserController.php";

define("BASE_URL", "/theonary/");
$request = trim(str_replace(BASE_URL, "", $_SERVER["REQUEST_URI"]), "/");

// Removes .php in the request
$request = preg_replace("/\.php$/", "", $request);

$userController = new UserController();

switch ($request) {
	case "":
	case "/":
	case "index":
		$userController->homePage();
		break;

	// USER
	case "login":
		$userController->loginPage();
		break;

	case "register":
		$userController->registerPage();
		break;

	case "update":
		$userController->updatePage();

	case "logout":
		$userController->logout();
		break;

	// ADMIN
	case "create":
		$userController->createPage();
		break;

	default:
		http_response_code(404);
		echo "<p>Page not found.</p>";
}
