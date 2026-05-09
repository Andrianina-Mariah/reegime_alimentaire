<?php

namespace App\Controllers;

use App\Models\RegimeActiviteModel;


class UtilisateurActivites extends BaseController
{
    private function requireUserId()
    {
        if (! session('is_logged_in')) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Connecte-toi pour accéder à cette page.',
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

        $model = new RegimeActiviteModel();
        $activites = $model->orderBy('nom', 'asc')->findAll();

        return view('activites/index', [
            'activites' => $activites,
        ]);
    }

    public function create()
    {
        $userId = $this->requireUserId();
        if (! is_int($userId)) {
            return $userId;
        }

        return view('activites/form', [
            'mode' => 'create',
            'action' => '/activites',
            'activite' => [
                'nom' => '',
                'duree' => '',
                'description' => '',
            ],
        ]);
    }

    public function store()
    {
        $userId = $this->requireUserId();
        if (! is_int($userId)) {
            return $userId;
        }

        $rules = [
            'nom' => 'required|min_length[2]|max_length[100]',
            'duree' => 'required|is_natural_no_zero|less_than_equal_to[600]',
            'description' => 'permit_empty|max_length[2000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new RegimeActiviteModel();
        $model->insert([
            'nom' => trim((string) $this->request->getPost('nom')),
            'duree' => (int) $this->request->getPost('duree'),
            'description' => trim((string) $this->request->getPost('description')),
        ]);

        return redirect()->to('/activites')->with('success', 'Activité ajoutée.');
    }

    public function edit(int $id)
    {
        $userId = $this->requireUserId();
        if (! is_int($userId)) {
            return $userId;
        }

        if ($id <= 0) {
            return redirect()->to('/activites')->with('errors', [
                'activite' => 'Activité invalide.',
            ]);
        }

        $model = new RegimeActiviteModel();
        $activite = $model->find($id);
        if ($activite === null) {
            return redirect()->to('/activites')->with('errors', [
                'activite' => 'Activité introuvable.',
            ]);
        }

        return view('activites/form', [
            'mode' => 'edit',
            'action' => '/activites/' . $id . '/modifier',
            'activite' => $activite,
        ]);
    }

    public function update(int $id)
    {
        $userId = $this->requireUserId();
        if (! is_int($userId)) {
            return $userId;
        }

        if ($id <= 0) {
            return redirect()->to('/activites')->with('errors', [
                'activite' => 'Activité invalide.',
            ]);
        }

        $rules = [
            'nom' => 'required|min_length[2]|max_length[100]',
            'duree' => 'required|is_natural_no_zero|less_than_equal_to[600]',
            'description' => 'permit_empty|max_length[2000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new RegimeActiviteModel();
        if ($model->find($id) === null) {
            return redirect()->to('/activites')->with('errors', [
                'activite' => 'Activité introuvable.',
            ]);
        }

        $model->update($id, [
            'nom' => trim((string) $this->request->getPost('nom')),
            'duree' => (int) $this->request->getPost('duree'),
            'description' => trim((string) $this->request->getPost('description')),
        ]);

        return redirect()->to('/activites')->with('success', 'Activité modifiée.');
    }

    public function delete(int $id)
    {
        $userId = $this->requireUserId();
        if (! is_int($userId)) {
            return $userId;
        }

        if ($id <= 0) {
            return redirect()->to('/activites')->with('errors', [
                'activite' => 'Activité invalide.',
            ]);
        }

        $model = new RegimeActiviteModel();
        if ($model->find($id) === null) {
            return redirect()->to('/activites')->with('errors', [
                'activite' => 'Activité introuvable.',
            ]);
        }

        $model->delete($id);

        return redirect()->to('/activites')->with('success', 'Activité supprimée.');
    }
}

