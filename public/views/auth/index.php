<!DOCTYPE html>
<html lang="es">

<head>
    <title><?php echo TITLE ?> | Iniciar sesión</title>
    <meta charset="utf-8" />
    <meta name="description" content="Las muñecas de Ramón" />
    <meta name="keywords" content="Las muñecas de Ramón" />
    <meta name="author" content="NuweSoft" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo BASE_URL ?>public/assets/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL ?>public/assets/css/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL ?>public/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL ?>public/assets/css/toastr.css" rel="stylesheet" type="text/css">
    <style>
        img {
            image-rendering: crisp-edges;
            -webkit-filter: contrast(110%);
        }
    </style>
</head>

<body id="kt_body" class="auth-bg">
    <div class="d-flex flex-column flex-root mt-12">
        <div id="log">
            <div class="d-flex flex-column flex-xl-row flex-column-fluid">
                <div class="d-flex flex-column flex-lg-row-fluid">
                    <div class="d-flex flex-row-fluid flex-center p-10">
                        <div class="d-flex flex-column">
                            <img alt="Logo" src="<?php echo BASE_URL ?>public/assets/img/sistema/logo2.png"
                                class="mb-lg-5 mb-md-5" height="200px" />

                            <div id="ador">
                                <h1 class="text-dark fs-2x">Bien venido, Las Muñecas de Rámon</h1>
                                <div class="fw-bold fs-4 text-gray-400 mb-10 text-center">Nightclub exclusivo en la
                                    ciudad de Linares <br /><b>"Un lugar para caballeros"</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="adorno">
                        <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-150px min-h-xl-350px"
                            style="background-image: url('<?php echo BASE_URL; ?>public/assets/img/sistema/presentation.png')">
                        </div>
                    </div>
                </div>
                <div class="flex-row-fluid d-flex flex-center justfiy-content-xl-first p-10">
                    <div class="d-flex flex-center p-15 shadow rounded w-100 w-md-550px mx-auto ms-xl-20">
                        <form class="form" novalidate="novalidate" id="kt_free_trial_form">
                            <div class="text-center mb-10">
                                <h1 class="text-dark mb-3"> Iniciar sesión </h1>
                                <div class="text-gray-400 fw-bold fs-4">
                                    <small class="text-gray-600 fw-bold fs-7">Ingrese sus datos para iniciar sesión en
                                        su
                                        cuenta</small>
                                </div>
                            </div>
                            <div class="fv-row mb-10">
                                <label class="form-label fs-6 fw-bold text-dark">Correo</label>
                                <input class="form-control form-control-lg form-control-solid" type="text" id="correo"
                                    name="correo" autocomplete="off" placeholder="Direccion de correo electronico" />
                            </div>
                            <div class="fv-row mb-10">
                                <div class="d-flex flex-stack mb-2">
                                    <label class="form-label fw-bold text-dark fs-6 mb-0">Contraseña</label>
                                </div>
                                <input class="form-control form-control-lg form-control-solid" type="password"
                                    id="password" name="password" autocomplete="off" placeholder="Contraseña" />
                            </div>
                            <div class="text-center pb-lg-0 pb-8">
                                <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up"
                                    onclick="login(event)">
                                    <span class="indicator-label">Iniciar sesión</span>
                                </button>
                                <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up">
                                    <span class="indicator-label">Has olvidado tu contraseña ?</span>
                                </button>
                                <div class=" d-flex flex-center flex-wrap mt-5 ">
                                    <?php include_once 'public/components/iconMode.php' ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="cod" class="mt-12">
            <div class="d-flex flex-center flex-column flex-column-fluid mt-6">
                <div class="w-lg-600px p-10 p-lg-15 mx-auto">
                    <form class="form w-100 mb-10" novalidate="novalidate">
                        <div class="text-center mb-10">
                            <img alt="Logo" class="mh-125px"
                                src="<?php echo BASE_URL ?>public/assets/img/sistema/logo.svg">
                        </div>
                        <div class="text-center mb-10">
                            <h1 class="text-dark mb-3">Verificación de dos pasos</h1>
                        </div>
                        <div class="mb-10 px-md-10">
                            <div class="fw-bolder text-center text-dark fs-6 mb-4">Ingresa tu código de seguridad de 4
                                dígitos</div>
                            <div class="d-flex flex-wrap justify-content-between">
                                <input id="cod1" type="text" data-inputmask="'mask': '9', 'placeholder': ''"
                                    maxlength="1"
                                    class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover me-2 my-2"
                                    inputmode="text">
                                <input id="cod2" type="text" data-inputmask="'mask': '9', 'placeholder': ''"
                                    maxlength="1"
                                    class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover me-2 my-2"
                                    inputmode="text">
                                <input id="cod3" type="text" data-inputmask="'mask': '9', 'placeholder': ''"
                                    maxlength="1"
                                    class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover me-2 my-2" " inputmode="
                                    text">
                                <input id="cod4" type="text" data-inputmask="'mask': '9', 'placeholder': ''"
                                    maxlength="1"
                                    class="form-control form-control-solid h-60px w-60px fs-2qx text-center border-primary border-hover me-2 my-2"
                                    inputmode="text">
                            </div>
                        </div>
                        <div class="d-flex flex-center">
                            <button type="button" class="btn btn-light-dark btn-sm hover-elevate-up"
                                onclick="verificarCodigo(event)">
                                <span class="indicator-label"><i class="fa-solid fa-paper-plane"></i> Enviar</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo BASE_URL ?>public/assets/js/plugins.bundle.js"></script>
    <script src="<?php echo BASE_URL ?>public/assets/js/scripts.bundle.js"></script>
    <script src="<?php echo BASE_URL ?>public/assets/js/axios.js"></script>
    <script src="<?php echo BASE_URL ?>public/assets/js/toastr.js"></script>
    <script src="<?php echo BASE_URL ?>public/assets/js/all.min.js"></script>
    <script>
        const BASE_URL = '<?php echo BASE_URL ?>';
    </script>
    <script src="<?php echo BASE_URL ?>public/views/auth/auth.js"></script>
</body>

</html>