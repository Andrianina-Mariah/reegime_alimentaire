<?php

namespace App\Controllers;

use App\Repositories\DashboardRepository;

class AdminDashboard extends BaseController
{
    public function index(): string
    {
        if (! session('is_admin_logged_in')) {
            return redirect()->to('/admin/login')->with('errors', [
                'auth' => 'Connecte-toi en administrateur pour acceder au dashboard.',
            ]);
        }

        $repository = new DashboardRepository();

        return view('admin/dashboard', [
            'stats' => [
                'utilisateurs' => $repository->countUtilisateurs(),
                'regimes' => $repository->countRegimes(),
                'activites' => $repository->countActivites(),
            ],
            'charts' => [
                'utilisateursGenre' => $repository->utilisateursParGenre(),
                'utilisateursStatut' => $repository->utilisateursParStatut(),
                'regimesPrix' => $repository->regimesParPrix(),
                'revenus' => $repository->revenus(),
            ],
        ]);
    }
}
