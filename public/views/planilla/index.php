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
                    <i class="fa-solid fa-list-check"></i> Planilla <small class="text-muted fs-6 fw-normal ms-1"></small>
                    </h1>
                    <ul class="breadcrumb fw-semibold fs-base mb-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="<?php echo BASE_URL ?>Dashboard" class="text-muted text-hover-primary">
                                Dashboard </a>
                        </li>

                        <li class="breadcrumb-item text-muted">
                            Planilla </li>
                    </ul>
                </div>
                <?php include_once 'public/views/layout/navbar.php' ?>
            </div>
        </div>
        <!-- contenido del sistema -->
        <div class="content d-flex flex-column flex-column-fluid fs-6" id="kt_content">
            <div class="container-fluid">
                <div class="row gy-5 g-xl-10">
                    <div class="col-xl-12 mb-xl-10"> 
                        <div class="card card-flush">
                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                <div class="row align-items-center">
                                    <div class>
                                        <small
                                            class="text-uppercase text-muted ls-1 mb-1"><b><?php echo TITLE ?></b></small>
                                        <h5 class="h3 mb-0">Lista planilla de sueldos</h5>
                                    </div>
                                </div>
                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                    <button class="btn btn-light-dark btn-sm text-center hover-scale"
                                        onclick="Mplanilla(event);"><i class="fa fa-plus"></i> Nuevo</button>
                                </div>
                            </div>
                            <div id="planilla_table" class="card-body pt-0">
                                <?php include_once 'public/components/planilla/tablePlanilla.php' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--fin contenido del sistema -->
        <?php include_once 'public/views/layout/footer.php' ?>

</body>
<script src="<?php echo BASE_URL ?>public/views/planilla/planilla.js"></script>

</html>
<?php include_once 'public/components/planilla/modalPlanilla.php' ?>