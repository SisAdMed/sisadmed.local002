<div class="card-body">
    <input type="hidden" id="id" name="id" value="<?= $r->id_cot ?? '' ?>">
    <input type="hidden" id="ori" name="ori" value="<?= $_SESSION['ori'] ?>">
    <input type="text" name="mod" id="mod" value="O" hidden>
    <div class="row">
        <div class="form-group col-md-6 col-sm-6 col-xs-12">
            <label for="id_emp">Empresa <span class="required">*</span></label>
            <select name="id_emp" id="id_emp" class="form-control text-xs" required>
            </select>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 ">
            <label for="fuente">Fuente</label>
            <select name="fuente" id="fuente" class="form-control text-xs"></select>
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12 ">
            <label for="origen">Origen</label>
            <select name="origen" id="origen" class="form-control select2 select2bs4 text-xs"></select>
        </div>

    </div>
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="id_tdo">Tipo <span class="required">*</span></label>
            <select name=" id_tdo" id="id_tdo" class="form-control text-xs"></select>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="num_tdo">Número <span class="required">*</span></label>
            <input type="text" class="form-control text-right text-xs" id="num_tdo" name="num_tdo">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 ori">
            <label for="num_control">Control <span class="required">*</span></label>
            <input type="text" class="form-control text-right text-xs" id="num_control" name="num_control">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="fecha_comp">Fecha registro <span class="required">*</span></label>
            <input type="date" id="fecha_venci" name="fecha_venci" hidden>
            <input type="date" class="form-control text-xs" id="fecha_comp" name="fecha_comp">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="">Fecha factura <span class="required">*</span></label>
            <input type="date" class="form-control text-xs" id="fec_fact" name="fec_fact">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="id_moneda">Moneda</label>
            <select name="id_moneda" id="id_moneda" class="form-control text-xs"></select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="tasa_cambio">Tasa</label>
            <input type="text" class="form-control text-right text-xs" name="tasa_cambio" id="tasa_cambio" readonly>
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="id_cli">Proveedor <span class="required">*</span></label>
            <input type="text" id="id_cli" name="id_cli" hidden>
            <div class="input-group">
                <input type="text" class="form-control text-xs" id="nom_cli" name="nom_cli" readonly>
                <div class="input-group-append">
                    <span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-Proveedores" title="Buscar y seleccionar Proveedor"><i class="fas fa-search"></i></a></span>
                </div>
            </div>
        </div>
        <div class="form-group col-md-4-col-sm-4 col-xs-12 especial_contrib ori">
            <label for="id_retiva">Retención de Iva</label>
            <select name="id_retiva" id="id_retiva" class="form-control text-xs"></select>
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
    <div class="row">
        <div class="form-group col-md-12 col-sm-12 col-xs-12 text-xs">
            <table id="tblDetalle" name="tblDetalle" class="display responsive nowrap table table-hover text-xs" style="width:100%;">
                <thead>
                    <th class="text-right">Item</th>
                    <th>Descripción</th>
                    <th class="text-right">Cantidad</th>
                    <th>Lote</th>
                    <th>Fec.Venc</th>
                    <th class="text-right">Uni.Com</th>
                    <th class="text-right">PVP Uni.</th>
                    <th class="text-right">Precio Compra</th>
                    <th class="text-center">IVA</th>
                    <th class="text-right">Sub-Total</th>
                    <th class="text-center">Acción</th>
                </thead>
                <tbody id="cuerpoTablaDetalle" name="cuerpoTablaDetalle">
                </tbody>
                <tfooter>
                    <th class="text-right">Item</th>
                    <th>Descripción</th>
                    <th class="text-right">Cantidad</th>
                    <th>Lote</th>
                    <th>Fec.Venc</th>
                    <th class="text-right">Uni.Com</th>
                    <th class="text-right">PVP Uni.</th>
                    <th class="text-right">Precio Compra</th>
                    <th class="text-center">IVA</th>
                    <th class="text-right">Sub-Total</th>
                    <th class="text-center">Acción</th>
                </tfooter>
            </table>
        </div>
    </div>
    <div class="center">
        <button type="button" class="btn btn-primary btn-sm text-xs" onclick="agregarDetalleFactura();">+ Agregar Detalle</button>
    </div>
</div>
<div class="loader">
    <img src="<?= IMG . '/ajax-loading.gif'  ?>" />
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Proveedores/modal_Proveedores.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Productos/modal_Productos.php';
?>