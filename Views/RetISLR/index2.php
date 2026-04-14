<?php headerAdmin($data);?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo $data['page_name']; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=base_url?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $data['page_name']; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <form  name="my_form" id="my_form" method="POST" class="form-horizontal form-label-left" >
            <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                            <div class="row">
                                    <div class="form-group col-sm-4 col-md-4 col-xs-12">
                                        <label for="id_emp">Empresa</label>
                                        <select name="id_emp" id="id_emp" class="form-control text-xs" ></select>
                                    </div>
                                    <div class="form-group col-sm-2 col-md-2 col-xs-12">
                                        <label for="fec_ini">Desde</label>
                                        <input type="date" name="fec_ini" id="fec_ini" class="form-control text-xs">
                                    </div>
                                    <div class="form-group col-sm-2 col-md-2 col-xs-12">
                                        <label for="fec_fin">Hasta</label>
                                        <input type="date" name="fec_fin" id="fec_fin" class="form-control text-xs">
                                    </div>
                                    <div class="form-group col-sm-2 col-md-2 col-xs-12 center">
                                        <button id="Data" data-id="excel" type="button" class="btn btn-success btn-lg" onclick="report_retislr(this)"><i class="fa-solid fa-file-excel" title="Reporte en Excel"></i></i></button>
                                        <button id="Data" data-id="pdf" type="button" class="btn btn-danger btn-lg" onclick="report_retislr(this)"><i class="fa-solid fa-file-pdf" title="Reporte en PDF"></i></i></button>
                                        <button id="Data" data-id="xml" type="button" class="btn btn-primary btn-lg" onclick="report_retislr(this)"><i class="fa-solid fa-x" title="Reporte en XML"></i></i></button>
                                    </div>
                                    <div class="form-group col-sm-2 col-md-2 col-xs-12">
                                        <label for="btnClear"></label>
                                        <input type="button" name="btnClear" id="btnClear" class="form-control btn btn-warning text-xs" value="Limpiar">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="loader">
                <img src="<?= IMG . '/ajax-loading.gif'  ?>" />
            </div>
        </form>    
    </section>
</div>
<?php footerAdmin($data);?>