<div class="d-flex d-lg-none align-items-center flex-grow-1">
    <div class="btn btn-icon btn-circle btn-active-light-primary ms-n2 me-1" id="kt_aside_toggle">
        <span class="svg-icon svg-icon-1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                    fill="currentColor" />
                <path opacity="0.3"
                    d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                    fill="currentColor" />
            </svg>
        </span>
    </div>
</div>

<div class="d-flex align-items-center flex-shrink-0">
    <?php include_once 'public/components/iconBell.php' ?>
    <?php include_once 'public/components/iconMode.php' ?>
    <div class="d-flex align-items-center ms-2 ms-lg-3" id="kt_header_user_menu_toggle">
        <div id="avatar" class="cursor-pointer symbol symbol-circle symbol-35px symbol-md-40px"
            data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent"
            data-kt-menu-placement="bottom-end">
        </div>
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
            data-kt-menu="true">
            <div class="menu-item px-3">
                <div class="menu-content d-flex px-3">
                    <div class="d-flex flex-column">
                        <a href="#" class="fw-semibold text-hover-primary fs-7">
                            <b class="text-center" id="correoo"></b>
                        </a>
                    </div>
                </div>
            </div>
            <div class="separator my-2"></div>
            <div class="menu-item px-5">
                <a href="<?php echo BASE_URL ?>perfil" class="menu-link px-5">
                    Perfil
                </a>
            </div>
            <div class="separator my-2"></div>
            <div class="menu-item px-5">
                <a class="btn menu-link px-5" onclick="logaout(event)">
                    Salir
                </a>
            </div>
        </div>
    </div>
</div>