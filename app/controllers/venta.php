<?php

namespace app\controllers;

use app\config\controller;
use app\config\guard;
use app\config\response;
use app\config\view;
use app\models\ventaModel;
use app\models\pedidoModel;
use app\models\comisionModel;
use app\models\anticipoModel;
use app\models\propinaModel;
use app\models\cuentaModel;
use app\models\cajaModel;
use Exception;

class venta extends controller
{
    private $venta;
    private $comision;
    private $cuenta;
    private $propina;
    private $pedido;
    private $anticipo;
    private $caja;

    public function __construct()
    {
        parent::__construct();
        $this->venta = new ventaModel();
        $this->pedido = new pedidoModel();
        $this->comision = new comisionModel();
        $this->anticipo = new anticipoModel();
        $this->propina = new propinaModel();
        $this->cuenta = new cuentaModel();
        $this->caja = new cajaModel();
    }

    public function index()
    {
        if ($this->method !== 'GET') {
            $this->response(Response::estado405());
        }
        if ($_SESSION['rol'] !== "Administrador" && $_SESSION['rol'] !== "Cajero") {
            return $this->response(response::estado403());
        }
        try {
            $view = new view();
            session_regenerate_id(true);
            if (!empty($_SESSION['activo'])) {
                echo $view->render('venta', 'index');
            } else {
                echo $view->render('auth', 'index');
            }
        } catch (Exception $e) {
            $this->response(Response::estado404($e));
        }
    }

