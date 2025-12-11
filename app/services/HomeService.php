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
		$rows = $this->erModel->getAll();

		foreach ($rows as &$record) {
			$record["event_date_formatted"] = FormatService::formatDate($record["event_time"]);
			$record["event_time_formatted"] = FormatService::formatTime($record["event_time"]);
			$record["type_name_formatted"] = FormatService::formatEventType($record["type_name"]);
		}
		unset($record);

		return ["records" => ["data" => $rows]];
	}
}