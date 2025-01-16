<?php
require_once 'app/config/layout.php';

layout()
    ->setTitle('Gestión de Cajas')
    ->setPageTitle('Cajas', 'fa-solid fa-cash-register')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Cajas')
    ->addScripts(BASE_URL . 'public/views/caja/caja.js')
    ->content(function () {
?>
    <div class="col-xl-12 mb-xl-10">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="row align-items-center">
                    <div class>
                        <small
                            class="text-uppercase text-muted ls-1 mb-1"><b><?php echo defined('TITLE') ? TITLE : 'Cajas'; ?></b></small>
                        <h5 class="h3 mb-0">Lista cajas</h5>
                    </div>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <button id="btn_nuevo_caja" class="btn btn-light-dark btn-sm text-center hover-scale"
                        onclick="Mcaja(event);"><i class="fa fa-plus"></i> Nuevo</button>
                </div>
            </div>
            <div id="caja_table" class="card-body pt-0">
                <?php include_once 'public/components/caja/tableCaja.php' ?>
            </div>
        </div>
    </div>
<?php
        include_once 'public/components/caja/modalApertura.php';
    })
    ->render();
