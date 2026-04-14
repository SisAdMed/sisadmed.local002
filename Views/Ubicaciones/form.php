<div class="card-body">
  <input type="hidden" name="id" value="<?php echo $r->id_ubi  ?? '' ?>">
  <div class="row">
      <div class="form-group col-md-12 col-sm-12 col-xs-12">
          <label for="id_emp">Empresa</label>
          <select name="id_emp" id="id_emp" class="form-control" >
              <?php 
                selEmpresa($r->id_emp ?? '');
               ?>
          </select>
      </div>
  </div>
  <div class="row">
    <div class="form-group col-md-4 col-sm-4 col-xs-12">
      <label for="cod_ubi"> Código <span class="required">*</span></label>
      <input autofocus type="text" class="form-control" id="cod_ubi" name="cod_ubi" placeholder="Ingrese código" required value="<?php echo $r->cod_ubi  ?? '' ?>" onkeyup="mayusculas(this);"  <?php echo !empty($r->id_ubi) ? 'disabled' : '' ?>>
      <span></span>

   </div>
   <div class="form-group col-md-4 col-sm-4 col-xs-12">
      <label for="nom_ubi">Nombre</label>
      <input autofocus type="text" class="form-control" id="nom_ubi" name="nom_ubi" placeholder="Ingrese nombre" required value="<?php echo $r->nom_ubi  ?? '' ?>" onkeyup="mayusculas(this);">
      <span></span>

   </div>
   <div class="form-group col-md-4 col-sm-4 col-xs-12">
      <label for="agru_ubi">Agrupador </label>
      <select class="custom-select rounded-0" id="agru_ubi" name="agru_ubi" required>
        <?php
        if (isset($r)) {
          agrupador($r->agru_ubi);
       } else {
        agrupador('');
     }
     ?>
  </select>
</div>
</div>
<div class="row">
   <div class="form-group col-md-4 col-sm-4 col-xs-12">
        <label for="refri_ubi">Refrigerado </label>
        <select class="custom-select rounded-0" id="refri_ubi" name="refri_ubi" required>
            <?php
            if (isset($r)) {
                agrupador($r->refri_ubi); 
            }else{
                agrupador('');
            }
            ?>
        </select>
        <span></span>
    </div>
    <div class="form-group col-md-4 col-sm-4 col-xs-12">
        <label for="uso_ubi">Uso interno </label>
        <select class="custom-select rounded-0" id="uso_ubi" name="uso_ubi" required>
            <?php
            if (isset($r)) {
                agrupador($r->uso_ubi);
            }else{
                agrupador('');
            }
            ?>
        </select>
        <span></span>
    </div>
  <div class="form-group col-md-4 col-sm-4 col-xs-12">
    <label for="status">Estado </label>
    <select class="custom-select rounded-0" id="status" name="status" required>
      <?php
      if (isset($r)) {
        status($r->status);
     }else{
        status('');
     }
     ?>
  </select>
</div>
</div>

</div>
