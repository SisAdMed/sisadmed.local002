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
                    <li class="nav-item">
                        <a class="nav-link" id="companies-web-tab" data-toggle="pill" href="#companies-web" role="tab" aria-controls="companies-web" aria-selected="false"><i class="fas fa-globe"></i> Contenido Web</a>
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
                        <label for="lourl_photogo">Seleccione una imagen</label>
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
        <div class="tab-pane fade" id="companies-web" role="tabpanel" aria-labelledby="companies-web-tab">
            <div class="row mt-2">

                <div class="col-5 col-sm-3">
                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active text-xs" id="web-info-tab" data-toggle="pill" href="#web-info" role="tab" aria-controls="web-info" aria-selected="true">Institucional (Misión/Visión)</a>
                        <a class="nav-link text-xs" id="web-valores-tab" data-toggle="pill" href="#web-valores" role="tab" aria-controls="web-valores" aria-selected="false">Valores Corporativos</a>
                        <a class="nav-link text-xs" id="web-cifras-tab" data-toggle="pill" href="#web-cifras" role="tab" aria-controls="web-cifras" aria-selected="false">Cifras de Éxito</a>
                        <a class="nav-link text-xs" id="web-redes-tab" data-toggle="pill" href="#web-redes" role="tab" aria-controls="web-redes" aria-selected="false">Redes Sociales</a>
                        <a class="nav-link text-xs" id="web-footer-tab" data-toggle="pill" href="#web-footer" role="tab" aria-controls="web-footer" aria-selected="false">Pie de Página</a>
                    </div>
                </div>

                <div class="col-7 col-sm-9" style="border-left: 1px solid #dee2e6;">
                    <div class="tab-content" id="vert-tabs-tabContent">

                        <div class="tab-pane text-left fade show active" id="web-info" role="tabpanel" aria-labelledby="web-info-tab">
                            <div class="row pt-2">
                                <div class="form-group col-md-12">
                                    <label for="historia">Nuestra Empresa / Reseña Histórica <span class="text-danger">*</span></label>
                                    <textarea name="historia" id="historia" class="form-control summernote"></textarea>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="mision">Misión <span class="text-danger">*</span></label>
                                    <textarea name="mision" id="mision" class="form-control summernote-short"></textarea>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="vision">Visión <span class="text-danger">*</span></label>
                                    <textarea name="vision" id="vision" class="form-control summernote-short"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="web-valores" role="tabpanel" aria-labelledby="web-valores-tab">

                            <div class="d-flex justify-content-between align-items-center mb-3 pt-2">
                                <h6 class="text-primary m-0"><i class="fas fa-list-ol"></i> Lista de Valores Corporativos</h6>

                                <button type="button" class="btn btn-success btn-xs" id="btn-add-valor">
                                    <i class="fas fa-plus"></i> Agregar Valor
                                </button>
                            </div>

                            <div id="contenedor-valores">

                                <div class="row fila-valor align-items-center mb-2">
                                    <div class="form-group col-md-2 mb-0">
                                        <label class="text-muted text-xxs mb-1 d-block">Icono FontAwesome</label>
                                        <input type="text" name="val_icono[]" class="form-control text-xs" placeholder="fa-star" value="fa-check">
                                    </div>
                                    <div class="form-group col-md-3 mb-0">
                                        <label class="text-muted text-xxs mb-1 d-block">Título del Valor</label>
                                        <input type="text" name="val_titulo[]" class="form-control text-xs" placeholder="Ej: Integridad">
                                    </div>
                                    <div class="form-group col-md-6 mb-0">
                                        <label class="text-muted text-xxs mb-1 d-block">Descripción</label>
                                        <input type="text" name="val_desc[]" class="form-control text-xs" placeholder="Breve descripción del valor...">
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <button type="button" class="btn btn-danger btn-xs btn-remove-fila" style="margin-top: 18px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade" id="web-cifras" role="tabpanel" aria-labelledby="web-cifras-tab">
                            <h5 class="text-primary mb-3 pt-2">Contadores Animados (Módulo de Impacto)</h5>

                            <div id="contenedor-cifras">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Cifra 1 (Número)</label>
                                        <input type="text" name="cifra_num[]" class="form-control text-xs" placeholder="Ej: 15">
                                        <label class="mt-1">Etiqueta</label>
                                        <input type="text" name="cifra_txt[]" class="form-control text-xs" placeholder="Ej: Años de Experiencia">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Cifra 2 (Número)</label>
                                        <input type="text" name="cifra_num[]" class="form-control text-xs" placeholder="Ej: 500">
                                        <label class="mt-1">Etiqueta</label>
                                        <input type="text" name="cifra_txt[]" class="form-control text-xs" placeholder="Ej: Clientes Satisfechos">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Cifra 3 (Número)</label>
                                        <input type="text" name="cifra_num[]" class="form-control text-xs" placeholder="Ej: 200">
                                        <label class="mt-1">Etiqueta</label>
                                        <input type="text" name="cifra_txt[]" class="form-control text-xs" placeholder="Ej: Insumos Certificados">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="web-redes" role="tabpanel" aria-labelledby="web-redes-tab">
                            <h5 class="text-primary mb-3 pt-2">Redes Sociales</h5>

                            <div id="contenedor-redes">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Instagram <i class="fa-brands fa-instagram"></i></label>
                                        <input type="text" name="red_nombre[]" class="form-control text-xs" placeholder="Ej: Instagram">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Facebook <i class="fa-brands fa-facebook"></i></label>
                                        <input type="text" name="red_nombre[]" class="form-control text-xs" placeholder="Ej: Facebook">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Twitter <i class="fa-brands fa-twitter"></i></label>
                                        <input type="text" name="red_nombre[]" class="form-control text-xs" placeholder="Ej: Twitter">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>LinkedIn <i class="fa-brands fa-linkedin"></i></label>
                                        <input type="text" name="red_nombre[]" class="form-control text-xs" placeholder="Ej: LinkedIn">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>WhatsApp <i class="fa-brands fa-whatsapp"></i></label>
                                        <input type="text" name="red_nombre[]" class="form-control text-xs" placeholder="Ej: WhatsApp">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="tab-pane fade" id="web-footer" role="tabpanel" aria-labelledby="web-footer-tab">
                            <h5 class="text-primary mb-3 pt-2">Información de Pie de Página</h5>

                            <div id="contenedor-footer">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Ciudad Pais</label>
                                        <input type="text" name="footer_city[]" id="footer_city" class="form-control text-xs" placeholder="Ej: Caracas, Venezuela">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Teléfono</label>
                                        <input type="text" name="footer_tel[]" id="footer_tel" class="form-control text-xs" placeholder="Ej: +58(212)   XXX-XXXX">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Correo Electrónico</label>
                                        <input type="text" name="footer_email[]" id="footer_email" class="form-control text-xs" placeholder="Ej: info@empresa.com">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Horario</label>
                                        <input type="text" name="footer_horario[]" id="footer_horario" class="form-control text-xs" placeholder="Ej: Lunes a Viernes 9:00 - 18:00">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Reseña</label>
                                        <textarea name="footer_desc[]" id="footer_desc" class="form-control text-xs" placeholder="Breve reseña o descripción para el pie de página..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/CuentasCtb/modal_CuentasCtb.php';
        ?>