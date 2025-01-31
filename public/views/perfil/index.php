<?php

use app\config\layout;

$Layout = (new layout())
    ->layout()
    ->setTitle('Gestión de Usuario')
    ->setPageTitle('Usuario', 'fa-solid fa-user')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Usuario')
    ->addScripts(BASE_URL . 'public/views/perfil/perfil.js')
    ->content(function () {
?>
    <div class="col-xl-12 mb-xl-10">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="row align-items-center">
                    <div class>
                        <small
                            class="text-uppercase text-muted ls-1 mb-1"><b><?php echo defined('TITLE') ? TITLE : 'Planillas'; ?></b></small>
                        <h5 class="h3 mb-0">Vista del Perfil de Usuario</h5>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <?php include_once 'public/components/perfil/formUsuario.php' ?>

                    <div class="row container-fluid justify-content-center align-items-center text-center">
                        <div class="col-xl-8 col-md-12 col-sm-12">
                            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold row">
                                <li class="nav-item mt-2 text-center col-4">
                                    <a type="button" class="nav-link text-active-primary ms-0 me-10 py-5 hover-elevate-up" onclick="general();">
                                        Datos generales
                                    </a>
                                </li>
                                <li class="nav-item mt-2 text-center col-4">
                                    <a type="button" class="nav-link text-active-primary ms-0 me-10 py-5 hover-elevate-up" onclick="session();">
                                        Seguridad
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php

    })
    ->render();
