<div class="card-body">
    <input type="hidden" name="id" value="<?php echo $r->id_moneda  ?? '' ?>">
    <div class="row">
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="id_pais">País <span>*</span> </label>
            <select class="custom-select rounded-0" id="id_pais" name="id_pais" required>
                <?php
                $pais = MonedasModel::selPaises();
                echo '<option value="">Seleccione...</option>';
                foreach ($pais as $value) {
                    if($r->id_pais == $value->id_pais){
                        echo '<option selected value="'.$value->id_pais.'">'.$value->nombre_pais.'</option>';
                    }else{
                        echo '<option value="'.$value->id_pais.'">'.$value->nombre_pais.'</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="codigo_moneda">Código <span class="required">*</span></label>
            <input type="text" class="form-control" id="codigo_moneda" onkeyup="mayusculas(this)" name="codigo_moneda" placeholder="Ingrese código" required value="<?php echo $r->codigo_moneda  ?? '' ?>">
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="nombre_moneda">Nombre </label>
            <input type="text" class="form-control" id="nombre_moneda" name="nombre_moneda" placeholder="Ingrese nombre" required value="<?php echo $r->nombre_moneda  ?? '' ?>">
        </div>
    </div>
</div>