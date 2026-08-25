// Objeto DataTransfer para mantener la lista actualizada de archivos
let contenedorArchivos = new DataTransfer();

$().ready(function () {
    // Validaciones del Formulario
    $('#my_form').validate({
        ignore: [],
        rules: {
            fecha: "required",
            titulo: "required",
            status: "required"
        },
        messages: {
            fecha: "Por favor, ingresa la fecha.",
            titulo: "Por favor, ingresa el título.",
            status: "Por favor, selecciona el status."
        },
        submitHandler: function (form) {
            // Contamos el total de tarjetas presentes en la galería (Nuevas + Existentes)
            let totalImagenes = $('#galeria-preview .preview-nueva-card, #galeria-preview .preview-existente-card, #galeria-preview .img-card-item').length;

            if (totalImagenes === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Atención',
                    text: 'Debe cargar al menos una imagen o video para poder guardar el carrusel.',
                    confirmButtonText: 'Entendido'
                });
                return false;
            }

            var boton = $("#btnok");
            boton.prop('disabled', true);
            $('#status').prop('disabled', false);

            var formData = new FormData(form);
            const url = `${base_url}/Carousel/store`;

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function () {
                    loader.show();
                },
                complete: function () {
                    loader.hide();
                },
                error: function (error) {
                    loader.hide();
                    boton.prop('disabled', false);
                    console.error('Error al procesar el formulario:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Servidor',
                        text: 'Ocurrió un error interno al intentar guardar el carrusel.'
                    });
                },
                success: function (data) {
                    Swal.fire({
                        title: data.title || 'Información',
                        text: data.msg || '',
                        icon: data.icon || 'info'
                    }).then((result) => {
                        if (data.icon === "success") {
                            window.location.href = `${base_url}/Carousel`;
                        } else {
                            boton.prop('disabled', false);
                        }
                    });
                }
            });
            return false;
        }
    });

    // Inicialización del formulario / Index
    let form = $("form").attr("id");
    if (form === undefined) {
        initCarousel();
    } else {
        let id = $("#id").val();
        if (id) {
            show_row(id);
        } else {
            dat_form_new();
        }
    }

    // Sortable: Reordenar tarjetas arrastrándolas
    $("#galeria-preview").sortable({
        placeholder: "ui-state-highlight rounded m-1 border-dashed",
        cursor: "move",
        opacity: 0.8
    });

    // -------------------------------------------------------------
    // SELECCIÓN DE NUEVOS ARCHIVOS (Imágenes y Videos MP4)
    // -------------------------------------------------------------
    $('#imagenes').off('change').on('change', function () {
        let files = this.files;
        let maxPesoBytes = 48 * 1024 * 1024; // 48MB
        let tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'video/mp4'];
        let anchoMinimo = 1000;
        let altoMinimo = 400;

        $('.preview-nueva-card').remove();
        contenedorArchivos = new DataTransfer();
        $('#sin-imagenes').hide();

        let mensajesError = [];
        let promesasCarga = [];

        $.each(files, function (i, file) {
            // Validar formato
            if (!tiposPermitidos.includes(file.type) && !file.name.toLowerCase().endsWith('.mp4')) {
                mensajesError.push(`<b>${file.name}</b>: Formato no válido (solo JPG, PNG, WEBP, MP4).`);
                return true;
            }

            // Validar tamaño
            if (file.size > maxPesoBytes) {
                let pesoMB = (file.size / (1024 * 1024)).toFixed(2);
                mensajesError.push(`<b>${file.name}</b>: Pesa ${pesoMB} MB (Supera el límite de 48 MB).`);
                return true;
            }

            let promesa = new Promise((resolve) => {
                let objectUrl = URL.createObjectURL(file);
                let esVideo = file.type === 'video/mp4' || file.name.toLowerCase().endsWith('.mp4');

                function agregarTarjetaAlDOM(mediaTagHtml) {
                    contenedorArchivos.items.add(file);
                    let indiceActual = contenedorArchivos.files.length - 1;

                    let cardHtml = `
                    <div class="card shadow-sm p-2 bg-white rounded position-relative preview-nueva-card m-1" style="width: 250px; cursor: move;">
                        <input type="hidden" name="file_index[]" value="${indiceActual}">
                        
                        <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 m-1 btn-quitar-nueva" data-index="${indiceActual}" style="z-index: 10;" title="Quitar">
                            <i class="fas fa-times"></i>
                        </button>

                        <span class="badge badge-warning position-absolute top-0 start-0 m-1" style="font-size: 10px; z-index: 2;">
                            Nuevo
                        </span>

                        ${mediaTagHtml}

                        <div class="mt-2">
                            <input type="text" name="mensaje_izq[${indiceActual}]" class="form-control text-xs mb-1" maxlength="255" placeholder="Mensaje Izquierdo">
                            <input type="text" name="mensaje_der[${indiceActual}]" class="form-control text-xs" maxlength="255" placeholder="Mensaje Derecho">
                        </div>
                    </div>`;

                    $('#galeria-preview').append(cardHtml);
                    resolve();
                }

                if (esVideo) {
                    let video = document.createElement('video');
                    video.preload = 'metadata';
                    video.muted = true;
                    video.playsInline = true;

                    video.onloadedmetadata = function () {
                        if (video.videoWidth < anchoMinimo || video.videoHeight < altoMinimo) {
                            mensajesError.push(`<b>${file.name}</b>: Resolución de video baja (${video.videoWidth}x${video.videoHeight}px).`);
                        }
                        let mediaHtml = `<video src="${objectUrl}" class="rounded" controls muted preload="metadata" style="width: 100%; height: 140px; object-fit: cover; background: #000;"></video>`;
                        agregarTarjetaAlDOM(mediaHtml);
                    };

                    video.onerror = function () {
                        mensajesError.push(`<b>${file.name}</b>: No se pudo cargar el archivo de video.`);
                        resolve();
                    };

                    video.src = objectUrl;
                } else {
                    let img = new Image();
                    img.onload = function () {
                        if (this.width < anchoMinimo || this.height < altoMinimo) {
                            mensajesError.push(`<b>${file.name}</b>: Resolución baja (${this.width}x${this.height}px).`);
                        }
                        let mediaHtml = `<img src="${objectUrl}" class="rounded img-fluid" style="width: 100%; height: 140px; object-fit: cover;">`;
                        agregarTarjetaAlDOM(mediaHtml);
                    };

                    img.onerror = function () {
                        mensajesError.push(`<b>${file.name}</b>: No se pudo cargar la imagen.`);
                        resolve();
                    };

                    img.src = objectUrl;
                }
            });

            promesasCarga.push(promesa);
        });

        Promise.all(promesasCarga).then(() => {
            document.getElementById('imagenes').files = contenedorArchivos.files;

            if (mensajesError.length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Observaciones en los archivos',
                    html: `<div class="text-start text-xs">${mensajesError.join('<br>')}</div>`,
                    confirmButtonText: 'Entendido'
                });
            }
        });
    });

    // Quitar archivo nuevo seleccionado
    $(document).off('click', '.btn-quitar-nueva').on('click', '.btn-quitar-nueva', function () {
        let indexAQuitar = $(this).data('index');
        let $card = $(this).closest('.preview-nueva-card');

        let nuevoContenedor = new DataTransfer();
        for (let i = 0; i < contenedorArchivos.files.length; i++) {
            if (i !== indexAQuitar) {
                nuevoContenedor.items.add(contenedorArchivos.files[i]);
            }
        }

        contenedorArchivos = nuevoContenedor;
        document.getElementById('imagenes').files = contenedorArchivos.files;

        $card.fadeOut(200, function () {
            $(this).remove();
            reindexarTarjetasNuevas();

            if ($('#galeria-preview .preview-nueva-card, #galeria-preview .preview-existente-card, #galeria-preview .img-card-item').length === 0) {
                $('#sin-imagenes').show();
            }
        });
    });

    // Quitar archivo existente de la BD
    $(document).off('click', '.btn-quitar-existente').on('click', '.btn-quitar-existente', function () {
        let idImagenBD = $(this).data('id');
        let $card = $(this).closest('.preview-existente-card');

        Swal.fire({
            title: '¿Quitar archivo guardado?',
            text: 'El archivo se marcará para ser eliminado al guardar los cambios.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, quitar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#contenedor-eliminados').append(`<input type="hidden" name="eliminar_existentes_ids[]" value="${idImagenBD}">`);

                $card.fadeOut(200, function () {
                    $(this).remove();
                    if ($('#galeria-preview .preview-nueva-card, #galeria-preview .preview-existente-card, #galeria-preview .img-card-item').length === 0) {
                        $('#sin-imagenes').show();
                    }
                });
            }
        });
    });

    // Reindexar nombres de inputs de nuevas tarjetas
    function reindexarTarjetasNuevas() {
        $('#galeria-preview .preview-nueva-card').each(function (nuevoIndice) {
            $(this).find('input[name="file_index[]"]').val(nuevoIndice);
            $(this).find('.btn-quitar-nueva').attr('data-index', nuevoIndice);
            $(this).find('input[name^="mensaje_izq"]').attr('name', `mensaje_izq[${nuevoIndice}]`);
            $(this).find('input[name^="mensaje_der"]').attr('name', `mensaje_der[${nuevoIndice}]`);
        });
    }
});

