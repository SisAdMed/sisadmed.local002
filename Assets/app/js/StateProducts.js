/*! 
* Funciones Estado de productos
* Copyright (c) 2026, Sisadmed.
* 14-08-2026 Creado por: José Vargas - Creación 09:23:00
*/
//Variables
$().ready(function () {
    $("form#my_form").validate({
        ignore: null,
        rules: {
            estado: {
                required: true,
                minlength: 4,
                maxlength: 50, 
            },
            foto_producto: {
                // Es obligatorio SOLO si no hay una foto actual en el registro
                required: function (element) {
                    return $("#foto_actual").val().trim() === "";
                },
                // Validar extensión si el usuario seleccionó un archivo
                extension: "jpg|jpeg|png|webp|ico"
            },
            status: "required"
        },
        messages: {
            estado: {
                required: "Debe especificar un Estado",
                minlength: "Debe especificar al menos {0} caracteres",
                maxlength: "Debe especificr máximo {} caracteres",
            },
            foto_producto: {
                required: "Debe seleccionar al menos una imagen para el producto.",
                extension: "Formato no válido. Solo se admiten archivos JPG, JPEG, PNG, WEBP o ICO."
            },
            status: "Debe especificar un Estatus"
        }
    })
    //Cargar el index
    form = $("form").attr("id");
    if (form === undefined) {
        initStateProducts();
    } else {
        id = $("#id").val();
        if (id) {
            show_row(id);
        } else {
            dat_form_new();
        }
    }

    const defaultImg = `${base_url}/Assets/img/no_picture.jpg`; // Imagen por defecto

    // Evento al cambiar el archivo seleccionado
    $('#inputFoto').on('change', function () {
        const file = this.files[0];

        if (file) {
            // Validar que el archivo sea efectivamente una imagen
            if (!file.type.startsWith('image/')) {
                alert('Por favor, selecciona un archivo de imagen válido (JPG, PNG, WEBP, ICO).');
                $(this).val(''); // Limpia el input
                $('#imgPreview').attr('src', defaultImg);
                $('#btnQuitarFoto').addClass('d-none');
                return;
            }

            // Crear la URL temporal para la vista previa instantánea
            const urlTemporal = URL.createObjectURL(file);
            $('#imgPreview').attr('src', urlTemporal);
            $('#btnQuitarFoto').removeClass('d-none'); // Muestra el botón de quitar
            $(this).valid();
        } else {
            $('#imgPreview').attr('src', defaultImg);
            $('#btnQuitarFoto').addClass('d-none');
        }
    });

    // Botón para cancelar / limpiar la imagen seleccionada
    $('#btnQuitarFoto').on('click', function () {
        $('#inputFoto').val(''); // Limpia el input file
        $('#imgPreview').attr('src', defaultImg); // Restaura imagen por defecto
        $(this).addClass('d-none'); // Oculta el botón
    });
    //Eliminar un registro
    $("#tblIndexMain").on("click", ".btn-delete", function () {
        var id = $(this).data("id"); // Obtiene el ID del registro        
        var name = $(this).data("code"); // Obtine el nombre
        var descrip = `¿Está seguro de eliminar el Estado de producto ${name}.?`
        Swal.fire({
            title: descrip,
            text: "¡No podrá revertir esta eliminación!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, borrar este registro!",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                const url = `${base_url}/StateProducts/destroy`; 
                $.ajax({
                    url: url, // URL de tu script de eliminación en el servidor
                    method: "POST",
                    data: { id: id, name: name },
                    dataType: "json",
                    beforeSend: function () {
                        loader.show();
                    },
                    complete: function () {
                        loader.hide();
                    },
                    success: function (resulta) {
                        // La respuesta del servidor debe indicar si fue exitoso
                        Swal.fire({
                            icon: `${resulta.icon}`,
                            title: `${resulta.title}`,
                            text: `${resulta.msg}`,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                var tableMain = $('#tblIndexMain').DataTable();
                                tableMain.ajax.reload(null, false);
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        // Captura de errores de servidor / red (HTTP 4xx, 5xx)
                        let mensajeError = 'Ocurrió un error inesperado al procesar la solicitud.';

                        if (xhr.status === 0) {
                            mensajeError = 'Sin conexión. Verifique su red a internet.';
                        } else if (xhr.status === 403) {
                            mensajeError = 'Acceso denegado o sesión expirada.';
                        } else if (xhr.status === 404) {
                            mensajeError = 'No se encontró la ruta del archivo en el servidor.';
                        } else if (xhr.status === 500) {
                            // Intentar leer mensaje JSON si el backend devolvió uno
                            console.log(xhr);
                            if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                                mensajeError = xhr.responseJSON.mensaje;
                            } else {
                                mensajeError = 'Error interno en el servidor (500).';
                            }
                        } else if (status === 'timeout') {
                            mensajeError = 'Tiempo de espera agotado. El servidor tardó demasiado en responder.';
                        } else if (status === 'parsererror') {
                            mensajeError = 'Error al procesar la respuesta del servidor (formato JSON inválido).';
                            console.error('Respuesta sin formato JSON:', xhr.responseText);
                        }
                        Swal.fire('Error', mensajeError, 'error');
                    },
                });
            }
        });
    });
    //
    //Guardar y/o Actualizar registro
    $("#my_form").on("submit", function (e) {
        e.preventDefault();
        if ($(this).valid()) {
            const form = this;
            const formData = new FormData(form);
            const url = `${base_url}/StateProducts/store`;
            const $btnSubmit = $(form).find('button[type="submit"]');
            $btnSubmit.prop("disabled", true);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false, // Requerido para envío de archivos/FormData
                processData: false, // Requerido para envío de archivos/FormData
                beforeSend: function () {
                    // Muestra una ventana de SweetAlert2 con spinner activo
                    Swal.fire({
                        title: 'Guardando...',
                        text: 'Procesando los datos e imagen, por favor espere.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading(); // Inicia la animación de carga
                        }
                    });
                },
                complete: function () {
                    loader.hide();
                    // Restaurar botón al finalizar (éxito o error)
                    $btnSubmit.prop('disabled', false).html('Guardar');
                    Swal.close();
                },
                error: function (xhr, status, error) {
                    // Captura de errores de servidor / red (HTTP 4xx, 5xx)
                    let mensajeError = 'Ocurrió un error inesperado al procesar la solicitud.';

                    if (xhr.status === 0) {
                        mensajeError = 'Sin conexión. Verifique su red a internet.';
                    } else if (xhr.status === 403) {
                        mensajeError = 'Acceso denegado o sesión expirada.';
                    } else if (xhr.status === 404) {
                        mensajeError = 'No se encontró la ruta del archivo en el servidor.';
                    } else if (xhr.status === 500) {
                        // Intentar leer mensaje JSON si el backend devolvió uno
                        if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                            mensajeError = xhr.responseJSON.mensaje;
                        } else {
                            mensajeError = 'Error interno en el servidor (500).';
                        }
                    } else if (status === 'timeout') {
                        mensajeError = 'Tiempo de espera agotado. El servidor tardó demasiado en responder.';
                    } else if (status === 'parsererror') {
                        mensajeError = 'Error al procesar la respuesta del servidor (formato JSON inválido).';
                        console.error('Respuesta sin formato JSON:', xhr.responseText);
                    }
                    Swal.fire('Error', mensajeError, 'error');
                },
                success: function (data) {
                    Swal.fire({
                        title: data.title,
                        text: data.msg,
                        icon: data.icon,
                    }).then((result) => {
                        if (data.icon != "error") {
                            window.location.href = `${base_url}/StateProducts`;
                        }
                    })
                }
            });
        }
    });
});
function dat_form_new() {
    listar_status(1);
}
function show_row(id) {
    const url = `${base_url}/StateProducts/show_row`;
    $.ajax({
        url: url,
        method: 'POST',
        dataSrc: '',
        dataType: 'json',
        data: { id: id },
        beforeSend: function () {
            loader.show();
        },
        complete: function () {
            loader.hide();
        },
        error: function (xhr, status, error) {
            loader.hide();
            // Captura de errores de servidor / red (HTTP 4xx, 5xx)
            let mensajeError = 'Ocurrió un error inesperado al procesar la solicitud.';

            if (xhr.status === 0) {
                mensajeError = 'Sin conexión. Verifique su red a internet.';
            } else if (xhr.status === 403) {
                mensajeError = 'Acceso denegado o sesión expirada.';
            } else if (xhr.status === 404) {
                mensajeError = 'No se encontró la ruta del archivo en el servidor.';
            } else if (xhr.status === 500) {
                // Intentar leer mensaje JSON si el backend devolvió uno
                if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                    mensajeError = xhr.responseJSON.mensaje;
                } else {
                    mensajeError = 'Error interno en el servidor (500).';
                }
            } else if (status === 'timeout') {
                mensajeError = 'Tiempo de espera agotado. El servidor tardó demasiado en responder.';
            } else if (status === 'parsererror') {
                mensajeError = 'Error al procesar la respuesta del servidor (formato JSON inválido).';
                console.error('Respuesta sin formato JSON:', xhr.responseText);
            }
            Swal.fire('Error', mensajeError, 'error');
        },
        success: function (data) {
            $("#estado").val(data.estado);
            estatus = data.status;
            listar_status(estatus);
            if (data.icono && data.icono.trim() !== '') {
                // Construir la URL completa asegurando formato limpio
                const rutaCompleta = `${base_url}/Assets/img/${data.icono.replace(/^\/+/, '')}`;

                $('#foto_actual').val(data.icono);      // Guardar valor en el input hidden
                $('#imgPreview').attr('src', rutaCompleta);  // Actualizar la vista previa
            } else {
                $('#foto_actual').val('');
                $('#imgPreview').attr('src', defaultImg);
            }

        }
    })
}