<div class="card-body">
    <input type="text" name="id" id="id" value="<?= $r[0]['id_comint'] ?? '' ?>" hidden>
    <input type="hidden" name="id_emp" id="id_emp">
    <input type="hidden" name="id_moneda" id="id_moneda">
    <input type="hidden" name="item" id="item">
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="id_comint">Registro Nro.</label>
            <input type="text" id="id_comint" name="id_comint" class="form-control text-right text-xs" readonly>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="fecha_comint">Fecha de compra</label>
            <input type="date" id="fecha_comint" name="fecha_comint" class="form-control text-xs">
        </div>
        <div class="form-group col-md-6 col-sm-6 col-xs-12">
            <label for="id_provint">Proveedor</label>
            <select name="id_provint" id="id_provint" class="form-control text-xs"></select>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="status">Estado</label>
            <select name="status" id="status" class="form-control text-xs"></select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label for="descrip_compint">Observaciones</label>
            <textarea id="descrip_compint" name="descrip_compint" rows="1" cols="50" class="form-control  text-xs"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <table id="tblCompInt" class="table table striped table-bordered table condensed table-hover text-xs">
                <thead class="btn-primary">
                    <th>Producto</th>
                    <th>Referencia</th>
                    <th>Marca</th>
                    <th class="text-right">Unid de empaque</th>
                    <th class="text-right" style="width: 10%;">Cantidad</th>
                    <th class="text-right" style="width:10%">Precio</th>
                    <th class="text-right">Tot unidades</th>
                    <th class="text-right">Precio unitario</th>
                    <th class=text-right>Precio total</th>
                    <th>
                        <center>Acciones</center>
                    </th>
                </thead>
                <tbody id="tblCompIntDet"></tbody>
                <tfoot>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><input type="text" id="tmon_mov" name="tmon_mov" class="form-control text-xs text-right" readonly></th>
                    <th>
                        <center><button type="button" id="btnAgregateCompInt" class="btn btn-primary text-xs" title="Agregar producto">Agregar</button></center>
                    </th>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Productos/modal_Productos.php';
?>