<div class="card-boy">
    <input type="text" name="id" id="id" value="<?= $r->id_banco ?? '' ?>" hidden>
    <div class="row">
        <div class="form-group col-md-1 col-sm-1 col-xs-12">
            <label for="cod_banco">Banco <span>*</span></label>
            <input autofocus type="text" class="form-control" id="cod_banco" name="cod_banco" title="Código del Banco" placeholder="Código Banco">
        </div>
        <div class="form-group col-md-9 col-sm-9 col-xs-12">
            <label for="nombre_banco">Nombre <span>*</span></label>
            <input autofocus type="text" class="form-control" id="nombre_banco" name="nombre_banco" title="Nombre del Banco" placeholder="Indique el Nombre del Banco">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="extranjero_ban">Extranjero</label>
            <select autofocus name="extranjero_ban" id="extranjero_ban" class="form-control" title="Indica si es extranjero"></select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label for="dir_banco">Dirección</label>
            <textarea autofocus class="form-control" rows="2" cols="50" title="Dirección" placeholder="Indique la dirección del banco"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6 col-md-6-col-xs-12">
            <label for="tel_banco">Teléfono(s)</label>
            <input autofocus type="text" class="form-control" name="tel_banco" id="tel_banco" title="Número(s) de teléfono(s)" placeholder="Indique el(los) número(s) de teléfóno(s)">
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="status">Estatus</label>
            <select autofocus name="status" id="status" class="form-control" title="Indica el status del registro"></select>
        </div>
    </div>
</div>