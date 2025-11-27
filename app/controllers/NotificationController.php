<?php
namespace App\Controllers;
use App\Models\Notification;

class NotificationController
{
	private $notifModel;

	public function __construct()
	{
		$this->notifModel = new Notification();
	}

	// FOR KPIS
	public function getTotal()
	{
		$notifications = $this->notifModel->getAll();
		return count($notifications);
	}

	// MAIN

	public function getAll()
	{
		return $this->notifModel->getAll();
	}

	public function getById($id)
	{
		return $this->notifModel->getById($id);
	}

	public function create($title, $content, $created_by)
	{
		return $this->notifModel->create($title, $content, $created_by);
	}

	public function delete($id)
	{
		return $this->notifModel->delete($id);
	}
}