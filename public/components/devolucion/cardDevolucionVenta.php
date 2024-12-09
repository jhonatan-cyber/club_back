<div hidden id="devolucion_venta" class="col-xl-12 mb-xl-10">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="row align-items-center">
                <div class>
                    <small
                        class="text-uppercase text-muted ls-1 mb-1"><b><?php echo TITLE ?></b></small>
                    <h5 class="h3 mb-0" id="title_">Lista devoluciones de venta</h5>
                </div>
            </div>
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                <button class="btn btn-light-dark btn-sm text-center hover-scale"
                    onclick="MDevolucionVenta(event)"><i class="fa fa-plus"></i> Nuevo</button>
                <button  class="btn btn-light-dark btn-sm text-center hover-scale"
                    onclick="atras();"><i class="fa-solid fa-arrow-left"></i> Atras</button>
            </div>
        </div>

        <div class="card-body pt-0">
            <?php include_once 'public/components/devolucion/tableDevolucionVenta.php' ?>
        </div>
    </div>
</div>
<?php include 'public/components/devolucion/modalDevolucionVenta.php' ?>