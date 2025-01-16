<?php
require_once 'app/config/layout.php';

layout()
    ->setTitle('Gestión de Inicio')
    ->setPageTitle('Dashboard', 'fa-solid fa-house-crack')
    ->addBreadcrumb('Dashboard', BASE_URL . 'home')
    ->addBreadcrumb('Dashboard')
    ->addScripts(BASE_URL . 'public/views/home/home.js')
    ->content(function () {
?>
    <div class="col-xl-12 mb-xl-10">
        <div class="card mb-5">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    <div class="octagon d-flex flex-center h-100px w-150px bg-dark mx-2">
                        <div class="text-center">
                            <div class="fs-lg-2hx fs-2x fw-bolder text-gray-800 d-flex align-items-center">
                                <div class="min-w-60px counted"><b class="text-gray-300" id="codigo"></b></div>
                            </div>
                            <span class="text-gray-300 fw-bold fs-4 lh-0">Codigo</span>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-3 col-sm-6 mb-10">
                        <div class="bg-light bg-opacity-20 rounded-3 p-6 mx-md-5">
                            <div class="row justify-content-between text-center">
                                <div class="col-2">
                                    <div
                                        class="d-flex flex-center w-60px h-60px rounded-3 bg-info bg-opacity-20">
                                        <i class="fa-solid fa-venus fa-2x"></i>
                                    </div>
                                </div>
                                <div class="col-10 ">
                                    <h3 class="mb-5">Chicas Disponibles</h3>
                                    <h1 class="text-gray-700 ">5</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-10">
                        <div class="bg-light bg-opacity-20 rounded-3 p-6 mx-md-5">
                            <div class="row justify-content-between text-center">
                                <div class="col-2">
                                    <div
                                        class="d-flex flex-center w-60px h-60px rounded-3 bg-danger bg-opacity-20">
                                        <i class="fa-solid fa-champagne-glasses"></i>
                                    </div>
                                </div>
                                <div class="col-10 ">
                                    <h3 class="mb-5">Meseros Disponibles</h3>
                                    <h1 class="text-gray-700 ">5</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-10">
                        <div class="bg-light bg-opacity-20 rounded-3 p-6 mx-md-5">
                            <div class="row justify-content-between text-center">
                                <div class="col-2 text-center justify-content-center">
                                    <div
                                        class="d-flex flex-center w-60px h-60px rounded-3 bg-primary bg-opacity-20">
                                        <i class="fa-solid fa-house"></i>
                                    </div>
                                </div>
                                <div class="col-10 text-center justify-content-center">
                                    <h3 class="mb-5">Piezas Disponibles</h3>
                                    <h1 class="text-gray-700 ">5</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
        include_once 'public/components/home/modalPlanilla.php';
    })
    ->render();
