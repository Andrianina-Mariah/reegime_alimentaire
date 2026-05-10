<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeRegimeRecetteModel extends Model
{
    protected $table = 'regime_regime_recettes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'regime_id',
        'recette_id',
        'jour',
    ];
}
