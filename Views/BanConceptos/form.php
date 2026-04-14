<div class="card-body"> 
    <input type="text" name="id" id="id" value="<?= $r->id_bancon ?? '' ?>" hidden>
    <div class="row">
        <div class="form-group col-sm-2 col-md-2 col-xs-12">
            <label for="cod_bancon">Código</label>
            <input autofocus type="text" class="form-control" name="cod_bancon" id="cod_bancon" onkeyup="mayusculas(this)">
        </div>
        <div class="form-group col-sm-6 col-md-6 col-xs-12">
            <label for="nom_bancon">Descripción <span>*</span></label>
            <input autofocus type="text" class="form-control" name="nom_bancon" id="nom_bancon" onkeyup="mayusculas(this)">
        </div>
        <div class="form-group col-sm-2 col-md-2 col-xs-12">
            <label for="agr_bancon">Agrupa</label>
            <select autofocus class="form-control" name="agr_bancon" id="agr_bancon"></select>
        </div>
        <div class="form-group col-sm-2 col-md-2 col-xs-12">
            <label for="id_bantdo">Afecta documento</label>
            <select autofocus class="form-control" name="id_bantdo" id="id_bantdo"></select>
        </div>
    </div>
    <div class="row">
         <div class="form-group col-md-4 col-sm-4 col-xs-12">
           <label for="nom_ctb">Cuenta contable</label>
           <input type="hidden" name="id_ctb" id="id_ctb">
           <div class="input-group">
               <input type="text" class="form-control text-xs" id="nom_ctb" name="nom_ctb" readonly>
               <div class="input-group-append">
                   <span class="input-group-text nom_ctb"><a href="#" data-toggle="modal" data-target="#modal-CuentasCtb" title="Buscar y seleccionar Cuentas Contables"> <i class="fas fa-search text-xs"></i></a></span>
               </div>
           </div>
       </div> 
       <div class="form-group col-md-4 col-sm-4 col-xs-12">
           <label for="nom_aux">Auxiliar contable</label>
            <input type="hidden" name="id_aux" id="id_aux">
            <div class="input-group">
               <input type="text" class="form-control text-xs id_aux" id="nom_aux" name="nom_aux" readonly>
               <div class="input-group-append">
                  <span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i id="div_aux" name="div_aux" class="fas fa-search"></i></a></span>
               </div>
            </div>
       </div>
       <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="id_retislr">Tipo de Retención</label>
            <select name="id_retislr" id="id_retislr" class="form-control"></select>
       </div>
    </div>
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="status">Status</label>
            <select autofocus name="status" id="status" class="form-control"></select>
        </div>
    </div>
     <?php
        require_once $_SERVER['DOCUMENT_ROOT'].'/Views/CuentasCtb/modal_CuentasCtb.php';
        require_once $_SERVER['DOCUMENT_ROOT'].'/Views/AuxiliarCtb/modal_AuxiliaresCtb.php';
    ?>
</div>