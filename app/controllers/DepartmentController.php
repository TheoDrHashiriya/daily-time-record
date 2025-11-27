<?php
namespace App\Controllers;
use App\Models\Department;

class DepartmentController extends Controller
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