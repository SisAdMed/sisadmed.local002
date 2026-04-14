<?php headerAdmin($data); 
    $origen = $_GET['ori'];
    $date_close = 'Fecha de Cierre';

    $proceso = 'Esta proceso consiste en actualizar saldos, generar asientos contables y reservar la información procesada solamente para consultas.';
    if($origen == 'A'){
        $proceso = 'Esta proceso consiste en reversar asientos contables y actualizar saldos.';
        $date_close = 'Nueva fecha de cierre';
    }
?>
<div class='content-wrapper'>
    <section class='content-header'>
        <div class='container-fluid'>
            <div class='row mb-2'>
                <div class='col-sm-12'>
                    <h1><?php echo $data['page_name']; ?>
                </div>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="row">
            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <center>
                            <p>
                            <h2 style="color: blue; font-weight: bold; text-align: center;"><?= $proceso ?></br>
                            La duración de este proceso dependerá del volumen de la información a procesar, pudiendo durar varios minutos. </h2>
                            </p>
                        </center>
                    </div>
                    <div class="card-body">
                        <form name="my_form" id="my_form">
                            <div class="form-group">
                                <label for="id_emp">Empresa</label>
                                <select name="id_emp" id="id_emp" class="form-control text-xs"></select>
                                <label for="date_close"> <?= $date_close ?></label>
                                <input type="date" id="date_close" name="date_close" class="form-control text-xs">
                            </div>
                            <center>
                                <button id="btn_close" name="btn_close" class="btn btn-primary btn-xs"><?php echo $origen == 'C' ? 'Iniciar Cierre de Módulos' : 'Iniciar Apertura de Módulos'; ?></button>
                            </center>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="progress mt-3">
                            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            </div>
                        </div>
                        <p id="progressText" class="mt-2 text-info">Esperando inicio...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php footerAdmin($data); ?>