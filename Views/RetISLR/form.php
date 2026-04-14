<div class='card-body'>
    <input type='hidden' id='id' name='id' value="<?php echo $r->id  ?? '' ; ?>">
    <div class='row'>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for="fecha_vigencia">Fecha de vigencia:</label>
            <input type="date" name="fecha_vigencia" id="fecha_vigencia" class="form-control text-xs">
        </div>
        <div class='form-group col-md-4 col-sm-4 col-xs-12'>
            <label for="descrip">Descripción:</label>
            <input type="text" name="descrip" id="descrip" class="form-control text-xs">
        </div>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for="minimo">Mínimo:</label>
            <input type="text" name="minimo" id="minimo" class="form-control text-xs text-right myNumberFormatDom">
            <input type="text" name="minimo_for" id="minimo_for" hidden >
        </div>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for="maximo">Máximo:</label>
            <input type="text" name="maximo" id="maximo" class="form-control text-xs text-right myNumberFormatDom">
            <input type="text" name="maximo_for" id="maximo_for" hidden >
        </div>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for="por_reten">Porcentaje de Retención:</label>
            <input type="text" name="por_reten" id="por_reten" class="form-control text-xs text-right myNumberFormatDom">
            <input type="text" name="por_reten_for" id="por_reten_for" hidden >
        </div>
    </div>
    <div class="row">
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for="por_imp_suj_ret">Por.Imp.Retención:</label>
            <input type="text" name="por_imp_suj_ret" id="por_imp_suj_ret" class="form-control text-xs text-right myNumberFormatDom">
            <input type="text" name="por_imp_suj_ret_for" id="por_imp_suj_ret_for" hidden>
        </div>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for="fac_reten">Factor Retención:</label>
            <input type="text" name="fac_reten" id="fac_reten" class="form-control text-xs text-right myNumberFormatDom">
            <input type="text" name="fac_reten_for" id="fac_reten_for" hidden >
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="code_seniat">Código Seniat</label>
            <input type="text" name="code_seniat" id="code_seniat" class="form-control text-xs">
        </div>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control text-xs"></select>
        </div>
    </div>
    <div class='loader'
        <img src='<?= IMG . '/ajax-loading.gif'  ?>' />
    </div>
</div>