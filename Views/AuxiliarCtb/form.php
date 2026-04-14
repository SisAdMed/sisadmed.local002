<div class="card-body">
    <input type="hidden" id="id" name="id" value="<?php echo $r->id_aux  ?? '' ?>">
    <div class="row">
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="cod_aux">Código<span class="required">*</span></label>
            <input  type="text" class="form-control mayusculas text-xs" id="cod_aux" name="cod_aux" placeholder="Ingrese código">
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="nombre_aux">Nombre</label><span class="required">*</span>
            <input type="text" class="form-control mayusculas text-xs" id="nombre_aux" name="nombre_aux" placeholder="Ingrese nombre">
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="agrupa_aux">Agrupador<span class="required">*</span></label>
            <select class="form-control custom-select rounded-0 text-xs" id="agrupa_aux" name="agrupa_aux"></select>
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="status_aux">Estado<span class="required">*</span></label>
            <select class="form-control custom-select rounded-0 text-xs" id="status_aux" name="status_aux"></select>
        </div>
    </div>
</div>