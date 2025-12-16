<?php
namespace App\Controllers;
use App\Models\User;
use App\Services\{AttendanceService, FormatService, DashboardService, QRCodeService};
use DateTime;
use PDOException;
use PrintService;

class UserController extends Controller
{
	private AttendanceService $attendanceService;
	private DashboardService $dashboardService;
	private User $userModel;

	public function __construct(AttendanceService $attendanceService, User $userModel, DashboardService $dashboardService)
	{
		$this->attendanceService = $attendanceService;
		$this->dashboardService = $dashboardService;
		$this->userModel = $userModel;
	}

	public function streamToPdf()
	{
		$users = $this->dashboardService->getUsers();
		PrintService::streamPdf(
			"all-users-" . FormatService::formatPdfName(FormatService::getCurrentDate()),
			["components/pdf/pdf-styles", "components/tables/users"],
			["users" => $users]
		);
		exit();
	}

	public function streamToPdfQrCode()
	{
		$id = $_POST["entity_id"] ?? "";

		if ($id)
			$_SESSION["entity_id"] = $id;
		else
			$id = $_SESSION["entity_id"];

		$user = $this->userModel->getById($id);
		$user["full_name_formatted"] = FormatService::formatFullName(
			$user["first_name"],
			$user["middle_name"],
			$user["last_name"],
		);
		$user["qr_code_base64"] = QRCodeService::render($user["qr_string"]);

		PrintService::streamPdf(
			"qr-code-" . FormatService::formatPdfName(strtolower($id . "-" . $user["full_name_formatted"])),
			["components/pdf/modals", "components/modals/user-qr"],
			["user" => $user]
		);
		exit();
	}

	public function streamToPdfUserRecords()
	{
		$user_id = (int) trim($_GET["user_id"]);
		$year = (int) trim($_GET["year"]);
		$month = (int) trim($_GET["month"]);

		if (empty($user_id))
			$errors["user_id"] = "User is required.";
		if (empty($year))
			$errors["year"] = "Year is required.";
		if (empty($month))
			$errors["month"] = "Month is required.";

		if (!empty($errors)) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$user = $this->userModel->getById($user_id);
		if (!$user) {
			echo json_encode(["success" => false, "message" => "User not found."]);
			exit();
		}

		$records = $this->attendanceService->getMonthlyRecordsForUser($user_id, $year, $month);
		$recordsForView = $records[$user_id] ?? [];

		// echo "<prev>";
		// var_dump($records);
		// echo "</prev>";
		// exit;

		$user["full_name_formatted"] = FormatService::formatFullName(
			$user["first_name"],
			$user["middle_name"],
			$user["last_name"],
		);

		$monthLabel = DateTime::createFromFormat("!m", $month)->format("F");

		PrintService::streamPdf(
			"dtr-{$user_id}-{$year}-{$month}",
			["components/pdf/pdf-styles", "components/tables/user-records"],
			[
				"user" => $user,
				"records" => $recordsForView,
				"year" => $year,
				"month" => $monthLabel,
			]
		);

		exit();
	}

	public function create()
	{
		$first_name = trim($_POST["first_name"]);
		$middle_name = trim($_POST["middle_name"]);
		$last_name = trim($_POST["last_name"]);
		$username = trim($_POST["username"]);
		$password = trim($_POST["password"]);
		$user_role = trim($_POST["user_role"]);
		$department = trim($_POST["department"]);
		$created_by = trim($_SESSION["user_id"] ?? "");

		if ($this->userModel->usernameExists($username))
			$errors["username"] = "Username already taken.";
		if (empty($first_name))
			$errors["first_name"] = "First name is required.";
		if (empty($last_name))
			$errors["last_name"] = "Last name is required.";
		if (empty($username))
			$errors["username"] = "Username is required.";
		if (empty($password))
			$errors["password"] = "Password is required.";
		if (empty($user_role))
			$errors["user_role"] = "Role is required.";
		if (empty($department))
			$errors["department"] = "Department is required.";

		if (!empty($errors)) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$hashed_password = password_hash($password, PASSWORD_DEFAULT);

		do
			$user_number = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
		while ($this->userModel->userNumberExists($user_number));

		$user_id = $this->userModel->create(
			$first_name,
			$last_name,
			$middle_name,
			$username,
			$hashed_password,
			$user_number,
			$user_role,
			$department,
			$created_by
		);

		do
			$qr_string = bin2hex(random_bytes(32));
		while ($this->userModel->qrStringExists($qr_string));
		$this->userModel->generateQrString($user_id, $qr_string);

		if ($user_id) {
			$message["success"] = "User created successfully.";
			$response = ["success" => true, "redirect" => "dashboard"];
		} else {
			$message["error"] = "Failed to create user.";
			$response = ["success" => false, "redirect" => "dashboard"];
		}

		$_SESSION["message"] = $message;
		header("Content-Type: application/json");
		echo json_encode($response);
		exit();
	}

