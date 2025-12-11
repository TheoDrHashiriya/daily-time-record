<?php
namespace App\Controllers;
use App\Models\EventRecord;
use App\Models\SystemLog;
use App\Services\AuthService;
use App\Services\FormatService;
use App\Services\HomeService;

class HomeController extends Controller
{
	private AuthService $authService;
	private EventRecord $recordModel;
	private HomeService $homeService;
	private SystemLog $systemLogModel;

	public function __construct(AuthService $authService, EventRecord $recordModel, HomeService $homeService, SystemLog $systemLogModel)
	{
		$this->authService = $authService;
		$this->homeService = $homeService;
		$this->recordModel = $recordModel;
		$this->systemLogModel = $systemLogModel;
	}

	public function index()
	{
		if ($this->authService->isAdmin())
			header("Location: dashboard");

		$data = $this->homeService->getAllData();
		$this->renderView("home/home", $data);
	}

	public function logout()
	{
		$message = $_SESSION["message"] ?? null;

		$this->authService->logout();

		session_start();

		if ($message)
			$_SESSION["message"] = $message;

		header("Location: home");
		exit();
	}

	public function processUserNumber()
	{
		$userNumber = trim($_POST["user_number"] ?? "");
		$errors = [];

		if (\strlen($userNumber) < 4)
			$errors["user_number"] = "Please enter a 4-digit number.";
		if (empty($userNumber))
			$errors["user_number"] = "Please enter your user number.";

		if ($errors) {
			ob_clean();
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		// Authentication
		$userNumberIsFromAdmin = $this->authService->userNumberIsFromAdmin($userNumber);
		$_SESSION["user_number_is_admin"] = $userNumberIsFromAdmin;

		// Show login modal instead of recording time in/out
		if ($userNumberIsFromAdmin) {
			header("Location: home");
			exit();
		}

		$result = $this->authService->authenticateUserNumber($userNumber);

		$authErrors = $result["errors"] ?? null;
		if ($authErrors) {
			ob_clean();
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $authErrors]);
			exit();
		}

		$user = $result["user"];
		$_SESSION["user_id"] = $user["id"];
		header("Location: home");
		exit();
	}

	public function processQrCode()
	{
		$id = $_SESSION["user_id"];
		$qr_code = trim($_POST["qr_code"] ?? "");
		$errors = [];

		if (!$id)
			$errors["qr_code"] = "Invalid user ID.";
		if (empty($qr_code))
			$errors["qr_code"] = "Please show your QR code.";

		if ($errors) {
			ob_clean();
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$result = $this->authService->authenticateQrCode($qr_code);
		if (isset($result["errors"])) {
			ob_clean();
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $result["errors"]]);
			exit();
		}

		// Event Attendance
		$currentHour = (int) date('H');
		$hasAmIn = $this->recordModel->hasRecorded($_SESSION["user_id"], AM_IN) ?? false;
		$hasAmOut = $this->recordModel->hasRecorded($_SESSION["user_id"], AM_OUT) ?? false;
		$hasPMIn = $this->recordModel->hasRecorded($_SESSION["user_id"], PM_IN) ?? false;
		$hasPMOut = $this->recordModel->hasRecorded($_SESSION["user_id"], PM_OUT) ?? false;

		$eventTypeToRecord = match (true) {
			$currentHour < 12 => $hasAmIn
			? ($hasAmOut ? null : AM_OUT)
			: AM_IN,
			$currentHour >= 12 => $hasPMIn
			? ($hasPMOut ? null : PM_OUT)
			: PM_IN,
		};

		if ($eventTypeToRecord)
			$this->recordTime($eventTypeToRecord);
		else {
			$timeMessage = match (true) {
				$currentHour < 12 => "Morning",
				$currentHour >= 12 => "Afternoon"
			};

			$_SESSION["message"]["info-title"] = "$timeMessage Already Clocked Out";
			$_SESSION["message"]["info"] = "You have already clocked out for this " . strtolower($timeMessage) . ".";
		}

		ob_clean();
		header("Content-Type: application/json");
		echo json_encode(["success" => true, "user" => $result["user"], "logoutAfter" => true]);
		exit();
	}

	private function recordTime($eventType)
	{
		$this->authService->requireLogin();

		$eventMessage = match ($eventType) {
			AM_IN => "Morning Time-In",
			AM_OUT => "Morning Time-Out",
			PM_IN => "Afternoon Time-In",
			PM_OUT => "Afternoon Time-Out",
			default => ""
		};

		if (!$this->recordModel->record($_SESSION["user_id"], $eventType)) {
			$_SESSION["message"]["error-title"] = $eventMessage . " Error";
			$_SESSION["message"]["error"] = "Failed to record " . strtolower($eventMessage) . ".";
			return false;
		}

		$greeting = match ($eventType) {
			AM_IN => "Good morning",
			AM_OUT => "Goodbye",
			PM_IN => "Good afternoon",
			PM_OUT => "Goodbye",
			default => ""
		};

		$_SESSION["message"]["success-title"] = $eventMessage . " Successful";
		$_SESSION["message"]["success"] = "$greeting, " . $_SESSION["first_name"] . ".";

		$notificationMessage = match ($eventType) {
			AM_IN => "in",
			AM_OUT => "out",
			PM_IN => "in",
			PM_OUT => "out",
			default => ""
		};

		$now = FormatService::getCurrentDate();
		$this->systemLogModel->create(
			$eventMessage,
			$_SESSION["username"] . " has timed $notificationMessage on " . FormatService::formatDate($now) . ", at " . FormatService::formatTime($now) . ".",
			$_SESSION["user_id"]
		);

		return true;
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
			ob_clean();
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		// Authenticate
		$result = $this->authService->authenticateUsernamePassword($username, $password);

		if (isset($result["errors"])) {
			ob_clean();
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $result["errors"]]);
			exit();
		}

		// Login / Session start
		$user = $result["user"];
		$this->authService->login($user);

		if ($this->authService->isAdmin())
			header("Location: dashboard");

		exit();
	}
}