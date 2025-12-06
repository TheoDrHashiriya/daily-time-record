<?php
namespace App\Controllers;
use App\Models\User;
use PDOException;

class UserController extends Controller
{
	private $userModel;

	public function __construct()
	{
		$this->userModel = new User();
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

		$errors = [];

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

		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		$created = $this->userModel->create(
			$first_name,
			$middle_name,
			$last_name,
			$username,
			$hashed_password,
			$user_role,
			$department,
			$created_by
		);

		header("Location: dashboard");
		header("Content-Type: application/json");
		echo json_encode(["success" => $created, "message" => $created ? "User created successfully." : "Failed to create user."]);
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
		$updated ? $message["success"] = "User updated successfully." : $message["error"] = "User to update notification.";

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
					$message["error"] = "Cannot delete user because it is referenced elsewhere.";
					break;

				default:
					$message["error"] = "Database error: " . $e->getMessage();
					break;
			}
		}

		$_SESSION["message"] = $message;
		header("Location: dashboard");
		exit();
	}
}