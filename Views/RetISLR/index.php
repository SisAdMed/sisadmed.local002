<?php headerAdmin($data);?>
<div class='content-wrapper'>
    <section class='content-header'>
        <div class='container-fluid'>
            <div class='row mb-2'>
                <div class='col-sm-12'>
                    <h1><?php echo $data['page_name']; ?>
                    <?php if (Permisos::create()) :?>
                        <ol class='breadcrumb float-sm-right'>
                            <li class='breadcrumb-item'><a href='<?= base_url ?>/RetISLR/nuevo' title='Nuevo registro'><i class='fa fa-plus-circle'></i></a></li>
                        </ol>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </section>
    <section class='content'>
        <?php echo Alertas::mostrarAlerta()?>
        <table id='tblTable' class='display responsive nowrap table table-hover' style='width:100%'>
            <thead>
                <th>Id</th>
                <th>Fecha Vigencia</th>
                <th>Descripción</th>
                <th class="text-right">Mínimo</th>
                <th class="text-right">Máximo</th>
                <th class="text-right">Porcentaje de Retención</th>
                <th class="text-right">Importe sujeto a retención</th> 
                <th class="text-right">Factor Retención</th>
                <th>Status</th>
                <th>Acciones</th>
            </thead>
            <tbody id='tblTableDet'>
            </tbody>
            <tfoot>
            <th>Id</th>
                <th>Fecha Vigencia</th>
                <th>Descripción</th>
                <th class="text-right">Mínimo</th>
                <th class="text-right">Máximo</th>
                <th class="text-right">Porcentaje de Retención</th>
                <th class="text-right">Importe sujeto a retención</th> 
                <th class="text-right">Factor Retención</th>
                <th>Status</th>
                <th>Acciones</th>
            </thead>
        </table>
    </section>
</div>
<?php footerAdmin($data);?>