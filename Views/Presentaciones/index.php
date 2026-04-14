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
                        <a href="<?= base_url ?>/Presentaciones/nuevo" title="Nuevo registro">
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
         <th>Código</th>
         <th>Nombre</th>
         <th>Status</th>
         <th>Acciones</th>
         <tbody>
            <?php if(is_iterable($objeto)) : ?>
               <?php foreach ($objeto as $r) : ?>
                  <tr>
                     <td><?= $r->id_pre ;?></td>
                     <td><?= $r->cod_pre ;?></td>
                     <td><?= $r->nom_pre ;?></td>
                     <?php if($r->status) : ?>
                        <td><span class="badge badge-success">Activo</span></td>
                     <?php else :?>
                        <td><span class="badge badge-danger">Activo</span></td>
                     <?php endif; ?>
                     <td>
                        <?php if (Permisos::updater()) : ?>
                           <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/Presentaciones/edit/' .  $r->id_pre; ?>"><i class="fa fa-edit"></i></a>
                        <?php endif ?>
                        <?php if (Permisos::deleter()) : ?>
                           <button id="Data" data-id="<?php echo $r->id_pre ?>" data-name="<?php echo $r->nom_pre ?>" data-code = "<?php echo $r->cod_pre; ?>" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)"><i class="fa fa-trash"></i></button>
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