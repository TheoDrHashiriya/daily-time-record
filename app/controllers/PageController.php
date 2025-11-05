<?php
require_once __DIR__ . "/../controllers/EventRecordController.php";
require_once __DIR__ . "/../controllers/UserController.php";

class PageController
{
	private $erController = "";
	private $userController = "";

	public function __construct()
	{
		$this->erController = new ERController();
		$this->userController = new UserController();
	}

	public function redirectToHome()
	{
		header("Location: .");
		exit();
	}

	public function home()
	{
		$records = $this->erController->getAll();
		$authData = $this->authenticate();
		$errors = $authData["errors"] ?? [];
		$username = $authData["username"] ?? [];
		require __DIR__ . "/../views/home.php";
	}

	public function timeIn()
	{
		$this->userController->requireLogin();
		$this->erController->timeIn($_SESSION["user_id"]);
		$this->redirectToHome();
	}

	public function timeOut()
	{
		$this->userController->requireLogin();
		$this->erController->timeOut($_SESSION["user_id"]);
		$this->redirectToHome();
	}

	public function authenticate()
	{
		$errors = [];
		$username = "";

		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			$username = trim($_POST["username"]);
			$password = trim($_POST["password"]);

			$result = $this->userController->authenticate($username, $password);

			if (isset($result["errors"]))
				$errors = $result["errors"];
			elseif (isset($result["success"])) {
				$userData = $result["user"];
				$_SESSION["user_id"] = $userData["id"];
				$_SESSION["user_role"] = $userData["user_role"];
				$_SESSION["username"] = $userData["username"];
				$_SESSION["first_name"] = $userData["first_name"];
				$_SESSION["is_logged_in"] = true;

				$_SESSION["has_timed_in_today"] = $this->erController->hasTimedInToday($_SESSION["user_id"]) ?? false;
				$_SESSION["has_timed_out_today"] = $this->erController->hasTimedOutToday($_SESSION["user_id"]) ?? false;
				$hasTimedInToday = $_SESSION["has_timed_in_today"];
				$hasTimedOutToday = $_SESSION["has_timed_out_today"];

				if ($this->userController->isAdmin()) {
					$this->dashboard();
					exit();
				}

				if (!$hasTimedInToday)
					$this->timeIn();
				elseif (!$hasTimedOutToday)
					$this->timeOut();

				// $this->logout();
				exit();
			}
		}
		return ["errors" => $errors, "username" => $username];
	}

	public function logout()
	{
		session_start();
		session_unset();
		session_destroy();
		$this->redirectToHome();
		exit();
	}

	// ADMIN FUNCTIONS

	public function redirectToDashboard()
	{
		header("Location: dashboard");
		exit();
	}

	public function dashboard()
	{
		$this->userController->requireLogin();
		$this->userController->requireAdmin();
		$records = $this->erController->getAll();
		$users = $this->userController->getAll();
		require __DIR__ . "/../views/admin/dashboard.php";
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
			$user_role = trim($_POST["user_role"] ?? "");

			if (!$first_name)
				$errors["first_name"] = "Please enter your first name.";

			if (!$last_name)
				$errors["last_name"] = "Please enter your last name.";

			if (!$username)
				$errors["username"] = "Please enter your username.";

			if (!$password)
				$errors["password"] = "Please enter your password.";

			if (empty($user_role))
				$errors["user_role"] = "Please enter your role.";

			if (empty($errors)) {
				$result = $this->userController->register(
					$first_name,
					$last_name,
					$middle_name,
					$username,
					$password,
					$user_role
				);

				if (isset($result["success"])) {
					$this->redirectToDashboard();
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
			$user_role = trim($_POST["user_role"] ?? $user["user_role"]);

			if (!$first_name)
				$errors["first_name"] = "Please enter your first name.";

			if (!$last_name)
				$errors["last_name"] = "Please enter your last name.";

			if (!$username)
				$errors["username"] = "Please enter your username.";

			if (!$password)
				$errors["password"] = "Please enter your password.";

			if (empty($user_role))
				$errors["user_role"] = "Please enter your role.";

			$result = $this->userController->update(
				$id,
				$first_name,
				$last_name,
				$middle_name,
				$username,
				$password,
				$user_role
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
		$this->erController->deleteAllFromUser($id);
		$this->userController->delete($id);
		$this->redirectToDashboard();
		exit();
	}

	// RECORDS

	public function deleteRecord($id)
	{
		$this->userController->requireLogin();
		$this->userController->requireAdmin();
		$this->erController->delete($id);
		$this->redirectToDashboard();
		exit();
	}
}