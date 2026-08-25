<div class="card-body">
    <input type="hidden" name="id" id="id" value="<?php echo $r->id_tmoinv  ?? '' ?>">
    <div class="row">
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label for="id_emp">Empresa <span class="required">*</span></label>
            <select autofocus class="form-control" name="id_emp" id="id_emp"></select>
        </div>
    </div>
    <div class="row"> 
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="cod_tmoinv">Código<span class="required">*</span></label>
            <input autofocus type="text" class="form-control" id="cod_tmoinv" name="cod_tmoinv" onkeyup="mayusculas(this);" >
            <span></span>
        </div>
        <div class="form-group col-md-6 col-sm-6 col-xs-12">
            <label for="nom__tmoinv">Nombre</label><span class="required">*</span>
            <input autofocus type="text" class="form-control" id="nom__tmoinv" name="nom__tmoinv" placeholder="Ingrese nombre" onkeyup="mayusculas(this);">
            <span></span>
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="tipo_tmoinv">Tipo</label><span class="required">*</span>
            <select class="form-control" name="tipo_tmoinv" id="tipo_tmoinv">
            </select>
            <span></span>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="tmosal_tmoinv">Tipo de Movimiento de Salida</label>
            <select class="form-control" id="tmosal_tmoinv" name="tmosal_tmoinv">
            </select>
            <span></span>
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="id_alm">Almacén</label>
            <select class="form-control" id="id_alm" name="id_alm">
            </select>
            <span></span>
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="consecutiv__tmoinv">Desea consecutivo?</label>
            <input type="checkbox" class="form-control" id="consecutiv__tmoinv" name="consecutiv__tmoinv">
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="proximo_tmoinv">N° próximo movimiento</label>
            <input type="number" class="form-control text-right" id="proximo_tmoinv" name="proximo_tmoinv" min="1" >
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6 col-sm-6 col-xs-12">
            <label for="nom_ctb">Cuenta contable</label>
            <input type="hidden" name="id_ctb" id="id_ctb">
            <div class="input-group">
                <input type="text" class="form-control text-xs" id="nom_ctb" name="nom_ctb" readonly>
                <div class="input-group-append">
                    <span class="input-group-text nom_ctb"><a href="#" data-toggle="modal" data-target="#modal-CuentasCtb" title="Buscar y seleccionar Cuentas Contables"> <i class="fas fa-search text-xs"></i></a></span>
                </div>
            </div>
        </div>
        <div class="form-group col-md-6 col-sm-6 col-xs-12">
            <label for="nom_aux">Auxiliar contable</label>
            <input type="hidden" name="id_aux" id="id_aux">
            <div class="input-group">
                <input type="text" class="form-control text-xs" id="nom_aux" name="nom_aux" readonly>
                <div class="input-group-append" id="div_aux">
                    <span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"> <i class="fas fa-search text-xs"></i></a></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="status">Estado<span class="required">*</span></label>
            <select class="form-control custom-select rounded-0" id="status" name="status">
            </select>
            <span></span>
        </div>
    </div>
</div>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/Views/CuentasCtb/modal_CuentasCtb.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/Views/AuxiliarCtb/modal_AuxiliaresCtb.php';
?>