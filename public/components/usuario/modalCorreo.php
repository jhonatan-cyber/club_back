<div class="modal fade" tabindex="-1" id="Modalcorreo">
    <div class="modal-dialog modal-dialog-centered modal-xxl">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#b2b1b4 !important;">
                <h3 class="modal-title">Actualizar correo</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-1"><i class="fa-solid fa-xmark"></i></span>
                </div>
            </div>
            <div class="separator mx-1 my-4"></div>
            <form method="post" id="frmCorreo">
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <form id="kt_modal_update_email_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#">
                        <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
                            <i class="ki-duotone ki-information fs-2tx text-primary me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="d-flex flex-stack flex-grow-1 ">
                                <div class=" fw-semibold">
                                    <div class="fs-6 text-gray-700 ">Tenga en cuenta que se requiere una dirección de correo electrónico válida para completar la verificación y acceso a su cuenta.</div>
                                </div>
                            </div>
                        </div>
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <label class="fs-6 fw-semibold form-label mb-2">
                                <span class="required">Dirección de correo electrónico</span>
                            </label>
                            <input class="form-control form-control-solid" id="email_" name="email_" type="email">
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                        </div>
                        <div class="text-center pt-15">
                            <button type="reset" class="btn btn-light-dark btn-sm hover-elevate-up me-3" data-bs-dismiss="modal">
                            <i class="fa fa-times"></i> Cancelar
                            </button>

                            <button type="submit" class="btn btn-light-dark btn-sm hover-elevate-up" onclick="updateCorreo(event);">
                                <span class="indicator-label">
                                <i class="fa fa-save"></i> Guardar
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>