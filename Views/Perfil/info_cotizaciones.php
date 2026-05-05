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
                            <div class="form-group col-sm-3 col-md-3 col-xs-12">
                                <label for="id_vend">Vendedor</label>
                                <select name="id_vend" id="id_vend" class="form-control select2 select2bs4 text-xs" multiple style="width:100%"></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-2 col-md-1 col-xs-12">
                                <label for="btnSearchCotizaciones"></label>
                                <input type="button" name="btnSearchCotizaciones" id="btnSearchCotizaciones" class="form-control btn btn-primary text-xs" value="Buscar">
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
        <div class="card-body">
            <!-- Tabla de consumo -->
            <table id="ReportexCotizaciones" class="table table-bordered tabla-dimension-real"></table>
            <div class="row" id="GrafCoti" style="display: none;">
                <!-- Gráfico de Barras -->
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-bar"></i> Comparativa por Vendedor</h3>
                        </div>                        
                        <div class="card-body">
                            <canvas id="barChart" style="min-height: 300px; height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Gráfico de Torta -->
                <div class="col-md-4">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-chart-pie"></i> Distribución de Facturación</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="pieChart" style="min-height: 300px; height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Clientes/modal_Clientes.php'; ?>
<?php footerAdmin($data); ?>