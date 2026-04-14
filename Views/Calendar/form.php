<div class="card-body">
    <input type="hidden" name="id" id="id" value="<?php echo $r['id'] ?? ''; ?>">
    <div class="row">
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="title">Título <span>*</span></label>
            <input type="text" id="title" name="title" class="form-control text-xs mayusculas">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="year">Año<span>*</span></label>
            <input type="number" id="year" name="year" class="form-control text-xs text-right" minlength="4" maxlength="4">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="background">Color de fondo</label>
            <div class="input-group my-colorpicker2 colorpicker-element" data-colorpicker-id="2">
                <input type="color" class="form-control text-xs" data-original-title="" title="Seleccione el color de fondo" id="background" name="background">
            </div>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="text">Color de texto</label>
            <div class="input-group my-colorpicker2 colorpicker-element" data-colorpicker-id="2">
                <input type="color" class="form-control text-xs" data-original-title="" title="Seleccione el color de texto" id="text" name="text">
            </div>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <input type="checkbox" name="all_day" id="all_day" class="form-control text-xs">
            <label for="all_day">Todo el día</label>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-10 col-sm-10 col-xs-12">
            <label for="description">Descripción</label>
            <textarea id="description" name="description" class="form-control text-xs" rows="3"></textarea>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control text-xs"></select>
        </div>
    </div>
    <section>
        <div class="calendar-view" style="display: none;">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="sticky-top mb-3">
                                <div class="card">
                                    <div class="card-header text-xs">
                                        <h4 class="card-title">Eventos</h4>
                                    </div>
                                    <div class="card-body">
                                        <!-- the events -->
                                        <div id="external-events">
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title text-xs">Crear Eventos</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                                            <ul class="fc-color-picker" id="color-chooser">
                                                <li><a class="text-primary" href="#"><i
                                                            class="fas fa-square"></i></a></li>
                                                <li><a class="text-warning" href="#"><i
                                                            class="fas fa-square"></i></a></li>
                                                <li><a class="text-success" href="#"><i
                                                            class="fas fa-square"></i></a></li>
                                                <li><a class="text-danger" href="#"><i
                                                            class="fas fa-square"></i></a></li>
                                                <li><a class="text-muted" href="#"><i
                                                            class="fas fa-square"></i></a></li>
                                            </ul>
                                        </div>
                                        <!-- /btn-group -->
                                        <div class="input-group">
                                            <input id="new-event" type="text" class="form-control text-xs"
                                                placeholder="Titulo del evento" title="Titulo del evento">

                                            <div class="input-group-append">
                                                <button id="add-new-event" type="button"
                                                    class="btn btn-primary btn-xs" title="Agregar evento">Agr.</button>
                                            </div>
                                            <!-- /btn-group -->
                                        </div>
                                        <!-- /input-group -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-10">
                            <div class="card card-primary">
                                <div class="card-body p-0">
                                    <!-- THE CALENDAR -->
                                    <div id="calendar"></div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
        </div>
    </section>
</div>