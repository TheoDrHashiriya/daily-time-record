<?php
require_once __DIR__ . "/../models/DailyTimeRecord.php";

class DTRController extends DailyTimeRecord
{
	private $dtrModel = "";

	public function __construct()
	{
		$this->dtrModel = new DailyTimeRecord();
	}

	public function timeIn($userId)
	{
		if ($this->dtrModel->hasTimedInToday($userId)) {
			$_SESSION["error"] = "You have already clocked in today.";
			$_SESSION["has_timed_in_today"] = true;
			return;
		}

		if ($this->dtrModel->recordTimeIn($userId)) {
			$_SESSION["success"] = "Time-in recorded successfully.";
			$_SESSION["has_timed_in_today"] = true;
			return;
		}

		$_SESSION["error"] = "Failed to record time-in.";
		$_SESSION["has_timed_in_today"] = false;
	}

	public function timeOut($userId)
	{
		if (!$this->dtrModel->hasTimedInToday($userId)) {
			$_SESSION["error"] = "You have not yet clocked in today.";
			$_SESSION["has_timed_out_today"] = false;
			return;
		}

		if ($this->dtrModel->recordTimeOut($userId)) {
			$_SESSION["success"] = "Time-out recorded successfully.";
			$_SESSION["has_timed_out_today"] = true;
			return;
		}

		$_SESSION["error"] = "Failed to record time-out.";
		$_SESSION["has_timed_out_today"] = false;
	}

	public function showUserRecords($userId)
	{
		return $this->dtrModel->getByUserId($userId);
	}

	public function delete($id){
		return $this->dtrModel->delete($id);
	}

	public function deleteAllFromUser($userId){
		return $this->dtrModel->deleteAllFromUser($userId);
	}
}