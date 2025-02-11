<?php

use app\config\layout;

$Layout = (new layout())
    ->layout()
    ->setTitle('Gestión de Ventas')
    ->setPageTitle('Ventas', 'fa-solid fa-store')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Ventas')
    ->addScripts(BASE_URL . 'public/views/venta/venta.js')
    ->content(function () {
?>
    <style>
        .cardi {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .cardi:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
    <div class="row" id="lista_venta">
        <div class="col-xl-12 mb-xl-10">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="row align-items-center">
                        <div class>
                            <small
                                class="text-uppercase text-muted ls-1 mb-1"><b><?php echo defined('TITLE') ? TITLE : 'Ventas'; ?></b></small>
                            <h5 class="h3 mb-0">Lista de Ventas</h5>
                        </div>
                    </div>
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <button id="btn_nuevo_venta" hidden class="btn btn-light-dark btn-sm text-center hover-scale"
                            onclick="nuevoVenta(event);"><i class="fa-solid fa-plus"></i>
                            Nuevo</button>
                    </div>
                </div>
                <div id="usuario_table" class="card-body pt-0">
                    <?php include_once 'public/components/venta/tableVenta.php' ?>
                </div>
            </div>
        </div>
    </div>
    <?php include_once 'public/components/venta/cardVenta.php' ?>
<?php
        include_once 'public/components/venta/modalBebida.php';
        include_once 'public/components/venta/ModalDetalleVenta.php';
    })
    ->render();
