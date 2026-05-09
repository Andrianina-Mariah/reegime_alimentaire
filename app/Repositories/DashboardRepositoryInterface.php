<?php

namespace App\Repositories;

interface DashboardRepositoryInterface
{
    public function countUtilisateurs(): int;
    public function countRegimes(): int;
    public function countActivites(): int;
    public function utilisateursParGenre(): array;
    public function utilisateursParStatut(): array;
    public function regimesParPrix(): array;
    public function revenus(): array;
}
