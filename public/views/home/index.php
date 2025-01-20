<?php

use app\config\layout;

$Layout = (new layout())
    ->layout()
    ->setTitle('Gestión de Inicio')
    ->setPageTitle('Dashboard', 'fa-solid fa-house-crack')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Dashboard')
    ->addScripts(BASE_URL . 'public/views/home/home.js')
    ->content(function () {
?>
    <div class="col-xl-12 mb-xl-10">
        <div class="card mb-5">
            <?php
            include_once 'public/components/home/cardAdmin.php';
            include_once 'public/components/home/cardEmpleado.php';
            ?>
        </div>
    </div>

<?php
    })
    ->render();
