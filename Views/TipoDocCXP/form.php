<div class="card-body">
    <input type="hidden" name="id" id="id" value="<?php echo $r->id_tdoc  ?? '' ?>">
    <div class="row">
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label for="id_emp">Empresa <span class="required">*</span></label>
            <select name="id_emp" id="id_emp" class="form-control text-xs"></select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-1 col-sm-1 col-xs-12">
            <label for="tipo_codigo">Código<span class="required">*</span></label>
            <input type="text" class="form-control text-xs mayusculas" id="tipo_codigo" name="tipo_codigo" placeholder="Ingrese código">
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="nom_tdoc">Nombre</label><span class="required">*</span>
            <input type="text" class="form-control text-xs mayusculas" id="nom_tdoc" name="nom_tdoc" placeholder="Ingrese nombre">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="tipo_tdoc">Tipo de Documento<span class="required">*</span></label>
            <select name="tipo_tdoc" id="tipo_tdoc" class="form-control text-xs">
            </select>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-center">
            <label for="sol_aprob">Sol. Aprob.</label><br>
            <input class="form-control" type="checkbox" name="sol_aprob" id="sol_aprob" title="Solicita aprobación">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-center">
            <label for="con_tdoc">Consec.</label><br>
            <input class="form-control" type="checkbox" name="con_tdoc" id="con_tdoc" title="Usa consecutivo">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="num_tdoc">Próximo</label>
            <input type="number" class="form-control text-right text-xs" id="num_tdoc" name="num_tdoc" placeholder="Ingrese numero siguiente">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="id_tmoinv">Movimiento de inventario</label>
            <select name="id_tmoinv" id="id_tmoinv" class="form-control text-xs">
            </select>
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
                <input type="text" class="form-control text-xs id_aux" id="nom_aux" name="nom_aux" readonly>
                <div class="input-group-append">
                    <span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i id="div_aux" name="div_aux" class="fas fa-search"></i></a></span>
                </div>
            </div>
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="status">Status<span class="required">*</span></label>
            <select class="form-control text-xs" id="status" name="status" required></select>
        </div>
    </div>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/CuentasCtb/modal_CuentasCtb.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/AuxiliarCtb/modal_AuxiliaresCtb.php';
?>