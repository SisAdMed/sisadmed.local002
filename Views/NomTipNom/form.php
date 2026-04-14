<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-NomTipNom" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="NomTipNom-basic-tab" data-toggle="pill" href="#NomTipNom-basic" role="tab" aria-controls="NomTipNom-basic" aria-selected="true">Tipo de nómina</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="NomTipNom-detail-tab" data-toggle="pill" href="#NomTipNom-detail" role="tab" aria-controls="NomTipNom-detail" aria-selected="true">Movimientos fijos de aplicación aútomatica</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="card body">
    <input type="text" name="id" id="id" value="<?= $r->id_nomtip ?? '' ?>" hidden>
    <div class="tab-content" id="custom-NomTipNom-tabcontent">
        <div class="tab-pane fade show active" id="NomTipNom-basic" role="tabpanel" aria-labelledby="NomTipNom-basic-tab">
            <div class="row">
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label for="id_emp">Empresa</label>
                    <select name="id_emp" id="id_emp" class="form-control"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-1 col-sm-1 col-xs-12">
                    <label for="codigo">Código</label>
                    <input type="text" class="form-control" name="codigo" id="codigo" onkeyup="mayusculas(this)">
                </div>
                <div class="form-group col-md-5 col-sm-5 col-xs-12">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre"  onkeyup="mayusculas(this)">"
                </div>
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="freq">Frecuencia</label>
                    <select name="freq" id="freq" class="form-control"></select>
                </div>
                 <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="tipo">Tipo</label>
                    <select name="tipo" id="tipo" class="form-control"></select>
                </div>
                 <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="nomcue">Concepto Sueldo</label>
                    <select name="nomcue" id="nomcue" class="form-control"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="antcue">Concepto Anticipo</label>
                    <select name="antcue" id="antcue" class="form-control"></select>
                </div>
                 <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="antpor">Anticipo < % ></label>
                    <input type="number" class="form-control text-right" name="antpor" id="antpor">
                </div>
                 <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="contrato">Contrato</label>
                    <select name="contrato" id="contrato" class="form-control"></select>
                </div>
                 <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="fecha">Última nómina</label>
                    <input type="date" class="form-control" name="fecha" id="fecha">
                </div>
                 <div class="form-group col-md-2 col-sm-2 col-xs-12">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control"></select>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show" id="NomTipNom-detail" role="tabpanel" aria-labelledby="NomTipNom-detail-tab">
        </div>
    </div>
</div>