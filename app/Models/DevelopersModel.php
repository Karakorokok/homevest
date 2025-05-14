<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class DevelopersModel extends Model
{
    protected $table            = 'developers';
    protected $primaryKey       = 'id_developer';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['developer_name'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setCreated_at'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function getCurrentDateTime() {

        $time = Time::now('Asia/Manila');
        return $time->toDateTimeString();
    }

    public function generateDeveloperID() {

        $lastDeveloperID = $this->orderBy('id_developer', 'DESC')->first();

        if ($lastDeveloperID) {
            $lastID = (int) $lastDeveloperID['id_developer'];;
            $newID = $lastID + 1;
        } 
        else {
            $newID = 10000;
        }

        return $newID;
    }

    public function insertDeveloper($data) {

        $data['id_developer'] = $this->generateDeveloperID(); 
        return $this->save($data);
    }

    public function deleteDeveloper($id) {
        return $this->delete($id); 
    }

    public function getDevelopers() {
        return $this->orderBy('developer_name', 'ASC')->findAll();
    }

    public function countDevelopers() {
        return $this->countAllResults();
    }

    public function queryDeveloper($keyword) {

        if (empty($keyword)) {
            return [];
        }
    
        return $this->like('developer_name', $keyword)
            ->findAll();
    }

    public function setCreated_at(array $data) {

        $data['data']['created_at'] = $this->getCurrentDateTime();
        return $data;
    }
}
