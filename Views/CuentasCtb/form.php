<div class="card-body">
   <input type="hidden" id="id" name="id" value="<?php echo $r->id_cta  ?? '' ?>">
   <div class="row">
      <div class="form-group col-md-6 col-sm-6 col-xs-12">
         <label for="cod_cta">Cuenta<span class="required">*</span></label>
         <input type="text" class="form-control mayusculas text-xs" id="cod_cta" name="cod_cta" placeholder="Ingrese código">
      </div>
      <div class="form-group col-md-6 col-sm-6 col-xs-12">
         <label for="nombre_cta">Descripción<span class="required">*</span></label>
         <input type="text" class="form-control mayusculas text-xs" id="nombre_cta" name="nombre_cta" placeholder="Ingrese nombre">
      </div>
   </div>
   <div class="row">
      <div class="form-group col-md-6 col-sm-6 col-xs-12">
         <label for="agrupa_cta">Agrupador<span class="required">*</span></label>
         <select class="form-control text-xs" id="agrupa_cta" name="agrupa_cta"></select>
      </div>
      <div class="form-group col-md-6 col-sm-6 col-xs-12">
         <label for="aux_cta">Utiliza auxiliares<span class="required">*</span></label>
         <select class="form-control text-xs" id="aux_cta" name="aux_cta"></select>
      </div>
   </div>
   <div class="row">
      <div class="form-group col-md-6 col-sm-6 col-xs-12">
         <label for="tip_cta">Tipo de cuenta<span class="required">*</span></label>
         <select class="form-control text-xs" id="tip_cta" name="tip_cta"></select>
      </div>
      <div class="form-group col-md-6 col-sm-6 col-xs-12">
         <label for="status">Estado<span class="required">*</span></label>
         <select class="form-control text-xs" id="status" name="status"></select>
      </div>
   </div>
</div>