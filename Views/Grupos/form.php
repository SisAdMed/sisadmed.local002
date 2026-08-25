<div class='card-body'>
    <input type='hidden' id='id' name='id' value='<?= $r->id_grupo ?? '' ?>'>
    <div class='row'>
        <div class='form-group col-md-1 col-sm-1 col-xs-12'>
            <label for="grupo_codigo">Código</label>
            <input type="text" id="grupo_codigo" name="grupo_codigo" class="form-control text-right text-xs" readonly>
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="grupo_nombre">Descripción</label>
            <input type="text" id="grupo_nombre" name="grupo_nombre" class="form-control text-xs" onkeyup="mayusculas(this);">
        </div>
        <div class="form-group col-sm-1 col-md-1 col-xs-12">
            <label for="view_internet">Web</label>
            <input type="checkbox" name="view_internet" id="view_internet" class="form-control text-xs" value="1">
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="icono">Icono</label>
            <input type="text" id="icono" name="icono" class="form-control text-xs" onkeyup="minusculas(this);" placeholder="Ejemplo: fa-solid fa-kit-medical">
        </div>
        <div class="form-group col-md-5 col-sm-5 col-xs-12">
            <label for="id_fab">Lab/Marca/Fab</label>
            <select class="form-control text-xs custom-select rounded-0 select2 select2bs4" id="id_fab" name="id_fab[]" multiple></select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6 col-sm-6 col-xs-12">
            <label for="catalogo">Descripción del Catálogo</label>
            <input type="text" name="catalogo" id="catalogo" class="form-control text-xs">
        </div>
        <div class="form-group col-md-6 col-sm-6 col-xs-12">
            <label for="ruta_catalogo">Seleccionar archivo PDF:</label>
            <input type="file" name="ruta_catalogo" id="ruta_catalogo" class="form-control text-xs" accept=".pdf, application/pdf">
            
            <input type="hidden" id="eliminar_pdf" name="eliminar_pdf" value="0">
            <input type="hidden" id="archivo_actual_nombre" name="archivo_actual_nombre" value="">

            <!-- Contenedor para mostrar el archivo actual -->
            <div id="contenedorArchivoActual" class="mt-2" style="display: none;">
                <span class="badge badge-info p-2">
                    <i class="fas fa-file-pdf mr-1"></i> <span id="nombreArchivoActual">archivo.pdf</span>
                </span>
                <a id="btnVerArchivo" href="#" target="_blank" class="btn btn-xs btn-primary ml-2">
                    <i class="fas fa-eye"></i> Ver PDF
                </a>
                <button type="button" id="btnQuitarArchivo" class="btn btn-xs btn-danger ml-1">
                    <i class="fas fa-trash"></i> Quitar
                </button>
            </div>

            <!-- BARRA DE PROGRESO -->
            <div class="progress progress-sm mt-2" id="progress-container" style="display: none; height: 16px;">
                <div id="progress-bar" class="progress-bar bg-success progress-bar-striped progress-bar-animated font-weight-bold" 
                     role="progressbar" style="width: 0%; font-size: 11px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    0%
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-10 col-sm-10 col-xs-12">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control text-xs" rows="5" placeholder="Descripción del grupo"></textarea>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control text-xs"></select>
        </div>
    </div>
</div>