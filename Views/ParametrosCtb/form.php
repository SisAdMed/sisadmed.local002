<section class="content">
    <!-- Tabla -->
    <div class="card-body">
        <input type="hidden" id="id"            name="id"           value="<?= $r->id_config ?? '' ?>">
        <div class="row">
            <div class="form-group col-md-4-12 col-sm-12 col-xs-12">
                <label for="id_emp">Empresa <span>*</span></label>
                <select name="id_emp" id="id_emp" class="form-control"></select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                <label for="id_tipcom">Tipo de Comprobante Contable <span>*</span></label>
                <select name="id_tipcom" id="id_tipcom" class="form-control">
                     <?php
                        SelTipoComprobantes($r->id_tipcom ?? '');
                        ?>
                </select>
            </div>
            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                <label for="id_tipcomp">Tipo de Comprobante Presupuesto</label>
                <select name="id_tipcomp" id="id_tipcomp" class="form-control">
                     <?php
                        SelTipoComprobantes($r->id_tipcomp ?? '');
                        ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                <label for="id_cuegyp">Cuenta Contable de Ganancias y/o Perdidas
                    <select name="id_cuegyp" id="id_cuegyp" class="form-control select2 select2bs4">
                        <?php
                        selCteCble($r->id_cuegyp ?? '');
                        ?>
                    </select>
                </label>
            </div>
            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                <label for="consecu_config">¿Desea consecutivos en comprobantes?
                    <select name="consecu_config" id="consecu_config" class="form-control select2 select2bs4" >
                        <?php
                        agrupador($r->consecu_config ?? '');
                        ?>
                    </select>
                </label>
            </div>
            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                <label for="numdia_config">¿Desea consecutivos por día?
                    <select name="numdia_config" id="numdia_config" class="form-control select2 select2bs4">
                        <?php
                        agrupador($r->numdia_config ?? '');
                        ?>
                    </select>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                <label for="id_cuenom">Nómina por pagar
                    <select name="id_cuenom" id="id_cuenom" class="form-control select2 select2bs4">
                        <?php
                        selCteCble($r->id_cuenom ?? '');
                        ?>
                    </select>
                </label>
            </div>
            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                <label for="id_cueval">Valoración teórica del Inventario
                    <select name="id_cueval" id="id_cueval" class="form-control select2 select2bs4">
                        <?php
                        selCteCble($r->id_cueval ?? '');
                        ?>
                    </select>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                <label for="id_cuecos">Costo de ventas
                    <select name="id_cuecos" id="id_cuecos" class="form-control select2 select2bs4">
                        <?php
                        selCteCble($r->id_cuecos ?? '');
                        ?>
                    </select>
                </label>
            </div>
            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                <label for="id_cueinv">Variación de Inventario
                    <select name="id_cueinv" id="id_cueinv" class="form-control select2 select2bs4" >
                        <?php
                        selCteCble($r->id_cueinv ?? '');
                        ?>
                    </select>
                </label>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
