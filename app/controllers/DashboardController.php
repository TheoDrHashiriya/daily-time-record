<?php
namespace App\Controllers;
use App\Models\User;
use App\Services\AuthService;
use App\Services\DashboardService;

class DashboardController extends Controller
{
	private $authService;
	private $dashboardService;

	public function __construct()
	{
		$this->authService = new AuthService(new User);
		$this->dashboardService = new DashboardService;
	}

	public function index()
	{
		$this->authService->requireLogin();
		$this->authService->requireAdmin();
		$data = $this->dashboardService->getAllData();
		$this->renderView(["admin/dashboard.php"], $data);
	}
}