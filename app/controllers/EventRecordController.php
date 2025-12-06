<?php
namespace App\Controllers;
use App\Models\EventRecord;

class EventRecordController extends Controller
{
	private EventRecord $erModel;

	public function __construct(EventRecord $erModel)
	{
		$this->erModel = $erModel;
	}

	// FOR KPIS

	public function getTotal()
	{
		$records = $this->erModel->getAll();
		return count($records);
	}

	public function getTotalUnclosed()
	{
		return $this->erModel->getTotalUnclosed();
	}

	// MAIN

	public function getAll()
	{
		return $this->erModel->getAll();
	}

	public function getByUserId($user_id)
	{
		return $this->erModel->getByUserId($user_id);
	}

	public function edit()
	{
		$id = trim($_POST["entity_id"]);
		$user_id = trim($_POST["user_id"] ?? "");
		$event_time = trim($_POST["event_time"] ?? "");
		$event_type = trim($_POST["event_type"] ?? "");

		$errors = [];
		// Validate user input
		if ($event_time === "")
			$errors["event_time"] = "Please enter the time of the event.";

		$this->erModel->update($id, $event_time, $event_type, $user_id);

		header("Location: dashboard");
		exit();
	}

	public function delete()
	{
		$id = trim($_POST["entity_id"]);
		$this->erModel->delete($id);
		header("Location: dashboard");
		exit();
	}

	public function deleteAllFromUser($user_id)
	{
		return $this->erModel->deleteAllFromUser($user_id);
	}
}