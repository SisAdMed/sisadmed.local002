<?php headerAdmin($data);?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Ventas por año por unidades</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="ventasxanio"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Ventas por mes para el año <?= Date("Y"); ?></h5>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart_2"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Ventas por producto</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart_3"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Top 10 productos mas vendidos </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart_4"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php footerAdmin($data);?>