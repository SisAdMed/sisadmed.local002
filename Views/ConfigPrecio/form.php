<?php    
if(!empty($r)){  
  $xfecha = formatFechaYMD($r->fecha_precio);  
}else{
   $xfecha = date('Y-m-d');  
}
?>
<div class="card-body">
   <input type="hidden" name="id" value="<?php echo $r->id_precio  ?? '' ?>">
   <div class="row">
      <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label for="id_emp">Empresa <span class="required">*</span></label>
            <select autofocus class="custom-select rounded-0" name="id_emp" id="id_emp" <?php echo !empty($r->id_precio) ? 'disabled' : '' ?>>
                <?php
                selEmpresa($r->id_emp ?? '');
                ?>
            </select>            
            
        </div>
   </div>
   <div class="row">
      <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="fecha_precio">Fecha <span class="required">*</span></label>
            <input autofocus type="date" class="form-control" id="fecha_precio" name="fecha_precio" placeholder="Ingrese fecha" required value="<?php echo $xfecha ?? '' ?>" <?php echo !empty($r->id_precio) ? 'readonly' : '' ?>>
        </div>  
      <div class="form-group col-md-2 col-sm-2 col-xs-12">
         <label for="tasa">Tasa<span class="required">*</span></label> 
            <input autofocus type="number" class="form-control text-right" id="tasa" name="tasa" placeholder="Ingrese tasa" required value="<?php echo $r->tasa  ?? '' ?>" step="0.01" >         
         <span></span>
      </div>
   </div> 
   <div class="row"> 
      <div class="form-group col-md-4 col-md-4 col-sm-4 col-xs-12">
            <label for="status">Estado </label>
            <select autofocus class="form-control custom-select rounded-0 " id="status" name="status" required>
             <?php                           
                status($r->status);            
            ?>
        </select>
    </div>
   </div>      
</div>