//Validar campos del formulario
$(function () {
	$("form[name='my_form']").validate({
		rules: {
			grupo_codigo: {
				required: true,
				minlength: 3,
				maxlength: 3,
			},
			grupo_nombre: {
				required: true,
				minlength: 5,
				maxlength: 100,
			},
			catalogo: {
				minlength: 10,
				maxlength: 100,
				required: function (element) {
					const inputFile = $("#ruta_catalogo")[0];
					return inputFile.files && inputFile.files.length > 0;
				}
			},
			status: "required",
			icono: {
				required: function () {
					return $("#view_internet").is(":checked");
				},
				minlength: 5,
			},
			descripcion: {
				required: function () {
					return $("#view_internet").is(":checked");
				},
				minlength: 5,
			}
		},
		messages: {
			grupo_codigo: {
				required: "Debe especificar un Código",
				minlength: "Debe contener al menos 3 carácteres",
				maxlength: "Debe contener máximo 3 carácteres",
			},
			grupo_nombre: {
				required: "Debe especificar una Descripción",
				minlength: "Debe contener al menos 5 carácteres",
				maxlength: "Debe contener máximo 100 carácteres",
			},
			catalogo: {
				required: "Debe especificar una descripción",
				minlength: "Debe espeficiar al menos 10 carácteres.",
				maxlength: "Debe especificar máximo 100 carácteres."
			},
			status: "Debe especificar un status",
			icono: {
				required: "Debe especificar un Icono",
				minlength: "Debe contener al menos 3 carácteres",
			},
			descripcion: {
				required: "Debe especificar una Descripción",
				minlength: "Debe contener al menos 5 carácteres",
			},
		},
	});
});
//AL ingresar al formulario
$(document).ready(function (e) {
	id = $('#id').val();
	if (id) {
		show_row(id);
	} else {
		next_codigo = next_codigo();
		//Marca para Facturacion    
		getMarcas('', 'id_fab');
		listar_status(1);
	}
})
//Mostrar datos de un registro en especifico
function show_row(id) {
	arrayFab = [];
	const url = `${base_url}/Grupos/show_row`;
	$.ajax({
		type: "POST",
		url: url,
		dataSrc: "",
		data: { 'id': id },
		dataType: "json",
		beforeSend: function () {
			loader.show();
		},
		success: function (data) {
			codigo = data.grupo_codigo;
			grupo_codigo = leftPadWithZeros(codigo, 3);
			$("#grupo_codigo").val(grupo_codigo);
			$('#grupo_nombre').val(data.grupo_nombre);
			$view_internet = $('#view_internet');
			$("#catalogo").val(data.catalogo);
			ruta_catalogo = data.ruta_catalogo;
			$('#eliminar_pdf').val('0');
			$('#ruta_catalogo').val('');
			if (ruta_catalogo && ruta_catalogo.trim() !== "") {
				$('#nombreArchivoActual').text(data.catalogo || "Documento actual");
				$('#nombreArchivoActual').attr('title', data.catalogo || "Documento actual");
				$('#btnVerArchivo').attr('href', `${ruta_pdf_groups}/${ruta_catalogo}`);
				$('#contenedorArchivoActual').show();
			} else {
				$('#contenedorArchivoActual').hide();
			}
			listar_status(data.status);
			if (data.view_internet == 1) {
				$view_internet.prop('checked', true);
				$('#icono').val(data.icono);
				$('#descripcion').val(data.descripcion);
			} else {
				$view_internet.prop('checked', false);
				$('#icono').val('');
				$('#descripcion').val('');
			}
			let id_fab_raw = data.id_fab;
			if (id_fab_raw) {
				if (Array.isArray(id_fab_raw)) {
					// Si por alguna razón ya era un Array, lo dejamos tal cual
					arrayFab = id_fab_raw;
				} else {
					// Forzamos a que sea String (String(id_fab_raw)) y luego aplicamos .split()
					arrayFab = String(id_fab_raw).split(',').map(item => item.trim());
				}
				$.ajax({
					url: `${base_url}/Fabricantes/getMarcas`,
					type: 'POST',
					dataType: 'json',
					success: function (todosLosFabricantes) {
						let $select = $('#id_fab');
						$select.empty();

						// 1. Primero creamos TODOS los <option> en el select
						todosLosFabricantes.forEach(function (fab) {
							$select.append(new Option(fab.nom_fab, fab.id_fab, false, false));
						});

						// 2. UNA VEZ CREADOS, asignamos el arreglo y disparamos el cambio
						$select.val(arrayFab).trigger('change');
					}
				});
			} else {
				getMarcas('', 'id_fab');
			}

		},
		error: function (xhr) {
			console.log(xhr.statusText + ' ' + xhr.responseTExt);
			loader.hide();
		},
		complete: function () {
			loader.hide();
		},
	});

}
//Próximo Código de Grupo, cuando es un registro nuevo
function next_codigo() {
	div_loading();
	const url = `${base_url}/Grupos/next_codigo`;
	$.ajax({
		type: 'POST',
		url: url,
		dataSrc: '',
		data: '',
		dataType: 'json',
		beforeSend: function () {
			$('.loader').show();
		},
		success: function (data) {
			codigo = data.grupo_codigo;
			grupo_codigo = leftPadWithZeros(codigo, 3);
			$('#grupo_codigo').val(grupo_codigo);
		},
		error: function (xhr) {
			$('.loader').hide();
			console.log(xhr.statusText + xhr.responseTExt);
		},
		complete: function () {
			$('.loader').hide();
		}
	})
}
//Guardar y/o Actualizar
$("#my_form").on("submit", function (e) {
	e.preventDefault();

	// Validar formulario (si usas jQuery Validate)
	if (typeof $(this).valid === "function" && !$(this).valid()) {
		return false;
	}

	var formData = new FormData(this);
	var $progressBar = $("#progress-bar");
	var $progressContainer = $("#progress-container");
	var $btnSubmit = $(this).find("button[type='submit']");
	var tieneArchivo = $("#ruta_catalogo")[0].files.length > 0;

	$.ajax({
		url: `${base_url}/Grupos/store`, // Cambia por tu endpoint
		method: 'POST',
		data: formData,
		contentType: false,
		processData: false,
		dataType: 'json',
		xhr: function () {
			var xhr = new window.XMLHttpRequest();

			if (tieneArchivo) {
				xhr.upload.addEventListener("progress", function (evt) {
					if (evt.lengthComputable) {
						var porcentaje = Math.round((evt.loaded / evt.total) * 100);
						$progressBar.css("width", porcentaje + "%").text(porcentaje + "%");
					}
				}, false);
			}

			return xhr;
		},
		beforeSend: function () {
			$btnSubmit.prop("disabled", true);

			// Solo mostrar la barra si realmente se está subiendo un archivo
			if (tieneArchivo) {
				$progressContainer.fadeIn(150);
				$progressBar.css("width", "0%").text("0%").removeClass('bg-danger').addClass('bg-success');
			}
		},
		success: function (res) {
			$btnSubmit.prop("disabled", false);

			if (res.status) {
				Swal.fire({
					icon: 'success',
					title: '¡Guardado!',
					text: res.message || 'Registro actualizado correctamente.',
					confirmButtonText: 'Aceptar'
				}).then(() => {
					window.location.href = `${base_url}/Grupos`;
				});
			} else {
				$progressBar.removeClass('bg-success').addClass('bg-danger');
				Swal.fire('Error', res.message, 'error');
			}
		},
		error: function (xhr) {
			$btnSubmit.prop("disabled", false);
			$progressBar.removeClass('bg-success').addClass('bg-danger');
			Swal.fire('Error', 'Ocurrió un error al procesar la solicitud.', 'error');
			console.error(xhr.responseText);
		}
	});
});
//Eliminar Registro
function eliminarBtn(id) {
	id = id.dataset.id;
	Swal.fire({
		title: "¿Está usted seguro de eliminar este registro?",
		text: "¡No podrá revertir esta eliminación!",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Si, borrar este registro!",
		cancelButtonText: "Cancelar",
	}).then((result) => {
		if (result.isConfirmed) {
			borrar(id);
		}
	});
}
//Funcion para borrar
async function borrar(id) {
	const datos = new FormData();
	datos.append('id', id);
	try {
		const url = `${base_url}/Grupos/delete_row`;
		const repuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const resulta = await repuesta.json();
		Swal.fire({
			icon: `${resulta.icon}`,
			title: `${resulta.title}`,
			text: `${resulta.msg}`,
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = `${base_url}/Grupos`;
			};
		});
	} catch (error) {
		Swal.fire({
			icon: 'error',
			title: 'Error.....',
			text: 'No se pudo eliminar el registro ya que se encuentra asociado'
		});
	}
}
/**Validar si se mostrar en FrontEnd */
$("#view_internet").on('change', function () {
	if ($(this).is(":checked")) {
		$("#icono").prop('disabled', false);
		$("#descripcion").prop('disabled', false);
	} else {
		$("#icono").val('');
		$("#descripcion").val('');
		$("#icono").prop('disabled', true);
		$("#descripcion").prop('disabled', true);
	}
})
$("#ruta_catalogo").on('change', function (e) {
	e.preventDefault();
	const file = this.files[0];
	if (file && file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
		Swal.fire({
			title: "Error en tipo de archivo",
			text: "Por favor, seleccione un archivo con formato PDF válido.",
			icon: "error"
		});
		this.value = ''; // Limpia el input
	}
})
// Evento al hacer clic en el botón Quitar
$('#btnQuitarArchivo').on('click', function (e) {
	e.preventDefault();

	// Marcar bandera para eliminar en PHP
	$('#eliminar_pdf').val('1');

	// Ocultar visualmente el contenedor del PDF
	$('#contenedorArchivoActual').slideUp(200);
});

// Si el usuario selecciona un nuevo archivo en el input file, cancelamos la bandera de borrar
$('#ruta_catalogo').on('change', function () {
	if (this.files.length > 0) {
		$('#eliminar_pdf').val('0'); // Se va a sustituir, no solo borrar
	}
});