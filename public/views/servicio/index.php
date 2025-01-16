<?php
require_once 'app/config/layout.php';

layout()
    ->setTitle('Gestión de Sercicios')
    ->setPageTitle('Sercicios', 'fa-solid fa-champagne-glasses')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Sercicios')
    ->addScripts(BASE_URL . 'public/views/servicio/servicio.js')
    ->content(function () {
?>
    <div class="col-xl-12 mb-xl-10">
        <div class="row" id="lista_servicio">
            <div class="col-xl-12 mb-xl-10">
                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="row align-items-center">
                            <div class>
                                <small
                                    class="text-uppercase text-muted ls-1 mb-1"><b><?php echo defined('TITLE') ? TITLE : 'Sercicios'; ?></b></small>
                                <h5 class="h3 mb-0">Lista Servicios</h5>
                            </div>
                        </div>
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <button class="btn btn-light-dark btn-sm text-center hover-scale"
                                onclick="nuevoServicio(event);"><i class="fa-solid fa-plus"></i>
                                Nuevo</button>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row" id="servicios">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once 'public/components/servicio/cardServicio.php' ?>
    </div>

<?php
        include_once 'public/components/servicio/modalBebida.php';
        include_once 'public/components/servicio/modalCuenta.php';
    })
    ->render();
