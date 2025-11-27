<?php
namespace App\Services;
use App\Models\User;
class AuthService
{
	private $userModel;

	public function __construct(User $userModel)
	{
		$this->userModel = $userModel;
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
		return $this->isLoggedIn() && $_SESSION["user_role"] === 1;
	}

	public function requireAdmin()
	{
		if (!$this->isAdmin()) {
			header("Location: .");
			exit();
		}
	}

	public function getCurrentUser()
	{
		return $this->isLoggedIn()
			? $this->userModel->getById($_SESSION["user_id"])
			: null;
	}

	public function authenticate(string $username, string $password)
	{
		$user = $this->userModel->getByUsername($username);

		if (!$user || !password_verify($password, $user["hashed_password"])) {
			// Error messages are vague so that pentesters won't try to
			// bruteforce usernames until it stops saying "User not found".
			return ["errors" => ["general" => "Incorrect credentials"]];
		}

		return ["success" => true, "user" => $user];
	}

	public function login(array $user)
	{
		$_SESSION["user_id"] = $user["id"];
		$_SESSION["user_role"] = $user["user_role"];
		$_SESSION["username"] = $user["username"];
		$_SESSION["first_name"] = $user["first_name"];
		$_SESSION["is_logged_in"] = true;
	}

	public static function logout()
	{
		session_unset();
		session_destroy();
		exit();
	}
}