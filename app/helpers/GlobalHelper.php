<?php
class GlobalHelper
{
	public static function formatText($text)
	{
		return ucwords(str_replace('_', ' ', $text));
	}

	public static function formatDate($date)
	{
		return date("l, M. j, Y", strtotime($date));
	}

	public static function formatTime($time)
	{
		return date("h:i A", strtotime($time));
	}

	public static function formatEventType($type_name)
	{
		return strtoupper(str_replace("time_", '', $type_name));
	}
}