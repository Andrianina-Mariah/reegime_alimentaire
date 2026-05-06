<?php

namespace App\Controllers;

use App\Models\ProduitModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Produit extends BaseController
{
    public function index(): string
    {
        $model = new ProduitModel();

        $data = [
            'produits' => $model->orderBy('nom', 'ASC')->findAll(),
        ];

        return view('ProduitView', $data);
    }

    public function show(int $id): string
    {
        $model = new ProduitModel();
        $produit = $model->find($id);

        if ($produit === null) {
            throw PageNotFoundException::forPageNotFound('Produit introuvable : ' . $id);
        }

        return 'Produit ID : ' . $id . ' - ' . esc($produit['nom']);
    }
}