<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-products" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="product-basic-tab" data-toggle="pill" href="#product-basic" role="tab" aria-controls="product-basic" aria-selected="true">Producto<span class="error-star text-danger"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="product-label-tab" data-toggle="pill" href="#product-label" role="tab" aria-controls="product-label" aria-selected="true">Etiqueta<span class="error-star text-danger"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="product-photo-tab" data-toggle="pill" href="#product-photo" role="tab" aria-controls="product-photo" aria-selected="true">Fotos<span class="error-star text-danger"></span></a>
                    </li>
                    <?php if ($_SESSION['administrator'] == 1) : ?>
                        <li class="nav-item">
                            <a class="nav-link" id="product-more-tab" data-toggle="pill" href="#product-more" role="tab" aria-controls="product-more" aria-selected="true">Más información<span class="error-star text-danger"></span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="product-change-tab" data-toggle="pill" href="#product-change" role="tab" aria-controls="product-change" aria-selected="true">Historial<span class="error-star text-danger"></span></a>
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
            <input type="hidden" id="id" name="id" value="<?= $r->id_prod ?? '' ?>">
            <div class="row">
                <div class="form-group col-md-3 col-sm-3 col-xs-12">
                    <label for="cod_prod">Código de barra<span class="">*</span></label>
                    <input type="text" class="form-control text-xs" id="cod_prod" name="cod_prod" placeholder="Ingrese código">
                </div>
                <div class="form-group col-md-3 col-sm-3 col-xs-12">
                    <label for="cod2_prod">Código referencial<span class="">*</span></label>
                    <input type="text" class="form-control text-xs" id="cod2_prod" name="cod2_prod" placeholder="Ingrese código">
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                    <label for="nom_prod">Nombre</label><span class="">*</span>
                    <input type="text" class="form-control text-xs" id="nom_prod" name="nom_prod" placeholder="Ingrese nombre">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                    <label for="gen_prod">Nombre Genérico</label>
                    <input type="text" class="form-control text-xs" id="gen_prod" name="gen_prod" placeholder="Ingrese nombre genérico">
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                    <label for="ref_prod">Referencia</label>
                    <input type="text" class="form-control text-xs" id="ref_prod" name="ref_prod" placeholder="Indique Referencia">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_presen1">Empaque<span class="">*</span></label>
                    <select class="form-control text-xs custom-select rounded-0 select2 select2bs4" id="id_presen1" name="id_presen1"></select>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_presen2">Presentación x Empaq.<span class="">*</span></label>
                    <select class="form-control text-xs custom-select rounded-0 select2 select2bs4" id="id_presen2" name="id_presen2"></select>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_pre">Presentación Final<span class="">*</span></label>
                    <select class="form-control text-xs custom-select rounded-0 select2 select2bs4" id="id_pre" name="id_pre"></select>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_fab">Lab/Marca/Fab<span class="">*</span></label>
                    <select class="form-control text-xs custom-select rounded-0 select2 select2bs4" id="id_fab" name="id_fab"></select>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_grupo">Grupo</label>
                    <select class="form-control text-xs custom-select rounded-0 select2 select2bs4" id="id_grupo" name="id_grupo"></select>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_sub_grupo">Sub Grupo</label>
                    <select class="form-control text-xs custom-select rounded-0 select2 select2bs4" id="id_sub_grupo" name="id_sub_grupo"></select>
                </div>

            </div>
            <div class="row">
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="origen">Origen</label><span class="">*</span>
                    <select class="form-control text-xs custom-select rounded-0 select2 select2bs4" id="origen" name="origen"></select>
                </div>
                <div class="form-group  col-md-1 col-sm-1 col-xs-12 text-right">
                    <label for="alto">Alto</label>
                    <input type="text" class="form-control text-xs text-right camponumero" id="alto" name="alto">
                </div>
                <div class="form-group col-md-1 col-sm-1 col-xs-12 text-right">
                    <label for="ancho">Ancho</label>
                    <input type="text" class="form-control text-xs text-right camponumero" id="ancho" name="ancho">
                </div>
                <div class="form-group col-md-1 col-sm-1 col-xs-12 text-right">
                    <label for="largo">Largo</label>
                    <input type="text" class="form-control text-xs text-right camponumero" id="largo" name="largo">
                </div>
                <div class="form-group form-check col-md-2 col-sm-2 col-xs-12 text-center">
                    <label for="iva_prod">Usa IVA</label>
                    <input type="checkbox" class="form-control text-xs float-center" id="iva_prod" name="iva_prod" style="vertical-align: bottom;">
                </div>
                <div class="form-group form-check col-md-1 col-sm-1 col-xs-12 text-center">
                    <label for="lote_prod">Usa Lote?</label>
                    <input type="checkbox" class="form-control text-xs float-center" id="lote_prod" name="lote_prod" style="vertical-align: bottom;">
                </div>
                <div class="form-group form-check col-md-1 col-sm-1 col-xs-12 text-center">
                    <label for="interno_prod">Uso Interno?</label>
                    <input type="checkbox" class="form-control text-xs float-center" id="interno_prod" name="interno_prod" style="vertical-align: bottom;">
                </div>
                <div class="form-group form-check col-md-2 col-sm-1 col-xs-12 text-center">
                    <label for="door_prod">Door to Door?</label>
                    <input type="checkbox" class="form-control text-xs float-center" id="door_prod" name="door_prod" style="vertical-align: bottom;">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-1 col-sm-1 col-xs-12">
                    <label for="uni_com_prod">Uni. compras</label>
                    <input type="number" class="form-control text-xs text-right" id="uni_com_prod" name="uni_com_prod" min="1">
                </div>
                <div class="form-group col-md-1 col-sm-1 col-xs-12">
                    <label for="uni_ven_prod">Unidad ventas</label>
                    <input type="number" class="form-control text-xs text-right" id="uni_ven_prod" name="uni_ven_prod" min="1">
                </div>
                <div class="form-group col-md-1 col-sm-1 col-xs-12">
                    <label for="con_cons_prod">Unidad consig</label>
                    <input type="number" class="form-control text-xs text-right" id="con_cons_prod" name="con_cons_prod" min="1">
                </div>
                <div class="form-group col-md-1 col-sm-1 col-xs-12">
                    <label for="conv_prod_cons">Conver consig</label>
                    <input type="number" class="form-control text-xs text-right" id="conv_prod_cons" name="conv_prod_cons" min="1">
                </div>
                <?php if ($_SESSION['administrator'] == 1) : ?>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="costo_prod">Costo</label>
                        <input type="text" class="form-control text-xs text-right camponumero4 costo_prod" id="costo_prod" name="costo_prod" placeholder="Indique Costo">
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="flete_prod">Flete</label>
                        <input type="text" class="form-control text-xs text-right camponumero4 costo_prod" id="flete_prod" name="flete_prod" placeholder="Indique Flete">
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="otros_prod">Otros cargos</label>
                        <input type="text" class="form-control text-xs text-right camponumero4 costo_prod" id="otros_prod" name="otros_prod" placeholder="Indique Otros">
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="door_costo">Cargos Door to Door</label>
                        <input type="text" class="form-control text-xs text-right camponumero4 costo_prod" id="door_costo" name="door_costo" placeholder="Indique Costo Door to Door">
                    </div>
                <?php endif ?>
            </div>
            <div class="row">
                <?php if ($_SESSION['administrator'] == 1) : ?>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="costo1">Costo 1</label>
                        <input type="text" class="form-control text-xs text-right camponumero4" id="costo1" name="costo1" readonly>
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="recar_prod">% Utilidad</label>
                        <input type="text" class="form-control text-xs text-right camponumero4" id="recar_prod" name="recar_prod" placeholder="Indique recargo">
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="ventas_prod">Venta</label>
                        <input type="text" class="form-control text-xs text-right camponumero4" id="ventas_prod" name="ventas_prod" placeholder="Indique Venta">
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="recar2_prod">% Utilidad Consig.</label>
                        <input type="text" class="form-control text-xs text-right camponumero4" id="recar2_prod" name="recar2_prod" placeholder="Indique utilidad de consignacón">
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="venta2_prod">Venta Consignación</label>
                        <input type="text" class="form-control text-xs text-right camponumero4" id="venta2_prod" name="venta2_prod" placeholder="Indique Venta">
                    </div>
                <?php endif ?>
                <div class="form-group col-md-1 col-sm-1 col-xs-12">
                    <label for="stock_minimo">Stock mínimo</label>
                    <input type="number" class="form-control text-xs text-right" name="stock_minimo" id="stock_minimo">
                </div>
                <div class="form-group col-md-1 col-sm-1 col-xs-12">
                    <label for="stock">Stock</label>
                    <input type="text" class="form-control text-xs text-right" name="stock" id="stock" disabled>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_fab_fac">Lab/Marca/Fab para Facturación</label>
                    <select class="form-control text-xs custom-select rounded-0 select2 select2bs4" id="id_fab_fac" name="id_fab_fac"></select>
                </div>

                <div class="form-group col-md-2 col-sm-2 col-xs-12 creado_por">
                    <label for="creado_por">Creado por:</label>
                    <input type="text" class="form-control text-xs" name="creado_por" id="creado_por" disabled>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12 creado_por">
                    <label for="create_date">Creado el:</label>
                    <input type="text" class="form-control text-xs" name="create_date" id="create_date" disabled>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12 modificado_por">
                    <label for="modificado_por">Modificado por:</label>
                    <input type="text" class="form-control text-xs" name="modificado_por" id="modificado_por" disabled>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12 modificado_por">
                    <label for="modify_date">Modificado el:</label>
                    <input type="text" class="form-control text-xs" name="modify_date" id="modify_date" disabled>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="status">Estado<span class="">*</span></label>
                    <select class="form-control text-xs custom-select rounded-0" id="status" name="status"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label for="des_prod">Descripción del producto</label>
                    <textarea class="form-control text-xs" name="des_prod" id="des_prod" cols="30" rows="5"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="product-label" role="tabpanel" aria-labelledby="product-label-tab">
            <div class="row">
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="nomcor_prod">Nombre corto</label>
                    <input type="text" class="form-control text-xs" id="nomcor_prod" name="nomcor_prod" placeholder="Indique nombre corto">
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="marcom_prod">Marca comercial</label>
                    <input type="text" class="form-control text-xs" id="marcom_prod" name="marcom_prod" placeholder="Indique nombre comercial">
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="fabpor_prod">Fabricado por</label>
                    <input type="text" class="form-control text-xs" id="fabpor_prod" name="fabpor_prod" placeholder="Indique Fabricado por">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="cpe_prod">CPE</label>
                    <input type="text" class="form-control text-xs" id="cpe_prod" name="cpe_prod" placeholder="Indique CPE">
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="connetpro_prod">Cantidad neto producto</label>
                    <input type="text" class="form-control text-xs" id="connetpro_prod" name="connetpro_prod" placeholder="Indique Cantidad neta producto">
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="connetcaj_prod">Cantidad neto caja</label>
                    <input type="text" class="form-control text-xs" id="connetcaj_prod" name="connetcaj_prod" placeholder="Indique Cantidad neta producto">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="regsan_prod">Registro sanitario</label>
                    <input type="text" class="form-control text-xs" id="regsan_prod" name="regsan_prod" placeholder="Indique Registro sanitario">
                </div>
                <div class="form-group col-md-8 col-sm-8 col-xs-12">
                    <label for="uso_prod">Uso producto</label>
                    <input type="text" class="form-control text-xs" id="uso_prod" name="uso_prod" placeholder="Indique Uso del producto">
                </div>
            </div>
            <div class="row">
                <input type="button" id="btn_print" class="btn btn-primary btn-xs" value="Imprimir etiqueta" />
            </div>
        </div>
        <div class="tab-pane fade" id="product-photo" role="tabpanel" aria-labelledby="product-photo-tab">
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" name="imgSel" id="imgSel">
                <div class="row">
                    <div class="col-lg-4">
                        <h1 class="text-primary">Subir imagen</h1>
                        <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="url_photo">Seleccione una imagen</label>
                                <input type='file' multiple accept="image/*" name="url_photo[]" id="url_photo" class="form-control text-xs" onchange="showImageHereFunc(event, 'url_photo', 'imgPreview')">
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-8">
                        <h1 class="text-primary text-center">Galería de imagenes</h1>
                        <hr>
                        <div id="imgPreview">
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
                        <textarea class="form-control text-xs" name="commet_prod" id="commet_prod" cols="30" rows="5"></textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pnae fade" id="product-change" role="tabpanel" aria-labelledby="product-change-tab">
                <div class="row">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <table id="tblTableHis" class="display responsive nowrap table table-hover" style="width:100%">
                        </table>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>