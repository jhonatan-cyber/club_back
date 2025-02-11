<div class="modal fade" tabindex="-1" id="ModalDplanilla">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <div class="modal-body">
                    <div class="card-body">
                        <p
                            class="text-center justify-content-end align-items-center text-gray-600 text-hover-primary me-5 mb-2">

                            <b>Informacion de la Planilla del Dia</b>
                        </p>
                        <div class="separator mx-1 my-4"></div>
                        <div class="row">
                            <div class="col-6 ml-2">
                            
                                <small id="total_anticipos"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>
                                <small id="total_propinas"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>
                                <small id="total_servicios"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>
                                <small id="total_ventas"
                                    class="d-flex align-items-center text-gray-600 text-hover-primary me-5 mb-2">
                                </small>

                            </div>
                        </div>
                        <div class="separator mx-1 my-4"></div>
                        <div class="row">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="table-responsive">
                                        <table id="tbDplanilla" class="table table-striped gy-5 gs-7 border rounded w-100  align-middle table-row-dashed fs-6 gy-5 dataTable no-footer">
                                            <thead>
                                                <tr class="text-center fw-bolder text-gray-800 fw-bold fs-7 text-uppercase gs-0">
                                                    <th rowspan="2" class="align-middle border-bottom border-end w-200px">Nombre Apellido</th>
                                                    <th colspan="10" class="text-center align-middle border-bottom">Total Acumulado<br>(S + V + S + P + HX - AN - AP = T)</th>
                                                </tr>
                                                <tr class="text-center fw-bolder text-gray-800 fw-bold fs-7 text-uppercase gs-0">
                                                    <th class="ps-2">Sueldo</th>
                                                    <th>Comision<br>Ventas</th>
                                                    <th>Comison<br>Servicios</th>
                                                    <th>Propinas</th>
                                                    <th>Horas<br> Extras</th>
                                                    <th>Monto<br>Horas Extras</th>
                                                    <th>Anticipo</th>
                                                    <th>Aporte<br>AFP</th>
                                                    <th>Total</th>
                                                    <th>Detalle</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="text-center fw-semibold text-gray-600">
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
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