<?php
require_once __DIR__ . "/../models/User.php";
class UserController extends User
{
	private $userModel = "";

	public function __construct()
	{
		$this->userModel = new User();
	}

	// PAGE RENDERERS

	public function homePage()
	{
		if (isset($_SESSION["user_id"])) {
			$dtrController = new DTRController();
			$userId = $_SESSION["user_id"];

			if ($_SESSION["role"] === "employee")
				$records = $dtrController->dashboard($userId);

			if ($_SESSION["role"] === "admin")
				$records = $dtrController->getAllRecords();
		}

		require "views/index.php";
	}

	public function loginPage()
	{
		if (isset($_SESSION["user_id"])) {
			header("Location: /");
			exit;
		}

		$errors = [];

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$username = trim($_POST["username"]);
			$password = trim($_POST["password"]);

			$result = $this->login($username, $password);

			if (isset($result["errors"])) {
				$errors = $result["errors"];
			}

			if (isset($result["success"])) {
				header("Location: .");
				exit;
			}
		}

		require "views/login.php";
	}

	public function registerPage()
	{
		if (isset($_SESSION["user_id"])) {
			header("Location: /");
			exit;
		}

		$errors = [];
		$success = false;

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$first_name = trim($_POST["first_name"]);
			$last_name = trim($_POST["last_name"]);
			$middle_name = trim($_POST["middle_name"] ?? "");
			$username = trim($_POST["username"]);
			$password = trim($_POST["password"]);

			if (!$first_name)
				$errors["first_name"] = "Please enter your first name.";

			if (!$last_name)
				$errors["last_name"] = "Please enter your last name.";

			if (!$username)
				$errors["username"] = "Please enter your username.";

			if (!$password)
				$errors["password"] = "Please enter your password.";

			if (empty($errors)) {
				$result = $this->register($first_name, $last_name, $middle_name, $username, $password);

				if (isset($result["success"])) {
					header("Location: login");
					exit;
				} else {
					$errors["general"] = $result["error"];
				}
			}
		}

		require "views/register.php";
	}

	public function updatePage()
	{
		if (!isset($_SESSION["user_id"])) {
			header("Location: /");
			exit;
		}

		$errors = [];
		$success = false;

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$first_name = trim($_POST["first_name"]);
			$last_name = trim($_POST["last_name"]);
			$middle_name = trim($_POST["middle_name"] ?? "");
			$username = trim($_POST["username"]);
			$password = trim($_POST["password"]);

			if (!$first_name)
				$errors["first_name"] = "Please enter your first name.";

			if (!$last_name)
				$errors["last_name"] = "Please enter your last name.";

			if (!$username)
				$errors["username"] = "Please enter your username.";

			if (!$password)
				$errors["password"] = "Please enter your password.";

			if (empty($errors)) {
				$id = $_SERVER["user_id"];
				$result = $this->updateUser($id, $first_name, $last_name, $middle_name, $username, $password);

				if (isset($result["success"])) {
					header("Location: /");
					exit;
				} else {
					$errors["general"] = $result["error"];
				}
			}
		}
	}

	// FUNCTIONS

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
		header("Location: " . BASE_URL);
		exit;
	}
}