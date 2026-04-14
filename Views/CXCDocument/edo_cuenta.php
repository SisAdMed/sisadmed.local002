<?php headerAdmin($data); ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo $data['page_name']; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $data['page_name']; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <form name="my_form" id="my_form">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $data['page_name']; ?></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="" Collapse>
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-sm-3 col-md-3 col-xs-12 text-xs">
                            <label for="id_emp">Empresa <span class="required">*</span></label>
                            <select name="id_emp" id="id_emp" class="form-control text-xs"></select>
                        </div>
                        <div class="form-group col-md-3 col-sm-3 col-xs-12 text-xs condi">
                            <label for="id_cli" class="text-xs">Nombre de Cliente <span class="required">*</span></label>
                            <input type="hidden" id="id_cli" name="id_cli">
                            <div class="input-group">
                                <input type="text" class="form-control text-xs" id="nom_cli" name="nom_cli" readonly>
                                <div class="input-group-append text-xs">
                                    <span class="input-group-text text-xs"><a href="#" data-toggle="modal" data-target="#modal-clientes" title="Buscar y seleccionar cliente"><i class="fas fa-search text-xs"></i></a></span>
                                </div>
                            </div>
                            <div class="form-group rep_fac_pag">
                                <label>Rango de fecha para los días promedios de cancelación</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control float-right text.right text-xs" id="rangedate">
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-center">
                            <a type="button" id="btn-search" name="btn-search" class="btn btn-primary btn-lg" title="Buscar"><i class="fa fa-search"></i></a>
                            <a type="button" id="btn-clear" name="btn-clear" class="btn btn-danger btn-lg" title="Limpiar"><i class="fa-solid fa-broom"></i></a>
                        </div>
                        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-center">
                            <button id="Data" data-id="excel" type="button" class="btn btn-success btn-lg" onclick="report_to_excel(this)"><i class="fa-solid fa-file-excel" title="Reporte en Excel"></i></i></button>
                            <button id="Data" data-id="pdf" type="button" class="btn btn-danger btn-lg" onclick="report_to_excel(this)"><i class="fa-solid fa-file-pdf" title="Reporte en PDF"></i></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <table id="tblCXCedo_cuenta" class="display responsive nowrap table table-hover table2excel_with_colors" style="width:100%">
                    </table>
                    <table id="tblRepFecPag" class="display responsive nowrap table table-hover table2excel_with_colors" style="width:100%">
                    </table>

                </div>
            </div>
        </form>
    </section>
</div>
<?php footerAdmin($data); ?>
<div class="loader">
    <img src="<?= IMG . '/ajax-loading.gif'  ?>" />
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Clientes/modal_Clientes.php';
?>