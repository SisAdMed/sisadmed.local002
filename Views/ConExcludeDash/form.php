<div class='card-body'>
    <input type='hidden' id='id' name='id' value='<?= $r->id ?? '' ?>'>
    <div class='row'>
        <div class='form-group col-md-4 col-sm-4 col-xs-12'>
            <label for='mod'>Módulo <span>*</span></label>
            <select name='mod' id='mod' class='form-control text-xs'></select>
            <input type="hidden" id="nom_mod" name="nom_mod">
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="id_concept">Concepto</label>
            <select name="id_concept" id="id_concept" class="select2 select2bs4 text-xs" style="width:100%">
                <input type="hidden" id="nom_con" name="nom_con">
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control text-xs"></select>
        </div>
    </div>
</div>