<?php headerAdmin($data)?>
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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php echo $data['page_name']; ?></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title=""Collapse>
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="my_form">
                    <div class="row">
                        <div class="form-group col-sm-4 col-md-4 col-xs-12">
                            <label for="id_emp">Empresa</label>
                            <select name="id_emp" id="id_emp" class="form-control text-xs"></select>
                        </div>
                        <div class="form-group col-sm-2 col-md-2 col-xs-12">
                            <label for="fec_ini">Fecha de inicio</label>
                            <input type="date" name="fec_ini" id="fec_ini" class="form-control text-xs">
                        </div>
                        <div class="form-group col-sm-2 col-md-2 col-xs-12">
                            <label for="fec_fin">Fecha final</label>
                            <input type="date" name="fec_fin" id="fec_fin" class="form-control text-xs">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4 col-sm-4 col-xs-12 text-xs">
                            <label for="">Cuenta</label>
                            <select name="id_bancue" id="id_bancue" class="select2 text-xs"></select>
                        </div>
                        <div class="form-group col-md-4 col-sm-4 col-xs-12 text-xs">
                            <label for="id_bancon">Concepto</label>
                            <input type="hidden" name="id_bancon" id="id_bancon" class="id_bancon" >
                            <div class="input-group">
                                <input type="text" class="form-control text-xs" id="nom_con" name="nom_con" readonly >
                                <div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal"          data-target="#modal-BanConceptos" title="Buscar y seleccionar Conceptos Bancarios"><i class="fas fa-search nom_con"></i></a></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 col-md-4 col-xs-12 text-center">
                            <!--
                            <a type="button" class="btn btn-primary btn-md" href=""><i class="fa fa-search" title="Buscar registros"></i></a>
                            <button id="Data" data-id="excel" type="button" class="btn btn-success btn-md" onclick="report_to_excel(this)"><i class="fa-solid fa-file-excel" title="Reporte en Excel"></i></button>
                            <a type="button" id="btn_clear" class="btn btn-warning btn-md" href=""><i class="fa-solid fa-broom" title="Limpiar campos"></i></a>
                            -->
                            <button type="button" id="btn-search" name="btn-search" class="btn btn-md btn-primary" title="Consultar Registros"><i class="fa fa-search"></i></button>
                            <button type="button" id="btn-excel" name="btn-excel" class="btn btn-md btn-success" title="Reporte en Excel"><i class="fa-solid fa-file-excel"></i></button>
                            <button type="button" id="btn-clear" name="btn-clear" class="btn btn-md btn-warning" title="Limpiar Campos"><i class="fa-solid fa-broom"></i></button>
                        </div>
                    </div>
                </form>
            </div>
             <div class="card-footer">
                    <table id="tblBanmov_cuenta" class="display responsive nowrap table table-hover table2excel_with_colors" style="width:100%">
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="loader">
    <img src="<?= IMG . '/ajax-loading.gif'  ?>" />
</div>
<?php footerAdmin($data)?>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/Views/BanConceptos/modal_BanConceptos.php';
?>