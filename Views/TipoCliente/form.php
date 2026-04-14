<div class='card-body'>
    <div class='row'>
        <div class='form-group col-md-1 col-sm-1 col-xs-12'>
            <label for="id"># Registro</label>
            <input type='text' class='form-control text_xs text-right' readonly id='id' name='id' value='<?= $r->id ?? '' ?>'>
        </div>
        <div class='form-group col-md-6 col-sm-6 col-xs-12'>
            <label for='description'>Descripción</label>
            <input name='description' id='description' class='form-control text-xs' onkeyup="mayusculas(this)">
            <span></span>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control text-xs"></select>
        </div>
    </div>
</div>