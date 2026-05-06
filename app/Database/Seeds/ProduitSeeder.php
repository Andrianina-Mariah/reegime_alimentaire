<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProduitSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom' => 'Clavier mécanique',
                'description' => 'Clavier AZERTY rétroéclairé avec switches tactiles.',
                'prix' => 79.90,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom' => 'Souris ergonomique',
                'description' => 'Souris sans fil pensée pour les longues sessions de travail.',
                'prix' => 34.50,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom' => 'Écran 27 pouces',
                'description' => 'Écran IPS Full HD pour le bureau et le multimédia.',
                'prix' => 189.99,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('produits')->insertBatch($data);
    }
}
