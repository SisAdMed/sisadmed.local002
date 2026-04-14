<div class='card-body'>
    <input type='hidden' id='id' name='id' value='<?= $r->id ?? '' ?>'>
    <div class='row'>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for='fecha_vigenc'>Fecha <span>*</span></label>
            <input type="date" name="fecha_vigenc" id="fecha_vigenc" class="form-control text-xs">
        </div>
        <div class='form-group col-md-4 col-sm-4 col-xs-12'>
            <label for='desc_retiva'>Descripción <span>*</span></label>
            <input name='desc_retiva' id='desc_retiva' class='form-control text-xs'>
        </div>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for='tasa_retiva'>Tasa <span>*</span></label>
            <input type="number" name="tasa_retiva" id="tasa_retiva" class="form-control text-xs text-right" onkeypress="getChange($this)">
        </div>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for='min_retiva'>Mínimo <span>*</span></label>
            <input type="number" name="min_retiva" id="min_retiva" class="form-control text-xs text-right" onkeypress="getChange($this)">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-5 col-sm-5 col-xs-12">
            <label for="nom_ctb">Cuenta contable</label>
            <input type="hidden" name="id_ctb" id="id_ctb">
            <div class="input-group">
                <input type="text" class="form-control text-xs" id="nom_ctb" name="nom_ctb" readonly>
                <div class="input-group-append">
                        <span class="input-group-text nom_ctb"><a href="#" data-toggle="modal" data-target="#modal-CuentasCtb" title="Buscar y seleccionar Cuentas Contables"> <i class="fas fa-search text-xs"></i></a></span>
                </div>
            </div>
        </div>
        <div class="form-group col-md-5 col-sm-5 col-xs-12">
            <label for="nom_aux">Auxiliar contable</label>
            <input type="hidden" name="id_aux" id="id_aux">
            <div class="input-group">
                <input type="text" class="form-control text-xs id_aux" id="nom_aux" name="nom_aux" readonly >
                <div class="input-group-append">
                    <span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i id="div_aux" name="div_aux" class="fas fa-search"></i></a></span>
                </div>
            </div>
        </div>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for='status'>Status <span>*</span></label>
            <select name="status" id="status" class="form-control text-xs"></select>
        </div>
    </div>
</div>
<div class='loader'
    <img src='<?= IMG . '/ajax-loading.gif'  ?>' />
</div>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/Views/CuentasCtb/modal_CuentasCtb.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/Views/AuxiliarCtb/modal_AuxiliaresCtb.php';
?>