<?php

namespace App\Controllers;

use App\Models\RegimeModel;

class Regimes extends BaseController
{
    private function requireUserId()
    {
        if (! session('is_logged_in')) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Connecte-toi pour accéder aux régimes.',
            ]);
        }

        $userId = (int) session('user_id');
        if ($userId <= 0) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Session invalide. Merci de te reconnecter.',
            ]);
        }

        return $userId;
    }

    public function index()
    {
        $userId = $this->requireUserId();
        if (! is_int($userId)) {
            return $userId;
        }

        $model = new RegimeModel();
        $regimes = $model->orderBy('nom', 'asc')->findAll();

        return view('regimes/index', [
            'regimes' => $regimes,
        ]);
    }
}
