<div class="row"> 
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="module-configfac" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="module-configfac-general-tab" data-toggle="pill" href="#module-configfac-general" role="tab" aria-controls="module-configfac-general" aria-selected="true">Configuración</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="module-values-tab" data-toggle="pill" href="#module-values" role="tab" aria-controls="module-values" aria-selected="true">Valores Predeterminados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="module-inventory-tab" data-toggle="pill" href="#module-inventory" role="tab" aria-controls="module-inventory" aria-selected="true">Inventario</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="card-body">
    <div class="tab-content" id="module-confifac-general-tabcontent">
        <input type="hidden" name="id" id="id" value="<?= $r->id_config_fac ?? '' ?>">
        <div class="tab-pane fade show active" id="module-configfac-general" role="tabpanel" aria-labelledby="module-configfac-tab">
            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-xs-12">
                    <label for="id_emp">Empresa</label>
                    <select autofocus name="id_emp" id="id_emp" class="form-control"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group-col-sm-4 col-md-4 col-xs-12">
                    <label for="id_con_sales">Concepto de Ventas</label>
                    <select autofocus name="id_con_sales" id="id_con_sales" class="form-control select2 select2bs4"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-2 col-md-2 col-xs-12">
                    <label for="status">Status</label>
                    <select autofocus name="status" id="status" class="form-control"></select>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="module-values" role="tabpanel" aria-labelledby="module-values-tab">
            <div class="row">
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="id_tdoc_fac">Tipo de Documento para Factura</label>
                    <select autofocus name="id_tdoc_fac" id="id_tdoc_fac" class="form-control"></select>
                </div>
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="id_tdoc_cre">Tipo de Documento para Nota de Crédito</label>
                    <select autofocus name="id_tdoc_cre" id="id_tdoc_cre" class="form-control"></select>
                </div>
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="id_tdoc_pre">Tipo de Documento para Presupuesto</label>
                    <select autofocus name="id_tdoc_pre" id="id_tdoc_pre" class="form-control"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="id_tdoc_not">Tipo de Documento para Nota de Entrega</label>
                    <select autofocus name="id_tdoc_not" id="id_tdoc_not" class="form-control"></select>
                </div>
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="id_tdoc_not_no_fis">Tipo de Documento para Nota de Entrega no Fiscal</label>
                    <select autofocus name="id_tdoc_not_no_fis" id="id_tdoc_not_no_fis" class="form-control"></select>
                </div>
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="id_tdoc_dev">Tipo de Documento para Nota de Devolución</label>
                    <select autofocus name="id_tdoc_dev" id="id_tdoc_dev" class="form-control"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="note_fac">Notas Factura</label>
                    <textarea name="note_fac" id="note_fac" class="form-control" rows="2" cols="50"></textarea>
                </div>
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="note_cre">Notas Crédito</label>
                    <textarea name="note_cre" id="note_cre" class="form-control"></textarea>
                </div>
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="note_pre">Notas Presupuesto</label>
                    <textarea name="note_pre" id="note_pre" class="form-control"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="note_not">Notas Nota de Entrega</label>
                    <textarea name="note_not" id="note_not" class="form-control"></textarea>
                </div>
                 <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="note_not_no_fis">Notas Nota de Entrega no Fiscal</label>
                    <textarea name="note_not_no_fis" id="note_not_no_fis" class="form-control"></textarea>
                </div>
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="note_dev">Notas Devolución</label>
                    <textarea name="note_dev" id="note_dev" class="form-control"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="module-inventory" role="tabpanel" aria-labelledby="module-inventory-tab">
            <div class="row">
                 <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="id_alm">Almacén de Factura</label>
                    <select autofocus name="id_alm" id="id_alm" class="form-control"></select>
                </div>
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="tmov_fac">Tipo de Movimiento de Salida</label>
                    <select autofocus name="tmov_fac" id="tmov_fac" class="form-control"></select>
                </div>
                  <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="tmov_noc">Tipo de Movimiento de Entrada</label>
                    <select autofocus name="tmov_noc" id="tmov_noc" class="form-control"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-2 col-md-2 col-xs-12">
                    <label for="id_ubi">Ubicación predeterminada</label>
                    <select autofocus name="id_ubi" id="id_ubi" class="form-control"></select>
                </div>
                <div class="form-group form-check col-md-2 col-sm-2 col-xs-12 text-center">
                    <label for="fac_stock">Facturar solo con stock</label>
                    <input autofocus type="checkbox" class="form-control float-center" id="fac_stock" name="fac_stock" style="vertical-align: bottom;">
                </div>
                 <div class="form-group form-check col-md-2 col-sm-2 col-xs-12 text-center">
                    <label for="cot_stock">Cotizar solo con stock</label>
                    <input autofocus type="checkbox" class="form-control float-center" id="cot_stock" name="cot_stock" style="vertical-align: bottom;">
                </div>
                <div class="form-group form-check col-md-2 col-sm-2 col-xs-12 text-center">
                    <label for="loc_pri_cot">Bloquear Precio de Venta en Cotizaciónes</label>
                    <input autofocus type="checkbox" class="form-control float-center" id="loc_pri_cot" name="loc_pri_cot" style="vertical-align: bottom;">
                </div>
                <div class="form-group form-check col-md-2 col-sm-2 col-xs-12 text-center">
                    <label for="loc_pri_inv">Bloquear Precio de Venta en Facturas, Notas de Entregas, Etc.</label>
                    <input autofocus type="checkbox" class="form-control float-center" id="loc_pri_inv" name="loc_pri_inv" style="vertical-align: bottom;">
                </div>
                <div class="form-group form-check col-md-2 col-sm-2 col-xs-12 text-center">
                    <label for="locked_invoice">Bloquear Facturación y Cotizaciónes</label>
                    <input autofocus type="checkbox" class="form-control float-center" id="locked_invoice" name="locked_invoice" style="vertical-align: bottom;">
                </div>
            </div>
        </div>
    </div>
</div>