<?php

namespace App\Repositories;

use App\Models\RegimeUtilisateurModel;

class RegimeUtilisateurRepository implements RegimeUtilisateurRepositoryInterface
{
    private RegimeUtilisateurModel $model;

    public function __construct(?RegimeUtilisateurModel $model = null)
    {
        $this->model = $model ?? new RegimeUtilisateurModel();
    }

    public function getAll()
    {
        return $this->model->findAll();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByEmailExceptId(string $email, int $id)
    {
        return $this->model->where('email', $email)->where('id !=', $id)->first();
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
