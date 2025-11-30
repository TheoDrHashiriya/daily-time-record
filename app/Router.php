<?php
namespace Router;
session_start();
// Composer autoload
require_once __DIR__ . "./../vendor/autoload.php";
// Configs
require_once __DIR__ . "/config/db_config.php";
require_once __DIR__ . "/config/email_config.php";
// Controllers
use App\Controllers\HomeController;
use App\Controllers\DashboardController;
use App\Controllers\UserController;
use App\Controllers\EventRecordController;
use App\Controllers\PageController;

class Router
{
	private $homeController;
	private $dashboardController;
	private $pageController;
	private $userController;
	private $erController;

	public function __construct()
	{
		$this->homeController = new HomeController();
		$this->dashboardController = new DashboardController();
		$this->pageController = new PageController();
		$this->userController = new UserController();
		$this->erController = new EventRecordController();
		$this->run();
	}

	public function postEntityIdIsSet()
	{
		return $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"]);
	}

	public function run()
	{
		// Gets request URI without forward-slashes and base URL
		$request = trim(
			str_replace(
				BASE_URL,
				"",
				$_SERVER["REQUEST_URI"]
			),
			"/"
		);

		// Removes .php in the request
		$request = preg_replace("/\.php$/", "", $request);

		switch ($request) {
			case "":
			case ".";
			case "/":
			case "home":
			case "index":
				$this->homeController->index();
				break;

			// EMPLOYEE
			case "authenticate":
				$this->homeController->processLogin();
				break;

			case "logout":
				$this->homeController->logout();
				break;

			case "timein":
				$this->pageController->timeIn();
				break;

			case "timeout":
				$this->pageController->timeOut();
				break;

			// ADMIN
			case "dashboard":
				// $this->pageController->dashboard();
				$this->dashboardController->index();
				break;

			case "register":
				$this->pageController->register();
				break;

			case "edit-user":
				if (!$this->userController->edit()) {
					http_response_code(405);
					echo "<p>Invalid request.</p>";
				}
				break;

			case "delete-user":
				if ($this->postEntityIdIsSet())
					$this->pageController->deleteUser($_POST["id"]);
				else
					http_response_code(405);
				echo "<p>Invalid request.</p>";
				break;

			case "edit-record":
				if (!$this->erController->edit()) {
					http_response_code(405);
					echo "<p>Invalid request.</p>";
				}
				break;

			case "delete-record":
				if (!$this->erController->delete()) {
					http_response_code(405);
					echo "<p>Invalid request.</p>";
				}
				break;

			case "delete-notification":
				if ($this->postEntityIdIsSet())
					$this->pageController->deleteNotification($_POST["id"]);
				else
					http_response_code(405);
				echo "<p>Invalid request.</p>";
				break;

			// PDF HANDLING
			case "all-events":
				$this->pageController->previewAllEventsPdf();
				break;

			case "all-users":
				$this->pageController->previewAllUsersPdf();
				break;

			case "all-notifications":
				$this->pageController->previewAllNotificationsPdf();
				break;

			case "all-departments":
				$this->pageController->previewAllDepartmentsPdf();
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
	}
}

new Router();