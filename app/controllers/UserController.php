<?php
namespace App\Controllers;
use App\Models\User;
use App\Services\DashboardService;
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

	// FOR KPIS

	public function getTotal()
	{
		$users = $this->userModel->getAll();
		return count($users);
	}

	// MAIN

	public function getAll()
	{
		return $this->userModel->getAll();
	}

	public function getById($id)
	{
		return $this->userModel->getById($id);
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

		if ($this->userModel->exists($username))
			$errors["username"] = "Username already taken.";
		if (empty($first_name))
			$errors["first_name"] = "First name is required.";
		if (empty($last_name))
			$errors["last_name"] = "Last name is required.";
		if (empty($username))
			$errors["username"] = "Username is required.";
		if (empty($password))
			$errors["password"] = "Password is required.";

		if (!empty($errors)) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$qr_code = bin2hex(random_bytes(32));
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		$created = $this->userModel->create(
			$first_name,
			$middle_name,
			$last_name,
			$username,
			$hashed_password,
			$qr_code,
			$user_role,
			$department,
			$created_by
		);
		$created ? $message["success"] = "User created successfully." : $message["error"] = "Failed to create user.";

		$_SESSION["message"] = $message;
		header("Location: dashboard");
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
		$current_password = trim($_POST["current_password"] ?? "");
		$new_password = trim($_POST["new_password"] ?? "");

		if ($this->userModel->exists($username))
			$errors["username"] = "Username already taken.";
		if (empty($first_name))
			$errors["first_name"] = "First name is required.";
		if (empty($last_name))
			$errors["last_name"] = "Last name is required.";
		if (empty($username))
			$errors["username"] = "Username is required.";
		if (empty($password))
			$errors["password"] = "Password is required.";

		if (!empty($errors)) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$updated = $this->userModel->update($id, $first_name, $middle_name, $last_name, $username, $user_role, $department, $created_at, $created_by);
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