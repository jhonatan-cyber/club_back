<!DOCTYPE html>
<html lang="es">
<?php require_once 'public/views/layout/head.php'; ?>

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-fixed">
    <?php require_once 'public/views/layout/aside.php'; ?>
    <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
        <div id="kt_header" class="header " data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
            <div class=" container-fluid  d-flex align-items-stretch justify-content-between" id="kt_header_container">
                <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-2 mb-5 mb-lg-0" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
                    <h1 class="text-dark fw-bold mt-1 mb-1 fs-2">
                    <i class="fa-solid fa-hourglass-half"></i>  Horas Extras <small class="text-muted fs-6 fw-normal ms-1"></small>
                    </h1>
                    <ul class="breadcrumb fw-semibold fs-base mb-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="<?php echo BASE_URL ?>home" class="text-muted text-hover-primary">
                                Dashboard </a>
                        </li>

                        <li class="breadcrumb-item text-muted">
                            Horas Extras </li>
                    </ul>
                </div>
                <?php require_once 'public/views/layout/navbar.php'; ?>
            </div>
        </div>
        <div class="content d-flex flex-column flex-column-fluid fs-6" id="kt_content">
            <div class="container-fluid ">
                <div class="row gy-5 g-xl-10">
                    <div class="col-xl-4 mb-xl-10 mobile-hide">
                        <div class="card shadow-sm">
                            <div class="card-body p-0">
                                <div class="card-p mb-10 text-center">
                                    <div class="text-center px-4">
                                        <img class="mw-100 mh-300px card-rounded-bottom" alt="" src="<?php echo BASE_URL ?>public/assets/img/sistema/horaExtra.png" />
                                    </div>
                                    <hr>
                                    <h5 class="text-muted mb-3">Agregar Hora Extra</h5>
                                    <button class="btn btn-light-dark btn-sm hover-elevate-up" onclick="Mhora(event);"><i class="fa fa-plus"></i> Nuevo</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 mb-xl-10 col-sm-12 mb-sm-5">
                        <div class="card shadow-sm">
                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                <div class="row align-items-center">
                                    <div class>
                                        <small class="text-uppercase text-muted ls-1 mb-1"><b><?php echo TITLE ?></b></small>
                                        <h5 class="h3 mb-0">Lista Horas Extras</h5>
                                    </div>
                                </div>
                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                    <button id="mod" class="btn btn-light-dark btn-sm hover-elevate-up" onclick="Mhora(event);"><i class="fa fa-plus"></i> Nuevo</button>
                                </div>
                            </div>
                            <?php require_once 'public/components/horaExtra/tableHoraExtra.php' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once 'public/views/layout/footer.php' ?>
        <script src="<?php BASE_URL ?>public/views/horaExtra/horaExtra.js"></script>
    </div>
</body>

</html>
<?php require_once 'public/components/horaExtra/modalHoraExtra.php' ?>
<?php require_once 'public/components/horaExtra/modalDetalleHoraExtra.php' ?>