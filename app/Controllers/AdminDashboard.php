<?php

namespace App\Controllers;

use App\Repositories\DashboardRepository;
use App\Repositories\DashboardRepositoryInterface;

class AdminDashboard extends BaseController
{
    protected DashboardRepositoryInterface $dashboardRepo;

    public function __construct(?DashboardRepositoryInterface $dashboardRepo = null)
    {
        $this->dashboardRepo = $dashboardRepo ?? new DashboardRepository();
    }

    public function index(): string
    {
        if (! session('is_admin_logged_in')) {
            return redirect()->to('/admin/login')->with('errors', [
                'auth' => 'Connecte-toi en administrateur pour acceder au dashboard.',
            ]);
        }

        return view('admin/dashboard', [
            'stats' => [
                'utilisateurs' => $this->dashboardRepo->countUtilisateurs(),
                'regimes' => $this->dashboardRepo->countRegimes(),
                'activites' => $this->dashboardRepo->countActivites(),
            ],
            'charts' => [
                'utilisateursGenre' => $this->dashboardRepo->utilisateursParGenre(),
                'utilisateursStatut' => $this->dashboardRepo->utilisateursParStatut(),
                'regimesPrix' => $this->dashboardRepo->regimesParPrix(),
                'revenus' => $this->dashboardRepo->revenus(),
            ],
        ]);
    }
}
