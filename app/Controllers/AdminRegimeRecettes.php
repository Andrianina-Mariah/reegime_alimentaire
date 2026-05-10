<?php

namespace App\Controllers;

use App\Models\RegimeModel;
use App\Models\RegimeRecetteModel;
use App\Models\RegimeRegimeRecetteModel;

class AdminRegimeRecettes extends BaseController
{
    private function requireAdmin()
    {
        if (! session('is_admin_logged_in')) {
            return redirect()->to('/admin/login')->with('errors', [
                'auth' => 'Connecte-toi en administrateur pour acceder aux associations.',
            ]);
        }

        return null;
    }

    public function index()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $associations = db_connect()
            ->table('regime_regime_recettes rrr')
            ->select('rrr.id, rrr.jour, r.nom AS regime_nom, rec.nom AS recette_nom, rec.type_repas')
            ->join('regime_regimes r', 'r.id = rrr.regime_id', 'inner')
            ->join('regime_recettes rec', 'rec.id = rrr.recette_id', 'inner')
            ->orderBy('r.nom', 'asc')
            ->orderBy('rrr.jour', 'asc')
            ->orderBy('rec.nom', 'asc')
            ->get()
            ->getResultArray();

        return view('admin/regime_recettes/index', [
            'associations' => $associations,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $regimes = (new RegimeModel())->orderBy('nom', 'asc')->findAll();
        $recettes = (new RegimeRecetteModel())->orderBy('nom', 'asc')->findAll();

        return view('admin/regime_recettes/create', [
            'regimes' => $regimes,
            'recettes' => $recettes,
        ]);
    }

    public function store()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $rules = [
            'regime_id' => 'required|integer|greater_than[0]',
            'recette_id' => 'required|integer|greater_than[0]',
            'jour' => 'required|integer|greater_than[0]|less_than_equal_to[30]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new RegimeRegimeRecetteModel();
        $model->insert([
            'regime_id' => (int) $this->request->getPost('regime_id'),
            'recette_id' => (int) $this->request->getPost('recette_id'),
            'jour' => (int) $this->request->getPost('jour'),
        ]);

        return redirect()->to('/admin/planning-recettes')->with('success', 'Association ajoutée avec succès.');
    }

    public function edit(int $id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $model = new RegimeRegimeRecetteModel();
        $association = $model->find($id);

        if ($association === null) {
            return redirect()->to('/admin/planning-recettes')->with('errors', [
                'association' => 'Association introuvable.',
            ]);
        }

        $regimes = (new RegimeModel())->orderBy('nom', 'asc')->findAll();
        $recettes = (new RegimeRecetteModel())->orderBy('nom', 'asc')->findAll();

        return view('admin/regime_recettes/edit', [
            'association' => $association,
            'regimes' => $regimes,
            'recettes' => $recettes,
        ]);
    }

    public function update(int $id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $rules = [
            'regime_id' => 'required|integer|greater_than[0]',
            'recette_id' => 'required|integer|greater_than[0]',
            'jour' => 'required|integer|greater_than[0]|less_than_equal_to[30]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new RegimeRegimeRecetteModel();
        $association = $model->find($id);

        if ($association === null) {
            return redirect()->to('/admin/planning-recettes')->with('errors', [
                'association' => 'Association introuvable.',
            ]);
        }

        $model->update($id, [
            'regime_id' => (int) $this->request->getPost('regime_id'),
            'recette_id' => (int) $this->request->getPost('recette_id'),
            'jour' => (int) $this->request->getPost('jour'),
        ]);

        return redirect()->to('/admin/planning-recettes')->with('success', 'Association modifiée avec succès.');
    }

    public function delete(int $id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $model = new RegimeRegimeRecetteModel();
        $association = $model->find($id);

        if ($association === null) {
            return redirect()->to('/admin/planning-recettes')->with('errors', [
                'association' => 'Association introuvable.',
            ]);
        }

        $model->delete($id);

        return redirect()->to('/admin/planning-recettes')->with('success', 'Association supprimée avec succès.');
    }
}
