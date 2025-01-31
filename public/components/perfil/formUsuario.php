<div class="col-xl-6 col-md-6 col-sm-12">
    <div class="row mb-4">
        <div class="col-4">
            <div>
                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative" id="imgperfil">
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="d-flex align-items-center m-2">
                <a class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                    <font id="nomapes"> </font>
                </a>
            </div>
            <a class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                <span class="svg-icon svg-icon-4 me-1"><svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <i class="fa-solid fa-address-card"></i>
                    </svg>
                </span>
                <font style="vertical-align: inherit;">
                    <font style="vertical-align: inherit;" id="eci">
                    </font>
                </font>
            </a>
        </div>
    </div>

    <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">

                        <a class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                            <span class="svg-icon svg-icon-4 me-1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <i class="fa-solid fa-location-dot"></i>
                                </svg>
                            </span>
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;" id="dir">
                                </font>
                            </font>
                        </a>
                        <a class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                            <span class="svg-icon svg-icon-4 me-1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <i class="fa-solid fa-at"></i>
                                </svg>
                            </span>
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;" id="mail">
                                </font>
                            </font>
                        </a>
                        <a class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                            <span class="svg-icon svg-icon-4 me-1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <i class="fa-solid fa-phone"></i>
                                </svg>
                            </span>
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;" id="tel">
                                </font>
                            </font>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-6 col-md-6 col-sm-12">
    <div class="text-center container-fluid ">
        <div class="d-flex flex-column flex-grow-1 pe-8">
            <div class="d-flex flex-wrap">
                <form method="post" id="frmperfil">
                    <input type="hidden" id="id_usuario_perfil">
                    <input type="hidden" id="rol_id_perfil" >
                    <div class="card-body border-top p-9">
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">Avatar</font>
                                </font>
                            </label>
                            <div class="col-lg-8">
                                <div class="image-input image-input-outline image-input-placeholder image-input-empty" data-kt-image-input="true">
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: none;"></div>
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change avatar" data-bs-original-title="Change avatar" data-kt-initialized="1" aria-describedby="tooltip395151">
                                        <i class="fa-solid fa-pen"></i>
                                        <input type="file" class="d-none" id="foto" name="foto" onchange="preview(event)">
                                        <button type="button" class="btn btn-icon btn-sm btn-active-color-danger" data-kt-image-input-action="remove" onclick="deleteImg(this)">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar" data-bs-original-title="Cancel avatar" data-kt-initialized="1">
                                        <i class="fa-solid fa-xmark"></i>
                                    </span>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar" data-bs-original-title="Remove avatar" data-kt-initialized="1">
                                        <i class="fa-solid fa-xmark"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">C.I</font>
                                </font>
                            </label>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-6 fv-row fv-plugins-icon-container input-group input-group-solid">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-address-card"></i></span>
                                        <input type="text" class="form-control form-control-md form-control-solid" name="ci" id="ci" placeholder="Cedula de identidad" aria-describedby="basic-addon1" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">Nombre completo</font>
                                </font>
                            </label>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-6 fv-row fv-plugins-icon-container input-group input-group-solid mb-3">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control form-control-md form-control-solid" name="nombre" id="nombre" placeholder="Nombre(s)" aria-describedby="basic-addon1" required />
                                    </div>
                                    <div class="col-lg-6 fv-row fv-plugins-icon-container input-group input-group-solid">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-signature"></i></span>
                                        <input type="text" class="form-control form-control-md form-control-solid" name="apellido" id="apellido" placeholder="Apellido(s)" aria-describedby="basic-addon1" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">Direccion</font>
                                </font>
                            </label>
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-6 fv-row fv-plugins-icon-container input-group input-group-solid">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-map-location-dot"></i></span>
                                        <input type="tel" class="form-control form-control-md form-control-solid" name="direccion" id="direccion" placeholder="Direccion" aria-label="edireccion" aria-describedby="basic-addon1" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">
                                <span>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;">Telefono</font>
                                    </font>
                                </span>
                            </label>

                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-lg-6 fv-row fv-plugins-icon-container input-group input-group-solid">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control form-control-md form-control-solid" name="telefono" id="telefono" placeholder="Número de teléfono" aria-label="telefono" aria-describedby="basic-addon1" pattern="[0-9]{7,}" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center mb-5">
                        <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up mb-2" onclick="editar_perfil(event)"><i class="fa fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up" onclick="limpiar()"><i class="fa-solid fa-broom"></i> Limpiar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>