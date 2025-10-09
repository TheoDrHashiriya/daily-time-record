<?php
require_once "../models/User.php";
class UserController extends User
{
	private $userModel="";
	public function __construct()
	{
		$this->userModel = new User();
	}
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
		$created = $this->createUser($first_name, $last_name, $middle_name, $username, $password);

		return $created ? ["success" => true] : ["error" => "Failed to create account."];
	}
}