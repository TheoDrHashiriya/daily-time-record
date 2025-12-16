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

	private function filterActiveUsersByDate(array $userIds, DateTime $day)
	{
		return array_filter($userIds, function ($id) use ($day) {
			$user = $this->userModel->getById($id);
			if (empty($user["attendance_effective_date"]))
				return true;
			$effectiveDate = new DateTime($user["attendance_effective_date"]);
			return $day >= $effectiveDate;
		});
	}

	public function getTopLate(int $limit = 10, ?DateTime $start = null, ?DateTime $end = null): array
	{
		$start ??= new DateTime("today -6 days");
		$end ??= new DateTime("today");

		$departments = $this->departmentModel->getAll();
		$userCounts = [];

		$day = clone $start;

		while ($day <= $end) {
			foreach ($departments as $dept) {
				$schedule = $this->departmentModel->getSchedule($dept["id"]);
				$users = $this->filterActiveUsersByDate(
					$this->userModel->getIdsByDepartmentAndRole($dept["id"]),
					$day
				);
				$records = $this->recordModel->getByDepartmentAndDate($dept["id"], $day);

				$byUser = [];
				foreach ($records as $r)
					$byUser[$r["user_id"]][] = $r;

				foreach ($users as $id) {
					$status = isset($byUser[$id])
						? $this->evaluateUser($byUser[$id], $schedule)
						: "absent";

					if (!isset($userCounts[$id]))
						$userCounts[$id] = ["late" => 0];

					if ($status === "late")
						$userCounts[$id]["late"]++;
				}
			}
			$day->modify("+1 day");
		}

		uasort($userCounts, fn($a, $b) => $b["late"] <=> $a["late"]);

		$userCounts = \array_slice($userCounts, 0, $limit, true);

		$labels = $late = $absent = [];
		foreach ($userCounts as $id => $counts) {
			$user = $this->userModel->getById($id);
			$labels[] = $user["first_name"] . " " . $user["last_name"];
			$late[] = $counts["late"];
		}

		return compact("labels", "late");
	}

	public function getTopAbsent(int $limit = 10, ?DateTime $start = null, ?DateTime $end = null): array
	{
		$start ??= new DateTime("today -6 days");
		$end ??= new DateTime("today");

		$departments = $this->departmentModel->getAll();
		$userCounts = [];

		$day = clone $start;

		while ($day <= $end) {
			foreach ($departments as $dept) {
				$schedule = $this->departmentModel->getSchedule($dept["id"]);
				$users = $this->filterActiveUsersByDate(
					$this->userModel->getIdsByDepartmentAndRole($dept["id"]),
					$day
				);
				$records = $this->recordModel->getByDepartmentAndDate($dept["id"], $day);

				$byUser = [];
				foreach ($records as $r)
					$byUser[$r["user_id"]][] = $r;

				foreach ($users as $id) {
					$status = isset($byUser[$id])
						? $this->evaluateUser($byUser[$id], $schedule)
						: "absent";

					if (!isset($userCounts[$id]))
						$userCounts[$id] = ["absent" => 0];

					if ($status === "absent")
						$userCounts[$id]["absent"]++;
				}
			}
			$day->modify("+1 day");
		}

		uasort($userCounts, fn($a, $b) => $b["absent"] <=> $a["absent"]);

		$userCounts = \array_slice($userCounts, 0, $limit, true);

		$labels = $late = $absent = [];
		foreach ($userCounts as $id => $counts) {
			$user = $this->userModel->getById($id);
			$labels[] = $user["first_name"] . " " . $user["last_name"];
			$absent[] = $counts["absent"];
		}

		return compact("labels", "absent");
	}

	public function getTopLateAndAbsent(int $limit = 10, ?DateTime $start = null, ?DateTime $end = null): array
	{
		$start ??= new DateTime("today -6 days");
		$end ??= new DateTime("today");

		$departments = $this->departmentModel->getAll();
		$userCounts = [];

		$day = clone $start;

		while ($day <= $end) {
			foreach ($departments as $dept) {
				$schedule = $this->departmentModel->getSchedule($dept["id"]);
				$users = $this->filterActiveUsersByDate(
					$this->userModel->getIdsByDepartmentAndRole($dept["id"]),
					$day
				);
				$records = $this->recordModel->getByDepartmentAndDate($dept["id"], $day);

				$byUser = [];
				foreach ($records as $r)
					$byUser[$r["user_id"]][] = $r;

				foreach ($users as $id) {
					$status = isset($byUser[$id])
						? $this->evaluateUser($byUser[$id], $schedule)
						: "absent";

					if (!isset($userCounts[$id]))
						$userCounts[$id] = ["late" => 0, "absent" => 0];

					if ($status === "late")
						$userCounts[$id]["late"]++;
					if ($status === "absent")
						$userCounts[$id]["absent"]++;
				}
			}
			$day->modify("+1 day");
		}

		uasort(
			$userCounts,
			fn($a, $b) => $b["late"] * 1.5 + $b["absent"] <=> $a["late"] * 1.5 + $a["absent"]
		);

		$userCounts = \array_slice($userCounts, 0, $limit, true);

		$labels = $late = $absent = [];
		foreach ($userCounts as $id => $counts) {
			$user = $this->userModel->getById($id);
			$labels[] = $user["first_name"] . " " . $user["last_name"];
			$late[] = $counts["late"];
			$absent[] = $counts["absent"];
		}

		return compact("labels", "late", "absent");
	}

	public function getWeeklySummary(int $department, DateTime $start, DateTime $end): array
	{
		$schedule = $this->departmentModel->getSchedule($department);
		$day = clone $start;

		
		$labels = $present = $late = $absent = [];
		
		
		while ($day <= $end) {
			$labels[] = $day->format("D");
			
			$users = $this->filterActiveUsersByDate(
				$this->userModel->getIdsByDepartmentAndRole($department),
				$day
			);
			
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
		$today = new DateTime("today");

		while ($day <= $end) {
			$labels[] = $day->format("D");

			if ($day->format("Y-m-d") === $today->format("Y-m-d"))
				$labels[array_key_last($labels)] .= " (today)";

			$dayPresent = $dayLate = $dayAbsent = 0;

			foreach ($departments as $dept) {
				$schedule = $this->departmentModel->getSchedule($dept["id"]);
				$users = $this->filterActiveUsersByDate(
					$this->userModel->getIdsByDepartmentAndRole($dept["id"]),
					$day
				);
				$records = $this->recordModel->getByDepartmentAndDate(
					$dept["id"],
					$day
				);

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
		$amStatus = "absent";
		$pmStatus = "absent";

		foreach ($records as $r) {
			if ($r["event_type"] === AM_IN) {
				$eventTime = new DateTime($r["event_time"]);
				$scheduleTime = new DateTime($eventTime->format("Y-m-d") . " " . $schedule["standard_am_time_in"]);
				$amStatus = $eventTime > $scheduleTime ? "late" : "present";
			}

			if ($r["event_type"] === PM_IN) {
				$eventTime = new DateTime($r["event_time"]);
				$scheduleTime = new DateTime($eventTime->format("Y-m-d") . " " . $schedule["standard_pm_time_in"]);
				$pmStatus = $eventTime > $scheduleTime ? "late" : "present";
			}
		}

		if ($amStatus === "late" || $pmStatus === "late")
			return "late";
		if ($amStatus === "present" || $pmStatus === "present")
			return "present";

		return "absent";
	}
}