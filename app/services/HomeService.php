<?php
namespace App\Services;
use App\Models\EventRecord;
use App\Models\User;

class HomeService
{
	private $authService;
	private $erModel;

	public function __construct()
	{
		$this->authService = new AuthService(new User);
		$this->erModel = new EventRecord();
	}

	public function getAllData()
	{
		$records = $this->erModel->getAll();

		foreach ($records as &$record) {
			$record["type_name_formatted"] = FormattingService::formatEventType($record["type_name"]);
			$record["event_date_formatted"] = FormattingService::formatDate($record["event_time"]);
			$record["event_time_formatted"] = FormattingService::formatTime($record["event_time"]);
		}
		unset($record);

		return ["records" => $records];
	}
}