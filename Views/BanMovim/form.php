<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="bank-movement" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="customer-banmovim-tab" data-toggle="pill" href="#customer-banmovim" role="tab" aria-controls="customer-banmovim" aria-selected="true">Principal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="customer-bancxc-tab" data-toggle="pill" href="#customer-bancxc" role="tab" aria-controls="customer-bancxc" aria-selected="true">Cuentas por Cobrar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="customer-bancxp-tab" data-toggle="pill" href="#customer-bancxp" role="tab" aria-controls="customer-bancxp" aria-selected="true">Cuentas por Pagar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="customer-bandet-tab" data-toggle="pill" href="#customer-bandet" role="tab" aria-controls="customer-bandet" aria-selected="true">Detalles</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="card-body text-xs">
    <div class="tab-content" id="bank-movement-tabcontent">
        <div class="tab-pane fade show active" id="customer-banmovim" role="tabpanel" aria-labelledby="customer-banmovim-tab">
            <input type="hidden" name="id" id="id" value="<?= $r->id_banmov ?? '' ?>">
            <input type="hidden" name="item" id="item">
            <input type="hidden" name="efecto" id="efecto">
            <input type="hidden" name="cxtmo" id="cxtmo">
            <div class="row">
                <div class="form-group col-md-12 col-sm-12 col-xs-12 text-xs">
                    <label for="id_emp">Empresa</label>
                    <select name="id_emp" id="id_emp" class="form-control text-xs" title="Empresa"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-3 col-sm-3 col-xs-12 text-xs">
                    <label for="id_bantmo">Tipo</label>
                    <select name="id_bantmo" id="id_bantmo" class="form-control text-xs"></select>
                </div>
                <div class="form-group col-md-3 col-sm-3 col-xs-12 text-xs">
                    <label for="">Cuenta</label>
                    <select name="id_bancue" id="id_bancue" class="select2 text-xs"></select>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12 text-xs">
                    <label for="fecha_comp">Fecha</label>
                    <input type="date" id="fecha_comp" name="fecha_comp" class="form-control text-xs">
                </div>

                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="num_banmov">Número</label>
                    <input type="number" id="num_banmov" name="num_banmov" class="form-control text-xs text-right">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_moneda">Moneda</label>
                    <select name="id_moneda" id="id_moneda" class="form-control text-xs"></select>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="tasa_cambio">Tasa</label>
                    <input type="text" name="tasa_cambio" id="tasa_cambio" class="form-control text-right text-xs" readonly />
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-12 benef_banmov">
                    <label for="benef_banmov">Beneficiario</label>
                    <input type="text" name="benef_banmov" id="benef_banmov" class="form-control text-xs" style="text-transform: uppercase;" />
                </div>
                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                    <label for="des_banmov">Descripción</label>
                    <textarea id="des_banmov" name="des_banmov" class="form-control text-xs" rows="3"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-3 col-sm-3 col-xs-12 text-xs">
                    <label for="Status">Status</label>
                    <select name="status" id="status" class="form-control text-xs"></select>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show" id="customer-bandet" role="tabpanel" aria-labelledby="customer-bandet-tab">
            <div class="dt-table">
                <table id="tbl_banmovin" class="table table-striped table-bordered table-condensed table-hover text-xs" style="width:100%">
                    <thead>
                        <th>Item</th>
                        <th>Concepto</th>
                        <th>Auxiliar</th>
                        <th>Monto</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody id='tbl_banmovin_det'></tbody>
                    <tfoot>
                        <th></th>
                        <th></th>
                        <th class="text-right">Total:</th>
                        <th><input type="text" class="form-control text-right text-xs" id="tmon_mov" name="tmon_mov" readonly></th>>
                        <th>
                            <center><button type="button" id="btn_agregate" class="btn btn-primary text-xs" title="Agregar detalle">Agregar</button></center>
                        </th>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="tab-pane fade show" id="customer-bancxc" role="tabpanel" aria-labelledby="customer-bancxc-tab">
            <div class="row">
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="id_cli">Nombre de Cliente <span class="required">*</span></label>
                    <input type="hidden" id="id_cli" name="id_cli">
                    <div class="input-group">
                        <input type="text" class="form-control text-xs" id="nom_cli" name="nom_cli" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-clientes" title="Buscar y seleccionar cliente"><i class="fas fa-search"></i></a></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <div class="dt-table">
                        <table id="tblSeatDetail" class="table table-striped table-bordered table-condensed table-hover text-xs" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th class="text-right">Item</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th class="text-right">Número</th>
                                    <th class="text-center">Fec. Emis.</th>
                                    <th class="text-center">Fec. Venc.</th>
                                    <th class="text-center">Moneda</th>
                                    <th class="text-right">Tasa</th>
                                    <th class="text-right result_mon_doc">Monto</th>
                                    <th class="text-right">Saldo</th>
                                    <th class="text-right">Cancelar</th>
                                    <th class="text-right">Ret IVA</th>
                                    <th class="text-right">Comprobante</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tbody"></tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right">Total:</td>
                                    <th class="text-right"></th>
                                    <th class="text-right"></th>
                                    <th><input type="text" class="form-control text-right text-xs" readonly id="tot_can_tbl" name="tot_can_tbl" /></th>
                                    <th><input type="text" class="form-control text-right text-xs" readonly id="tot_ret_tbl" name="tot_ret_tbl"></th>
                                    <th></th>
                                    <th class="text"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 com-sm-12 col-xs-12"></div>
                <input type="button" class="btn btn-primary text-xs newdetail" value="Nuevo registro">
            </div>
        </div>
        <div class="tab-pane fade show" id="customer-bancxp" role="tabpanel" aria-labelledby="customer-bancxp-tab">
            <div class="row">
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="id_ent">Nombre de Proveedor <span class="required">*</span></label>
                    <input type="hidden" id="id_ent" name="id_ent">
                    <div class="input-group">
                        <input type="text" class="form-control text-xs" id="nom_ent" name="nom_ent" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-Proveedores" title="Buscar y seleccionar Proveedor"><i class="fas fa-search"></i></a></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 col-sm-12 col xs 12">
                    <div class="dt-table">
                        <table id="tblSeatDetail_cxp" class="table table-striped table-bordered table-condensed table-hover text-xs" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th class="text-right">Item</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th class="text-right">Número</th>
                                    <th class="text-center">Fec. Emis.</th>
                                    <th class="text-center">Fec. Venc.</th>
                                    <th class="text-center">Moneda</th>
                                    <th class="text-right">Tasa</th>
                                    <th class="text-right result_mon_doc">Monto</th>
                                    <th class="text-right">Saldo</th>
                                    <th class="text-right">Cancelar</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right">Total:</td>
                                    <th class="text-right"></th>
                                    <th class="text-right"></th>
                                    <th><input type="text" class="form-control text-right text-xs tot_movim" readonly id="tot_can_tbl_cxp" name="tot_can_tbl_cxp" /></th>
                                    <th class="text"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 com-sm-12 col-xs-12"></div>
                <input type="button" class="btn btn-primary text-xs newdetail" value="Nuevo registro">
            </div>
        </div>
    </div>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/BanConceptos/modal_BanConceptos.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Clientes/modal_Clientes.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Proveedores/modal_Proveedores.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/AuxiliarCtb/modal_AuxiliaresCtb.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/CXCDocument/modal_doc_pen_cxc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/CXPDocument/modal_doc_pen_cxp.php';
?>