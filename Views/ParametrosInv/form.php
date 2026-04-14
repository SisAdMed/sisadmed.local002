<?php
    $marcadoval_min = 'unchecked';
    if(isset($r)){
        if($r->val_min == 1){
            $marcadoval_min = 'checked';
        }
    }
?>
<section class="content">
    <!-- Tabla -->
    <div class="card-body">
        <input type="hidden" id="id"            name="id"           value="<?= $r->id ?? '' ?>">
        <input type="hidden" id="id_empr"       name="id_empr"      value="<?= $r->id_emp ?? '' ?>">
        <div class="row">
            <div class="form-group col-md-4-12 col-sm-12 col-xs-12">
                <label for="id_emp">Empresa <span>*</span></label>
                <select autofocus name="id_emp" id="id_emp" class="form-control" required></select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="val_min">Verificar existencia en línea</label>
                <input autofocus type="checkbox" class="form-control" id="val_min" name="val_min" style="vertical-align: bottom;" <?php echo $marcadoval_min ?> >
            </div>
            <div class="form-group col-md-2 col-sm-2 col-xs-12">
                <label for="costo_pie3">Costo del pie cúbico</label>
                <input type="number" id="costo_pie3" name="costo_pie3" class="form-control text-right" value="<?php echo number_format($r->costo_pie3, 4) ?? '' ?>">
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
