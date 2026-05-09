<?php

namespace App\Controllers;

use App\Libraries\GoldOption;
use Dompdf\Dompdf;
use Dompdf\Options;
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

            $imc = $tailleMetres > 0
                ? round(((float) $sante['poids']) / ($tailleMetres * $tailleMetres), 2)
                : null;
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

    public function pdf(int $regimeId)
    {
        $userId = $this->requireUserId();

        if (! is_int($userId)) {
            return $userId;
        }

        if ($regimeId <= 0) {
            return redirect()->to('/regimes')->with('errors', [
                'regime' => 'Régime invalide.',
            ]);
        }

        if (! class_exists(Dompdf::class)) {
            return redirect()->back()->with('errors', [
                'pdf' => 'La librairie PDF est manquante. Installe DomPDF pour continuer.',
            ]);
        }

        $user = $this->utilisateurRepo->findById($userId);

        if ($user === null) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Utilisateur introuvable. Merci de te reconnecter.',
            ]);
        }

        $regime = $this->regimeRepo->findById($regimeId);

        if ($regime === null) {
            return redirect()->to('/regimes')->with('errors', [
                'regime' => 'Régime introuvable.',
            ]);
        }

        $activites = db_connect()
            ->table('regime_regime_activites rra')
            ->select('a.id, a.nom, a.description, a.duree')
            ->join('regime_activites a', 'a.id = rra.activite_id', 'inner')
            ->where('rra.regime_id', $regimeId)
            ->orderBy('a.nom', 'asc')
            ->get()
            ->getResultArray();

        $sante = $this->santeRepo->findByUserId($userId);

        $imc = null;

        if (isset($sante['imc'])) {
            $imc = (float) $sante['imc'];
        } elseif (isset($sante['taille'], $sante['poids'])) {
            $tailleMetres = ((float) $sante['taille']) / 100;

            $imc = $tailleMetres > 0
                ? round(((float) $sante['poids']) / ($tailleMetres * $tailleMetres), 2)
                : null;
        }

        $categorieImc = $this->categorieImc($imc);

        $objectif = $this->resolveObjectif(
            $user['objectif'] ?? null,
            $categorieImc
        );

        $objectifLabel = match ($objectif) {
            'perte_poids' => 'Perte de poids',
            'prise_poids' => 'Prise de poids',
            default => 'Maintien',
        };

        $goldDetails = GoldOption::details();

        $isGold = isset($user['is_gold']) && (int) $user['is_gold'] === 1;

        $discountRate = (float) ($goldDetails['discountRate'] ?? 0.15);

        $prix = (float) ($regime['prix'] ?? 0);

        $prixFinal = $isGold
            ? $prix * (1 - $discountRate)
            : $prix;

        $formatNumber = static function ($value): string {
            return number_format((float) $value, 0, ',', ' ');
        };

        $html = view('regimes/pdf', [
            'regime' => $regime,
            'activites' => $activites,
            'user' => $user,
            'sante' => $sante,
            'imc' => $imc,
            'isGold' => $isGold,
            'goldDetails' => $goldDetails,
            'prixFinal' => $prixFinal,
            'formatNumber' => $formatNumber,
            'objectifLabel' => $objectifLabel,
            'categorieImcLabel' => $categorieImc['label'] ?? null,
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $nom = (string) ($regime['nom'] ?? 'regime');

        $safeNom = preg_replace('/[^a-zA-Z0-9\-_]+/', '_', $nom) ?: 'regime';

        $filename = sprintf(
            'regime_%d_%s.pdf',
            $regimeId,
            $safeNom
        );

        return $this->response
            ->setContentType('application/pdf')
            ->setHeader(
                'Content-Disposition',
                'attachment; filename="' . $filename . '"'
            )
            ->setBody($dompdf->output());
    }
}