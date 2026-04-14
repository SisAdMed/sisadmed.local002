<?php
$totmsj = '';
$totapr = '';
$tiempo = '';
$date_today = date("D, j M Y H:i:s ") . 'GMT';
?>
<!DOCTYPE html>
    <html lang="<?= SITE_LANG ?>" style="height: auto;">
    <head>
        <!---<meta http-equiv="Expires" content="<?= $date_today ?>">-->
        <meta http-equiv="Expires" content="">
        <meta http-equiv="Last-Modified" content="0">
        <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta charset="<?= SITE_CHARSET ?>">
        <meta http-equiv="X-UA-Compatible">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title><?= SITE_DESC ?></title>
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?= CSS ?>/all.min.css">
        <!-- Favicon
            <link rel="shortcut icon" href="<?= IMG ?>img/favicon.ico">
        -->
        <?= get_favicon(); ?>
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="<?= CSS ?>/tempusdominus-bootstrap-4.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?= CSS ?>/icheck-bootstrap.min.css">
        <!-- JQVMap -->
        <link rel="stylesheet" href="<?= CSS ?>/jqvmap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= CSS ?>/adminlte.min.css">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="<?= CSS ?>/OverlayScrollbars.min.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="<?= CSS ?>/daterangepicker.css">
        <!--Color Picker-->|
        <link rel="stylesheet" href="<?= CSS ?>/bootstrap-colorpicker.min.css">
        <!-- summernote -->
        <!-- <link rel="stylesheet" href="<?= CSS ?>/css/summernote-bs4.min.css"> -->
        <!-- Select 2 -->
        <link rel="stylesheet" href="<?= CSS ?>/select2.min.css">
        <!-- SweetAlert 2 -->
        <link rel="stylesheet" href="<?= CSS ?>/sweetalert2.min.css">
        <!--Plugins -->
        <link rel="stylesheet" href="<?= PLUGINS ?>/noty/noty.css">
        <!-- DataTables-->
        <link rel="stylesheet" href="<?= CSS ?>/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="<?= CSS ?>/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="<?= CSS ?>/buttons.bootstrap4.min.css">
        <link rel="stylesheet" href="<?= CSS ?>/dataTables.checkboxes.css">
        <link rel="stylesheet" href="<?= CSS ?>/dataTables.dataTables.min.css">
        <!-- Read PDF 
        <link rel="stylesheet" type="text/css" href="<?= CSS ?>pdf_viewer.min.css">-->
        <!-- Bootstrap Toggle-->
        <link rel="stylesheet" type="text/css" href="<?= CSS ?>/bootstrap-switch.css">
        <!-- FullCalendar -->         
        <link rel="stylesheet" href="<?= CSS ?>/fullcalendar/main.css">
        <!-- Mis estilos -->
        <link rel="stylesheet" type="text/css" href="<?= ASSETS ?>/app/css/app.css">
    </head>
    <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed text-xs" data-sidebar-expand-on-hover="true">
        <div class="content" style="height: auto;">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="<?= base_url ?>" class="nav-link">Home</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto" style="height: auto;">
                <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell text-sm"></i>
                    <span id="tot_not" class="badge badge-danger navbar-badge"></span>
                </a>
                <div class="tot_notify" style="width:500 px"></div>
                </li>
                <li class="dropdown user user-menu open">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                        <img src="<?= $_SESSION['photo_user'] ?? ''; ?>" class="user-image" alt="User Image">
                        <span class="hidden-xs"> <?= $_SESSION['full_name'] ?? '' ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="<?= $_SESSION['photo_user'] ?? '' ?>" class="img-circle" alt="User Image">
                            <p><?= $_SESSION['full_name'] ?? ''; ?></p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-center">
                                <a href="<?= base_url ?>/ChangePassword" class="btn btn-default btn-flat" style="width: 100%;">Cambiar contraseña</a>
                            </div>
                            <div class="pull-center">
                                <a href="<?= base_url ?>/Logout" class="btn btn-default btn-flat" style="width: 100%;">Cerrar sesión</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
                    <i class="fas fa-th-large" title="Confgiuración personalizada"></i>
                </a>
            </ul>
        </nav>
        <?php
        require_once('Views/Alerta/index.php');
        require_once('Views/Templates/nav.php');
        ?>