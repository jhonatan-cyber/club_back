<div class="modal fade" tabindex="-1" id="ModalPieza">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#b2b1b4 !important;">
                <h3 class="modal-title" id="tituloPieza"></h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                    aria-label="Close">
                    <span class="svg-icon svg-icon-1"><i class="fa-solid fa-xmark"></i></span>
                </div>
            </div>
            <div class="row m-3 text-center">
                <div class="col-12">
                    <small class="text-gray-600 fw-bold fs-7.5">El registro de las habitaciones es importante para el
                        control de los servicios brindados.</small>
                </div>
            </div>
            <div class="separator mx-1 my-4"></div>
            <form method="post" id="frmPieza">
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" class="form-control" id="id_pieza" name="id_pieza">
                        <small class="text-gray-700 d-block m-1"><b>Nombre</b></small>
                        <div class="input-group input-group-solid mb-3">
                            <span class="input-group-text" id="basic-addon1"><i
                                    class="fa-solid fa-house-user"></i></span>
                            <input type="text" class="form-control form-control-sm form-control-solid" id="nombre"
                                placeholder="Nombre de la habitación" />
                        </div>
                        <small class="text-gray-700 d-block m-1"><b>Precio</b></small>
                        <div class="input-group input-group-solid mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-coins"></i></span>
                            <input type="number" class="form-control form-control-sm form-control-solid" id="precio"
                                placeholder="Precio de la habitación" />
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-6 text-center"><button type="button"
                            class="btn btn-light-dark btn-sm hover-elevate-up" data-bs-dismiss="modal"><i
                                class="fa fa-times"></i> Cancelar</button></div>
                    <div class="col-6 text-center"><button type="button"
                            class="btn btn-light-dark btn-sm hover-elevate-up" onclick="createPieza(event)"><i
                                class="fa fa-save"></i> Guardar</button></div>
                </div>
            </form>
            <br>
        </div>
    </div>
</div>