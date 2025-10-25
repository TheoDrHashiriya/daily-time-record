<?php
require_once __DIR__ . "/../models/DailyTimeRecord.php";

class DTRController extends DailyTimeRecord
{
	private $dtrModel = "";

	public function __construct()
	{
		$this->dtrModel = new DailyTimeRecord();
	}

	public function hasUnclosedRecord($id)
	{

	}

	public function timeIn($userId)
	{
		if ($this->dtrModel->hasTimedInToday($userId)) {
			return ["error" => "You have already clocked in today."];
		}

		if ($this->dtrModel->recordTimeIn($userId)) {
			return ["success" => "Time-in recorded successfully."];
		}

		return ["error" => "Failed to record time-in."];
	}

	public function showUserRecords($userId)
	{
		return $this->dtrModel->getByUserId($userId);
	}
}