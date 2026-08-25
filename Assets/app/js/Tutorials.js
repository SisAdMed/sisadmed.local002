$().ready(function () {
	//Validaciones
	$('#my_form').validate({
		ignore: [],
		rules: {
			fecha: "required",
			titulo: "required",
			url: "required",
			resumen: "required",
			contenido: "required",
			status: "required"
		},
		messages: {
			fecha: "Por favor, ingresa la fecha.",
			titulo: "Por favor, ingresa el título del tutorial.",
			url: "Por favor, ingresa la URL limpia.",
			resumen: "Por favor, ingresa el resumen del tutorial.",
			contenido: "Por favor, ingresa el contenido del tutorial.",
			status: "Por favor, selecciona el status del tutorial."
		}
	});
	//Cargar el Index
	form = $("form").attr("id");
	if (form === undefined) {
		initTutorials();
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
					url: `${base_url}/Tutorials/destroy`,
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
			const url = `${base_url}/Tutorials/store`;
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
							window.location.href = `${base_url}/Tutorials`;
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
function initTutorials() {
	const title = "Internet - Tutoriales";
	const origen = "Tutorials";
	const id_menu = 205;
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
		{ data: "fecha", title: "Fecha", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN) },
		{ data: "url", title: "URL" },
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
	$("#fecha").val(GetTodayDate(0));
	listar_status(1);
}
function show_row(id) {
	const url = `${base_url}/Tutorials/show_row`;
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
			$("#fecha").val(data.fecha);
			$("#titulo").val(data.titulo);
			$("#url").val(data.url);
			$("#resumen").val(data.resumen);
			$("#contenido").summernote('code', decodeHTMLEntities(data.contenido));
			if (data.view_internet == 1) {
				$("#view_internet").prop('checked', true);
			}
			listar_status(data.status);
			if (data.imagen) {
				$('#vistaPrevia').attr('src', `${base_url}/Assets/img/tutorials/${data.imagen}`);
			} else {
				$('#vistaPrevia').attr('src', `${base_url}/Assets/img/no_picture.jpg`);
			}
		},
	});
}