<?php
require_once __DIR__ . "/../models/DailyTimeRecord.php";

class DTRController extends DailyTimeRecord
{
	private $dtrModel = "";

	public function __construct()
	{
		$this->dtrModel = new DailyTimeRecord();
	}

	public function hasUnclosedRecord($userId)
	{

	}

	public function timeIn($userId)
	{
		if ($this->dtrModel->hasTimedInToday($userId))
			return ["error" => "You have already clocked in today."];

		if ($this->dtrModel->recordTimeIn($userId))
			return ["success" => "Time-in recorded successfully."];

		$_SESSION["has_timed_in_today"] = $this->dtrModel->hasTimedInToday($userId);

		return ["error" => "Failed to record time-in."];
	}

	public function timeOut($userId)
	{
		if (!$this->dtrModel->hasTimedInToday($userId))
			return ["error" => "You have not yet clocked in today."];

		if ($this->dtrModel->recordTimeOut($userId))
			return ["success" => "Time-out recorded successfully."];

		$_SESSION["has_timed_out_today"] = $this->dtrModel->hasTimedOutToday($userId);

		return ["error" => "Failed to record time-in."];
	}

	public function showUserRecords($userId)
	{
		return $this->dtrModel->getByUserId($userId);
	}
}