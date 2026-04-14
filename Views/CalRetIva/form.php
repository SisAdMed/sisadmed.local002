<div class='card-body'>
    <input type='hidden' id='id' name='id' value='<?= $r->id ?? '' ?>'>
    <div class='row'>
        <div class='form-group col-md-4 col-sm-4 col-xs-12'>
            <label for='id_emp'>Empresa <span>*</span></label>
            <select name='id_emp' id='id_emp' class='form-control text-xs'>
            </select>
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="id_cli">Proveedor <span class="required">*</span></label>
            <input type="text" id="id_cli" name="id_cli" hidden>
            <div class="input-group">
                <input type="text" class="form-control text-xs" id="nom_cli" name="nom_cli" readonly>
                <div class="input-group-append">
                    <span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-Proveedores" title="Buscar y seleccionar cliente"><i class="fas fa-search"></i></a></span>
                </div>
            </div>
        </div>
</div>
<div class='loader'
    <img src='<?= IMG . '/ajax-loading.gif'  ?>' />
</div>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/Views/Proveedores/modal_Proveedores.php';
?>