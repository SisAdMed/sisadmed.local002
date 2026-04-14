<?php headerAdmin($data);?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1><?php echo $data['page_name']; ?>
                        <?php if (Permisos::create()) : ?>
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url ?>/ActPrecios/nuevo" title="Nuevo registro"><i class="fa fa-plus-circle"></i></a></li>
                            </ol>
                        <?php endif ?>
                    </h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <?php echo Alertas::mostrarAlerta() ?>
        <table id="tblTable" class="display responsive nowrap table table-hover" style="width:100%">
            <thead>
                <th>Id</th>
                <th>Fec.Creado</th>
                <th>Fec.Vig.</th>
                <th>Fec.Apro.</th>
                <th>Descripción</th>
                <th class="text-center">Status</th>
                <th class="text-center">Acciones</th>
            </thead>
            <tbody>
                <?php if(is_iterable($objeto)) : ?>
                    <?php foreach ($objeto as $r) : ?>
                        <tr>
                            <?php $fecha_creacion =  new DateTime($r->fecha_creacion ?? ''); ?>
                            <?php $fecha_vigencia =  new DateTime($r->fecha_vigencia ?? ''); ?>
                            <?php $fecha_aprobado =  new DateTime($r->fecha_aprobado ?? ''); ?>
                            <td><?php echo $r->id_pro_his; ?></td>
                            <td><?php echo $fecha_creacion->format('d-m-Y H:i:s'); ?></td>
                            <?php if($r->fecha_vigencia != '0000-00-00 00:00:00') : ?>
                                <td><?php echo $fecha_vigencia->format('d-m-Y H:i:s'); ?></td>
                            <?php else : ?>
                                <td></td>
                            <?php endif ?>

                            <?php if($r->fecha_aprobado != '0000-00-00 00:00:00') : ?>
                                <td><?php echo $fecha_aprobado->format('d-m-Y H:i:s'); ?></td>
                            <?php else : ?>
                                <td></td>
                            <?php endif ?>
                            <td><?= $r->observa ?? '' ;?></td>
                            <?php if ($r->status == 'A') : ?>
                                <td class="text-center"><span class="badge badge-success">Activo</span></td>
                            <?php else : ?>
                                <td class="text-center"><span class="badge badge-danger">Inactivo</span></td>
                            <?php endif ?>
                            <td class="text-center">
                                <?php if (Permisos::updater()) : ?>
                                    <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/ActPrecios/edit/' .  $r->id_pro_his; ?>"><i class="fa fa-edit"></i></a>
                                <?php endif ?>
                                <?php if (Permisos::deleter()) : ?>
                                    <button id="Data" data-id="<?php echo $r->id_pro_his ?>" data-name="<?php echo $r->fecha_creacion ?>" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)"><i class="fa fa-trash"></i></button>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </section>
</div>
<?php footerAdmin($data); ?>