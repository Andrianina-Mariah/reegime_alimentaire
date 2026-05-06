<?php
namespace App\Models;
use CodeIgniter\Model;
class ProduitModel extends Model
{
    protected $table = 'produits';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nom', 'description', 'prix'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
}