<?php

namespace App\Models;

use CodeIgniter\Model;

class FavoritesModel extends Model
{
    protected $table            = 'favorites';
    protected $primaryKey       = 'id_favorite';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_user', 'id_restate'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getFavorites($id_user) {
        return $this->select('restate.*, favorites.*')
            ->join('restate', 'restate.id_restate = favorites.id_restate')
            ->where('favorites.id_user', $id_user)
            ->findAll();
    }
    
    public function doesFavoriteExist($id_restate) {
        $result = $this->where('id_restate', $id_restate)->findAll();

        if($result) {
            return 1;
        }
        return 0;
    }

    public function insertToFavorites($data) {
        return $this->save($data);
    }

    public function deleteFavorites($id_restate) {
        return $this->where('id_restate', $id_restate)->delete();
    }
}
