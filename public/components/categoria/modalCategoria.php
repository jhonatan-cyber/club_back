<div class="modal fade" tabindex="-1" id="ModalCategoria">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="tituloCategoria"></h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
            <div class="row m-3 text-center">
                <div class="col-12">
                    <small class="text-gray-600 fw-bold fs-7.5">El registro de categorias es importante para la gestión de los productos</small>
                </div>
            </div>
            <div class="separator mx-1 my-4"></div>
            <form method="post" id="frmCategoria">
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" class="form-control" id="id_categoria">
                        <small class="text-gray-700 d-block m-1"><b>Nombre de la Categoria</b></small>
                        <div class="input-group input-group-solid mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-address-card"></i></span>
                            <input type="text" class="form-control form-control-sm form-control-solid" id="nombre" placeholder="Nombre del Categoria" />
                        </div>
                        <small class="text-gray-700 d-block m-1"><b>Descripcion de la Categoria</b></small>
                        <div class="input-group input-group-solid mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-audio-description"></i></span>
                            <textarea class="form-control" id="descripcion"  placeholder="Ingrese descripcion" type="text"  style="height: 100px;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row text-center ">
                <div class="col-6 text-center"><button type="button" class="btn btn-light-dark btn-sm hover-elevate-up" onclick="createCategoria(event)"><i class="fa fa-save"></i> Guardar</button></div>
                <div class="col-6 text-center"><button type="button" class="btn btn-light-dark btn-sm hover-elevate-up" data-bs-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button></div>

                </div>
            </form>
            <br>
        </div>
    </div>
</div>