<?php

namespace App\Repositories;

interface RegimeActiviteRepositoryInterface
{
    public function getAll();
    public function findById(int $id);
    public function store(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
