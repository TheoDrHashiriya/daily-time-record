<?php
namespace App\Controllers;
use App\Models\User;

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

	public function register($first_name, $last_name, $middle_name, $username, $password, $role)
	{
		if ($this->userModel->exists($username))
			return ["error" => "Username already taken."];

		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$created = $this->userModel->create($first_name, $last_name, $middle_name, $username, $hashedPassword, $role);
		return $created ? ["success" => true] : ["error" => "Failed to create account."];
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

		$this->userModel->update(
			$id,
			$first_name,
			$middle_name,
			$last_name,
			$username,
			$user_role,
			$department,
			$created_at
		);

		$created_by = trim($_POST["created_by"] ?? "");

		if ($created_by !== "")
			$this->userModel->updateCreator($id, $created_by);

		header("Location: dashboard");
		exit();
	}

	public function delete()
	{
		$id = trim($_POST["entity_id"]);
		$this->userModel->delete($id);
		header("Location: dashboard");
		exit();
	}
}