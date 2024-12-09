<?php

namespace app\controllers;

use app\config\cache;
use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\anticipoModel;
use Exception;

class anticipo extends controller
{
    private $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new anticipoModel();
    }

    public function index()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }

        try {
            $view = new view();
            session_regenerate_id(true);
            if (!empty($_SESSION['activo'])) {
                echo $view->render('anticipo', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            return $this->response(response::estado404($e));
        }
    }

    public function getAnticipos()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());

        try {
            /*  $cacheKey = 'anticipos_list';
             $anticipos = cache::get($cacheKey);

             if (!$anticipos) {

                 cache::set($cacheKey, $anticipos, 600);
             } */
            $anticipos = $this->model->getAnticipos();
            if (empty($anticipos)) {
                return $this->response(response::estado204('No se encontraron anticipos'));
            }

            return $this->response(response::estado200($anticipos));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function getAnticipo(int $id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $anticipo = $this->model->getAnticipo($id);
            if (empty($anticipo)) {
                return $this->response(response::estado204());
            }
            return $this->response(response::estado200($anticipo));
        } catch (Exception $e) {
            return $this->response(response::estado404($e));
        }
    }

    public function createAnticipo()
    {
        if ($this->method !== 'POST') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());

        try {
            $comisionUsuario = $this->model->getComisionUsuario($this->data['usuario_id']);

            if (empty($comisionUsuario)) {
                $anticipo = $this->model->createAnticipo($this->data);
                if ($anticipo !== 'ok') {
                    return $this->response(response::estado500('Error al crear anticipo'));
                }
                return $this->response(response::estado201());
            }

            $anticipo = $this->model->createAnticipo($this->data);
            if ($anticipo !== 'ok') {
                return $this->response(response::estado500('Error al crear anticipo'));
            }
            $id_anticipo = $this->model->getLastAnticipo()['id_anticipo'];
            $detalle_comision = $this->model->getDetatalleComisionUsuario($this->data['usuario_id']);
            if (empty($detalle_comision)) {
                return $this->response(response::estado500('Error al obtener detalle comision'));
            }
            $ultima_comision = end($detalle_comision);
            $excedente = $this->data['monto'] - $ultima_comision['comision'];
            $estado = 1;

            if ($this->data['monto'] > $ultima_comision['comision']) {
                $estado = 0;
            }
            $detalle = [
                'id_detalle_comision' => $ultima_comision['id_detalle_comision'],
                'estado' => $estado,
            ];
            if ($detalle['estado'] === 0) {
                $pago = $this->model->updateDetalleComision($detalle);

                if ($pago !== 'ok') {
                    return $this->response(response::estado500('Error al actualizar detalle comision'));
                }
            }
            $comision = $this->model->getComision($ultima_comision['comision_id']);

            if (empty($comision)) {
                return $this->response(response::estado500('Error al obtener comision'));
            }
            $estado_comision = $comision['estado'];

            if ($this->data['monto'] > $comision['monto']) {
                $estado_comision = 0;
            } else {
                $estado_comision = 1;
            }

            $monto = $comision['monto'];
            if ($monto >= $this->data['monto']) {
                $monto = $monto - $this->data['monto'];
            } else {
                $monto = 0;
            }

            $comision_ = [
                'id_comision' => $comision['id_comision'],
                'estado' => $estado_comision,
            ];

            if ($comision_['estado'] === 0) {
                $update_comision = $this->model->updateComision($comision_);
                if ($update_comision !== 'ok') {
                    return $this->response(response::estado500('Error al actualizar comision'));
                }
            }
            
            $anticipo = $this->model->updateAnticipo($id_anticipo);
            if ($anticipo !== 'ok') {
                return $this->response(response::estado500('error al actualizar anticipo'));
            }
            if ($excedente > 0) {
                $detalle_comision_actualizada = $this->model->getDetatalleComisionUsuario($this->data['usuario_id']);
                if (empty($detalle_comision_actualizada)) {
                    return $this->response(response::estado500('Error al obtener detalle comision'));
                }
                foreach ($detalle_comision_actualizada as $value) {
                    $detalle_comision_update = [
                        'id_detalle_comision' => $value['id_detalle_comision'],
                        'estado' => $value['estado'],
                    ];
                    if ($excedente > $value['comision']) {
                        $detalle_comision_update = [
                            'id_detalle_comision' => $value['id_detalle_comision'],
                            'estado' => 0,
                        ];
                    }
                    $detalle_comision_ = $this->model->updateDetalleComision($detalle_comision_update);
                    if ($detalle_comision_ !== 'ok') {
                        return $this->response(response::estado500('Error al actualizar detalle comision'));
                    }
                }
            }

            return $this->response(response::estado201());
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
}
