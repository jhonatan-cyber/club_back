<div class="modal fade" tabindex="-1" id="Modalpassword">
    <div class="modal-dialog modal-dialog-centered modal-xxl">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#b2b1b4 !important;">
                <h3 class="modal-title">Actualizar Contraseña</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-1"><i class="fa-solid fa-xmark"></i></span>
                </div>
            </div>
            <div class="separator mx-1 my-4"></div>
            <form method="post" id="frmPassword">
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">

                    <form id="kt_modal_update_password_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#">

                        <div class="fv-row mb-10 fv-plugins-icon-container">
                            <label class="required form-label fs-6 mb-2">Contraseña Actual</label>

                            <div class="input-group input-group-solid mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
                                <input type="password" pattern="[A-Za-z]{3}" class="form-control form-control form-control-solid" id="actual" name="actual" />
                                <span class="input-group-text" id="basic-addon2" onclick="mostrarPassword('actual', 'icono-actual')"><i class="fas fa-eye" id="icono-actual"></i></span>
                            </div>
                        </div>

                        <div class="mb-10 fv-row fv-plugins-icon-container" data-kt-password-meter="true">

                            <div class="mb-1">

                                <label class="form-label fw-semibold fs-6 mb-2">
                                    Nueva Contraseña
                                </label>

                                <div class="position-relative mb-3">
                                    <div class="input-group input-group-solid mb-3">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
                                        <input type="password" pattern="[A-Za-z]{3}" class="form-control form-control form-control-solid" id="nuevo" name="nuevo" />
                                        <span class="input-group-text" id="basic-addon2" onclick="mostrarPassword('nuevo', 'icono-nuevo')"><i class="fas fa-eye" id="icono-nuevo"></i></span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                </div>

                            </div>

                            <div class="text-muted">
                                Utilice 8 o más caracteres con una combinación de letras, números y caracteres. símbolos.
                            </div>

                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>

                        <div class="fv-row mb-10 fv-plugins-icon-container">
                            <label class="form-label fw-semibold fs-6 mb-2">Confirmar nueva contraseña</label>
                            <div class="input-group input-group-solid mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
                                <input type="password" pattern="[A-Za-z]{3}" class="form-control form-control form-control-solid" id="confirmar_nuevo" name="confirmar_nuevo" />
                                <span class="input-group-text" id="basic-addon2" onclick="mostrarPassword('confirmar_nuevo', 'icono-confirmar_nuevo')"><i class="fas fa-eye" id="icono-confirmar_nuevo"></i></span>
                            </div>
                        </div>

                        <div class="text-center pt-15">
                            <button type="reset" class="btn btn-light-dark btn-sm hover-elevate-up me-3" data-bs-dismiss="modal">
                                <i class="fa fa-times"></i> Cancelar
                            </button>

                            <button type="submit" class="btn btn-light-dark btn-sm hover-elevate-up" onclick="updatePassword(event);">
                                <span class="indicator-label">
                                    <i class="fa fa-save"></i> Guardar
                                </span>
                            </button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
            </form>
        </div>
    </div>
</div>