<div class="card-body text-xs">    
    <input type="hidden" name="id" id="id" value="<?= $r->id ?? '' ?>">
    <input type="hidden" id="foto_actual" name="foto_actual" value="<?= !empty($r->icono) ? $r->icono : '' ?>">
    <div class="row text-xs">
        <div class="form-group col-md-4 col-sm-4 col-xs-12">
            <label for="estado">Estado del producto</label>
            <input type="text" name="estado" id="estado" class="form-control text-xs">
        </div>
        <div class="form-group col-md-6 col-sm-6 col-xs-12">
            <label for="inputFoto" class="form-label fw-bold">Seleccionar Imagen del Producto:</label>

            <!-- accept="image/*" restringe el explorador de archivos a solo imágenes -->
            <input type="file"
                class="form-control text-xs"
                id="inputFoto"
                name="foto_producto"
                accept="image/*">

            <!-- Contenedor de Vista Previa -->
            <div class="mt-3 text-center">
                <label class="d-block text-muted small mb-2">Vista previa:</label>

                <!-- Imagen donde se renderiza la previsualización -->
                <img id="imgPreview"
                    src="<?= base_url ?>/Assets/img/no_picture.jpg"
                    alt="Previsualización"
                    class="img-thumbnail rounded shadow-sm"
                    style="max-width: 220px; max-height: 220px; object-fit: contain; display: inline-block;">

                <!-- Botón opcional para quitar/limpiar la selección -->
                <div class="mt-2">
                    <button type="button" class="btn btn-outline-danger btn-sm d-none" id="btnQuitarFoto">
                        <i class="fas fa-trash-alt me-1"></i> Quitar imagen
                    </button>
                </div>

            </div>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-2">
            <label for="status">Estatus</label>
            <select name="status" id="status" class="form-control text-xs"></select>
        </div>
    </div>
</div>