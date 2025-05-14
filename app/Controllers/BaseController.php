<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
        $this->autoLoginFromRememberMe(); 

    }

    protected function autoLoginFromRememberMe()
    {
        if (session()->get('id_user')) {
            return; 
        }

        if (isset($_COOKIE['remember_me'])) {
            [$selector, $validator] = explode(':', $_COOKIE['remember_me']);

            $db = \Config\Database::connect();
            $token = $db->table('remember_tokens')
                        ->where('selector', $selector)
                        ->where('expires_at >=', date('Y-m-d H:i:s'))
                        ->get()
                        ->getRow();

            if ($token && hash_equals($token->hashed_validator, hash('sha256', $validator))) {
                $UserModel = new \App\Models\UserModel();
                $user = $UserModel->find($token->id_user);
                if ($user) {
                    session()->set([
                        'id_user' => $user['id_user'],
                        'user_role' => $user['role'],
                    ]);
                }
            }
        }
    }
}
