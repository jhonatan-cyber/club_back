<?php

namespace app\config;

use app\config\response;

class view
{
    public function render($route, $view, $data = [])
    {
        $routeBase = __DIR__ . '/../../public/views/' . $route . '/';
        $archivo = $routeBase . $view . '.php';

        if (file_exists($archivo)) {
            ob_start();
            require $archivo;
            $output = ob_get_clean();
            return $output;
        } else {
            die(json_encode(response::estado404($archivo)));
        }
    }
}
