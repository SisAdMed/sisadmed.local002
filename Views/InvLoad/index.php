<?php headerAdmin($data);?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1><?php echo $data['page_name']; ?>
                    <?php if (Permisos::create()) : ?>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url ?>/InvLoad/nuevo" title="Nuevo registro"><i class="fa fa-plus-circle"></i></a></li>
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
            <th>Empresa</th>
            <th>Tipo</th>
            <th>Descripción</th>
            <th class="text-right">Número</th>
            <th>Fecha</th>
            <th>Almacén</th>
            <th>Modo</th>
            <th class="text-center">Status</th>
            <th class="text-center">Acciones</th>
        </thead>
        <tbody>
            <?php if(is_iterable($objeto)) : ?>
                <?php foreach ($objeto as $r) : ?>
                    <tr>
                        <td><?php echo $r->id_movinv; ?></td>
                        <td><?php echo $r->nombre_emp; ?></td>
                        <td><?php echo $r->cod_tmoinv; ?></td>
                        <td><?php echo $r->nom__tmoinv; ?></td>
                        <td class="text-right"><?php echo $r->num_InvLoad;?></td>
                        <td><?php echo formatFecha($r->fecha_comp);?></td>
                        <td><?php echo $r->nom_alm;?></td>
                        <!-- //Modo -->
                        <?php if ($r->modo == "P") : ?>
                            <td class="text-center"><span class="badge badge-info">Preliminar</span></td>
                        <?php else : ?>
                            <td class="text-center"><span class="badge badge-success">Definitivo</span></td>
                        <?php endif ?>
                        <!-- //Status -->
                        <?php if ($r->status == "1") : ?>
                            <td class="text-center"><span class="badge badge-success">Activo</span></td>
                        <?php else : ?>
                            <td class="text-center"><span class="badge badge-danger">Inactivo</span></td>
                        <?php endif ?>
                        <td class="text-center">
                            <?php if (Permisos::updater()) : ?>
                                <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/InvLoad/edit/' .  $r->id_movinv; ?>"><i class="fa fa-edit" title="Editar"></i></a>
                            <?php endif ?>
                            <?php if (Permisos::deleter() && ($r->modo == 'P')) : ?>
                                <button id="Data" data-id="<?php echo $r->id_movinv ?>" data-name="<?php echo $r->nom__tmoinv ?>" data-code = "<?php echo $r->cod_tmoinv; ?>" data-number = "<?php echo $r->num_InvLoad; ?>" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)" title="Eliminar"><i class="fa fa-trash"></i></button>
                             <?php endif ?>
                        <?php if (Permisos::updater() && ($r->modo == 'P')) : ?>
                        <button id="Dataapp" data-id="<?php echo $r->id_movinv ?>" data-name="<?php echo $r->nom__tmoinv . ' - Número ' . $r->num_InvLoad?>" data-code = "<?php echo $r->cod_tmoinv; ?>"  data-number = "<?php echo $r->cod_tmoinv; ?>" type="button" class="btn btn-primary btn-xs" onclick="approveBtn(this)" title="Aprobar"><i class="fa fa-check"></i></button>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
    <?php endif ?>
</tbody>
<tfoot>
    <th>Id</th>
    <th>Empresa</th>
    <th>Tipo</th>
    <th>Descripción</th>
    <th class="text-right">Número</th>
    <th>Fecha</th>
    <th>Modo</th>
    <th class="text-center">Status</th>
    <th class="text-center">Acciones</th>
</tfoot>
</table>
</section>
</div>
<?php footerAdmin($data); ?>