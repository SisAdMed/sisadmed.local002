<div class='card-body'>
    <input type='hidden' id='id' name='id' value='<?= $r->id ?? '' ?>'>
    <div class='row'>
        <div class='form-group col-md-4 col-sm-4 col-xs-12'>
            <label for='id_emp'>Empresa</label>
            <select name='id_emp' id='id_emp' class='form-control text-xs' title="Nombre de Empresa">
            </select>
        </div>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for='fec_ini'>Fecha de Inicio</label>
            <input type="date" name="fec_ini" id="fec_ini" class="form-control text-xs" title="Fecha de inicio">
        </div>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for='fec_final'>Fecha de Corte</label>
            <input type="date" name="fec_final" id="fec_final" class="form-control text-xs" title="Fecha de Corte">
        </div>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for='id_vend'>Vendedor</label>
            <select name="id_vend" id="id_vend" class="form-control text-xs select2 select2bs4" title="Vendedor">
            </select>
        </div>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for='status'>Estatus</label>
            <select name='status' id='status' class='form-control text-xs' title="status">
            </select>
        </div>
    </div>
</div>
<div class='card-footer'>
    <table id="tblCalCom" name="tblCalCom" class="table table-striped table-bordered table-condensed table-hover text-xs compact" style="width:100%;">
    </table>
</div>