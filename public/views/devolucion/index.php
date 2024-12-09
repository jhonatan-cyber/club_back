<!DOCTYPE html>
<html lang="es">
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
                        <i class="fa-solid fa-money-bill-wheat"></i> Devolucion <small class="text-muted fs-6 fw-normal ms-1"></small>
                    </h1>
                    <ul class="breadcrumb fw-semibold fs-base mb-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="<?php echo BASE_URL ?>Dashboard" class="text-muted text-hover-primary">
                                Dashboard </a>
                        </li>

                        <li class="breadcrumb-item text-muted">
                            Devolucion </li>
                    </ul>
                </div>
                <?php include_once 'public/views/layout/navbar.php' ?>
            </div>
        </div>
        <!-- contenido del sistema -->
        <div class="content d-flex flex-column flex-column-fluid fs-6" id="kt_content">
            <div class="container-fluid">
                <div class="row gy-5 g-xl-10">
                    <div id="devoluciones" class="col-xl-12 mb-xl-10">
                        <div class="row align-items-center mb-5">
                            <div class>
                                <small
                                    class="text-uppercase text-muted ls-1 mb-4"><b><?php echo TITLE ?></b></small>
                                <h4 class="h3 mb-0">Devoluciones</h4>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div onclick="devolucionServicio(event)" type="button" class="col-4 rounded shadow-sm parent-hover bg-light-primary btn btn-outline btn-outline-dashed btn-outline-default px-6 py-5 m-2">
                                <div class="m-4 px-9">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                                            <i class="fa-solid fa-venus-mars"></i>
                                            <small>Devoluciones de Servicios</small>
                                        </span>
                                    </div>
                                    <span class="fs-6 fw-semibold text-gray-600">
                                        <small><b>Se realiza la devolucion del efectivo cancelado por el servicio de la dama acompañante </b></small>
                                    </span>
                                </div>
                            </div>
                            <div onclick="devolucionVenta(event)" type="button" class="col-4 rounded shadow-sm parent-hover bg-light-primary btn btn-outline btn-outline-dashed btn-outline-default px-6 py-5 m-2">
                                <div class="m-4 px-9">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
                                        <i class="fa-solid fa-champagne-glasses"></i>
                                            <small>Devoluciones de Venta</small>
                                        </span>
                                    </div>
                                    <span class="fs-6 fw-semibold text-gray-600">
                                        <small><b>Se realiza la devolucion del efectivo cancelado por la venta de productos</b></small>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include_once 'public/components/devolucion/cardDevolucionServicio.php' ?>
                    <?php include_once 'public/components/devolucion/cardDevolucionVenta.php' ?>
                </div>
            </div>
        </div>
        <!--fin contenido del sistema -->
        <?php include_once 'public/views/layout/footer.php' ?>

</body>
<script src="<?php echo BASE_URL ?>public/views/devolucion/devolucion.js"></script>

</html>
<?php include_once 'public/components/devolucion/modalDetalleDevolucion.php' ?>
