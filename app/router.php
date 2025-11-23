<?php
session_start();

require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "./../vendor/autoload.php";
require_once __DIR__ . "/helpers/GlobalHelper.php";
require_once __DIR__ . "/controllers/PageController.php";

$pageController = new PageController();

$request = trim(str_replace(BASE_URL, "", $_SERVER["REQUEST_URI"]), "/");
// Removes .php in the request
$request = preg_replace("/\.php$/", "", $request);

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

	case "delete-notification":
		if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"]))
			$pageController->deleteNotification($_POST["id"]);
		else
			http_response_code(405);
		echo "<p>Invalid request.</p>";
		break;

	// PDF HANDLING
	case "all-events":
		$pageController->previewAllEventsPdf();
		break;

	case "all-users":
		$pageController->previewAllUsersPdf();
		break;

	case "all-notifications":
		$pageController->previewAllNotificationsPdf();
		break;

	case "all-departments":
		$pageController->previewAllDepartmentsPdf();
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
