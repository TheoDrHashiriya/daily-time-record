<?php
namespace App\Services;
use App\Models\Department;
use App\Models\EventRecord;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserRole;

class DashboardService
{
	private AuthService $authService;
	private Department $departmentModel;
	private Notification $notificationModel;
	private EventRecord $recordModel;
	private User $userModel;
	private UserRole $userRoleModel;

	public function __construct(
		AuthService $authService,
		Department $departmentModel,
		Notification $notificationModel,
		EventRecord $recordModel,
		User $userModel,
		UserRole $userRoleModel
	) {
		$this->authService = $authService;
		$this->departmentModel = $departmentModel;
		$this->notificationModel = $notificationModel;
		$this->recordModel = $recordModel;
		$this->userModel = $userModel;
		$this->userRoleModel = $userRoleModel;
	}

	public function getAllData()
	{
		$isAdmin = $this->authService->isAdmin();

		$departments = $this->getDepartments();
		$notifications = $this->getNotifications()["notifications"];
		$notifications_unread = $this->getNotifications()["notifications_unread"];
		$records = $this->getEventRecords();
		$record_types = $this->getEventRecordTypes();
		$users = $this->getUsers();
		$user_roles = $this->getUserRoles();

		return [
			"isAdmin" => $isAdmin,
			"departments" => $departments,
			"notifications" => $notifications,
			"notifications_unread" => $notifications_unread,
			"records" => $records,
			"record_types" => $record_types,
			"users" => $users,
			"user_roles" => $user_roles,
		];
	}

	public function getDepartments()
	{
		$departments = $this->departmentModel->getAll();

		foreach ($departments as &$department) {
			$department["created_at_formatted"] = FormattingService::formatTime($department["created_at"]);
			$department["created_on_formatted"] = FormattingService::formatDate($department["created_at"]);
		}
		unset($department);

		return $departments;
	}

	public function getNotifications()
	{
		$notifications = $this->notificationModel->getAll();
		$notifications_unread = $this->notificationModel->getAllUnread();

		foreach ($notifications as &$notification) {
			$notification["created_by_full_name_formatted"] = FormattingService::formatFullName(
				$notification["first_name"],
				$notification["middle_name"],
				$notification["last_name"]
			);
			$notification["created_at_formatted"] = FormattingService::formatTime($notification["created_at"]);
			$notification["created_on_formatted"] = FormattingService::formatDate($notification["created_at"]);
		}
		unset($notification);

		$notifications = FormattingService::sortArrayByColumn($notifications, "created_at");

		return [
			"notifications" => $notifications,
			"notifications_unread" => $notifications_unread
		];
	}

	public function getEventRecords()
	{
		$records = $this->recordModel->getAll();

		foreach ($records as &$record) {
			$record["event_date_formatted"] = FormattingService::formatDate($record["event_time"]);
			$record["event_time_formatted"] = FormattingService::formatTime($record["event_time"]);
			$record["type_name_formatted"] = FormattingService::formatEventType($record["type_name"]);
			$record["user_id_code"] = FormattingService::formatIdToCode($record["user_id"]);
		}
		unset($record);

		return $records;
	}

	public function getEventRecordTypes()
	{
		$record_types = $this->recordModel->getAllTypes();

		foreach ($record_types as &$type) {
			$type["type_name_formatted"] = FormattingService::formatEventType($type["type_name"]);
		}
		unset($type);

		return $record_types;
	}

	public function getUsers()
	{
		$users = $this->userModel->getAll();

		foreach ($users as &$user) {
			$user["full_name_formatted"] = FormattingService::formatFullName(
				$user["first_name"],
				$user["middle_name"],
				$user["last_name"],
			);
		}
		unset($user);

		return $users;
	}

	public function getUserRoles()
	{
		$user_roles = $this->userRoleModel->getAll();

		foreach ($user_roles as &$role) {
			$role["role_name_formatted"] = FormattingService::formatText($role["role_name"]);
		}
		unset($role);

		return $user_roles;
	}
}