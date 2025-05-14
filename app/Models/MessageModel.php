<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class MessageModel extends Model
{
    protected $table            = 'messages';
    protected $primaryKey       = 'id_message';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    =   [
                                        'sender',
                                        'receiver',
                                        'messagecontent',
                                        'status'
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

    public function getMessages($userID) {

        return $this->select('messages.*, 
                             sender_user.firstname AS sender_firstname, 
                             sender_user.lastname AS sender_lastname, 
                             sender_user.role AS sender_role, 
                             receiver_user.firstname AS receiver_firstname, 
                             receiver_user.lastname AS receiver_lastname, 
                             receiver_user.role AS receiver_role')
                    ->join('users AS sender_user', 'sender_user.id_user = messages.sender', 'left')
                    ->join('users AS receiver_user', 'receiver_user.id_user = messages.receiver', 'left')
                    ->where('messages.receiver', $userID)
                    ->orWhere('messages.sender', $userID)
                    ->orderBy('messages.created_at', 'DESC')
                    ->findAll();
    }
    
    public function getConvo($person1, $person2) {

        return $this->groupStart()
                        ->where('receiver', $person1)
                        ->where('sender', $person2)
                    ->groupEnd()
                    ->orGroupStart()
                        ->where('receiver', $person2)
                        ->where('sender', $person1)
                    ->groupEnd()
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    public function sendMessage($data) {

        return $this->insert($data);
    }

    public function setCreated_at(array $data) {

        $data['data']['created_at'] = $this->getCurrentDateTime();
        return $data;
    }
        
}
