<?php headerAdmin($data);?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Actualizar Número de Control</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Actualizar Número de Control</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="form-group col-sm-2 col-md-2 col-xs-12">
                <label for="id_emp">Empresa</label>
                <select name="id_emp" id="id_emp" class="form-control text-xs" ></select>
            </div>
            <div class="form-group col-sm-2 col-md-2 col-xs-12">
                <label for="id_tdo_ctrl">Tipo de Documento</label>
                <select name="id_tdo_ctrl" id="id_tdo_ctrl" class="form-control text-xs" ></select>
            </div>
            <div class="form-group col-sm-2 col-md-2 col-xs-12">
                <label for="fec_ini">Desde</label>
                <input type="date" name="fec_ini" id="fec_ini" class="form-control text-xs">
            </div>
            <div class="form-group col-sm-2 col-md-2 col-xs-12">
                <label for="fec_fin">Hasta</label>
                <input type="date" name="fec_fin" id="fec_fin" class="form-control text-xs">
            </div>
            <div class="form-group col-sm-2 col-md-2 col-xs-12">
                <label for="btnSearch"></label>
                <input type="button" name="btnSearch" id="btnSearch" class="form-control btn btn-primary text-xs" value="Buscar">
            </div>
            <div class="form-group col-sm-2 col-md-2 col-xs-12">
                <label for="btnClear"></label>
                <input type="button" name="btnClear" id="btnClear" class="form-control btn btn-warning text-xs" value="Limpiar">
            </div>
        </div>
    </section>
    <section>
        <div class="row">
            <div class="form-group col-sm-12 col-md-12 col-xs-12">
                <table id="tblnrocontrol" class="isplay responsive nowrap table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">Item</th>
                            <th scope="col">Empresa</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Número</th>
                            <th scope="col">Control</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tblDetnrocontrol"></tbody>
                    <tfoot>
                        <th scope="col">Item</th>
                        <th scope="col">Empresa</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Número</th>
                        <th scope="col">Control</th>
                        <th scope="col">Acción</th>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
</div>
<?php footerAdmin($data);?>