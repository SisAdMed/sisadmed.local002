<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-companies" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="companies-basic-tab" data-toggle="pill" href="#companies-basic" role="tab" aria-controls="companies-basic" aria-selected="true">Empresas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="companies-dates-tab" data-toggle="pill" href="#companies-dates" role="tab" aria-controls="companies-dates" aria-selected="true">Configuración de fechas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="companies-logo-tab" data-toggle="pill" href="#companies-logo" role="tab" aria-controls="companies-logo" aria-selected="true">Logo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="companies-correo-tab" data-toggle="pill" href="#companies-correo" role="tab" aria-controls="companies-correo" aria-selected="true">Configuración de correo</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="card-body">
    <div class="tab-content" id="custom-companies-tabcontent">
        <div class="tab-pane fade show active" id="companies-basic" role="tabpanel" aria-labelledby="companies-basic-tab">
            <input type="hidden" name="id" id="id" value="<?php echo $r->id_emp  ?? '' ?>">
            <div class="row">
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="cod_emp">Código <span class="required">*</span></label>
                    <input type="number" class="form-control text-xs" id="cod_emp" name="cod_emp" placeholder="Ingrese código">
                </div>
                <div class="form-group col-md-7 col-sm-7 col-xs-12">
                    <label for="nombre_emp">Nombre </label>
                    <input type="text" class="form-control text-xs mayusculas" id="nombre_emp" name="nombre_emp" placeholder="Ingrese nombre">
                </div>
                <div class="form-group col-md-3 col-sm-3 col-xs-12">
                    <label for="rif_empresa">Rif <span class="required">*</span></label>
                    <input type="text" class="form-control rif text-xs mayusculas" id="rif_empresa" name="rif_empresa" placeholder="Ingrese rif">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="tel_emp">Teléfono </label>
                    <input type="text" class="form-control text-xs" id="tel_emp" name="tel_emp" placeholder="Ingrese teléfono">
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="email_emp">Email </label>
                    <input type="email" class="form-control text-xs" id="email_emp" name="email_emp" placeholder="Ingrese email">
                </div>
                <div class="form-group col-md-4 col-md-4 col-sm-4 col-xs-12">
                    <label for="status">Estado </label>
                    <select class="form-control select2 text-xs" id="status" name="status"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="dir_emp">Dirección </label>
                    <textarea name="dir_emp" id="dir_emp" class="form-control text-xs mayusculas" cols="143" rows="2"></textarea>
                </div>
                <div class="form-group col-sm-3 col-md-3 col-xs-12">
                    <label for="nom_ctb_iva_deb_fis">IVA Débito Fiscal</label>
                    <input type="hidden" name="iva_deb_fis" id="iva_deb_fis">
                    <div class="input-group">
                        <input type="text" class="form-control text-xs" id="nom_ctb_iva_deb_fis" name="nom_ctb_iva_deb_fis" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text nom_ctb"><a href="#" data-toggle="modal" title="Buscar y seleccionar Cuentas Contables" class="btn-open-modal" data-target-id="iva_deb_fis" data-target-name="nom_ctb_iva_deb_fis"> <i class="fas fa-search text-xs id_ctb"></i></a></span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-3 col-md-3 col-xs-12">
                    <label for="nom_ctb_iva_cre_fis">IVA Crébito Fiscal</label>
                    <input type="hidden" name="iva_cre_fis" id="iva_cre_fis">
                    <div class="input-group">
                        <input type="text" class="form-control text-xs" id="nom_ctb_iva_cre_fis" name="nom_ctb_iva_cre_fis" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text nom_ctb"><a href="#" data-toggle="modal" title="Buscar y seleccionar Cuentas Contables" class="btn-open-modal" data-target-id="iva_cre_fis" data-target-name="nom_ctb_iva_cre_fis"> <i class="fas fa-search text-xs"></i></a></span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-2 col-md-2 col-xs-12">
                    <label for="Zona Fiscal">Zona Fiscal</label>
                    <select name="id_iva" id="id_iva" class="form-control text-xs select2"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="id_pais">País </label>
                    <select name="id_pais" id="id_pais" class="select2 select2bs4  text-xs"></select>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="id_moneda">Moneda </label>
                    <select name="id_moneda" id="id_moneda" class="select2 select2bs4 text-xs"></select>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="especial_contrib">Contribuyente Especial </label>
                    <select name="especial_contrib" id="especial_contrib" class="select2 select2bs4 text-xs"></select>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="companies-dates" role="tabpanel" aria-labelledby="companies-dates-tab">
            <div class="row">
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="fec_ini_fis">Inicio año fiscal</label>
                    <input type="date" name="fec_ini_fis" id="fec_ini_fis" class="form-control text-xs">
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="fec_fin_fis">Fin año fiscal</label>
                    <input type="date" name="fec_fin_fis" id="fec_fin_fis" class="form-control text-xs">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-2 col-md-2 col-xs-12">
                    <label for="fec_ctb">Cierre de Contabilidad</label>
                    <input type="date" class="form-control text-xs" name="fec_ctb" id="fec_ctb">
                </div>
                <div class="form-group col-sm-2 col-md-2 col-xs-12">
                    <label for="fec_ban">Cierre de Bancos</label>
                    <input type="date" class="form-control text-xs" name="fec_ban" id="fec_ban">
                </div>
                <div class="form-group col-sm-2 col-md-2 col-xs-12">
                    <label for="fec_cxc">Cierre de Cuentas por Cobrar</label>
                    <input type="date" class="form-control text-xs" name="fec_cxc" id="fec_cxc">
                </div>
                <div class="form-group col-sm-2 col-md-2 col-xs-12">
                    <label for="fec_cxp">Cierre de Cuentas por Pagar</label>
                    <input type="date" class="form-control text-xs" name="fec_cxp" id="fec_cxp">
                </div>
                <div class="form-group col-sm-2 col-md-2 col-xs-12">
                    <label for="fec_nom">Cierre de Nóminas</label>
                    <input type="date" class="form-control text-xs" name="fec_nom" id="fec_nom">
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="companies-logo" role="tabpanel" aria-labelledby="companies-logo-tab">
            <div class="row">
                <div class="col-lg-4">
                    <h1 class="text-primary">Subir imagen</h1>
                    <div class="form-group">
                        <label for="logo">Seleccione una imagen</label>
                        <input type="file" accept="image/*" name="url_photo" id="url_photo" onchange="previewImage(event, '#imgPreview')">
                    </div>
                </div>
                <div class="col-lg-8">
                    <h1 class="text-primary text-center">Galería de imagenes</h1>
                    <hr>
                    <div class="card-columns text-center">
                        <div id="cardimg1">
                            <img id="imgPreview" name="imgPreview" width="600px" height="200px" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="companies-correo" role="tabpanel" aria-labelledby="companies-correo-tab">
            <div class="row">
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label for="host">Servidor</label>
                    <input type="text" class="form-control text-xs" name="host" id="host" placeholder="Especifique el nombre del servidor de correo" title="Especifique el nombre del servidor de correo">
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label for="usuario">Correo que envía</label>
                    <input type="text" class="form-control text-xs" name="usuario" id="usuario" placeholder="Indique el usuario desde donde se enviaran los correos" title="Indique el usuario desde donde se enviaran los correos">
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label for="pass_email">Contraseña</label>
                    <input type="password" autocomplete="on" class="form-control text-xs" name="pass_email" id="pass_email" title="Contraseña de correo que envía">
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label for="puerto_send">Puerto de envío</label>
                    <input type="number" class="form-control text-xs" name="puerto_send" id="puerto_send" placeholder="Indique el puerto de envío" title="Indique el puerto de envío">
                </div>
                <div class="row">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <a href="<?php echo base_url . '/Empresas/print_test' ?>" class="btn btn-primary btn-xs">Test de envío</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/CuentasCtb/modal_CuentasCtb.php';
?>