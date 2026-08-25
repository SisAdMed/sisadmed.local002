$().ready(function () {
	//Validaciones
	$('#my_form').validate({
		ignore: [],
		rules: {
			fecha_publicacion: "required",
			titulo: "required",
			url_limpia: "required",
			resumen: "required",
			contenido: "required",
			status: "required"
		},
		messages: {
			fecha_publicacion: "Por favor, ingresa la fecha de publicación.",
			titulo: "Por favor, ingresa el título de la noticia.",
			url_limpia: "Por favor, ingresa la URL limpia.",
			resumen: "Por favor, ingresa el resumen de la noticia.",
			contenido: "Por favor, ingresa el contenido de la noticia.",
			status: "Por favor, selecciona el status de la noticia."
		}
	});
	//Cargar el Index
	form = $("form").attr("id");
	if (form === undefined) {
		initNoticias();
	} else {
		id = $("#id").val();
		if (id) {
			show_row(id);
		} else {
			dat_form_new();
		}
	}
	// 1. Destruimos cualquier inicialización automática colgada
	if ($('#contenido').data('summernote')) {
		$('#contenido').summernote('destroy');
	}

	// 2. Arrancamos el editor totalmente limpio
	$('#contenido').summernote({
		height: 300, // Le fijas una altura cómoda para trabajar
		lang: 'es-ES', // Idioma español
		// 1. Agregar el botón 'fontsize' en el toolbar
		toolbar: [
			['style', ['style']],
			['font', ['bold', 'underline', 'clear']],
			['fontname', ['fontname']],
			['fontsize', ['fontsize']], // <-- AQUÍ SE ACTIVA EL TAMAÑO DE FUENTE
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['table', ['table']],
			['insert', ['link', 'picture', 'video']],
			['view', ['fullscreen', 'codeview', 'help']]
		],

		// 2. Opcional: Personalizar la lista de tamaños disponibles (en px)
		fontsizeUnits: ['px', 'pt'], // Unidades permitidas
		fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '36', '48']
	});

	// Aquí ya debes tener tu código de: $('#contenido').summernote();
	// Y el código de la vista previa de la imagen...

	// ─── GENERACIÓN DE URL LIMPIADA EN VIVO ───
	$("#titulo").on("keyup", function () {

		var texto = $(this).val();

		// 1. Convertimos a minúsculas y limpiamos espacios de los extremos
		var slug = texto.toLowerCase().trim();

		// 2. Reemplazamos acentos y la eñe para evitar caracteres raros en la URL
		slug = slug.replace(/[áàäâ]/g, "a")
			.replace(/[éèëê]/g, "e")
			.replace(/[íìïî]/g, "i")
			.replace(/[óòöô]/g, "o")
			.replace(/[úùüû]/g, "u")
			.replace(/[ñ]/g, "n");

		// 3. Quitamos todo lo que NO sea letra, número, espacio o guion
		slug = slug.replace(/[^a-z0-9\s-]/g, "");

		// 4. Cambiamos los espacios (uno o varios seguidos) por un único guion
		slug = slug.replace(/[\s-]+/g, "-");

		// 5. Por si acaso, limpiamos guiones sobrantes al principio o al final
		slug = slug.replace(/^-+|-+$/g, "");

		// 6. Estampamos el resultado en vivo en el campo de la URL
		$("#url_limpia").val(slug);

	});
	// Código mágico para previsualizar la imagen en vivo
	$("#subirImagen").change(function () {
		// Validamos que el usuario sí haya seleccionado un archivo
		if (this.files && this.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				// Cambiamos el atributo 'src' de la imagen por los datos del archivo cargado
				$('#vistaPrevia').attr('src', e.target.result);
			}

			reader.readAsDataURL(this.files[0]);
		} else {
			// Si cancela la selección, volvemos a poner la imagen por defecto
			$('#vistaPrevia').attr('src', `${base_url}/Assets/img/no_picture.jpg`);
		}
	});
	// Escuchar cuando el usuario escribe en el campo Título
	$("#titulo").on('keyup', function () {

		// 1. Agparamos el valor del título
		var texto = $(this).val();

		// 2. Lo pasamos a minúsculas
		var slug = texto.toLowerCase();

		// 3. Reemplazamos acentos y caracteres especiales comunes
		slug = slug.replace(/[áàäâ]/g, "a")
			.replace(/[éèëê]/g, "e")
			.replace(/[íìïî]/g, "i")
			.replace(/[óòöô]/g, "o")
			.replace(/[úùüû]/g, "u")
			.replace(/[ñ]/g, "n");

		// 4. Quitamos cualquier carácter que no sea una letra, número, espacio o guión
		slug = slug.replace(/[^a-z0-9\s-]/g, "");

		// 5. Cambiamos espacios en blanco (uno o más) por un solo guión corto
		slug = slug.replace(/[\s-]+/g, "-");

		// 6. Limpiamos guiones que queden huérfanos al inicio o al final
		slug = slug.trim().replace(/^-+|-+$/g, "");

		// 7. Estampamos el resultado en el input de la URL limpia
		$("#url_limpia").val(slug);

	});
	//Eliminar Registro
	$(document).on('click', '.btn-delete', function (e) {
		e.preventDefault();
		e.stopPropagation();
		var table = $("#tblIndexMain").DataTable();
		var row = $(this).closest('tr');
		if (row.hasClass('child')) {
			row.row.prev();
		}
		var data = table.row(row).data();
		let id = data.id;
		let title = data.titulo;
		Swal.fire({
			icon: 'question',
			title: 'Está seguro de eliminar esta noticia con el título de ' + title + '?',
			showConfirmButton: true,
			confirmButtonText: 'ELIMINAR',
			confirmButtonColor: '#3085d6',
			showCancelButton: true,
			cancelButtonText: 'CANCELAR',
			cancelButtonColor: '#d33',
			buttonsStyling: true,
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: `${base_url}/News/destroy`,
					method: 'POST',
					data: { id: id, title: title },
					dataType: 'json',
					success: function (data) {
						Swal.fire({
							icon: `${data.icon}`,
							title: `${data.title}`,
							text: `${data.msg}`,
						}).then((result) => {
							if (result.isConfirmed) {
								table.ajax.reload(null, false);
							};
						});
					}
				})
			};
		});
	});
	//Guardar y/o Actualizarregistro
	$("#my_form").on("submit", function (e) {
		e.preventDefault();
		var boton = $("#btnok");
		//boton.prop('disabled', true);
		if ($(this).valid()) {
			$('#status').prop('disabled', false);
			status = $("#status").val();
			var formData = new FormData(this);
			const url = `${base_url}/News/store`;
			//Activar status		
			//Ajax para Guardar y/o Actualizar
			$.ajax({
				url: url,
				method: 'POST',
				dataSrc: '',
				data: formData,
				processData: false,       // Prevent jQuery from trying to convert the FormData to a string
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
					console.log('Ha ocurrido el siguiente error:', error)
				},
				success: function (data) {
					// Valores por defecto que vienen de tu PHP
					let tituloAlert = data.title;
					let textoAlert = data.msg;
					let iconoAlert = data.icon;
					Swal.fire({
						title: tituloAlert,
						text: textoAlert,
						icon: iconoAlert,
					}).then((result) => {
						if (data.icon != "error") {
							window.location.href = `${base_url}/News`;
						}
						boton.prop('disabled', false);
						$('#status').prop('disabled', true);
					})
				},
			});
		} else {
			boton.prop('disabled', false);
			$('#status').prop('disabled', true);
			return false;
		}
	})
});
function initNoticias() {
	const title = "Internet - Noticias";
	const origen = "News";
	const id_menu = 203;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 || permisos_upd == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/gestion/${row.token_edit}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id}" data-name="${row.titulo}" data-code = "${row.cod_tmocxc}" type="button" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></button>     `;
				}
				return t_menu;
			},
		},
		{ data: "titulo", title: "Título" },
		{ data: "fecha_publicacion", title: "Fecha de publicación", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN) },
		{ data: "url_limpia", title: "URL" },
		{
			data: null, title: "Vista en la Web", className: "text-center",
			render: function (data, type, row) {
				if (row.view_internet == 1) {
					return '<input type="checkbox" checked disabled></input>';
				} else {
					return '<input type="checkbox" unchecked disabled></input>';
				}
			}
		},
		{
			data: null,
			title: "Status",
			className: "text-center",
			render: function (data, type, row) {
				var status = "";
				var clase = "";
				if (row.status == 1) {
					status = "Activo";
					clase = "badge-success";
				} else if (row.status == 0) {
					status = "Inactivo";
					clase = "badge-danger";
				} else if (row.status == 9) {
					status = "Por aprobar";
					clase = "badge-warning";
				}
				return `<span class="badge ${clase}">${status}</span>`;
			},
		},
	]);
}
function dat_form_new() {
	$("#fecha_publicacion").val(GetTodayDate(0));
	listar_status(1);
}
function show_row(id) {
	const url = `${base_url}/News/show_row`;
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
		error: function (error) {
			loader.hide();
			console.log('Ha ocurrido el siguiente error:', error)
		},
		success: function (data) {
			$("#fecha_publicacion").val(data.fecha_publicacion);
			$("#titulo").val(data.titulo);
			$("#url_limpia").val(data.url_limpia);
			$("#resumen").val(data.resumen);
			$("#contenido").summernote('code', decodeHTMLEntities(data.contenido));
			if (data.view_internet == 1) {
				$("#view_internet").prop('checked', true);
			}
			listar_status(data.status);
			if (data.imagen) {
				$('#vistaPrevia').attr('src', `${base_url}/Assets/img/news/${data.imagen}`);
			} else {
				$('#vistaPrevia').attr('src', `${base_url}/Assets/img/no_picture.jpg`);
			}
		},
	});
}