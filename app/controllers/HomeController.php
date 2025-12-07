<?php
namespace App\Controllers;
use App\Models\EventRecord;
use App\Models\Notification;
use App\Services\AuthService;
use App\Services\FormatService;
use App\Services\HomeService;

class HomeController extends Controller
{
	private AuthService $authService;
	private EventRecord $recordModel;
	private HomeService $homeService;
	private Notification $notificationModel;

	public function __construct(AuthService $authService, EventRecord $recordModel, HomeService $homeService, Notification $notificationModel)
	{
		$this->authService = $authService;
		$this->homeService = $homeService;
		$this->recordModel = $recordModel;
		$this->notificationModel = $notificationModel;
	}
	
	public function index()
	{
		$data = $this->homeService->getAllData();
		$this->renderView("home/home", $data);
	}

	public function logout()
	{
		header("Location: .");
		$this->authService->logout();
		exit();
	}

	public function processLoginFromCode()
	{
		$code = trim($_POST["code"] ?? "");
		$errors = [];

		if (\strlen($code) < 4)
			$errors["code"] = "Please enter a 4-digit code.";
		if (empty($code))
			$errors["code"] = "Please enter your user code.";

		if ($errors) {
			ob_clean();
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		// Authentication
		$codeIsAdmin = $this->authService->codeIsAdmin($code);

		// Show login modal instead of recording time in/out
		if ($codeIsAdmin) {
			$_SESSION["code_is_admin"] = true;
			header("Location: home");
			exit();
		}
		
		$result = $this->authService->authenticateCode($code);
		
		$authErrors = $result["errors"] ?? null;
		if ($authErrors) {
			ob_clean();
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $authErrors]);
			exit();
		}

		// Log In
		$user = $result["user"];
		$this->authService->login($user);

		// Event Attendance
		$hasTimedInToday = $this->recordModel->hasTimedInToday($user["id"]) ?? false;
		$hasTimedOutToday = $this->recordModel->hasTimedOutToday($user["id"]) ?? false;

		if ($hasTimedInToday && $hasTimedOutToday)
			$_SESSION["error"] = "You have already clocked out for today.";
		elseif (!$hasTimedInToday && !$hasTimedOutToday)
			$this->timeIn();
		elseif ($hasTimedInToday && !$hasTimedOutToday)
			$this->timeOut();

		// Logout after record
		$this->logout();
		exit();
	}

	public function processLoginFromPassword()
	{
		$username = trim($_POST["username"] ?? "");
		$password = trim($_POST["password"] ?? "");
		$errors = [];

		// Validate user input
		if (empty($username))
			$errors["username"] = "Please enter your username.";
		if (empty($password))
			$errors["password"] = "Please enter your password.";

		if ($errors) {
			$data = array_merge(
				$this->homeService->getAllData(),
				["errors" => $errors, "username" => $username]
			);
			$this->renderView("home/home", $data);
			return;
		}

		// Authenticate
		$result = $this->authService->authenticateUsernamePassword($username, $password);

		if (isset($result["errors"])) {
			$errors = $result["errors"];
			$data = array_merge(
				$this->homeService->getAllData(),
				["errors" => $errors, "username" => $username]
			);
			$this->renderView("home/home", $data);
			return;
		}

		// Login / Session start
		$user = $result["user"];
		$this->authService->login($user);

		// Redirect based on role
		if ($this->authService->isAdmin()) {
			header("Location: dashboard");
			exit();
		}
		exit();
	}

	public function timeIn()
	{
		$this->authService->requireLogin();

		$now = FormatService::getCurrentDate();
		if (!$this->recordModel->recordTimeIn($_SESSION["user_id"]))
			$_SESSION["error"] = "Failed to record time-in.";
		else {
			$_SESSION["success"] = "Time-in recorded successfully.";
			$this->notificationModel->create(
				"Time In",
				$_SESSION["username"] . " has timed in on " . FormatService::formatDate($now) . ", at " . FormatService::formatTime($now) . ".",
				$_SESSION["user_id"]
			);
		}
	}

	public function timeOut()
	{
		$this->authService->requireLogin();

		$now = FormatService::getCurrentDate();
		if (!$this->recordModel->recordTimeOut($_SESSION["user_id"]))
			$_SESSION["error"] = "Failed to record time-out.";
		else {
			$_SESSION["success"] = "Time-out recorded successfully.";
			$this->notificationModel->create(
				"Time Out",
				$_SESSION["username"] . " has timed out on " . FormatService::formatDate($now) . ", at " . FormatService::formatTime($now) . ".",
				$_SESSION["user_id"]
			);
		}
	}
}