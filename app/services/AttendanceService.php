<?php
namespace App\Services;

use App\Models\Department;
use App\Models\EventRecord;
use App\Models\User;
use DateTime;

class AttendanceService
{
	private Department $departmentModel;
	private EventRecord $recordModel;
	private User $userModel;

	public function __construct(Department $departmentModel, EventRecord $recordModel, User $userModel)
	{
		$this->departmentModel = $departmentModel;
		$this->recordModel = $recordModel;
		$this->userModel = $userModel;
	}

	public function getWeeklySummary(int $department, DateTime $start, DateTime $end): array
	{
		$schedule = $this->departmentModel->getSchedule($department);
		$users = $this->userModel->getIdsByDepartment($department);

		$labels = $present = $late = $absent = [];

		$day = clone $start;

		while ($day <= $end) {
			$labels[] = $day->format("D");

			$records = $this->recordModel->getByDepartmentAndDate($department, $day);

			$counts = $this->classifyDay($users, $records, $schedule);

			$present[] = $counts["present"];
			$late[] = $counts["late"];
			$absent[] = $counts["absent"];
			$day->modify("+1 day");
		}

		return compact("labels", "present", "late", "absent");
	}

	public function getWeeklySummaryAllDepartments(DateTime $start, DateTime $end): array
	{
		$departments = $this->departmentModel->getAll();

		$labels = $present = $late = $absent = [];

		$day = clone $start;

		while ($day <= $end) {
			$labels[] = $day->format("D");

			$dayPresent = $dayLate = $dayAbsent = 0;

			foreach ($departments as $dept) {
				$schedule = $this->departmentModel->getSchedule($dept["id"]);
				$users = $this->userModel->getIdsByDepartment($dept["id"]);
				$records = $this->recordModel->getByDepartmentAndDate($dept["id"], $day);

				$counts = $this->classifyDay($users, $records, $schedule);

				$dayPresent += $counts["present"];
				$dayLate += $counts["late"];
				$dayAbsent += $counts["absent"];
			}

			$present[] = $dayPresent;
			$late[] = $dayLate;
			$absent[] = $dayAbsent;
			$day->modify("+1 day");
		}

		return compact("labels", "present", "late", "absent");
	}

	private function classifyDay(array $users, array $records, array $schedule): array
	{
		$byUser = [];

		foreach ($records as $r)
			$byUser[$r["user_id"]][] = $r;

		$present = $late = $absent = 0;

		foreach ($users as $id) {
			if (!isset($byUser[$id])) {
				$absent++;
				continue;
			}

			$status = $this->evaluateUser($byUser[$id], $schedule);

			match ($status) {
				"late" => $late++,
				"present" => $present++,
				default => $absent++
			};
		}

		return compact("present", "late", "absent");
	}

	private function evaluateUser(array $records, array $schedule): string
	{
		foreach ($records as $r) {
			if ($r["type_name"] === "AM_IN")
				return (
					substr($r["event_time"], 11, 8)
					> $schedule["standard_am_time_in"]
				) ? "late" : "present";
		}
		return "absent";
	}
}