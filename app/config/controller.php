<?php

namespace app\config;

use Exception;
use app\config\response;

class controller
{
    protected $data;
    protected $header;
    protected $method;

    public function __construct()
    {
        $this->data = json_decode(file_get_contents('php://input'), true);
        $this->header = getallheaders();
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    public function response(array $data)
    {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function exists(array $params)
    {
        try {
            $missingParams = $this->getMissingParams($params);
            if (!empty($missingParams)) {
                $this->response(['Parametros faltantes: ' . implode(', ', $missingParams)]);;
            }
            $this->validate();
            return true;
        } catch (Exception $e) {
            $this->response(response::estado400());
        }
    }
    private function getMissingParams(array $params): array
    {
        return array_diff($params, array_keys($this->data));
    }
    private function validate(): void
    {
        $emptyParams = $this->getEmptyParams();

        if (!empty($emptyParams)) {
            $this->response(response::estado400(implode(',', $emptyParams)));
        }
    }
    private function getEmptyParams(): array
    {
        $emptyParams = [];

        foreach ($this->data as $key => $value) {
            if (empty($value)) {
                $emptyParams[] = $key;
            }
        }

        return $emptyParams;
    }
}