    public function createVenta()
    {

        if ($this->method !== 'POST') {
            return $this->response(Response::estado405());
        }

        if ($this->data === null) {
            return $this->response(response::estado400('Datos JSON no válidos.'));
        }

        guard::validateToken($this->header, guard::secretKey());

        try {
            $productos = $this->data['productos'] ?? [];
            $chicasRaw = $this->data['usuario_id'] ?? [];
            $chicas = [];

            foreach ($chicasRaw as $item) {
                if (is_array($item)) {
                    foreach ($item as $subItem) {
                        $chicas = array_merge($chicas, explode(',', $subItem));
                    }
                } elseif (is_string($item)) {
                    $chicas = array_merge($chicas, explode(',', $item));
                } elseif (is_numeric($item)) {
                    $chicas[] = $item;
                }
            }

            $chicas = array_map('trim', $chicas);
            $chicas = array_map('intval', $chicas);

            $d_venta = [
                'codigo' => (string) $this->data['codigo'],
                'cliente_id' => (int) $this->data['cliente_id'],
                'pieza_id' => (int) $this->data['pieza_id'],
                'metodo_pago' => (string) $this->data['metodo_pago'],
                'iva' => (int) $this->data['iva'],
                'total' => (int) $this->data['total'],
                'total_comision' => (int) $this->data['total_comision'] ?? 0,
            ];

            $venta = $this->venta->createVenta($d_venta);
            if ($venta !== 'ok') {
                return $this->response(response::estado500('Error al crear la venta'));
            }

            $id_venta = $this->venta->getLastVenta();

            foreach ($productos as $value) {
                $d_detalle_venta = [
                    'venta_id' => (int)$id_venta['id_venta'],
                    'producto_id' => (int)$value['id_producto'],
                    'precio' => (int)$value['precio'],
                    'cantidad' => (int)$value['cantidad'],
                    'comision' => (int)$value['comision'],
                    'sub_total' => (int)$value['subtotal'],
                ];
                $detalle_venta = $this->venta->createDetalleVenta($d_detalle_venta);

                if ($detalle_venta !== 'ok') {
                    return $this->response(response::estado500('Error al crear el detalle de la venta'));
                }
            }

            if ($this->data['propina'] > 0) {
                $propina = $this->propina->createPropina($this->data['propina']);
                if ($propina !== 'ok') {
                    return $this->response(response::estado500('Error al crear la propina'));
                }

                $personal = $this->venta->getMeserosCajera();
                if (empty($personal)) {
                    return $this->response(Response::estado204('No se encontraron meseros y cajeras disponibles'));
                }

                $id_propina = $this->propina->getLastPropina()['id_propina'];
                if (empty($id_propina)) {
                    return $this->response(Response::estado500('Error al obtener el ID de la propina'));
                }
                if (count($personal) > 0) {
                    $propinaPorPersona = $this->data['propina'] / count($personal);
                    foreach ($personal as $empleado) {
                        $d_propina = [
                            'monto' => (int)$propinaPorPersona,
                            'propina_id' => (int)$id_propina,
                            'usuario_id' => (int)$empleado['usuario_id']
                        ];
                        $propina_detalle = $this->propina->createDetallePropina($d_propina);
                        if ($propina_detalle !== 'ok') {
                            return $this->response(response::estado500('Error al crear el detalle de la propina'));
                        }
                    }
                }
            }

            if (!empty($chicas)) {
                $comisiones = [];

                foreach ($chicas as $chica) {
                    if (!isset($comisiones[$chica])) {
                        $comisiones[$chica] = 0;
                    }
                }


                foreach ($productos as $index => $producto) {
                    $id_producto_comision = $producto['comision'] ?? 0;

                    if (isset($chicasRaw[$index])) {
                        $usuario_producto = [];
                        $item_raw = $chicasRaw[$index];


                        if (is_array($item_raw)) {
                            foreach ($item_raw as $subItem) {
                                $usuario_producto = array_merge($usuario_producto, explode(',', $subItem));
                            }
                        } else {
                            $usuario_producto = explode(',', $item_raw);
                        }

                        $usuario_producto = array_map('trim', $usuario_producto);
                        $usuario_producto = array_map('intval', $usuario_producto);

                        $num_usuarios = count($usuario_producto);
                        if ($num_usuarios > 0) {
                            $comision_usuario = $id_producto_comision / $num_usuarios;
                            foreach ($usuario_producto as $usuario) {
                                if (!isset($comisiones[$usuario])) {
                                    $comisiones[$usuario] = 0;
                                }
                                $comisiones[$usuario] += $comision_usuario;
                            }
                        }
                    }
                }

                $d_comision = [
                    'venta_id' => (int)$id_venta['id_venta'],
                    'monto' => (int)$this->data['total_comision'],
                ];

                $comision = $this->comision->createComision($d_comision);
                if ($comision !== 'ok') {
                    return $this->response(response::estado500('Error al crear la comision'));
                }
                if (!empty($comisiones)) {
                    $id_comision = $this->comision->getLastComision()['comision_id'];
                    foreach ($comisiones as $usuario_id => $comisionPorUsuario) {
                        $d_detalle_comision = [
                            'comision_id' => (int)$id_comision,
                            'chica_id' => (int)$usuario_id,
                            'comision' => (int)$comisionPorUsuario,
                        ];

                        $detalle_comision = $this->comision->cretaeDetalleComision($d_detalle_comision);

                        if ($detalle_comision !== 'ok') {
                            return $this->response(response::estado500('Error al crear el detalle de la comisión'));
                        }

                        $d_usuario_venta = [
                            'venta_id' => (int)$id_venta['id_venta'],
                            'usuario_id' => (int)$usuario_id,
                        ];
                        $usuario_venta = $this->venta->createUsuarioVenta($d_usuario_venta);
                        if ($usuario_venta !== 'ok') {
                            return $this->response(response::estado500('Error al crear el detalle de usuario venta de la venta'));
                        }

                        $anticipos = $this->anticipo->getAnticipoUsuario($usuario_id);
                        if (!empty($anticipos) && is_array($anticipos)) {
                            foreach ($anticipos as $anticipo) {
                                if (isset($anticipo['id_anticipo']) && $this->data['total_comision'] > $anticipo['monto']) {
                                    $updateResult = $this->anticipo->updateAnticipo((int)$anticipo['id_anticipo']);
                                    if ($updateResult !== 'ok') {
                                        return $this->response(response::estado500('Error al actualizar el anticipo'));
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($this->data['id_pedido'])) {
                $pedido = $this->pedido->updatePedido($this->data['id_pedido']);
                if ($pedido !== 'ok') {
                    return $this->response(response::estado500('Error al actualizar el pedido'));
                }
            }

            $monto_cierre = 0;
            $monto_transferencia = 0;
            if ($this->data['metodo_pago'] === 'Efectivo') {
                $monto_cierre = $this->data['total'];
            } else if ($this->data['metodo_pago'] == 'Transferencia' || $this->data['metodo_pago'] == 'Tarjeta') {
                $monto_transferencia = $this->data['total'];
            }

            $d_caja = [
                'monto_cierre' => (int)$monto_cierre,
                'monto_trasferencia' => (int)$monto_transferencia,
            ];
            $caja = $this->caja->updateCaja($d_caja);
            if ($caja !== 'ok') {
                return $this->response(response::estado500('Error al actualizar la caja'));
            }

            if (!empty($this->data['id_cuenta'])) {
                $d_cuenta = [
                    'metodo_pago' => $this->data['metodo_pago'],
                    'id_cuenta' => $this->data['id_cuenta']
                ];
                $cuenta = $this->cuenta->cobrarCuenta($d_cuenta);
                if ($cuenta !== 'ok') {
                    return $this->response(response::estado500('Error al cobrar la cuenta'));
                }
            }
            return $this->response(response::estado201('Venta realizada con éxito'));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function getVentas()
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }

        guard::validateToken($this->header, guard::secretKey());

        try {
            $ventas = $this->venta->getVentas();
            if (!empty($ventas)) {
                return $this->response(response::estado200($ventas));
            }
            return $this->response(response::estado204('No se encontraron ventas'));
        } catch (Exception $e) {
            return $this->response(response::estado500($e));
        }
    }

    public function getVenta(int $id)
    {
        if ($this->method !== 'GET') {
            return $this->response(Response::estado405());
        }
        guard::validateToken($this->header, guard::secretKey());
        try {
            $venta = $this->venta->getVenta($id);
            if (!empty($venta)) {
                return $this->response(response::estado200($venta));
            }
            return $this->response(response::estado204());
        } catch (Exception $e) {
            return $this->response(Response::estado500($e));
        }
    }
}
