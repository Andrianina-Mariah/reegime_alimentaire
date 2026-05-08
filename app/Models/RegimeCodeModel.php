<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeCodeModel extends Model
{
    protected $table = 'regime_codes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'code',
        'montant',
        'used',
    ];
}
