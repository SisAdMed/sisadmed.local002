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
                        <h3 class="card-title"><?= $data['page_name'] ?></h3>
                        <div class="card-tools">
                            <!-- Collapse Button -->
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
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
                                <select name="id_gru" id="id_gru" class="select2 select2bs4 text-xs" multiple style="width:100%"></select>
                            </div>
                            <div class="form-group col-sm-3 col-md-3 col-xs-12">
                                <label for="id_vend">Vendedor</label>
                                <select name="id_vend" id="id_vend" class="select2 select2bs4 text-xs" multiple style="width:100%"></select>
                            </div>
                            <div class="form-group col-sm-3 col-md-3 col-xs-12">
                                <label for="id_tipocliente">Tipo de Cliente</label>
                                <select name="id_tipocliente" id="id_tipocliente" class="select2 select2bs4 text-xs" multiple style="width:100%"></select>
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
        <div class="row" id="consumo-content">
            <div class="col-12 col-sm-12">
                      <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <UL class="nav nav-tabs" id="consumo" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="consumo-table-tab" data-toggle="pill" href="#consumo-table" role="tab" aria-controls="consumo-table" aria-selected="true">Tabla</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="consumo-grafica-tab" data-toggle="pill" href="#consumo-grafica" role="tab" aria-controls="consumo-grafica" aria-selected="true">Gráfica</a>
                            </li>
                        </UL>
                    </div>
                </div>              
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content" id="consumo-tabcontent">
                <div class="tab-pane fade show active" id="consumo-table" role="tabpanel" aria-labelledby="consumo-table-tab">
                    <!-- Tabla de consumo -->
                    <table id="ReportexConsumo" class="table table-bordered tabla-dimension-real"></table>
                </div>
                <div class="tab-pane fade show" id="consumo-grafica" role="tabpanel" aria-labelledby="consumo-grafica-tab">
                    <!-- Gráfica de consumo -->
                    <div class="row">
                        <div id="graficaConsumo" style="width: 100%;">
                            <div class="row">
                                <div class="form-group col-sm-2 col-md-2 col-xs-12">
                                    <label for="sel_fecha">Año</label>
                                    <select name="sel_fecha" id="sel_fecha" class="select2 select2bs4 text-xs" multiple style="width:100%"></select>
                                </div>
                                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                                    <label for="sel_marca">Marca</label>
                                    <select name="sel_marca" id="sel_marca" class="select2 select2bs4 text-xs" multiple style="width:100%"></select>
                                </div>
                                <div class="form-group col-sm-2 col-md-2 col-xs-12">
                                    <label for="sel_tipo_cliente">Tipo Cliente</label>
                                    <select name="sel_tipo_cliente" id="sel_tipo_cliente" class="select2 select2bs4 text-xs" multiple style="width:100%"></select>
                                </div>
                                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                                    <label for="sel_vendedor">Vendedor</label>
                                    <select name="sel_vendedor" id="sel_vendedor" class="select2 select2bs4 text-xs" multiple style="width:100%"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-xs-12 ">
                                     <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title text-xs">Utilidad por Cliente</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body card-scroll">
                                            
                                                
                                                    <table id="ReportexGrafica" class="table table-bordered tabla-dimension-real" style="width: 100%;"></table>
                                                
                                                <!-- /.col -->
                                            
                                            <!-- /.row -->
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer p-0">
                                            <ul class="nav nav-pills flex-column">
                                            </ul>
                                        </div>
                                        <!-- /.footer -->
                                    </div>
                                </div>                                    
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title text-xs">Utilidad por Marca</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body card-scroll">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="chart-responsive">
                                                        <canvas id="marcaChart" height="100px"></canvas>
                                                    </div>
                                                    <div id="custom-legend-marca" style="display: flex; flex-direction: column; gap: 10px;">
                                                    </div>
                                                    <!-- ./chart-responsive -->
                                                </div>
                                                <!-- /.col -->
                                            </div>
                                            <!-- /.row -->
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer p-0">
                                            <ul class="nav nav-pills flex-column">
                                            </ul>
                                        </div>
                                        <!-- /.footer -->
                                    </div>
                                </div>
                                <div class="form-group col-sm-4 col-md-4 col-xs-12">
                                    <div class="card card-success">
                                        <div class="card-header">
                                            <h3 class="card-title text-xs">Tipo de Cliente</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body card-scroll">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="chart-responsive">
                                                        <canvas id="tipoChart" height="100px"></canvas>
                                                    </div>
                                                    <div id="custom-legend-tipo-cliente" class="text-xs" style="display: flex; flex-direction: column; gap: 10px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer p-0">
                                            <ul class="nav nav-pills flex-column">
                                            </ul>
                                        </div>
                                    </div>
                                </div>   
                                 <div class="form-group col-sm-4 col-md-4 col-xs-12">
                                    <div class="card card-success">
                                        <div class="card-header">
                                            <h3 class="card-title text-xs">Tipo de Cliente</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body card-scroll">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="chart-responsive">
                                                        <canvas id="tipoChart" height="100px"></canvas>
                                                    </div>
                                                    <div id="custom-legend-tipo-cliente" class="text-xs" style="display: flex; flex-direction: column; gap: 10px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer p-0">
                                            <ul class="nav nav-pills flex-column">
                                            </ul>
                                        </div>
                                    </div>
                                </div>                            
                            </div>
                        </div>

                    </div>
                </div>
    </section>
</div>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Clientes/modal_Clientes.php'; ?>
<?php footerAdmin($data); ?>