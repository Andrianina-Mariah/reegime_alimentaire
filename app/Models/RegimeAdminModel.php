<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeAdminModel extends Model
{
    protected $table = 'regime_admins';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom',
        'email',
        'password',
    ];
}
