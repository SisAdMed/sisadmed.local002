<div class="card">
    <div class="card-header">
        <input type="hidden" id="id" name="id" value="<?= $r->id_cot ?? '' ?>">
        <div class="row text-xs">
            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                <label for="id_emp">Empresa <span class="required">*</span></label>
                <select name="id_emp" id="id_emp" class="form-control custom-select rounded-0 text-xs">
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="id_tdo">Tipo <span class="required">*</span></label>
                <select name=" id_tdo" id="id_tdo" class="form-control custom-select rounded-0 text-xs"></select>
            </div>

            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="num_tdo">Número <span class="required">*</span></label>
                <input type="number" class="form-control text-right text-xs" id="num_tdo" name="num_tdo">
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="fecha_comp">Fecha <span class="required">*</span></label>
                <input type="date" class="form-control text-xs" id="fecha_comp" name="fecha_comp">
            </div>
            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                <label for="id_cli">Nombre de Cliente <span class="required">*</span></label>
                <input type="hidden" id="id_cli" name="id_cli">
                <div class="input-group">
                    <input type="text" class="form-control text-xs" id="nom_cli" name="nom_cli" readonly>
                    <div class="input-group-append">
                        <span class="input-group-text text-xs"><a href="#" data-toggle="modal" data-target="#modal-clientes" title="Buscar y seleccionar cliente"><i class="fas fa-search text-xs"></i></a></span>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="id_fab">Marca</label> 
                <select name="id_fab" id="id_fab" class="select2 select2bs4 text-xs" multiple style="width:100%">
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="id_moneda">Moneda</label>
                <select name="id_moneda" id="id_moneda" class="custom-select rounded-0 text-xs"></select>

            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="tasa_cambio">Tasa</label>
                <input type="text" name="tasa_cambio" id="tasa_cambio" class="form-control text-right text-xs">
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="id_vend">Vendedor</label>
                <select name="id_vend" id="id_vend" class="custom-select rounded-0 text-xs" required></select>
            </div>
            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                <label for="observa">Observaciones</label>
                <textarea name="observa" id="observa" class="form-control" cols="50"></textarea>
            </div>
        </div>
        <div class="row">
            <!--Moneda Foaranea-->
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-center foranea text-xs text-xs">
                <label for="sub_total" class="text-xs">Sub-Total USD</label>
                <input type="text" name="sub_total" id="sub_total" class="form-control text-right text-xs" readonly>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right foranea text-xs">
                <label for="iva" class="text-xs">IVA USD</label>
                <input type="text" name="iva" id="iva" class="form-control text-right text-xs" readonly>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right foranea text-xs">
                <label for="total_frm" class="text-xs">Total USD</label>
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
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right local text-xs">
                <label for="sub_totall" class=" text-xs">Sub-Total Bs.</label>
                <input type="text" name="sub_totall" id="sub_totall" class="form-control text-right text-xs" readonly>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right local text-xs">
                <label for="ival" class=" text-xs">IVA Bs.</label>
                <input type="text" name="ival" id="ival" class="form-control text-right text-xs" readonly>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right local text-xs">
                <label for="total_frml" class=" text-xs">Total Bs.</label>
                <input type="text" name="total_frml" id="total_frml" class="form-control text-right text-xs" readonly>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title text-xs text-center">Detalle de Cotización</h3>
            </div>
        </div>
        <input type="hidden" id="item" name="item">
        <div class="row">
            <div class="form-group col-md-12 col-sm-12 col-xs-12 text-xs">
                <table id="tblDetalle" name="tblDetalle" class="table table-striped table-bordered table-condensed table-hover text-xs compact" style="width:100%;">
                    <thead>
                        <th class="text-right">Item</th>
                        <th>Descripción</th>
                        <th class="text-right">Cantidad</th>
                        <th class="text-right">Stock</th>
                        <th class="text-right">Uni.Vta</th>
                        <th class="text-right">PVP Uni.</th>
                        <th class="text-right">Precio Vta</th>
                        <th class="text-right">Descuento</th>
                        <th style="width:10%" class="text-center">IVA</th>
                        <th class="text-right">Sub-Total</th>
                        <th class="text-center">Acción</th>
                    </thead>
                    <tbody id="cuerpoTablaDetalle" name="cuerpoTablaDetalle"></tbody>
                    <tfooter>
                        <th class="text-right">Item</th>
                        <th>Descripción</th>
                        <th class="text-right">Cantidad</th>
                        <th class="text-right">Stock</th>
                        <th class="text-right">Uni.Vta</th>
                        <th class="text-right">PVP Uni.</th>
                        <th class="text-right">Precio Vta</th>
                        <th class="text-right">Descuento</th>
                        <th style="width:10%" class="text-center">IVA</th>
                        <th class="text-right">Sub-Total</th>
                        <th class="text-center">Acción</th>
                    </tfooter>
                </table>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="center">
                <button type="button" class="btn btn-primary btn-sm" onclick="agregarDetalleProductos('P');">+ Nuevo producto</button>
            </div>
        </div>
    </div>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Productos/modal_Productos.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/TipoDcto/modal_Descuentos.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Clientes/modal_Clientes.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Fabricantes/modal_fabricantes.php';
?>