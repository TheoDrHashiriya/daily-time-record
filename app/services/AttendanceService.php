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

	private function calculateUndertime(array $records, array $schedule): string
	{
		$undertimeMinutes = 0;
		$dateStr = isset($records[0]["event_time"])
			? substr($records[0]["event_time"], 0, 10)
			: date("Y-m-d");

		$amOut = null;
		$pmOut = null;

		foreach ($records as $r) {
			if ($r["event_type"] === AM_OUT)
				$amOut = new DateTime($r["event_time"]);
			if ($r["event_type"] === PM_OUT)
				$pmOut = new DateTime($r["event_time"]);
		}

		if ($amOut) {
			$scheduledAmOut = new DateTime($dateStr . " " . $schedule["standard_am_time_out"]);
			if ($amOut < $scheduledAmOut)
				$undertimeMinutes += ($scheduledAmOut->getTimestamp() - $amOut->getTimestamp()) / 60;
		}

		if ($pmOut) {
			$scheduledPmOut = new DateTime($dateStr . " " . $schedule["standard_pm_time_out"]);
			if ($pmOut < $scheduledPmOut)
				$undertimeMinutes += ($scheduledPmOut->getTimestamp() - $pmOut->getTimestamp()) / 60;
		}

		if (!$amOut) {
			$scheduledAmOut = new DateTime($dateStr . " " . $schedule["standard_am_time_out"]);
			$scheduledAmIn = new DateTime($dateStr . " " . $schedule["standard_am_time_in"]);
			$undertimeMinutes += ($scheduledAmOut->getTimestamp() - $scheduledAmIn->getTimestamp()) / 60;
		}

		if (!$pmOut) {
			$scheduledPmOut = new DateTime($dateStr . " " . $schedule["standard_pm_time_out"]);
			$scheduledPmIn = new DateTime($dateStr . " " . $schedule["standard_pm_time_in"]);
			$undertimeMinutes += ($scheduledPmOut->getTimestamp() - $scheduledPmIn->getTimestamp()) / 60;
		}
		$hours = floor($undertimeMinutes / 60);
		$minutes = $undertimeMinutes % 60;

		return \sprintf("%d:%02d", $hours, $minutes);
	}

	public function getMonthlyRecordsForUser(int $userId, int $year, int $month): array
	{
		$monthlyRecords = [];
		$start = new DateTime("$year-$month-01");
		$end = (clone $start)->modify("last day of this month");

		$day = clone $start;

		$user = $this->userModel->getById($userId);
		$department = $user["department"];

		$schedule = $this->departmentModel->getSchedule($department);

		while ($day <= $end) {
			$dateStr = $day->format("Y-m-d");

			$dailyRecord = [
				"am_in" => null,
				"am_out" => null,
				"pm_in" => null,
				"pm_out" => null,
				"undertime" => null,
				"status" => "absent",
			];

			$records = $this->recordModel->getByDepartmentAndDate($department, $day);
			$userRecords = array_filter($records, fn($r) => $r["user_id"] === $userId);

			if (!empty($userRecords)) {
				foreach ($userRecords as $r) {
					if ($r["event_type"] === AM_IN)
						$dailyRecord["am_in"] = FormatService::formatTime($r["event_time"]);
					if ($r["event_type"] === AM_OUT)
						$dailyRecord["am_out"] = FormatService::formatTime($r["event_time"]);
					if ($r["event_type"] === PM_IN)
						$dailyRecord["pm_in"] = FormatService::formatTime($r["event_time"]);
					if ($r["event_type"] === PM_OUT)
						$dailyRecord["pm_out"] = FormatService::formatTime($r["event_time"]);
				}
				$dailyRecord["status"] = $this->evaluateUser($userRecords, $schedule);
				$dailyRecord["undertime"] = $this->calculateUndertime($userRecords, $schedule);
			}

			$monthlyRecords[$userId][$dateStr] = $dailyRecord;
			$day->modify("+1 day");
		}
		return $monthlyRecords;
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
			fn($a, $b) => $b["late"] + $b["absent"] * 1.5 <=> $a["late"] + $a["absent"] * 1.5
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