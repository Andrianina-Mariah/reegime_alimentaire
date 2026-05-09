<?php

namespace App\Repositories;

use App\Models\ProduitModel;

class ProduitRepository implements ProduitRepositoryInterface
{
    private ProduitModel $model;

    public function __construct(?ProduitModel $model = null)
    {
        $this->model = $model ?? new ProduitModel();
    }

    public function getAll()
    {
        return $this->model->orderBy('nom', 'ASC')->findAll();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
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
