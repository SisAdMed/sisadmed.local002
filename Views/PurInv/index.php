<?php headerAdmin($data); ?>
<input type="hidden" id="ori" name="ori" value="<?= $_SESSION['ori'] ?>">
<input type="hidden" id="mod" name="mod" value="P">
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               <h1><?php echo $data['page_name']; ?>
               <?php if (Permisos::create()) : ?>
                  <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="<?= base_url ?>/PurInv/nuevo" title="Nuevo registro"><i class="fa fa-plus-circle"></i></a></li>
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
         <th>Código</th>
         <th>Descripción</th>
         <th>Número</th>
         <th>Fecha</th>
         <th>Proveedor</th>
         <th>Moneda</th>
         <th>Tasa</th>
         <th>Origen</th>
         <th class="text-center">Status</th>
         <th class="text-center">Acciones</th>
      </thead>
      <tbody>
         <?php if(is_iterable($objeto)) : ?>
            <?php foreach ($objeto as $r) : ?>
               <tr>
                  <td><?= $r->id_cot ?></td>
                  <td><?= $r->nombre_emp ?></td>
                  <td><?= $r->tipo_codigo ?></td>
                  <td><?= $r->nom_tdoc ?></td>
                  <td class="text-right"><?= $r->num_tdo ?></td>
                  <td><?= formatFecha($r->fecha_comp) ?></td>
                  <td><?= $r->nom_ent ?></td>
                  <td><?= $r->codigo_moneda ?></td>
                  <td><?= formatNumber($r->tasa_cambio,8) ?></td>
                  <td><?= $r->origen ?></td>
                  <?php if ($r->status == "1") : ?>
                     <td class="text-center"><span class="badge badge-success">Activo</span></td>
                  <?php else : ?>
                     <td class="text-center"><span class="badge badge-danger">Inactivo</span></td>
                  <?php endif ?>
                  <td class="text-center">
                     <?php if (Permisos::updater() AND $r->status == "1") : ?>
                        <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/PurInv/edit/' .  $r->id_cot; ?>" title="Editar"><i class="fa fa-edit"></i></a>
                     <?php endif ?>
                     <?php if (Permisos::deleter() AND $r->status == "1") : ?>
                        <button id="Data" data-id="<?php echo $r->id_cot ?>" data-name="<?php echo 'COM-' . $r->tipo_codigo . '-' . $r->id_emp . '-' . $r->num_tdo ?>" data-code ="<?php echo $r->id_emp ?>" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)" title="Eliminar"><i class="fa fa-trash"></i></button>
                     <?php endif ?>
                     <?php if(Permisos::deleter() AND $r->status == "1" ) : ?>
                        <a type="button" class="btn btn-success btn-xs" onclick="copiarBtn(this)" href="<?php echo base_url . '/PurInv/nueva/' .  $r->id_cot; ?>" title="Copiar"><i class="fa fa-copy"></i></a>
                     <?php endif ?>
                     <?php if(Permisos::updater() AND $r->status == "1") : ?>
                        <a type="button" class="btn btn-primary btn-xs" data-code = "<?php echo $r->num_tdo; ?>" href="<?php echo base_url . '/PurInv/print_RetIva/' . $r->id_cot ?>" target="_blank"><i class="fa-solid fa-print" title="Imprimir"></i></a>
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