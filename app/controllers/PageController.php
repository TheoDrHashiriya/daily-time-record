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

	public function redirectToHome()
	{
		header("Location: .");
		exit();
	}

	public function dashboard()
	{
		$_SESSION["is_logged_in"] = $this->userController->isLoggedIn();

		if ($_SESSION["is_logged_in"]) {
			$userRole = $_SESSION["role"];
			$userId = $_SESSION["user_id"];

			$users = [];
			$records = [];

			if ($userRole === "employee")
				$records = $this->dtrController->getByUserId($userId);

			if ($userRole === "admin" || $userRole === "manager") {
				$users = $this->userController->getAll();
				$records = $this->dtrController->getAll();
			}
		}
		require __DIR__ . "/../views/dashboard.php";
	}

	public function timeIn()
	{
		$this->userController->requireLogin();
		$this->dtrController->timeIn($_SESSION["user_id"]);
		$this->redirectToHome();
	}

	public function timeOut()
	{
		$this->userController->requireLogin();
		$this->dtrController->timeOut($_SESSION["user_id"]);
		$this->redirectToHome();
	}

	public function login()
	{
		if ($this->userController->isLoggedIn()) {
			$this->redirectToHome();
			exit();
		}

		$errors = [];

		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			$username = trim($_POST["username"]);
			$password = trim($_POST["password"]);

			$result = $this->userController->authenticate($username, $password);

			if (isset($result["errors"]))
				$errors = $result["errors"];
			elseif (isset($result["success"])) {
				$userData = $result["user"];

				if (session_status() === PHP_SESSION_NONE)
					session_start();

				$_SESSION["user_id"] = $userData["id"];
				$_SESSION["username"] = $userData["username"];
				$_SESSION["role"] = $userData["role"];
				$_SESSION["first_name"] = $userData["first_name"];
				$_SESSION["is_logged_in"] = true;

				$_SESSION["has_timed_in_today"] = $this->dtrController->hasTimedInToday($_SESSION["user_id"]) ?? false;
				$_SESSION["has_timed_out_today"] = $this->dtrController->hasTimedOutToday($_SESSION["user_id"]) ?? false;

				$this->redirectToHome();
				exit();
			}
		}

		require __DIR__ . "/../views/login.php";
	}

	public function logout()
	{
		session_start();
		session_unset();
		session_destroy();
		$this->redirectToHome();
		exit();
	}

	// USERS

	public function register()
	{
		$this->userController->requireLogin();
		$this->userController->requireAdmin();

		$errors = [];
		$success = false;

		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			$first_name = trim($_POST["first_name"]);
			$last_name = trim($_POST["last_name"]);
			$middle_name = trim($_POST["middle_name"] ?? "");
			$username = trim($_POST["username"]);
			$password = trim($_POST["password"]);
			$role = trim($_POST["role"] ?? "");

			if (!$first_name)
				$errors["first_name"] = "Please enter your first name.";

			if (!$last_name)
				$errors["last_name"] = "Please enter your last name.";

			if (!$username)
				$errors["username"] = "Please enter your username.";

			if (!$password)
				$errors["password"] = "Please enter your password.";

			if (empty($role))
				$errors["role"] = "Please enter your role.";

			if (empty($errors)) {
				$result = $this->userController->register(
					$first_name,
					$last_name,
					$middle_name,
					$username,
					$password,
					$role
				);

				if (isset($result["success"])) {
					$this->redirectToHome();
					exit();
				} else {
					$errors["general"] = $result["error"];
				}
			}
		}

		require __DIR__ . "/../views/admin/register.php";
	}

	public function editUser($id)
	{
		$this->userController->requireLogin();
		$this->userController->requireAdmin();

		$user = $this->userController->getById($id);

		// echo "User data:\n<prev>";
		// foreach ($user as $key => $value)
		// 	echo "| $key = $value \n";
		// echo "</prev>";

		$errors = [];
		$success = false;

		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			$first_name = trim($_POST["first_name"] ?? $user["first_name"]);
			$last_name = trim($_POST["last_name"] ?? $user["last_name"]);
			$middle_name = isset($_POST["middle_name"]) ? trim($_POST["middle_name"] ?? $user["middle_name"]) : "";
			$username = trim($_POST["username"] ?? $user["username"]);
			$password = trim($_POST["password"] ?? $user["password"]);
			$role = trim($_POST["role"] ?? $user["role"]);

			if (!$first_name)
				$errors["first_name"] = "Please enter your first name.";

			if (!$last_name)
				$errors["last_name"] = "Please enter your last name.";

			if (!$username)
				$errors["username"] = "Please enter your username.";

			if (!$password)
				$errors["password"] = "Please enter your password.";

			if (empty($role))
				$errors["role"] = "Please enter your role.";

			$result = $this->userController->update(
				$id,
				$first_name,
				$last_name,
				$middle_name,
				$username,
				$password,
				$role
			);

			// if (isset($result["success"])) {
			// 	$this->redirectToHome();
			// 	exit();
			// } else {
			// 	$errors["general"] = $result["error"];
			// }
		}
		require __DIR__ . "/../views/admin/edit-user.php";
	}

	public function deleteUser($id)
	{
		$this->userController->requireLogin();
		$this->userController->requireAdmin();
		$this->dtrController->deleteAllFromUser($id);
		$this->userController->delete($id);
		$this->redirectToHome();
		exit();
	}

	// RECORDS

	public function deleteRecord($id)
	{
		$this->userController->requireLogin();
		$this->userController->requireAdmin();
		$this->dtrController->delete($id);
		$this->redirectToHome();
		exit();
	}
}