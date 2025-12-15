<?php
namespace App\Services;

use DateTime;

class ValidationService
{
	public static function isValidTime($time) // HH:MM:SS
	{
		return preg_match("/^(2[0-3]|[01]?[0-9]):([0-5][0-9]):([0-5][0-9])$/", $time);
	}

	public static function isValidTimeNoSeconds($time) // HH:MM
	{
		return preg_match("/^(2[0-3]|[01]?[0-9]):([0-5][0-9])$/", $time);
	}

	public static function isAMFromString(string $datetime)
	{
		$datetime = new DateTime($datetime);
		return (int) $datetime->format("H") < 12;
	}

	public static function isIncompleteDateTime(string $input, $format = "Y-m-d H:i:s")
	{
		$datetime = DateTime::createFromFormat($format, $input);
		$errors = DateTime::getLastErrors();

		return (!$datetime || !empty($errors["warning_count"])) ? true : false;
	}

	public static function isIncompleteDateTimeLocal(string $input)
	{
		$formats = [
			"Y-m-d\TH:i",
			"Y-m-d\TH:i:s"
		];

		foreach ($formats as $format) {
			$datetime = DateTime::createFromFormat($format, $input);
			$errors = DateTime::getLastErrors() ?: ["warning_count" => 0, "error_count" => 0];

			if ($datetime && $errors["warning_count"] === 0 && $errors["error_count"] === 0)
				return false;
		}

		return true;
	}
}