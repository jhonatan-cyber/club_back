<div class="modal fade" tabindex="-1" id="ModalDetalleHoraExtra">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <div class="modal-body">
                    <div class="card-body">
                        <p
                            class="text-center justify-content-end align-items-center text-gray-600 text-hover-primary me-5 mb-2">

                            <b>Informacion de las Horas Extra</b>
                        </p>
                        <div class="row">

                            <div class="col-6">
                                <hr>

                                </small>
                                <small id="usuario_hora"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>
                            </div>
                            <div class="col-6">
                                <hr>
                                <small id="total_horas_extras"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>

                                <small id="total_pagar_horas"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>

                            </div>
                        </div>
                        <div class="separator mx-1 my-4"></div>
                        <div class="row">
                            <div id="kt_ecommerce_products_table_wrapper"
                                class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer">
                                        <thead>
                                            <tr class="text-center text-gray-800 fw-bold fs-7 text-uppercase gs-0">
                                                <th>Fecha</th>
                                                <th>Horas</th>
                                                <th>Monto</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600" id="detalle_horas_extra"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-5">
                            <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up"
                                data-bs-dismiss="modal">
                                <i class="fa fa-times"></i> Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>