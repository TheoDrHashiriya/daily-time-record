<?php
namespace App\Controllers;
use App\Models\Department;
use App\Services\ValidationService;
use PDOException;

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
		$standard_time_in = trim($_POST["standard_time_in"]);
		$standard_time_out = trim($_POST["standard_time_out"]);

		if ($this->departmentModel->nameExists($department_name))
			$errors["department_name"] = "Department name already taken.";
		if ($this->departmentModel->abbreviationExists($abbreviation))
			$errors["abbreviation"] = "Abbreviation already taken.";

		if (!ValidationService::isValidTime($standard_time_in))
			$errors["standard_time_in"] = "Invalid standard time in.";
		if (!ValidationService::isValidTime($standard_time_out))
			$errors["standard_time_out"] = "Invalid standard time out.";

		if (empty($department_name))
			$errors["department_name"] = "Department name is required.";
		if (empty($abbreviation))
			$errors["abbreviation"] = "Abbreviation is required.";
		if (empty($standard_time_in))
			$errors["standard_time_in"] = "Standard time in is required.";
		if (empty($standard_time_out))
			$errors["standard_time_out"] = "Standard time out is required.";

		if (!empty($errors)) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$created = $this->departmentModel->create($department_name, $abbreviation, $standard_time_in, $standard_time_out);
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
		$standard_time_in = trim($_POST["standard_time_in"]);
		$standard_time_out = trim($_POST["standard_time_out"]);

		if ($this->departmentModel->nameExistsExceptCurrent($id, $department_name))
			$errors["department_name"] = "Name must be unique.";
		if ($this->departmentModel->abbreviationExistsExceptCurrent($id, $abbreviation))
			$errors["abbreviation"] = "Abbreviation must be unique.";

		if (!ValidationService::isValidTime($standard_time_in))
			$errors["standard_time_in"] = "Invalid standard time in.";
		if (!ValidationService::isValidTime($standard_time_out))
			$errors["standard_time_out"] = "Invalid standard time out.";

		if (empty($department_name))
			$errors["department_name"] = "Department name is required.";
		if (empty($abbreviation))
			$errors["abbreviation"] = "Abbreviation is required.";
		if (empty($standard_time_in))
			$errors["standard_time_in"] = "Standard time in is required.";
		if (empty($standard_time_out))
			$errors["standard_time_out"] = "Standard time out is required.";

		if (!empty($errors)) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$updated = $this->departmentModel->update($id, $department_name, $abbreviation, $standard_time_in, $standard_time_out);
		$updated ? $message["success"] = "Department updated successfully." : $message["error"] = "Failed to update department.";

		$_SESSION["message"] = $message;

		header("Location: dashboard");
		exit();
	}

	public function delete()
	{
		$id = trim($_POST["entity_id"]);
		$message = [];

		try {
			$deleted = $this->departmentModel->delete($id);
			$deleted ? $message["success"] = "Department deleted successfully." : $message["error"] = "Failed to delete department.";
		} catch (PDOException $e) {
			switch ($e->getCode()) {
				case 23000:
					$message["error-title"] = "Department In Use";
					$message["error"] = "Cannot delete a department in use.";
					$_SESSION["message"] = $message;
					header("Location: dashboard");
					break;

				default:
					$message["error-title"] = "Database Error";
					$message["error"] = $e->getMessage();
					break;
			}
		}
		
		$_SESSION["message"] = $message;
		header("Location: dashboard");
		exit();
	}
}