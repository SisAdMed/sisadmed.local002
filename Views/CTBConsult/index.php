<?php headerAdmin($data);?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1><?php echo $data['page_name']; ?>
                    </h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content-header">
        <div class="container-fluid">
            <form method= "POST" name="my_form" id="my_form">
                <div class="row">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label for="id_emp">Empresa</label>
                        <select name="id_emp" id="id_emp" class="form-control"></select>
                        <span></span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="fec_ini">Fecha de inicio</label>
                        <input type="date" name="fec_ini" id="fec_ini" class="form-control">
                        <span></span>
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="fec_fin">Fecha de corte</label>
                        <input type="date" name="fec_fin" id="fec_fin" class="form-control">
                        <span></span>
                    </div>
                    <div class="form-group col-md-3 col-sm-3 col-xs-12">
                        <label for="nom_ctb">Cuenta contable</label>
                        <input type="hidden" name="id_ctb" id="id_ctb">
                        <div class="input-group">
                            <input type="text" class="form-control text-xs" id="nom_ctb" name="nom_ctb" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text nom_ctb"><a href="#" data-toggle="modal" data-target="#modal-CuentasCtb" title="Buscar y seleccionar Cuentas Contables"> <i class="fas fa-search text-xs"></i></a></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-3 col-sm-3 col-xs-12">
                        <label for="nom_aux">Auxiliar contable</label>
                        <input type="hidden" name="id_aux" id="id_aux">
                        <div class="input-group">
                            <input type="text" class="form-control text-xs" id="nom_aux" name="nom_aux" readonly>
                            <div id="div_aux">
                                <div class="input-group-append">
                                    <span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"> <i class="fas fa-search text-xs"></i></a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-1 col-sm-1 col-xs-12">
                        <label for="niv_det">Nivel de detalle</label>
                        <select name="niv_det" id="niv_det" class="form-control text-right"></select>
                    </div>
                    <div class="form-group col-md-1 col-sm-1 center-block text-center tb-2">
                    <input id="btn_con" type="button" class="btn btn-success" value="Consultar" />
                    </div>
                </div>
                <div class="row">
                    <div id="dynamicTable" class="form-group col-md-12 col-sm-12 col-xs-12">
                        <table id="Con_Mov_Ctb" style="width:100%"></table>
                    </div>
                </div>
            </form>
        </div>  
    </section>
</div>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/Views/CuentasCtb/modal_CuentasCtb.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/Views/AuxiliarCtb/modal_AuxiliaresCtb.php';
?>
<?php footerAdmin($data); ?>
