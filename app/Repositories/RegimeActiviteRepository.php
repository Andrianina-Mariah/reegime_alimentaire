<?php

namespace App\Repositories;

use App\Models\RegimeActiviteModel;

class RegimeActiviteRepository implements RegimeActiviteRepositoryInterface
{
    private RegimeActiviteModel $model;

    public function __construct(?RegimeActiviteModel $model = null)
    {
        $this->model = $model ?? new RegimeActiviteModel();
    }

    public function getAll()
    {
        return $this->model->orderBy('id', 'desc')->findAll();
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
