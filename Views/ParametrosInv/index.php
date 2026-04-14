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
                        <a href="<?= base_url ?>/ParametrosInv/nuevo" title="Nuevo registro">
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
         <th>Empresa</th>
         <th style="width:10%;">Acciones</th>
         <tbody>
            <?php if(is_iterable($r)) : ?>
               <?php foreach ($r as $p) : ?>
                  <tr>
                     <td><?= $p->id ;?></td>
                     <td><?= $p->nombre_emp ;?></td>
                     <td>
                        <?php if (Permisos::updater()) : ?>
                           <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/ParametrosInv/edit/' .  $p->id; ?>"><i class="fa fa-edit"></i></a>
                        <?php endif ?>
                        <?php if (Permisos::deleter()) : ?>
                           <button id="Data" data-id="<?php echo $p->id ?>" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)"><i class="fa fa-trash"></i></button>
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