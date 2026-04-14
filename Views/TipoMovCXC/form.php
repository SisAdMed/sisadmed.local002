<div class="card-body">
    <input type="text" name="id" id="id" value="<?= $r->id_tmocxc ?? '' ?>" hidden>
    <div class="row">
        <div class="form-group col-md-12 col-sm-12 col xs-12">
            <label for="id_emp">Empresa</label>
            <select autofocus name="id_emp" id="id_emp" class="form-control text-xs" title="Empresa"></select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="cod_tmocxc">Código</label>
            <input autofocus type="text" name="cod_tmocxc" id="cod_tmocxc" class="form-control text-xs mayusculas" title="Código Tipo de Movimiento" placeholder="Indique el Tipo de Movimiento" onkeyup="mayusculas(this)">
        </div>
        <div class="form-group col-md-6 col-sm-6 col-xs-12">
            <label for="des_tmocxc">Descripción</label>
            <input autofocus type="text" name="des_tmocxc" id="des_tmocxc" class="form-control text-xs mayusculas" title="Descripción" placeholder="Indique la Descripción del Tipo de Movimiento" onkeyup="mayusculas(this)">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="acc_tmocxc">Acción</label>
            <select autofocus name="acc_tmocxc" id="acc_tmocxc" class="form-control text-xs" title="Acción del Tipo de Movimiento"></select>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="rec_tmocxc">Relac. de Caja</label>
            <select autofocus name="rec_tmocxc" id="rec_tmocxc" class="form-control text-xs" title="Relación de caja"></select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="con_tmocxc">¿Desea conecutivo?</label>
            <select autofocus name="con_tmocxc" id="con_tmocxc" class="form-control text-xs"></select>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="next_tmocxc">Próximo</label>
            <input autofocus type="number" name="next_tmocxc" id="next_tmocxc" class="form-control text-right text-xs">
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="nom_ctb">Cuenta contable</label>
            <input type="hidden" name="id_ctb" id="id_ctb">
            <div class="input-group">
                <input type="text" class="form-control text-xs" id="nom_ctb" name="nom_ctb" readonly>
                <div class="input-group-append">
                    <span class="input-group-text nom_ctb"><a href="#" data-toggle="modal" data-target="#modal-CuentasCtb" title="Buscar y seleccionar Cuentas Contables"> <i class="fas fa-search text-xs"></i></a></span>
                </div>
            </div>
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12 div_aux">
            <label for="nom_aux">Auxiliar contable</label>
            <input type="hidden" name="id_aux" id="id_aux">
            <div class="input-group">
                <input type="text" class="form-control text-xs id_aux" id="nom_aux" name="nom_aux" readonly>
                <div class="input-group-append">
                    <span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i class="fas fa-search"></i></a></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="status">Status</label>
            <select autofocus name="status" id="status" class="form-control text-xs"></select>
        </div>
    </div>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/CuentasCtb/modal_CuentasCtb.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/AuxiliarCtb/modal_AuxiliaresCtb.php';
?>