<div class="card-boy">
    <input type="text" name="id" id="id" value="<?= $r->id_bantmo ?? '' ?>" hidden>
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="cod_bantmo">Código</label>
            <input autofocus type="text" name="cod_bantmo" id="cod_bantmo" class="form-control" title="Código del Movimiento" placeholder="Indique Código del Movimiento" onkeyup="mayusculas(this);">
        </div>
        <div class="form-group col-md-6 col-sm-6 col-xs-12">
            <label for="nom_bantmo">Nombre <span class="requiered">*</span></label>
            <input autofocus type="text" name="nom_bantmo" id="nom_bantmo" class="form-control" title="Nombre del Movimiento" placeholder="Indique el Nombre del Movimiento" onkeyup="mayusculas(this);">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="acc_bantmo">Acción</label>
            <select autofocus name="acc_bantmo" id="acc_bantmo" class="form-control"></select>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="idb_bantmo">Genera IGTF</label>
            <select autofocus name="idb_bantmo" id="idb_bantmo" class="form-control"></select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-1 col-sm-1 col-xs-12">
            <label for="con_bantmo">Consecutivo</label>
            <select autofocus name="con_bantmo" id="con_bantmo" class="form-control"></select>
        </div>
        <div class="form-group col-md-1 col-sm-1 col-xs-12">
            <label for="cash_bantmo">Efectivo</label>
            <select autofocus name="cash_bantmo" id="cash_bantmo" class="form-control"></select>
        </div>
        <div class="form-group col-md-1 col-sm-1 col-xs-12">
            <label for="che_bantmo">Cheque</label>
            <select autofocus name="che_bantmo" id="che_bantmo" class="form-control"></select>
        </div>
        <div class="form-group col-md-1 col-sm-1 col-xs-12">
            <label for="tra_bantmo">Transferencia</label>
            <select autofocus name="tra_bantmo" id="tra_bantmo" class="form-control"></select>
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="efe_bantmo">Efecto</label>
            <select autofocus name="efe_bantmo" id="efe_bantmo" class="form-control"></select>
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="id_cxtmo">Tipo de Movimiento</label>
            <select autofocus name="id_cxtmo" id="id_cxtmo" class="form-control"></select>
        </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="status">Status</label>
            <select autofocus name="status" id="status" class="form-control"></select>
        </div>
    </div>
</div>