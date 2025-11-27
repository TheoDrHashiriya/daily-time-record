<?php
namespace App\Controllers;
use App\Models\User;

class UserController extends Controller
{
	private $userModel;

	public function __construct()
	{
		$this->userModel = new User();
	}

	// FOR KPIS

	public function getTotal()
	{
		$users = $this->userModel->getAll();
		return count($users);
	}

	// MAIN

	public function getAll()
	{
		return $this->userModel->getAll();
	}

	public function getById($id)
	{
		return $this->userModel->getById($id);
	}

	public function register($first_name, $last_name, $middle_name, $username, $password, $role)
	{
		if ($this->userModel->exists($username))
			return ["error" => "Username already taken."];

		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$created = $this->userModel->create($first_name, $last_name, $middle_name, $username, $hashedPassword, $role);
		return $created ? ["success" => true] : ["error" => "Failed to create account."];
	}

	public function update($id, $first_name, $last_name, $middle_name, $username, $password, $role)
	{
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$updated = $this->userModel->update($id, $first_name, $last_name, $middle_name, $username, $hashedPassword, $role);
		return $updated ? ["success" => true] : ["error" => "Failed to update account."];
	}

	public function delete($id)
	{
		return $this->userModel->delete($id);
	}
}