<div class="modal fade" tabindex="-1" id="ModalDetalleVenta">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <div class="modal-body">
                    <div class="card-body">
                        <p
                            class="text-center justify-content-end align-items-center text-gray-600 text-hover-primary me-5 mb-2">

                            <b>Informacion de la Venta</b>
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
                                <small id="codigo_d_venta"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>
                                <small id="usuario"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>
                              
                            </div>
                            <div class="col-6">
                                <hr>
                                <small id="cliente"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>
                                <small id="total_comision"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>
                                <small id="metodo"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>
                            </div>
                        </div>
                     
                        <div class="row">
                            <h5 class="text-gray-600 text-uppercase text-hover-primary me-5 mb-3 text-center ">Detalle de Venta</h5>
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
                                        <tbody class="fw-semibold text-gray-600" id="detalle_productos"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="separator mx-1 my-4"></div>
                            <div class="col-12">

                                <div class="d-flex justify-content-end">
                                    <h4 id="total_" class="text-gray-600 text-uppercase text-hover-primary me-5 mb-3">
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-5">
                            <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up btn-block"
                                onclick="cerrarModal(event)"><i class="fa fa-xmark"></i> cerrar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>