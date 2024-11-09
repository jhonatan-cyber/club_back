<div class="modal fade" tabindex="-1" id="ModalDetalleCuenta">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <div class="modal-body">
                    <div class="card-body">
                        <p
                            class="text-center justify-content-end align-items-center text-gray-600 text-hover-primary me-5 mb-2">

                            <b>Informacion de la Cuenta</b>
                        </p>
                        <div class="row">

                            <div class="col-6">
                                <hr>
                                <small id="hora"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>
                                <small id="fecha"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>
                                <small id="codigo"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>
                                <small id="usuario"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>
                                <small id="cliente"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>
                            </div>
                            <div class="col-6">
                                <hr>
                                <small class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-1"><b>Metodo
                                        de pago</b></small>
                                <div class="d-flex input-group input-group-solid mb-3">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="fa-solid fa-money-bill"></i></span>
                                    <select
                                        class="form-select form-select-solid form-select-sm rounded-start-0 border-start"
                                        id="metodo_pago">
                                        <option value="0" selected>Seleccione un metodo de pago</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Tarjeta">Tarjeta</option>
                                        <option value="Transferencia">Transferencia</option>
                                    </select>
                                </div>
                                <small id="comision"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2"></small>

                            </div>
                        </div>
                        <div class="separator mx-1 my-4"></div>
                        <div class="row">
                            <div id="kt_ecommerce_products_table_wrapper"
                                class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer">
                                        <thead>
                                            <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0">
                                                <th>Bebida</th>
                                                <th>Cantidad</th>
                                                <th>precio</th>
                                                <th>comision</th>
                                                <th>Sub total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600" id="detalle_productos"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="separator mx-1 my-4"></div>
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <h4 id="total" class="text-gray-600 text-uppercase text-hover-primary me-5 mb-3">
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-5">
                            <button id="btn_cobrar" type="button" class="btn btn-light-dark btn-sm hover-elevate-up"><i
                                    class="fa fa-save"></i> Cobrar Cuenta</button>
                            <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up"
                                data-bs-dismiss="modal">
                                <i class="fa fa-times"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>