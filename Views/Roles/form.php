<div class="card-body">
    <input type="hidden" name="id" value="<?php echo $rol->id_rol  ?? '' ?>">
    <div class="row">
        <div class="form-group col-md-6 col-sm-6 col-xs-12">
            <label for="nombre_rol">Nombre <span class="required">*</span></label>
            <input type="text" class="form-control" id="nombre_rol" name="nombre_rol" placeholder="Ingrese nombre" required value="<?php echo $rol->nombre_rol  ?? '' ?>">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6 col-md-6 col-sm-6 col-xs-12">
            <label for="selstatus">Estado </label>
            <select class="custom-select rounded-0" id="selstatus" name="selstatus" required>
                <?php
                    if (isset($rol)) {
                         status($rol->status_rol);
                    }else{
                        status(' ');
                    }
                ?>
            </select>
        </div>
    </div>
</div>