<?php

namespace App\Repositories;

interface RegimeSanteRepositoryInterface
{
    public function getAll();
    public function findById(int $id);
    public function findByUserId(int $userId);
    public function store(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
