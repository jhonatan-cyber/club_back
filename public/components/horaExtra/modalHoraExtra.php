<div class="modal fade" tabindex="-1" id="ModalHoraExtra">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Hora Extra</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
            <div class="row m-3 text-center">
                <div class="col-12">
                    <small class="text-gray-600 fw-bold fs-7.5">El registro de horas extras es importante para la gestión de los sueldos de los usuarios.</small>
                </div>
            </div>
            <div class="separator mx-1 my-4"></div>
            <form method="post">
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" class="form-control" id="id_hora_extra">
                        <small class="text-gray-700 d-block m-1"><b>Mesero</b></small>
                        <div class="row mb-3">
                            <div class="input-group input-group-solid flex-nowrap">
                                <span class="input-group-text"><i class="fa-solid fa-user-plus"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select
                                        class="form-select form-select-solid form-select-sm rounded-start-0 border-start"
                                        id="usuario_id">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <small class="text-gray-700 d-block m-1"><b>Horas</b></small>
                        <div class="input-group input-group-solid mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-clock"></i></span>
                            <input type="number" min="0" class="form-control form-control-sm form-control-solid"
                                id="hora" placeholder="Horas extra" />
                        </div>
                        <small class="text-gray-700 d-block m-1"><b>Monto</b></small>
                        <div class="input-group input-group-solid mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-money-bill-1-wave"></i></span>
                            <input type="number" min="0" class="form-control form-control-sm form-control-solid"
                                id="monto" placeholder="Monto extra" />
                        </div>
                        <small class="text-gray-700 d-block m-1"><b>Fecha</b></small>
                        <div class="input-group input-group-solid mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-calendar-days"></i></span>
                            <input type="text" min="0" class="form-control form-control-sm form-control-solid"
                                    id="fecha" disabled />
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                <div class="col-6 text-center"><button type="button"
                            class="btn btn-light-dark btn-sm hover-elevate-up" onclick="createHoraExtra(event)"><i
                                class="fa fa-save"></i> Guardar</button></div>
                    <div class="col-6 text-center"><button type="button"
                            class="btn btn-light-dark btn-sm hover-elevate-up" data-bs-dismiss="modal"><i
                                class="fa fa-times"></i> Cancelar</button></div>
                   
                </div>
            </form>
            <br>
        </div>
    </div>
</div>