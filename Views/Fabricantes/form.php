<div class="card-body">
   <input type="hidden" name="id" id="id" value="<?php echo $r->id_fab  ?? '' ?>">
   <div class="row">
      <div class="form-group col-md-4 col-sm-4 col-xs-12">
         <label for="nom_fab">Nombre </label>
         <input type="text" class="form-control" id="nom_fab" name="nom_fab" onkeyup="mayusculas(this)" placeholder="Ingrese nombre" required>
         <span></span>
      </div>
      <div class="form-group col-md-4 col-sm-4 col-xs-12">
         <label for="adicional01">No calcular adicional</label>
         <input type="checkbox" id="adicional01" name="adicional01" class="form-control text-left">
      </div>
      <div class="form-group col-md-4 col-sm-4 col-xs-12">
         <label for="status">Estado<span class="required">*</span></label>
         <select class="form-control custom-select rounded-0" id="status" name="status" required>
         </select>
         <span></span>
      </div>
      <div class="row">
         <div class="form-group col-md-4 col-sm-4 col-xs-12">
         <label for="observa">Observaciones</label>
         <textarea name="observa" id="observa" class="form-control" cols="250"></textarea>
         </div>
      </div>
   </div>
</div>