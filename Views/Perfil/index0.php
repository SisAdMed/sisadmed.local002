    $diferencia = 0;
    $porcentual = 0;
    $tasa_o = 0;
    $tasa_p = 0;

    $tasa = curl_dolar_bcv();
    
    $fecha = $tasa['fecha'];
    $tasa_ofi = $tasa['bcv'];
    //
    $tasa_o = str_replace(",", ".", $tasa_ofi);
    
    //
    curl_dolar_par();
    $tasa_par = curl_dolar_par();
    $fecha_par = $tasa_par['fecha'];
    $tasa_par = $tasa_par['bcv'];
    //
    $tasa_p = str_replace(",", ".", $tasa_par);
    $tasa_p = str_replace('.', ',', number_format($tasa_p,4));

    //Diferncia entre tasas
    //
    $diferencia =floatval($tasa_p) - floatval($tasa_o);
    if(floatval($tasa_o) != 0){
        //$porcentual  = floatval($diferencia) / (floatval($tasa_o) ) * 100;
        $porcentual  =  (floatval($tasa_o) ) / floatval($tasa_p);
    }
    //
    $diferencia = str_replace('.', ',', number_format($diferencia,4));
    $porcentual = str_replace('.', ',', number_format($porcentual, 4)) . ' %';
?>
  <?php if($_SESSION['id_rol'] == 1) : ?>
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
        <?php if(isset($_SESSION['id_rol']) && ($_SESSION['id_rol'] == 1 || $_SESSION['id_rol'] == 8)) : ?>
            <section class="content"><div class="container-fluid">
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
                                    <p>Tasa del día Paralelo <?= $fecha_par ?? '' ?></p>
                                    <h3 class="text-right"><?= $tasa_p ?? '' ?></h3>
                                </div>
                                <a href="<?= base_url ?>/Cambios" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <p>Diferencia entre Tasas <?= $fecha_par ?? '' ?></p>
                                    <h3 class="text-right"><?= $diferencia ?? '' ?> / <?=  $porcentual  ?></h3>
                                </div>
                                <a href="<?= base_url ?>/Cambios" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
       <?php endif ?>
    </div>
<?php endif ?>