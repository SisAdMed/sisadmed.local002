<?php headerAdmin($data); ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1><?php echo $data['page_name']; ?>
                        <?php if (Permisos::create()) : ?>
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url ?>/Productos/nuevo" title="Nuevo registro"><i class="fa fa-plus-circle"></i></a></li>
                            </ol>
                        <?php endif ?>
                    </h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <?php echo Alertas::mostrarAlerta() ?>
        <table id="tblTable_prod" class="display responsive nowrap table table-hover" style="width:100%">
            <thead>
                <th>Id</th>
                <th>Código</th>
                <th>Código 2</th>
                <th>Descripción</th>
                <th>Referencia</th>
                <th>Marca</th>
                <th>Foto</th>
                <th>Cant.</th>
                <?php if ($_SESSION['administrator'] == 1) : ?>
                    <th>Costo</th>
                    <th>Flete</th>
                    <th>Otros cargos</th>
                    <th>Cargos Door to Door</th>
                    <th>Costo 1</th>
                    <th>% Utilidad</th>
                    <th>Venta</th>
                    <th>Bul.Emp</th>
                    <th>Unidades</th>
                    <th>Empaque</th>
                    <th>Grupo</th>
                    <th>Nombre Genérico</th>
                    <th>Descripción del Producto</th>
                    <th>Un.Compra</th>
                    <th>Un.Venta</th>
                    <th>Util.Consig</th>
                    <th>Venta Consig</th>
                    <th>IVA</th>
                    <th>Lote</th>
                    <th>Interno</th>
                    <th>Door to Door</th>
                    <th>% Utilidad Consig.</th>
                    <th>Venta Consignación</th>
                    <th>Origen</th>
                    <th>Alto</th>
                    <th>Ancho</th>
                    <th>Largo</th>
                    <th>Adicional</th>
                <?php endif ?>
                <th>Stock</th>
                <th class="text-center">Status</th>
                <th class="text-center">Acciones</th>
            </thead>

            <tbody>
                <?php if (is_iterable($objeto)) : ?>
                    <?php foreach ($objeto as $r) : ?>
                        <tr>
                            <td><?php echo $r->id_prod; ?></td>
                            <td><?php echo $r->cod_prod; ?></td>
                            <td><?php echo $r->cod2_prod; ?></td>
                            <td><?php echo $r->nom_prod; ?></td>
                            <td><?php echo $r->ref_prod; ?></td>
                            <td><?php echo $r->nom_fab; ?></td>
                            <?php if ($r->fotos > 0) : ?>
                                <td class="text-center"><span class="badge badge-success">Si</span></td>
                            <?php else : ?>
                                <td class="text-center"><span class="badge badge-danger">No</span></td>
                            <?php endif ?>
                            <td><?php echo $r->fotos; ?></td>
                            <?php if (isset($_SESSION['id_rol']) && $_SESSION['administrator'] == 1) : ?>
                                <td><?php echo $r->costo_prod; ?></td>
                                <td><?php echo $r->flete_prod; ?></td>
                                <td><?php echo $r->otros_prod; ?></td>
                                <td><?php echo $r->door_costo; ?></td>
                                <td><?php echo ($r->costo_prod + $r->flete_prod + $r->otros_prod + $r->door_costo); ?></td>
                                <td><?php echo $r->recar_prod; ?></td>
                                <td><?php echo $r->ventas_prod; ?></td>
                                <td><?php echo $r->bultos ?></td>
                                <td><?php echo $r->nom_pre ?></td>
                                <td><?php echo $r->empaque ?></td>
                                <td><?php echo $r->grupo_nombre; ?></td>
                                <td><?php echo $r->gen_prod; ?></td>
                                <td><?php echo $r->des_prod; ?></td>
                                <td><?php echo $r->uni_com_prod; ?></td>
                                <td><?php echo $r->uni_ven_prod; ?></td>
                                <td><?php echo $r->con_cons_prod; ?></td>
                                <td><?php echo $r->conv_prod_cons; ?></td>
                                <td><?php echo $r->iva_prod; ?></td>
                                <td><?php echo $r->lote_prod; ?></td>
                                <td><?php echo $r->interno_prod; ?></td>
                                <td><?php echo $r->door_prod; ?></td>
                                <td><?php echo $r->recar2_prod ?></td>
                                <td><?php echo $r->venta2_prod ?></td>
                                <td><?php echo $r->origen ?></td>
                                <td><?php echo $r->alto; ?></td>
                                <td><?php echo $r->ancho; ?></td>
                                <td><?php echo $r->largo; ?></td>
                                <td><?php echo $r->adicional; ?></td>
                            <?php endif ?>
                            <td><?php echo $r->stock; ?></td>
                            <?php if ($r->status == "1") : ?>
                                <td class="text-center"><span class="badge badge-success">Activo</span></td>
                            <?php else : ?>
                                <td class="text-center"><span class="badge badge-danger">Inactivo</span></td>
                            <?php endif ?>
                            <td class="text-center">
                                <?php if (Permisos::updater() || Permisos::read()) : ?>
                                    <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/Productos/edit/' .  $r->id_prod; ?>"><i class="fa fa-edit"></i></a>
                                <?php endif ?>
                                <?php if (Permisos::deleter()) : ?>
                                    <button id="Data" data-id="<?php echo $r->id_prod ?>" data-name="<?php echo $r->nom_prod ?>" data-code="<?php echo $r->cod_prod; ?>" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)"><i class="fa fa-trash"></i></button>
                                <?php endif ?>
                                <?php if (isset($_SESSION['id_rol']) && $_SESSION['administrator'] == 1) : ?>
                                    <button id="Data" data-id="<?php echo $r->id_prod ?>" data-name="<?php echo $r->nom_prod ?>" data-code="<?php echo $r->cod_prod; ?>" type="button" class="btn btn-primary btn-xs" onclick="copiarBtn(this)"><i class="fa fa-copy" title="Copiar producto"></i></button>
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