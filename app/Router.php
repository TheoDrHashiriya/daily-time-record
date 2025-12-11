<?php
namespace Router;

require_once __DIR__ . "/bootstrap.php";

// Controllers
use App\Controllers\{HomeController, DashboardController, DepartmentController, EventRecordController, SystemLogController, UserController, PageController};
use App\Models\{Department, EventRecord, SystemLog, User, UserRole};
use App\Services\{AuthService, DashboardService, HomeService};

class Router
{
	private HomeController $homeController;
	private DashboardController $dashboardController;
	private DepartmentController $departmentController;
	private EventRecordController $recordController;
	private SystemLogController $systemLogController;
	private PageController $pageController;
	private UserController $userController;

	public function __construct()
	{
		// Initiations, DI stuff

		// Models
		$departmentModel = new Department();
		$systemLogModel = new SystemLog();
		$recordModel = new EventRecord();
		$userModel = new User();
		$userRoleModel = new UserRole();

		// Services
		$authService = new AuthService($userModel);
		$homeService = new HomeService();
		$dashboardService = new DashboardService(
			$authService,
			$departmentModel,
			$systemLogModel,
			$recordModel,
			$userModel,
			$userRoleModel
		);

		// Controllers
		$this->homeController = new HomeController($authService, $recordModel, $homeService, $systemLogModel);
		$this->dashboardController = new DashboardController($authService, $dashboardService);
		$this->recordController = new EventRecordController($recordModel, $dashboardService);
		$this->departmentController = new DepartmentController($departmentModel);
		$this->systemLogController = new SystemLogController($dashboardService, $systemLogModel);
		$this->pageController = new PageController($this->recordController);
		$this->userController = new UserController($userModel, $dashboardService);
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
		$requestUri = $_SERVER["REQUEST_URI"];
		$parsedUrl = parse_url($requestUri);

		// Gets request URI without forward-slashes and base URL
		$requestPath = trim(
			str_replace(
				BASE_URL,
				"",
				$parsedUrl["path"]
			),
			"/"
		);

		// Removes .php in the request
		$requestPath = preg_replace("/\.php$/", "", $requestPath);

		switch ($requestPath) {
			case "":
			case ".";
			case "/":
			case "home":
			case "index":
				$this->homeController->index();
				break;

			case "authenticate":
				$this->homeController->processUserNumber();
				break;

			case "login":
				$this->homeController->processLoginFromPassword();
				break;

			case "login-qr":
				$this->homeController->processQrCode();
				break;

			case "logout":
				$this->homeController->logout();
				break;

			// ADMIN
			case "dashboard":
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

			case "create-record":
				if (!$this->recordController->create())
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

			case "create-system-log":
				if (!$this->systemLogController->create())
					$this->respondWithStatus(405);
				break;

			case "edit-system-log":
				if (!$this->systemLogController->edit())
					$this->respondWithStatus(405);
				break;

			case "delete-system-log":
				if (!$this->systemLogController->delete())
					$this->respondWithStatus(405);
				break;

			// PDF HANDLING
			case "all-events":
				$this->recordController->streamToPdf();
				break;

			case "all-users":
				$this->userController->streamToPdf();
				break;

			case "all-system-logs":
				$this->systemLogController->streamToPdf();
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