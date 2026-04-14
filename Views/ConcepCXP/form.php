<div class="card-body">
    <input type="hidden" name="id" id="id" value="<?php echo $r['id'] ?? ''; ?>">
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="codigo_con">Código <span>*</span></label>
            <input type="text" id="codigo_con" name="codigo_con" class="form-control text-xs mayusculas">
        </div>
        <div class="form-group col-md-8 col-sm-8 col-xs-12">
            <label for="nombre_con">Descripción <span>*</span></label>
            <input type="text" id="nombre_con" name="nombre_con" class="form-control text-xs mayusculas">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="agrupa_con">Agrupador</label>
            <select id="agrupa_con" name="agrupa_con" class="form-control text-xs"></select>
        </div>
    </div>
    <div class="agrupa_con">
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
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4 col-sm-4 col-xs-12 agrupa_con">
            <label for="id_retislr">Retención de ISLR</label>
            <select name="id_retislr" id="id_retislr" class="form-control text-xs"></select>
        </div>
        <div class="form-group col-md-6 col-sm-6 col-xs-12">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control text-xs"></select>
        </div>
    </div>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/CuentasCtb/modal_CuentasCtb.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/AuxiliarCtb/modal_AuxiliaresCtb.php';
?>