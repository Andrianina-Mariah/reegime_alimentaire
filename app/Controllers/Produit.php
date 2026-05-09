<?php

namespace App\Controllers;

use App\Repositories\ProduitRepository;
use App\Repositories\ProduitRepositoryInterface;
use CodeIgniter\Exceptions\PageNotFoundException;

class Produit extends BaseController
{
    protected ProduitRepositoryInterface $produitRepo;

    public function __construct(?ProduitRepositoryInterface $produitRepo = null)
    {
        $this->produitRepo = $produitRepo ?? new ProduitRepository();
    }

    public function index(): string
    {
        $data = [
            'produits' => $this->produitRepo->getAll(),
        ];

        return view('ProduitView', $data);
    }

    public function show(int $id): string
    {
        $produit = $this->produitRepo->findById($id);

        if ($produit === null) {
            throw PageNotFoundException::forPageNotFound('Produit introuvable : ' . $id);
        }

        return 'Produit ID : ' . $id . ' - ' . esc($produit['nom']);
    }
}
