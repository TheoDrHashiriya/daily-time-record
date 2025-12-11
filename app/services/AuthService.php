<?php
namespace App\Services;
use App\Models\User;
class AuthService
{
	private User $userModel;

	public function __construct(User $userModel)
	{
		$this->userModel = $userModel;
	}

	public static function isLoggedIn()
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

	public static function isAdmin()
	{
		return self::isLoggedIn() && isset($_SESSION["user_role"]) && $_SESSION["user_role"] === ROLE_ADMIN;
	}

	public function requireAdmin()
	{
		if (!$this->isAdmin()) {
			header("Location: .");
			exit();
		}
	}

	public function userNumberIsFromAdmin($user_number)
	{
		$user = $this->userModel->getByUserNumber($user_number);
		if (!$user)
			return false;
		return $user["user_role"] === 1;
	}

	public function getCurrentUser()
	{
		return $this->isLoggedIn()
			? $this->userModel->getById($_SESSION["user_id"])
			: null;
	}

	public function authenticateUserNumber($user_number)
	{
		if (!($user = $this->userModel->getByUserNumber($user_number)))
			return ["errors" => ["user_number" => "Incorrect user number."]];
		return ["success" => true, "user" => $user];
	}

	public function authenticateUsernamePassword(string $username, string $password)
	{
		$user = $this->userModel->getByUsername($username) ?? null;

		if (!$user || !password_verify($password, $user["hashed_password"]))
			// Error messages are vague so that pentesters won't try to
			// bruteforce usernames until it stops saying "User not found".
			return ["errors" => ["general" => "Incorrect credentials."]];

		return ["success" => true, "user" => $user];
	}

	public function authenticateQrCode($qr_code)
	{
		$id = $_SESSION["user_id"];

		if (!$id)
			return ["errors" => ["qr_code" => "Invalid user ID."]];

		$user = $this->userModel->getById($id);

		if (!$user)
			return ["errors" => ["qr_code" => "User not found."]];
		if ($user["qr_code"] !== $qr_code)
			return ["errors" => ["qr_code" => "Incorrect QR code."]];

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
	}
}