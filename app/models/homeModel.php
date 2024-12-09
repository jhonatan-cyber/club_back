<?php
namespace app\models;

use app\config\query;
use app\config\response;
use Exception;

class homeModel extends query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCodigo()
    {
        $sql = "CALL getCodigo()";
        try {
            return $this->select($sql);
        } catch (Exception $e) {
            return response::estado500($e);
        }
    }

}