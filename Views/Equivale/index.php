<?php headerAdmin($data);?>
<div class='content-wrapper'>
    <section class='content-header'>
        <div class='container-fluid'>
            <div class='row mb-2'>
                <div class='col-sm-12'>
                    <h1><?php echo $data['page_name']; ?>
                    <?php if (Permisos::create()) :?>
                        <ol class='breadcrumb float-sm-right'>
                            <li class='breadcrumb-item'><a href='<?= base_url ?>/Equivale/nuevo' title='Nuevo registro'><i class='fa fa-plus-circle'></i></a></li>
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
                <th>Empresa</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th class="text-center">Formato</th>
                <th class="text-center">Status</th>
                <th class="text-center">Acciones</th>
            </thead>
            <tbody id='tblTableDet'>
                <?php if(is_iterable($objeto)) : ?>
                    <?php foreach ($objeto as $r) : ?>
                        <tr>
                            <td><?= $r->item?></td>
                            <td><?= $r->nombre_emp?></td>
                            <td><?= $r->nom_ent?></td>
                            <td><?= formatFecha($r->fecha)?></td>
                            <td class="text-center"><?= $r->format?></td>
                            <?php if($r->status == 1) : ?>
                                <td class="text-center"><span class="badge badge-success">Activo</span></td>
                            <?php else :?>
                                <td class="text-center"><span class="badge badge-danger">Activo</span></td>
                            <?php endif; ?>
                            <td class="text-center">
                            <?php if (Permisos::updater()) : ?>
                                <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/Equivale/edit/' .  $r->id_emp . '|' . $r->id_ent . '|' . $r->fecha; ?>"><i class="fa fa-edit"></i></a>
                            <?php endif ?>
                            <?php if (Permisos::deleter()) : ?>
                                <button id="Data" data-id="<?php echo $r->id_emp ?>" data-name="<?php echo $r->id_ent ?>" data-code="<?php echo $r->fecha ?>" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)"><i class="fa fa-trash"></i></button>
                            <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <th>Id</th>
                <th>Empresa</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th class="text-center">Formato</th>
                <th class="text-center">Status</th>
                <th class="text-center">Acciones</th>
            </tfoot>
        </table>
        <div class="loader">
            <img src="<?= IMG . '/ajax-loading.gif'  ?>" />
        </div>
    </section>
</div>
<?php footerAdmin($data);?>