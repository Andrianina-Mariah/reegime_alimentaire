<?php

namespace App\Repositories;

interface RegimeAdminRepositoryInterface
{
    public function getAll();
    public function findById(int $id);
    public function findByEmail(string $email);
    public function store(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
