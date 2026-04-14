<?php headerAdmin($data); ?>
<div class='content-wrapper'>
    <section class='content-header'>
        <div class='container-fluid'>
            <div class='row mb-2'>
                <div class='col-sm-12'>
                    <h1><?php echo $data['page_name']; ?>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Movimientos por Documentos</h3>
            </div>
            <div class="card-body">
                <form id="my_form" name="my_form">
                    <div class="row">
                        <div class="select-group col-sm-4 col-md-4 col-xs-12">
                            <label for="id_emp">Empresa</label>
                            <select name="id_emp" id="id_emp" class="select-control select2 select2bs4 text-xs"></select>
                        </div>
                        <div class="select-group col-sm-2 col-md-2 col-xs-12">
                            <label for="id_tdoc">Tipo de Documento</label>
                            <select name="id_tdoc" id="id_tdoc" class="select-control select2 select2bs4 text-xs"></select>
                        </div>
                        <div class="select-group col-sm-2 col-md-2 col-xs-12">
                            <label for="num_tdo">Número de Documento</label>
                            <input type="text" name="num_tdo" id="num_tdo" class="form-control text-xs text-right"></input>
                        </div>
                        <div class="select-group col-sm-4 col-md-4 col-xs-12">
                            <label for="id_cli">Proveedor</label>
                            <select name="id_cli" id="id_cli" class="select-control select2 select2bs4 text-xs"></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-2 col-md-2 col-xs-12">
                            <label for="fec_ini">Fecha de Inicio</label>
                            <input type="date" name="fec_ini" id="fec_ini" class="form-control text-xs "></input>
                        </div>
                        <div class="form-group col-sm-2 col-md-2 col-xs-12 text-center">
                            <label for="fec_fin">Fecha de Corte</label>
                            <input type="date" name="fec_fin" id="fec_fin" class="form-control text-xs"></input>
                        </div>
                        <div class="form-group col-sm-8 col-md-8 col-xs-12 text-center">
                            <label>Acciones</label> </br>
                            <button id="Data" data-id="btn-search" type="button" class="btn btn-primary btn-lg" onclick="action_btn(this)"><i class="fa-brands fa-searchengin" title="Consultar registros"></i></button>
                            <button id="Data" data-id="btn-clear" type="button" class="btn btn-danger btn-lg" onclick="action_btn(this)"><i class="fa-solid fa-broom" title="Limpiar campos"></i></button>
                            <button id="Data" data-id="btn-excel" type="button" class="btn btn-success btn-lg" onclick="action_btn(this)"><i class="fa-solid fa-file-excel" title="Exportar a Excel"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div id="show_rows">
                    
                </div>
            </div>
        </div>
    </section>
</div>
<?php footerAdmin($data); ?>