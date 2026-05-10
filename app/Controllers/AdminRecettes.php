<?php

namespace App\Controllers;

use App\Models\RegimeRecetteModel;

class AdminRecettes extends BaseController
{
    private function requireAdmin()
    {
        if (! session('is_admin_logged_in')) {
            return redirect()->to('/admin/login')->with('errors', [
                'auth' => 'Connecte-toi en administrateur pour acceder aux recettes.',
            ]);
        }

        return null;
    }

    public function index()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $model = new RegimeRecetteModel();

        return view('admin/recettes/index', [
            'recettes' => $model->orderBy('id', 'desc')->findAll(),
        ]);
    }

    public function create()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        return view('admin/recettes/create');
    }

    public function store()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $rules = [
            'nom' => 'required|min_length[2]|max_length[120]',
            'description' => 'permit_empty|max_length[500]',
            'type_repas' => 'required|min_length[3]|max_length[30]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new RegimeRecetteModel();
        $model->insert([
            'nom' => trim((string) $this->request->getPost('nom')),
            'description' => trim((string) $this->request->getPost('description')),
            'type_repas' => trim((string) $this->request->getPost('type_repas')),
        ]);

        return redirect()->to('/admin/recettes')->with('success', 'Recette ajoutee avec succes.');
    }

    public function edit(int $id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $model = new RegimeRecetteModel();
        $recette = $model->find($id);

        if ($recette === null) {
            return redirect()->to('/admin/recettes')->with('errors', [
                'recette' => 'Recette introuvable.',
            ]);
        }

        return view('admin/recettes/edit', [
            'recette' => $recette,
        ]);
    }

    public function update(int $id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $rules = [
            'nom' => 'required|min_length[2]|max_length[120]',
            'description' => 'permit_empty|max_length[500]',
            'type_repas' => 'required|min_length[3]|max_length[30]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new RegimeRecetteModel();
        $recette = $model->find($id);

        if ($recette === null) {
            return redirect()->to('/admin/recettes')->with('errors', [
                'recette' => 'Recette introuvable.',
            ]);
        }

        $model->update($id, [
            'nom' => trim((string) $this->request->getPost('nom')),
            'description' => trim((string) $this->request->getPost('description')),
            'type_repas' => trim((string) $this->request->getPost('type_repas')),
        ]);

        return redirect()->to('/admin/recettes')->with('success', 'Recette modifiee avec succes.');
    }

    public function delete(int $id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $model = new RegimeRecetteModel();
        $recette = $model->find($id);

        if ($recette === null) {
            return redirect()->to('/admin/recettes')->with('errors', [
                'recette' => 'Recette introuvable.',
            ]);
        }

        $model->delete($id);

        return redirect()->to('/admin/recettes')->with('success', 'Recette supprimee avec succes.');
    }
}
