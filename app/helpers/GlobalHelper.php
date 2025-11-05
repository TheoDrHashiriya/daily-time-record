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
}