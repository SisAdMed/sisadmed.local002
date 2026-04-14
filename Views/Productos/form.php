<!--Validar estado del consecutivo-->
<?php

$valor = false;



if (isset($data['valor']) && $data['valor'] == 'N') {
    $valor = true;
}
if (isset($r)) {
    $id_prod = $r->id_prod;
    $uni_ven_prod = $r->uni_ven_prod;
}


$marcadoiva = 'unchecked';
$marcadolot = 'unchecked';
$marcadouso = 'unchecked';
$marcadodoor = 'unchecked';
$visible = 'disabled';

$ventas_prod = 0;
$recar_prod = 0;
$otros_prod = 0;
$flete_prod = 0;
$costo_prod = 0;
$costo1 = 0;
$recar2_prod = 0;
$venta2_prod = 0;
$door_costo = 0;

if (isset($r)) {
    if ($r->iva_prod == 1) {
        $marcadoiva = "checked";
    }
    if ($r->lote_prod == 1) {
        $marcadolot = "checked";
    }
    if ($r->interno_prod == 1) {
        $marcadouso = "checked";
    }
    if ($r->door_prod == 1) {
        $marcadodoor  = "checked";
    }

    //Montos formateados
    $costo_prod = $r->costo_prod;
    $flete_prod = $r->flete_prod;
    $otros_prod = $r->otros_prod;
    $door_costo = $r->door_costo;
    $costo1 = ($r->costo_prod + $r->flete_prod + $r->otros_prod);
    $recar_prod = $r->recar_prod;
    $ventas_prod = $r->ventas_prod;
    $recar2_prod = $r->recar2_prod;
    $venta2_prod = $r->venta2_prod;

    if ($door_costo > 0) {
        $marcadodoor = "checked";
    }
    if ($r->door_prod == 1) {
        $costo1 = $costo_prod + $flete_prod + $otros_prod + $door_costo;
    }
    if ($valor) {
        $costo_prod = $costo_prod / $uni_ven_prod;
        $flete_prod = $flete_prod / $uni_ven_prod;
        $otros_prod = $otros_prod / $uni_ven_prod;
        $door_costo = $door_costo / $uni_ven_prod;
        $costo1 = ($costo_prod + $flete_prod + $otros_prod + $door_costo);
        $ventas_prod = $costo1 / $recar_prod;

        $uni_ven_prod = 1;

        $id_prod = '';
    }
}
?>
<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-products" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="product-basic-tab" data-toggle="pill" href="#product-basic" role="tab" aria-controls="product-basic" aria-selected="true">Producto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="product-label-tab" data-toggle="pill" href="#product-label" role="tab" aria-controls="product-label" aria-selected="true">Etiqueta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="product-photo-tab" data-toggle="pill" href="#product-photo" role="tab" aria-controls="product-photo" aria-selected="true">Fotos</a>
                    </li>
                    <?php if ($_SESSION['administrator'] == 1) : ?>
                        <li class="nav-item">
                            <a class="nav-link" id="product-more-tab" data-toggle="pill" href="#product-more" role="tab" aria-controls="product-more" aria-selected="true">Más información</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="product-change-tab" data-toggle="pill" href="#product-change" role="tab" aria-controls="product-change" aria-selected="true">Historial</a>
                        </li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card-body">
    <div class="tab-content" id="custom-products-tabcontent">
        <div class="tab-pane fade show active" id="product-basic" role="tabpanel" aria-labelledby="product-basic-tab">
            <input type="hidden" id="id" name="id" value="<?php echo $id_prod  ?? '' ?>">
            <div class="row">
                <div class="form-group col-md-3 col-sm-3 col-xs-12">
                    <label for="cod_prod">Código de barra<span class="required">*</span></label>
                    <input autofocus type="text" class="form-control" id="cod_prod" name="cod_prod" placeholder="Ingrese código" required value="<?php echo $r->cod_prod  ?? '' ?>" onkeyup="mayusculas(this);">
                    <span></span>
                </div>
                <div class="form-group col-md-3 col-sm-3 col-xs-12">
                    <label for="cod2_prod">Código referencial<span class="required">*</span></label>
                    <input autofocus type="text" class="form-control" id="cod2_prod" name="cod2_prod" placeholder="Ingrese código" required value="<?php echo $r->cod2_prod  ?? '' ?>" onkeyup="mayusculas(this);">
                    <span></span>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                    <label for="nom_prod">Nombre</label><span class="required">*</span>
                    <input autofocus type="text" class="form-control" id="nom_prod" name="nom_prod" placeholder="Ingrese nombre" required value="<?php echo $r->nom_prod  ?? '' ?>" onblur="mayusculas(this);" maxlength="255">
                    <span></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                    <label for="gen_prod">Nombre Genérico</label>
                    <input autofocus type="text" class="form-control" id="gen_prod" name="gen_prod" placeholder="Ingrese nombre genérico" value="<?php echo $r->gen_prod  ?? '' ?>" onkeyup="mayusculas(this);">
                    <span></span>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                    <label for="ref_prod">Referencia</label>
                    <input autofocus type="text" class="form-control" id="ref_prod" name="ref_prod" placeholder="Indique Referencia" value="<?php echo $r->ref_prod ?? '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_presen1">Empaque<span class="required">*</span></label>
                    <select autofocus class="form-control custom-select rounded-0 select2 select2bs4" id="id_presen1" name="id_presen1" required>
                        <?php
                        SelPresentacion($r->id_presen1 ?? '');
                        ?>
                    </select>
                    <span></span>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_presen2">Presentación x Empaq.<span class="required">*</span></label>
                    <select autofocus class="form-control custom-select rounded-0 select2 select2bs4" id="id_presen2" name="id_presen2" required>
                        <?php
                        SelPresentacion($r->id_presen2 ?? '');
                        ?>
                    </select>
                    <span></span>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_pre">Presentación Final<span class="required">*</span></label>
                    <select autofocus class="form-control custom-select rounded-0 select2 select2bs4" id="id_pre" name="id_pre" required>
                        <?php
                        SelPresentacion($r->id_pre ?? '');
                        ?>
                    </select>
                    <span></span>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_fab">Lab/Marca/Fab<span class="required">*</span></label>
                    <select autofocus class="form-control custom-select rounded-0 select2 select2bs4" id="id_fab" name="id_fab" required>
                        <?php
                        SelFabricante($r->id_fab ?? '');
                        ?>
                    </select>
                    <span></span>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_grupo">Grupo</label>
                    <select autofocus class="form-control custom-select rounded-0 select2 select2bs4" id="id_grupo" name="id_grupo">
                        <?php
                        SelGrupo($r->id_grupo ?? '');
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_sub_grupo">Sub Grupo</label>
                    <select autofocus class="form-control custom-select rounded-0 select2 select2bs4" id="id_sub_grupo" name="id_sub_grupo">
                        <?php
                        SelSubGrupo($r->id_sub_grupo ?? '');
                        ?>
                    </select>
                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="origen">Origen</label><span class="required">*</span>
                    <select autofocus class="form-control custom-select rounded-0 select2 select2bs4" id="origen" name="origen">
                        <?php
                        SelOrigen($r->origen ?? '');
                        ?>
                    </select>
                </div>
                <div class="form-group  col-md-1 col-sm-1 col-xs-12 text-right">
                    <label for="alto">Alto</label>
                    <input autofocus type="number" class="form-control text-right dimension" id="alto" name="alto" value="<?php echo $r->alto ?? '0' ?>">
                </div>
                <div class="form-group col-md-1 col-sm-1 col-xs-12 text-right">
                    <label for="ancho">Ancho</label>
                    <input autofocus type="number" class="form-control text-right dimension" id="ancho" name="ancho" value="<?php echo $r->ancho ?? '0' ?>">
                </div>
                <div class="form-group col-md-1 col-sm-1 col-xs-12 text-right">
                    <label for="largo">Largo</label>
                    <input autofocus type="number" class="form-control text-right dimension" id="largo" name="largo" value="<?php echo $r->largo ?? '0' ?>">
                </div>
                <div class="form-group form-check col-md-2 col-sm-2 col-xs-12 text-center">
                    <label for="iva_prod">Usa IVA</label>
                    <input autofocus type="checkbox" class="form-control float-center" id="iva_prod" name="iva_prod" style="vertical-align: bottom;" <?php echo $marcadoiva ?>>
                </div>
                <div class="form-group form-check col-md-1 col-sm-1 col-xs-12 text-center">
                    <label for="lote_prod">Usa Lote?</label>
                    <input autofocus type="checkbox" class="form-control float-left" id="lote_prod" name="lote_prod" style="vertical-align: bottom;" <?php echo $marcadolot ?>>
                </div>
                <div class="form-group form-check col-md-1 col-sm-1 col-xs-12 text-center">
                    <label for="interno_prod">Uso Interno?</label>
                    <input autofocus type="checkbox" class="form-control float-left" id="interno_prod" name="interno_prod" style="vertical-align: bottom;" <?php echo $marcadouso ?>>
                </div>
                <div class="form-group form-check col-md-2 col-sm-2 col-xs-12 text-center">
                    <label for="door_prod">Door to Door?</label>
                    <input autofocus type="checkbox" class="form-control float-left door_prod" id="door_prod" name="door_prod" style="vertical-align: bottom;" <?php echo $marcadodoor ?>>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-1 col-sm-1 col-xs-12">
                    <label for="uni_com_prod">Uni. compras</label>
                    <input autofocus type="number" class="form-control text-right" id="uni_com_prod" name="uni_com_prod" min="1" value="<?php echo $r->uni_com_prod ?? '' ?>">
                </div>
                <div class="form-group col-md-1 col-sm-1 col-xs-12">
                    <label for="uni_ven_prod">Unidad ventas</label>
                    <input autofocus type="number" class="form-control text-right" id="uni_ven_prod" name="uni_ven_prod" min="1" value="<?php echo $uni_ven_prod ?? '' ?>">
                </div>
                <div class="form-group col-md-1 col-sm-1 col-xs-12">
                    <label for="con_cons_prod">Unidad consig</label>
                    <input autofocus type="number" class="form-control text-right" id="con_cons_prod" name="con_cons_prod" min="1" value="<?php echo $r->con_cons_prod ?? '' ?>">
                </div>
                <div class="form-group col-md-1 col-sm-1 col-xs-12">
                    <label for="conv_prod_cons">Conver consig</label>
                    <input autofocus type="number" class="form-control text-right" id="conv_prod_cons" name="conv_prod_cons" min="1" value="<?php echo $r->conv_prod_cons ?? '1' ?>">
                </div>
                <?php if ($_SESSION['administrator'] == 1) : ?>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="costo_prod">Costo</label>
                        <input autofocus type="text" class="form-control text-right mask" id="costo_prod" name="costo_prod" value="<?php echo number_format($costo_prod, 4) ?? '' ?>" placeholder="Indique Costo" onpaste="return false" onkeyup="updateCost1()" onblur="check(this)" required>
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="flete_prod">Flete</label>
                        <input autofocus type="text" class="form-control text-right" id="flete_prod" name="flete_prod" value="<?php echo number_format($flete_prod, 4) ?? '' ?>" placeholder="Indique Flete" onpaste="return false" onkeyup="updateCost1()" onblur="check(this)" required>
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="otros_prod">Otros cargos</label>
                        <input autofocus type="text" class="form-control text-right" id="otros_prod" name="otros_prod" value="<?php echo number_format($otros_prod, 4) ?? '' ?>" placeholder="Indique Otros" onpaste="return false" onkeydown="updateCost1()" onkeyup="updateCost1()" onblur="check(this)" min="0" required>
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="door_costo">Cargos Door to Door</label>
                        <input autofocus type="text" class="form-control text-right" id="door_costo" name="door_costo" value="<?php echo number_format($door_costo, 4) ?? '' ?>" placeholder="Indique Costo Door to Door" onpaste="return false" onkeydown="updateCost1()" onkeyup="updateCost1()" onblur="check(this)" min="0" required>
                    </div>
                <?php endif ?>
            </div>
            <div class="row">
                <?php if ($_SESSION['administrator'] == 1) : ?>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="costo1">Costo 1</label>
                        <input autofocus type="text" class="form-control text-right" id="costo1" name="costo1" value="<?php echo number_format($costo1, 4) ?>" disabled onblur="check(this)">
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="recar_prod">% Utilidad</label>
                        <input autofocus type="text" class="form-control text-right" id="recar_prod" name="recar_prod" value="<?php echo number_format($recar_prod ?? '0', 4) ?>" placeholder="Indique recargo" onblur="check(this)" onpaste="return false" onkeyup="rechargeSale(1)">
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="ventas_prod">Venta</label>
                        <input autofocus type="text" class="form-control text-right" id="ventas_prod" name="ventas_prod" value="<?php echo number_format($ventas_prod ?? '0', 4) ?>" placeholder="Indique Venta" onblur="check(this)" onpaste="return false" onkeypress="rechargeSale(2)">
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="recar2_prod">% Utilidad Consig.</label>
                        <input autofocus type="text" class="form-control text-right" id="recar2_prod" name="recar2_prod" value="<?php echo number_format($recar2_prod ?? '0', 4) ?>" placeholder="Indique utilidad de consignacón" onblur="check(this)" onpaste="return false" onkeyup="rechargeSale(3)">
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="venta2_prod">Venta Consignación</label>
                        <input autofocus type="text" class="form-control text-right" id="venta2_prod" name="venta2_prod" value="<?php echo number_format($venta2_prod ?? '0', 4) ?>" placeholder="Indique Venta" onblur="check(this)" onpaste="return false" onkeypress="rechargeSale(4)">
                    </div>
                <?php endif ?>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="status">Estado<span class="required">*</span></label>
                    <select autofocus class="form-control custom-select rounded-0" id="status" name="status" required>
                        <?php
                        status($r->status ?? '');
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="stock_minimo">Stock mínimo</label>
                    <input autofocus type="number" class="form-control text-right" name="stock_minimo" id="stock_minimo" value="<?php echo $r->stock_minimo ?? '' ?>">
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="stock">Stock</label>
                    <input autofocus type="number" class="form-control text-right" name="stock" id="stock" value="<?php echo $r->stock ?? '' ?>" disabled>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_fab_fac">Lab/Marca/Fab para Facturación</label>
                    <select autofocus class="form-control custom-select rounded-0 select2 select2bs4" id="id_fab_fac" name="id_fab_fac" required>
                        <?php
                        SelFabricante($r->id_fab_fac ?? '');
                        ?>
                    </select>
                    <span></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label for="des_prod">Descripción del producto</label>
                    <textarea class="form-control" name="des_prod" id="des_prod" cols="30" rows="5"><?php echo $r->des_prod ?? '' ?></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="product-label" role="tabpanel" aria-labelledby="product-label-tab">
            <input type="hidden" name="id" value="<?php echo $id_prod  ?? '' ?>">
            <div class="row">
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="nomcor_prod">Nombre corto</label>
                    <input autofocus type="text" class="form-control" id="nomcor_prod" name="nomcor_prod" value="<?php echo $r->nomcor_prod ?? '' ?>" placeholder="Indique nombre corto">
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="marcom_prod">Marca comercial</label>
                    <input autofocus type="text" class="form-control" id="marcom_prod" name="marcom_prod" value="<?php echo $r->marcom_prod ?? '' ?>" placeholder="Indique nombre comercial">
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="fabpor_prod">Fabricado por</label>
                    <input autofocus type="text" class="form-control" id="fabpor_prod" name="fabpor_prod" value="<?php echo $r->fabpor_prod ?? '' ?>" placeholder="Indique Fabricado por">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="cpe_prod">CPE</label>
                    <input autofocus type="text" class="form-control" id="cpe_prod" name="cpe_prod" value="<?php echo $r->cpe_prod ?? '' ?>" placeholder="Indique CPE">
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="connetpro_prod">Cantidad neto producto</label>
                    <input autofocus type="text" class="form-control" id="connetpro_prod" name="connetpro_prod" value="<?php echo $r->connetpro_prod ?? '' ?>" placeholder="Indique Cantidad neta producto">
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="connetcaj_prod">Cantidad neto caja</label>
                    <input autofocus type="text" class="form-control" id="connetcaj_prod" name="connetcaj_prod" value="<?php echo $r->connetcaj_prod ?? '' ?>" placeholder="Indique Cantidad neta producto">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="regsan_prod">Registro sanitario</label>
                    <input autofocus type="text" class="form-control" id="regsan_prod" name="regsan_prod" value="<?php echo $r->regsan_prod ?? '' ?>" placeholder="Indique Registro sanitario">
                </div>
                <div class="form-group col-md-8 col-sm-8 col-xs-12">
                    <label for="uso_prod">Uso producto</label>
                    <input autofocus type="text" class="form-control" id="uso_prod" name="uso_prod" value="<?php echo $r->uso_prod ?? '' ?>" placeholder="Indique Uso del producto">
                </div>
            </div>
            <div class="row">
                <input type="button" id="btn_print" class="btn btn-primary" value="Imprimir etiqueta" />
            </div>
        </div>
        <div class="tab-pane fade" id="product-photo" role="tabpanel" aria-labelledby="product-photo-tab">
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" name="imgSel" id="imgSel">
                <div class="row">
                    <div class="col-lg-4">
                        <h1 class="text-primary">Subir imagen</h1>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="url_photo">Seleccione una imagen</label>
                                <input type='file' multiple accept="image/*" name="url_photo[]" id="url_photo" class="form-control" onchange="showImageHereFunc(event, 'url_photo', 'imgPreview')">
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-8">
                        <h1 class="text-primary text-center">Galería de imagenes</h1>
                        <hr>
                        <div id="imgPreview">
                            <?php if (!empty($id_prod)) : ?>
                                <?php $showImg = ProductosModel::showImg($id_prod);
                                $i = 0; ?>
                                <?php if ($showImg) : ?>
                                    <?php foreach ($showImg as $value) : ?>
                                        <div class="card" id="cardimg<?= $i++ ?>" style="display:inline-block;">
                                            <img id="img<?= $i ?>" name="img<?= $i ?>" width="200px" height="200px" src="<?= base_url . $value['url_photo'] ?>" title="<?= $value['filename'] ?>">
                                            <button id="detimg" data-id="<?= $value['id_photo'] ?>" data-name="<?= base_url . $value['url_photo'] ?>" data-code="<?= IMAGE_PATH . 'products' . DS . $value['filename'] ?>" type="button" title="Eliminar imagén" class="btn btn-danger" onclick="deleteimg(this)"><i class="fa-sharp fa-solid fa-xmark"></i></i></button>
                                        </div>
                                    <?php endforeach ?>
                                <?php endif ?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php if ($_SESSION['administrator'] == 1) : ?>
            <div class="tab-pane fade" id="product-more" role="tabpanel" aria-labelledby="product-more-tab">
                <div class="row">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label for="commet_prod">Comentario adicional</label>
                        <textarea class="form-control" name="commet_prod" id="commet_prod" cols="30" rows="5"><?php echo $r->commet_prod ?? '' ?></textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pnae fade" id="product-change" role="tabpanel" aria-labelledby="product-change-tab">
                <div class="row">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <table id="tblTableHis" class="display responsive nowrap table table-hover" style="width:100%">
                            <thead>
                                <th>Id</th>
                                <th>fecha</th>
                                <th class="text-right">Costo</th>
                                <th class="text-right">Flete</th>
                                <th class="text-right">Otros</th>
                                <th class="text-right">Door</th>
                                <th class="text-right">Costo 1</th>
                                <th class="text-right">Utilidad 1</th>
                                <th class="text-right">Ventas 1</th>
                                <th class="text-right">Utilidad 2</th>
                                <th class="text-right">Ventas 2</th>
                            </thead>
                            <tfoot>
                                <th>Id</th>
                                <th>fecha</th>
                                <th class="text-right">Costo</th>
                                <th class="text-right">Flete</th>
                                <th class="text-right">Otros</th>
                                <th class="text-right">Door</th>
                                <th class="text-right">Costo 1</th>
                                <th class="text-right">Utilidad 1</th>
                                <th class="text-right">Ventas 1</th>
                                <th class="text-right">Utilidad 2</th>
                                <th class="text-right">Ventas 2</th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>