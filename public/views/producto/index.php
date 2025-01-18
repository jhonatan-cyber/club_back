<?php
use app\config\layout;

$Layout = (new layout())
    ->layout()
    ->setTitle('Gestión de Productos')
    ->setPageTitle('Productos', 'fa-brands fa-product-hunt')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Productos')
    ->addScripts(BASE_URL . 'public/views/producto/producto.js')
    ->content(function () {
?>
    <div class="col-xl-12 mb-xl-10">
        <div class="row" id="categorias">
        </div>
        <div class="row" id="productos" hidden>
            <div class="col-xl-12 mb-xl-10">
                <div class="card card-flush">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="row align-items-center">
                            <div class>
                                <small
                                    class="text-uppercase text-muted ls-1 mb-1"><b><?php echo TITLE ?></b></small>
                                <h5 class="h3 mb-0">Lista de Productos - <b id="nombr_bebida"></b></h5>
                            </div>
                        </div>
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <button class="btn btn-light-dark btn-sm text-center hover-scale"
                                onclick="Atras(event);"><i class="fa-solid fa-arrow-left"></i>
                                Atras</button>
                            <button class="btn btn-light-dark btn-sm text-center hover-scale"
                                onclick="Mproducto(event);"><i class="fa fa-plus"></i> Nuevo</button>
                        </div>
                    </div>
                    <div id="usuario_table" class="card-body pt-0">
                        <?php include_once 'public/components/producto/tableProducto.php' ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

<?php
        include_once 'public/components/producto/modalProducto.php';
    })
    ->render();
