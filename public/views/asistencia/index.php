<?php
use app\config\layout;

$Layout = (new layout())
    ->layout()
    ->setTitle('Gestión de Asistencias')
    ->setPageTitle('Asistencias', 'fa-solid fa-calendar-check')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Asistencias')
    ->addScripts(BASE_URL . 'public/views/asistencia/asistencia.js')
    ->content(function () {
?>
    <div class="col-xl-12 mb-xl-10">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="row align-items-center">
                    <div class>
                        <small class="text-uppercase text-muted ls-1 mb-1"><b><?php echo defined('TITLE') ? TITLE : 'Asistencias'; ?></b></small>
                    </div>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <span class="text-gray-700 d-block m-1 fw-bold text-uppercase f-6" id="txt_fecha"></span>
                </div>
            </div>
            <div class="card-body pt-0">
                <h5 class="h3 mb-4">Lista de Asistencias</h5>
                <div id="asistencia_table">
                    <?php include_once 'public/components/asistencia/tableAsistencia.php' ?>
                </div>
            </div>
        </div>
    </div>

<?php
        include_once 'public/components/asistencia/modalAsistencia.php';
    })
    ->render();
