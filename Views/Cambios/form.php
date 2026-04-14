<div class="card-body">
    <input type="hidden" name="id" value="<?php echo $r->id_cambio  ?? '' ?>">
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="fecha_cambio">Fecha <span class="required">*</span></label>
            <input autofocus type="date" class="form-control" id="fecha_cambio" name="fecha_cambio" placeholder="Ingrese fecha" required value="<?php echo $r->fecha_cambio  ?? '' ?>">
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="id_moneda">Moneda <span class="required">*</span> </label>
            <select class="custom-select rounded-0" id="id_moneda" name="id_moneda" required>
                <?php
                SelMonedas($r->id_moneda ?? '');
                ?>
            </select>
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
           <label for="cambio_compra">Compra <span class="required">*</span></label>
           <input autofocus type="number" step="0.00000001" class="form-control text-right" id="cambio_compra" name="cambio_compra" placeholder="Ingrese valor de compra" required value="<?php echo $r->cambio_compra  ?? '' ?>" step>
       </div>
       <div class="form-group col-md-3 col-sm-3 col-xs-12">
           <label for="cambio_venta">Venta <span class="required">*</span></label>
           <input autofocus type="number" step="0.00000001" class="form-control text-right" id="cambio_venta" name="cambio_venta" placeholder="Ingrese valor de venta" required value="<?php echo $r->cambio_venta  ?? '' ?>">
       </div>
    </div>
</div>