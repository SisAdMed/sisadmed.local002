<?php /** @var array $data */ ?>
<?php headerAdmin($data); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $data['page_name'] ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><?= $data['page_name']; ?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
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
                    <div class="form-group col-sm-4 col-md-4 col-xs-12">
                        <label for="id_emp">Empresa</label>
                        <select name="id_emp" id="id_emp" class="form-control text-xs"></select>
                    </div>
                    <div class="form-group col-sm-2 col-md-2 col-xs-12">
                        <label for="fec_ini">Fecha Desde</label>
                        <input type="date" name="fec_ini" id="fec_ini" class="form-control text-xs">
                    </div>
                    <div class="form-group col-sm-2 col-md-2 col-xs-12">
                        <label for="fec_fin">Fecha Hasta</label>
                        <input type="date" name="fec_fin" id="fec_fin" class="form-control text-xs">
                    </div>
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
                </div>
                <div class="row">
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="id_fab">Marca</label>
                        <select name="id_fab" id="id_fab" class="select2 select2bs4 text-xs" multiple style="width:100%"></select>
                    </div>
                    <div class="form-group col-md-2 col-md-2 col-sm-2 col-xs-12">
                        <label for="id_tipocliente">Tipo de Cliente</label>
                        <select class="form-control text-xs" id="id_tipocliente" name="id_tipocliente" required>
                        </select>
                    </div>
                    <!--- Comentado, al pendiente por si es solicitado
                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                        <label for="id_prod">Producto</label>
                        <input type="hidden" name="id_prod" id="id_prod" class="text-xs">
                        <div class="input-group">
                            <input type="text" class="form-control text-xs" id="nom_prod" name="nom_prod" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text text-xs">
                                    <a href="#" data-toggle="modal" data-target="#modal-productos01" title="Buscar y seleccionar productos"><i class="fas fa-search text-xs"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    -->
                    <div class="form-group col-md-4 col-sm-4 col-xs-12 text-right" style="place-items: center;">
                        <button id="Data" data-id="btn-search" type="button" class="btn btn-primary btn-lg" onclick="action(this)"><i class="fa-brands fa-searchengin" title="Consultar registros"></i></button>
                        <button id="Data" data-id="btn-clear" type="button" class="btn btn-danger btn-lg" onclick="action(this)"><i class="fa-solid fa-broom" title="Limpiar campos"></i></button>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <table id="tblresult" class="display responsive nowrap table table-hover" style="width:100%">
                    <thead></thead>
                </table>
            </div>
        </div>
    </section>
</div>
<?php footerAdmin($data);
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Productos/modal_Productos.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Clientes/modal_Clientes.php';
?>