<div class="card">
    <div class="card-header">
        <div class="row">
            <input type="text" name="id" id="id" value="<?= $r[0]->id_movement ?? '' ?>" hidden>
            <input type="text" name="movem_origen" id="movem_origen" value="CXP" hidden>
            <div class="form-group col-md-12 col-sm-12 col-xs-12 text-xs">
                <label for="id_emp">Empresa <span class="required">*</span></label>
                <select autofocus class="form-control text-xs" name="id_emp" id="id_emp"></select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-xs">
                <label for="id_tmocxp">Tipo de Movimiento</label>
                <select autofocus name="id_tmocxp" id="id_tmocxp" class="form-control text-xs"></select>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-xs">
                <label for="movem_number">Número de Movimiento</label>
                <input autofocus type="number" name="movem_number" id="movem_number" class="form-control text-right text-xs">
            </div>
            <div class="form-group col-md-6 col-sm-6 col-xs-12 text-xs">
                <label for="id_ent">Nombre de Proveedor <span class="required">*</span></label>
                <input type="hidden" id="id_ent" name="id_ent">
                <div class="input-group">
                    <input type="text" class="form-control text-xs" id="nom_ent" name="nom_ent" readonly>
                    <div class="input-group-append">
                        <span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-Proveedores" title="Buscar y seleccionar Proveedor"><i class="fas fa-search"></i></a></span>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-xs">
                <label for="fecha_comp">Fecha emisión</label>
                <input autofocus type="date" name="fecha_comp" id="fecha_comp" class="form-control text-xs">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-1 col-sm-1 col-xs-12 text-xs">
                <label for="id_moneda">Moneda</label>
                <select autofocus class="form-control custom-select rounded-0 text-xs" name="id_moneda" id="id_moneda"></select>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-xs">
                <label for="tasa_cambio">Tasa de cambio</label>
                <input autofocus type="text" name="tasa_cambio" id="tasa_cambio" class="form-control text-right text-xs">
            </div>
            <div class="form-group col-md-7 col-sm-7 col-xs-12 text-xs">
                <label for="movem_descrip">Descripción</label>
                <textarea name="movem_descrip" id="movem_descrip" class="form-control text-xs"></textarea>
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12 text-xs">
                <label for="status">Estatus</label>
                <select autofocus class="form-control custom-select rounded-0 text-xs" name="status" id="status"></select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title text-xs">Detalle de movimiento</h3>
            </div>
        </div>
        <input type="text" name="item" id="item" hidden>
        <div class="row">
            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                <table id="tblSeatDetail_cxp" name="tblSeatDetail_cxp" class="table table-striped table-bordered table-condensed table-hover text-xs" style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="width: 5px;">Id</th>
                            <th>Item</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th class="text-right">Número</th>
                            <th class="text-center">Fec. Emisión</th>
                            <th class="text-center">Fec. Vencim.</th>
                            <th>Moneda</th>
                            <th class="text-right">Tasa</th>
                            <th class="text-right">Monto</th>
                            <th class="text-right">Saldo</th>
                            <th class="text-right">Cancelar</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="11" class="text-right">TotaL:</th>
                            <th><input type="button" class="form-control text-right text-xs" readonly id="tot_can_tbl_cxp" name="tot_can_tbl_cxp" /></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                <input type="button" id="newdetail" name="newdetail" class="btn btn-primary text-xs" value="Nuevo registro">
            </div>
        </div>
    </div>
</div>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Proveedores/modal_Proveedores.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/CXPDocument/modal_doc_pen_cxp.php';
?>