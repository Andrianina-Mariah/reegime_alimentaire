<?php

namespace App\Controllers;

use App\Repositories\RegimeRepository;
use App\Repositories\RegimeRepositoryInterface;

class AdminRegimes extends BaseController
{
    protected RegimeRepositoryInterface $regimeRepo;

    public function __construct(?RegimeRepositoryInterface $regimeRepo = null)
    {
        $this->regimeRepo = $regimeRepo ?? new RegimeRepository();
    }

    private function requireAdmin()
    {
        if (! session('is_admin_logged_in')) {
            return redirect()->to('/admin/login')->with('errors', [
                'auth' => 'Connecte-toi en administrateur pour acceder aux regimes.',
            ]);
        }

        return null;
    }

    public function index()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        return view('admin/regimes/index', [
            'regimes' => $this->regimeRepo->getAll(),
        ]);
    }

    public function create()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        return view('admin/regimes/create');
    }

    public function store()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $rules = [
            'nom' => 'required|min_length[2]|max_length[100]',
            'duree' => 'required|integer|greater_than[0]',
            'prix' => 'required|numeric|greater_than_equal_to[0]',
            'variation_poids' => 'required|numeric',
            'pourcentage_viande' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'pourcentage_poisson' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'pourcentage_volaille' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->regimeRepo->store([
            'nom' => trim((string) $this->request->getPost('nom')),
            'duree' => (int) $this->request->getPost('duree'),
            'prix' => (float) $this->request->getPost('prix'),
            'variation_poids' => (float) $this->request->getPost('variation_poids'),
            'pourcentage_viande' => (int) $this->request->getPost('pourcentage_viande'),
            'pourcentage_poisson' => (int) $this->request->getPost('pourcentage_poisson'),
            'pourcentage_volaille' => (int) $this->request->getPost('pourcentage_volaille'),
        ]);

        return redirect()->to('/admin/regimes')->with('success', 'Regime ajoute avec succes.');
    }

    public function edit(int $id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $regime = $this->regimeRepo->findById($id);

        if ($regime === null) {
            return redirect()->to('/admin/regimes')->with('errors', [
                'regime' => 'Regime introuvable.',
            ]);
        }

        return view('admin/regimes/edit', [
            'regime' => $regime,
        ]);
    }

    public function update(int $id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $rules = [
            'nom' => 'required|min_length[2]|max_length[100]',
            'duree' => 'required|integer|greater_than[0]',
            'prix' => 'required|numeric|greater_than_equal_to[0]',
            'variation_poids' => 'required|numeric',
            'pourcentage_viande' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'pourcentage_poisson' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'pourcentage_volaille' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $regime = $this->regimeRepo->findById($id);

        if ($regime === null) {
            return redirect()->to('/admin/regimes')->with('errors', [
                'regime' => 'Regime introuvable.',
            ]);
        }

        $this->regimeRepo->update($id, [
            'nom' => trim((string) $this->request->getPost('nom')),
            'duree' => (int) $this->request->getPost('duree'),
            'prix' => (float) $this->request->getPost('prix'),
            'variation_poids' => (float) $this->request->getPost('variation_poids'),
            'pourcentage_viande' => (int) $this->request->getPost('pourcentage_viande'),
            'pourcentage_poisson' => (int) $this->request->getPost('pourcentage_poisson'),
            'pourcentage_volaille' => (int) $this->request->getPost('pourcentage_volaille'),
        ]);

        return redirect()->to('/admin/regimes')->with('success', 'Regime modifie avec succes.');
    }

    public function delete(int $id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $regime = $this->regimeRepo->findById($id);

        if ($regime === null) {
            return redirect()->to('/admin/regimes')->with('errors', [
                'regime' => 'Regime introuvable.',
            ]);
        }

        $this->regimeRepo->delete($id);

        return redirect()->to('/admin/regimes')->with('success', 'Regime supprime avec succes.');
    }
}
