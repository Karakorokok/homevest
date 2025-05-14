<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\DevelopersModel;
use App\Models\AffiliationModel;
use App\Models\OTPModel;
use App\Models\RestateModel;

class AdminController extends BaseController {

    public function adminHome() {

        $UserModel = new UserModel();
        $data['UsersCount'] = $UserModel->countUsers();
        $data['AgentsCount'] = $UserModel->countAgents();
        $DevelopersModel = new DevelopersModel();
        $data['DevelopersCount'] = $DevelopersModel->countDevelopers();

        return view('administrator/adminhome', $data);
    }

    public function adminProfile() {

        $id_user =  session()->get('id_user');
        $UserModel = new UserModel();
        $data['Admin'] = $UserModel->getAdminAccount($id_user);
        return view('administrator/adminprofile', $data);
    }

    public function adminAgents() {

        $UserModel = new UserModel();
        $rawAgents = $UserModel->getAgentsAndAffiliation();
        $DevelopersModel = new DevelopersModel();
        $data['Developers'] = $DevelopersModel->getDevelopers();
        $AffiliationModel = new AffiliationModel();
        $data['Affiliation'] =  $AffiliationModel->getAffiliations();
        $groupedAgents = [];

        foreach ($rawAgents as $row) {
            $id = $row['id_user'];

            if (!isset($groupedAgents[$id])) {
                $groupedAgents[$id] = $row;
                $groupedAgents[$id]['developer_names'] = [];
                $groupedAgents[$id]['developer_ids'] = []; 
            }
            
            if (!empty($row['developer_name'])) {
                $groupedAgents[$id]['developer_names'][] = $row['developer_name'];
                $groupedAgents[$id]['developer_ids'][] = $row['id_developer']; 
            }
        }

        foreach ($groupedAgents as &$agent) {
            if (!empty($agent['developer_names'])) {
                sort($agent['developer_names']); 
                $agent['developer_names'] = implode(', ', $agent['developer_names']);
            } 
            else {
                $agent['developer_names'] = '';
            }
        }

        $data['Agents'] = array_values($groupedAgents);

        return view('administrator/adminagents', $data);
    }

    public function adminAddAgent() {

        if($this->request->getMethod() == 'POST') {
          
            $UserModel = new UserModel();
            $AffiliationModel = new AffiliationModel();
            $OTPModel = new OTPModel();

            $firstname = $this->request->getPost('firstname');
            $lastname = $this->request->getPost('lastname');
            $toEmail = $this->request->getPost('email');
            $selectedDevelopers = $this->request->getPost('developer_ids'); 

            if(!empty($selectedDevelopers)) {
                $agentData = [
                    'role' => 2,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $toEmail,
                ];
        
                $returnedID = $UserModel->insertAgent($agentData);

                if($returnedID) {

                    $result = $AffiliationModel->insertAffiliations($returnedID , $selectedDevelopers);

                    if($result) {

                        $returnedOTP =  $OTPModel->insertOTP($returnedID);

                        $email = \Config\Services::email();

                        $email->setFrom('homevest04@gmail.com', 'Homevest Admin');
                        $email->setTo($toEmail);
                        $email->setSubject('One-Time Password (OTP)');
                        $email->setMailType('html');
                        
                        $message = "
                        <html>
                            <head>
                                <style>
                                	* { font-family: 'Arial'; }
             
                                    .mb { margin-bottom: 10px; }
                                    
                                    .mbb {
                                        margin-bottom: 40px;
                                        font-size: 25px;
                                    }

                                    .center {
                                    	border: 1px solid #9fa6b2;
                                        border-radius: 5px;
                                        padding: 20px;
                                        text-align: center; 
                                    }

                                    .asBtn, .asBtn:visited {
                                    	background-color: #3b71ca;
                                        padding: 10px;
                                        color: #fff !important;
                                    	text-decoration: none;
                                        border-radius: 5px;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class='center'>
                                    <div class='mb'>Your One-Time Password is:</div>
                                    <div class='mbb'>$returnedOTP</div>

                                    <a href='https://homevest.site/otp' target='_blank' class='asBtn'>Create Account</a>
                                </div>
                            </body>
                        </html>"
                        ;
            
                        $email->setMessage($message);

                        if($email->send()) {
                            return redirect()->to('/adminagents');
                        } 
                        else {
                            return redirect()->to('/error');
                        }

                    }
                } 
                else {
                    return redirect()->to('/error');
                }
            }
            else {
                return redirect()->to('/adminagents');
            }
        }
    }

    public function adminRemoveAgent() {

        if ($this->request->getMethod() == 'POST') {

            $id = $this->request->getPost('id_user');
         
            $UserModel = new UserModel();
            $AffiliationModel = new AffiliationModel();

            $result = $UserModel->deleteAgent($id);

            if($result) {
                $res = $AffiliationModel->deleteAgentFromAffiliation($id);
                if($res) {
                    return redirect()->to('/adminagents');
                }
            } 
            else {
                return redirect()->to('/error');
            }
        }
    }

    public function adminEditAffiliation() {

        if ($this->request->getMethod() == 'POST') {

            $id = $this->request->getPost('id_user');
            $selectedDevelopers = $this->request->getPost('developer_ids'); 

            $AffiliationModel = new AffiliationModel();
            $deleteFirst = $AffiliationModel->deleteAgentFromAffiliation($id);

            if($deleteFirst) {
                $reInsert = $AffiliationModel->insertAffiliations($id, $selectedDevelopers);

                if($reInsert) {
                    return redirect()->to('/adminagents');
                }
                else {
                    return redirect()->to('/error'); 
                }
            }

        }

    }

    public function adminDevelopers() {

        $DevelopersModel = new DevelopersModel();
        $data['Developers'] = $DevelopersModel->getDevelopers();

        return view('administrator/admindevelopers', $data);
    }

    public function adminAddDeveloper() {

        if ($this->request->getMethod() == 'POST') {
    
            $id = $this->request->getPost('id_developer');
            $developername = $this->request->getPost('developername');
    
            $data = [
                'id_developer' => $id,
                'developer_name' => $developername
            ];
    
            $DevelopersModel = new DevelopersModel();
    
            if($id) {
                $data['id_developer'] = $id;
                $result = $DevelopersModel->save($data);
            } 
            else {
                $result = $DevelopersModel->insertDeveloper($data);
            }
    
            if($result) {
                return redirect()->to('/admindevelopers');
            } 
            else {
                return redirect()->to('/error');
            }
        }
    }

    public function adminRemoveDeveloper() {

        if ($this->request->getMethod() == 'POST') {

            $id = $this->request->getPost('id_developer');

            $DevelopersModel = new DevelopersModel();
            $AffiliationModel = new AffiliationModel();
            $RestateModel = new RestateModel();

            $result = $DevelopersModel->deleteDeveloper($id);

            if($result) {
                $res = $AffiliationModel->deleteDeveloperFromAffiliation($id);
                if($res) {
                    $re = $RestateModel->deleteRestateByDeveloper($id);
                    if($re) {
                        return redirect()->to('/admindevelopers');
                    }
                }
            } 
            else {
                return redirect()->to('/error');
            }
        }
    }
    
}
