<?php

namespace app\controllers;

use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\planillaModel;
use Exception;

class planilla extends controller
{
    private $model;
    private static $validar_numero = '/^[0-9]+$/';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct();
        $this->model = new planillaModel();
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
                echo $view->render('planilla', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            http_response_code(404);
            $this->response(response::estado404($e));
        }
    }

    public function getPlanillas()
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $planillas = $this->model->getPlanillas();
            if (empty($planillas)) {
                return $this->response(response::estado204());
            }
            return $this->response(response::estado200($planillas));
        } catch (Exception $e) {
            return $this->response(response::estado404($e));
        }
    }
    public function pagarPlanilla(int $usuario_id)
    {
        if ($this->method !== 'GET') {
            return $this->response(response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());

        try {
            $comision_id = $this->model->getComision_id($usuario_id);
            $propina_id = $this->model->getPropina_id($usuario_id);
            $horas_extra_id = $this->model->getHorasExtra($usuario_id);

            if (count($horas_extra_id) !== 0) {
                $horas_extra = $this->model->updateHorasExtra($usuario_id);
                if ($horas_extra !== 'ok') {
                    return $this->response(response::estado500('Error al actualizar las horas extras'));
                }
            }

            if (count($propina_id) !== 0) {
                foreach ($propina_id as $value) {
                    $propina = $this->model->getPropina($value['propina_id']);

                    $detallePropina = $this->model->updatedetallePropina($usuario_id);
                    if ($detallePropina !== 'ok') {
                        return $this->response(response::estado500('Error al actualizar el detalle de la propina'));
                    }
                    $monto = 0;
                    $estado = 0;

                    if ($value['monto'] < $propina['propina']) {
                        $monto = $propina['propina'] - $value['monto'];
                        $estado = 1;
                    }


                    $dataPropina = [
                        'id_propina' => $value['propina_id'],
                        'propina' => $monto,
                        'estado' => $estado,
                    ];
                    $propinas = $this->model->updatePropina($dataPropina);
                    if ($propinas !== 'ok') {
                        return $this->response(response::estado500('Error al actualizar la propina'));
                    }
                }
            }

            if (count($comision_id) !== 0) {
                foreach ($comision_id as $value) {

                    $comision = $this->model->getComision($value['comision_id']);

                    if (count($comision) == 0) {
                        return $this->response(response::estado204());
                    }

                    $monto = 0;
                    $estado = 0;

                    if ($value['comision'] < $comision['monto']) {
                        $monto = $comision['monto'] - $value['comision'];
                        $estado = 1;
                    }
                    $dataComision = [
                        'id_comision' => $value['comision_id'],
                        'monto' => $monto,
                        'estado' => $estado
                    ];
                    $detalleComision = $this->model->updateDetalleComision($usuario_id);
                    if ($detalleComision !== 'ok') {
                        return $this->response(response::estado500('Error al actualizar el detalle de la comision'));
                    }
                    $comisiones = $this->model->updateComsion($dataComision);
                    if ($comisiones !== 'ok') {
                        return $this->response(response::estado500('Error al actualizar la comision'));
                    }
                }
            }

            $sueldo = $this->model->updateSueldo($usuario_id);
            if ($sueldo !== 'ok') {
                return $this->response(response::estado500('Error al pagar el sueldo'));
            }

            return $this->response(response::estado201('Planilla pagada con exito'));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }
}
