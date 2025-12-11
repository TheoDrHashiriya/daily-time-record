<?php
namespace App\Services;

use DateTime;

class ValidationService
{
	public static function isValidTime($input)
	{
		// HH:MM:SS
		return preg_match("/^(2[0-3]|[01]?[0-9]):([0-5][0-9]):([0-5][0-9])$/", $input);
	}

	public static function isIncompleteDateTime($input, $format = "Y-m-d H:i:s")
	{
		$datetime = \DateTime::createFromFormat($format, $input);
		$errors = \DateTime::getLastErrors();

		return (!$datetime || !empty($errors["warning_count"])) ? true : false;
	}
}