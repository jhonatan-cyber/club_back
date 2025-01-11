<?php

namespace app\controllers;

use app\config\controller;
use app\config\guard;
use app\config\response;

class token extends controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function tokenVerify()
    {
        if ($this->method !== 'POST') {
            return $this->response(response::estado405());
        }

        $headers = getallheaders();
        $secretKey = guard::secretKey();

        $tokenStatus = guard::checkTokenStatus($headers, $secretKey);
        if ($tokenStatus['status'] !== true) {
           
            return $this->response(response::estado401($tokenStatus)); ;
       
        }
       
        return $this->response(response::estado200($tokenStatus));
       
    }
}
