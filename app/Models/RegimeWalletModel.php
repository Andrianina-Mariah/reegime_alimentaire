<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeWalletModel extends Model
{
    protected $table = 'regime_wallet';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id',
        'solde',
    ];
}
