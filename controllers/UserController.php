<?php
require_once "../models/User.php";
class UserController extends User
{
	private $userModel = "";

	public function __construct()
	{
		$this->userModel = new User();
	}

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

	public function login($username, $password)
	{
		$errors = [];

		if (!$username)
			$errors["username"] = "Please enter your username.";

		if (!$password)
			$errors["password"] = "Please enter your password.";

		if ($errors)
			return ["errors" => $errors];

		$userData = $this->userModel->getByUsername($username);

		if (!$userData || !password_verify($password, $userData["password"])) {
			// Error messages are vague so that pentesters won't try to
			// bruteforce usernames until it stops saying "User not found".
			$errors["general"] = "Incorrect credentials.";
			return ["errors" => $errors];
		}

		session_start();
		$_SESSION["user_id"] = $userData["id"];
		$_SESSION["username"] = $userData["username"];
		$_SESSION["role"] = $userData["role"];
		$_SESSION["first_name"] = $userData["first_name"];

		return ["success" => true];
	}

	public function logout()
	{
		session_start();
		session_unset();
		session_destroy();
		header("Location: index.php");
		exit;
	}
}