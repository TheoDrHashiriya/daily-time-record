<?php
namespace App\Controllers;
use App\Models\SystemLog;
use App\Services\DashboardService;
use PrintService;

class SystemLogController extends Controller
{
	private DashboardService $dashboardService;
	private SystemLog $systemLogModel;

	public function __construct(DashboardService $dashboardService, SystemLog $systemLogModel)
	{
		$this->dashboardService = $dashboardService;
		$this->systemLogModel = $systemLogModel;
	}

	// FOR KPIS
	public function getTotal()
	{
		$notifications = $this->systemLogModel->getAll();
		return count($notifications);
	}

	// MAIN

	public function getAll()
	{
		return $this->systemLogModel->getAll();
	}

	public function getById($id)
	{
		return $this->systemLogModel->getById($id);
	}

	public function streamToPdf()
	{
		$system_logs = $this->dashboardService->getSystemLogs();
		PrintService::streamPdf(
			"all-system-logs.pdf",
			["components/pdf/pdf-styles", "components/tables/system-logs"],
			["system_logs" => $system_logs]
		);
		exit();
	}

	public function create()
	{
		$title = trim($_POST["title"]);
		$content = trim($_POST["content"]);
		$created_by = trim($_POST["created_by"]);

		if (empty($title))
			$errors["title"] = "Title is required.";
		if (empty($content))
			$errors["content"] = "Content is required.";

		if (!empty($errors)) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$created = $this->systemLogModel->create($title, $content, $created_by);
		$created ? $message["success"] = "SystemLog created successfully." : $message["error"] = "Failed to create notification.";
		$_SESSION["message"] = $message;
		header("Location: dashboard");
		exit();
	}

	public function edit()
	{
		$id = trim($_POST["entity_id"]);
		$title = trim($_POST["title"]);
		$content = trim($_POST["content"]);
		$created_by = trim($_POST["created_by"]);

		if (empty($title))
			$errors["title"] = "Title is required.";
		if (empty($content))
			$errors["content"] = "Content is required.";

		if (!empty($errors)) {
			header("Content-Type: application/json");
			echo json_encode(["success" => false, "errors" => $errors]);
			exit();
		}

		$updated = $this->systemLogModel->update($id, $title, $content, $created_by);
		$updated ? $message["success"] = "System log updated successfully." : $message["error"] = "Failed to update notification.";

		$_SESSION["message"] = $message;

		header("Location: dashboard");
		exit();
	}

	public function delete()
	{
		$id = trim($_POST["entity_id"]);
		$deleted = $this->systemLogModel->delete($id);
		$deleted ? $message["success"] = "System log deleted successfully." : $message["error"] = "Failed to delete notification.";
		$_SESSION["message"] = $message;
		header("Location: dashboard");
		exit();
	}
}