// -------------------------------------------------------------
// FUNCIONES AUXILIARES Y DE CONSULTA
// -------------------------------------------------------------
function initCarousel() {
    const title = "Internet - Carrusel";
    const origen = "Carousel";
    const id_menu = 206;
    get_permiso(id_menu);
    IndexDataTable(origen, tblIndexMain, title, [
        {
            data: null,
            title: "Acciones",
            className: "text-center",
            render: function (data, type, row) {
                var t_menu = "";
                if (permisos_cre == 1 || permisos_upd == 1) {
                    t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/gestion/${row.token_edit}"><i class="fa fa-edit"></i></a> `;
                }
                if (permisos_del == 1) {
                    t_menu += `<button id="Data" data-id="${row.id}" data-name="${row.titulo}" type="button" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></button> `;
                }
                return t_menu;
            },
        },
        { data: "fecha", title: "Fecha", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN) },
        { data: "titulo", title: "Título" },
        {
            data: null, title: "Vista en la Web", className: "text-center",
            render: function (data, type, row) {
                return row.view_internet == 1 ? '<input type="checkbox" checked disabled>' : '<input type="checkbox" disabled>';
            }
        },
        {
            data: null,
            title: "Status",
            className: "text-center",
            render: function (data, type, row) {
                var status = row.status == 1 ? "Activo" : (row.status == 0 ? "Inactivo" : "Por aprobar");
                var clase = row.status == 1 ? "badge-success" : (row.status == 0 ? "badge-danger" : "badge-warning");
                return `<span class="badge ${clase}">${status}</span>`;
            },
        },
    ]);
}

