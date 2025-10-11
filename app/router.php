<?php
session_start();

require_once "controllers/DailyTimeRecordController.php";
require_once "controllers/UserController.php";

define("BASE_URL", "/theonary/");
$request = trim(str_replace(BASE_URL, "", $_SERVER["REQUEST_URI"]), "/");

// Removes .php in the request
$request = preg_replace("/\.php$/", "", $request);

// echo "<pre>";
// echo "Request: ";
// var_dump($request);
// echo "__DIR__: ";
// var_dump(__DIR__);
// echo "</pre>";

$userController = new UserController();

switch ($request) {
	case "":
	case "/":
	case "index":
		$userController->home();
		break;

	case "login":
		$userController->loginPage();
		break;

	case "register":
		$userController->registerPage();
		break;

	case "logout":
		$userController->logout();
		break;

	default:
		http_response_code(404);
		echo "<p>Page not found.</p>";
}
