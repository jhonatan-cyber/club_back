<div class="row" id="nuevo_servicio" hidden>
    <div class="col-xl-12 mb-xl-10">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="row align-items-center">
                    <div>
                        <small class="text-uppercase text-muted ls-1 mb-1"><b><?php echo TITLE ?></b></small>
                        <h5 class="h3 mb-0">Nuevo Servicio</h5>
                    </div>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <buttonn onclick="atras()" class="btn btn-light-dark btn-sm text-center hover-scale"><i
                            class="fa-solid fa-arrow-left"></i>
                        Atras</>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3" id="car_ticket">
        <div class="col-xl-12 mb-xl-10"">
            <div class=" card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="row align-items-center">
                    <div class>
                        <small class="text-uppercase text-muted ls-1 mb-1"><b><?php echo TITLE ?></b></small>
                        <h5 class="h3 mb-0"><b>Datos Ticket Servicio</b></h5>
                    </div>
                </div>
            </div>
            <div class="separator mx-1 my-4"></div>
            <div class="card-body pt-0">
                <div class="row mt-4 mb-4">
                    <div class="col-xl-3 col-md-3 col-sm-6 mt-3">
                        <small class="form-label ls-1 mb-3"><b>Cliente</b></small>
                        <div class="input-group input-group-solid flex-nowrap">
                            <span class="input-group-text"><i class="fa-solid fa-users"></i></span>
                            <div class="overflow-hidden flex-grow-1">
                                <select
                                    class="form-select form-select-solid form-select-sm rounded-start-0 border-start"
                                    id="cliente_id" name="cliente_id" data-control="select2">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-3 col-sm-6 mt-3">
                        <small class="form-label ls-1 mb-3"><b>Dama acompañante</b></small>
                        <div class="input-group input-group-solid flex-nowrap">
                            <span class="input-group-text"><i class="fa-solid fa-child-dress"></i></span>
                            <div class="overflow-hidden flex-grow-1">
                                <select
                                    class="form-select form-select-solid form-select-sm rounded-start-0 border-start"
                                    id="usuario_id" data-allow-clear="true" multiple="multiple"
                                    data-placeholder="Seleccione acompañante" data-control="select2">
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-3 col-sm-6 mt-3">
                        <small class="form-label ls-1 mb-3"><b>Metodo
                                de pago</b></small>
                        <div class="d-flex input-group input-group-solid mb-3">
                            <span class="input-group-text" id="basic-addon1"><i
                                    class="fa-solid fa-money-bill"></i></span>
                            <select class="form-select form-select-solid form-select-sm rounded-start-0 border-start"
                                id="metodo_pago">
                                <option value="0" selected>Seleccione un metodo de pago</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta">Tarjeta</option>
                                <option value="Transferencia">Transferencia</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-3 col-sm-6 mt-3">
                        <small class="form-label ls-1 mb-3"><b>Pieza</b></small>
                        <div class="input-group input-group-solid flex-nowrap">
                            <span class="input-group-text"><i class="fa-solid fa-house-user"></i></span>
                            <div class="overflow-hidden flex-grow-1">
                                <select
                                    class="form-select form-select-solid form-select-sm rounded-start-0 border-start"
                                    id="pieza_id" name="pieza_id" data-control="select2">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-xl-3 col-md-3 col-sm-6 mt-3">
                        <small class="form-label ls-1 mb-3"><b>Precio</b></small>
                        <div class="input-group input-group-solid mb-3">
                            <span class="input-group-text" id="basic-addon1"><i
                                    class="fa-solid fa-money-bill-transfer"></i></span>
                            <input type="number" step="0.01" min="0"
                                class="form-control form-control-sm form-control-solid" id="precio"
                                placeholder="Precio" />
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-3 col-sm-6 mt-3">
                        <small class="form-label ls-1 mb-3"><b>Iva</b></small>
                        <div class="input-group input-group-solid mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-coins"></i></span>
                            <input type="number" step="0.01" min="0"
                                class="form-control form-control-sm form-control-solid" id="iva" placeholder="Iva"
                                disabled />
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-3 col-sm-6 mt-3">
                        <small class="form-label ls-1 mb-3"><b>Tiempo</b></small>
                        <div class="input-group input-group-solid mb-3">
                            <span class="input-group-text" id="basic-addon1"><i
                                    class="fa-solid fa-stopwatch-20"></i></span>
                            <input type="number" min="0" class="form-control form-control-sm form-control-solid"
                                id="tiempo" placeholder="Tiempo de servicio" />
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-3 col-sm-12 mt-3 text-center">
                        <div class="col-12">
                            <div class="form-group mb-3 text-center">
                                <small class="form-label ls-1text-muted"><i class="fas fa-money-bill"></i> <b>
                                        $ Total
                                    </b></small>
                                <h5 class="text-gray-900"><b id="total"></b></h5>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-3 text-center">
                            <button id="btn-generar" hidden type="button" class="btn btn-light-dark btn-sm text-center hover-scale"
                                    onclick="createCuentaServicio(event)">
                                    <i class="fa-brands fa-opencart"></i> Generar servicio</button>
                                <button id="btn-registrar" type="button" class="btn btn-light-dark btn-sm text-center hover-scale"
                                    onclick="createServicio(event)">
                                    <i class="fa-brands fa-opencart"></i> Generar servicio</button>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="mt-4" id="producto_servicio" hidden>
                    <div class="row" id="precio_bebidas">
                    </div>
                    <div class="separator mx-1 my-4"></div>
                    <div id="kt_ecommerce_products_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <small class="text-muted text-center d-block">Detalles Producto</small>
                        <div class="separator mx-1 my-4"></div>
                        <div class="table-responsive">
                            <table class="table table-striped gy-5 gs-7 border rounded w-100  align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                id="tbCarritoCuenta">
                                <thead>
                                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0">
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Sub Total</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>