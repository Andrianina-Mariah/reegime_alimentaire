<?php

namespace App\Controllers;

use App\Libraries\GoldOption;
use App\Repositories\RegimeRepository;
use App\Repositories\RegimeRepositoryInterface;
use App\Repositories\RegimeSanteRepository;
use App\Repositories\RegimeSanteRepositoryInterface;
use App\Repositories\RegimeUtilisateurRepository;
use App\Repositories\RegimeUtilisateurRepositoryInterface;

class Regimes extends BaseController
{
    protected RegimeUtilisateurRepositoryInterface $utilisateurRepo;
    protected RegimeSanteRepositoryInterface $santeRepo;
    protected RegimeRepositoryInterface $regimeRepo;

    public function __construct(
        ?RegimeUtilisateurRepositoryInterface $utilisateurRepo = null,
        ?RegimeSanteRepositoryInterface $santeRepo = null,
        ?RegimeRepositoryInterface $regimeRepo = null
    ) {
        $this->utilisateurRepo = $utilisateurRepo ?? new RegimeUtilisateurRepository();
        $this->santeRepo = $santeRepo ?? new RegimeSanteRepository();
        $this->regimeRepo = $regimeRepo ?? new RegimeRepository();
    }

    /**
     * @return array{key:string,label:string}
     */
    private function categorieImc(?float $imc): array
    {
        if ($imc === null || $imc <= 0) {
            return [
                'key' => 'inconnu',
                'label' => 'IMC non disponible',
            ];
        }

        if ($imc < 18.5) {
            return [
                'key' => 'maigreur',
                'label' => 'Maigreur (< 18.5)',
            ];
        }

        if ($imc < 25) {
            return [
                'key' => 'normal',
                'label' => 'Corpulence normale (18.5–24.9)',
            ];
        }

        if ($imc < 30) {
            return [
                'key' => 'surpoids',
                'label' => 'Surpoids (25–29.9)',
            ];
        }

        return [
            'key' => 'obesite',
            'label' => 'Obésité (≥ 30)',
        ];
    }

    private function resolveObjectif(?string $objectif, array $categorieImc): string
    {
        $objectif = strtolower(trim((string) $objectif));

        if (in_array($objectif, ['perte_poids', 'prise_poids', 'maintien'], true)) {
            return $objectif;
        }

        return match ($categorieImc['key'] ?? 'inconnu') {
            'maigreur' => 'prise_poids',
            'surpoids', 'obesite' => 'perte_poids',
            'normal' => 'maintien',
            default => 'maintien',
        };
    }

    private function requireUserId()
    {
        if (! session('is_logged_in')) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Connecte-toi pour accéder aux régimes.',
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

        $categorieImc = $this->categorieImc($imc);
        $objectif = $this->resolveObjectif($user['objectif'] ?? null, $categorieImc);

        $objectifLabel = match ($objectif) {
            'perte_poids' => 'Perte de poids',
            'prise_poids' => 'Prise de poids',
            default => 'Maintien',
        };

        $regimes = [];
        $filtreElargi = false;

        if ($objectif === 'perte_poids') {
            $regimes = $this->regimeRepo->findByVariationLessThan(0);

            if (count($regimes) <= 1) {
                $filtreElargi = true;
                $regimes = $this->regimeRepo->findByVariationLessThanOrEqual(0);
            }
        } elseif ($objectif === 'prise_poids') {
            $regimes = $this->regimeRepo->findByVariationGreaterThan(0);

            if (count($regimes) <= 1) {
                $filtreElargi = true;
                $regimes = $this->regimeRepo->findByVariationGreaterThanOrEqual(0);
            }
        } else {
            $regimes = $this->regimeRepo->findByVariationBetween(-1, 1);

            if (count($regimes) <= 1) {
                $filtreElargi = true;
                $regimes = $this->regimeRepo->findByVariationBetween(-2, 2);
            }
        }

        $goldDetails = GoldOption::details();

        return view('regimes/index', [
            'user' => $user,
            'sante' => $sante,
            'imc' => $imc,
            'categorieImc' => $categorieImc,
            'objectif' => $objectif,
            'objectifLabel' => $objectifLabel,
            'regimes' => $regimes,
            'filtreElargi' => $filtreElargi,
            'goldDetails' => $goldDetails,
        ]);
    }
}
