<?php
require_once 'app/config/layout.php';

layout()
    ->setTitle('Gestión de Comisiones')
    ->setPageTitle('Comisiones', 'fa-solid fa-hand-holding-dollar')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Comisiones')
    ->addScripts(BASE_URL . 'public/views/comision/comision.js')
    ->content(function () {
?>

    <div class="col-xl-12 mb-xl-10">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="row align-items-center">
                    <div class>
                        <small
                            class="text-uppercase text-muted ls-1 mb-1"><b><?php echo defined('TITLE') ? TITLE : 'Comisiones'; ?></b></small>
                        <h5 class="h3 mb-0">Lista comisiones</h5>
                    </div>
                </div>

            </div>
            <div id="comision_table" class="card-body pt-0">
                <?php include 'public/components/comision/tableComision.php' ?>
            </div>
        </div>
    </div>


<?php

    })
    ->render();
