<div class="card-body">
    <input type="hidden" id="id" name="id" value="<?php echo $r->id_vend  ?? '' ?>">
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="ced_vend">Cédula <span class="">*</span></label>
            <input autofocus type="text" class="form-control masked text-xs" id="ced_vend" name="ced_vend" placeholder="Eje. V-00.000.000" onchange="mayusculas(this)" title="Cédula de identidad">
            <span></span>
        </div>
        <div class="form-group col-md-5 col-sm-2 col-xs-12">
            <label for="nom_vend">Nombre <span class="">*</span></label>
            <input autofocus type="text" class="form-control text-xs" id="nom_vend" name="nom_vend" placeholder="Ingrese nombre" onchange="mayusculas(this)" title="Nombre(s)">
            <span></span>
        </div>
        <div class="form-group col-md-5 col-sm-2 col-xs-12">
            <label for="ape_vend">Apellido <span class="">*</span></label>
            <input autofocus type="text" class="form-control text-xs" id="ape_vend" name="ape_vend" placeholder="Ingrese apellido" onchange="mayusculas(this)" title="Apellido(s)">
            <span></span>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="email_vend">Email </label>
            <input autofocus type="email" class="form-control text-xs" id="email_vend" name="email_vend" placeholder="Ingrese email" title="Email">
            <span></span>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="fecing_vend">Fecha de ingreso </label>
            <input autofocus type="date" class="form-control text-xs" id="fecing_vend" name="fecing_vend" title="Ingrese email">
            <span></span>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="comi_vend">Comisión sobre ventas </label>
            <input autofocus type="text" class="form-control text-right text-xs camponumero" id="comi_vend" name="comi_vend" placeholder="Ingrese comisión" title="Comisión sobre ventas">
            <span></span>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="id_pais">País</label>
            <select autofocus name="id_pais" id="id_pais" class="form-control select2 select2bs4 text-xs">
            </select>
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="id_edo">Estado</label>
            <select autofocus name="id_edo" id="id_edo" class="form-control select2 select2bs4 text-xs">
            </select>
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="id_ciudad">Ciudad</label>
            <select autofocus name="id_ciudad" id="id_ciudad" class="form-control select2 select2bs4 text-xs">
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label for="dir_vend">Dirección</label>
            <textarea autofocus class="form-control text-xs" name="dir_vend" id="dir_vend" onkeyup="mayusculas(this);" cols="143" rows="2"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4 col-md-4 col-sm-4 col-xs-12">
            <label for="status">Estado</label>
            <select class="form-control custom-select rounded-0 text-xs" id="status" name="status">
            </select>
        </div>
    </div>
</div>