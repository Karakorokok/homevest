<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class OTPModel extends Model
{
    protected $table            = 'otp';
    protected $primaryKey       = 'id_otp';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_user', 'otp', 'is_used'];

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

    public function insertOTP($userID) {
       
        helper('text');
        $otp = strtoupper(random_string('alnum', 6));
        
        $data = [
            'id_user' => $userID,
            'otp' => $otp,
            'is_used' => 0
        ];
    
        if ($this->insert($data)) {
            return $otp;
        }
        
        return false;
    }

    public function checkOTP($code) {
    
        $row = $this->where('otp', $code)
                    ->where('is_used', 0)
                    ->select('id_user')
                    ->first();
    
        if ($row) {
            $this->where('otp', $code)->set(['is_used' => 1])->update();
            return $row;
        }
    
        return null; 
    }
    public function setCreated_at(array $data) {

        $data['data']['created_at'] = $this->getCurrentDateTime();
        return $data;
    }

}
