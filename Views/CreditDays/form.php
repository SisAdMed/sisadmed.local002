<div class="card-body">
    <input type="text" id="id" name="id" value="<?= $r->id_diascre ?? '' ?>" hidden>
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="cod_diascre">Días de Crédito</label>
            <input type="number" id="cod_diascre" name="cod_diascre" class="form-control text-right text-xs">
        </div>
        <div class="form-group col-md-8 col-sm-8 col-xs-12">
            <label for="des_diascre">Descripción</label>
            <input type="text" id="des_diascre" name="des_diascre" class="form-control text-xs mayusculas">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="status">Estatus</label>
            <select name="status" id="status" class="form-control text-xs"></select>
        </div>
    </div>
</div>