<?php
require_once __DIR__ . "/../models/User.php";
class UserController extends User
{
	private $userModel = "";

	public function __construct()
	{
		$this->userModel = new User();
	}

	public function isLoggedIn()
	{
		return isset($_SESSION["user_id"]);
	}

	public function requireLogin()
	{
		if (!$this->isLoggedIn()) {
			header("Location: .");
			exit();
		}
	}

	public function isAdmin()
	{
		return $this->isLoggedIn() && $_SESSION["role"] === "admin";
	}

	public function requireAdmin()
	{
		if (!$this->isAdmin()) {
			header("Location: .");
			exit();
		}
	}

	public function authenticate($username, $password)
	{
		$errors = [];

		if (!$username)
			$errors["username"] = "Please enter your username.";

		if (!$password)
			$errors["password"] = "Please enter your password.";

		if ($errors)
			return ["errors" => $errors];

		$userData = $this->getByUsername($username);

		if (!$userData || !password_verify($password, $userData["password"])) {
			// Error messages are vague so that pentesters won't try to
			// bruteforce usernames until it stops saying "User not found".
			$errors["general"] = "Incorrect credentials.";
			return ["errors" => $errors];
		}

		return ["success" => true, "user" => $userData];
	}

	public function getAll()
	{
		$this->requireAdmin();
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

	public function update($id, $first_name, $last_name, $middle_name, $username, $password, $role)
	{
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$updated = $this->userModel->update($id, $first_name, $last_name, $middle_name, $username, $hashedPassword, $role);
		return $updated ? ["success" => true] : ["error" => "Failed to update account."];
	}

	public function delete($id)
	{
		return $this->userModel->delete($id);
	}
}