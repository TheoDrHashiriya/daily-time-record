<?php
namespace App\Services;

class FormatService
{
	// TEXT

	public static function formatText(string $text)
	{
		return ucwords(str_replace('_', ' ', $text));
	}

	public static function formatFullName(string $first, string $middle = "", string $last = "")
	{
		$fullName = ucfirst($first);

		if (!empty($middle))
			$fullName .= ' ' . strtoupper($middle[0]) . ".";

		if (!empty($last))
			$fullName .= ' ' . ucfirst($last);

		return $fullName;
	}

	public static function formatEventType($type_name)
	{
		return strtoupper(str_replace(["AM_", "PM_"], '', $type_name));
	}

	public static function formatNoUnderScore($text)
	{
		return strtoupper(str_replace('_', ' ', $text));
	}

	public static function formatPdfName($text)
	{
		return strtolower(str_replace([' ', '_', ':'], '-', $text));
	}

	public static function formatBoolean($bool)
	{
		return $bool === 1 ? "True" : "False";
	}

	// DATE & TIME

	public static function getCurrentDate()
	{
		return date("Y-m-d H:i:s");
	}

	public static function formatDate($date)
	{
		return date("D, M j, Y", strtotime($date));
	}

	public static function formatTime($time)
	{
		return date("h:i:s A", \is_string($time) ? strtotime($time) : $time);
	}

	// SORTING

	public static function sortArrayByColumn($array, $column_name, $sort_order)
	{
		$column = array_column($array, $column_name);
		array_multisort($column, $sort_order, $array);
		return $array;
	}
}