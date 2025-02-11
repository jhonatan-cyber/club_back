<?php

use app\config\layout;

$Layout = (new layout())
    ->layout()
    ->setTitle('Gestión de Detalle Planillas')
    ->setPageTitle('Detalle Planillas', 'fa-solid fa-list-ol')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Detalle Planillas')
    ->addScripts(BASE_URL . 'public/views/dplanilla/dplanilla.js')
    ->content(function () {
?>
    <style>
        .custom-day-cell {
            cursor: pointer;
            transition: background-color 0.3s ease;
            /* Animación suave */
        }

        .custom-day-cell:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }
    </style>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>


    <div class="col-xl-12 mb-xl-10">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="row align-items-center">
                    <div class>
                        <small
                            class="text-uppercase text-muted ls-1 mb-1"><b><?php echo defined('TITLE') ? TITLE : 'Detalle Planillas'; ?></b></small>
                        <h5 class="h3 mb-0">Calendario de Planillas Detallada</h5>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">

                <div id='calendar'></div>
            </div>
        </div>
    </div>

<?php
        include_once 'public/components/dplanilla/modalDplanilla.php';
    })
    ->render();
