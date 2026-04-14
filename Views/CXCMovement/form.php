<div class="card">
    <div class="card-header">
        <div class="row">
            <input type="text" name="id" id="id" value="<?= $r[0]->id_movement ?? '' ?>" hidden>
            <input type="text" name="movem_origen" id="movem_origen" value="CXC" hidden>
            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                <label for="id_emp">Empresa <span class="required">*</span></label>
                <select autofocus class="form-control custom-select rounded-0 text-xs" name="id_emp" id="id_emp"></select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="id_tmocxc">Tipo de Movimiento</label>
                <select autofocus name="id_tmocxc" id="id_tmocxc" class="form-control text-xs"></select>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="movem_number">Número de Movimiento</label>
                <input autofocus type="number" name="movem_number" id="movem_number" class="form-control text-right text-xs">
            </div>
            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                <label for="nom_cli" class="text-xs">Nombre de Cliente <span class="required">*</span></label>
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
                <input autofocus type="date" name="fecha_comp" id="fecha_comp" class="form-control text-xs">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-1 col-sm-1 col-xs-12">
                <label for="id_moneda">Moneda</label>
                <select autofocus class="form-control custom-select rounded-0 text-xs" name="id_moneda" id="id_moneda"></select>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="tasa_cambio">Tasa de cambio</label>
                <input autofocus type="text" name="tasa_cambio" id="tasa_cambio" class="form-control text-right text-xs">
            </div>
            <div class="form-group col-md-7 col-sm-7 col-xs-12">
                <label for="movem_descrip">Descripción</label>
                <textarea name="movem_descrip" id="movem_descrip" class="form-control text-xs"></textarea>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="status">Estatus</label>
                <select autofocus class="form-control custom-select rounded-0 text-xs" name="status" id="status"></select>
            </div>
        </div>
    </div>
    <div class="card-body"> 
        <div class="card card-primary text-center">
            <div class="card-header text-center">
                <h3 class="card-title text-xs text-center">Detalle de movimiento</h3>
            </div>
        </div>
        <input type="text" name="item" id="item" hidden>
        <div class="row">
            <div class="form-group col-md-12 col-sm-12 col-xs-12 text-xs">
                <table name="tblSeatDetail" class="table table-striped table-bordered table-condensed table-hover text-xs" style="width: 100%;">
                    <thead>
                        <th style="width:5px">Id</th>
                        <th>Item</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Número</th>
                        <th>Fecha Emis.</th>
                        <th>Fecha Venc.</th>
                        <th style="width: 30px">Moneda</th>
                        <th class="text-right">Tasa</th>
                        <th class="text-right result_mon_doc">Monto</th>
                        <th class="text-right">Saldo</th>
                        <th class="text-right">Cancelar</th>
                        <th class="text-right">Ret IVA</th>
                        <th class="text-right">Comprobante</th>
                        <th class="text-center">Acción</th>
                    </thead>
                    <tbody id="tbody"></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="11" class="text-right">Total:</th>
                            <th><input type="text" class="form-control text-right text-xs" readonly id="tot_can_tbl_cxc" name="tot_can_tbl_cxc" /></th>
                            <th><input type="text" class="form-control text-right text-xs" readonly id="tot_ret_tbl_cxc" name="tot_ret_tbl_cxc"></th>
                            <th></th>
                            <th class="text-center"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12 col-sm-12 col-xs-12"></div>
            <input type="button" id="newdetail" name="newdetail" class="btn btn-primary text-xs" value="Nuevo registro">
        </div>
    </div>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Clientes/modal_Clientes.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/CXCDocument/modal_doc_pen_cxc.php';
?>