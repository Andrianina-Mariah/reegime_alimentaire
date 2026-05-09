<?php

namespace App\Repositories;

use App\Models\RegimeCodeModel;

class RegimeCodeRepository implements RegimeCodeRepositoryInterface
{
    private RegimeCodeModel $model;

    public function __construct(?RegimeCodeModel $model = null)
    {
        $this->model = $model ?? new RegimeCodeModel();
    }

    public function getAll()
    {
        return $this->model->findAll();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
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
