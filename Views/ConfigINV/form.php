<div class="card-body">
    <input type="hidden" id="id" name="id" value="<?php echo $r->id_config ?? '' ?>">
    <div class="row">
        <div class="form-group col-sm-12 col-md-12 col-xs-12">
            <label for="id_emp">Empresa</label>
            <select autofocus name="id_emp" id="id_emp" class="form-control"></select>
        </div>
    </div>
    <div class="row">
        <div class="form-group-col-sm-4 col-md-4 col-xs-12">
            <label for="id_alm">Almácen por defecto</label>
            <select autofocus name="id_alm" id="id_alm" class="form-control select2 select2bs4"></select>
        </div>
        <div class="form-group-col-sm-4 col-md-4 col-xs-12">
            <label for="id_ubi">Ubicación por defecto</label>
            <select autofocus name="id_ubi" id="id_ubi" class="form-control select2 select2bs4"></select>
        </div>
        <div class="form-group-col-sm-4 col-md-4 col-xs-12">
            <label for="id_mov_ent">Tipo de Movimiento de Entrada</label>
            <select autofocus name="id_mov_ent" id="id_mov_ent" class="form-control select2 select2bs4"></select>
        </div>
    </div>
    <div class="row">
        <div class="form-group-col-sm-4 col-md-4 col-xs-12">
            <label for="id_mov_sal">Tipo de Movimiento de Salida</label>
            <select autofocus name="id_mov_sal" id="id_mov_sal" class="form-control select2 select2bs4"></select>
        </div>
        <div class="form-group-col-sm-4 col-md-4 col-xs-12">
            <label for="id_mov_tra_ent">Tipo de Movimiento de Transferencia de Entrada</label>
            <select autofocus name="id_mov_tra_ent" id="id_mov_tra_ent" class="form-control select2 select2bs4"></select>
        </div>
        <div class="form-group-col-sm-4 col-md-4 col-xs-12">
            <label for="id_mov_tra_sal">Tipo de Movimiento de Transferencia de Salida</label>
            <select autofocus name="id_mov_tra_sal" id="id_mov_tra_sal" class="form-control select2 select2bs4"></select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-2 col-md-2 col-xs-12">
            <label for="status">Status</label>
            <select autofocus name="status" id="status" class="form-control"></select>
        </div>
    </div>
</div>