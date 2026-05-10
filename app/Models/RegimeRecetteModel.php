<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeRecetteModel extends Model
{
    protected $table = 'regime_recettes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom',
        'description',
        'type_repas',
    ];
}
