<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use App\Models\OTPModel;

class LoginController extends BaseController {
    
    public function login() {

        if($this->request->getMethod() == 'POST') {

            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $validation = \Config\Services::validation();

            $validation->setRules([
                'username' => 'required',
                'password' => 'required',
            ]);

            if($validation->withRequest($this->request)->run()) {

                $UserModel = new UserModel();

                $user = $UserModel->getLogin($username, $password);

                if($user) {
                    session()->set([
                        'id_user' => $user['id_user'],
                        'user_role' => $user['role'],
                    ]);

                    //Remember me
                    if($this->request->getPost('remember_me')) {
                        helper('text');
                
                        $selector = bin2hex(random_bytes(6)); 
                        $validator = bin2hex(random_bytes(32)); 
                        $hashedValidator = hash('sha256', $validator);
                        $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
                
                        $db = \Config\Database::connect();
                        $db->table('remember_tokens')->insert([
                            'id_user' => $user['id_user'],
                            'selector' => $selector,
                            'hashed_validator' => $hashedValidator,
                            'expires_at' => $expires
                        ]);
                
                        $cookieValue = $selector . ':' . $validator;
                        setcookie('remember_me', $cookieValue, time() + (86400 * 30), "/", "", false, true);
                    }
                    //End Remember me

                    if (session()->get('user_role') == 3) {

                        session()->set([
                            'firstname' => $user['firstname'],
                            'middlename' => $user['middlename'],
                            'lastname' => $user['lastname'],
                        ]);

                        return redirect()->to('/adminhome');
                    } 
                  
                    return redirect()->to('/home');
                }
                session()->setFlashdata('validationErrors', 'Incorrect username or password');
                return redirect()->to('/login');
                
            }
            session()->setFlashdata('validationErrors', 'Username and Password is required');
            return redirect()->to('/login'); 
        }
        return view('login');
    }

    public function signup() {

        if($this->request->getMethod() == 'POST') {
           
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $repassword = $this->request->getPost('repassword');
            $firstname = $this->request->getPost('firstname');
            $lastname = $this->request->getPost('lastname');
            $email = $this->request->getPost('email');

            $validation = \Config\Services::validation();

            $validation->setRules([
                'username' => [
                    'rules' => 'required|is_unique[users.username]',
                    'errors' => [
                        'required' => 'Username is required',
                        'is_unique' => 'Username already taken',
                    ],
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Password is required',
                    ],
                ],
                'lastname' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Lastname is required',
                    ],
                ],
                'firstname' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Firstname is required',
                    ],
                ],
            ]);

            if($validation->withRequest($this->request)->run()) {
                
                if ($password == $repassword) {

                    $UserModel = new UserModel();

                    $forInsertUserData = [
                        'username' => $username,
                        'password' => $password,
                        'role' => 1,
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'email' => $email,
                    ];
                    
                    $result = $UserModel->insertUser($forInsertUserData );
    
                    session()->setFlashdata('successMessage', 'Account created successfully.');
                    return redirect()->to('/signup');
                }
                else {
                    session()->setFlashdata('validationErrors', ['password' => 'Passwords do not match']);
                    session()->setFlashdata('formData', $this->request->getPost());
                    return redirect()->to('/signup');
                }
              
            }
            else {
                session()->setFlashdata('validationErrors', $validation->getErrors());
                session()->setFlashdata('formData', $this->request->getPost());
                return redirect()->to('/signup');
            }

        }
        return view('signup');
    }

    public function otp() {
        
        if($this->request->getMethod() == 'POST') {

            $otp = $this->request->getPost('otp');
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $repassword = $this->request->getPost('repassword');
          

            $validation = \Config\Services::validation();

            $validation->setRules([
                'username' => [
                    'rules' => 'required|is_unique[users.username]',
                    'errors' => [
                        'required' => 'Username is required',
                        'is_unique' => 'Username already taken',
                    ],
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Password is required',
                    ],
                ],
                'otp' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'OTP is required',
                    ],
                ],
            ]);

            if($validation->withRequest($this->request)->run()) {
                
                if ($password == $repassword) {

                    $OTPModel = new OTPModel();
                    $UserModel = new UserModel();

                    $exist = $OTPModel->checkOTP($otp);

                    if($exist) {
                        $returnedID = $exist;
                        $data = [
                            'username' => $username,
                            'password' => $password,
                        ];
                        
                        $result = $UserModel->updateUser($returnedID, $data);
        
                        session()->setFlashdata('successMessage', 'Account created successfully.');
                        return redirect()->to('/otp');
                    }
                    else {
                        session()->setFlashdata('validationErrors', ['otp' => 'Incorrect OTP']);
                        session()->setFlashdata('formData', $this->request->getPost());
                        return redirect()->to('/otp');
                    }
                }
                else {
                    session()->setFlashdata('validationErrors', ['password' => 'Passwords do not match']);
                    session()->setFlashdata('formData', $this->request->getPost());
                    return redirect()->to('/otp');
                }
              
            }
            else {
                session()->setFlashdata('validationErrors', $validation->getErrors());
                session()->setFlashdata('formData', $this->request->getPost());
                return redirect()->to('/otp');
            }

        }
        return view('otp');
    }

    public function logout() {

        if (isset($_COOKIE['remember_me'])) {
            [$selector] = explode(':', $_COOKIE['remember_me']);
            $db = \Config\Database::connect();
            $db->table('remember_tokens')->where('selector', $selector)->delete();
    
            setcookie('remember_me', '', time() - 3600, "/");
        }

        session()->destroy();
        return redirect()->to('/login');
    }
}
