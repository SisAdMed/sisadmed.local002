<?php headerAdmin($data);?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1><?php echo $data['page_name']; ?>
                    <?php if (Permisos::create()) : ?>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url ?>/NomCon/nuevo" title="Nuevo registro"><i class="fa fa-plus-circle"></i></a></li>
                        </ol>
                    <?php endif ?>
                </h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <?php echo Alertas::mostrarAlerta() ?>
    <table id="tblTableNomCon" class="display responsive nowrap table table-hover" style="width:100%">
        <thead>
            <th>Id</th>
            <th>Concepto</th>
            <th>Descripción</th>
            <th>Tipo</th>
            <th>Parámetro</th>
            <th class="text-right">Fac./Tope/Mont.</th>
            <th class="text-center">Status</th>
            <th class="text-center">Acciones</th>
        </thead>
        <tbody id="tbody">
        </tbody>
        <tfoot>
            <th>Id</th>
            <th>Concepto</th>
            <th>Descripción</th>
            <th>Tipo</th>
            <th>Parámetro</th>
            <th class="text-right">Fac./Tope/Mont.</th>
            <th class="text-center">Status</th>
            <th class="text-center">Acciones</th>
        </tfoot>
    </table>
</section>
</div>
<?php footerAdmin($data); ?>