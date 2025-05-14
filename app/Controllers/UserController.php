<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\MessageModel;
use App\Models\UserModel;
use App\Models\AffiliationModel;
use App\Models\DevelopersModel;
use App\Models\RestateModel;
use App\Models\FavoritesModel;

class UserController extends BaseController {

    public function userHome() {

        $sessionUserID = session()->get('id_user');
        $sessionUserRole = session()->get('user_role');
        
        if($sessionUserRole == 3) {
              return redirect()->to('/login');
        }
        
        $UserModel = new UserModel();
        $data['UserData'] = $UserModel->getUserData($sessionUserID);
        $AffiliationModel = new AffiliationModel();
        $data['HomeDevelopers'] = $AffiliationModel->getAffiliationsByID($sessionUserID);
      
        return view('/useragent/home', $data);
    }
    

    public function searchResults() {

        $data = [];

        if($this->request->getMethod() == 'POST') {

            $keyword = $this->request->getPost('searchquery');

            $homes = ['home', 'homes', 'house', 'houses', 'real estate'];
            $agents = ['agent', 'agents', 'broker', 'real estate agents'];

            if (in_array(strtolower($keyword), $homes)) {
                return redirect()->to('/homeslist');
            }
    
            if (in_array(strtolower($keyword), $agents)) {
                return redirect()->to('/agentslist'); 
            }

            $UserModel = new UserModel();
            $RestateModel = new RestateModel();
            $DevelopersModel = new DevelopersModel();

            $restate =  $RestateModel->queryRestate($keyword);

            if ($restate) {
                return redirect()->to('/homeslist?search=' . urlencode($keyword));
            }

            $developers = $DevelopersModel->queryDeveloper($keyword);

            if ($developers) {
                return redirect()->to('/homeslist?search=' . urlencode($keyword));
            }

            $user =  $UserModel->queryUser($keyword);

            if ($user) {
                return redirect()->to('/agentslist?search=' . urlencode($keyword));
            }

        }

        return view('/useragent/searchresults', $data);
    }

    public function profile() {

        $sessionUserID = session()->get('id_user');
        
        $UserModel = new UserModel();
        $data['UserData'] = $UserModel->getUserData($sessionUserID);
        $AffiliationModel = new AffiliationModel();
        $data['UserAffiliation'] = $AffiliationModel->getAffiliationsByID($sessionUserID);
        $FavoritesModel = new FavoritesModel();
        $data['Favorites'] = $FavoritesModel->getFavorites($sessionUserID);

        return view('/useragent/profile', $data);

    }

    public function editProfile() {

        $sessionUserID = session()->get('id_user');

        if($this->request->getMethod() == 'POST') {

            $firstname = $this->request->getPost('firstname');
            $middlename = $this->request->getPost('middlename');
            $lastname = $this->request->getPost('lastname');
            $email = $this->request->getPost('email');
            $phone = $this->request->getPost('phone');
            $address = $this->request->getPost('address');
            $description = $this->request->getPost('description');
            $experience = $this->request->getPost('experience');
            $license = $this->request->getPost('license');

            $toEditData = [
                'firstname' => $firstname,
                'middlename' => $middlename,
                'lastname' => $lastname,
                'phone' => $phone,
                'email' => $email,
                'address' => $address,
                'description' => $description,
                'experience' => $experience,
                'license' => $license,
            ];
            
            $UserModel = new UserModel();
            $edit =  $UserModel->updateUser($sessionUserID, $toEditData);

            if($edit) {
                return redirect()->to('/profile');
            }
            else {
                return redirect()->to('/error');
            }

        }
    }

    public function agentsList() {

        $AffiliationModel = new AffiliationModel();
        $DevelopersModel = new DevelopersModel();
        $UserModel = new UserModel();
        
        $searchQuery = $this->request->getGet('search');

        $data['Developers'] = $DevelopersModel->getDevelopers();
        $data['selectedDeveloper'] = 'All';
        $data['search'] = $searchQuery;
        
        if ($this->request->getMethod() == 'POST') {
            $developer = $this->request->getPost('developer');

            if($developer == "all") {
                $data['Agents'] =   $UserModel->getAgentsAndAffiliation();
                $data['selectedDeveloper'] = 'All';
            }
            else {
                $data['Agents'] = $AffiliationModel->getAffiliationsByDeveloper($developer);
                $developerInfo = $DevelopersModel->find($developer);
                $data['selectedDeveloper'] = $developerInfo['developer_name'];
            }
        }
        else {
            $data['Agents'] =   $UserModel->getAgentsAndAffiliation(); //load all default
        }

        return view('/useragent/agentlist', $data);
    }

