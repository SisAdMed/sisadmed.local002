<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-movements" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="movements-basic-tab" data-toggle="pill" href="#movements-basic" role="tab" aria-controls="movements-basic" aria-selected="true">Movimiento</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="movements-detail-tab" data-toggle="pill" href="#movements-detail" role="tab" aria-controls="movements-detail" aria-selected="true">Detalle</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="card-body">
    <input type="text" id="id" name="id" value="<?= $r->id_movinv ?? '' ?>" hidden>
    <input type="text" id="modo" name="modo" value="<?= $r->modo ?? '' ?>" hidden>
    <div class="tab-content" id="custom-movements-tabcontent">
        <div class="tab-pane fade show active" id="movements-basic" role="tabpanel" aria-labelledby="movements-basic-tab">
            <div class="row">
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label for="id_emp">Empresa <span class="required">*</span></label>
                    <select autofocus class="form-control custom-select rounded-0 text-xs" name="id_emp" id="id_emp"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_tInvLoad">Tipo de Movimiento</label>
                    <select autofocus class="form-control custom-select rounded-0 text-xs" name="id_tInvLoad" id="id_tInvLoad"></select>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="num_InvLoad">Número</label>
                    <input type="text" class="form-control text-right text-xs" name="num_InvLoad" id="num_InvLoad" readonly>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="fecha_comp">Fecha</label>
                    <input type="date" class="form-control text-right text-xs" name="fecha_comp" id="fecha_comp">
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_moneda">Moneda</label>
                    <select autofocus class="form-control custom-select rounded-0 text-xs" name="id_moneda" id="id_moneda"></select>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="tasa_cambio">Tasa de Cambio</label>
                    <input type="text" class="form-control text-right text-xs" name="tasa_cambio" id="tasa_cambio" readonly>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_alm">Almacén</label>
                    <select autofocus class="form-control custom-select rounded-0 text-xs" name="id_alm" id="id_alm"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-8 col-sm-8 col-xs-12">
                    <label for="descrip_InvLoad">Descripción</label>
                    <textarea id="descrip_InvLoad" name="descrip_InvLoad" class="form-control text-xs"></textarea>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="status">Estatus</label>
                    <select autofocus class="form-control custom-select rounded-0 text-xs" name="status" id="status"></select>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show" id="movements-detail" role="tabpanel" aria-labelledby="movements-detail-tab">
            <input type="text" name="item" id="item" hidden>
            <div class="row">
             <div class="form-group col-md-4 col-sm-4 col-xs-12">
                <label for="archivo_car_invent">Seleccionar archivo</label>
                <input type="file" class="form-control text-xs" name="archivo_car_invent" id="archivo_car_invent">
            </select>
        </div>
        <div class="form-group col-md-12 col-sm-12 col-xs-12 text-xs">
            <table class="display responsive nowrap table table-hover text-xs" style="width:100%;">
                <thead>
                    <th>Item</th>
                    <th>Producto</th>
                    <th>Ubicación</th>
                    <th>Lote</th>
                    <th>Fecha Venc.</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>
                </thead>
                <tbody id="tblInvMovDet" name="tblInvMovDet"></tbody>
                <tfoot>
                   <th>Item</th>
                    <th>Producto</th>
                    <th>Ubicación</th>
                    <th>Lote</th>
                    <th>Fecha Venc.</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>
                </tfoot>
            </table>
            <p><input type="button" id="btnAddRow" name="btnAddRow" class="btn btn-primary text-xs" value="Agregar nuevo registro" /></p>
        </div>
    </div>
</div>
</div>
</div>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/Views/Productos/modal_Productos.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/Views/Ubicaciones/modal_Ubicaciones.php';
?>