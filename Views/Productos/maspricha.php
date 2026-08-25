<?php

/** @var array $data */ ?>
<?php headerAdmin($data) ?>
<div class="content-wrapper">
    <section class="container-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo $data['page_name'] ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrum-item"><a href="<?= base_url ?>">Inicio</a></li>
                        <li class="breadcrum-item active">&nbsp;&nbsp;&nbsp; <?php echo $data['page_name'] ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header">
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
                <form id="frm_maspricha" method="POST">
                    <div class="row">
                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                            <label for="id_prod">Producto</label>
                            <input type="hidden" name="id_prod" id="id_prod" class="text-xs">
                            <div class="input-group">
                                <input type="text" class="form-control text-xs" id="nom_prod" name="nom_prod" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text text-xs">
                                        <a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search text-xs"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-2 col-sm-2 col-xs-12">
                            <label for="id_fab">Marca</label>
                            <select name="id_fab" id="id_fab" class="select2 select2bs4 text-xs" multiple style="width:100%"></select>
                        </div>
                        <div class="form-group col-md-2 col-sm-2 col-xs-12">
                            <label for="utilidad">Nueva Utilidad Recargo</label>
                            <input type="text" name="utilidad" id="utilidad" class="form-control text-xs camponumero text-right" min="0.01" max="1.00" placeholder="Ingrese nueva utilidad">
                        </div>
                    </div>
                    <div id="contenedor-tabla" class="table-responsive"></div>
                </form>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="form-group col-md-6 col-sm-6 col-xs-12 text-right">
                        <button type="button" class="btn btn-primary btn-xs" id="btnGuardar">Guardar</button>
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-xs-12 text-left">
                        <button type="button" class="btn btn-warning btn-xs" id="btnCancel">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php footerAdmin($data); ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Productos/modal_Productos.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Fabricantes/modal_fabricantes.php'; ?>
