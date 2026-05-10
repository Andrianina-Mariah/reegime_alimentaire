<?php

namespace App\Controllers;

use App\Repositories\RegimeCodeRepository;
use App\Repositories\RegimeCodeRepositoryInterface;

class AdminCodes extends BaseController
{
    protected RegimeCodeRepositoryInterface $codeRepo;

    public function __construct(?RegimeCodeRepositoryInterface $codeRepo = null)
    {
        $this->codeRepo = $codeRepo ?? new RegimeCodeRepository();
    }

    private function requireAdmin()
    {
        if (! session('is_admin_logged_in')) {
            return redirect()->to('/admin/login')->with('errors', [
                'auth' => 'Connecte-toi en administrateur pour acceder aux codes.',
            ]);
        }

        return null;
    }

    public function index()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $status = strtolower((string) $this->request->getGet('status'));
        $codes = $this->codeRepo->getAll();

        $usedCount = 0;
        foreach ($codes as $row) {
            if ((int) ($row['used'] ?? 0) === 1) {
                $usedCount++;
            }
        }

        $filtered = $codes;
        if ($status === 'used') {
            $filtered = array_values(array_filter($codes, static fn ($row) => (int) ($row['used'] ?? 0) === 1));
        } elseif ($status === 'unused') {
            $filtered = array_values(array_filter($codes, static fn ($row) => (int) ($row['used'] ?? 0) === 0));
        }

        return view('admin/codes/index', [
            'codes' => $filtered,
            'status' => $status,
            'stats' => [
                'total' => count($codes),
                'used' => $usedCount,
                'unused' => count($codes) - $usedCount,
            ],
        ]);
    }

    public function create()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        return view('admin/codes/create');
    }

    public function store()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $rules = [
            'code' => 'required|min_length[4]|max_length[20]',
            'montant' => 'required|numeric|greater_than_equal_to[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $code = strtoupper(trim((string) $this->request->getPost('code')));
        if ($code === '') {
            return redirect()->back()->withInput()->with('errors', [
                'code' => 'Le code est requis.',
            ]);
        }

        if ($this->codeRepo->findByCode($code) !== null) {
            return redirect()->back()->withInput()->with('errors', [
                'code' => 'Ce code existe deja.',
            ]);
        }

        $this->codeRepo->store([
            'code' => $code,
            'montant' => (float) $this->request->getPost('montant'),
            'used' => 0,
        ]);

        return redirect()->to('/admin/codes')->with('success', 'Code cree avec succes.');
    }

    public function delete(int $id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $code = $this->codeRepo->findById($id);

        if ($code === null) {
            return redirect()->to('/admin/codes')->with('errors', [
                'code' => 'Code introuvable.',
            ]);
        }

        $this->codeRepo->delete($id);

        return redirect()->to('/admin/codes')->with('success', 'Code supprime avec succes.');
    }
}
