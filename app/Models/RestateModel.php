<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class RestateModel extends Model
{
    protected $table            = 'restate';
    protected $primaryKey       = 'id_restate';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    =   [ 
                                        'id_developer',
                                        'model',
                                        'location',
                                        'area_lot',
                                        'price',
                                        'downpayment',
                                        'reservation_fee',
                                        'years_to_pay',
                                        'area_floor',
                                        'bedrooms',
                                        'comfort_rooms',
                                        'others',
                                        'created_by',
                                        'edited_by',
                                    ];

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

    public function fetchAllRestate() {
        return $this->select('restate.*, developers.*')
            ->join('developers', 'developers.id_developer = restate.id_developer')
            ->findAll();
    }
    public function fetchRestatesByDeveloper($developerID) {

        return $this->select('restate.*, developers.*, users.*')
            ->join('developers', 'developers.id_developer = restate.id_developer')
            ->join('users', 'users.id_user = restate.created_by')
            ->where('restate.id_developer', $developerID)
            ->findAll();
    }

    public function fetchRestateDetailsByID($id_restate) {
        return $this->select('restate.*, developers.*')
            ->join('developers', 'developers.id_developer = restate.id_developer')
            ->where('restate.id_restate', $id_restate)
            ->get()
            ->getRowArray();
    }

    public function fetchAgentsbyRestate($id_restate) {
        return  $this->select('users.*, affiliation.*, restate.*')
            ->join('affiliation', 'affiliation.id_developer = restate.id_developer')
            ->join('users', 'users.id_user = affiliation.id_agent')
            ->where('restate.id_restate', $id_restate)
            ->findAll();
    }

    public function insertRestate($data) {

        return $this->save($data);
    }

    public function deleteRestate($id) {

        return $this->where('id_restate', $id)->delete();
    }

    public function deleteRestateByDeveloper($id) {
        
        return $this->where('id_developer', $id)->delete();
    }

    public function queryRestate($keyword) {

        if (empty($keyword)) {
            return [];
        }
    
        return $this->like('model', $keyword)
            ->orLike('location', $keyword)
            ->findAll();
    }

    public function setCreated_at(array $data) {

        $data['data']['created_at'] = $this->getCurrentDateTime();
        return $data;
    }
    
}
