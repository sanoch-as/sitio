<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /sitio/public/login.php');
    exit();
}

require_once __DIR__ . '/../config/database.php'; // Conexión a la BD

$current_page = basename($_SERVER['PHP_SELF']);

// Obtener menús desde la BD
$query = "SELECT nombre, icono, url FROM menu ORDER BY orden";
$result = $conn->query($query);
$menus = $result->fetch_all(MYSQLI_ASSOC);
?>




<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Solicitudes </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="Inline validation is very easy to implement using the Architect Framework.">
    <meta name="msapplication-tap-highlight" content="no">

    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    
    <link href="./assets/css/main.css" rel="stylesheet"> 
    <link href="./assets/css/sanoch.css" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <div class="app-header header-shadow">
            <div class="app-header__logo">
                <div class="logo-src"></div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>


        </div>

        <div class="app-main">
            <div class="app-sidebar sidebar-shadow">
                <div class="app-header__logo">
                    <div class="logo-src"></div>
                    <div class="header__pane ml-auto">
                        <div>
                            <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="app-header__mobile-menu">
                    <div>
                        <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="app-header__menu">
                    <span>
                        <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                            <span class="btn-icon-wrapper">
                                <i class=" fa fa-ellipsis-v fa-w-6"></i>
                            </span>
                        </button>
                    </span>
                </div>
                <div class="scrollbar-sidebar">
                    <div class="app-sidebar__inner">

                        <ul class="vertical-nav-menu">
                            <li class="app-sidebar__heading">Solicitudes</li>

                            <?php foreach ($menus as $menu) : ?>
                                <li>
                                    <a href="<?= $menu['url'] ?>" class="<?= ($current_page == basename($menu['url'])) ? 'active' : '' ?>">
                                        <i class="<?= $menu['icono'] ?>" style="font-size:23px;"  aria-hidden="true" ></i> <?= $menu['nombre'] ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>


                    </div>
                </div>
            </div>
            <div class="app-main__outer"> <!-- Ventana Trabajo-->
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="metismenu-icon pe-7s-note2">
                                    </i>
                                </div>
                                <div>Consultar votante en Padrón Electoral ALEXIS SANCHEZ OCHOA
                                    <div class="page-title-subheading">Consultar si votante está registrado en el padrón electoral.
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div id="iframe" class="responsive-iframe"><iframe src="graph.php" style="width: 100%; height: 100%; border: none;"></iframe></div>
                </div>
            </div>

        </div>
    </div>
    <div class="app-wrapper-footer" style="padding-top: 20px;">
        <div class="app-footer">
            <div class="app-footer__inner" style="padding-top: 20px;"> Derechos Reservados - <a href="http://www.google.com"> Alexis Sánchez O.</a>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".vertical-nav-menu a").forEach(function(link) {
                link.addEventListener("click", function(event) {
                    event.preventDefault();

                    let url = this.getAttribute("href");
                    let iframeContainer = document.getElementById("iframe");

                    // Limpiar el contenedor y crear un nuevo iframe
                    iframeContainer.innerHTML = '';
                    let iframe = document.createElement("iframe");
                    iframe.src = url;
                    iframe.classList.add("responsive-iframe");

                    iframeContainer.appendChild(iframe);
                });
            });
        });
    </script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    
</body>

</html>