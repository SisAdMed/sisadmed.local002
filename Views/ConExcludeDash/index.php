<?php headerAdmin($data); ?>
<div class='content-wrapper'>
    <section class='content-header'>
        <div class='container-fluid'>
            <div class='row mb-2'>
                <div class='col-sm-12'>
                    <h1><?php echo $data['page_name']; ?>
                        <?php if (Permisos::create()) : ?>
                            <ol class='breadcrumb float-sm-right'>
                                <li class='breadcrumb-item'><a href='<?= base_url ?>/ConExcludeDash/new' title='Nuevo registro'><i class='fa fa-plus-circle'></i></a></li>
                            </ol>
                        <?php endif ?>
                </div>
            </div>
        </div>
    </section>
    <section class='content'>
        <?php echo Alertas::mostrarAlerta() ?>
        <table id='tblTable' class='display responsive nowrap table table-hover' style='width:100%'>
            <thead>
                <th>Id</th>
                <th>Módulo</th>
                <th>Concepto</th>
                <th class="text-center">Status</th>
                <th class="text-center">Acciones</th>
            </thead>
            <tbody>
                <?php if (is_iterable($objeto)) : ?>
                    <?php foreach ($objeto as $r) : ?>
                        <tr>
                            <td><?= $r->id ?></td>
                            <td><?= $r->nombre ?></td>
                            <td><?= $r->cod_con . ' - ' . $r->nom_con ?></td>
                            <?php if ($r->status == 1) : ?>
                                <td class="text-center"><span class="badge badge-success">Activo</span></td>
                            <?php else : ?>
                                <td class="text-center"><span class="badge badge-danger">Inactivo</span></td>
                            <?php endif ?>
                            <td class="text-center">
                                <?php if (Permisos::updater()) : ?>
                                    <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/ConExcludeDash/edit/' .  $r->id; ?>"><i class="fa fa-edit"></i></a>
                                <?php endif ?>
                                <?php if (Permisos::deleter()) : ?>
                                    <button id="Data" data-id="<?php echo $r->id ?>" data-name="<?php echo $r->cod_con . ' - ' . $r->nom_con ?>" data-code="<?php echo $r->nombre; ?>" type="button" class="btn btn-danger btn-xs delete-row"><i class="fa fa-trash"></i></button>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
            <tfoot>
                <th>Id</th>
                <th>Módulo</th>
                <th>Concepto</th>
                <th>Status</th>
                <th>Acciones</th>
                </thead>
        </table>
    </section>
</div>
<?php footerAdmin($data); ?>