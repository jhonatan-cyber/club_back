<div class="modal fade" tabindex="-1" id="ModalDevolucionVenta">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Devolución de venta</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
            <div class="row m-3 text-center">
                <div class="col-12">
                    <small class="text-gray-600 fw-bold fs-7.5">Ingresa los detalles de la devolución.</small>
                </div>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" id="id_devolucion_venta">
                        <div class="row">
                        <small class="text-gray-700 d-block m-1"><b>Cliente</b></small>
                            <div class="input-group input-group-solid flex-nowrap">
                                <span class="input-group-text"><i class="fa-solid fa-users"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select
                                        class="form-select form-select-solid form-select-sm rounded-start-0 border-start"
                                        id="cliente_id">
                                    </select>
                                </div>
                            </div>
                            <small class="text-gray-700 d-block m-1"><b>Acompañante</b></small>
                            <div class="input-group input-group-solid flex-nowrap">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select
                                        class="form-select form-select-solid form-select-sm rounded-start-0 border-start"
                                        id="chica_id">
                                    </select>
                                </div>
                            </div>

                            <small class="text-gray-700 d-block m-1"><b>Producto</b></small>

                            <div class="input-group input-group-solid flex-nowrap">
                                <span class="input-group-text"><i class="fa-solid fa-scale-balanced"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select
                                        class="form-select form-select-solid form-select-sm rounded-start-0 border-start"
                                        id="producto_id">
                                    </select>
                                </div>
                            </div>
                            <small class="text-gray-700 d-block m-1"><b>Cantidad a devolver</b></small>
                            <div class="input-group input-group-solid ">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-arrow-down-1-9"></i></span>
                                <input type="number" min="0" class="form-control form-control-sm form-control-solid"
                                    id="cantidad" placeholder="Cantidad a devolver" />
                                    
                            </div>
                            <small class="text-gray-700 d-block m-1 mt-2 mb-3" id="subtotal"></small>
                            <small class="text-gray-700 d-block m-1"><b>Monto a devolver</b></small>
                            <div class="input-group input-group-solid mb-3">
                                <span class="input-group-text" id="basic-addon1"><i
                                        class="fa-solid fa-address-card"></i></span>
                                <input type="number" min="0" class="form-control form-control-sm form-control-solid"
                                    id="monto" placeholder="Monto a devolver" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center ">
                    <div class="col-6 text-center">
                        <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up"
                            onclick="createDevolucionVenta(event)">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                    </div>
                    <div class="col-6 text-center">
                        <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up"
                            data-bs-dismiss="modal">
                            <i class="fa fa-times"></i> Cancelar
                        </button>
                    </div>
                </div>
            </form>
            <br>
        </div>
    </div>
</div>