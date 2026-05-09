<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeActiviteModel extends Model
{
    protected $table = 'regime_activites';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom',
        'description',
        'duree',
    ];
}
