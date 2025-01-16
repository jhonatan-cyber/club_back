<div class="d-flex flex-column flex-root">
    <div class="page d-flex flex-row flex-column-fluid">
        <div id="kt_aside" class="aside " data-kt-drawer="true" data-kt-drawer-name="aside"
            data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
            data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
            data-kt-drawer-toggle="#kt_aside_toggle">
            <div class="aside-logo d-flex justify-content-center align-items-center pt-5 pb-3 " id="kt_aside_logo">
                <a href="<?php echo BASE_URL ?>home">
                    <img alt="Logo" src="<?php echo BASE_URL ?>public/assets/img/sistema/logo2.png" class="logo-default"
                        style="width:auto; height: 100px;" />
                </a>
            </div>
            <div class="aside-menu flex-column-fluid px-3 px-lg-6">
                <div class="menu menu-column menu-sub-indention menu-active-bg menu-pill menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-arrow-gray-500 fw-semibold fs-5 my-5 mt-lg-2 mb-lg-0"
                    id="kt_aside_menu" data-kt-menu="true">
                    <div class="hover-scroll-y me-n3 pe-3" id="kt_aside_menu_wrapper" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
                        data-kt-scroll-wrappers="#kt_aside_menu"
                        data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-offset="20px">
                        <div class="menu-item mb-1">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>home">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
                                            <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2"
                                                fill="currentColor" />
                                            <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2"
                                                fill="currentColor" />
                                            <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Dashboards</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <div class="menu-content">
                                <div class="separator mx-1 my-1"></div>
                            </div>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>cajas" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                <i class="fa-solid fa-cash-register"></i>
                                </span>
                                <span class="menu-title">Cajas</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <div class="menu-content">
                                <div class="separator mx-1 my-1"></div>
                            </div>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>clientes" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-users"></i>
                                </span>
                                <span class="menu-title">Clientes</span>
                            </a>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>roles" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-address-card"></i>
                                </span>
                                <span class="menu-title">Roles</span>
                            </a>
                        </div>

                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>usuarios" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-user-plus"></i>
                                </span>
                                <span class="menu-title">Usuarios</span>
                            </a>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>asistencias" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                <i class="fa-solid fa-calendar-check"></i>
                                </span>
                                <span class="menu-title">Asistencias</span>
                            </a>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>horasExtras" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                <i class="fa-solid fa-hourglass-half"></i>
                                </span>
                                <span class="menu-title">Horas Extras</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <div class="menu-content">
                                <div class="separator mx-1 my-1"></div>
                            </div>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>categorias" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-layer-group"></i>
                                </span>
                                <span class="menu-title">Categorias</span>
                            </a>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>productos" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                    <i class="fa-brands fa-product-hunt"></i>
                                </span>
                                <span class="menu-title">Productos</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <div class="menu-content">
                                <div class="separator mx-1 my-1"></div>
                            </div>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>pedidos" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-champagne-glasses"></i>
                                </span>
                                <span class="menu-title">Pedidos</span>
                            </a>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>ventas" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                <i class="fa-solid fa-store"></i>
                                </span>
                                <span class="menu-title">Ventas</span>
                            </a>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>cuentas" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-coins"></i>
                                </span>
                                <span class="menu-title">Cuentas</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <div class="menu-content">
                                <div class="separator mx-1 my-1"></div>
                            </div>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>habitaciones" data-bs-toggle="tooltip"
                                data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-house-user"></i>
                                </span>
                                <span class="menu-title">Habitaciones</span>
                            </a>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>servicios" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-venus-mars"></i>
                                </span>
                                <span class="menu-title">Servicios</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <div class="menu-content">
                                <div class="separator mx-1 my-1"></div>
                            </div>
                        </div>
                      
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>propinas" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                <i class="fa-solid fa-dollar-sign"></i>
                                </span>
                                <span class="menu-title">Propinas</span>
                            </a>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>comisiones" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-hand-holding-dollar"></i>
                                </span>
                                <span class="menu-title">Comisiones</span>
                            </a>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>anticipos" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-money-bill-transfer"></i>
                                </span>
                                <span class="menu-title">Anticipos</span>
                            </a>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>devoluciones" data-bs-toggle="tooltip"
                                data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-money-bill-wheat"></i>
                                </span>
                                <span class="menu-title">Devoluciones</span>
                            </a>
                        </div>
                        <div class="menu-item mb-3">
                            <a class="menu-link hover-elevate-up shadow-sm parent-hover btn btn-light-dark btn-sm"
                                href="<?php echo BASE_URL ?>planillas" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-list-check"></i>
                                </span>
                                <span class="menu-title">Planillas</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>