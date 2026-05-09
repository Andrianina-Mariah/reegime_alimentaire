<?php

namespace App\Controllers;

use App\Repositories\RegimeSanteRepository;
use App\Repositories\RegimeSanteRepositoryInterface;
use App\Repositories\RegimeUtilisateurRepository;
use App\Repositories\RegimeUtilisateurRepositoryInterface;
use App\Repositories\RegimeWalletRepository;
use App\Repositories\RegimeWalletRepositoryInterface;

class Auth extends BaseController
{
    protected RegimeUtilisateurRepositoryInterface $utilisateurRepo;
    protected RegimeSanteRepositoryInterface $santeRepo;
    protected RegimeWalletRepositoryInterface $walletRepo;

    public function __construct(
        ?RegimeUtilisateurRepositoryInterface $utilisateurRepo = null,
        ?RegimeSanteRepositoryInterface $santeRepo = null,
        ?RegimeWalletRepositoryInterface $walletRepo = null
    ) {
        $this->utilisateurRepo = $utilisateurRepo ?? new RegimeUtilisateurRepository();
        $this->santeRepo = $santeRepo ?? new RegimeSanteRepository();
        $this->walletRepo = $walletRepo ?? new RegimeWalletRepository();
    }

    public function registerStepOne(): string
    {
        return view('auth/register_step1');
    }

    public function storeRegisterStepOne()
    {
        $rules = [
            'nom' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'password' => 'required|min_length[8]|max_length[255]',
            'genre' => 'required|in_list[femme,homme]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));

        if ($this->utilisateurRepo->findByEmail($email) !== null) {
            return redirect()->back()->withInput()->with('errors', [
                'email' => 'Cet email est deja utilise.',
            ]);
        }

        session()->set('registration_step1', [
            'nom' => trim((string) $this->request->getPost('nom')),
            'email' => $email,
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'genre' => (string) $this->request->getPost('genre'),
        ]);

        return redirect()->to('/inscription/etape-2');
    }

    public function registerStepTwo()
    {
        if (session('registration_step1') === null) {
            return redirect()->to('/inscription')->with('errors', [
                'inscription' => 'Complete d abord la premiere etape.',
            ]);
        }

        return view('auth/register_step2');
    }

    public function storeRegisterStepTwo()
    {
        $stepOne = session('registration_step1');

        if ($stepOne === null) {
            return redirect()->to('/inscription')->with('errors', [
                'inscription' => 'Complete d abord la premiere etape.',
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

        $db = db_connect();
        $db->transStart();

        $userId = $this->utilisateurRepo->store([
            'nom' => $stepOne['nom'],
            'email' => $stepOne['email'],
            'password' => $stepOne['password'],
            'genre' => $stepOne['genre'],
            'is_gold' => 0,
        ]);

        $this->santeRepo->store([
            'user_id' => $userId,
            'taille' => $taille,
            'poids' => $poids,
        ]);

        $this->walletRepo->store([
            'user_id' => $userId,
            'solde' => 0,
        ]);

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('errors', [
                'database' => 'Impossible de finaliser l inscription pour le moment.',
            ]);
        }

        session()->remove('registration_step1');

        return redirect()->to('/login')->with('success', 'Compte cree avec succes. Tu peux maintenant te connecter.');
    }

    public function login(): string
    {
        return view('auth/login');
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

        $email = strtolower(trim((string) $this->request->getPost('email')));
        $user = $this->utilisateurRepo->findByEmail($email);

        if ($user === null || ! password_verify((string) $this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('errors', [
                'login' => 'Email ou mot de passe incorrect.',
            ]);
        }

        session()->set([
            'user_id' => $user['id'],
            'user_nom' => $user['nom'],
            'user_email' => $user['email'],
            'is_logged_in' => true,
        ]);

        return redirect()->to('/profil')->with('success', 'Connexion reussie.');
    }

    public function logout()
    {
        session()->remove([
            'user_id',
            'user_nom',
            'user_email',
            'is_logged_in',
        ]);

        return redirect()->to('/login')->with('success', 'Tu es bien deconnecte.');
    }
}
