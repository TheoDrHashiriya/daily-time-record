<?php
namespace App\Controllers;
use App\Models\User;
use App\Models\EventRecord;
use App\Services\AuthService;
use App\Services\HomeService;

class HomeController extends Controller
{
	private $authService;
	private $homeService;
	private $erModel;

	public function __construct()
	{
		$this->authService = new AuthService(new User);
		$this->homeService = new HomeService;
		$this->erModel = new EventRecord();
	}

	public function index()
	{
		$data = $this->homeService->getAllData();
		$this->renderView(["home.php"], $data);
	}

	public function logout()
	{
		header("Location: .");
		$this->authService->logout();
		exit();
	}

	public function processLogin()
	{
		$username = trim($_POST["username"] ?? "");
		$password = trim($_POST["password"] ?? "");
		$errors = [];

		// Validate user input
		if ($username === "")
			$errors["username"] = "Please enter your username.";
		if ($password === "")
			$errors["password"] = "Please enter your password.";

		if ($errors) {
			$data = array_merge(
				$this->homeService->getAllData(),
				["errors" => $errors, "username" => $username]
			);
			$this->renderView(["home.php"], $data);
			return;
		}

		// Authenticate
		$result = $this->authService->authenticate($username, $password);

		if (isset($result["errors"])) {
			$errors = $result["errors"];
			$data = array_merge(
				$this->homeService->getAllData(),
				["errors" => $errors, "username" => $username]
			);
			$this->renderView(["home.php"], $data);
			return;
		}

		// Login / Session start
		$user = $result["user"];
		$this->authService->login($user);

		// Event Attendance
		$_SESSION["has_timed_in_today"] = $this->erModel->hasTimedInToday($_SESSION["user_id"]) ?? false;
		$_SESSION["has_timed_out_today"] = $this->erModel->hasTimedOutToday($_SESSION["user_id"]) ?? false;

		// Redirect based on role
		if ($this->authService->isAdmin()) {
			header("Location: dashboard");
			exit();
		}
		exit();
	}
}