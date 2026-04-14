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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php echo $data['page_name']; ?> Principal</h3>
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
                        <label for="fec_ini">Fecha de inicio</label>
                        <input type="date" name="fec_ini" id="fec_ini" class="form-control text-xs">
                    </div>
                    <div class="form-group col-sm-2 col-md-2 col-xs-12">
                        <label for="fec_fin">Fecha final</label>
                        <input type="date" name="fec_fin" id="fec_fin" class="form-control text-xs">
                    </div>
                    <div class="form-group col-sm-4 col-md-4 col-xs-12">
                        <label for="id_alm">Almacén</label>
                        <select name="id_alm" id="id_alm" class="select2 select2bs4 text-xs" multiple></select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-2 col-md-2 col-xs-12">
                        <label for="id_ubi">Ubicación</label>
                        <select name="id_ubi" id="id_ubi" class="select2 select2bs4 text-xs"></select>
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="id_fab">Marca</label>
                        <select name="id_fab" id="id_fab" class="form-control select2 select2bs4 text-xs" multiple style="width:100%"></select>
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
                        <input type="button" id="btn-search" name="btn-search" class="btn btn-primary" value="Consultar">
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12 text-center">
                        <button id="Data" data-id="excel" type="button" class="btn btn-success btn-lg" onclick="report_to_excel(this)"><i class="fa-solid fa-file-excel" title="Reporte en Excel"></i></i></button>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <table id="tblTableConMovInv" class="display responsive nowrap table table-hover table2excel_with_colors" style="width:100%">
                    <thead>
                        <th>Empresa</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Número</th>
                        <th>Almacen</th>
                        <th>Producto</th>
                        <th>Código</th>
                        <th>Referencia</th>
                        <th>Origen</th>
                        <th>Cliente</th>
                        <th>Marca</th>
                        <th>Entrdas</th>
                        <th>Salidas</th>
                        <th>Saldos</th>
                    </thead>
                    <tbody id=tblTableConMovInvDet></tbody>
                    <tfoot>
                        <th>Empresa</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Número</th>
                        <th>Almacen</th>
                        <th>Producto</th>
                        <th>Código</th>
                        <th>Referencia</th>
                        <th>Origen</th>
                        <th>Cliente</th>
                        <th>Marca</th>
                        <th>Entrdas</th>
                        <th>Salidas</th>
                        <th>Saldos</th>
                    </tfoot>
                </table>
                <div class="loader">
                    <img src="<?= IMG . '/ajax-loading.gif'  ?>" />
                </div>
            </div>
        </div>
    </section>
</div>
<?php footerAdmin($data); ?>

<div class="modal fade" id="modal-productos01">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Buscar y seleccionar Productos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12 col-xs-12" id="listar_product_modal" name="listar_product_modal">
                    <table id="tblModalProd01" name="tblModalProd01" class="display responsive nowrap table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Código</th>
                                <th>Código 2</th>
                                <th>Descripción</th>
                                <th>Referencia</th>
                                <th>Marca</th>
                                <th class="text-right">Stock</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#modal-productos01').on('show.bs.modal', function(e) {
        id_emp = $("#id_emp").val();
        id_fab = $("#id_fab").val();
        datos = {
            stock: 0,
            id_emp: id_emp,
            id_fab: id_fab
        };
        url = `${base_url}/Productos/listar_productos_modal`;
        $('#tblModalProd01').DataTable().clear();
        $('#tblModalProd01').DataTable().destroy();
        var tblModal = $('#tblModalProd01').DataTable({
            aProcessing: true,
            aServerSide: true,
            fnCreatedRow: function(rowEl, data) {
                $(rowEl).attr('id', data.id_prod);
            },
            processing: true,
            ajax: {
                url: url,
                type: "POST",
                deferRender: true,
                data: datos,
                dataSrc: '',
                select: true,
            },
            language: {
                url: `${base_url}/Assets/json/es-ES.json`,
            },
            columns: [{
                    "data": "id_prod"
                },
                {
                    "data": "cod_prod"
                },
                {
                    "data": "cod2_prod"
                },
                {
                    "data": "nom_prod"
                },
                {
                    "data": "ref_prod"
                },
                {
                    "data": "nom_fab"
                },
                {
                    "data": "stock"
                },
            ],
            columnDefs: [{
                    targets: 0,
                    visible: false,
                    searchable: false,
                },
                {
                    targets: 6,
                    className: 'text-right'
                }
            ],
        });
    });
    /*
    //Seleccionar registro marcado del Modal de clietnes y mostrarlo en el formulario
    $('body').on('click', '#tblModalProd01 tr', function() {
        id_prod = $(this).attr('id');
        $("#id_prod").val(id_prod);
        var table = new DataTable('#tblModalProd01')
        var rows = table.rows(0).data();
        var nom_prod = rows[0]['nom_prod'];
        $('#nom_prod').val(nom_prod);
        $('#modal-productos01').modal('hide')
    });

    */
    $('body').on('click', '#tblModalProd01 td', function() {
        $('#tblTableConMovInvDet').empty();
        dtb = $('#tblModalProd01').DataTable();
        var rowData = dtb.row(this).data();
        $('#id_prod').val(rowData['id_prod']);
        $('#nom_prod').val(rowData['nom_prod'])
        $('#modal-productos01').modal('hide');
    })
</script>