<div class="card">
    <div class="card-header">
        <input type="hidden" name="id" id="id" value="<?php echo $r->id_comp  ?? '' ?>">
        <div class="row">
            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                <label for="id_emp">Empresa <span class="required">*</span></label>
                <select class="form-control text-xs" name="id_emp" id="id_emp"></select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                <label for="id_tipcom_nombre">Tipo Cbte. <span>*</span></label>
                <select name="id_tipcom" id="id_tipcom" class="form-control text-xs"></select>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="num_comp">Número <span>*</span></label>
                <input type="number" class="form-control text-right text-xs" id="num_comp" name="num_comp">
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="fecha_comp">Fecha <span class="required">*</span></label>
                <input type="date" class="form-control text-xs" id="fecha_comp" name="fecha_comp" placeholder="Ingrese fecha">
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="id_moneda">Moneda <span class="required">*</span></label>
                <select class="form-control text-xs" name="id_moneda" id="id_moneda"></select>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="tasa_cambio">Tasa de Cambio <span class="required">*</span></label>
                <input type="text" class="form-control text-right text-xs" id="tasa_cambio" name="tasa_cambio" placeholder="Ingrese valor del cambio">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-10 col-sm-10 col-xs-12">
                <label for="desc_comp">Descripción <span class="required">*</span></label>
                <input type="text" class="form-control text-xs mayusculas" id="desc_comp" name="desc_comp" placeholder="Ingrese una descripción">
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control text-xs"></select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title text-xs text-center">Detalle de Comprobante</h3>
            </div>
        </div>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <input type="number" name="item" id="item" hidden>
            <table id="tblAsiento" class="table table-striped table-bordered table-condensed table-hover text-xs" style="width:100%">
                <thead class="btn-primary">
                    <tr>
                        <th class="text-right">Item</th>
                        <th>Cuenta Contable</th>
                        <th>Auxiliar Contable</th>
                        <th>Descripción detalle</th>
                        <th>Tipo</th>
                        <th class="text-right">Debe</th>
                        <th class="text-right">Haber</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tblAsientoDet"></tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right text-xs">Total Asiento Contable</th>
                        <th class="text-right"><input type="text" name="mondebe" id="mondebe" class="form-control text-right text-xs" readonly></th>
                        <th class="text-right"><input type="text" name="monhabe" id="monhabe" class="form-control text-right text-xs" readonly></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="center">
                <input type="button" id="btnAgregate" name="btnAgregate" class="btn btn-primary text-xs" value="Agregar nuevo registro" />
            </div>
        </div>
    </div>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/CuentasCtb/modal_CuentasCtb.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/AuxiliarCtb/modal_AuxiliaresCtb.php';
?>