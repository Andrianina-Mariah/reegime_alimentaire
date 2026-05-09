<?php

namespace App\Controllers;

use App\Libraries\ObjectifLogic;
use App\Repositories\RegimeSanteRepository;
use App\Repositories\RegimeSanteRepositoryInterface;
use App\Repositories\RegimeUtilisateurRepository;
use App\Repositories\RegimeUtilisateurRepositoryInterface;

class Objectifs extends BaseController
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
            'perte_poids' => 'Perte de poids',
            'maintien' => 'Maintien',
            'prise_poids' => 'Prise de poids',
        ];
    }

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

        $user = $this->utilisateurRepo->findById($userId);
        if ($user === null) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Utilisateur introuvable. Merci de te reconnecter.',
            ]);
        }

        $sante = $this->santeRepo->findByUserId($userId);

        $imc = null;
        if (isset($sante['imc'])) {
            $imc = (float) $sante['imc'];
        } elseif (isset($sante['taille'], $sante['poids'])) {
            $tailleMetres = ((float) $sante['taille']) / 100;
            $imc = $tailleMetres > 0 ? round(((float) $sante['poids']) / ($tailleMetres * $tailleMetres), 2) : null;
        }

        $objectifActuel = (string) ($user['objectif'] ?? '');
        $objectifRecommande = ObjectifLogic::objectifRecommande($imc);

        $impact = null;
        if ($objectifActuel !== '') {
            $impact = ObjectifLogic::impact($objectifActuel, $imc);
        }

        return view('profil/objectif', [
            'user' => $user,
            'sante' => $sante,
            'imc' => $imc,
            'categorieImc' => ObjectifLogic::categorieImc($imc),
            'objectifs' => $this->objectifsDisponibles(),
            'objectifActuel' => $objectifActuel,
            'objectifRecommande' => $objectifRecommande,
            'impact' => $impact,
        ]);
    }

    public function save()
    {
        $userId = $this->requireUserId();
        if (! is_int($userId)) {
            return $userId;
        }

        $objectifs = $this->objectifsDisponibles();
        $objectif = strtolower(trim((string) $this->request->getPost('objectif')));

        if ($objectif === '' || ! array_key_exists($objectif, $objectifs)) {
            return redirect()->back()->withInput()->with('errors', [
                'objectif' => 'Choisis un objectif valide.',
            ]);
        }

        $this->utilisateurRepo->update($userId, [
            'objectif' => $objectif,
        ]);

        return redirect()->to('/profil/objectif')->with('success', 'Objectif enregistré.');
    }
}