function dat_form_new() {
    $("#fecha").val(GetTodayDate(0));
    listar_status(1);
}

function show_row(id) {
    const url = `${base_url}/Carousel/show_row`;
    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        data: { id: id },
        beforeSend: function () {
            loader.show();
        },
        complete: function () {
            loader.hide();
        },
        error: function (error) {
            loader.hide();
            console.error('Error al obtener registro:', error);
        },
        success: function (data) {
            if (!data || data.length === 0) return;

            // 1. Cargar Cabecera
            let data_enca = data[0];
            $("#fecha").val(data_enca.fecha);
            $("#titulo").val(data_enca.titulo);
            $("#view_internet").prop('checked', data_enca.view_internet == 1);
            listar_status(data_enca.status);

            // 2. Limpiar Galería y Ocultar mensaje vacío
            $('#galeria-preview').empty();
            $('#sin-imagenes').hide();

            // 3. Renderizar cada Imagen o Video MP4 existente
            $.each(data, function (index, item) {
                if (!item.imagen) return; // Si la fila no trae archivo, omitir

                let nombreArchivo = item.imagen;
                let rutaCompleta = `${base_url}/Assets/img/carousel/${nombreArchivo}`;
                let esVideo = nombreArchivo.toLowerCase().endsWith('.mp4');

                // Renderizar el tag multimedia correcto
                let mediaTag = esVideo
                    ? `<video src="${rutaCompleta}" class="rounded" controls muted preload="metadata" style="width: 100%; height: 140px; object-fit: cover; background: #000;"></video>`
                    : `<img src="${rutaCompleta}" class="rounded img-fluid" style="width: 100%; height: 140px; object-fit: cover;">`;

                let cardHtml = `
                <div class="card shadow-sm p-2 bg-white rounded position-relative preview-existente-card m-1" style="width: 250px; cursor: move;">
                    <input type="hidden" name="existente_id[]" value="${item.id_detalle || item.id}">
                    
                    <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 m-1 btn-quitar-existente" data-id="${item.id_detalle || item.id}" style="z-index: 10;" title="Quitar">
                        <i class="fas fa-times"></i>
                    </button>

                    <span class="badge badge-info position-absolute top-0 start-0 m-1" style="font-size: 10px; z-index: 2;">
                        Guardada
                    </span>

                    ${mediaTag}

                    <div class="mt-2">
                        <input type="text" name="existente_mensaje_izq[${item.id_detalle || item.id}]" class="form-control text-xs mb-1" maxlength="255" value="${item.mensaje_izq || ''}" placeholder="Mensaje Izquierdo">
                        <input type="text" name="existente_mensaje_der[${item.id_detalle || item.id}]" class="form-control text-xs" maxlength="255" value="${item.mensaje_der || ''}" placeholder="Mensaje Derecho">
                    </div>
                </div>`;

                $('#galeria-preview').append(cardHtml);
            });
        }
    });
}