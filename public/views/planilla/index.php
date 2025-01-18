<?php
use app\config\layout;

$Layout = (new layout())
    ->layout()
    ->setTitle('Gestión de Planillas')
    ->setPageTitle('Planillas', 'fa-solid fa-calendar-check')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Planillas')
    ->addScripts(BASE_URL . 'public/views/planilla/planilla.js')
    ->content(function () {
?>
    <div class="col-xl-12 mb-xl-10">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="row align-items-center">
                    <div class>
                        <small
                            class="text-uppercase text-muted ls-1 mb-1"><b><?php echo defined('TITLE') ? TITLE : 'Planillas'; ?></b></small>
                        <h5 class="h3 mb-0">Lista planilla de sueldos</h5>
                    </div>
                </div>
            </div>
            <div id="planilla_table" class="card-body pt-0">
                <?php include_once 'public/components/planilla/tablePlanilla.php' ?>
            </div>
        </div>
    </div>

<?php
        include_once 'public/components/planilla/modalPlanilla.php';
    })
    ->render();
