<?php
namespace App\Services;
use App\Models\User;
use App\Models\EventRecord;
use App\Models\Department;
use App\Models\Notification;

class DashboardService
{
	private $userModel;
	private $recordModel;
	private $notificationModel;
	private $departmentModel;

	public function __construct()
	{
		$this->userModel = new User();
		$this->recordModel = new EventRecord();
		$this->notificationModel = new Notification();
		$this->departmentModel = new Department();
	}

	public function getAllData()
	{
		$users = $this->userModel->getAll();
		$records = $this->recordModel->getAll();
		$notifications = $this->notificationModel->getAll();
		$notifications_unread = $this->notificationModel->getAllUnread();
		$departments = $this->departmentModel->getAll();

		return [
			"users" => $users,
			"records" => $records,
			"notifications" => $notifications,
			"notifications_unread" => $notifications_unread,
			"departments" => $departments
		];
	}
}