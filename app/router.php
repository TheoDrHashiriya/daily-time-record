<?php
session_start();

define("BASE_URL", "/theonary/");

$request = trim(str_replace(BASE_URL, "", $_SERVER["REQUEST_URI"]), "/");

// echo "<pre>";
// echo "Request: ";
// var_dump($request);
// echo "__DIR__: ";
// var_dump(__DIR__);
// echo "</pre>";

switch ($request) {
	case "":
	case "/":
		require "controllers/DailyTimeRecordController.php";
		require "views/index.php";
		break;

	case "login":
		require "controllers/UserController.php";
		require "views/login.php";
		break;

	case "logout":
		require "controllers/UserController.php";
		$userController = new UserController();
		$userController->logout();
		break;

	case "register":
		require "controllers/UserController.php";
		require "views/register.php";
		break;

	default:
		http_response_code(404);
		echo "<p>Page not found.</p>";
}
