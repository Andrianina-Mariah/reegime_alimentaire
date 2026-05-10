<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeAchatModel extends Model
{
    protected $table = 'regime_regime_achats';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id',
        'regime_id',
        'prix_paye',
        'created_at',
    ];
}
