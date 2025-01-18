<?php
use app\config\layout;

$Layout = (new layout())
    ->layout()
    ->setTitle('Gestión de Propinas')
    ->setPageTitle('Propinas', 'fa-solid fa-dollar-sign')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Propinas')
    ->addScripts(BASE_URL . 'public/views/propina/propina.js')
    ->content(function () {
?>

    <div class="col-xl-12 mb-xl-10">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="row align-items-center">
                    <div class>
                        <small
                            class="text-uppercase text-muted ls-1 mb-1"><b><?php echo defined('TITLE') ? TITLE : 'Propinas'; ?></b></small>
                        <h5 class="h3 mb-0">Lista propinas</h5>
                    </div>
                </div>

            </div>
            <div id="propina_table" class="card-body pt-0">
                <?php include_once 'public/components/propina/tablePropina.php' ?>
            </div>
        </div>
    </div>
<?php
    })
    ->render();
