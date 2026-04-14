<div class="card-body">
    <input type="hidden" name="id" value="<?php echo $r->id_menu  ?? '' ?>">
    <div class="row">
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="nombre_menu">Nombre <span class="required">*</span></label>
            <input type="text" class="form-control" id="nombre_menu" name="nombre_menu" placeholder="Ingrese nombre" required value="<?php echo $r->nombre_menu  ?? '' ?>">
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="desc_menu">Descripción </label>
            <input type="text" class="form-control" id="desc_menu" name="desc_menu" placeholder="Ingrese descripción" required value="<?php echo $r->desc_menu  ?? '' ?>">
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="padre_menu">Menú padre </label>
            <select class="form-control custom-select rounded-0 select2 select2bs4 rounded-0" id="padre_menu" name="padre_menu" required>
                <?php
                $rp = MenuModel::padre_menu();
                echo '<option value = "0">Seleccione...</option>';
                if(isset($rp)){
                    foreach ($rp as $value) {
                        if($r->padre_menu == $value->id_menu){
                            $selected = "selected";
                        }else{
                            $selected = "";
                        }
                        echo '<option '.$selected.' value="'.$value->id_menu.'">'.$value->nombre_menu.'</option>';
                        }
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="page_menu">Página </label>
            <input type="text" class="form-control" id="page_menu" name="page_menu" placeholder="Ingrese página" required value="<?php echo $r->page_menu  ?? '' ?>">
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="icono_menu">Icono </label>
            <input type="text" class="form-control" id="icono_menu" name="icono_menu" placeholder="Ingrese nombre de icono" required value="<?php echo $r->icono_menu  ?? '' ?>">
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="orden_menu">Orden </label>
            <input type="number" class="form-control" id="orden_menu" name="orden_menu" min= "1" placeholder="Ingrese orden" required value="<?php echo $r->orden_menu  ?? '' ?>">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6 col-md-6 col-sm-6 col-xs-12">
            <label for="status_menu">Estado </label>
            <select class="custom-select rounded-0" id="status_menu" name="status_menu" required>
                <?php
                if (isset($r)) {
                    status($r->status_menu);
                } else {
                    status('');
                }
                ?>
            </select>
        </div>
    </div>
</div>