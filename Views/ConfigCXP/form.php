<div class="row">
   <div class="col-12 col-sm-12">
      <div class="card card-primary card-tabs">
         <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="customer-configcxc" role="tablist">
               <li class="nav-item">
                  <a class="nav-link active" id="customer-basic-tab" data-toggle="pill" href="#customer-basic" role="tab" aria-controls="customer-basic" aria-selected="true">Configuración</a>
               </li>
              <!--  <li class="nav-item">
                  <a class="nav-link" id="customer-contact-tab" data-toggle="pill" href="#customer-contact" role="tab" aria-controls="customer-contact" aria-selected="true">Contactos</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" id="customer-finance-tab" data-toggle="pill" href="#customer-finance" role="tab" aria-controls="customer-finance" aria-selected="true">Financiero</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" id="customer-logo-tab" data-toggle="pill" href="#customer-logo" role="tab" aria-controls="customer-logo" aria-selected="true">Logo</a>
               </li> -->
            </ul>
         </div>
      </div>
   </div>
</div>
<div class="card-body">
   <div class="tab-content" id="customer-configcxc-tabcontent">
      <input type="hidden" name="id" id="id" value="<?= $r->id_config ?? '' ?>">
      <div class="tab-pane fade show active" id="customer-basic" role="tabpanel" aria-labelledby="customer-basic-tab">
         <div class="row">
            <div class="form-group col-sm-12 col-md-12 col-xs-12">
               <label for="id_emp">Empresa</label>
               <select autofocus name="id_emp" id="id_emp" class="form-control"></select>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-sm-2 col-md-2 col-xs-12">
               <label for="show_doc">¿Mostrar Documentos?</label>
               <select autofocus name="show_doc" id="show_doc" class="form-control"></select>
            </div>
            <div class="form-group col-sm-2 col-md-2 col-xs-12">
               <label for="over_charges">¿Permitir pagos en exceso?</label>
               <select name="over_charges" id="over_charges" class="form-control"></select>
            </div>
            <div class="form-group col-sm-2 col-md-2 col-xs-12">
               <label for="fir_due_date">Madurez Primer vencimiento</label>
               <input autofocus type="number" name="fir_due_date" id="fir_due_date" class="form-control text-right">
            </div>
             <div class="form-group col-sm-2 col-md-2 col-xs-12">
               <label for="sec_due_date">Madurez Segundo vencimiento</label>
               <input autofocus type="number" name="sec_due_date" id="sec_due_date" class="form-control text-right">
            </div>
             <div class="form-group col-sm-2 col-md-2 col-xs-12">
               <label for="thi_due_date">Madurez Tercer vencimiento</label>
               <input autofocus type="number" name="thi_due_date" id="thi_due_date" class="form-control text-right">
            </div>
             <div class="form-group col-sm-2 col-md-2 col-xs-12">
               <label for="fou_due_date">Madurez Cuarto vencimiento</label>
               <input autofocus type="number" name="fou_due_date" id="fou_due_date" class="form-control text-right">
            </div>
         </div>
         <div class="row">
            <div class="form-group col-sm-2 col-md-2 col-xs-12">
               <label for="id_tdo">Tipo de Documento Fac. Servicios</label>
               <select name="id_tdo" id="id_tdo" class="form-control"></select>
            </div>
            <div class="form-group col-sm-3 col-md-3 col-xs-12">
               <label for="id_retiva">Concepto Retención de IVA</label>
               <select autofocus name="id_retiva" id="id_retiva" class="form-control select2 select2bs4 text-xs"></select>
            </div>
            <div class="form-group col-sm-3 col-md-3 col-xs-12">
               <label for="id_retislr">Concepto Retención de ISLR</label>
               <select autofocus name="id_retislr" id="id_retislr" class="form-control select2 select2bs4 text-xs"></select>
            </div>
              <div class="form-group col-sm-2 col-md-2 col-xs-12">
               <label for="con_ret_iva">Consec.Ret.de IVA</label>
               <input autofocus type="number" name="con_ret_iva" id="con_ret_iva" class="form-control text-right">
            </div>
            <div class="form-group col-sm-2 col-md-2 col-xs-12">
               <label for="status">Status</label>
               <select name="status" id="status" class="form-control"></select>
            </div>
         </div>
      </div>
   </div>
  <!--  <div class="tab-pane fade show" id="customer-contact" role="tabpanel" aria-labelledby="customer-contact-tab">
   </div>
   <div class="tab-pane fade show" id="customer-finance" role="tabpanel" aria-labelledby="customer-finance-tab">
   </div>
   <div class="tab-pane fade show" id="customer-logo" role="tabpanel" aria-labelledby="customer-logo-tab">
   </div> -->
</div>
</div>
<div class="loader">
   <img src="<?= IMG . '/ajax-loading.gif'  ?>" />
</div>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/Views/ConcepCXP/modal_ConcepCXP.php';
?>