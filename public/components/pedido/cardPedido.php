<div class="row" id="nuevo_pedido" hidden>
    <div class="col-12 mb-5">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="row align-items-center">
                    <div>
                        <small class="text-uppercase text-muted ls-1 mb-1"><b><?php echo TITLE ?></b></small>
                        <h5 class="h3 mb-0">Nuevo Pedido</h5>
                    </div>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <button onclick="atras(event)" class="btn btn-light-dark btn-sm text-center hover-scale"><i
                            class="fa-solid fa-arrow-left"></i>
                        Atras</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 mb-5" id="car_ticket">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="row align-items-center">
                    <div class>
                        <small class="text-uppercase text-muted ls-1 mb-1"><b><?php echo TITLE ?></b></small>
                        <h5 class="h3 mb-0"><b>Datos Ticket Pedido</b></h5>
                    </div>
                </div>
            </div>
            <div class="separator mx-1 my-4"></div>
            <div class="card-body pt-0">
                <div class="row mt-4 mb-4">
                    <div class="col-xl-4 col-md-4 col-sm-6 mt-3">
                        <small for="cliente_id" class="form-label ls-1 mb-1"><b>Cliente</b></small>
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
                    <div class="col-xl-4 col-md-4 col-sm-6 mt-3">
                        <small for="chica_id" class="form-label ls-1 mb-1"><b>Anfitriona</b></small>
                        <div class="input-group input-group-solid flex-nowrap">
                            <span class="input-group-text"><i class="fa-solid fa-child-dress"></i></span>
                            <div class="overflow-hidden flex-grow-1">
                                <select
                                    class="form-select form-select-solid form-select-sm rounded-start-0 border-start"
                                    id="chica_id" data-allow-clear="true" multiple="multiple" data-placeholder="Seleccione acompañante"
                                    data-control="select2">
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-4 col-sm-6 mt-3">
                        <div class="col-12">
                            <div class="form-group mb-3 text-center">
                                <small class="text-muted"><i class="fas fa-money-bill"></i> $ Total</small>
                                <h5 class="text-gray-900"><b id="total"></b></h5>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-3 text-center">
                                <button type="button" class="btn btn-light-dark btn-sm text-center hover-scale"
                                    onclick="createPedido(event)">
                                    <i class="fa-brands fa-opencart"></i> Generar pedido</button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row m-5" id="precio_bebidas">
                </div>

                <div id="kt_ecommerce_products_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <small class="text-muted text-center d-block mb-3">Detalles Producto</small>

                    <div class="table-responsive">
                        <table class="table table-striped gy-5 gs-7 border rounded w-100  align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                            id="tbCarritoPedido">
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