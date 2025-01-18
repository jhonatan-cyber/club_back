<div hidden id="devolucion_servicio" class="col-xl-12 mb-xl-10">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="row align-items-center">
                <div class>
                    <small
                        class="text-uppercase text-muted ls-1 mb-1"><b><?php echo TITLE ?></b></small>
                    <h5 class="h3 mb-0" id="title_servicios"></h5>
                </div>
            </div>
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                <button id="btn_nuevo" class="btn btn-light-dark btn-sm text-center hover-scale"
                    onclick="listarServicios(event);"><i class="fa fa-plus"></i> Nuevo</button>
                <button id="btn_atras_dev" hidden class="btn btn-light-dark btn-sm text-center hover-scale"
                    onclick="atras();"><i class="fa-solid fa-arrow-left"></i> Atras</button>
                <button id="btn_atras" hidden class="btn btn-light-dark btn-sm text-center hover-scale"
                    onclick="listarDevolucionesServicios();"><i class="fa-solid fa-arrow-left"></i> Atras</button>
            </div>
        </div>
        <div id="servicio_table" hidden class="card-body pt-0">
            <?php include_once 'public/components/devolucion/tableServicio.php' ?>
        </div>

        <div id="devolucion_table" class="card-body pt-0">
            <?php include_once 'public/components/devolucion/tableDevolucion.php' ?>
        </div>
    </div>
</div>