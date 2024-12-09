<div class="modal fade" tabindex="-1" id="ModalRol">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="tituloRol"></h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
            <div class="row m-3 text-center">
                <div class="col-12">
                    <small class="text-gray-600 fw-bold fs-7.5">El registro de roles es importante para la gestión de los usuarios</small>
                </div>
            </div>
            <div class="separator mx-1 my-4"></div>
            <form method="post" id="frmRol">
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" class="form-control" id="id_rol" name="id_rol">
                        <small class="text-gray-700 d-block m-1"><b>Nombre del Rol</b></small>
                        <div class="input-group input-group-solid mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-address-card"></i></span>
                            <input type="text" class="form-control form-control-sm form-control-solid" id="nombre_r" name="nombre_r" placeholder="Nombre del Rol" aria-label="nombre" aria-describedby="basic-addon1" />
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-6 text-center"><button type="button" class="btn btn-light-dark btn-sm hover-elevate-up" onclick="createRol(event)"><i class="fa fa-save"></i> Guardar</button></div>
                    <div class="col-6 text-center"><button type="button" class="btn btn-light-dark btn-sm hover-elevate-up" data-bs-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button></div>

                </div>
            </form>
            <br>
        </div>
    </div>
</div>