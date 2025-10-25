<?php
session_start();

// echo "Session data:\n<prev>";
// foreach ($_SESSION as $key => $value)
// 	echo "| $key = $value |\n";
// echo "</prev>";

require_once __DIR__ . "controllers/PageController.php";

define("BASE_URL", "/theonary/");

$request = trim(str_replace(BASE_URL, "", $_SERVER["REQUEST_URI"]), "/");

// Removes .php in the request
$request = preg_replace("/\.php$/", "", $request);

$pageController = new PageController();

switch ($request) {
	case "":
	case "/":
	case "index":
		$pageController->homePage();
		break;

	// USER
	case "login":
		$pageController->loginPage();
		break;

	case "logout":
		$pageController->logout();
		break;

	case "update":
		$pageController->updateUserPage();

	case "timein":
		$pageController->timeIn();
		break;

	case "timeout":
		$pageController->timeOut();
		break;

	// ADMIN
	case "create":
		$pageController->createUserPage();
		break;

	case "register":
		$pageController->registerPage();
		break;

	default:
		http_response_code(404);
		echo "<p>Page not found.</p>";
}
