<?php headerAdmin($data);
$diferencia = 0;
$porcentual = 0;
$tasa_o = 0;
$tasa = "";
$tasa_p = 0;
$tasa_ofi = 0;


$tasa = curl_dolar_bcv();
if($tasa){
    $fecha = $tasa['fecha'];
    $tasa_ofi = $tasa['bcv'];

    $tasa_o = str_replace(",", ".", $tasa_ofi);
}else{
    Alertas::new('No se pudo obtener la tasa oficial del día', 'warning');
    
}


//

$tasa_par = curl_dolar_par();

if($tasa_par){
    $fecha_par = $tasa_par['fecha'];
    $tasa_par = $tasa_par['bcv'];

    $tasa_p = str_replace(",", ".", $tasa_par);
    $tasa_p = str_replace('.', ',', number_format($tasa_p, 4));
}


//Diferncia entre tasas
//
$diferencia = floatval($tasa_p) - floatval($tasa_o);
if (floatval($tasa_o) != 0) {
    $porcentual  = floatval($diferencia) / (floatval($tasa_o)) * 100;
}
//
$diferencia = str_replace('.', ',', number_format($diferencia, 4));
$porcentual = str_replace('.', ',', number_format($porcentual, 2)) . ' %';
?>
<?php if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] != 1) : ?>
    <div class="content-wrapper iframe-mode" data-widget="iframe" data-loading-screen="750">
        <div class="nav navbar navbar-expand navbar-white navbar-light border-bottom p-0">
            <div class="nav-item dropdown">
                <a class="nav-link bg-danger dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Close</a>
                <div class="dropdown-menu mt-0">
                    <a class="dropdown-item" href="#" data-widget="iframe-close" data-type="all">Close All</a>
                    <a class="dropdown-item" href="#" data-widget="iframe-close" data-type="all-other">Close All Other</a>
                </div>
            </div>
            <a class="nav-link bg-light" href="#" data-widget="iframe-scrollleft"><i class="fas fa-angle-double-left"></i></a>
            <ul class="navbar-nav overflow-hidden" role="tablist"></ul>
            <a class="nav-link bg-light" href="#" data-widget="iframe-scrollright"><i class="fas fa-angle-double-right"></i></a>
            <a class="nav-link bg-light" href="#" data-widget="iframe-fullscreen"><i class="fas fa-expand"></i></a>
        </div>
        <div class="tab-content">
            <div class="tab-empty">
                <h2 class="display-4"></h2>
            </div>
            <div class="tab-loading">
                <div>
                    <h2 class="display-4">La aplicación se esta cargando... <i class="fa fa-sync fa-spin"></i></h2>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1><b>Bienvenidos al <?= SITE_DESC ?><b></h1>
                    </div>
                </div>
            </div>
        </section>
        <?php if ($_SESSION['administrator'] == 1) : ?>
            <section class="content">
                <div class="container-fluid">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h3 id="tot_user" name="tot_user"></h3>
                                        <p>Usuarios registrados</p>
                                    </div>
                                    <a href="<?php base_url ?>/Usuarios/nuevo">
                                        <div class="icon">
                                            <i class="fa-solid fa-square-plus" title="Agregar usuario"></i>
                                        </div>
                                    </a>
                                    <a href="<?= base_url ?>/Usuarios" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3 id="tot_prod" name="tot_prod"></h3>
                                        <p>Productos registrados</p>
                                    </div>
                                    <a href="<?php base_url ?>/Productos/nuevo">
                                        <div class="icon">
                                            <i class="fa-solid fa-square-plus" title="Agregar producto"></i>
                                        </div>
                                    </a>
                                    <a href="<?= base_url ?>/Productos" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3 id="tot_cli" name="tot_cli"></h3>
                                        <p>Clientes registrados</p>
                                    </div>
                                    <a href="<?php base_url ?>/Clientes/nuevo">
                                        <div class="icon">
                                            <i class="fa-solid fa-square-plus" title="Agregar cliente"></i>
                                        </div>
                                    </a>
                                    <a href="<?= base_url ?>/Clientes" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <p>Tasa del día Oficial <?= $fecha ?? '' ?></p>
                                        <h3 class="text-right"><?= $tasa_ofi ?? '' ?></h3>
                                    </div>
                                    <a href="<?= base_url ?>/Cambios" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <p>Tasa del día Alterno <?= $fecha_par ?? '' ?></p>
                                        <h3 class="text-right"><?= $tasa_p ?? '' ?></h3>
                                    </div>
                                    <a href="<?= base_url ?>/Cambios" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <p>Diferencia entre Tasas <?= $fecha_par ?? '' ?></p>
                                        <h3 class="text-right"><?= $diferencia ?? '' ?> / <?= $porcentual  ?></h3>
                                    </div>
                                    <a href="<?= base_url ?>/Cambios" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        <?php endif ?>
    </div>
<?php endif; ?>
<?php footerAdmin($data); ?>