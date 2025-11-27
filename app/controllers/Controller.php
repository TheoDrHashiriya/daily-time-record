<?php
namespace App\Controllers;
class Controller
{
	protected function renderView(array $views, array $data = [])
	{
		extract($data);
		foreach ($views as $i => $view) {
			require_once __DIR__ . "/../views/" . $view;
		}
	}
}