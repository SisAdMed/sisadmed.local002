<div class="card-body">
    <input type="hidden" name="id" value="<?php echo $r->id_pais  ?? '' ?>">
    <div class="row">
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="codigo_pais">Código <span class="required">*</span></label>
            <input type="text" class="form-control" onkeyup="mayusculas(this)" id="codigo_pais" name="codigo_pais" placeholder="Ingrese código" required value="<?php echo $r->codigo_pais  ?? '' ?>">
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="nombre_pais">Nombre <span class="required">*</span></label>
            <input type="text" class="form-control" onkeyup="mayusculas(this)" id="nombre_pais" name="nombre_pais" placeholder="Ingrese nombre" required value="<?php echo $r->nombre_pais  ?? '' ?>">
        </div>   
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="iso_pais">Código ISO <span class="required">*</span></label>
            <input type="number" class="form-control" id="iso_pais" name="iso_pais" placeholder="Ingrese código ISO" required value="<?php echo $r->iso_pais  ?? '' ?>">
        </div>               
    </div>
</div>