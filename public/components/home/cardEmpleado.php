<div id="cardEmpleado" class="card-body">
    <div class="row mt-5">

        <h1><i class="fa-solid fa-person-dress"></i> <b id="nombreChica"></b></h1>

        <div id="cPropinas" class="col-lg-3 col-md-3 col-sm-6 mt-3">
            <input type="radio" class="btn-check" value="corporate" id="kt_create_account_form_account_type_corporate">
            <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center">
                <i class="fa-solid fa-dollar-sign fs-2x me-5"></i>
                <span class="d-block fw-bold text-start">
                    <span class="text-dark fw-bolder d-block fs-4 mb-2">Propinas por cobrar : <b id="propinas_usuario"></b></span>
                    <span class="text-gray-400 fw-bold fs-6">Vista de las propinas obtenidas</span>
                </span>
            </label>
        </div>
        <div id="cAsistencias" class="col-lg-3 col-md-3 col-sm-6 mt-3">
            <input type="radio" class="btn-check" value="corporate" id="kt_create_account_form_account_type_corporate">
            <label id="btnAsistencias" class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center">
                <i class="fa-solid fa-calendar-check fs-2x me-5"></i>
                <span class="d-block fw-bold text-start">
                    <span class="text-dark fw-bolder d-block fs-4 mb-2">Asistencias</span>
                    <span class="text-gray-400 fw-bold fs-6">Vista de asistencias</span>
                </span>
            </label>
        </div>
        <div id="cServicios" class="col-lg-3 col-md-3 col-sm-6 mt-3">
            <input type="radio" class="btn-check" value="corporate" id="kt_create_account_form_account_type_corporate">
            <label id="btnServicios" class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center">
                <i class="fa-solid fa-mars-and-venus fs-2x me-5"></i>
                <span class="d-block fw-bold text-start">
                    <span class="text-dark fw-bolder d-block fs-4 mb-2">Servicios </span>
                    <span class="text-gray-400 fw-bold fs-6">Vista de servicios realizados</span>
                </span>
            </label>
        </div>
        <div id="cAnticipos" class="col-lg-3 col-md-3 col-sm-6 mt-3">
            <input type="radio" class="btn-check" value="corporate" id="kt_create_account_form_account_type_corporate">
            <label id="btnAnticipos" class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center">
                <i class="fa-solid fa-money-bill-transfer fs-2x me-5"></i>
                <span class="d-block fw-bold text-start">
                    <span class="text-dark fw-bolder d-block fs-4 mb-2">Anticipos </span>
                    <span class="text-gray-400 fw-bold fs-6">Vista de los anticipos obtenidos</span>
                </span>
            </label>
        </div>
        <div id="cComisiones" class="col-lg-3 col-md-3 col-sm-6 mt-3">
            <input type="radio" class="btn-check" value="corporate" id="kt_create_account_form_account_type_corporate">
            <label id="btnComisiones" class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center">
                <i class="fa-solid fa-hand-holding-dollar fs-2x me-5"></i>
                <span class="d-block fw-bold text-start">
                    <span class="text-dark fw-bolder d-block fs-4 mb-2">Comisiones </span>
                    <span class="text-gray-400 fw-bold fs-6">Vista de las comisiones obtenidas por las ventas</span>
                </span>
            </label>
        </div>

        <a id="cPedidos" href="<?php echo BASE_URL ?>pedidos" class="col-lg-3 col-md-3 col-sm-6 mt-3">
            <input type="radio" class="btn-check" value="corporate" id="kt_create_account_form_account_type_corporate">
            <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center">
            <i class="fa-solid fa-champagne-glasses fs-2x me-5"></i>
                <span class="d-block fw-bold text-start">
                    <span class="text-dark fw-bolder d-block fs-4 mb-2">Pedidos </span>
                    <span class="text-gray-400 fw-bold fs-6">Realizar nuevos pedidos</span>
                </span>
            </label>
        </a>
    </div>
</div>

<?php include_once 'public/components/asistencia/modalAsistencia.php';
include_once 'public/components/home/modalAnticipo.php'; 
include_once 'public/components/home/modalComision.php';
include_once 'public/components/home/modalServicio.php';?>