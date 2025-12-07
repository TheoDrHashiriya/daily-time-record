<?php
namespace Router;

require_once __DIR__ . "/bootstrap.php";

// Controllers
use App\Controllers\{HomeController, DashboardController, DepartmentController, EventRecordController, NotificationController, UserController, PageController};
use App\Models\{Department, EventRecord, Notification, User, UserRole};
use App\Services\{AuthService, DashboardService, HomeService};

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
		$this->recordController = new EventRecordController($recordModel, $dashboardService);
		$this->departmentController = new DepartmentController($departmentModel);
		$this->notificationController = new NotificationController($notificationModel);
		$this->pageController = new PageController($this->recordController);
		$this->userController = new UserController();
		$this->run();
	}

	public function respondWithStatus($code)
	{
		http_response_code($code);

		switch ($code) {
			case 404:
				echo "<p>Page not found.</p>";
				break;

			case 405:
			default:
				echo "<p>Invalid request.</p>";
		}
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

			// CRUD

			case "create-department":
				if (!$this->departmentController->create())
					$this->respondWithStatus(405);
				break;

			case "edit-department":
				if (!$this->departmentController->edit())
					$this->respondWithStatus(405);
				break;

			case "delete-department":
				if (!$this->departmentController->delete())
					$this->respondWithStatus(405);
				break;

			case "create-user":
				if (!$this->userController->create())
					$this->respondWithStatus(405);
				break;

			case "edit-user":
				if (!$this->userController->edit())
					$this->respondWithStatus(405);
				break;

			case "delete-user":
				if (!$this->userController->delete())
					$this->respondWithStatus(405);
				break;

			case "edit-record":
				if (!$this->recordController->edit())
					$this->respondWithStatus(405);
				break;

			case "delete-record":
				if (!$this->recordController->delete())
					$this->respondWithStatus(405);
				break;

			case "create-notification":
				if (!$this->notificationController->create())
					$this->respondWithStatus(405);
				break;

			case "edit-notification":
				if (!$this->notificationController->edit())
					$this->respondWithStatus(405);
				break;

			case "delete-notification":
				if (!$this->notificationController->delete())
					$this->respondWithStatus(405);
				break;

			// PDF HANDLING
			case "all-events":
				$this->recordController->streamToPdf();
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

			default:
				$this->respondWithStatus(404);
		}
	}
}

new Router();