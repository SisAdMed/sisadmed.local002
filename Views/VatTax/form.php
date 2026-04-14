<div class="card-body">
    <input type="text" name="id" id="id" value="<?= $r->id_iva ?? '' ?>" hidden>
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="cod_iva">Código Impuesto:</label>
            <input autofocus type="text" name="cod_iva" id="cod_iva" class="form-control" onkeyup="mayusculas(this)">
        </div>
        <div class="form-group col-md-6 col-sm-6 col-xs-12">
            <label for="des_iva">Descripción Impuesto</label>
            <input autofocus type="text" name="des_iva" id="des_iva" class="form-control">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="fec_iva">Fec. Vigencia</label>
            <input autofocus type="date" name="fec_iva" id="fec_iva" class="form-control">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="status">Estatus</label>
            <select autofocus name="status" id="status" class="form-control"></select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right">
            <label for="txr1_iva">Impuesto 1</label>
            <input autofocus type="number" name="txr1_iva" id="txr1_iva" class="form-control text-right txr1_iva" step="0.01" min="0.01" max=100>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right">
            <label for="txr2_iva">Impuesto 2</label>
            <input autofocus type="number" name="txr2_iva" id="txr2_iva" class="form-control text-right txr1_iva" step="0.01">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right">
            <label for="txr3_iva">Impuesto 3</label>
            <input autofocus type="number" name="txr3_iva" id="txr3_iva" class="form-control text-right txr1_iva" step="0.01">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right">
            <label for="txr4_iva">Impuesto 4</label>
            <input autofocus type="number" name="txr4_iva" id="txr4_iva" class="form-control text-right txr1_iva" step="0.01">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right">
            <label for="txr5_iva">Impuesto 5</label>
            <input autofocus type="number" name="txr5_iva" id="txr5_iva" class="form-control text-right txr1_iva" step="0.01">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right">
            <label for="total_impto">Total impuesto</label>
            <input autofocus type="number" name="total_impto" id="total_impto" class="form-control text-right" step="0.01">
        </div>
    </div>
</div>