	public function showQr()
	{
		$id = $_GET["id"] ?? null;
		if (!$id)
			return;

		$user = $this->userModel->getById($id);
		if (!$user)
			return;

		echo QRCodeService::render($user["qr_string"]);
		exit;
	}

	public function regenerateQr()
	{
		$user_id = $_POST["entity_id"] ?? null;

		if (!$user_id)
			$errors["general"] = "Invalid user ID.";

		if (!$user_id) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$user = $this->userModel->getById($user_id);
		if (!$user)
			$errors["general"] = "User not found.";

		if (!$user) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$old_qr_string = $user["qr_string"];

		$expired = $this->userModel->expireQrString($old_qr_string);
		if (!$expired)
			$errors["general"] = "Failed to expire QR code.";

		if (!$expired) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		do
			$new_qr_string = bin2hex(random_bytes(32));
		while ($this->userModel->qrStringExists($new_qr_string));
		$this->userModel->generateQrString($user_id, $new_qr_string);

		$message["success"] = "QR code regenerated successfully.";
		$response = ["success" => true, "redirect" => "dashboard"];

		$_SESSION["message"] = $message;
		header("Content-Type: application/json");
		echo json_encode($response);
		exit();
	}

	public function edit()
	{
		$id = trim($_POST["entity_id"]);
		$first_name = trim($_POST["first_name"]);
		$middle_name = trim($_POST["middle_name"]);
		$last_name = trim($_POST["last_name"]);
		$username = trim($_POST["username"]);
		$user_role = trim($_POST["user_role"]);
		$department = trim($_POST["department"]);
		$created_at = trim($_POST["created_at"] ?? "");
		$created_by = trim($_POST["created_by"] ?? "");
		$new_password = trim($_POST["password"] ?? "");

		if ($this->userModel->usernameExistsExceptCurrent($id, $username))
			$errors["username"] = "Username already taken.";
		if (empty($first_name))
			$errors["first_name"] = "First name is required.";
		if (empty($last_name))
			$errors["last_name"] = "Last name is required.";
		if (empty($username))
			$errors["username"] = "Username is required.";

		if (!empty($errors)) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		if (!empty($new_password))
			$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

		$updated = $this->userModel->update(
			$id,
			$first_name,
			$middle_name,
			$last_name,
			$username,
			$user_role,
			$department,
			$created_at,
			$created_by,
			$hashed_password ?? ''
		);

		if ($updated) {
			$message["success"] = "User updated successfully.";
			$response = ["success" => true, "redirect" => "dashboard"];
		} else {
			$message["error"] = "Failed to update user.";
			$response = ["success" => false, "redirect" => "dashboard"];
		}

		$_SESSION["message"] = $message;
		header("Content-Type: application/json");
		echo json_encode($response);
		exit();
	}

	public function delete()
	{
		$id = trim($_POST["entity_id"]);
		$message = [];
		$response = [];

		try {
			$deleted = $this->userModel->delete($id);
			if ($deleted) {
				$message["success"] = "User deleted successfully.";
				$response = ["success" => true, "redirect" => "dashboard"];
			} else {
				$message["error"] = "Failed to delete user.";
				$response = ["success" => false, "redirect" => "dashboard"];
			}
		} catch (PDOException $e) {
			switch ($e->getCode()) {
				case 23000:
					$message["error-title"] = "User In Use";
					$message["error"] = "Cannot delete user because it is referenced elsewhere.";
					break;

				default:
					$message["error-title"] = "Database Error";
					$message["error"] = $e->getMessage();
					break;
			}
			$response = ["success" => false, "redirect" => "dashboard"];
		}

		$_SESSION["message"] = $message;
		header("Content-Type: application/json");
		echo json_encode($response);
		exit();
	}
}