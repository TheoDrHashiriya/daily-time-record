<?php
namespace App\Services;
use App\Models\Department;
use App\Models\EventRecord;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserRole;

class DashboardService
{
	private $departmentModel;
	private $notificationModel;
	private $recordModel;
	private $userModel;
	private $userRoleModel;

	public function __construct()
	{
		$this->departmentModel = new Department();
		$this->notificationModel = new Notification();
		$this->recordModel = new EventRecord();
		$this->userModel = new User();
		$this->userRoleModel = new UserRole();
	}

	public function getAllData()
	{
		$departments = $this->departmentModel->getAll();
		$notifications = $this->notificationModel->getAll();
		$notifications_unread = $this->notificationModel->getAllUnread();
		$records = $this->recordModel->getAll();
		$record_types = $this->recordModel->getAllTypes();
		$users = $this->userModel->getAll();
		$user_roles = $this->userRoleModel->getAll();

		// Format Data
		foreach ($notifications as &$notification) {
			$notification["created_by_full_name_formatted"] = FormattingService::formatFullName(
				$notification["first_name"],
				$notification["middle_name"],
				$notification["last_name"]
			);
		}
		unset($notification);

		foreach ($records as &$record) {
			$record["type_name_formatted"] = FormattingService::formatEventType($record["type_name"]);
			$record["event_date_formatted"] = FormattingService::formatDate($record["event_time"]);
			$record["event_time_formatted"] = FormattingService::formatTime($record["event_time"]);
		}
		unset($record);

		foreach ($record_types as &$type) {
			$type["type_name_formatted"] = FormattingService::formatEventType($type["type_name"]);
		}
		unset($type);

		foreach ($users as &$user) {
			$user["full_name_formatted"] = FormattingService::formatFullName(
				$user["first_name"],
				$user["middle_name"],
				$user["last_name"],
			);
		}
		unset($user);

		foreach ($user_roles as &$role) {
			$role["role_name_formatted"] = FormattingService::formatText($role["role_name"]);
		}
		unset($role);

		return [
			"departments" => $departments,
			"notifications" => $notifications,
			"notifications_unread" => $notifications_unread,
			"records" => $records,
			"record_types" => $record_types,
			"users" => $users,
			"user_roles" => $user_roles,
		];
	}
}