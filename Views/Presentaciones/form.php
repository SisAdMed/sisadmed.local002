<div class="card-body">
   <input type="hidden" name="id" value="<?php echo $r->id_pre  ?? '' ?>">
   <div class="row">        
      <div class="form-group col-md-4 col-sm-4 col-xs-12">
         <label for="cod_pre">Código <span class="required">*</span></label>
         <input type="text" class="form-control" id="cod_pre" onkeyup="mayusculas(this)" name="cod_pre" placeholder="Ingrese código" required value="<?php echo $r->cod_pre  ?? '' ?>" <?php echo !empty($r->id_pre) ? 'disabled' : '' ?>>
         <span></span>
      </div>
      <div class="form-group col-md-4 col-sm-4 col-xs-12">
         <label for="nom_pre">Nombre </label>
         <input type="text" class="form-control" id="nom_pre" name="nom_pre" onkeyup="mayusculas(this)" placeholder="Ingrese nombre" required value="<?php echo $r->nom_pre  ?? '' ?>">
         <span></span>
      </div>                          
      <div class="form-group col-md-4 col-sm-4 col-xs-12">       
         <label for="status">Estado<span class="required">*</span></label> 
         <select class="form-control custom-select rounded-0" id="status" name="status" required>
            <?php
               if (isset($r)) {
                  status($r->status); 
               }else{
                  status(''); 
               }
            ?>
         </select>
         <span></span>                         
      </div>    
   </div>  
</div>