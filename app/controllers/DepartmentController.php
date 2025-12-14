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
		$standard_am_time_in = trim($_POST["standard_am_time_in"]);
		$standard_am_time_out = trim($_POST["standard_am_time_out"]);
		$standard_pm_time_in = trim($_POST["standard_pm_time_in"]);
		$standard_pm_time_out = trim($_POST["standard_pm_time_out"]);

		if ($this->departmentModel->nameExists($department_name))
			$errors["department_name"] = "Department name already taken.";
		if ($this->departmentModel->abbreviationExists($abbreviation))
			$errors["abbreviation"] = "Abbreviation already taken.";
		if (empty($department_name))
			$errors["department_name"] = "Department name is required.";
		if (empty($abbreviation))
			$errors["abbreviation"] = "Abbreviation is required.";

		if (!ValidationService::isValidTimeNoSeconds($standard_am_time_in))
			$errors["standard_am_time_in"] = "Invalid standard AM time in.";
		if (!ValidationService::isValidTimeNoSeconds($standard_am_time_out))
			$errors["standard_am_time_out"] = "Invalid standard AM time out.";
		if (!ValidationService::isValidTimeNoSeconds($standard_pm_time_in))
			$errors["standard_pm_time_in"] = "Invalid standard PM time in.";
		if (!ValidationService::isValidTimeNoSeconds($standard_pm_time_out))
			$errors["standard_pm_time_out"] = "Invalid standard PM time out.";

		$earlier = "Time in must be earlier than time out.";
		$later = "Time out must be later than time in.";
		if ($standard_am_time_in > $standard_am_time_out) {
			$errors["standard_am_time_in"] = $earlier;
			$errors["standard_am_time_out"] = $later;
		}

		if ($standard_pm_time_in > $standard_pm_time_out) {
			$errors["standard_pm_time_in"] = $earlier;
			$errors["standard_pm_time_out"] = $later;
		}

		if (empty($standard_am_time_in))
			$errors["standard_am_time_in"] = "Standard AM time in is required.";
		if (empty($standard_am_time_out))
			$errors["standard_am_time_out"] = "Standard AM time out is required.";
		if (empty($standard_pm_time_in))
			$errors["standard_pm_time_in"] = "Standard PM time in is required.";
		if (empty($standard_pm_time_out))
			$errors["standard_pm_time_out"] = "Standard PM time out is required.";



		if (!empty($errors)) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$created = $this->departmentModel->create(
			$department_name,
			$abbreviation,
			$standard_am_time_in,
			$standard_am_time_out,
			$standard_pm_time_in,
			$standard_pm_time_out
		);
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
		$standard_am_time_in = trim($_POST["standard_am_time_in"]);
		$standard_am_time_out = trim($_POST["standard_am_time_out"]);
		$standard_pm_time_in = trim($_POST["standard_pm_time_in"]);
		$standard_pm_time_out = trim($_POST["standard_pm_time_out"]);

		if ($this->departmentModel->nameExistsExceptCurrent($id, $department_name))
			$errors["department_name"] = "Name must be unique.";
		if ($this->departmentModel->abbreviationExistsExceptCurrent($id, $abbreviation))
			$errors["abbreviation"] = "Abbreviation must be unique.";
		if (empty($department_name))
			$errors["department_name"] = "Department name is required.";
		if (empty($abbreviation))
			$errors["abbreviation"] = "Abbreviation is required.";

		if (!ValidationService::isValidTime($standard_am_time_in))
			$errors["standard_am_time_in"] = "Invalid standard AM time in.";
		if (!ValidationService::isValidTime($standard_am_time_out))
			$errors["standard_am_time_out"] = "Invalid standard AM time out.";
		if (!ValidationService::isValidTime($standard_pm_time_in))
			$errors["standard_pm_time_in"] = "Invalid standard PM time in.";
		if (!ValidationService::isValidTime($standard_pm_time_out))
			$errors["standard_pm_time_out"] = "Invalid standard PM time out.";

		$earlier = "Time in must be earlier than time out.";
		$later = "Time out must be later than time in.";
		if ($standard_am_time_in > $standard_am_time_out) {
			$errors["standard_am_time_in"] = $earlier;
			$errors["standard_am_time_out"] = $later;
		}

		if ($standard_pm_time_in > $standard_pm_time_out) {
			$errors["standard_pm_time_in"] = $earlier;
			$errors["standard_pm_time_out"] = $later;
		}

		if (empty($standard_am_time_in))
			$errors["standard_am_time_in"] = "Standard AM time in is required.";
		if (empty($standard_am_time_out))
			$errors["standard_am_time_out"] = "Standard AM time out is required.";
		if (empty($standard_pm_time_in))
			$errors["standard_pm_time_in"] = "Standard PM time in is required.";
		if (empty($standard_pm_time_out))
			$errors["standard_pm_time_out"] = "Standard PM time out is required.";

		if (!empty($errors)) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$updated = $this->departmentModel->update(
			$id,
			$department_name,
			$abbreviation,
			$standard_am_time_in,
			$standard_am_time_out,
			$standard_pm_time_in,
			$standard_pm_time_out
		);
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