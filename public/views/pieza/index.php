<?php
use app\config\layout;

$Layout = (new layout())
    ->layout()
    ->setTitle('Gestión de Habitaciones')
    ->setPageTitle('Habitaciones', 'fa-solid fa-house-user')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Habitaciones')
    ->addScripts(BASE_URL . 'public/views/pieza/pieza.js')
    ->content(function () {
?>
    <div class="row">
        <div class="col-xl-4 mb-xl-10 mobile-hide">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="card-p mb-10 text-center">
                        <div class="text-center px-4">
                            <img class="mw-100 mh-300px card-rounded-bottom" alt=""
                                src="<?php echo BASE_URL ?>public/assets/img/sistema/pieza.png" />
                        </div>
                        <hr>
                        <h5 class="text-muted mb-3">Agregar Habitacion</h5>
                        <button class="btn btn-light-dark btn-sm hover-elevate-up"
                            onclick="MPieza(event);"><i class="fa fa-plus"></i> Nuevo</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 mb-xl-10 col-sm-12 mb-sm-5">
            <div class="card shadow-sm">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="row align-items-center">
                        <div class>
                            <small
                                class="text-uppercase text-muted ls-1 mb-1"><b><?php echo defined('TITLE') ? TITLE : 'HJabitaciones'; ?></b></small>
                            <h5 class="h3 mb-0">Lista de Habitaciones</h5>
                        </div>
                    </div>
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <button id="mod" class="btn btn-light-dark btn-sm hover-elevate-up"
                            onclick="MPieza(event);"><i class="fa fa-plus"></i> Nuevo</button>
                    </div>
                </div>

                <?php include 'public/components/pieza/tablePieza.php'; ?>

            </div>
        </div>
    </div>
<?php
        require_once 'public/components/pieza/modalPieza.php';
    })
    ->render();
