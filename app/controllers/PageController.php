<?php
require_once __DIR__ . "/../controllers/EventRecordController.php";
require_once __DIR__ . "/../controllers/UserController.php";
require_once __DIR__ . "/../controllers/NotificationController.php";
require_once __DIR__ . "/../controllers/DepartmentController.php";

class PageController
{
	private $erController;
	private $userController;
	private $notifController;
	private $depController;

	public function __construct()
	{
		$this->erController = new ERController();
		$this->userController = new UserController();
		$this->notifController = new NotificationController();
		$this->depController = new DepartmentController();
	}

	public function previewAllEventsPdf()
	{
		$this->userController->requireLogin();
		$this->userController->requireAdmin();

		$records = $this->erController->getAll();

		ob_start();
		require __DIR__ . "/../views/partials/pdf-styles.php";
		require __DIR__ . "/../views/partials/records-table.php";
		$html = ob_get_clean();

		$dompdf = PdfHelper::generatePdfString($html);
		$dompdf->stream("all-events.pdf", ["Attachment" => false]);
		exit();
	}

	public function previewAllNotificationsPdf()
	{
		$this->userController->requireLogin();
		$this->userController->requireAdmin();

		$notifications = $this->notifController->getAll();

		ob_start();
		require __DIR__ . "/../views/partials/pdf-styles.php";
		require __DIR__ . "/../views/partials/notifications-table.php";
		$html = ob_get_clean();

		$dompdf = PdfHelper::generatePdfString($html);
		$dompdf->stream("all-notifications.pdf", ["Attachment" => false]);
		exit();
	}

	public function previewAllDepartmentsPdf()
	{
		$this->userController->requireLogin();
		$this->userController->requireAdmin();

		$departments = $this->depController->getAll();

		ob_start();
		require __DIR__ . "/../views/partials/pdf-styles.php";
		require __DIR__ . "/../views/partials/departments-table.php";
		$html = ob_get_clean();

		$dompdf = PdfHelper::generatePdfString($html);
		$dompdf->stream("all-departments.pdf", ["Attachment" => false]);
		exit();
	}

	public function previewAllUsersPdf()
	{
		$this->userController->requireLogin();
		$this->userController->requireAdmin();

		$users = $this->userController->getAll();

		ob_start();
		require __DIR__ . "/../views/partials/pdf-styles.php";
		require __DIR__ . "/../views/partials/users-table.php";
		$html = ob_get_clean();

		$dompdf = PdfHelper::generatePdfString($html);
		$dompdf->stream("all-users.pdf", ["Attachment" => false]);
		exit();
	}

	// MAIN

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

		$now = GlobalHelper::getCurrentDate();
		$this->notifController->create(
			"Time In",
			$_SESSION["username"] . " has timed in on " . GlobalHelper::formatDate($now) . ", at " . GlobalHelper::formatTime($now) . ".",
			$_SESSION["user_id"]
		);
	}

	public function timeOut()
	{
		$this->userController->requireLogin();
		$this->erController->timeOut($_SESSION["user_id"]);

		$now = GlobalHelper::getCurrentDate();
		$this->notifController->create(
			"Time Out",
			$_SESSION["username"] . " has timed out on " . GlobalHelper::formatDate($now) . ", at " . GlobalHelper::formatTime($now) . ".",
			$_SESSION["user_id"]
		);
	}

	public function authenticate()
	{
		$errors = [];
		$username = '';

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
				else
					$this->timeOut();

				$this->redirectToHome();

				// $this->logout();
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
		$notifications = $this->notifController->getAll();
		$notifications_unread = $this->notifController->getAllUnread();
		$departments = $this->depController->getAll();
		$kpiData = $this->getKpiData();
		require __DIR__ . "/../views/admin/dashboard.php";
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

	public function editRecord()
	{
	}

	public function deleteRecord($id)
	{
		$this->userController->requireLogin();
		$this->userController->requireAdmin();
		$this->erController->delete($id);
		$this->redirectToDashboard();
		exit();
	}

	// NOTIFICATIONS
	public function deleteNotification($id)
	{
		$this->userController->requireLogin();
		$this->userController->requireAdmin();
		$this->notifController->delete($id);
		$this->redirectToDashboard();
		exit();
	}
}