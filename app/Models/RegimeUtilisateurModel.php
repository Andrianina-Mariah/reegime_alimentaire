<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeUtilisateurModel extends Model
{
    protected $table = 'regime_utilisateurs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom',
        'email',
        'password',
        'genre',
        'is_gold',
        'objectif',
    ];
}
