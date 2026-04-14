<div class="card">
    <div class="card-header">
        <input type="text" name="id" id="id" value="<?= $r->id_cot ?? '' ?>" hidden>
        <div class="row">
            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                <label for="id_emp">Empresa <span class="required">*</span></label>
                <select class="form-control text-xs select2" name="id_emp" id="id_emp"></select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="id_tdo">Tipo de Documento</label>
                <select name="id_tdo" id="id_tdo" class="form-control text-xs select2"></select>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="num_tdo">Número de Documento</label>
                <input type="number" name="num_tdo" id="num_tdo" class="form-control text-right text-xs">
            </div>
               <div class="form-group col-md-1 col-sm-21col-xs-12" id="control">
                <label for="nro_control">Nro. Control</label>
                <input type="text" name="nro_control" id="nro_control" class="form-control text-right text-xs" readonly>
            </div>
            <div class="form-group col-md-3 col-sm-3 col-xs-12">
                <label for="" class="text-xs">Nombre de Cliente <span class="required">*</span></label>
                <input type="hidden" id="id_cli" name="id_cli">
                <div class="input-group">
                    <input type="text" class="form-control text-xs" id="nom_cli" name="nom_cli" readonly>
                    <div class="input-group-append text-xs">
                        <span class="input-group-text  text-xs"><a href="#" data-toggle="modal" data-target="#modal-clientes" title="Buscar y seleccionar cliente"><i class="fas fa-search text-xs"></i></a></span>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="fecha_comp">Fecha emisión</label>
                <input type="date" name="fecha_comp" id="fecha_comp" class="form-control text-xs ">
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="fecha_venci">Fecha vencimiento</label>
                <input type="date" name="fecha_venci" id="fecha_venci" class="form-control text-xs">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-1 col-sm-1 col-xs-12">
                <label for="id_moneda">Moneda</label>
                <select class="form-control text-xs select2" name="id_moneda" id="id_moneda"></select>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="tasa_cambio">Tasa de cambio</label>
                <input type="text" name="tasa_cambio" id="tasa_cambio" class="form-control text-right text-xs" readonly>
            </div>
            <div class="form-group col-md-3 col-sm-3 col-xs-12">
                <label for="descrip_cot">Descripción</label>
                <textarea name="descrip_cot" id="descrip_cot" class="form-control text-xs"></textarea>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12" id="afectado">
                <input type="hidden" id="id_afectado" name="id_afectado">
                <label for="doc_afectado">Documento afectado</label>
                <div class="input-group">
                    <input type="text" class="form-control text-xs" id="doc_afectado" name="doc_afectado">
                    <div class="input-group-append text-xs">
                        <span class="input-group-text text-xs"><a href="#" data-toggle="modal" data-target="#modal_DocAfectadoCXC" title="Buscar y seleccionar Documentos"><i class="fas fa-search text-xs"></i></a></span>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="status">Estatus</label>
                <select class="form-control text-xs select2" name="status" id="status"></select>
            </div>
        </div>
        <div class="row">
            <!--Moneda Foaranea-->
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-center foranea text-xs text-xs">
                <label for="sub_total" class="text-xs">Sub-Total</label>
                <input type="text" name="sub_total" id="sub_total" class="form-control text-right text-xs" readonly>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right foranea text-xs">
                <label for="iva" class="text-xs">IVA</label>
                <input type="text" name="iva" id="iva" class="form-control text-right text-xs" readonly>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right foranea text-xs">
                <label for="total_frm" class="text-xs">Total</label>
                <input type="text" name="total_frm" id="total_frm" class="form-control text-right text-xs" readonly>
            </div>
            <!--Moneda Foaranea mostrando Moneda local-->
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right foranea text-xs">
                <label for="sub_totalBs" class=" text-xs">Sub-Total Bs.</label>
                <input type="text" name="sub_totalBs" id="sub_totalBs" class="form-control text-right text-xs" readonly>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right foranea text-xs">
                <label for="ivaBs" class=" text-xs">IVA Bs.</label>
                <input type="text" name="ivaBs" id="ivaBs" class="form-control text-right text-xs" readonly>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right foranea text-xs">
                <label for="total_frmBs" class=" text-xs">Total Bs.</label>
                <input type="text" name="total_frmBs" id="total_frmBs" class="form-control text-right text-xs" readonly>
            </div>
            <!--Moneda Local-->
            <div class="form-group col-md-4 col-sm-4 col-xs-12 text-right local text-xs">
                <label for="sub_totall" class=" text-xs">Sub-Total</label>
                <input type="text" name="sub_totall" id="sub_totall" class="form-control text-right text-xs" readonly>
            </div>
            <div class="form-group col-md-4 col-sm-4 col-xs-12 text-right local text-xs">
                <label for="ival" class=" text-xs">IVA</label>
                <input type="text" name="ival" id="ival" class="form-control text-right text-xs" readonly>
            </div>
            <div class="form-group col-md-4 col-sm-4 col-xs-12 text-right local text-xs">
                <label for="total_frml" class=" text-xs">Total</label>
                <input type="text" name="total_frml" id="total_frml" class="form-control text-right text-xs" readonly>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title text-xs text-center">Detalle de Documento</h3>
            </div>
        </div>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <input type="number" name="item" id="item" value="0" hidden >
            <table id="tblDetalle" name="tblDetalle" class="table table-striped table-bordered table-condensed table-hover text-xs compact" style="width:100%;">
                <thead>
                    <th class="text-right">Item</th>
                    <th>Concepto</th>
                    <th>Auxiliar</th>
                    <th class="text-right">Monto</th>
                    <th>IVA</th>
                    <th class="text-right">Monto IVA</th>
                    <th>Total</th>
                    <th class="text-center">Acción</th>
                </thead>
                <tbody id="tblCxcDocument"></tbody>
            </table>
        </div>       
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="center">
                <input type="button" id="btnAddRow" name="btnAddRow" class="btn btn-primary text-xs" value="Agregar nuevo registro" />
            </div>
        </div>
    </div>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Clientes/modal_Clientes.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/ConcepCXC/modal_ConcepCXC.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/AuxiliarCtb/modal_AuxiliaresCtb.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/CXCDocument/modal_DocAfectadoCXC.php';
?>