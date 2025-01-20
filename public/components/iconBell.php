<div  class="d-flex align-items-center ms-1 ms-lg-3">
    <div id="bell" class="btn btn-icon btn-active-light-primary position-relative w-30px h-30px w-md-40px h-md-40px menu-trigger"
        data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent"
        data-kt-menu-placement="bottom-end">
        <span class="svg-icon svg-icon-1">
            <i class="fa-regular fa-bell"></i>
        </span>
        <span id="pedido-count"
            class="badge badge-success position-absolute top-0 start-100 translate-middle">0</span>
    </div>
    <div class="menu menu-sub menu-sub-dropdown menu-column w-350px" data-kt-menu="true"
        style="z-index: 105; position: fixed; inset: 0px auto auto 0px; margin: 0px; transform: translate(452px, 65px);"
        data-popper-placement="bottom-end">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pedidos
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="mh-350px scroll-y py-3" id="pedido_">
                 
                </div>
            </div>
        </div>
    </div>
</div>