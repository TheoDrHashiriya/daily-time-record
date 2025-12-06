<?php
namespace App\Controllers;
use App\Models\Notification;

class NotificationController extends Controller
{
	private Notification $notificationModel;

	public function __construct(Notification $notificationModel)
	{
		$this->notificationModel = $notificationModel;
	}

	// FOR KPIS
	public function getTotal()
	{
		$notifications = $this->notificationModel->getAll();
		return count($notifications);
	}

	// MAIN

	public function getAll()
	{
		return $this->notificationModel->getAll();
	}

	public function getById($id)
	{
		return $this->notificationModel->getById($id);
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

		$created = $this->notificationModel->create($title, $content, $created_by);
		$created ? $message["success"] = "Notification created successfully." : $message["error"] = "Failed to create notification.";
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

		$updated = $this->notificationModel->update($id, $title, $content, $created_by);
		$updated ? $message["success"] = "Notification updated successfully." : $message["error"] = "Failed to update notification.";

		$_SESSION["message"] = $message;

		header("Location: dashboard");
		exit();
	}

	public function delete()
	{
		$id = trim($_POST["entity_id"]);
		$deleted = $this->notificationModel->delete($id);
		$deleted ? $message["success"] = "Notification deleted successfully." : $message["error"] = "Failed to delete notification.";
		$_SESSION["message"] = $message;
		header("Location: dashboard");
		exit();
	}
}