<div class="card-body">
    <input type="hidden" name="id" id="id" value="<?= $r->id ?? '' ?>">
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="fecha_proceso">Fecha de proceso <span>*</span></label>
            <input type="date" class="form-control" name="fecha_proceso" id="fecha_proceso" title="Indique la fecha de proceso">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="fecha_envio">Fecha de envío</label>
            <input type="date" class="form-control" name="fecha_envio" id="fecha_envio" title="Indique la fecha de envío">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="fecha_recibido">Fecha de recibido</label>
            <input type="date" class="form-control" name="fecha_recibido" id="fecha_recibido" title="Indique la fecha de recibido">
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="proveedor">Proveedor</label>
            <input type="text" class="form-control" name="proveedor" id="proveedor" placeholder="Indique el proveedor" title="Indique el proveedor">
        </div>
            <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="proveedor">Línea</label>
            <input type="text" class="form-control" name="linea" id="linea" placeholder="Indique la línea" title="Indique la línea">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="costo_pie3">Costo del pie cúbico</label>
            <input type="number" class="form-control" name="costo_pie3" id="costo_pie3">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="costo_envio">Costo de envío</label>
            <input type="number" class="form-control" name="costo_envio" id="costo_envio">
        </div>
        <div class="form-group col-md-8 col-sm-8 col-xs-12">
            <label for="observacion">Óbservacíones</label>
            <textarea class="form-control" name="observacion" id="observacion" rows="1"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="center">
             <button type="button" class="btn btn-primary btn-sm" onclick="agregarDetalleNotiEnvio();">+ Agregar Detalle</button>
        </div>
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <table name="tblNotEnvio" id="tblNotEnvio" class="display responsive nowrap table table-hover">
                <thead>
                    <th>Fec.Compra</th>
                    <th>Proveedor</th>
                    <th>Producto</th>
                    <th>Referencia</th>
                    <th>Descripción</th>
                    <th class="text-right">Uni.Empaq.</th>
                    <th class="text-right">Cant.Caj.</th>
                    <th class="text-right">Pre.Uni.</th>
                    <th class="text-right">Pre.Caj.</th>
                    <th class="text-right">Costo Unid.</th>
                </thead>
                <tbody name="TblDetNotEnvio" id="TblDetNotEnvio"></tbody>
                <tfooter>
                    <th>Fec.Compra</th>
                    <th>Proveedor</th>
                    <th>Producto</th>
                    <th>Referencia</th>
                    <th>Descripción</th>
                    <th class="text-right">Uni.Empaq.</th>
                    <th class="text-right">Cant.Caj.</th>
                    <th class="text-right">Pre.Uni.</th>
                    <th class="text-right">Pre.Caj.</th>
                    <th class="text-right">Costo Unid.</th>
                </tfooter>
            </table>
        </div>
    </div>
</div>