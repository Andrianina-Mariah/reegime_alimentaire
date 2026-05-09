<?php

namespace App\Controllers;

use App\Libraries\GoldOption;
use Dompdf\Dompdf;
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

    public function exportPdf(int $id)
    {
        $userId = $this->requireUserId();
        if (! is_int($userId)) {
            return $userId;
        }

        if (! class_exists(Dompdf::class)) {
            return redirect()->back()->with('errors', [
                'pdf' => 'La librairie PDF est manquante. Installe DomPDF pour continuer.',
            ]);
        }

        if ($id <= 0) {
            return redirect()->back()->with('errors', [
                'regime' => 'Régime invalide.',
            ]);
        }

        $user = $this->utilisateurRepo->findById($userId);
        if ($user === null) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Utilisateur introuvable. Merci de te reconnecter.',
            ]);
        }

        $regime = $this->regimeRepo->findById($id);
        if ($regime === null) {
            return redirect()->back()->with('errors', [
                'regime' => 'Régime introuvable.',
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

        $activites = db_connect()
            ->table('regime_regime_activites rra')
            ->select('a.id, a.nom, a.description, a.duree')
            ->join('regime_activites a', 'a.id = rra.activite_id', 'inner')
            ->where('rra.regime_id', $id)
            ->orderBy('a.nom', 'asc')
            ->get()
            ->getResultArray();

        $isGold = isset($user['is_gold']) && (int) $user['is_gold'] === 1;
        $goldDetails = GoldOption::details();
        $discountRate = (float) ($goldDetails['discountRate'] ?? 0.15);
        $prix = (float) ($regime['prix'] ?? 0);
        $prixFinal = $isGold ? $prix * (1 - $discountRate) : $prix;

        $html = view('regimes/pdf', [
            'user' => $user,
            'sante' => $sante,
            'imc' => $imc,
            'regime' => $regime,
            'activites' => $activites,
            'isGold' => $isGold,
            'goldDetails' => $goldDetails,
            'prixFinal' => $prixFinal,
        ]);

        $dompdf = new Dompdf();
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($html);
        $dompdf->render();

        $filename = 'regime-' . $id . '.pdf';

        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }
}
