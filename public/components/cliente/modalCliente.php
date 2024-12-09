<div class="modal fade" tabindex="-1" id="Modalcliente">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="tituloCliente"></h3>
                <div class="btn btn-icon btn-sm btn-active-light-climary ms-2" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
            <div class="row m-3 text-center">
                <div class="col-12">
                    <small class="text-gray-600 fw-bold fs-7.5">Ingresa los detalles del nuevo cliente en el
                        formulario.</small>
                </div>
            </div>
            <div class="separator mx-1 my-4"></div>
            <form method="post" id="frmCliente">
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" id="id_cliente" name="id_cliente">
                        <div class="row">
                            <small class="text-gray-700 d-block m-1"><b>RUT</b></small>
                            <div class="input-group input-group-solid mb-3">
                                <span class="input-group-text" id="basic-addon1"><i
                                        class="fa-solid fa-address-card"></i></span>
                                <input type="number" class="form-control form-control-sm form-control-solid" id="run"
                                    placeholder="Cedula de identidad" />
                            </div>
                            <small class="text-gray-700 d-block m-1"><b>Nombre(s)</b></small>
                            <div class="input-group input-group-solid mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control form-control-sm form-control-solid" id="nombre_cl" placeholder="Nombre(s)" />
                            </div>
                            <small class="text-gray-700 d-block m-1"><b>Apellido(s)</b></small>
                            <div class="input-group input-group-solid mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-signature"></i></span>
                                <input type="text" class="form-control form-control-sm form-control-solid" id="apellido_cl" placeholder="Apellido(s)" />
                            </div>
                            <small class="text-gray-700 d-block m-1"><b>Telefono</b></small>
                            <div class="input-group input-group-solid mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-phone"></i></span>
                                <input type="number" class="form-control form-control-sm form-control-solid" id="telefono_cl" placeholder="Telefono" pattern="[0-9]{7,}" required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div id="registrar" class="col-6 text-center">
                        <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up"
                            onclick="createCliente(event)">
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
                <br>
            </form>

        </div>
    </div>
</div>