<div class="modal fade" tabindex="-1" id="ModalAgregarCuenta">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <div class="modal-body">
                    <div class="card-body">
                        <p
                            class="text-center justify-content-end align-items-center text-gray-600 text-hover-primary me-5 mt-5 mb-5">

                            <b>Agregar productos a la cuenta</b>
                        </p>
                        <div class="row" id="precio_bebidas">
                        </div>
                        <div class="separator mx-1 my-4"></div>
                        <div class="row">
                            <div id="kt_ecommerce_products_table_wrapper"
                                class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                        id="tbCarritoCuenta">
                                        <thead>
                                            <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0">
                                                <th>Bebida</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Sub Total</th>
                                                <th>Comision</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="separator mx-1 my-4"></div>
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <h4 id="total_cuenta"
                                        class="text-gray-600 text-uppercase text-hover-primary me-5 mb-3">
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-5">
                            <button id="btn_agregar_cuenta" type="button" class="btn btn-light-dark btn-sm hover-elevate-up"><i
                                    class="fa fa-save"></i> Agregar a la Cuenta</button>
                            <button onclick="cerrarModal(event)" type="button"
                                class="btn btn-light-dark btn-sm hover-elevate-up">
                                <i class="fa fa-times"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>