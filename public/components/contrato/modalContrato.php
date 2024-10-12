<div class="modal fade" tabindex="-1" id="ModalContrato">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#b2b1b4 !important;">
                <h3 class="modal-title" id="tituloContrato"></h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                    aria-label="Close">
                    <span class="svg-icon svg-icon-1"><i class="fa-solid fa-xmark"></i></span>
                </div>
            </div>
            <div class="row m-3 text-center">
                <div class="col-12">
                    <small class="text-gray-600 fw-bold fs-7.5">El registro de los contratos es importante para la
                        gestión de los usuarios y sus sueldos.</small>
                </div>
            </div>
            <div class="separator mx-1 my-4"></div>
            <form method="post">
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" class="form-control" id="id_contrato" name="id_contrato">
                        <small class="text-gray-700 d-block m-1"><b>Usuario</b></small>
                        <div class="row mb-3">
                            <div class="input-group input-group-solid flex-nowrap">
                                <span class="input-group-text"><i class="fa-solid fa-user-plus"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select
                                        class="form-select form-select-solid form-select-sm rounded-start-0 border-start"
                                        id="usuario_id">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <small class="text-gray-700 d-block m-1"><b>Sueldo</b></small>
                        <div class="input-group input-group-solid mb-3">
                            <span class="input-group-text" id="basic-addon1"><i
                                    class="fa-solid fa-money-bill-trend-up"></i></span>
                            <input type="number" min="0" class="form-control form-control-sm form-control-solid"
                                id="sueldo" placeholder="Sueldo" />
                        </div>
                        <small class="text-gray-700 d-block m-1"><b>Aporte Fonasa</b></small>
                        <div class="input-group input-group-solid mb-3">
                            <span class="input-group-text" id="basic-addon1"><i
                                    class="fa-solid fa-comments-dollar"></i></span>
                            <input type="number" min="0" class="form-control form-control-sm form-control-solid"
                                id="fonasa" placeholder="Aporte a Fonasa" />
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-6 text-center"><button type="button"
                            class="btn btn-light-dark btn-sm hover-elevate-up" data-bs-dismiss="modal"><i
                                class="fa fa-times"></i> Cancelar</button></div>
                    <div class="col-6 text-center"><button type="button"
                            class="btn btn-light-dark btn-sm hover-elevate-up" onclick="createContrato(event)"><i
                                class="fa fa-save"></i> Guardar</button></div>
                </div>
            </form>
            <br>
        </div>
    </div>
</div>