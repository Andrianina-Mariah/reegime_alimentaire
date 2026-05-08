<?php

namespace App\Repositories;

class DashboardRepository
{
    private $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function countUtilisateurs(): int
    {
        return (int) $this->db->table('regime_utilisateurs')->countAllResults();
    }

    public function countRegimes(): int
    {
        return (int) $this->db->table('regime_regimes')->countAllResults();
    }

    public function countActivites(): int
    {
        return (int) $this->db->table('regime_activites')->countAllResults();
    }

    public function utilisateursParGenre(): array
    {
        return $this->db->table('regime_utilisateurs')
            ->select('genre AS label, COUNT(*) AS total')
            ->groupBy('genre')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function utilisateursParStatut(): array
    {
        return $this->db->table('regime_utilisateurs')
            ->select("CASE WHEN is_gold = 1 THEN 'Gold' ELSE 'Standard' END AS label, COUNT(*) AS total", false)
            ->groupBy('is_gold')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function regimesParPrix(): array
    {
        return $this->db->table('regime_regimes')
            ->select('nom AS label, prix AS total')
            ->orderBy('prix', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function revenus(): array
    {
        $regimes = $this->db->table('regime_regimes')
            ->selectSum('prix', 'total')
            ->get()
            ->getRowArray();

        $wallet = $this->db->table('regime_wallet')
            ->selectSum('solde', 'total')
            ->get()
            ->getRowArray();

        $codesUtilises = $this->db->table('regime_codes')
            ->selectSum('montant', 'total')
            ->where('used', 1)
            ->get()
            ->getRowArray();

        return [
            ['label' => 'Valeur regimes', 'total' => (float) ($regimes['total'] ?? 0)],
            ['label' => 'Soldes wallet', 'total' => (float) ($wallet['total'] ?? 0)],
            ['label' => 'Codes utilises', 'total' => (float) ($codesUtilises['total'] ?? 0)],
        ];
    }
}
