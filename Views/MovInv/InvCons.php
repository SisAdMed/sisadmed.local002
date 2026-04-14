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
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                <div class="row">
                    <div class="form-group col-sm-4 col-md-4 col-xs-12">
                        <label for="id_emp">Empresa</label>
                        <select name="id_emp" id="id_emp" class="form-control text-xs"></select>
                    </div>
                    <div class="form-group col-sm-2 col-md-2 col-xs-12">
                        <label for="fec_fin">Fecha</label>
                        <input type="date" name="fec_fin" id="fec_fin" class="form-control text-xs">
                    </div>
                    <div class="form-group col-sm-4 col-md-4 col-xs-12">
                        <label for="id_alm">Almacén</label>
                        <select name="id_alm" id="id_alm" class=" form-control select2 select2bs4 text-xs" multiple></select>
                    </div>
                    <div class="form-group col-sm-2 col-md-2 col-xs-12">
                        <label for="id_ubi">Ubicación</label>
                        <select name="id_ubi" id="id_ubi" class="select2 select2bs4 text-xs"></select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="id_fab">Marca</label>
                        <select name="id_fab" id="id_fab" class="select2 select2bs4 text-xs" multiple style="width:100%"></select>
                    </div>
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
                    <div class="form-group col-md-2 col-sm-2 col-xs-12 text-center">
                        <button id="Data" data-id="btn-search" type="button" class="btn btn-primary btn-lg" onclick="action(this)"><i class="fa-brands fa-searchengin" title="Consultar registros"></i></button>
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12 text-center">
                        <button id="Data" data-id="btn-clear" type="button" class="btn btn-danger btn-lg" onclick="action(this)"><i class="fa-solid fa-broom" title="Limpiar campos"></i></button>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <table id="tblTableConMovInv" class="display responsive nowrap table table-hover" style="width:100%;">
                    <tfoot>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
</div>
<?php footerAdmin($data); ?>