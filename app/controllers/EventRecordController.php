<?php
require_once __DIR__ . "/../models/EventRecord.php";

class ERController extends EventRecord
{
	private $erModel = "";

	public function __construct()
	{
		$this->erModel = new EventRecord();
	}

	public function timeIn($user_id)
	{
		if ($this->erModel->hasTimedInToday($user_id)) {
			$_SESSION["error"] = "You have already clocked in today.";
			$_SESSION["has_timed_in_today"] = true;
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
			$_SESSION["error"] = "You have not yet clocked in today.";
			$_SESSION["has_timed_out_today"] = false;
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

	public function getByUserId($user_id)
	{
		return $this->erModel->getByUserId($user_id);
	}

	public function delete($id){
		return $this->erModel->delete($id);
	}

	public function deleteAllFromUser($user_id){
		return $this->erModel->deleteAllFromUser($user_id);
	}
}