    public function homesList() {

        $DevelopersModel = new DevelopersModel();
        $RestateModel = new RestateModel();

        $searchQuery = $this->request->getGet('search');

        $data['Developers'] = $DevelopersModel->getDevelopers();
        $data['selectedDeveloper'] = 'All';
        $data['search'] = $searchQuery;

        if ($this->request->getMethod() == 'POST') {

            $developer = $this->request->getPost('developer');

            if($developer == "all") {
                $data['Restates'] =   $RestateModel->fetchAllRestate();
                $data['selectedDeveloper'] = 'All';
            }
            else {
                $data['Restates'] = $RestateModel->fetchRestatesByDeveloper($developer);
                $developerInfo = $DevelopersModel->find($developer);
                $data['selectedDeveloper'] = $developerInfo['developer_name'];
            }
        }
        else {
            $data['Restates'] =   $RestateModel->fetchAllRestate(); //load all default
        }

        return view('/useragent/homeslist', $data);
    }

    public function modelDetails($id_restate) {

        $RestateModel = new RestateModel();
        $data['RestateDetail'] = $RestateModel->fetchRestateDetailsByID($id_restate);
        $data['RestateAgents'] = $RestateModel->fetchAgentsbyRestate($id_restate);

        $FavoritesModel = new FavoritesModel();
        $data['Favorites'] = $FavoritesModel->doesFavoriteExist($id_restate);

        return view('/useragent/modeldetails', $data);

    }

    public function addToFavorites() {

        if ($this->request->getMethod() == 'POST') {

            $id_user = $this->request->getPost('id_user');
            $id_restate = $this->request->getPost('id_restate');

            $FavoritesModel = new FavoritesModel();
            $doesExist = $FavoritesModel->doesFavoriteExist($id_restate);

            if($doesExist == 1) {
                $delete = $FavoritesModel->deleteFavorites($id_restate);
                if($delete) {
                    return redirect()->to('modeldetails/'.$id_restate);
                }
                else {
                    return redirect()->to('/error');
                }
            }
            else {
                $data = [
                    'id_user' => $id_user,
                    'id_restate' => $id_restate,
                ];

                $result = $FavoritesModel->insertToFavorites($data);
    
                if($result) {
                    return redirect()->to('modeldetails/'.$id_restate);
                }
                else {
                    return redirect()->to('/error');
                }
            }
           
        }
      
    }

    public function homesListing() {

        $data = [];

        $developerFromHome = $this->request->getVar('developer'); 
        $data['selectedDeveloper'] = $developerFromHome;
        $data['selectedDeveloperDisplay'] = $this->request->getVar('devname');
      
        $RestateModel = new RestateModel();
        $data['Models'] = $RestateModel->fetchRestatesByDeveloper($developerFromHome); 

        if ($this->request->getMethod() == 'POST') {

            $id_restate = $this->request->getPost('id_restate');
            $developer = $this->request->getPost('developerid');
            $developername = $this->request->getPost('developername');
            $creator = $this->request->getPost('creator');
            $model = $this->request->getPost('model');
            $location = $this->request->getPost('location');
            $arealot = $this->request->getPost('area_lot');
            $price = $this->request->getPost('price');
            $downpayment = $this->request->getPost('downpayment');
            $reservationfee = $this->request->getPost('reservation_fee');
            $yearstopay = $this->request->getPost('years_to_pay');
            $areafloor = $this->request->getPost('area_floor');
            $bedrooms = $this->request->getPost('bedrooms');
            $comfortrooms = $this->request->getPost('comfort_rooms');
            $others = $this->request->getPost('others');

            $RestateModel = new RestateModel();

            $toInsert = [
                'id_restate' => $id_restate,
                'id_developer' => $developer,
                'model' => $model,
                'location' => $location,
                'area_lot' => $arealot,
                'price' => $price,
                'downpayment' => $downpayment,
                'reservation_fee' => $reservationfee,
                'years_to_pay' => $yearstopay,
                'area_floor' => $areafloor,
                'bedrooms' => $bedrooms,
                'comfort_rooms' => $comfortrooms,
                'created_by' => $creator,
                'others' => $others,
            ];
            
            $result = $RestateModel->insertRestate($toInsert);

            if($result) {
                return redirect()->to('/homeslisting?developer=' . $developer .'&devname=' . $developername);
            }
            else {
                return redirect()->to('/error');
            }
        }

        return view('/useragent/homeslisting', $data);
    }

