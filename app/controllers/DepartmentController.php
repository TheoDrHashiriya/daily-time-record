<?php
require_once __DIR__ . "/../models/Department.php";
class DepartmentController extends Department
{
	private $depModel;

	public function __construct()
	{
		$this->depModel = new Department();
	}

	// FOR KPIS
	public function getTotal()
	{
		$departments = $this->depModel->getAll();
		return count($departments);
	}

	// MAIN

	public function getAll()
	{
		return $this->depModel->getAll();
	}

	public function getById($id)
	{
		return $this->depModel->getById($id);
	}

	public function delete($id)
	{
		return $this->depModel->delete($id);
	}
}