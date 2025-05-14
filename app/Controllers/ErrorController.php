<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ErrorController extends BaseController {

    public function error() {
        return view('myerror');
    }
}
