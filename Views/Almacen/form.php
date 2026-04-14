<div class="card-body">
    <input type="hidden" name="id" id="id" value="<?php echo $r->id_alm  ?? '' ?>">
    <div class="row">
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label for="id_emp">Empresa <span class="required">*</span></label>
            <select class="custom-select rounded-0 text-xs" name="id_emp" id="id_emp"></select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="cod_alm">Código<span class="required">*</span></label>
            <input type="text" class="form-control  text-xs" id="cod_alm" name="cod_alm" placeholder="Ingrese código">
        </div>
        <div class="form-group col-md-10 col-sm-10 col-xs-12">
            <label for="nom_alm">Nombre</label><span class="required">*</span>
            <input type="text" class="form-control  text-xs" id="nom_alm" name="nom_alm" placeholder="Ingrese nombre" style="text-transform: uppercase;">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="con_alm">Contacto</label>
            <input type="text" class="form-control  text-xs" id="con_alm" name="con_alm" placeholder="Ingrese nombre de contacto">
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="email_alm">Email</label>
            <input type="email" class="form-control text-xs" id="email_alm" name="email_alm" placeholder="Ingrese email">
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="tel_alm">Teléfono</label>
            <input type="text" class="form-control text-xs" id="tel_alm" name="tel_alm" placeholder="Ingrese teléfono">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label for="dir_alm" class="form-label">Dirección</label>
            <textarea class="form-control text-xs" id="dir_alm" name="dir_alm" rows="3" placeholder="Ingrese dirección" style="text-transform: uppercase;"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="id_cli">Nombre de Cliente <span class="required">*</span></label>
            <input type="hidden" id="id_cli" name="id_cli">
            <div class="input-group">
                <input type="text" class="form-control text-xs" id="nom_cli" name="nom_cli" readonly>
                <div class="input-group-append">
                    <span class="input-group-text text-xs"><a href="#" data-toggle="modal" data-target="#modal-clientes" title="Buscar y seleccionar cliente"><i class="fas fa-search text-xs"></i></a></span>
                </div>
            </div>
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="status">Estado </label>
            <select class="form-control custom-select rounded-0 text-xs" id="status" name="status"></select>
        </div>
    </div>
    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Clientes/modal_Clientes.php';
    ?>