<?php headerAdmin($data); ?>
<div class='content-wrapper'>
    <section class='content-header'>
        <div class='container-fluid'>
            <div class='row mb-2'>
                <div class='col-sm-12'>
                    <h1><?php echo $data['page_name']; ?>
                        <?php if (Permisos::create()) : ?>
                            <ol class='breadcrumb float-sm-right'>
                                <li class='breadcrumb-item'><a href='<?= base_url ?>/Grupos/nuevo' title='Nuevo registro'><i class='fa fa-plus-circle'></i></a></li>
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
                <th>Grupo</th>
                <th>Descripción</th>
                <th>Ver in Internet</th>
                <th>Status</th>
                <th>Acciones</th>
            </thead>
            <tbody id='tblTableDet'>
                <?php foreach ($objeto as $r) : ?>
                    <tr>
                        <td><?= $r->id_grupo; ?></td>
                        <td><?= $r->grupo_codigo; ?></td>
                        <td><?= $r->grupo_nombre; ?></td>
                        <td>
                            <?php if ($r->view_internet) : ?>
                                <input type="checkbox" checked disabled>
                            <?php else : ?>
                                <input type="checkbox" disabled>
                            <?php endif ?>
                        </td>

                        <!--Status-->
                        <?php if ($r->status) : ?>
                            <td><span class="badge badge-success">Activo</span></td>
                        <?php else : ?>
                            <td><span class="badge badge-danger">Inactivo</span></td>
                        <?php endif ?>
                        <!--Acciones-->
                        <td>
                            <?php if (Permisos::updater()) : ?>
                                <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/Grupos/edit/' .  $r->id_grupo; ?>"><i class="fa fa-edit"></i></a>
                            <?php endif ?>
                            <?php if (Permisos::deleter()) : ?>
                                <button id="Data" data-id="<?php echo $r->id_grupo ?>" data-name="<?php echo $r->grupo_nombre ?>" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)"><i class="fa fa-trash"></i></button>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
            <tfoot>
                <th>Id</th>
                <th>Grupo</th>
                <th>Descripción</th>
                <th>Ver in Internet</th>
                <th>Status</th>
                <th>Acciones</th>
                </thead>
        </table>
    </section>
</div>
<?php footerAdmin($data); ?>