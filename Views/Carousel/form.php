<div class="card">
    <div class="card-header">
        <input type="hidden" id="id" name="id" value="<?= $r->id ?? '' ?>">
        <div class="row">
            <div class="form-group col-sm-2 col-md-2 col-xs-12">
                <label for="fecha">Fecha de Publicación</label>
                <input type="date" class="form-control text-xs" id="fecha" name="fecha">
            </div>
            <div class="form-group col-sm-7 col-md-7 col-xs-12">
                <label for="titulo">Título de la Campaña</label>
                <input type="text" class="form-control text-xs" id="titulo" name="titulo" placeholder="Ingrese título de la campaña">
            </div>
            <div class="form-group col-sm-1 col-md-1 col-xs-12">
                <label for="view_internet">Ver Internet</label>
                <input class="form-control text-xs" type="checkbox" id="view_internet" name="view_internet">
            </div>
            <div class="form-group col-sm-2 col-md-2 col-xs-12">
                <label for="status">Status</label>
                <select class="form-control text-xs" id="status" name="status"></select>
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Input para seleccionar imágenes -->
        <div class="row">
            <div class="form-group col-12">
                <label for="imagenes">Seleccionar Imágenes para el Carrusel</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input text-xs" id="imagenes" name="imagenes[]" accept="image/jpeg, image/png, image/webp, video/mp4, .mp4" multiple>
                    <label class="custom-file-label text-xs" for="imagenes" data-browse="Buscar">Seleccionar archivos...</label>
                </div>
                <small class="form-text text-muted">
                    Formatos: JPG, PNG, WEBP, MP4. Máximo 48MB por imagen. Dimensión recomendada: 1920x600 px.
                </small>
            </div>
        </div>

        <!-- Galería de imágenes (Vista previa ampliada con mensajes individuales) -->
        <div class="row mt-2">
            <div class="col-12">
                <label class="text-xs font-weight-bold">Imágenes asociadas / Vista previa y Configuración de Mensajes:</label>
                <div id="galeria-preview" class="d-flex flex-wrap border p-2 rounded bg-light" style="min-height: 180px;">

                    <!-- A. RECORRER E IMPRIMIR LAS IMÁGENES EXISTENTES EN BD -->                    
                    <?php if (!empty($imagenesExistentes)): ?>                                                 
                        <?php foreach ($imagenesExistentes as $index => $img): ?>
                            <div class="card shadow-sm p-2 bg-white rounded position-relative img-card-item preview-existente-card m-1" style="width: 250px;">

                                <!-- ID de la imagen en la tabla f00281 -->
                                <input type="hidden" name="existente_id[]" value="<?= $img['id']; ?>">
                                <input type="hidden" name="existente_foto[]" value="<?= htmlspecialchars($img['imagen']); ?>">

                                <!-- Botón para eliminar esta foto existente -->
                                <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 m-1 btn-quitar-existente" data-id="<?= $img['id']; ?>" title="Eliminar foto guardada">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                <span class="badge badge-info position-absolute top-0 start-0 m-1" style="font-size: 10px; z-index: 2;">
                                    Guardada
                                </span>

                                <!-- Vista previa de la imagen guardada -->
                                <img src="<?= IMG_CARRUSEL . htmlspecialchars($img['imagen']); ?>" class="rounded img-fluid" style="width: 100%; height: 140px; object-fit: cover;">

                                <div class="mt-2">
                                    <input type="text" name="existente_mensaje_izq[<?= $img['id']; ?>]" class="form-control text-xs mb-1" maxlength="255" value="<?= htmlspecialchars($img['mensaje_izq'] ?? ''); ?>" placeholder="Mensaje Izquierdo">
                                    <input type="text" name="existente_mensaje_der[<?= $img['id']; ?>]" class="form-control text-xs" maxlength="255" value="<?= htmlspecialchars( $img['mensaje_der'] ?? ''); ?>" placeholder="Mensaje Derecho">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p id="sin-imagenes" class="text-muted text-center w-100 my-4">No hay imágenes asociadas a este carrusel.</p>
                    <?php endif; ?>

                </div>

                <!-- Contenedor oculto para rastrear las IDs de imágenes existentes que el usuario decida ELIMINAR -->
                <div id="contenedor-eliminados"></div>
            </div>
        </div>
    </div>
</div>