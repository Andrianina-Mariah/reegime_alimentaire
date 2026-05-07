<?php

namespace App\Controllers;

use App\Models\RegimeAdminModel;

class AdminAuth extends BaseController
{
    public function login(): string
    {
        return view('admin/login');
    }

    public function authenticate()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $admins = new RegimeAdminModel();
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $admin = $admins->where('email', $email)->first();

        if ($admin === null || ! password_verify((string) $this->request->getPost('password'), $admin['password'])) {
            return redirect()->back()->withInput()->with('errors', [
                'login' => 'Email ou mot de passe administrateur incorrect.',
            ]);
        }

        session()->set([
            'admin_id' => $admin['id'],
            'admin_nom' => $admin['nom'],
            'admin_email' => $admin['email'],
            'is_admin_logged_in' => true,
        ]);

        return redirect()->to('/')->with('success', 'Connexion administrateur reussie.');
    }

    public function logout()
    {
        session()->remove([
            'admin_id',
            'admin_nom',
            'admin_email',
            'is_admin_logged_in',
        ]);

        return redirect()->to('/admin/login')->with('success', 'Session administrateur fermee.');
    }
}
