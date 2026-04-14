<div class="card-body">
    <input type="hidden" name="id" id="id" value="<?php echo $r->id_zona  ?? '' ?>">
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="cod_zona">Código<span class="required">*</span></label> 
            <input type="text" class="form-control text-xs mayusculas" id="cod_zona" name="cod_zona" placeholder="Ingrese código">         
        </div>
        <div class="form-group col-md-7 col-sm-7 col-xs-12">
            <label for="nombre_zona">Nombre</label><span class="required">*</span>
            <input type="text" class="form-control text-xs mayusculas" id="nombre_zona" name="nombre_zona" placeholder="Ingrese nombre">
        </div>        
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="status">Estado<span class="required">*</span></label> 
            <select class="form-control text-xs" id="status" name="status"></select>    
        </div>
    </div>
</div>