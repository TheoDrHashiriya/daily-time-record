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

	public function requireLogIn()
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

	// ADMIN-ONLY

	public function showAllUsers()
	{
		$this->requireAdmin();

		return $this->getAllUsers();
	}

	// OTHERS
	public function show($id)
	{
		return $this->getById($id);
	}

	public function register($first_name, $last_name, $middle_name, $username, $password)
	{
		if ($this->userExists($username)) {
			return ["error" => "Username already taken."];
		}

		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$created = $this->createUser($first_name, $last_name, $middle_name, $username, $hashedPassword);

		return $created ? ["success" => true] : ["error" => "Failed to create account."];
	}
}