<div class="row"> 
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="module-configban" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="module-configban-general-tab" data-toggle="pill" href="#module-configban-general" role="tab" aria-controls="module-configban-general" aria-selected="true">Configuración</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="module-values-tab" data-toggle="pill" href="#module-values" role="tab" aria-controls="module-values" aria-selected="true">I.G.T.F</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="card-body">
    <div class="tab-content" id="module-confifac-general-tabcontent">
        <input type="hidden" name="id" id="id" value="<?= $r->id_config ?? '' ?>">
        <div class="tab-pane fade show active" id="module-configban-general" role="tabpanel" aria-labelledby="module-configban-tab">
            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-xs-12">
                    <label for="id_emp">Empresa</label>
                    <select autofocus name="id_emp" id="id_emp" class="form-control"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group-col-sm-4 col-md-4 col-xs-12">
                    <label for="id_bancon_CXC">Concepto de Cobranzas</label>
                    <input type="hidden" id="id_bancon_CXC" name="id_bancon_CXC">
                    <div class="input-group">
                        <input type="text" class="form-control text-xs" id="nom_bancon_CXC" name="nom_bancon_CXC" readonly>
                        <div class="input-group-append text-xs">
                            <span class="input-group-text  text-xs"><a href="#" data-toggle="modal" data-target="#modal-BanConceptos" class="tipo_banconcep" data-id="cob" title=" Buscar y seleccionar Conceptos Bancarios"><i class="fas fa-search text-xs"></i></a></span>
                        </div>
                    </div>
                </div>
                <div class="form-group-col-sm-4 col-md-4 col-xs-12">
                    <label for="id_bancon_CXP">Concepto de Pagos</label>
                    <input type="hidden" id="id_bancon_CXP" name="id_bancon_CXP">
                    <div class="input-group">
                        <input type="text" class="form-control text-xs " id="nom_bancon_CXP" name="nom_bancon_CXP" readonly>
                        <div class="input-group-append text-xs">
                            <span class="input-group-text  text-xs"><a href="#" data-toggle="modal" data-target="#modal-BanConceptos" class="tipo_banconcep" data-id="pag" title=" Buscar y seleccionar Conceptos Bancarios"><i class="fas fa-search text-xs"></i></a></span>
                        </div>
                    </div>
                </div>
                <div class="form-group-col-sm-4 col-md-4 col-xs-12">
                    <label for="id_bancon_RETIVA">Concepto de Retención de IVA Clientes</label>
                    <input type="hidden" id="id_bancon_RETIVA" name="id_bancon_RETIVA">
                    <div class="input-group">
                        <input type="text" class="form-control text-xs " id="nom_bancon_RETIVA" name="nom_bancon_RETIVA" readonly>
                        <div class="input-group-append text-xs">
                            <span class="input-group-text  text-xs"><a href="#modal-BanConceptos" data-toggle="modal" data-id="ret" class="tipo_banconcep" title=" Buscar y seleccionar Conceptos Bancarios"><i class="fas fa-search text-xs"></i></a></span>
                        </div>
                    </div>
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
    </div>
</div>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/Views/BanConceptos/modal_BanConceptos.php';
?>