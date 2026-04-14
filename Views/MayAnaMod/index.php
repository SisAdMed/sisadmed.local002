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
        <form name="my_form" id="my_form" method="POST" class="form-horizontal form-label-left">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-xs-12">
                            <label for="id_emp">Empresa</label>
                            <select name="id_emp" id="id_emp" class="form-control text-xs"></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-2 col-md-2 col-xs-12">
                            <label for="fec_ini">Desde</label>
                            <input type="date" name="fec_ini" id="fec_ini" class="form-control text-xs">
                        </div>
                        <div class="form-group col-sm-2 col-md-2 col-xs-12">
                            <label for="fec_fin">Hasta</label>
                            <input type="date" name="fec_fin" id="fec_fin" class="form-control text-xs">
                        </div>
                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                            <label for="nom_ctb">Cuenta contable</label>
                            <input type="hidden" name="id_ctb" id="id_ctb">
                            <div class="input-group">
                                <input type="text" class="form-control text-xs" id="nom_ctb" name="nom_ctb" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text nom_ctb"><a href="#" data-toggle="modal" data-target="#modal-CuentasCtb" title="Buscar y seleccionar Cuentas Contables"> <i class="fas fa-search text-xs"></i></a></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                            <label for="nom_aux">Auxiliar contable</label>
                            <input type="hidden" name="id_aux" id="id_aux">
                            <div class="input-group">
                                <input type="text" class="form-control text-xs id_aux" id="nom_aux" name="nom_aux" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i id="div_aux" name="div_aux" class="fas fa-search"></i></a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row center">
                        <div class="form-group col-sm-12 col-md-12 col-xs-12 center">
                            <input type='submit' id='btnok' name='btnok' class='btn btn-success btn-xs' value='Consultar' />
                            <input type='button' id='btnclear' name='btnclear' class='btn btn-primary btn-xs' value='Limpiar' />
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- <div class="container"> -->
                        <div class="row">
                            <div class="col">
                                <table id="tbl_analitico" class="table table-striped table-bordered display responsive nowrap table-hover text-xs" style="width: 100%;">
                                    <thead></thead>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                   <!--  </div> -->

                </div>
                <div class="card-footer"></div>
            </div>
        </form>
    </section>
</div>
<?php footerAdmin($data);
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/CuentasCtb/modal_CuentasCtb.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/AuxiliarCtb/modal_AuxiliaresCtb.php';
?>