<?php
namespace Router;

require_once __DIR__ . "/bootstrap.php";

// Controllers
use App\Controllers\HomeController;
use App\Controllers\DashboardController;
use App\Controllers\DepartmentController;
use App\Controllers\EventRecordController;
use App\Controllers\NotificationController;
use App\Controllers\UserController;
use App\Controllers\PageController;
use App\Models\Department;
use App\Models\EventRecord;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserRole;
use App\Services\AuthService;
use App\Services\DashboardService;
use App\Services\HomeService;

class Router
{
	private HomeController $homeController;
	private DashboardController $dashboardController;
	private DepartmentController $departmentController;
	private EventRecordController $recordController;
	private NotificationController $notificationController;
	private PageController $pageController;
	private UserController $userController;

	public function __construct()
	{
		// Initiations, DI stuff

		// Models
		$departmentModel = new Department();
		$notificationModel = new Notification();
		$recordModel = new EventRecord();
		$userModel = new User();
		$userRoleModel = new UserRole();

		// Services
		$authService = new AuthService($userModel);
		$homeService = new HomeService();
		$dashboardService = new DashboardService(
			$authService,
			$departmentModel,
			$notificationModel,
			$recordModel,
			$userModel,
			$userRoleModel
		);

		// Controllers
		$this->homeController = new HomeController($authService, $recordModel, $homeService, $notificationModel);
		$this->dashboardController = new DashboardController($authService, $dashboardService);
		$this->recordController = new EventRecordController($recordModel);
		$this->departmentController = new DepartmentController($departmentModel);
		$this->notificationController = new NotificationController($notificationModel);
		$this->pageController = new PageController($this->recordController);
		$this->userController = new UserController();
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

			case "authenticate":
				$this->homeController->processLoginFromCode();
				break;

			case "login":
				$this->homeController->processLoginFromPassword();
				break;

			case "logout":
				$this->homeController->logout();
				break;

			case "time-in":
				$this->homeController->timeIn();
				break;

			case "time-out":
				$this->homeController->timeOut();
				break;

			// ADMIN
			case "dashboard":
				// $this->pageController->dashboard();
				$this->dashboardController->index();
				break;

			case "register":
				$this->pageController->register();
				break;

			// CRUD

			case "create-department":
				if (!$this->departmentController->create()) {
					http_response_code(405);
					echo "<p>Invalid request.</p>";
				}
				break;

			case "edit-department":
				if (!$this->departmentController->edit()) {
					http_response_code(405);
					echo "<p>Invalid request.</p>";
				}
				break;

			case "delete-department":
				if (!$this->departmentController->delete()) {
					http_response_code(405);
					echo "<p>Invalid request.</p>";
				}
				break;

			case "create-user":
				if (!$this->userController->create()) {
					http_response_code(405);
					echo "<p>Invalid request.</p>";
				}
				break;

			case "edit-user":
				if (!$this->userController->edit()) {
					http_response_code(405);
					echo "<p>Invalid request.</p>";
				}
				break;

			case "delete-user":
				if (!$this->userController->delete()) {
					http_response_code(405);
					echo "<p>Invalid request.</p>";
				}
				break;

			case "edit-record":
				if (!$this->recordController->edit()) {
					http_response_code(405);
					echo "<p>Invalid request.</p>";
				}
				break;

			case "delete-record":
				if (!$this->recordController->delete()) {
					http_response_code(405);
					echo "<p>Invalid request.</p>";
				}
				break;

			case "create-notification":
				if (!$this->notificationController->create()) {
					http_response_code(405);
					echo "<p>Invalid request.</p>";
				}
				break;

			case "edit-notification":
				if (!$this->notificationController->edit()) {
					http_response_code(405);
					echo "<p>Invalid request.</p>";
				}
				break;

			case "delete-notification":
				if (!$this->notificationController->delete()) {
					http_response_code(405);
					echo "<p>Invalid request.</p>";
				}
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