<?php
namespace App\Services;
use App\Models\EventRecord;
use App\Models\User;
use App\Helpers\GlobalHelper;

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

		foreach ($records as $record) {
			$record["type_name_formatted"] = GlobalHelper::formatEventType($record["type_name"]);
			$record["event_date_formatted"] = GlobalHelper::formatDate($record["event_time"]);
			$record["event_time_formatted"] = GlobalHelper::formatTime($record["event_time"]);
		}

		return ["records" => $records];
	}
}