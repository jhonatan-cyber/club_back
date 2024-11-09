<div class="modal fade" tabindex="-1" id="ModalCuenta">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <div class="modal-body">
                    <div class="card-body">
                        <p class="text-center text-uppercase align-items-center text-gray-600 text-hover-primary  mb-1">

                            <b>Detalle Servicio</b>
                        </p>
                        <hr>
                        <div class="row">

                            <div class="col-6">

                                <small id="hora_s"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary mb-1">
                                </small>
                                <small id="fecha_s"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary mb-1">
                                </small>
                                <small id="codigo_s"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary mb-1">
                                </small>
                                <small id="usuario_s"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary mb-1">
                                </small>
                                <small id="cliente_s"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary mb-1">
                                </small>
                                <small id="tiempo_s"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary mb-1">
                                </small>

                            </div>
                            <div class="col-6">
                                <small id="pieza_s"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary mb-1">
                                </small>
                                <small id="precio_servicio_s"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary mb-1">
                                </small>
                                <small id="precio_pieza_s"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary mb-1">
                                </small>
                                <small id="iva_s"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary mb-1">
                                </small>

                                <small id="total_s"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary mb-1">
                                </small>
                                <small id="metodo_s"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary mb-1">
                                </small>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <p
                                class="text-center text-uppercase align-items-center text-gray-600 text-hover-primary mb-3 ">
                                <b>Detalle
                                    de Cuenta</b>
                            </p>
                            <hr>
                            <div id="kt_ecommerce_products_table_wrapper"
                                class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer">
                                        <thead>
                                            <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0">
                                                <th>Bebida</th>
                                                <th>Cantidad</th>
                                                <th>precio</th>
                                                <th>Sub total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600" id="detalle_cuenta"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="separator mx-1 my-4"></div>
                            <div class="col-12">
                                <div class="col-6 col-sm-12 mt-2">
                                    <small
                                        class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-1"><b>Metodo
                                            de pago</b></small>
                                    <div class="d-flex input-group input-group-solid mb-3">
                                        <span class="input-group-text" id="basic-addon1"><i
                                                class="fa-solid fa-money-bill"></i></span>
                                        <select
                                            class="form-select form-select-solid form-select-sm rounded-start-0 border-start"
                                            id="metodo_pago_c">
                                            <option value="0" selected>Seleccione un metodo de pago</option>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Tarjeta">Tarjeta</option>
                                            <option value="Transferencia">Transferencia</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <h5 id="total_c" class="text-gray-600 text-uppercase text-hover-primary  mb-3">
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-5">
                            <button id="btn_finalizar_servicio" type="button"
                                class="btn btn-light-dark btn-sm hover-elevate-up btn-block"><i
                                    class="fa-solid fa-door-open"></i> Finalizar
                                Servicio</button>
                            <button id="btn_cobrar_cuenta" type="button"
                                class="btn btn-light-dark btn-sm hover-elevate-up btn-block"><i
                                    class="fa-solid fa-cash-register"></i> Cobrar
                                Cuenta</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>