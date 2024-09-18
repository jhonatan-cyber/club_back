<div class="modal fade" tabindex="-1" id="ModalUsuario">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#b2b1b4 !important;">
                <h3 class="modal-title" id="tituloUsuario"></h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                    aria-label="Close">
                    <span class="svg-icon svg-icon-1"><i class="fa-solid fa-xmark"></i></span>
                </div>
            </div>
            <div class="row m-3 text-center">
                <div class="col-12">
                    <small class="text-gray-600 fw-bold fs-7.5">Ingresa los detalles del nuevo usuario en el formulario.
                        Asegúrate de establecer el rol adecuado para obtener los permisos nesesarios para aceeder al
                        sistema.</small>
                </div>
            </div>
            <div class="separator mx-1 my-4"></div>
            <form method="post" id="frmUsuario">
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" id="id_usuario" name="id_usuario">
                        <input type="hidden" id="imagen_anterior">
                        <div class="row">
                            <div class="col-7">
                                <small class="text-gray-700 d-block m-1" id="txt_ci"><b>Documento de
                                        identidad</b></small>
                                <div class="input-group input-group-solid mb-3">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="fa-solid fa-address-card"></i></span>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        id="run" name="run" placeholder="run" />
                                </div>
                                <small class="text-gray-700 d-block m-1" id="txt_nombre"></small>
                                <div class="input-group input-group-solid mb-3">
                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        id="nombre" name="nombre" placeholder="Nombres" />
                                </div>
                                <small class="text-gray-700 d-block m-1" id="txt_apellido"></small>
                                <div class="input-group input-group-solid mb-3">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="fas fa-signature"></i></span>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        id="apellido" name="apellido" placeholder="Apellidos" />
                                </div>
                                <small class="text-gray-700 d-block m-1" id="txt_direccion"></small>
                                <div class="input-group input-group-solid mb-3">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="fas fa-map-location-dot"></i></span>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        id="direccion" name="direccion" placeholder="Direccion" />
                                </div>
                                <small class="text-gray-700 d-block m-1" id="txt_telefono"></small>
                                <div class="input-group input-group-solid mb-3">
                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-phone"></i></span>
                                    <input type="number" class="form-control form-control-sm form-control-solid"
                                        id="telefono" name="telefono" placeholder="Telefono" pattern="[0-9]{7,}"
                                        required />
                                </div>

                            </div>
                            <div class="col-5 mt-12">
                                <small class="text-gray-700 d-block text-center m-1"><b>Foto</b></small>
                                <div class="image-input image-input-outline image-input-placeholder image-input-empty"
                                    data-kt-image-input="true">
                                    <div id="imagen" class="image-input-wrapper w-125px h-125px"
                                        style="background-image: none;"></div>
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                        aria-label="Change avatar" data-bs-original-title="Change avatar"
                                        data-kt-initialized="1" aria-describedby="tooltip395151">
                                        <i class="fa-solid fa-pen"></i>
                                        <input type="file" class="d-none" id="foto" name="foto"
                                            onchange="preview(event)">
                                        <button type="button" class="btn btn-icon btn-sm btn-active-color-danger"
                                            data-kt-image-input-action="remove" onclick="deleteImg(this)">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </label>
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                        aria-label="Cancel avatar" data-bs-original-title="Cancel avatar"
                                        data-kt-initialized="1">
                                        <i class="fa-solid fa-xmark"></i>
                                    </span>
                                    <span
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                        aria-label="Remove avatar" data-bs-original-title="Remove avatar"
                                        data-kt-initialized="1">
                                        <i class="fa-solid fa-xmark"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="row" id="corre">
                                <small class="text-gray-700 d-block m-1" id="txt_correo"></small>
                                <div class="input-group input-group-solid mb-3">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="fas fa-envelope"></i></span>
                                    <input type="email" pattern="^(?=.*[0-9])$"
                                        class="form-control form-control-sm form-control-solid" id="correo"
                                        name="correo" placeholder="Correo electronico" aria-label="correo"
                                        aria-describedby="basic-addon1" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                        required />
                                </div>
                            </div>


                            <div class="row" id="contraseñas">
                                <div class="col-6">
                                    <small class="text-gray-700 d-block m-1" id="txt_contraseña"></small>
                                    <div class="input-group input-group-solid mb-3">
                                        <span class="input-group-text" id="basic-addon1"><i
                                                class="fa-solid fa-lock"></i></span>
                                        <input type="password" pattern="[A-Za-z]{3}"
                                            class="form-control form-control-sm form-control-solid" id="password"
                                            name="password" placeholder="Contraseña" aria-label="password"
                                            aria-describedby="basic-addon1" />
                                        <span class="input-group-text" id="basic-addon2"
                                            onclick="mostrarPassword('password', 'icono-password')"><i
                                                class="fas fa-eye" id="icono-password"></i></span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <small class="text-gray-700 d-block m-1" id="txt_confirmar"></small>
                                    <div class="input-group input-group-solid mb-3">
                                        <span class="input-group-text" id="basic-addon1"><i
                                                class="fas fa-key"></i></span>
                                        <input type="password" pattern="[A-Za-z]{3}"
                                            class="form-control form-control-sm form-control-solid" id="repetir"
                                            name="repetir" placeholder="Confirmar" aria-label="repetir"
                                            aria-describedby="basic-addon1" />
                                        <span class="input-group-text" id="basic-addon2"
                                            onclick="mostrarPassword('repetir', 'icono-repetir')"><i class="fas fa-eye"
                                                id="icono-repetir"></i></span>
                                    </div>
                                </div>
                            </div>
                            <small class="text-gray-700 d-block m-1"><b>Rol</b></small>
                            <div class="row mb-3">
                                <div class="input-group input-group-solid flex-nowrap">
                                    <span class="input-group-text"><i class="fa-solid fa-scale-balanced"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select
                                            class="form-select form-select-solid form-select-sm rounded-start-0 border-start"
                                            id="rol_id" name="rol_id">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-6 text-center">
                        <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up"
                            data-bs-dismiss="modal">
                            <i class="fa fa-times"></i> Cancelar
                        </button>
                    </div>
                    <div id="registrar" class="col-6 text-center">
                        <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up"
                            onclick="createUsuario(event)">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
                <br>
            </form>

        </div>
    </div>
</div>