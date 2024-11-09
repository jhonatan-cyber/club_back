<?php include_once 'public/views/layout/head.php' ?>

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-fixed">
    <?php include_once 'public/views/layout/aside.php' ?>
    <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
        <div id="kt_header" class="header " data-kt-sticky="true" data-kt-sticky-name="header"
            data-kt-sticky-offset="{default: '200px', lg: '300px'}">
            <div class=" container-fluid  d-flex align-items-stretch justify-content-between" id="kt_header_container">
                <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-2 mb-5 mb-lg-0"
                    data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
                    <h1 class="text-dark fw-bold mt-1 mb-1 fs-2">
                        Dashboard <small class="text-muted fs-6 fw-normal ms-1"></small>
                    </h1>
                    <ul class="breadcrumb fw-semibold fs-base mb-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="<?php echo BASE_URL ?>home" class="text-muted text-hover-primary">
                                Dashboard </a>
                        </li>

                        <li class="breadcrumb-item text-muted">
                            Dashboard </li>
                    </ul>
                </div>
                <?php include_once 'public/views/layout/navbar.php' ?>
            </div>
        </div>
        <!-- contenido del sistema -->
        <div class="content d-flex flex-column flex-column-fluid fs-6" id="kt_content">
            <div class=" container-fluid " id="kt_content_container">
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
        </div>
        <?php include_once 'public/views/layout/footer.php' ?>

</body>
<script src="<?php echo BASE_URL ?>public/views/home/home.js"></script>

</html>