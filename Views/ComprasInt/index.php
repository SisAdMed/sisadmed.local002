<?php headerAdmin($data); ?>
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               <h1><?php echo $data['page_name']; ?>
                  <?php if (Permisos::create()) : ?>
                     <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url ?>/ComprasInt/nuevo" title="Nuevo registro"><i class="fa fa-plus-circle"></i></a></li>
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
            <th>Registro</th>
            <th>Fecha</th>
            <th>Proveedor</th>
            <th class="text-center">Status</th>
            <th class="text-center">Acciones</th>
         </thead>
         <tbody>
            <?php if (is_iterable($r)) : ?>
               <?php foreach ($r as $r) : ?>
                  <tr>
                     <td><?= $r->id_comint ?></td>
                     <td><?= $r->id_comint ?></td>
                     <td><?= formatFecha($r->fecha_comint) ?></td>
                     <td><?= $r->nombre_provint ?></td>
                     <?php if ($r->status == "1") : ?>
                        <td class="text-center"><span class="badge badge-success">Activo</span></td>
                     <?php else : ?>
                        <td class="text-center"><span class="badge badge-danger">Inactivo</span></td>
                     <?php endif ?>
                     <td class="text-center">
                        <?php if (Permisos::updater() and $r->status == "1") : ?>
                           <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/ComprasInt/edit/' .  $r->id_comint; ?>" title="Editar"><i class="fa fa-edit"></i></a>
                        <?php endif ?>
                        <?php if (Permisos::deleter() and $r->status == "1") : ?>
                           <button id="Data" data-id="<?php echo $r->id_comint ?>" data-name="<?php echo $r->fecha_comint ?>" type="button" class="btn btn-danger btn-xs btn-delete-index" title="Eliminar"><i class="fa fa-trash"></i></button>
                           <button id="Data" data-id="<?= $r->id_comint ?>" data-code="PDF" type="button" class="btn btn-danger btn-xs" onclick="print_comint(this)" title="Imprimir PDF"><i class="fa-solid fa-file-pdf"></i></button>
                           <button id="Data" data-id="<?= $r->id_comint ?>" data-code="EXCEL" type="button" class="btn btn-success btn-xs" onclick="print_comint(this)" title="Imprimir Excel"><i class="fa-solid fa-file-excel"></i></button>
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