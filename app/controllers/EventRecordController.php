<?php
namespace App\Controllers;
use App\Models\EventRecord;

class EventRecordController extends EventRecord
{
	private $erModel;

	public function __construct()
	{
		$this->erModel = new EventRecord();
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

	public function timeIn($user_id)
	{
		if ($this->erModel->hasTimedInToday($user_id)) {
			$_SESSION["error"] = "You have already clocked in for today.";
			$_SESSION["has_timed_in_today"] = true;
			return;
		}

		if ($this->erModel->hasTimedOutToday($user_id)) {
			$_SESSION["error"] = "You have already clocked out for today.";
			$_SESSION["has_timed_out_today"] = true;
			return;
		}

		if ($this->erModel->recordTimeIn($user_id)) {
			$_SESSION["success"] = "Time-in recorded successfully.";
			$_SESSION["has_timed_in_today"] = true;
			return;
		}

		$_SESSION["error"] = "Failed to record time-in.";
		$_SESSION["has_timed_in_today"] = false;
	}

	public function timeOut($user_id)
	{
		if (!$this->erModel->hasTimedInToday($user_id)) {
			$_SESSION["error"] = "You have not yet clocked in for today.";
			$_SESSION["has_timed_out_today"] = false;
			return;
		}

		if ($this->erModel->hasTimedOutToday($user_id)) {
			$_SESSION["error"] = "You have already clocked out for today.";
			$_SESSION["has_timed_out_today"] = true;
			return;
		}

		if ($this->erModel->recordTimeOut($user_id)) {
			$_SESSION["success"] = "Time-out recorded successfully.";
			$_SESSION["has_timed_out_today"] = true;
			return;
		}

		$_SESSION["error"] = "Failed to record time-out.";
		$_SESSION["has_timed_out_today"] = false;
	}

	public function getAll()
	{
		return $this->erModel->getAll();
	}

	public function getByUserId($user_id)
	{
		return $this->erModel->getByUserId($user_id);
	}

	public function delete($id)
	{
		return $this->erModel->delete($id);
	}

	public function deleteAllFromUser($user_id)
	{
		return $this->erModel->deleteAllFromUser($user_id);
	}
}