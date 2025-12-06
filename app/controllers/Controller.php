<?php
namespace App\Controllers;
class Controller
{
	protected function renderView($view, $data = [])
	{
		extract($data);
		require_once VIEWS_PATH . '/' . $view . ".php";

	}

	protected function renderViews($views = [], $data = [])
	{
		extract($data);
		foreach ($views as $i => $view) {
			require_once VIEWS_PATH . '/' . $view . ".php";
		}
	}
}