<?php headerAdmin($data); ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $data['page_name'] ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href=<?= base_url ?>>Home</a></li>
                        <li class="breadcrumb-item active"><?= $data['page_name'] ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="form-group col-sm-4 col-md-4 col-xs-12">
                                <label for="id_emp">Empresa</label>
                                <select name="id_emp" id="id_emp" class="form-control text-xs"></select>
                            </div>
                            <div class="form-group col-sm-2 col-md-2 col-xs-12">
                                <label for="fec_ini">Desde</label>
                                <input type="date" name="fec_ini" id="fec_ini" class="form-control text-xs">
                            </div>
                            <div class="form-group col-sm-2 col-md-2 col-xs-12">
                                <label for="fec_fin">Hasta</label>
                                <input type="date" name="fec_fin" id="fec_fin" class="form-control text-xs">
                            </div>
                            <div class="form-group col-sm-4 col-md-4 col-xs-12">
                                <label for="id_fab">Marca/Fabricante/Laboratorio</label>
                                <select name="id_fab" id="id_fab" class="select2 select2bs4 text-xs" multiple style="width:100%"></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-3 col-md-3 col-xs-12">
                                <label for="" class="text-xs">Nombre de Cliente</label>
                                <input type="hidden" id="id_cli" name="id_cli">
                                <div class="input-group">
                                    <input type="text" class="form-control text-xs" id="nom_cli" name="nom_cli" readonly>
                                    <div class="input-group-append text-xs">
                                        <span class="input-group-text  text-xs"><a href="#" data-toggle="modal" data-target="#modal-clientes" title="Buscar y seleccionar cliente"><i class="fas fa-search text-xs"></i></a></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-3 col-md-3 col-xs-12">
                                <label for="id_gru">Grupo</label>
                                <select name="id_gru" id="id_gru" class="form-control select2 select2bs4 text-xs"></select>
                            </div>
                            <div class="form-group col-sm-3 col-md-3 col-xs-12">
                                <label for="id_vend">Vendedor</label>
                                <select name="id_vend" id="id_vend" class="form-control select2 select2bs4 text-xs"></select>
                            </div>
                            <div class="form-group col-sm-3 col-md-3 col-xs-12">
                                <label for="id_tipocliente">Tipo de Cliente</label>
                                <select name="id_tipocliente" id="id_tipocliente" class="form-control select2 select2bs4 text-xs"></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-2 col-md-1 col-xs-12">
                                <label for="btnSearchConsumo"></label>
                                <input type="button" name="btnSearchConsumo" id="btnSearchConsumo" class="form-control btn btn-primary text-xs" value="Buscar">
                            </div>
                            <div class="form-group col-sm-2 col-md-1 col-xs-12">
                                <label for="btnClear"></label>
                                <input type="button" name="btnClear" id="btnClear" class="form-control btn btn-warning text-xs" value="Limpiar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="card">
            <div>
                <table id="ReportexConsumo" class="table table-bordered tabla-dimension-real">
                </table>
            </div>
        </div>
    </section>
</div>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Clientes/modal_Clientes.php'; ?>
<?php footerAdmin($data); ?>