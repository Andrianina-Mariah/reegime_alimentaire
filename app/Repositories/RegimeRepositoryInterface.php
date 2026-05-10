<?php

namespace App\Repositories;

interface RegimeRepositoryInterface
{
    public function getAll();
    public function getAllWithRevenue();
    public function findById(int $id);
    public function findByVariationLessThan(float $value);
    public function findByVariationLessThanOrEqual(float $value);
    public function findByVariationGreaterThan(float $value);
    public function findByVariationGreaterThanOrEqual(float $value);
    public function findByVariationBetween(float $min, float $max);
    public function store(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
