<?php

namespace App\Repositories;

interface RegimeWalletRepositoryInterface
{
    public function getAll();
    public function findById(int $id);
    public function findByUserId(int $userId);
    public function store(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
