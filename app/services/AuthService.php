<?php
class AuthService
{
	public static function isLoggedIn()
	{
		return isset($_SESSION["user_id"]);
	}

	public static function requireLogin()
	{
		if (!self::isLoggedIn()) {
			header("Location: .");
			exit();
		}
	}

	public static function isAdmin()
	{
		return self::isLoggedIn() && $_SESSION["user_role"] === 1;
	}

	public static function requireAdmin()
	{
		if (!self::isAdmin()) {
			header("Location: .");
			exit();
		}
	}

	public static function authenticate($username, $password)
	{
		$errors = [];

		if (!$username)
			$errors["username"] = "Please enter your username.";
		if (!$password)
			$errors["password"] = "Please enter your password.";
		if ($errors)
			return ["errors" => $errors];

		$userModel = new User();
		$userData = $userModel->getByUsername($username);

		if (!$userData || !password_verify($password, $userData["hashed_password"])) {
			// Error messages are vague so that pentesters won't try to
			// bruteforce usernames until it stops saying "User not found".
			$errors["general"] = "Incorrect credentials.";
			return ["errors" => $errors];
		}

		return ["success" => true, "user" => $userData];
	}
}