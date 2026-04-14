<div class="row">
   <div class="col-12 col-sm-12">
      <div class="card card-primary card-tabs">
         <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="customer-products" role="tablist">
               <li class="nav-item">
                  <a class="nav-link active" id="customer-basic-tab" data-toggle="pill" href="#customer-basic" role="tab" aria-controls="customer-basic" aria-selected="true">Principal</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" id="customer-contact-tab" data-toggle="pill" href="#customer-contact" role="tab" aria-controls="customer-contact" aria-selected="true">Contactos</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" id="customer-finance-tab" data-toggle="pill" href="#customer-finance" role="tab" aria-controls="customer-finance" aria-selected="true">Financiero</a>
               </li>
            </ul>
         </div>
      </div>
   </div>
</div>
<div class="card-body">
   <div class="tab-content" id="customer-products-tabcontent">
      <div class="tab-pane fade show active" id="customer-basic" role="tabpanel" aria-labelledby="customer-basic-tab">
         <input type="hidden" name="id" id="id" value="<?= $r[0]->id_ent ?? '' ?>">
         <input type="hidden" name="tip_ent" id="tip_ent" value="P">
         <div class="row">
            <div class="form-group col-md-1 col-sm-1 col-xs-12">
               <label for="rif_ent">RIF/Cédula</label>
               <input type="text" class="form-control rif mayusculas text-xs" id="rif_ent" name="rif_ent" placeholder="J-00000000-0">
            </div>
            <div class="form-group col-md-5 col-sm-5 col-xs-12">
               <label for="nom_ent">Nombre</label>
               <input type="text" class="form-control mayusculas text-xs" id="nom_ent" name="nom_ent" placeholder="Ingrese Nombre">
            </div>
            <div class="form-group col-md-5 col-sm-5 col-xs-12">
               <label for="cor_ent">Nombre corto</label>
               <input type="text" class="form-control mayusculas text-xs" id="cor_ent" name="cor_ent" placeholder="Ingrese Nombre">
            </div>
            <div class="form-group col-md-1 col-sm-1 col-xs-12">
               <label for="postal_ent">Zona Postal</label>
               <input autofocus type="text" class="form-control text-xs" id="postal_ent" name="postal_ent" placeholder="Zona postal" required>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-3 col-sm-3 col-xs-12">
               <label for="id_pais">País</label>
               <select name="id_pais" id="id_pais" class="select2 select2bs4 text-xs">
               </select>
            </div>
            <div class="form-group col-md-3 col-sm-3 col-xs-12">
               <label for="id_edo">Estado</label>
               <select name="id_edo" id="id_edo" class="select2 select2bs4 text-xs">
               </select>
            </div>
            <div class="form-group col-md-3 col-sm-3 col-xs-12">
               <label for="id_ciudad">Ciudad</label>
               <select name="id_ciudad" id="id_ciudad" class="select2 select2bs4 text-xs">
               </select>
            </div>
            <div class="form-group col-md-3 col-sm-3 col-xs-12">
               <label for="id_diascre">Días de Crédito</label>
               <select name="id_diascre" id="id_diascre" class="select2 select2bs4 text-xs">
               </select>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-12 col-sm-12 col-xs-12">
               <label for="dir_ent">Dirección</label>
               <textarea class="form-control text-xs mayusculas" name="dir_ent" id="dir_ent" cols="143" rows="2"></textarea>
            </div>
         </div>
         <div class="row">
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-center">
               <label for="contr_esp">Contribuyente especial</label>
               <input type="checkbox" name="contr_esp" id="contr_esp" class="form-control text-xs">
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
               <label for="id_por_ret_iva">Porcentaje Ret. IVA</label>
               <select name="id_por_ret_iva" id="id_por_ret_iva" class="select2 select2bs4 text-xs"></select>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
               <label for="status">Condición</label>
               <select class="select2 select2bs4  text-xs" id="status" name="status" required>
               </select>
            </div>
         </div>
      </div>
      <div class="tab-pane fade show" id="customer-contact" role="tabpanel" aria-labelledby="customer-contact-tab">
         <div class="row">
            <div class="form-group col-md-12 col-sm-12 col-xs-12">
               <table id="det_con" name="det_con" class="display responsive nowrap table table-hover text-xs" style="width:100%">
                  <thead>
                     <tr>
                        <th>Nombre(s)</th>
                        <th>Apellidos(s)</th>
                        <th>Correo</th>
                        <th>Area</th>
                        <th>Teléfono</th>
                        <th>Departamento</th>
                        <th class="text-center">Acción</th>
                     </tr>
                  </thead>
                  <tbody></tbody>
                  <tfoot>
                     <tr>
                        <th colspan="7"><button type="button" class="btn btn-primary text-xs" id="btn_accion">Nuevo contacto</button></th>
                     </tr>
                  </tfoot>
               </table>
            </div>
         </div>
      </div>
      <div class="tab-pane fade show" id="customer-finance" role="tabpanel" aria-labelledby="customer-finance-tab">
         <div class="row">
            <div class="form-group col-md-12 col-sm-12 col-xs-12">
               <table id="tblCXCedo_cuenta" name="tblCXCedo_cuenta" class="display responsive nowrap table table-hover text-xs" style="width:100%">
               </table>
            </div>
         </div>
      </div>
   </div>
</div>