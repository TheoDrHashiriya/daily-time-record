<?php
require_once "controllers/UserController.php";
$userController = new UserController();

$request = trim($_SERVER["REQUEST_URI"], "/");

switch ($request) {
	case "/logout":
		$userController->logout();
		break;
	default:
		http_response_code(404);
		echo "Page not found";
}