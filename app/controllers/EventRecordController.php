<?php
namespace App\Controllers;
use App\Models\EventRecord;
use App\Services\DashboardService;
use App\Services\FormatService;
use App\Services\ValidationService;
use PrintService;

class EventRecordController extends Controller
{
	private EventRecord $recordModel;
	private DashboardService $dashboardService;

	public function __construct(EventRecord $recordModel, DashboardService $dashboardService)
	{
		$this->recordModel = $recordModel;
		$this->dashboardService = $dashboardService;
	}

	// FOR KPIS

	public function getTotal()
	{
		$records = $this->recordModel->getAll();
		return count($records);
	}

	public function getTotalUnclosed()
	{
		return $this->recordModel->getTotalUnclosed();
	}

	// MAIN

	public function getAll()
	{
		return $this->recordModel->getAll();
	}

	public function getByUserId($user_id)
	{
		return $this->recordModel->getByUserId($user_id);
	}

	public function streamToPdf()
	{
		$records = $this->dashboardService->getEventRecords();
		PrintService::streamPdf(
			"all-records-" . FormatService::formatPdfName(FormatService::getCurrentDate()),
			["components/pdf/pdf-styles", "components/tables/records"],
			["records" => $records]
		);
		exit();
	}

	public function create()
	{
		$user_id = trim($_POST["user_id"] ?? "");
		$event_time = trim($_POST["event_time"] ?? "");
		$event_type = trim($_POST["event_type"] ?? "");

		// Validate user input
		if (empty($user_id))
			$errors["user_id"] = "Please enter the record creator.";
		if (ValidationService::isIncompleteDateTime($event_time))
			$errors["event_time"] = "Please enter the complete time of the event.";
		if (empty($event_time))
			$errors["event_time"] = "Please enter the time of the event.";
		if (empty($event_type))
			$errors["event_type"] = "Please enter the record type.";

		if (!empty($errors)) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$created = $this->recordModel->create($user_id, $event_time, $event_type);
		$created ? $message["success"] = "Record created successfully." : $message["error"] = "Failed to create record.";

		$_SESSION["message"] = $message;
		header("Location: dashboard");
		exit();
	}

	public function edit()
	{
		$id = trim($_POST["entity_id"]);
		$user_id = trim($_POST["user_id"] ?? "");
		$event_time = trim($_POST["event_time"] ?? "");
		$event_type = trim($_POST["event_type"] ?? "");

		// Validate user input
		if ($event_time === "")
			$errors["event_time"] = "Please enter the time of the event.";

		if (!empty($errors)) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$updated = $this->recordModel->update($id, $event_time, $event_type, $user_id);
		$updated ? $message["success"] = "Record updated successfully." : $message["error"] = "Failed to updated record.";

		$_SESSION["message"] = $message;
		header("Location: dashboard");
		exit();
	}

	public function delete()
	{
		$id = trim($_POST["entity_id"]);
		$deleted = $this->recordModel->delete($id);
		$deleted ? $message["success"] = "Record deleted successfully." : $message["error"] = "Failed to delete record.";
		$_SESSION["message"] = $message;
		header("Location: dashboard");
		exit();
	}

	public function deleteAllFromUser($user_id)
	{
		return $this->recordModel->deleteAllFromUser($user_id);
	}
}