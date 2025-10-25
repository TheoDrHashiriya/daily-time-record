<?php
require_once __DIR__ . "/../controllers/DailyTimeRecordController.php";
require_once __DIR__ . "/../controllers/UserController.php";

class PageController
{
	private $dtrController = "";
	private $userController = "";

	public function __construct()
	{
		$this->dtrController = new DTRController();
		$this->userController = new UserController();

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

		$userData = $this->userController->getByUsername($username);

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
		header("Location: .");
		exit();
	}

	public function homePage()
	{
		if ($this->userController->isLoggedIn()) {
			$userRole = $_SESSION["role"];
			$userId = $_SESSION["user_id"];

			$isLoggedin = $this->userController->isLoggedIn();
			$hasTimedIn = $this->dtrController->hasTimedInToday($userId);

			$records = [];
			$users = [];

			if ($userRole === "employee")
				$records = $this->dtrController->getByUserId($userId);

			if ($userRole === "admin")
				$users = $this->userController->showAll();

			if ($userRole === "admin" || $userRole === "manager")
				$records = $this->dtrController->getAll();
		}

		require __DIR__ . "/../views/index.php";
	}

	public function timeIn()
	{
		$this->userController->requireLogin();

		if ($this->dtrController->hasTime) {

		}
	}

	public function loginPage()
	{
		$this->userController->requireLogin();

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

		require __DIR__ . "/../views/login.php";
	}

	public function registerPage()
	{
		$this->userController->requireLogin();
		$this->userController->requireAdmin();

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
				$result = $this->userController->register(
					$first_name,
					$last_name,
					$middle_name,
					$username,
					$password
				);

				if (isset($result["success"])) {
					header("Location: login");
					exit;
				} else {
					$errors["general"] = $result["error"];
				}
			}
		}

		require __DIR__ . "/../views/register.php";
	}

	public function updateUserPage()
	{
		$this->userController->requireLogin();
		$this->userController->requireAdmin();

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
				$id = $_SESSION["user_id"];
				$result = $this->userController->updateUser(
					$id,
					$first_name,
					$last_name,
					$middle_name,
					$username,
					$password
				);

				if (isset($result["success"])) {
					header("Location: .");
					exit;
				} else {
					$errors["general"] = $result["error"];
				}
			}
		}
	}
}