    public function homesListingDelete() {

        if ($this->request->getMethod() == 'POST') {

            $idtodel = $this->request->getPost('id_restatetodel');
            $developerid = $this->request->getPost('developerid'); 
            $developername = $this->request->getPost('developername');
            $RestateModel = new RestateModel();

            $delete = $RestateModel->deleteRestate($idtodel);

            if($delete) {
                return redirect()->to('/homeslisting?developer=' . $developerid .'&devname=' . $developername);
            }
            else {
                return redirect()->to('/error');
            }

        }
      
    }

    public function profileView($id_user) {

        $UserModel = new UserModel();
        $data['UserData'] = $UserModel->getUserData($id_user);
        $AffiliationModel = new AffiliationModel();
        $data['UserAffiliation'] = $AffiliationModel->getAffiliationsByID($id_user);

        return view('/useragent/profileview', $data);

    }

    public function saveProfilePhoto() {

        $sessionUserID = session()->get('id_user');
        $profilephoto = $this->request->getFile('profilephoto');

        if (!$profilephoto->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid file upload.']);
        }
    
        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($profilephoto->getMimeType(), $allowedTypes)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Only JPEG and PNG images are allowed.']);
        }
    
        $destination = FCPATH . '/resources/images/profilepics/';
        $extension = $profilephoto->getExtension();
        $newFileName = $sessionUserID . '.' . $extension;

        $existingFiles = glob($destination . $sessionUserID . '.*');
        foreach ($existingFiles as $existingFile) {
            unlink($existingFile);
        }
    
        if ($profilephoto->move($destination, $newFileName, true)) {
            return $this->response->setJSON(['success' => true, 'message' => 'File uploaded successfully.']);
        } 
        else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to save file.']);
        }
    }

    public function saveModelPhoto() {

        $developerid = $this->request->getPost('developerid'); 
        $developername = $this->request->getPost('developername');
        $id_restate = $this->request->getPost('id_restate'); 
        $files = $this->request->getFileMultiple('modelphotos');
    
        $destination = FCPATH . '/resources/images/modelphotos/';
        $allowedTypes = ['image/jpeg', 'image/png'];
    
        if (empty($files)) {
            // return $this->response->setJSON(['success' => false, 'message' => 'No files uploaded.']);
            return redirect()->to('/homeslisting?developer=' . $developerid .'&devname=' . $developername);
        }

        $existingFiles = glob($destination . $id_restate . '_*.*'); 

        $maxIndex = 0;
        foreach ($existingFiles as $filePath) {
            $filename = basename($filePath);             
            $parts = explode('_', $filename);          
            if (count($parts) == 2) {
                $numberPart = explode('.', $parts[1])[0];
                $indexNum = intval($numberPart);
                if ($indexNum > $maxIndex) {
                    $maxIndex = $indexNum;
                }
            }
        }

        $index = $maxIndex + 1;
        
        foreach ($files as $file) {
            if (!$file->isValid()) {
                continue;
            }
    
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                continue; 
            }
    
            $extension = $file->getExtension();
            $newFileName = $id_restate . '_' . $index . '.' . $extension;
    
            if ($file->move($destination, $newFileName, true)) {
                $index++;
            }
        }
    
        if ($index > 1) {
            // return $this->response->setJSON(['success' => true, 'message' => 'Files uploaded successfully.']);
            return redirect()->to('/homeslisting?developer=' . $developerid .'&devname=' . $developername);
        }
        else {
            // return $this->response->setJSON(['success' => false, 'message' => 'No valid files uploaded.']);
            return redirect()->to('/error');
        }
    }

    public function deleteModelPhoto()
    {
        $photoPath = $this->request->getPost('photoPath');
    
        $photo_dir = '/resources/images/modelphotos/';
        $fullPath = FCPATH . $photoPath;
    
        $success = false;
        $message = 'Invalid file.';
    
        if (strpos(realpath($fullPath), realpath(FCPATH . $photo_dir)) === 0 && preg_match('/^\d+_\d+\.(jpg|jpeg|png)$/i', basename($photoPath))) {
            if (file_exists($fullPath)) {
                if (unlink($fullPath)) {
                    $success = true;
                    $message = 'Photo deleted successfully.';
                } else {
                    $message = 'Failed to delete file.';
                }
            } else {
                $message = 'File does not exist.';
            }
        }
    
        return $this->response->setJSON(['success' => $success, 'message' => $message]);
    }    

}
