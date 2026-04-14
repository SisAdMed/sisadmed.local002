<div class="card-boy">
    <input type="text" name="id" id="id" value="<?= $r->id_bantdo ?? '' ?>" hidden>
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-2">
            <label for="cod_bantdo">Código</label>
            <input autofocus type="text" name="cod_bantdo" id="cod_bantdo" class="form-control" title="Código del Documento" placeholder="Indique Código del Documento" onkeyup="mayusculas(this);">
        </div>
        <div class="form-group col-md-8 col-sm-8 col-xs-8">
            <label for="nom_bantdo">Nombre <span class="requiered">*</span></label>
            <input autofocus type="text" name="nom_bantdo" id="nom_bantdo" class="form-control" title="Nombre del Documento" placeholder="Indique el Nombre del Documento" onkeyup="mayusculas(this);">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-2">
            <label for="status">Status</label>
            <select autofocus name="status" id="status" class="form-control"></select>
        </div>
    </div>
</div>