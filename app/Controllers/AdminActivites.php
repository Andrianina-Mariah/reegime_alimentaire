<?php

namespace App\Controllers;

use App\Repositories\RegimeActiviteRepository;
use App\Repositories\RegimeActiviteRepositoryInterface;

class AdminActivites extends BaseController
{
    protected RegimeActiviteRepositoryInterface $activiteRepo;

    public function __construct(?RegimeActiviteRepositoryInterface $activiteRepo = null)
    {
        $this->activiteRepo = $activiteRepo ?? new RegimeActiviteRepository();
    }

    private function requireAdmin()
    {
        if (! session('is_admin_logged_in')) {
            return redirect()->to('/admin/login')->with('errors', [
                'auth' => 'Connecte-toi en administrateur pour acceder aux activites.',
            ]);
        }

        return null;
    }

    public function index()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        return view('admin/activites/index', [
            'activites' => $this->activiteRepo->getAll(),
        ]);
    }

    public function create()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        return view('admin/activites/create');
    }

    public function store()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $rules = [
            'nom' => 'required|min_length[2]|max_length[100]',
            'description' => 'permit_empty|max_length[255]',
            'duree' => 'required|integer|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->activiteRepo->store([
            'nom' => trim((string) $this->request->getPost('nom')),
            'description' => trim((string) $this->request->getPost('description')),
            'duree' => (int) $this->request->getPost('duree'),
        ]);

        return redirect()->to('/admin/activites')->with('success', 'Activite ajoutee avec succes.');
    }

    public function edit(int $id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $activite = $this->activiteRepo->findById($id);

        if ($activite === null) {
            return redirect()->to('/admin/activites')->with('errors', [
                'activite' => 'Activite introuvable.',
            ]);
        }

        return view('admin/activites/edit', [
            'activite' => $activite,
        ]);
    }

    public function update(int $id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $rules = [
            'nom' => 'required|min_length[2]|max_length[100]',
            'description' => 'permit_empty|max_length[255]',
            'duree' => 'required|integer|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $activite = $this->activiteRepo->findById($id);

        if ($activite === null) {
            return redirect()->to('/admin/activites')->with('errors', [
                'activite' => 'Activite introuvable.',
            ]);
        }

        $this->activiteRepo->update($id, [
            'nom' => trim((string) $this->request->getPost('nom')),
            'description' => trim((string) $this->request->getPost('description')),
            'duree' => (int) $this->request->getPost('duree'),
        ]);

        return redirect()->to('/admin/activites')->with('success', 'Activite modifiee avec succes.');
    }

    public function delete(int $id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $activite = $this->activiteRepo->findById($id);

        if ($activite === null) {
            return redirect()->to('/admin/activites')->with('errors', [
                'activite' => 'Activite introuvable.',
            ]);
        }

        $this->activiteRepo->delete($id);

        return redirect()->to('/admin/activites')->with('success', 'Activite supprimee avec succes.');
    }
}
