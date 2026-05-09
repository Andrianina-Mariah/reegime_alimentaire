<?php

namespace App\Repositories;

interface RegimeCodeRepositoryInterface
{
    public function getAll();
    public function findById(int $id);
    public function findByCode(string $code);
    public function store(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
