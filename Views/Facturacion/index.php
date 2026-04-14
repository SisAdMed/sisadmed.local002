<?php headerAdmin($data);?>
<div class="content-wrapper">
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-12">
               <h1><?php echo $data['page_name']; ?>
               <?php if (Permisos::create()) : ?>
                  <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="<?= base_url?>/Facturacion/nuevo?tipo=<?= $_SESSION['tipo_fact']?>" title="Nuevo registro"><i class="fa fa-plus-circle"></i></a></li>
                  </ol>
               <?php endif ?>
            </h1>
         </div>
      </div>
   </div>
</section>
<section class="content">
   <?php echo Alertas::mostrarAlerta() ?>
   <table id="tblTable" class="display responsive nowrap table table-hover text-xs" style="width:100%">
      <thead>
         <th>Id</th>
         <th>Empresa</th>
         <th>Tipo</th>
         <th class="text-right">Número</th>
         <th class="text-right">Número de Control</th>
         <th>Cliente</th>
         <th>Fecha</th>
         <th>Moneda</th>
         <th class="text-right">Tasa</th>
         <th>Vendedor</th>
         <th>Origen</th>
         <th class="text-center">Status</th>
         <th class="text-center">Acciones</th>
      </thead>
      <tbody>
         <?php if(is_iterable($objeto)) : ?>
            <?php foreach ($objeto as $r) : ?>
               <?php
                  $style = "";
                  if($r->penapro == 1){
                     $style = 'style="background-color:#FF7F50"';
                  }
               ?>
               <tr title="<?= ($r->penapro == 1 ? "Pendiente por Aprobación" : "" )?>" <?= $style ?>>
                  <td><?= $r->id_cot ?></td>
                  <td><?= $r->nombre_emp ?></td>
                  <td><?= $r->nom_tdoc ?></td>
                  <td class="text-right"><?= $r->num_tdo ?></td>
                  <td class="text-right"><?= $r->nro_control ?></td>
                  <td><?= $r->nom_ent ?></td>
                  <td><?= formatFecha($r->fecha_comp) ?></td>
                  <td><?= $r->codigo_moneda ?></td>
                  <td class="text-right"><?= number_format($r->tasa_cambio,4,",",".") ?></td> 
                  <td><?= $r->nom_vend ?></td>
                  <td><?= $r->fuente ?></td>
                  <?php if ($r->status == "1") : ?>
                     <td class="text-center"><span class="badge badge-success">Activo</span></td>
                  <?php else : ?>
                     <td class="text-center"><span class="badge badge-danger">Inactivo</span></td>
                  <?php endif ?>
                  <td class="text-center">
                     <?php if (Permisos::updater() AND $r->status == "1") : ?>
                        <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/Facturacion/edit/' .  $r->id_cot; ?>" title="Editar"><i class="fa fa-edit"></i></a>
                     <?php endif ?>
                     <?php if (Permisos::deleter() AND $r->status == "1") : ?>
                        <button id="Data" data-id="<?php echo $r->id_cot ?>" data-name="<?php echo $r->nom_tdoc ?>" data-code = "<?php echo $r->num_tdo; ?>" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)" title="Eliminar"><i class="fa fa-trash"></i></button>
                     <?php endif ?>
                     <?php if(Permisos::deleter() AND $r->status == "1" ) : ?>
                        <a type="button" class="btn btn-success btn-xs" onclick="copiarBtn(this)" href="<?php echo base_url . '/Facturacion/nueva/' .  $r->id_cot; ?>" title="Copiar"><i class="fa fa-copy"></i></a>
                     <?php endif ?>
                     <?php if(Permisos::updater() AND $r->status == "1" AND $r->penapro != 1) : ?>
                        <button id="Data" 
                           data-id_cot="<?php echo $r->id_cot ?>" 
                           data-print="<?php echo $r->print_special ?>" 
                           data-moneda="<?php echo $r->id_moneda ?>" 
                           data-tasa="<?php echo $r->tasa_cambio ?>" 
                           type="button" class="btn btn-primary btn-xs" onclick="printer_invoice(this)" title="Imprimir"><i class="fa fa-print"></i></button>
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