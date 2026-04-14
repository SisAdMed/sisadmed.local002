<div class='card-body'>
    <input type='hidden' id='id' name='id' value='<?= $r->id ?? '' ?>'>
    <div class='row'>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for='id_grupo'>Grupo</label>
            <input type="hidden" id="nombre_grupo" name="nombre_grupo">
            <select name='id_grupo' id='id_grupo' class='form-control select2 select2bs4 text-xs'> </select>
        </div>
        <div class="form-group col-md-8 col-sm-8 col-xs-12">
            <label for="sub_grupo_nombre">Sub Grupo</label>
            <input type="text" id="sub_grupo_nombre" name="sub_grupo_nombre" class="form-control text-xs" onkeyup="mayusculas(this);">
        </div>
        <div class='form-group col-md-2 col-sm-2 col-xs-12'>
            <label for='status'>Status</label>
            <select name='status' id='status' class='form-control text-xs'> </select>
        </div>
    </div>
</div>