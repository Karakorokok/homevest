<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    =   [   
                                        'id_user',
                                        'username', 
                                        'password',
                                        'role',
                                        'firstname',
                                        'middlename',
                                        'lastname',
                                        'email',
                                        'phone',
                                        'address',
                                        'description',
                                        'experience',
                                        'license',
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

    public function getAdminAccount($id_user) {
        return $this->where('role', 3)->where('id_user', $id_user)->first();
    }

    public function generateUserID() {

        $lastUser = $this->where('role', 1)->orderBy('id_user', 'DESC')->first();

        if ($lastUser) {
            $lastID = intval(str_replace("U-", "", $lastUser['id_user']));
            $newID = "U-" . ($lastID + 1);
        } 
        else {
            $newID = "U-1";
        }

        return $newID;
    }

    public function generateAgentID() {

        $lastUser = $this->where('role', 2)->orderBy('id_user', 'DESC')->first();

        if ($lastUser) {
            $lastID = intval(str_replace("AG-", "", $lastUser['id_user']));
            $newID = "AG-" . ($lastID + 1);
        } 
        else {
            $newID = "AG-1";
        }

        return $newID;
    }

    public function insertUser($data) {

        $data['id_user'] = $this->generateUserID(); 
        return $this->insert($data);
        
    }

    public function updateUser($ID, $data) {
        
        return $this->update($ID, $data);
    }

    public function insertAgent($data) {

        $data['id_user'] = $this->generateAgentID(); 
        if ($this->insert($data)) {
            return $data['id_user'];
        }
        return false;
    }

    public function getLogin($username, $password) {

        $user = $this->where('username', $username)->first();

        if ($user && $password == $user['password']) {
            return $user;
        }
        return false;
    }

    public function getUserData($userID) {

        return $this->where('id_user', $userID)->first();
    }

    public function getAgents() {

        return $this->where('role', 2)->findAll();
    }

    public function getAgentsAndAffiliation() {
        return $this->select('users.*, affiliation.*, developers.*')
                ->join('affiliation', 'affiliation.id_agent = users.id_user', 'left')
                ->join('developers', 'developers.id_developer = affiliation.id_developer', 'left')
                ->where('users.role', 2)
                ->findAll();
    }

    public function countUsers() {
        return $this->where('role', 1)->countAllResults();
    }

    public function countAgents() {
        return $this->where('role', 2)->countAllResults();
    }

    public function deleteAgent($id) {
        return $this->delete($id); 
    }

    public function queryUser($keyword) {

        if (empty($keyword)) {
            return [];
        }
    
        return $this->where('role', 2)
            ->groupStart()
                ->like('firstname', $keyword)
                ->orLike('middlename', $keyword)
                ->orLike('lastname', $keyword)
            ->groupEnd()
            ->findAll();
    }

    public function setCreated_at(array $data) {

        $data['data']['created_at'] = $this->getCurrentDateTime();
        return $data;
    }
    
}
