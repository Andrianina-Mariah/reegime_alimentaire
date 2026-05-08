<?php

namespace App\Controllers;

use App\Libraries\GoldOption;
use App\Models\RegimeModel;
use App\Models\RegimeSanteModel;
use App\Models\RegimeUtilisateurModel;

class Regimes extends BaseController
{
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

        $utilisateurs = new RegimeUtilisateurModel();
        $santeModel = new RegimeSanteModel();
        $regimeModel = new RegimeModel();

        $user = $utilisateurs->find($userId);
        if ($user === null) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Utilisateur introuvable. Merci de te reconnecter.',
            ]);
        }

        $sante = $santeModel->where('user_id', $userId)->first();
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
            $regimes = $regimeModel
                ->where('variation_poids <', 0)
                ->orderBy('variation_poids', 'asc')
                ->findAll();

            if (count($regimes) <= 1) {
                $filtreElargi = true;
                $regimes = $regimeModel
                    ->where('variation_poids <=', 0)
                    ->orderBy('variation_poids', 'asc')
                    ->findAll();
            }
        } elseif ($objectif === 'prise_poids') {
            $regimes = $regimeModel
                ->where('variation_poids >', 0)
                ->orderBy('variation_poids', 'desc')
                ->findAll();

            if (count($regimes) <= 1) {
                $filtreElargi = true;
                $regimes = $regimeModel
                    ->where('variation_poids >=', 0)
                    ->orderBy('variation_poids', 'desc')
                    ->findAll();
            }
        } else {
            $regimes = $regimeModel
                ->groupStart()
                    ->where('variation_poids >=', -1)
                    ->where('variation_poids <=', 1)
                ->groupEnd()
                ->orderBy('prix', 'asc')
                ->findAll();

            if (count($regimes) <= 1) {
                $filtreElargi = true;
                $regimes = $regimeModel
                    ->groupStart()
                        ->where('variation_poids >=', -2)
                        ->where('variation_poids <=', 2)
                    ->groupEnd()
                    ->orderBy('prix', 'asc')
                    ->findAll();
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
