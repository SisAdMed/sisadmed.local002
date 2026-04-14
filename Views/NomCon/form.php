<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-nomcon" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="nomcon-basic-tab" data-toggle="pill" href="#nomcon-basic" role="tab" aria-controls="nomcon-basic" aria-selected="true">Conceptos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nomcon-detail-tab" data-toggle="pill" href="#nomcon-detail" role="tab" aria-controls="nomcon-detail" aria-selected="true">Conceptos de Integracíon</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="card body">
    <div class="tab-content" id="custom-nomcon-tabcontent">
    <input type="text" name="id" id="id" value="<?= $r[0]['id_nomcue'] ?? '' ?>" hidden>
        <div class="tab-pane fade show active" id="nomcon-basic" role="tabpanel" aria-labelledby="nomcon-basic-tab">
            <div class="row">
                <div class="form-group col-md-1 col-sm-1 col-xs-12">
                    <label for="codigo">Concepto</label>
                    <input type="text" class="form-control validar text-right" name="codigo" id="codigo">
                    <span></span>
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" >
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="tipo">Tipo</label>
                    <select name="tipo" id="tipo" class="form-control"></select>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="parametro">Parámetro</label>
                    <select name="parametro" id="parametro" class="form-control"></select>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12" id="factopmo">
                    <label for="factop" class="factop">Factor/Tope/Multi</label>
                    <input type="text" class="form-control text-right validar" name="factop" id="factop">
                </div>
                <div class="form-group col-md-1 col-sm-1 col-xs-12" id="nomunimo">
                    <label for="nomuni">Cantidad</label>
                    <input type="text" class="form-control text-right" name="nomuni" id="nomuni">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-2 col-sm-2 col-xs-12" id="nomfjumo">
                    <label for="nomfju">Afectada por Faltas Justificadas</label>
                    <select name="nomfju" id="nomfju" class="form-control"></select>
                </div>
                <div class="form-group col-md-4 col-sm-4 col-xs-12">
                    <label for="nom_ctb">Cuenta contable</label>
                    <input type="hidden" name="id_ctb" id="id_ctb">
                    <div class="input-group">
                        <input type="text" class="form-control text-xs" id="nom_ctb" name="nom_ctb" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text nom_ctb"><a href="#" data-toggle="modal" data-target="#modal-CuentasCtb" title="Buscar y seleccionar Cuentas de Nómina"> <i class="fas fa-search text-xs"></i></a></span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control"></select>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show" id="nomcon-detail" role="tabpanel" aria-labelledby="nomcon-detail-tab">
            <div class="row">
                <div class="form-group col-sm-12 col-xs-12 col-md-12">
                    <table id="tbl_nomdcu" class="display responsive nowrap table table-hover" style="width:100%">
                        <h5 class="text-center">DETALLE DE CONCEPTOS DE INTEGRACIÓN
                        </h5>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Concepto</th>
                                <th>Descrípción</th>
                                <th>Tipo</th>
                                <th>Parámetro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    </table>
                    <div>
                        <button type="button" class="btn btn-primary btn-sm text-xs" onclick="agregarDetalleConcepto();">+ Agregar Concepto Detalle</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/Views/CuentasCtb/modal_CuentasCtb.php';
    require_once __DIR__.'/modal_ConceptosNom.php';
    ?>
</div>