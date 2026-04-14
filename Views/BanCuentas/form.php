<div class="card-body">
   <input type="hidden" name="id" id="id" value="<?php echo $r->id_bancue  ?? '' ?>">
   <div class="row">
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label for="id_emp">Empresa <span class="required">*</span></label>
            <select autofocus class="form-control custom-select rounded-0" name="id_emp" id="id_emp" title="Empresa"></select>
        </div>
    </div>
   <div class="row">
      <div class="form-group col-md-2 col-sm-2 col-xs-12">
         <label for="id_banco">Institución Bancaria</label>
         <select autofocus class="form-control" name="id_banco" id="id_banco"></select>
      </div>
      <div class="form-group col-md-2 col-sm-2 col-xs-12">
         <label for="suc_bancue">Sucursal</label>
         <input autofocus class="form-control" type="number" name="suc_bancue" id="suc_bancue">
      </div>
      <div class="form-group col-md-2 col-sm-2 col-xs-12">
         <label for="con_bancue">Control</label>
         <input autofocus class="form-control" type="number" name="con_bancue" id="con_bancue">
      </div>
      <div class="form-group col-md-2 col-sm-2 col-xs-12">
         <label for="cue_bancue">Cuenta corta</label>
         <input autofocus class="form-control" type="number" name="cue_bancue" id="cue_bancue">
      </div>
      <div class="form-group col-md-4 col-sm-4 col-xs-12">
         <label for="cuenta_bancue">Número de Cuenta Bancaria</label>
         <input autofocus class="form-control" type="text" name="cuenta_bancue" id="cuenta_bancue" readonly>
      </div>
   </div>
   <div class="row">
     <!--  <div class="form-group col-md-5 col-sm-5 col-xs-12">
         <label for="id_cta">Cuenta Contable</label>
         <select autofocus name="id_cta" id="id_cta" class="form-control select2 select2bs4"></select>
      </div>
      <div class="form-group col-md-5 col-sm-5 col-xs-12">
         <label for="id_aux">Auxiliar Contable</label>
         <select autofocus name="id_aux" id="id_aux" class="form-control select2 select2bs4"></select>
      </div> -->
      <div class="form-group col-md-4 col-sm-4 col-xs-12">
           <label for="nom_ctb">Cuenta contable</label>
           <input type="hidden" name="id_ctb" id="id_ctb">
           <div class="input-group">
               <input type="text" class="form-control text-xs" id="nom_ctb" name="nom_ctb" readonly>
               <div class="input-group-append">
                   <span class="input-group-text nom_ctb"><a href="#" data-toggle="modal" data-target="#modal-CuentasCtb" title="Buscar y seleccionar Cuentas Contables"> <i class="fas fa-search text-xs"></i></a></span>
               </div>
           </div>
       </div> <div class="form-group col-md-4 col-sm-4 col-xs-12">
           <label for="nom_aux">Auxiliar contable</label>
            <input type="hidden" name="id_aux" id="id_aux">
            <div class="input-group">
               <input type="text" class="form-control text-xs id_aux" id="nom_aux" name="nom_aux" readonly> 
               <div class="input-group-append">
                  <span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i id="div_aux" name="div_aux" class="fas fa-search"></i></a></span>
               </div>
            </div>
       </div>
      <div class="form-group col-md-2 col-sm-2 col-xs-12">
         <label for="status">Status</label>
         <select autofocus class="custom-select rounded-0 form-control" name="status" id="status"></select>
      </div>
   </div>
     <?php
        require_once $_SERVER['DOCUMENT_ROOT'].'/Views/CuentasCtb/modal_CuentasCtb.php';
        require_once $_SERVER['DOCUMENT_ROOT'].'/Views/AuxiliarCtb/modal_AuxiliaresCtb.php';
    ?>
</div>