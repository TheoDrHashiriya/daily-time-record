<?php
namespace App\Controllers;


class PageController
{
	private $erController;
	private $userController;
	private $notifController;
	private $depController;

	public function __construct()
	{
		$this->erController = new EventRecordController();
		$this->userController = new UserController();
		$this->notifController = new NotificationController();
		$this->depController = new DepartmentController();
	}

	public function previewAllEventsPdf()
	{
		AuthService::requireLogin();
		AuthService::requireAdmin();

		$records = $this->erController->getAll();
		PdfService::streamPdf(
			"all-events.pdf",
			["partials/pdf-styles.php", "partials/records-table.php"],
			["records" => $records]
		);
		exit();
	}

	public function previewAllNotificationsPdf()
	{
		AuthService::requireLogin();
		AuthService::requireAdmin();

		$notifications = $this->notifController->getAll();
		PdfService::streamPdf(
			"all-notifications.pdf",
			["partials/pdf-styles.php", "partials/notifications.table.php"],
			["notifications" => $notifications]
		);
		exit();
	}

	public function previewAllDepartmentsPdf()
	{
		AuthService::requireLogin();
		AuthService::requireAdmin();

		$departments = $this->depController->getAll();
		PdfService::streamPdf(
			"all-departments.pdf",
			["partials/pdf-styles.php", "partials/departments.table.php"],
			["departments" => $departments]
		);
		exit();
	}

	public function previewAllUsersPdf()
	{
		AuthService::requireLogin();
		AuthService::requireAdmin();

		$users = $this->userController->getAll();
		PdfService::streamPdf(
			"all-users.pdf",
			["partials/pdf-styles.php", "partials/users.table.php"],
			["users" => $users]
		);
		exit();
	}

	// MAIN

	public function redirectToHome()
	{
		header("Location: .");
		exit();
	}

	// public function home()
	// {
	// 	$records = $this->erController->getAll();
	// 	$authData = $this->authenticate();
	// 	$errors = $authData["errors"] ?? [];
	// 	$username = $authData["username"] ?? [];
	// 	require __DIR__ . "/../views/home.php";
	// }

	public function timeIn()
	{
		AuthService::requireLogin();
		$this->erController->timeIn($_SESSION["user_id"]);

		$now = GlobalHelper::getCurrentDate();
		$this->notifController->create(
			"Time In",
			$_SESSION["username"] . " has timed in on " . GlobalHelper::formatDate($now) . ", at " . GlobalHelper::formatTime($now) . ".",
			$_SESSION["user_id"]
		);
	}

	public function timeOut()
	{
		AuthService::requireLogin();
		$this->erController->timeOut($_SESSION["user_id"]);

		$now = GlobalHelper::getCurrentDate();
		$this->notifController->create(
			"Time Out",
			$_SESSION["username"] . " has timed out on " . GlobalHelper::formatDate($now) . ", at " . GlobalHelper::formatTime($now) . ".",
			$_SESSION["user_id"]
		);
	}

	// ADMIN FUNCTIONS

	public function redirectToDashboard()
	{
		header("Location: dashboard");
		exit();
	}

	public function dashboard()
	{
		AuthService::requireLogin();
		AuthService::requireAdmin();
		$records = $this->erController->getAll();
		$users = $this->userController->getAll();
		$notifications = $this->notifController->getAll();
		$notifications_unread = $this->notifController->getAllUnread();
		$departments = $this->depController->getAll();
		$kpiData = $this->getKpiData();
		require __DIR__ . "/../views/admin/dashboard.php";
		exit();
	}

	public function getKpiData()
	{
		$kpiData["events_total"] = $this->erController->getTotal();
		$kpiData["events_unclosed"] = $this->erController->getTotalUnclosed();
		$kpiData["users_total"] = $this->userController->getTotal();
		$kpiData["notifications_total"] = $this->notifController->getTotal();
		$kpiData["departments_total"] = $this->depController->getTotal();
		return $kpiData;
	}

	// USERS

	public function register()
	{
		AuthService::requireLogin();
		AuthService::requireAdmin();

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
		AuthService::requireLogin();
		AuthService::requireAdmin();

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
		AuthService::requireLogin();
		AuthService::requireAdmin();
		$this->erController->deleteAllFromUser($id);
		$this->userController->delete($id);
		$this->redirectToDashboard();
		exit();
	}

	// RECORDS

	public function editRecord()
	{
	}

	public function deleteRecord($id)
	{
		AuthService::requireLogin();
		AuthService::requireAdmin();
		$this->erController->delete($id);
		$this->redirectToDashboard();
		exit();
	}

	// NOTIFICATIONS
	public function deleteNotification($id)
	{
		AuthService::requireLogin();
		AuthService::requireAdmin();
		$this->notifController->delete($id);
		$this->redirectToDashboard();
		exit();
	}
}