<div class="card">   
    <div class="card-body">
        <input type="hidden" id="id" name="id" value="<?= $r->id ?? '' ?>">
        <div class="row">
            <div class="form-group col-sm-2 col-md-2 col-xs-12">
                <label for="fecha">Fecha de Publicación</label>
                <input type="date" class="form-control text-xs" id="fecha" name="fecha">
            </div>
            <div class="form-group col-sm-4 col-md-4 col-xs-12">
                <label for="titulo">Título del Tutorial</label>
                <input type="text" class="form-control text-xs" id="titulo" name="titulo" placeholder="Ingrese título del tutorial">
            </div>
            <div class="form-group col-sm-6 col-md-6 col-xs-12">
                <label for="url">URL del Video (YouTube)</label>
                <input type="text" class="form-control text-xs" id="url" name="url" placeholder="Ingrese URL del video">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-9 col-md-9 col-xs-12 text-center">
                <label for="resumen">Resumen</label>
                <input type="text" class="form-control text-xs" id="resumen" name="resumen" placeholder="Resumen Breve del Tutorial">
            </div>
            <div class="form-group col-sm-1 col-md-1 col-xs-12">
                <label for="view_internet">Ver en Internet</label>
                <input class="form-control text-xs" type="checkbox" id="view_internet" name="view_internet">
            </div>
            <div class="form-group col-sm-2 col-md-2 col-xs-12">
                <label for="status">Status</label>
                <select class="form-control text-xs" id="status" name="status"></select>
            </div>

        </div>
        <div class="row">
            <div class="col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                    <label for="contenido">Instrucciones o Contenido Detallado</label>
                    <textarea id="contenido" name="contenido" class="form-control" rows="10"></textarea>
                </div>
            </div>

            <div class="form-group col-md-4 col-sm-4 col-xs-12">
                <label for="subirImagen">Imagen Destacada</label>
                <div class="input-group">
                    <input type="file" class="custom-file-input text-xs" id="subirImagen" name="nuevaImagen" accept="image/*">
                    <label class="custom-file-label" for="imagen">Seleccionar archivo</label>
                </div>
                <div class="card card-outline card-success text-center p-2" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                    <img id="vistaPrevia" src="<?= base_url ?>/Assets/img/no_picture.jpg" alt="Vista previa" class="img-thumbnail img-fluid style-preview" style="max-height: 220px; object-fit: cover;">
                </div>
                <small class="text-muted">Si no seleccionas ninguna, se asignará una por defecto.</small>
            </div>
        </div>
    </div>
</div>