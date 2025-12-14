<?php
namespace App\Services;
use App\Models\Department;
use App\Models\EventRecord;
use App\Models\SystemLog;
use App\Models\User;
use App\Models\UserRole;

class DashboardService
{
	private AuthService $authService;
	private Department $departmentModel;
	private SystemLog $systemLogModel;
	private EventRecord $recordModel;
	private User $userModel;
	private UserRole $userRoleModel;

	public function __construct(
		AuthService $authService,
		Department $departmentModel,
		SystemLog $systemLogModel,
		EventRecord $recordModel,
		User $userModel,
		UserRole $userRoleModel
	) {
		$this->authService = $authService;
		$this->departmentModel = $departmentModel;
		$this->systemLogModel = $systemLogModel;
		$this->recordModel = $recordModel;
		$this->userModel = $userModel;
		$this->userRoleModel = $userRoleModel;
	}

	public function getAllData()
	{
		$isAdmin = $this->authService->isAdmin();

		$departments = $this->getDepartments();
		$system_logs = $this->getSystemLogs();
		$system_log_types = $this->getSystemLogTypes();
		$records = $this->getEventRecords();
		$record_types = $this->getEventRecordTypes();
		$users = $this->getUsers();
		$user_roles = $this->getUserRoles();

		return [
			"isAdmin" => $isAdmin,
			"departments" => $departments,
			"system_logs" => $system_logs,
			"system_log_types" => $system_log_types,
			"records" => $records,
			"record_types" => $record_types,
			"users" => $users,
			"user_roles" => $user_roles,
		];
	}

	public function getDepartments()
	{
		$rows = $this->departmentModel->getAll();
		$total = \count($rows);

		foreach ($rows as &$department) {
			$department["standard_am_time_in_formatted"] = FormatService::formatTime($department["standard_am_time_in"]) ?? "";
			$department["standard_am_time_out_formatted"] = FormatService::formatTime($department["standard_am_time_out"]) ?? "";
			$department["standard_pm_time_in_formatted"] = FormatService::formatTime($department["standard_pm_time_in"]) ?? "";
			$department["standard_pm_time_out_formatted"] = FormatService::formatTime($department["standard_pm_time_out"]) ?? "";
			$department["created_at_formatted"] = FormatService::formatTime($department["created_at"]);
			$department["created_on_formatted"] = FormatService::formatDate($department["created_at"]);
		}
		unset($department);

		return [
			"data" => $rows,
			"total" => $total
		];
	}

	public function getSystemLogs()
	{
		$system_logs = $this->systemLogModel->getAll();
		$notifications_unread = $this->systemLogModel->getAllUnread($_SESSION["user_id"] ?? "");

		foreach ($system_logs as &$log) {
			$log["created_by_full_name_formatted"] = FormatService::formatFullName(
				$log["first_name"],
				$log["middle_name"],
				$log["last_name"]
			);
			$log["created_at_formatted"] = FormatService::formatTime($log["created_at"]);
			$log["created_on_formatted"] = FormatService::formatDate($log["created_at"]);
		}
		unset($log);

		$system_logs = FormatService::sortArrayByColumn($system_logs, "created_at");

		return [
			"all" => $system_logs,
			"notifications" => ["unread" => $notifications_unread]
		];
	}

	public function getSystemLogTypes()
	{
		$rows = $this->systemLogModel->getAllTypes();

		foreach ($rows as &$type) {
			$type["type_name_formatted"] = FormatService::formatNoUnderScore($type["type_name"]);
			$type["is_notification_formatted"] = FormatService::formatBoolean($type["is_notification"]);
		}
		unset($type);

		return ["data" => $rows];
	}

	public function getEventRecords()
	{
		//  SEARCH PARAMETERS
		$search = $_GET["records"] ?? "";
		$date = $_GET["event_date"] ?? "";

		$rows = $this->recordModel->getAll($search, $date);
		$total = \count($rows);
		$unclosed = $this->recordModel->getTotalUnclosed();

		foreach ($rows as &$record) {
			$record["event_date_formatted"] = FormatService::formatDate($record["event_time"]);
			$record["event_time_formatted"] = FormatService::formatTime($record["event_time"]);
			$record["full_name_formatted"] = FormatService::formatFullName(
				$record["first_name"],
				$record["middle_name"],
				$record["last_name"],
			);

			if ($this->authService->isAdmin())
				$record["type_name_formatted"] = FormatService::formatNoUnderScore($record["type_name"]);
			else
				$record["type_name_formatted"] = FormatService::formatEventType($record["type_name"]);
		}
		unset($record);

		return [
			"search" => $search,
			"data" => $rows,
			"total" => $total,
			"unclosed" => $unclosed
		];
	}

	public function getEventRecordTypes()
	{
		$rows = $this->recordModel->getAllTypes();

		foreach ($rows as &$type) {
			$type["type_name_formatted"] = FormatService::formatNoUnderScore($type["type_name"]);
		}
		unset($type);

		return ["data" => $rows];
	}

	public function getUsers()
	{
		//  SEARCH PARAMETERS
		$search = $_GET["users"] ?? "";
		$fullname = $_GET["fullname"] ?? "";
		$user_number = $_GET["user_number"] ?? "";
		$role = $_GET["role"] ?? "";
		$department_name = $_GET["department_name"] ?? "";

		$rows = $this->userModel->getAll($search, $fullname, $user_number, $role, $department_name);
		$admins = $this->userModel->getAllByRole(ROLE_ADMIN);
		$total = \count($rows);

		foreach ($rows as &$user) {
			$user["full_name_formatted"] = FormatService::formatFullName(
				$user["first_name"],
				$user["middle_name"],
				$user["last_name"],
			);
			$user["role_name_formatted"] = FormatService::formatText(text: $user["role_name"]);
			$user["department_name_formatted"] = FormatService::formatText($user["department_name"]);
			$user["creator_first_name"] ? $user["created_by_formatted"] = FormatService::formatFullName(
				$user["creator_first_name"],
				$user["creator_middle_name"],
				$user["creator_last_name"],
			) : null;
			$user["created_at_formatted"] = FormatService::formatDate($user["created_at"]);
			// $user["qr_code_base64"] = QRCodeService::render($user["qr_string"]) ?? "User has no QR Code.";
		}
		unset($user);

		foreach ($admins as &$admin) {
			$admin["admins"]["full_name_formatted"] = FormatService::formatFullName(
				$admin["first_name"],
				$admin["middle_name"],
				$admin["last_name"],
			);
		}
		unset($admin);

		return [
			"search" => $search,
			"data" => $rows,
			"admins" => $admins,
			"total" => $total,
		];
	}

	public function getUserRoles()
	{
		$rows = $this->userRoleModel->getAll();

		foreach ($rows as &$role) {
			$role["role_name_formatted"] = FormatService::formatText($role["role_name"]);
		}
		unset($role);

		return ["data" => $rows];
	}
}