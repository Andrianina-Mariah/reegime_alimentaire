<?php

namespace App\Controllers;

use App\Models\RegimeSanteModel;
use App\Models\RegimeUtilisateurModel;

class Profil extends BaseController
{
    public function index()
    {
        if (! session('is_logged_in')) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Connecte-toi pour acceder a ton profil.',
            ]);
        }

        $userId = (int) session('user_id');

        if ($userId <= 0) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Session invalide. Merci de te reconnecter.',
            ]);
        }

        $utilisateurs = new RegimeUtilisateurModel();
        $santeModel = new RegimeSanteModel();

        $user = $utilisateurs->find($userId);
        $sante = $santeModel->where('user_id', $userId)->first();

        if ($user === null) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Utilisateur introuvable. Merci de te reconnecter.',
            ]);
        }

        return view('profil/index', [
            'user' => $user,
            'sante' => $sante,
        ]);
    }
}
