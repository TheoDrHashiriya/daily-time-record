<?php
namespace App\Controllers;
use App\Models\Department;

class DepartmentController extends Controller
{
	private Department $departmentModel;

	public function __construct(Department $departmentModel)
	{
		$this->departmentModel = $departmentModel;
	}

	// FOR KPIS
	public function getTotal()
	{
		$departments = $this->departmentModel->getAll();
		return count($departments);
	}

	// MAIN

	public function getAll()
	{
		return $this->departmentModel->getAll();
	}

	public function getById($id)
	{
		return $this->departmentModel->getById($id);
	}

	public function create()
	{
		$department_name = trim($_POST["department_name"]);
		$abbreviation = trim($_POST["abbreviation"]);

		if ($this->departmentModel->nameExists($department_name))
			$errors["department_name"] = "Department name already taken.";
		if ($this->departmentModel->abbreviationExists($abbreviation))
			$errors["abbreviation"] = "Abbreviation already taken.";
		if (empty($department_name))
			$errors["department_name"] = "Department name is required.";
		if (empty($abbreviation))
			$errors["abbreviation"] = "Abbreviation is required.";

		if (!empty($errors)) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$created = $this->departmentModel->create($department_name);
		$created ? $message["success"] = "Department created successfully." : $message["error"] = "Failed to create department.";

		$_SESSION["message"] = $message;

		header("Location: dashboard");
		exit();
	}

	public function edit()
	{
		$id = trim($_POST["entity_id"]);
		$department_name = trim($_POST["department_name"]);
		$abbreviation = trim($_POST["abbreviation"]);

		if (empty($department_name))
			$errors["department_name"] = "Department name is required.";
		if (empty($abbreviation))
			$errors["abbreviation"] = "Abbreviation is required.";

		if (!empty($errors)) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$updated = $this->departmentModel->update($id, $department_name);
		$updated ? $message["success"] = "Department updated successfully." : $message["error"] = "Failed to update department.";

		$_SESSION["message"] = $message;

		header("Location: dashboard");
		exit();
	}

	public function delete()
	{
		$id = trim($_POST["entity_id"]);

		$isInUse = $this->departmentModel->isInUse($id);
		if ($isInUse) {
			$message["error"] = "Cannot delete a department in use.";
			$_SESSION["message"] = $message;
			header("Location: dashboard");
			exit();
		}

		$deleted = $this->departmentModel->delete($id);
		$deleted ? $message["success"] = "Department deleted successfully." : $message["error"] = "Failed to delete department.";
		$_SESSION["message"] = $message;
		header("Location: dashboard");
		exit();
	}
}