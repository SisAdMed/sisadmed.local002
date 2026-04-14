<?php headerAdmin($data); ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1><?php echo $data['page_name'] ;?>
                        <?php if(Permisos::create()) :; ?>
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url ?>/Bancos/nuevo" title="Nuevo registro "><i class="fa fa-plus-circle"></i></a></li>
                            </ol>
                        <?php endif; ?>
                    </h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <?php echo Alertas::mostrarAlerta() ?>
        <table id="tblTable" class="display responsive nowarp table table-hover" style="width:100%">
            <thead>
                <th>Id</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Extranjero</th>
                <th>Teléfono(s)</th>
                <th class="text-center">Status</th>
                <th class="text-center">Acciones</th>
            </thead>
            <tbody>
                <?php if(is_iterable($r)) : ?>
                    <?php foreach ($r as $o) : ?>
                        <tr>
                            <td><?php echo $o->id_banco ; ?></td>
                            <td><?php echo $o->cod_banco ; ?></td>
                            <td><?php echo $o->nombre_banco ; ?></td>
                            <td><?php echo $o->extranjero_ban ; ?></td>
                            <td><?php echo $o->tel_banco ; ?></td>
                            <?php if ($o->status == 1) : ?>
                                <td class="text-center"><span class="badge badge-success">Activo</span></td>
                            <?php else : ?>
                                <td class="text-center"><span class="badge badge-danger">Inactivo</span></td>
                            <?php endif ?>
                            <td class="text-center">
                                <?php if (Permisos::updater()) : ?>
                                    <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/Bancos/edit/'.$o->id_banco; ?>"><i class="fa fa-edit"></i></a>
                                <?php endif ?>
                                <?php if (Permisos::deleter()) : ?>
                                    <button id="Data" data-id="<?php echo $o->id_banco ?>" data-name="<?php echo $o->nombre_banco ?>" data-code = "<?php echo $o->cod_banco; ?>" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)"><i class="fa fa-trash"></i></button>
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