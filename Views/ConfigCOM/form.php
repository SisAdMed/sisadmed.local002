<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="module-configfac" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="module-configfac-general-tab" data-toggle="pill" href="#module-configfac-general" role="tab" aria-controls="module-configfac-general" aria-selected="true">Configuración</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="module-values-tab" data-toggle="pill" href="#module-values" role="tab" aria-controls="module-values" aria-selected="true">Valores Predeterminados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="module-inventory-tab" data-toggle="pill" href="#module-inventory" role="tab" aria-controls="module-inventory" aria-selected="true">Inventario</a>
                    </li> 
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="card-body">
    <div class="tab-content" id="module-confifac-general-tabcontent">
        <input type="hidden" name="id" id="id" value="<?= $r->id_comcfg ?? '' ?>">
        <div class="tab-pane fade show active" id="module-configfac-general" role="tabpanel" aria-labelledby="module-configfac-tab">
            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-xs-12">
                    <label for="id_emp">Empresa</label>
                    <select autofocus name="id_emp" id="id_emp" class="form-control text-xs"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group-col-sm-4 col-md-4 col-xs-12">
                    <label for="con_purcon">Concepto de Compras</label>
                    <select autofocus name="con_purcon" id="con_purcon" class="form-control select2 select2bs4 text-xs"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-2 col-md-2 col-xs-12">
                    <label for="status">Status</label>
                    <select autofocus name="status" id="status" class="form-control text-xs"></select>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="module-values" role="tabpanel" aria-labelledby="module-values-tab">
            <div class="row">
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="tdoc_pur">Tipo de Documento para Factura</label>
                    <select autofocus name="tdoc_pur" id="tdoc_pur" class="form-control text-xs"></select>
                </div>
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="tdoc_purcrenot">Tipo de Documento para Nota de Crédito</label>
                    <select autofocus name="tdoc_purcrenot" id="tdoc_purcrenot" class="form-control text-xs"></select>
                </div>
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="tdoc_purord">Tipo de Documento para Orden de Compra</label>
                    <select autofocus name="tdoc_purord" id="tdoc_purord" class="form-control text-xs"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="tdoc_purdelnot">Tipo de Documento para Nota de Entrega</label>
                    <select autofocus name="tdoc_purdelnot" id="tdoc_purdelnot" class="form-control text-xs"></select>
                </div>
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="tdoc_purretnot">Tipo de Documento para Nota de Devolución</label>
                    <select autofocus name="tdoc_purretnot" id="tdoc_purretnot" class="form-control text-xs"></select>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="module-inventory" role="tabpanel" aria-labelledby="module-inventory-tab">
            <div class="row">
                <div class="form-group col-sm-3 col-md-3 col-xs-12">
                    <label for="tmov_pur">Tipo de Movimiento de Entrada</label>
                    <select autofocus name="tmov_pur" id="tmov_pur" class="form-control text-xs"></select>
                </div>
                <div class="form-group col-sm-3 col-md-3 col-xs-12">
                    <label for="tmov_pur_sal">Tipo de Movimiento de Salida</label>
                    <select autofocus name="tmov_pur_sal" id="tmov_pur_sal" class="form-control text-xs"></select>
                </div>
                <div class="form-group col-sm-3 col-md-3 col-xs-12">
                    <label for="id_alm">Almacén</label>
                    <select autofocus name="id_alm" id="id_alm" class="form-control text-xs"></select>
                </div>
                <div class="form-group col-sm-3 col-md-3 col-xs-12">
                    <label for="id_ubi">Ubicación</label>
               <input type="hidden" id="id_ubi" name="id_ubi">
               <div class="input-group">
                    <input type="text" class="form-control text-xs" id="nom_ubi" name="nom_ubi" readonly>
                    <div class="input-group-append text-xs">
                        <span class="input-group-text  text-xs"><a href="#" data-toggle="modal" data-target="#modal-ubicaciones" title=" Buscar y seleccionar Ubicaciones"><i class="fas fa-search text-xs"></i></a></span>
                    </div>
                    </div>
                </div>
        </div>
    </div>
</div>
<!-- Modales requeridos -->
<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/Views/Ubicaciones/modal_Ubicaciones.php';
?>