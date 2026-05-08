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

    public function editPerso()
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
        $user = $utilisateurs->find($userId);

        if ($user === null) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Utilisateur introuvable. Merci de te reconnecter.',
            ]);
        }

        return view('profil/edit_perso', [
            'user' => $user,
        ]);
    }

    public function updatePerso()
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

        $rules = [
            'nom' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'genre' => 'required|in_list[femme,homme]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $utilisateurs = new RegimeUtilisateurModel();
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $existing = $utilisateurs->where('email', $email)->where('id !=', $userId)->first();

        if ($existing !== null) {
            return redirect()->back()->withInput()->with('errors', [
                'email' => 'Cet email est deja utilise.',
            ]);
        }

        $utilisateurs->update($userId, [
            'nom' => trim((string) $this->request->getPost('nom')),
            'email' => $email,
            'genre' => (string) $this->request->getPost('genre'),
        ]);

        session()->set([
            'user_nom' => trim((string) $this->request->getPost('nom')),
            'user_email' => $email,
        ]);

        return redirect()->to('/profil')->with('success', 'Informations personnelles mises a jour.');
    }

    public function editSante()
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

        $santeModel = new RegimeSanteModel();
        $sante = $santeModel->where('user_id', $userId)->first();

        return view('profil/edit_sante', [
            'sante' => $sante,
        ]);
    }

    public function updateSante()
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

        $rules = [
            'taille' => 'required|greater_than_equal_to[80]|less_than_equal_to[240]',
            'poids' => 'required|greater_than_equal_to[20]|less_than_equal_to[300]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $taille = (float) $this->request->getPost('taille');
        $poids = (float) $this->request->getPost('poids');
        $tailleMetres = $taille / 100;
        $imc = $tailleMetres > 0 ? round($poids / ($tailleMetres * $tailleMetres), 2) : 0;

        $santeModel = new RegimeSanteModel();
        $existing = $santeModel->where('user_id', $userId)->first();

        if ($existing === null) {
            $santeModel->insert([
                'user_id' => $userId,
                'taille' => $taille,
                'poids' => $poids,
                'imc' => $imc,
            ]);
        } else {
            $santeModel->update($existing['id'], [
                'taille' => $taille,
                'poids' => $poids,
                'imc' => $imc,
            ]);
        }

        return redirect()->to('/profil')->with('success', 'Informations sante mises a jour.');
    }
}
