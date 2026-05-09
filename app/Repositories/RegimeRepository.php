<?php

namespace App\Repositories;

use App\Models\RegimeModel;

class RegimeRepository implements RegimeRepositoryInterface
{
    private RegimeModel $model;

    public function __construct(?RegimeModel $model = null)
    {
        $this->model = $model ?? new RegimeModel();
    }

    public function getAll()
    {
        return $this->model->orderBy('id', 'desc')->findAll();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function findByVariationLessThan(float $value)
    {
        return $this->model->where('variation_poids <', $value)->orderBy('variation_poids', 'asc')->findAll();
    }

    public function findByVariationLessThanOrEqual(float $value)
    {
        return $this->model->where('variation_poids <=', $value)->orderBy('variation_poids', 'asc')->findAll();
    }

    public function findByVariationGreaterThan(float $value)
    {
        return $this->model->where('variation_poids >', $value)->orderBy('variation_poids', 'desc')->findAll();
    }

    public function findByVariationGreaterThanOrEqual(float $value)
    {
        return $this->model->where('variation_poids >=', $value)->orderBy('variation_poids', 'desc')->findAll();
    }

    public function findByVariationBetween(float $min, float $max)
    {
        return $this->model
            ->groupStart()
                ->where('variation_poids >=', $min)
                ->where('variation_poids <=', $max)
            ->groupEnd()
            ->orderBy('prix', 'asc')
            ->findAll();
    }

    public function store(array $data)
    {
        return $this->model->insert($data);
    }

    public function update(int $id, array $data)
    {
        return $this->model->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->model->delete($id);
    }
}
