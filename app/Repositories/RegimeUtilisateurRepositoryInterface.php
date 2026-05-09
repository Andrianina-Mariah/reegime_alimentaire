<?php

namespace App\Repositories;

interface RegimeUtilisateurRepositoryInterface
{
    public function getAll();
    public function findById(int $id);
    public function findByEmail(string $email);
    public function findByEmailExceptId(string $email, int $id);
    public function store(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
