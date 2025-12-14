<?php
namespace App\Services;
use App\Models\EventRecord;
use App\Models\User;

class HomeService
{
	private $authService;
	private $recordModel;

	public function __construct()
	{
		$this->authService = new AuthService(new User);
		$this->recordModel = new EventRecord();
	}

	public function getAllData()
	{
		$rows = $this->recordModel->getAll();

		foreach ($rows as &$record) {
			$record["event_date_formatted"] = FormatService::formatDate($record["event_time"]);
			$record["event_time_formatted"] = FormatService::formatTime($record["event_time"]);
			$record["type_name_formatted"] = FormatService::formatEventType($record["type_name"]);
			$record["full_name_formatted"] = FormatService::formatFullName(
				$record["first_name"],
				$record["middle_name"],
				$record["last_name"],
			);
		}
		unset($record);

		return ["records" => ["data" => $rows]];
	}
}