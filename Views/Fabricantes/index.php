<?php headerAdmin($data)?>
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <class class="row mb-2">
            <div class="col-sm-12">
               <h1><?php echo $data['page_name']; ?>
               <?php if(Permisos::create()) : ?>
                  <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item">
                        <a href="<?= base_url ?>/Fabricantes/nuevo" title="Nuevo registro">
                           <i class="fa fa-plus-circle"></i>
                        </a>
                     </li>
                  </ol>
               <?php endif; ?>
            </h1>
         </div>
      </class>
   </div>
</section>
<section class="content">
   <?php Alertas::mostrarAlerta() ?>
   <table id="tblTable" class="display responsive nowrap table table-hover" style="width: 100%;">
      <thead>
         <th>Id</th>
         <th>Nombre</th>
         <th>No Calcula Adicional</th> -->
         <th>Status</th>
         <th>Acciones</th>
         <tbody>
            <?php if(is_iterable($objeto)) : ?>
               <?php foreach ($objeto as $r) : ?>
                  <tr>
                     <td><?= $r->id_fab ;?></td>
                     <td><?= $r->nom_fab ;?></td>
                     <?php if($r->adicional01 == 1) : ?>
                        <td><input type="checkbox" checked disabled></td>
                     <?php else :?>
                           <td><input type="checkbox" unchecked disabled></td>
                     <?php endif ?>

               <!--       <td class="text-right"><?php echo formatNumber($r->adicional01 ?? 0,2) ?></td> -->
                     <?php if($r->status == 1) : ?>
                        <td><span class="badge badge-success">Activo</span></td>
                     <?php else :?>
                        <td><span class="badge badge-danger">Activo</span></td>
                     <?php endif; ?>
                     <td>
                        <?php if (Permisos::updater()) : ?>
                           <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/Fabricantes/edit/' .  $r->id_fab; ?>"><i class="fa fa-edit"></i></a>
                        <?php endif ?>
                        <?php if (Permisos::deleter()) : ?>
                           <button id="Data" data-id="<?php echo $r->id_fab ?>" data-name="<?php echo $r->nom_fab ?>" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)"><i class="fa fa-trash"></i></button>
                        <?php endif ?>
                     </td>
                  </tr>
               <?php endforeach; ?>
            <?php endif; ?>
         </tbody>
      </thead>
   </table>
</section>
</div>
<?php footerAdmin($data)?>