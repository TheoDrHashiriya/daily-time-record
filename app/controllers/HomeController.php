<?php
namespace App\Controllers;
use App\Models\EventRecord;
use App\Models\Department;
use App\Models\SystemLog;
use App\Services\AuthService;
use App\Services\FormatService;
use App\Services\HomeService;

class HomeController extends Controller
{
	private AuthService $authService;
	private Department $departmentModel;
	private EventRecord $recordModel;
	private HomeService $homeService;
	private SystemLog $systemLogModel;

	public function __construct(AuthService $authService, Department $departmentModel, EventRecord $recordModel, HomeService $homeService, SystemLog $systemLogModel)
	{
		$this->authService = $authService;
		$this->departmentModel = $departmentModel;
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

		// Show login modal instead of recording time in/out
		if ($userNumberIsFromAdmin) {
			header("Content-Type: application/json");
			echo json_encode([
				"success" => true,
				"user_number_is_admin" => $userNumberIsFromAdmin,
			]);
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
		$_SESSION["first_name"] = $user["first_name"];

		header("Content-Type: application/json");
		echo json_encode(["success" => true, "redirect" => "home"]);
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

		$user = $result["user"];
		$_SESSION["full_name"] = FormatService::formatFullName(
			$user["first_name"],
			$user["middle_name"],
			$user["last_name"],
		);

		// Event Attendance
		$currentHour = (int) date('H');
		$hasAmIn = $this->recordModel->hasRecorded($_SESSION["user_id"], AM_IN) ?? false;
		$hasAmOut = $this->recordModel->hasRecorded($_SESSION["user_id"], AM_OUT) ?? false;
		$hasPMIn = $this->recordModel->hasRecorded($_SESSION["user_id"], PM_IN) ?? false;
		$hasPMOut = $this->recordModel->hasRecorded($_SESSION["user_id"], PM_OUT) ?? false;

		$eventTypeToRecord = match (true) {
			$currentHour < 12 => $hasAmIn ? ($hasAmOut ? null : AM_OUT) : AM_IN,
			$currentHour >= 12 => $hasPMIn ? ($hasPMOut ? null : PM_OUT) : PM_IN,
			default => null
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

		header("Content-Type: application/json");
		echo json_encode(["success" => true, "user" => $result["user"], "redirect" => "logout"]);
		exit();
	}

	private function recordTime($eventType)
	{
		$full_name = $_SESSION["full_name"] ?? "";
		$user_id = $_SESSION["user_id"];
		$this->authService->requireLogin();

		$currentHour = (int) date('H');
		$eventMessage = match ($eventType) {
			AM_IN => "Morning Time-In",
			AM_OUT => "Morning Time-Out",
			PM_IN => $currentHour < 17 ? "Afternoon Time-In" : "Evening Time-In",
			PM_OUT => $currentHour < 17 ? "Afternoon Time-Out" : "Evening Time-Out",
			default => ""
		};

		// MESSAGE MODALS

		if (!$this->recordModel->record($user_id, $eventType)) {
			$_SESSION["message"]["error-title"] = $eventMessage . " Error";
			$_SESSION["message"]["error"] = "Failed to record " . strtolower($eventMessage) . ".";
			return false;
		}

		$greeting = match ($eventType) {
			AM_IN => "Good morning",
			AM_OUT => "Goodbye",
			PM_IN => $currentHour < 17 ? "Good afternoon" : "Good evening",
			PM_OUT => "Goodbye",
			default => ""
		};

		$_SESSION["message"]["success-title"] = $eventMessage . " Successful";
		$_SESSION["message"]["success"] = "$greeting, " . $_SESSION["first_name"] . ".";

		// SYSTEM LOGS

		$user_department = $this->departmentModel->getByUserId($user_id);
		$standard_times = [
			"AM_IN" => strtotime($user_department["standard_am_time_in"]),
			"AM_OUT" => strtotime($user_department["standard_am_time_out"]),
			"PM_IN" => strtotime($user_department["standard_pm_time_in"]),
			"PM_OUT" => strtotime($user_department["standard_pm_time_out"]),
		];

		$dateNow = FormatService::getCurrentDate();
		$timeNow = time();

		$systemLogType = match (true) {
			$eventType === AM_IN && $timeNow < $standard_times["AM_IN"] + EARLY_IN_OFFSET => LOG_AM_IN_EARLY,
			$eventType === AM_IN && $timeNow <= $standard_times["AM_IN"] + LATE_GRACE_PERIOD => LOG_AM_IN,
			$eventType === AM_IN && $timeNow > $standard_times["AM_IN"] + LATE_GRACE_PERIOD => LOG_AM_IN_LATE,

			$eventType === AM_OUT && $timeNow < $standard_times["AM_OUT"] + EARLY_OUT_OFFSET => LOG_AM_OUT_EARLY,
			$eventType === AM_OUT && $timeNow <= $standard_times["AM_OUT"] + LATE_GRACE_PERIOD => LOG_AM_OUT,
			$eventType === AM_OUT && $timeNow > $standard_times["AM_OUT"] + LATE_GRACE_PERIOD => LOG_AM_OUT_LATE,

			$eventType === PM_IN && $timeNow < $standard_times["PM_IN"] + EARLY_IN_OFFSET => LOG_PM_IN_EARLY,
			$eventType === PM_IN && $timeNow <= $standard_times["PM_IN"] + LATE_GRACE_PERIOD => LOG_PM_IN,
			$eventType === PM_IN && $timeNow > $standard_times["PM_IN"] + LATE_GRACE_PERIOD => LOG_PM_IN_LATE,

			$eventType === PM_OUT && $timeNow < $standard_times["PM_OUT"] + EARLY_OUT_OFFSET => LOG_PM_OUT_EARLY,
			$eventType === PM_OUT && $timeNow <= $standard_times["PM_OUT"] + LATE_GRACE_PERIOD => LOG_PM_OUT,
			$eventType === PM_OUT && $timeNow > $standard_times["PM_OUT"] + LATE_GRACE_PERIOD => LOG_PM_OUT_LATE,

			default => null
		};

		$notificationMessage = match ($eventType) {
			AM_IN => "in",
			AM_OUT => "out",
			PM_IN => "in",
			PM_OUT => "out",
		};

		$early = "early";
		$late = "late";
		$logTypeMessage = match ($systemLogType) {
			LOG_AM_IN_EARLY => $early,
			LOG_AM_IN_LATE => $late,
			LOG_AM_OUT_EARLY => $early,
			LOG_AM_OUT_LATE => $late,

			LOG_PM_IN_EARLY => $early,
			LOG_PM_IN_LATE => $late,
			LOG_PM_OUT_EARLY => $early,
			LOG_PM_OUT_LATE => $late,
			default => "",
		};

		$this->systemLogModel->create(
			ucfirst($logTypeMessage) . ' ' . $eventMessage,
			$full_name . " has timed $notificationMessage $logTypeMessage on " . FormatService::formatDate($dateNow) . ", at " . FormatService::formatTime($timeNow) . ".",
			$_SESSION["user_id"],
			$systemLogType
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

		if ($this->authService->isAdmin()) {
			// 	$_SESSION["message"]["success-title"] = "Logged In";
			// 	$_SESSION["message"]["success"] = "Welcome, " . $user["first_name"] . ".";
			header("Content-Type: application/json");
			echo json_encode(["success" => true, "redirect" => "dashboard"]);
			exit();
		}

		header("Content-Type: application/json");
		echo json_encode(["success" => true, "redirect" => "home"]);
		exit();
	}
}