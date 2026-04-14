<?php headerAdmin($data);?>
<!-- CONTENIDO DINAMICO -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1><?php echo $data['page_name']; ?>
                        <?php if (Permisos::create()) : ?>
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url ?>/Monedas/nuevo" title="Nuevo registro"><i class="fa fa-plus-circle"></i></a></li>
                            </ol>
                        <?php endif ?>
                    </h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Tabla -->
        <?php echo Alertas::mostrarAlerta() ?>
        <table id="tblTable" class="display responsive nowrap table table-hover" style="width:100%">
            <thead>
                <th>Id</th>
                <th>País</th>
                <th>Moneda</th>
                <th>Nombre</th>                
                <th>Acciones</th>
            </thead>
            <tbody>
                <?php if(is_iterable($objeto)) : ?>
                    <?php foreach ($objeto as $r) : ?>
                        <tr>
                            <td><?php echo $r->id_moneda; ?></td>
                            <td><?php echo $r->nombre_pais; ?></td>
                            <td><?php echo $r->codigo_moneda; ?></td>
                            <td><?php echo $r->nombre_moneda; ?></td>                                                   
                            <!--Acciones-->
                            <td>
                                <?php if (Permisos::updater()) : ?>
                                    <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/Monedas/edit/' .  $r->id_moneda; ?>"><i class="fa fa-edit"></i></a>
                                <?php endif ?>
                                <?php if (Permisos::deleter()) : ?>
                                    <button id="Data" data-id="<?php echo $r->id_moneda ?>" data-name="<?php echo $r->nombre_moneda ?>" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)"><i class="fa fa-trash"></i></button>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </section>
    <!-- /.content -->
</div>
<!-- /.CONTENIDO DINAMICO -->
<?php footerAdmin($data); ?>