<?php
class GlobalHelper
{
	public static function formatText($text)
	{
		return ucwords(str_replace('_', ' ', $text));
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
		return date("h:i:s A", strtotime($time));
	}

	public static function formatEventType($type_name)
	{
		return strtoupper(str_replace("time_", '', $type_name));
	}

	// SORTING

	public static function sortArrayByColumn($array, $column_name)
	{
		$column = array_column($array, $column_name);
		array_multisort($column, SORT_DESC, $array);
		return $array;
	}
}