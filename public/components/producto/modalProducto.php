<div class="modal fade" tabindex="-1" id="ModalProducto">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="tituloProducto"></h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
            <div class="row m-3 text-center">
                <div class="col-12">
                    <small class="text-gray-600 fw-bold fs-7.5">Ingresa los detalles del nuevo producto en el
                        formulario, es importante para la gestion de ventas y pedidos.</small>
                </div>
            </div>
            <div class="separator mx-1 my-4"></div>
            <form method="post">
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" id="id_producto" name="id_producto">
                        <input type="hidden" id="imagen_anterior">
                        <div class="row">
                            <div class="col-7">
                                <small class="text-gray-700 d-block m-1" id="txt_codigo"><b>Codigo</b></small>
                                <div class="input-group input-group-solid mb-3">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="fa-solid fa-barcode"></i></span>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        id="codigo" disabled />
                                </div>
                                <small class="text-gray-700 d-block m-1" id="txt_nombre"></small>
                                <div class="input-group input-group-solid mb-3">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="fa-brands fa-product-hunt"></i></span>
                                    <input type="text" class="form-control form-control-sm form-control-solid"
                                        id="nombre" placeholder="Nombre" />
                                </div>
                                <small class="text-gray-700 d-block m-1" id="txt_precio"></small>
                                <div class="input-group input-group-solid mb-3">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="fa-solid fa-money-bill-transfer"></i></span>
                                    <input type="number" step="0.01" min="0"
                                        class="form-control form-control-sm form-control-solid" id="precio"
                                        placeholder="Precio" />
                                </div>
                                <small class="text-gray-700 d-block m-1" id="txt_comision"></small>
                                <div class="input-group input-group-solid mb-3">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="fa-solid fa-coins"></i></span>
                                    <input type="number" step="0.01" min="0"
                                        class="form-control form-control-sm form-control-solid" id="comision"
                                        placeholder="Comisión" />
                                </div>
                            </div>
                            <div class="col-5 mt-6">
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
                            <small class="text-gray-700 d-block m-1" id="txt_descripcion"></small>
                            <div class="input-group input-group-solid mb-3">
                                <span class="input-group-text"> <i class="fa-solid fa-audio-description"></i> </span>
                                <textarea class="form-control" id="descripcion" name="glosa" placeholder="Descripción"
                                    type="text"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div id="registrar" class="col-6 text-center">
                        <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up"
                            onclick="createProducto(event)">
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