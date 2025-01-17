<?php
use app\config\layout;

$Layout = (new layout())
    ->layout()
    ->setTitle('Gestión de Pedidos')
    ->setPageTitle('Pedidos', 'fa-solid fa-champagne-glasses')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Pedidos')
    ->addScripts(BASE_URL . 'public/views/pedido/pedido.js')
    ->content(function () {
?>
    <section class="row" id="lista_pedido">
        <div class="col-xl-12 mb-xl-10">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="row align-items-center">
                        <div class>
                            <small
                                class="text-uppercase text-muted ls-1 mb-1"><b><?php echo defined('TITLE') ? TITLE : 'Pedido'; ?></b></small>
                            <h5 class="h3 mb-0">Lista de pedidos</h5>
                        </div>
                    </div>
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <button class="btn btn-light-dark btn-sm text-center hover-scale"
                            onclick="nuevoPedido(event);"><i class="fa-solid fa-plus"></i>
                            Nuevo</button>
                    </div>
                </div>
                <div id="usuario_table" class="card-body pt-0">
                    <?php include_once 'public/components/pedido/tablePedido.php' ?>
                </div>
            </div>
        </div>
    </section>

    <?php include_once 'public/components/pedido/cardPedido.php' ?>

<?php
        include_once 'public/components/pedido/modalBebida.php';
        include_once 'public/components/pedido/modalVenta.php';
    })
    ->render();
