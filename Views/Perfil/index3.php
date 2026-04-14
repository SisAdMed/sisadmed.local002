<?php headerAdmin($data); ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
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
                            <div class="form-group col-sm-5 col-md-5 col-xs-12">
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
                            <div class="form-group col-sm-2 col-md-1 col-xs-12">
                                <label for="btnSearch"></label>
                                <input type="button" name="btnSearch" id="btnSearch" class="form-control btn btn-primary text-xs" value="Buscar">
                            </div>
                            <div class="form-group col-sm-2 col-md-1 col-xs-12">
                                <label for="btnClear"></label>
                                <input type="button" name="btnClear" id="btnClear" class="form-control btn btn-warning text-xs" value="Limpiar">
                            </div>
                            <div class="form-group col-sm-2 col-md-1 col-xs-12 excel">
                                <label for="btnExcel"></label>
                                <button type="button" id="btnExcel" class="btn btn-success btn-lg"><i class="fa-solid fa-file-excel" title="Descargar detallado en Excel"></i></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" style="width:100%">
                <div class="card-body">
                    <table id="tabla_grafica_prod" class="display responsive nowrap table table-hover" style="width:100%">                     
                    </table>
                </div>
                <div class="card-footer">
                    <table id="tabla_grafica_prod_det" class="display responsive nowrap table table-hover" style="width:100%">                    
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<?php footerAdmin($data); ?>