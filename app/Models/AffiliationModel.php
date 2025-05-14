<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class AffiliationModel extends Model
{
    protected $table            = 'affiliation';
    protected $primaryKey       = 'id_affiliation';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_agent', 'id_developer'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

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


    public function insertAffiliations($agentID, $developerID) {
        foreach ($developerID as $devID) {
            $this->insert([
                'id_agent' => $agentID,
                'id_developer' => $devID
            ]);
        }

        return true;
    }

    public function getAffiliations() {
        return $this->findAll();
    }

    public function getAffiliationsByID($id) {
        return $this->select('affiliation.*, developers.*')
        ->join('developers', 'developers.id_developer = affiliation.id_developer')
        ->where('affiliation.id_agent', $id)
        ->findAll();
    }

    public function getAffiliationsByDeveloper($developerID) {
        return $this->select('affiliation.*, developers.*, users.*')
        ->join('developers', 'developers.id_developer = affiliation.id_developer')
        ->join('users', 'users.id_user = affiliation.id_agent')
        ->where('affiliation.id_developer', $developerID)
        ->findAll();
    }

    public function deleteDeveloperFromAffiliation($id) {
        return $this->where('id_developer', $id)->delete();
    }

    public function deleteAgentFromAffiliation($id) {
        return $this->where('id_agent', $id)->delete();
    }

}
