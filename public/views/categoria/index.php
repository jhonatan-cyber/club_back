<?php
require_once 'app/config/layout.php';

layout()
    ->setTitle('Gestión de Categorias')
    ->setPageTitle('Roles', 'fa-solid fa-layer-group')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Categorias')
    ->addScripts(BASE_URL . 'public/views/categoria/categoria.js')
    ->content(function () {
?>
    <div class="row">
        <div class="col-xl-4 mb-xl-10 d-none d-xl-block">
            <div class="card shadow-sm">
                <div class="card-body text-center p-4">
                    <img class="mw-100 mh-300px card-rounded-bottom" alt="Categoría"
                        src="<?php echo BASE_URL ?>public/assets/img/sistema/categoria.png" />
                    <hr>
                    <h5 class="text-muted mb-3">Agregar Categoría</h5>
                    <button class="btn btn-light-dark btn-sm hover-elevate-up" onclick="MCategoria(event);">
                        <i class="fa fa-plus"></i> Nuevo
                    </button>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-sm-12 mb-5">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center py-4">
                    <div>
                        <small class="text-uppercase text-muted ls-1 mb-1"><b><?php echo defined('TITLE') ? TITLE : 'Categorias'; ?></b></small>
                        <h5 class="h3 mb-0">Lista de Categorias</h5>
                    </div>
                    <button class="btn btn-light-dark btn-sm hover-elevate-up" onclick="MCategoria(event);">
                        <i class="fa fa-plus"></i> Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <?php include 'public/components/categoria/tableCategoria.php'; ?>
                </div>
            </div>
        </div>
    </div>
<?php
        include_once 'public/components/categoria/modalCategoria.php';
    })
    ->render();
