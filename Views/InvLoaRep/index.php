<?php headerAdmin($data); ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1><?php echo $data['page_name']; ?>
                        <?php if (Permisos::create()) : ?>
                            <ol class="breadcrumb float-sm-right">
                            </ol>
                        <?php endif ?>
                    </h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
         <form  name="my_form" id="my_form" action="<?php echo base_url ?>/InvLoaRep/store" method="POST" class="form-horizontal form-label-left" >
           <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label for="id_emp">Empresa</label>
                        <select name="id_emp" id="id_emp" class="form-control"></select>
                    </div>
                </div>
               <div class="row">
                   <div class="form-group col-md-6 col-sm-6 col-xs-12">
                       <label for="id_fab">Marca/Fabricante/Laboratorio</label>
                       <select name="id_fab" id="id_fab" class="form-control select2 select2bs4"></select>
                   </div>
                   <div class="form-group col-md-6 col-sm-6 col-xs-12">
                       <label for="id_grupo">Grupo</label>
                       <select name="id_grupo" id="id_grupo" class="form-control select2 select2bs4"></select>
                   </div>
               </div>
           </div>
           <div class="card-footer">
               <div class="row">
                   <div class="form-group col-md-6 col-sm-6 col-xs-12 text-center">
                    <button type="submit" class="btn btn-success" name="btn_excel" id="btn_excel"> Descargar Excel </button>
                   </div>
               </div>
           </div>
    </form>
    </section>
</div>
<?php footerAdmin($data); ?>