<?php

namespace App\Controllers;

use App\Libraries\ObjectifLogic;
use App\Models\RegimeModel;
use App\Models\RegimeSanteModel;
use App\Models\RegimeUtilisateurModel;

class Regimes extends BaseController
{
    public function suggestions()
    {
        if (! session('is_logged_in')) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Connecte-toi pour accéder aux suggestions.',
            ]);
        }

        $userId = (int) session('user_id');
        if ($userId <= 0) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Session invalide. Merci de te reconnecter.',
            ]);
        }

        $utilisateurs = new RegimeUtilisateurModel();
        $santeModel = new RegimeSanteModel();
        $regimesModel = new RegimeModel();

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

        $objectif = (string) ($user['objectif'] ?? '');
        if ($objectif === '') {
            $categorie = ObjectifLogic::categorieImc($imc);
            $objectif = match ($categorie['key']) {
                'maigreur' => 'prise_poids',
                'surpoids', 'obesite' => 'perte_poids',
                'normal' => 'maintien',
                default => 'maintien',
            };
        }

        $objectifLabel = match ($objectif) {
            'perte_poids' => 'Perte de poids',
            'prise_poids' => 'Prise de poids',
            default => 'Maintien',
        };

        $regimes = [];
        $filtreElargi = false;

        if ($objectif === 'perte_poids') {
            $regimes = $regimesModel
                ->where('variation_poids <', 0)
                ->orderBy('variation_poids', 'asc')
                ->findAll();

            if (count($regimes) <= 1) {
                $filtreElargi = true;
                $regimes = $regimesModel
                    ->where('variation_poids <=', 0)
                    ->orderBy('variation_poids', 'asc')
                    ->findAll();
            }
        } elseif ($objectif === 'prise_poids') {
            $regimes = $regimesModel
                ->where('variation_poids >', 0)
                ->orderBy('variation_poids', 'desc')
                ->findAll();

            if (count($regimes) <= 1) {
                $filtreElargi = true;
                $regimes = $regimesModel
                    ->where('variation_poids >=', 0)
                    ->orderBy('variation_poids', 'desc')
                    ->findAll();
            }
        } else {
            $regimes = $regimesModel
                ->groupStart()
                    ->where('variation_poids >=', -1)
                    ->where('variation_poids <=', 1)
                ->groupEnd()
                ->orderBy('prix', 'asc')
                ->findAll();

            if (count($regimes) <= 1) {
                $filtreElargi = true;
                $regimes = $regimesModel
                    ->groupStart()
                        ->where('variation_poids >=', -2)
                        ->where('variation_poids <=', 2)
                    ->groupEnd()
                    ->orderBy('prix', 'asc')
                    ->findAll();
            }
        }
        $categorieImc = ObjectifLogic::categorieImc($imc);

        return view('regimes/suggestions', [
            'user' => $user,
            'sante' => $sante,
            'imc' => $imc,
            'categorieImc' => $categorieImc,
            'objectif' => $objectif,
            'objectifLabel' => $objectifLabel,
            'regimes' => $regimes,
            'filtreElargi' => $filtreElargi,
        ]);
    }
}
