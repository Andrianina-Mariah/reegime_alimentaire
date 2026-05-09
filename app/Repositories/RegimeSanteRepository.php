<?php

namespace App\Repositories;

use App\Models\RegimeSanteModel;

class RegimeSanteRepository implements RegimeSanteRepositoryInterface
{
    private RegimeSanteModel $model;

    public function __construct(?RegimeSanteModel $model = null)
    {
        $this->model = $model ?? new RegimeSanteModel();
    }

    public function getAll()
    {
        return $this->model->findAll();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function findByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)->first();
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
