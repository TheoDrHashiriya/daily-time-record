<?php
namespace App\Controllers;
use App\Models\User;
use App\Services\FormatService;
use App\Services\DashboardService;
use App\Services\QRCodeService;
use PDOException;
use PrintService;

class UserController extends Controller
{
	private DashboardService $dashboardService;
	private User $userModel;

	public function __construct(User $userModel, DashboardService $dashboardService)
	{
		$this->dashboardService = $dashboardService;
		$this->userModel = $userModel;
	}

	public function streamToPdf()
	{
		$users = $this->dashboardService->getUsers();
		PrintService::streamPdf(
			"all-events.pdf",
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
			"qr-code-" . FormatService::formatPdfName(strtolower($user["full_name_formatted"])) . ".pdf",
			["components/pdf/modals", "components/modals/user-qr"],
			["user" => $user]
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
		$user_id = $this->userModel->create(
			$first_name,
			$last_name,
			$middle_name,
			$username,
			$hashed_password,
			$user_role,
			$department,
			$created_by
		);
		$user_id ? $message["success"] = "User created successfully." : $message["error"] = "Failed to create user.";

		do
			$qr_string = bin2hex(random_bytes(32));
		while ($this->userModel->qrStringExists($qr_string));
		$this->userModel->generateQrString($user_id, $qr_string);

		$_SESSION["message"] = $message;
		header("Location: dashboard");
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
		$updated ? $message["success"] = "User updated successfully." : $message["error"] = "Failed to update user.";

		$_SESSION["message"] = $message;
		header("Location: dashboard");
		exit();
	}

	public function delete()
	{
		$id = trim($_POST["entity_id"]);
		$message = [];

		try {
			$deleted = $this->userModel->delete($id);
			$deleted ? $message["success"] = "User deleted successfully." : $message["error"] = "Failed to delete user.";
		} catch (PDOException $e) {
			switch ($e->getCode()) {
				case 23000:
					$message["error-title"] = "User In Use";
					$message["error"] = "Cannot delete user because it is referenced elsewhere.";
					$_SESSION["message"] = $message;
					header("Location: dashboard");
					break;

				default:
					$message["error-title"] = "Database Error";
					$message["error"] = $e->getMessage();
					break;
			}
		}

		$_SESSION["message"] = $message;
		header("Location: dashboard");
		exit();
	}
}