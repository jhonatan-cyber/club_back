<?php
require_once 'app/config/layout.php';

layout()
    ->setTitle('Gestión de Cuentas')
    ->setPageTitle('Cuentas', 'fa-solid fa-coins')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Cuentas')
    ->addScripts(BASE_URL . 'public/views/cuenta/cuenta.js')
    ->content(function () {
?>
    <div class="col-xl-12 mb-xl-10">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="row align-items-center">
                    <div class>
                        <small
                            class="text-uppercase text-muted ls-1 mb-1"><b><?php echo defined('TITLE') ? TITLE : 'Cuentas'; ?></b></small>
                        <h5 class="h3 mb-0">Lista cuentas</h5>
                    </div>
                </div>
            </div>
            <div id="cuenta_table" class="card-body pt-0">
                <?php include_once 'public/components/cuenta/tableCuenta.php' ?>
            </div>
        </div>
    </div>

<?php
        include_once 'public/components/cuenta/modalDetalleCuenta.php';
        include_once 'public/components/cuenta/modalAgregarCuenta.php';
        include_once 'public/components/venta/modalBebida.php';
    })
    ->render();
