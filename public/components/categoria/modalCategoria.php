<div class="modal fade" tabindex="-1" id="ModalCategoria">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#b2b1b4 !important;">
                <h3 class="modal-title" id="tituloCategoria"></h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-1"><i class="fa-solid fa-xmark"></i></span>
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
                        <input type="hidden" class="form-control" id="id_categoria" name="id_categoria">
                        <small class="text-gray-700 d-block m-1"><b>Nombre de la Categoria</b></small>
                        <div class="input-group input-group-solid mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-address-card"></i></span>
                            <input type="text" class="form-control form-control-sm form-control-solid" id="nombre_c" name="nombre_c" placeholder="Nombre del Categoria" aria-label="nombre" aria-describedby="basic-addon1" />
                        </div>
                        <small class="text-gray-700 d-block m-1"><b>Descripcion de la Categoria</b></small>
                        <div class="input-group input-group-solid mb-3">
                            <span class="input-group-text"><svg class="svg-inline--fa fa-audio-description" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="audio-description" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zM213.5 173.3l72 144c5.9 11.9 1.1 26.3-10.7 32.2s-26.3 1.1-32.2-10.7l-9.4-18.9H150.9l-9.4 18.9c-5.9 11.9-20.3 16.7-32.2 10.7s-16.7-20.3-10.7-32.2l72-144c4.1-8.1 12.4-13.3 21.5-13.3s17.4 5.1 21.5 13.3zm-.4 106.6L192 237.7l-21.1 42.2h42.2zM304 184c0-13.3 10.7-24 24-24h56c53 0 96 43 96 96s-43 96-96 96H328c-13.3 0-24-10.7-24-24V184zm48 24v96h32c26.5 0 48-21.5 48-48s-21.5-48-48-48H352z"></path></svg><!-- <i class="fa-solid fa-audio-description"></i> Font Awesome fontawesome.com --></span>
                            <textarea class="form-control" id="descripcion_c" name="descripcion_c" placeholder="Ingrese descripcion" type="text" aria-label="descripcion" style="height: 100px;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-6 text-center"><button type="button" class="btn btn-light-dark btn-sm hover-elevate-up" data-bs-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button></div>
                    <div class="col-6 text-center"><button type="button" class="btn btn-light-dark btn-sm hover-elevate-up" onclick="createCategoria(event)"><i class="fa fa-save"></i> Guardar</button></div>
                </div>
            </form>
            <br>
        </div>
    </div>
</div>