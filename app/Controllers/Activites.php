<?php

namespace App\Controllers;

use App\Repositories\RegimeRepository;
use App\Repositories\RegimeRepositoryInterface;

class Activites extends BaseController
{
    protected RegimeRepositoryInterface $regimeRepo;

    public function __construct(?RegimeRepositoryInterface $regimeRepo = null)
    {
        $this->regimeRepo = $regimeRepo ?? new RegimeRepository();
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

    private function requireAdminId()
    {
        if (! session('is_admin_logged_in')) {
            return redirect()->to('/admin/login')->with('errors', [
                'auth' => 'Connecte-toi en tant qu\'administrateur.',
            ]);
        }

        $adminId = (int) session('admin_id');
        if ($adminId <= 0) {
            return redirect()->to('/admin/login')->with('errors', [
                'auth' => 'Session admin invalide. Merci de te reconnecter.',
            ]);
        }

        return $adminId;
    }

    /**
     * Page utilisateur: affiche les activités recommandées pour un régime donné.
     */
    public function recommandees(int $regimeId)
    {
        $userId = $this->requireUserId();
        if (! is_int($userId)) {
            return $userId;
        }

        if ($regimeId <= 0) {
            return redirect()->to('/profil')->with('errors', [
                'regime' => 'Régime invalide.',
            ]);
        }

        $regime = $this->regimeRepo->findById($regimeId);
        if ($regime === null) {
            return redirect()->to('/profil')->with('errors', [
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

        return view('activites/recommandees', [
            'regime' => $regime,
            'activites' => $activites,
        ]);
    }

    /**
     * Admin: associe une liste d'activités à un régime.
     *
     * Attendu: POST activite_ids[]
     */
    public function associerAuRegime(int $regimeId)
    {
        $adminId = $this->requireAdminId();
        if (! is_int($adminId)) {
            return $adminId;
        }

        if ($regimeId <= 0) {
            return redirect()->back()->with('errors', [
                'regime' => 'Régime invalide.',
            ]);
        }

        if ($this->regimeRepo->findById($regimeId) === null) {
            return redirect()->back()->with('errors', [
                'regime' => 'Régime introuvable.',
            ]);
        }

        $ids = $this->request->getPost('activite_ids');
        if (! is_array($ids)) {
            $ids = [];
        }

        $activiteIds = [];
        foreach ($ids as $id) {
            $id = (int) $id;
            if ($id > 0) {
                $activiteIds[$id] = true;
            }
        }
        $activiteIds = array_keys($activiteIds);

        $db = db_connect();
        $db->transStart();

        $db->table('regime_regime_activites')->where('regime_id', $regimeId)->delete();

        foreach ($activiteIds as $activiteId) {
            $db->table('regime_regime_activites')->insert([
                'regime_id' => $regimeId,
                'activite_id' => $activiteId,
            ]);
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->with('errors', [
                'database' => 'Impossible de sauvegarder les associations.',
            ]);
        }

        return redirect()->back()->with('success', 'Activités associées au régime.');
    }
}
