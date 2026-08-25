/*
 * Funciones Clientes
 * Copyright 2025-2025
 * 20-11-2025 Creación de Archivo José Vargas 23:25:00
 */
//Variables
// AL Iniciar la aplicación
$().ready(function () {
	//Validaciones
	$("form#my_form").validate({
		ignore: [],
		rules: {
			rif_ent: "required",
			nom_ent: {
				required: true,
				minlength: 5,
				maxlength: 200,
			},
			cor_ent: "required",
			id_vend: "required",
			id_zona: "required",
			postal_ent: "required",
			id_por_ret_iva: {
				required: function () {
					return $("#contr_esp").is(":checked");
				},
			},
			id_pais: "required",
			id_edo: "required",
			id_ciudad: "required",
			dir_ent: {
				required: true,
				minlength: 20,
			},
			id_emp: "required",
			id_motcam: "required",
			id_moneda: "required",
			id_diascre: "required",
			id_tipocliente: "required",
			id_alm: {
				required: function () {
					return $("#c_consig").is(":checked");
				}
			},
			url: {
				required: function (element) {
					return $("#internet").is(":checked");
				}
			},
			/*
			logo_ent: {
				required: function (element) {
					return $("#internet").is(":checked");
				}
			},*/
			status: "required",
		},
		messages: {
			rif_ent: "Debe especificarun RIF",
			nom_ent: {
				required: "Debe especificar un nombre",
				minlength: "Debe contener al menos {0} carácteres",
				maxlength: "Debe contenedor máximo {0} carácteres",
			},
			cor_ent: "Debe especificar un nombre corto",
			id_vend: "Debe especificar un vendedor",
			id_zona: "Debe especificar una zona",
			postal_ent: "Debe especificar una zona postal",
			id_por_ret_iva: "Debe especificar un Porcentaje",
			id_pais: "Debe especificar un pais",
			id_edo: "Debe especificar un estado",
			id_ciudad: "Debe especificar una ciudad",
			dir_ent: {
				required: "Debe especificar una dirección",
				minlength: "Debe contener al menos {0} carácteres",
			},
			id_emp: "Debe especificar una empresa a facturar",
			id_motcam: "Debe especificar un motiva de facturación",
			id_moneda: "Debe especificar una moenda para facturación",
			id_diascre: "Debe especificar los Días de Crédito",
			id_tipocliente: "Debe especificar un Tipo de Cliente",
			id_alm: "Debe especificar un Almacén",
			/*url: "Debe especificar la url del cliente",*/
			logo_ent: "Debe especificar un logo del cliente",
			status: "Debe espeficicar un status",
		},
		invalidHandler: function (event, validator) {
			// 1. Limpiamos todos los asteriscos previos
			$(".error-star").text("");
			// 2. Recorremos los campos con error
			$.each(validator.errorList, function (index, error) {
				// Buscamos el tab-pane más cercano al input con error
				var tabId = $(error.element).closest('.tab-pane').attr('id');
				// Buscamos el enlace (tab) que apunta a ese ID y le ponemos el asterisco
				$('a[href="#' + tabId + '"]').find(".error-star").text(" *");
			});
		},
		success: function (label, element) {
			// Opcional: Quitar el asterisco si el campo ya es válido
			var tabPane = $(element).closest('.tab-pane');
			var tabId = tabPane.attr('id');
			// Si ya no hay más inputs con clase 'error' en este panel, quitamos el asterisco
			if (tabPane.find("input.error").length === 0) {
				$('a[href="#' + tabId + '"]').find(".error-star").text("");
			}
		}
	});
	//Cargar el Index
	form = $("form").attr("id");
	if (form === undefined) {
		initClientes();
	} else {
		id = $("#id").val();
		if (id) {
			show_row(id);
		} else {
			dat_form_new();
		}
	}
});
//Nuevo Registro
function dat_form_new() {
	listar_vendedores();
	listar_zonas("", "id_zona");
	listar_paises("", "id_pais");
	listar_dias_credito();
	listar_empresas();
	listar_motivo_cambio(0);
	listar_monedas(0);
	listar_tipos_clientes(0);
	listar_statusEntidad(0, "status");
}
//
//Consultar registro
function show_row(id) {
	var formData = $(this).serialize();
	const url = `${base_url}/Clientes/show_row`
	//Ajax para 
	var promesa = $.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: { id: id },
		dataType: 'json',
		contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		beforeSend: function () {
			loader.show();
		},
		complete: function () {
			loader.hide();
		},
		error: function (PDOException) {
			loader.hide();
			console.log('Ha ocurrido el siguiente error:', PDOException.responseText)
		},
		success: function (data) {
			$("#rif_ent").val(data[0].rif_ent);
			$("#nom_ent").val(decodificarHTML(data[0].nom_ent));
			$("#cor_ent").val(data[0].cor_ent);
			id_vend = data[0].id_vend;
			listar_vendedores(id_vend, true);
			id_zona = data[0].id_zona;
			listar_zonas(id_zona, "id_zona", true);
			$("#postal_ent").val(data[0].postal_ent);
			contr_esp = data[0].contr_esp;
			if (contr_esp == 1) {
				$("#contr_esp").prop("checked", true);
			} else {
				$("#contr_esp").prop("checked", false);
			}
			id_por_ret_iva = data[0].id_por_ret_iva;
			listar_retiva(id_por_ret_iva, "id_por_ret_iva");
			id_pais = data[0].id_pais;
			listar_paises(id_pais);
			id_edo = data[0].id_edo;
			listar_estados(id_pais, id_edo);
			id_ciudad = data[0].id_ciudad;
			listar_ciudades(id_edo, id_ciudad);
			id_diascre = data[0].id_diascre;
			listar_dias_credito(id_diascre);
			$("#dir_ent").html(decodificarHTML(data[0].dir_ent));
			id_emp = data[0].id_emp;
			listar_empresas(id_emp, true);
			id_motcam = data[0].id_motcam;
			listar_motivo_cambio(id_motcam, true);
			id_moneda = data[0].id_moneda;
			listar_monedas(id_moneda, true);
			id_tipocliente = data[0].id_tipocliente;
			listar_tipos_clientes(id_tipocliente, true);
			status = data[0].status;
			listar_statusEntidad(status, "status");
			view_internet = data[0].view_internet;
			if (view_internet == 1) {
				$("#internet").prop('checked', true);
				$("#url").prop('disabled', false);
			} else {
				$("#internet").prop('checked', false);
				$("#url").prop('disabled', true);
			}
			$("#url").val(data[0].url)
			logo = data[0].logo_ent;
			$("#imgPreview").attr('src', logo);
			//Cargar contactos
			if (!miTabledet_con) {
				miTabledet_con = $("#det_con").DataTable({
					responsive: true,
					info: false,
					paging: false,
					searching: false,
					destroy: true,
					clear: true,
					language: {
						url: `${base_url}/Assets/json/es-ES.json`,
					},
				});
			}
			item_det = 0;
			$.each(data, function (index, xitem) {
				if (xitem.nom_con != null) {
					item_det++;
					var htmlTags = [
						`<input type="text" class="form-control text-xs" id="nom_con${item_det}" name="nom_con[]" placeholder="Nombre" style="text-transform:uppercase;" value="${xitem.nom_con}" >`,
						`<input type="text" class="form-control text-xs" id="ape_con${item_det}" name="ape_con[]" placeholder="Apellido" style="text-transform:uppercase;" value="${xitem.ape_con}">`,
						`<input type="email" class="form-control text-xs" id="${item_det}" name="email_con[]" placeholder="Correo" style="text-transform:lowercase;" value="${xitem.email_con}">`,
						`<select class="form-control text-xs" id="id_pre${item_det}" name="id_pre[]"></select>`,
						`<input type="text" class="form-control text-xs" id="num_tel_con${item_det}" name="num_tel_con[]" placeholder="000-00-00" pattern="[0-9]{3}-[0-9]{2}-[0-9]{2}" >`,
						`<select class="form-control text-xs" id="id_dep${item_det}" name="id_dep[]" id="id_dep"></select>`,
						`<button type="button" class="btn btn-danger btn-xs borrar" title="Eliminar contacto" ><i class="far fa-trash-alt"></i></button>`,
					];
					miTabledet_con.row.add(htmlTags);
					miTabledet_con.draw();
					//Poblar selects
					listar_codigos_area(xitem.id_pre, `id_pre${item_det}`);
					listar_dpto_ent(xitem.id_dep, `id_dep${item_det}`);
					$(`#num_tel_con${item_det}`).addClass(`number-phone`);
					$(".number-phone").mask(mobileMaskBehavior, mobileOptions);
					$(`#num_tel_con${item_det}`).val(xitem.num_tel_con);
					//Posicionarme en el campo Nombre
					$(`#nom_con${item_det}`).focus();
				}
			});
			//Fin contactos
			//Adicionales
			$("#note_fac").val(data[0].note_fac_custom);
			id_alm = data[0].id_alm;
			listar_almacenes(id_emp, id_alm);
			id_ubi = data[0].id_ubi;
			listar_ubicaciones("id_ubi", id_ubi, "N", id_emp);
			c_consig = data[0].c_consig;
			if (c_consig == 1) {
				$("#c_consig").prop("checked", true);
			} else {
				$("#c_consig").prop("checked", false);
			}
			handling_conver = data[0].handling_conver;
			if (handling_conver == 1) {
				$("#handling_conver").prop("checked", true);
			} else {
				$("#handling_conver").prop("checked", false);
			}
			print_lote = data[0].print_lote;
			if (print_lote == 1) {
				$("#print_lote").prop("checked", true);
			} else {
				$("#print_lote").prop("checked", false);
			}
			print_special = data[0].print_special;
			if (print_special == 1) {
				$("#print_special").prop("checked", true);
			} else {
				$("#print_special").prop("checked", false);
			}
			req_exc_rat = data[0].req_exc_rat;
			if (req_exc_rat == 1) {
				$("#req_exc_rat").prop("checked", true);
			} else {
				$("#req_exc_rat").prop("checked", false);
			}
			$("#cant_dec").val(data[0].cant_dec);
			//Fin Adicionales
		},
	});
	promesa.then(function () {
		//Financiero
		//Cargar Estados de Cuenta
		const url = `${base_url}/CXCDocument/edo_cuenta_data`;
		id_ent = $("#id").val();
		nom_ent = $("#nom_ent").val();
		$("#tblCXCedo_cuenta").empty();
		var formData = $(this).serialize();
		//Ajax para
		$.ajax({
			url: url,
			method: "POST",
			dataSrc: "",
			data: { id_emp: id_emp, id_cli: id_ent },
			dataType: "json",
			beforeSend: function () {
				loader.show();
			},
			complete: function () {
				loader.hide();
			},
			error: function (PDOException) {
				loader.hide();
				console.log("Ha ocurrido el siguiente error:", PDOException.responseText);
			},
			success: function (data) {
				var tabla = $("#tblCXCedo_cuenta");
				//Obtener la estructura de datos( columnas y filas)				
				//Crear DataTable
				tabla.DataTable({
					responsive: true,
					data: data,
					dataType: "json",
					autoWidth: true,
					paging: false,
					destroy: true,
					info: false,
					columns: [
						{ data: "nombre_emp", title: "Empresa", visible: false },
						{ data: "nom_ent", title: "Proveedor", visible: false },
						{ data: "nom_tdoc", title: "Documento" },
						{ data: "fecha_comp", title: "Fecha", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN) },
						{ data: "cod_diascre", title: "Días Créd", className: "text-right" },
						{ data: "dias_calle", title: "Días Calle", className: "text-right" },
						{ data: "num_tdo", title: "Número", className: "text-right" },
						{ data: 'mon_exe_dom', title: 'Exento', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2) },
						{ data: 'mon_base_dom', title: 'Base', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2) },
						{ data: 'mon_iva_dom', title: 'Mon IVA', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2) },
						{ data: 'ret_iva_dom', title: 'Reten.IVA', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2) },
						{ data: 'por_cob_dom', title: 'Por Cobrar', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2) },
						{ data: 'mon_exe_for', title: 'Exento', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2) },
						{ data: 'mon_base_for', title: 'Base', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2) },
						{ data: 'mon_iva_for', title: 'Mon IVA', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2) },
						{ data: 'ret_iva_for', title: 'Reten.IVA', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2) },
						{ data: 'por_cob_for', title: 'Por Cobrar', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2) },
						{
							data: "tasa_cambio", title: 'Tasa', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2),
						},
						{
							data: null, title: 'Status', className: 'text-center',
							render: function (row, type, data) {
								if (data.dias_calle > data.cod_diascre) {
									return "VENCIDO";
								} else {
									return "VIGENTE";
								}
							}
						},
					],
					//Totales
					footerCallback: function (row, data, start, end, display) {
						$("#tblCXCedo_cuenta tfoot").remove();
						var htmlTags = `<tfoot>
							<tr>
								<th colspan="5" class="text-right">Totales:</th>`
						var api = this.api();
						// Remove the formatting to get integer data for summation
						var intVal = function (i) {
							return typeof i === "string"
								? i.replace(/[\$,]/g, "") * 1
								: typeof i === "number"
									? i
									: 0;
						};
						//Indices de las columnas a totalizar
						var columnasATotalizar = [7, 8, 9, 10, 11, 12, 13, 14, 15, 16];
						//Recorrer columnas a Totalizar                        
						columnasATotalizar.forEach(function (columna) {
							//Total todas las páginas
							var total = api
								.column(columna)
								.data()
								.reduce(function (a, b) {
									return intVal(a) + intVal(b);
								}, 0);
							//Actualizar footer                                  
							htmlTags += `<th class="text-right">${format_number_with_dec_new(total, 2)}</th>`;
							$(api.column(columna).footer()).html(format_number_with_dec_new(total, 2));
							//                            
						});
						htmlTags += `<th colspan="2"></th></tr></tfoot>`;
						$("#tblCXCedo_cuenta").append(htmlTags);
					},
					language: {
						url: `${base_url}/Assets/json/es-ES.json`,
					},
				});
			}
		});
	});
}
//Al seleccionar empresa mostrar Almacnes y ubicaciones
$("#id_emp").change(function () {
	var id_emp = $(this).val();
	listar_almacenes(id_emp);
	listar_ubicaciones("id_ubi", 0, "N", id_emp);
});
//Función para recargar el datatable
$(".refresh-button").on("click", function () {
	tableIndex.ajax.reload(null, false);
});
//Eliminar un registro
$("#tblIndexMain").on("click", ".btn-delete-index", function () {
	var recordId = $(this).data("id"); // Obtiene el ID del registro
	var recordCode = $(this).data("code"); // Obtine el Tipo Doc
	var recordName = $(this).data("name"); // Obtine el nombre
	var descrip = `¿Está seguro de eliminar el Cliente ${recordCode} ${recordName}?.`
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
			const url = `${base_url}/Clientes/destroy`;
			$.ajax({
				url: url, // URL de tu script de eliminación en el servidor
				type: "POST",
				data: { id: recordId, recordCode: recordCode, recordName: recordName },
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
							// Recarga el DataTable
							//tableIndex.draw(false); // El 'false' previene que se reajuste la paginación a la página 1.
							tableIndex.ajax.reload(null, false);
						}
					});
				},
				error: function (xhr, status, error) {
					loader.hide();
					alert("Hubo un error en la solicitud.");
					console.error(xhr.responseText);
				},
			});
		}
	});
});
//Guardar y/o Actualizar registro
$("#my_form").on("submit", function (e) {
	e.preventDefault();
	var boton = $("#btnok");
	boton.prop('disabled', true);
	if ($(this).valid()) {
		var formData = new FormData(this);
		const url = `${base_url}/Clientes/store`;
		//Ajax para Guardar y/o Actualizar
		$.ajax({
			url: url,
			method: 'POST',
			dataSrc: '',
			data: formData,
			processData: false,       // Prevent jQuery from trying to convert the FormData to a string
			contentType: false,       // Prevent jQuery from setting a default Content-Type header
			dataType: 'json',
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
				boton.prop('disabled', false);
				return false;
			},
			error: function (PDOException) {
				loader.hide();
				console.log('Ha ocurrido el siguiente error:', PDOException.responseText)
			},
			success: function (data) {
				Swal.fire({
					title: data.title,
					text: data.msg,
					icon: data.icon,
				}).then((result) => {
					if (data.icon != "error") {
						window.location.href = `${base_url}/Clientes`;
					}
				})
			},
		});
	} else {
		boton.prop('disabled', false);
		return false;
	}
})
/**Validar si se mostrar en FrontEnd */
$("#internet").on('change', function () {
	if ($(this).is(":checked")) {
		$("#url").val('');
		$("#imgPreview").attr('src', '');
		$("#url").prop('disabled', false);
	} else {
		$("#url").val('');
		$("#imgPreview").attr('src', '');
		$("#url").prop('disabled', true);
	}
})