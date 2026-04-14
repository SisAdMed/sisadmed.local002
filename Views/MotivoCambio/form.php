<?php
   $adic_01 = formatNumber(0,2);
   $adic_02 = formatNumber(0,2);
   if(isset($r)){
      $adic_01 = formatNumber($r->adic_01, 2);
      $adic_02 = formatNumber($r->adic_02, 2);
   }
?>
<div class="card-body">
   <input type="hidden" name="id" id="id" value="<?php echo $r->id_motcam  ?? '' ?>">
   <div class="row">
      <div class="form-group col-md-6 col-sm-6 col-xs-12">
         <label for="nom_motcam">Nombre </label>
         <input autofocus type="text" class="form-control" id="nom_motcam" name="nom_motcam" onkeyup="mayusculas(this)" placeholder="Ingrese nombre" required value="<?php echo $r->nom_motcam  ?? '' ?>">
         <span></span>
      </div>
      <div class="form-group col-md-2 col-sm-2 col-xs-12">
         <label for="adic_01">Recargo dólares </label>
         <input autofocus type="text" class="form-control text-right validar" id="adic_01" name="adic_01" onkeyup="mayusculas(this)" placeholder="Ingrese recargo dólares" required value="<?php echo $adic_01 ?>" >
         <span></span>
      </div>
      <div class="form-group col-md-2 col-sm-2 col-xs-12">
         <label for="adic_02">Recargo bolívares </label>
         <input autofocus type="text" class="form-control text-right validar" id="adic_02" name="adic_02" onkeyup="mayusculas(this)" placeholder="Ingrese recargo bolívares" required value="<?php echo $adic_02 ?>">
         <span></span>
      </div>
      <div class="form-group col-md-2 col-sm-2 col-xs-12">
         <label for="status">Estado<span class="required">*</span></label>
         <select autofocus class="form-control custom-select rounded-0" id="status" name="status" required>
            <?php
               status($r->status ?? '');
            ?>
         </select>
      </div>
   </div>
   <div class="row">
      <div class="center">
             <button type="button" class="btn btn-primary btn-sm" onclick="agregarFabMarLabLin();">+ Agregar</button>
        </div>
      <table id="tblTableFab" class="display responsive nowrap table table-hover" style="width:100%">
         <thead>
            <th>Id</th>
            <th>Fab/Mar/Lab/Lin</th>
            <th>Adicional</th>
            <th>Vigencia</th>
            <th>Acciones</th>
         </thead>
          <tbody id="cuerpoFabMarLabLin" name="cuerpoFabMarLabLin">
         </tbody>
      </table>
   </div>
</div>