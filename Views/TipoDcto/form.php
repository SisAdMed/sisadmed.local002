<div class="card-body">
    <input type="hidden" name="id" id="id" value="<?php echo $r->id ?? '' ?>">
    <div class="row">
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="codigo_tipdes">Código<span class="required">*</span></label>
            <input autofocus type="text" class="form-control" id="codigo_tipdes" name="codigo_tipdes" placeholder="Ingrese código"  onkeyup="mayusculas(this);" >
            <span></span>
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="valor_tipdes">Descuento</label><span class="required">*</span>
            <input autofocus type="number" class="form-control text-right" id="valor_tipdes" name="valor_tipdes" placeholder="Ingrese valor de Descuento" step="0.01">
            <span></span>
        </div>
         <div class="form-group col-md-3 col-sm-3 col-xs-12 text-left">
            <label for="appreq">Requiere aprobación</label><span class="required">*</span>
            <input type="checkbox" id="appreq" name="appreq" class="form-control text-left">
            <span></span>
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="status">Estado<span class="required">*</span></label>
            <select autofocus class="form-control" id="status" name="status">
            </select>
            <span></span>
        </div>
    </div>
</div>