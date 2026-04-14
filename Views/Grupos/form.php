<div class='card-body'>
    <input type='hidden' id='id' name='id' value='<?= $r->id_grupo ?? '' ?>'>
    <div class='row'>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for="grupo_codigo">Código</label>
            <input type="text" id="grupo_codigo" name="grupo_codigo" class="form-control text-right text-xs" readonly>
        </div>
        <div class="form-group col-md-6 col-sm-6 col-xs-12">
            <label for="grupo_nombre">Descripción</label>
            <input type="text" id="grupo_nombre" name="grupo_nombre" class="form-control text-xs" onkeyup="mayusculas(this);">
        </div>
        <div class="form-group col-md-2 col-sm-2 colxs-12">
            <label for="Status">Status</label>
            <select name="status" id="status" class="form-control text-xs"></select>
        </div>
    </div>
</div>