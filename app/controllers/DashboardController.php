<?php
namespace App\Controllers;
use App\Services\AuthService;
use App\Services\DashboardService;

class DashboardController extends Controller
{
	private AuthService $authService;
	private DashboardService $dashboardService;

	public function __construct(AuthService $authService, DashboardService $dashboardService)
	{
		$this->authService = $authService;
		$this->dashboardService = $dashboardService;
	}

	public function index()
	{
		$this->authService->requireLogin();
		$this->authService->requireAdmin();
		$data = $this->dashboardService->getAllData();
		$this->renderView("admin/dashboard", $data);
	}
}