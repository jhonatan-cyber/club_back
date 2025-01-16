<?php
require_once 'app/config/layout.php';

layout()
    ->setTitle('Gestión de Devoluciones')
    ->setPageTitle('Devoluciones', 'fa-solid fa-money-bill-wheat')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Devoluciones')
    ->addScripts(BASE_URL . 'public/views/devolucion/devolucion.js')
    ->content(function () {
?>
    <div id="devoluciones" class="col-xl-12 mb-xl-10">
        <div class="row align-items-center mb-5">
            <div class>
                <small
                    class="text-uppercase text-muted ls-1 mb-4"><b><?php echo defined('TITLE') ? TITLE : 'Devoluciones'; ?></b></small>
                <h4 class="h3 mb-0">Devoluciones</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div onclick="devolucionServicio(event)" type="button" class="col-4 rounded shadow-sm parent-hover bg-light-primary btn btn-outline btn-outline-dashed btn-outline-default px-6 py-5 m-2">
                <div class="m-4 px-9">
                    <div class="d-flex align-items-center mb-2">
                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                            <i class="fa-solid fa-venus-mars"></i>
                            <small>Devoluciones de Servicios</small>
                        </span>
                    </div>
                    <span class="fs-6 fw-semibold text-gray-600">
                        <small><b>Se realiza la devolucion del efectivo cancelado por el servicio de la dama acompañante </b></small>
                    </span>
                </div>
            </div>
            <div onclick="devolucionVenta(event)" type="button" class="col-4 rounded shadow-sm parent-hover bg-light-primary btn btn-outline btn-outline-dashed btn-outline-default px-6 py-5 m-2">
                <div class="m-4 px-9">
                    <div class="d-flex align-items-center mb-2">
                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                            <i class="fa-solid fa-champagne-glasses"></i>
                            <small>Devoluciones de Venta</small>
                        </span>
                    </div>
                    <span class="fs-6 fw-semibold text-gray-600">
                        <small><b>Se realiza la devolucion del efectivo cancelado por la venta de productos</b></small>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php include_once 'public/components/devolucion/cardDevolucionServicio.php' ?>
    <?php include_once 'public/components/devolucion/cardDevolucionVenta.php' ?>

<?php
        include_once 'public/components/devolucion/modalDetalleDevolucion.php';
    })
    ->render();
