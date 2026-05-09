<?php

namespace App\Controllers;

use App\Repositories\RegimeSanteRepository;
use App\Repositories\RegimeSanteRepositoryInterface;
use App\Repositories\RegimeUtilisateurRepository;
use App\Repositories\RegimeUtilisateurRepositoryInterface;

class Profil extends BaseController
{
    protected RegimeUtilisateurRepositoryInterface $utilisateurRepo;
    protected RegimeSanteRepositoryInterface $santeRepo;

    public function __construct(
        ?RegimeUtilisateurRepositoryInterface $utilisateurRepo = null,
        ?RegimeSanteRepositoryInterface $santeRepo = null
    ) {
        $this->utilisateurRepo = $utilisateurRepo ?? new RegimeUtilisateurRepository();
        $this->santeRepo = $santeRepo ?? new RegimeSanteRepository();
    }

    /**
     * @return array<string,string>
     */
    private function objectifsDisponibles(): array
    {
        return [
            'perte_poids' => 'Réduire mon poids',
            'maintien' => 'Atteindre mon IMC idéal',
            'prise_poids' => 'Augmenter mon poids',
        ];
    }

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

        $user = $this->utilisateurRepo->findById($userId);
        $sante = $this->santeRepo->findByUserId($userId);

        if ($user === null) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Utilisateur introuvable. Merci de te reconnecter.',
            ]);
        }

        return view('profil/index', [
            'user' => $user,
            'sante' => $sante,
            'objectifs' => $this->objectifsDisponibles(),
        ]);
    }

    public function updateObjectif()
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

        $objectif = strtolower(trim((string) $this->request->getPost('objectif')));
        $objectifs = $this->objectifsDisponibles();

        if ($objectif === '' || ! array_key_exists($objectif, $objectifs)) {
            return redirect()->back()->withInput()->with('errors', [
                'objectif' => 'Choisis un objectif valide.',
            ]);
        }

        $this->utilisateurRepo->update($userId, [
            'objectif' => $objectif,
        ]);

        return redirect()->to('/profil')->with('success', 'Objectif mis à jour.');
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

        $user = $this->utilisateurRepo->findById($userId);

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

        $email = strtolower(trim((string) $this->request->getPost('email')));
        $existing = $this->utilisateurRepo->findByEmailExceptId($email, $userId);

        if ($existing !== null) {
            return redirect()->back()->withInput()->with('errors', [
                'email' => 'Cet email est deja utilise.',
            ]);
        }

        $this->utilisateurRepo->update($userId, [
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

        $sante = $this->santeRepo->findByUserId($userId);

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

        $existing = $this->santeRepo->findByUserId($userId);

        if ($existing === null) {
            $this->santeRepo->store([
                'user_id' => $userId,
                'taille' => $taille,
                'poids' => $poids,
                'imc' => $imc,
            ]);
        } else {
            $this->santeRepo->update($existing['id'], [
                'taille' => $taille,
                'poids' => $poids,
                'imc' => $imc,
            ]);
        }

        return redirect()->to('/profil')->with('success', 'Informations sante mises a jour.');
    }
}
