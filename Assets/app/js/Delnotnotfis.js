/*! 
* Funciones Notas de Entrega No Fiscal 
* Copyright (c) 2026, Sisadmed.
* 11-06-2026 Creado por: José Vargas - Optimización 09:31:00
*/
//Variables
tabla = "tblDetalle";
item = 0;
tipo_fac = GetURLParameter("ori");
// Guardar un texto simple (por ejemplo, el tema seleccionado)
localStorage.setItem('tipo_fac', tipo_fac);
//Al iniciar la página, se ejecuta esta función para cargar los datos de las notas de entrega no fiscal
$().ready(function () {
	//Validaciones
	$("form#my_form").validate({
		ignore: null,
		rules: {
			id_emp: "required",
			id_tdo: "required",
			fecha_comp: "required",
			id_cli: "required",
			id_moneda: "required",
			tasa_cambio: "required",
			id_vend: "required",
			descrip_cot: "required",
			item: "required",

		},
		messages: {
			id_emp: "Debe especifiar una Empresa",
			id_tdo: "Debes especificar un Tipo de Documento",
			fecha_comp: "Debe especificar uan Fecha de Emisión",
			id_cli: "Debe especificar un cliente",
			id_moneda: "Debe especificar un Tipo de Moneda",
			tasa_cambio: "Debe especificar una Tasa de Cambio",
			id_vend: "Debe especificar un Vendedor",
			descrip_cot: "Debe especificar una Observación",
			item: "El documento debe tener al menos un detalle"
		},
	});
	//Cargar el Index
	form = $("form").attr("id");
	tipo_fac = localStorage.getItem('tipo_fac');
	if (form === undefined) {
		initDelnotnotfis();
	} else {
		id = $("#id").val();
		if (id) {
			show_row(id, tipo_fac);
		} else {
			dat_form_new();
		}
	}
	//
	//Validar Empresa Seleccionada
	$(document).on("change", "#id_emp", async function (e) {
		event.preventDefault();
		id = $("#id").val();
		$("#id_tdo").val("");
		id_tdo_val = "";
		id_tdo_cfg = "";
		id_emp = $("#id_emp").val();
		id_tdo_cfg = await tip_doc_fac(id_emp);
		id_alm_ppal = id_tdo_cfg["id_alm"];
		loc_pri_inv = id_tdo_cfg["loc_pri_inv"];
		if (!id) {
			if (id_tdo_cfg) {
				if (tipo_fac == "F") {
					id_tdo_val = id_tdo_cfg["id_tdoc_fac"];
				} else if (tipo_fac == "C") {
					id_tdo_val = id_tdo_cfg["id_tdoc_cre"];
				} else if (tipo_fac == "N") {
					id_tdo_val = id_tdo_cfg["id_tdoc_not"];
				} else if (tipo_fac == "Z") {
					id_tdo_val = id_tdo_cfg["id_tdoc_not_no_fis"];
				}
				id_tdoc_pre = id_tdo_cfg["id_tdoc_pre"];
				listar_tipos_documentos(id_emp, tipo_fac, id_tdo_val);
				$("#id_tdo").css("pointer-events", "none");
				//ALmacen y Ubicacion segun el consignado
				id_alm_def = id_tdo_cfg["id_alm"];
				id_ubi_def = id_tdo_cfg["id_ubi"];
				$("#id_alm_def").val(id_alm_def);
				$("#id_ubi_def").val(id_ubi_def);
				$("#id_tdo").trigger("change");
			}
			listar_tipos_documentos(id_emp, "P", id_tdoc_pre, false, "fuente");
			$("#fuente").css("pointer-events", "none");
			$("#fuente").trigger("change");
		}
		stock = id_tdo_cfg["fac_stock"];
	});
	//Validar si el Tipo de Documento usa consecutivo o no para poder aisgnar el número del documento
	$(document).on("change", "#id_tdo", async function (e) {
		e.preventDefault()
		const datos = new FormData();
		datos.append("id", id_tdo_val);
		try {
			const url = `${base_url}/CXCDocument/val_tdo`;
			const respuesta = await fetch(url, {
				method: "POST",
				body: datos,
			});
			const resultado = await respuesta.json();
			if (resultado) {
				if (resultado["con_tdoc"] == 0) {
					$("#num_tdo").prop("readonly", false);
				} else {
					$("#num_tdo").prop("readonly", true);
				}
			}
		} catch (error) {
			console.log(error);
		}
	});
	//Lleno el combo de origen de facturacion por la fuente seleccionada
	$(document).on("change", "#fuente", function (e) {
		e.preventDefault();
		id_empr = $("#id_emp").val();
		id_emp = id_empr;
		listar_cotizacones(id_emp, "0", "origen");
	});
	//Completar datos del encabezado y detalle de la factura al seleccioanr un origen
	$(document).on("change", "#origen", function (e) {
		e.preventDefault();
		id_cotiza = $("#origen").val();
		$("#cuerpoTablaDetalle").html("");
		show_data_cotiza(id_cotiza);
	});
	//Al cambiar la fecha de emisión 
	$("#fecha_comp").on('change', async function (e) {
		e.preventDefault();
		id_emp = $("#id_emp").val();
		//Validar Fechas de Contabilidad
		id_emp_cfg = get_empresa_config(id_emp);
		fec_ctb = id_emp_cfg["fec_cxc"];
		fecha_comp = $(this).val();
		if (fecha_comp <= fec_ctb) {
			$("#fecha_comp").addClass("is-invalid");
			$("#fecha_comp").attr("title", "La fecha del documento no puede ser menor a la fecha de contabilidad de la empresa");
			$("#btnok").prop("disabled", true);
		} else {
			$("#fecha_comp").removeClass("is-invalid");
			$("#btnok").prop("disabled", false);
		}
		id_cli = $("#id_cli").val();
		id_moneda = $("#id_moneda").val();
		if (id_cli) {
			const datosProvee = await tid_vend(id_cli);
			cod_diascre = datosProvee["cod_diascre"] + 1;
			fecha_venci = GetTodayDate(cod_diascre, fecha_comp);
			$("#fecha_venci").val(fecha_venci);
			$("#id_moneda").val(id_moneda);
			$("#id_moneda").trigger("change");
		}
		$("#id_cli").trigger('change');
	})
	//Seleccioanr por defecto el vendedor al momento de escoger el cliente
	$(document).on("change", "#id_cli", function (e) {
		e.preventDefault();
		fetcingData($(this).val());
	});
	//Eliminar un registro
	$("#tblIndexMain").on("click", ".btn-delete", function () {
		var recordId = $(this).data("id"); // Obtiene el ID del registro
		var recordCode = $(this).data("code"); // Obtine el Tipo Doc
		var recordName = $(this).data("name"); // Obtine el nombre
		var descrip = `¿Está seguro de eliminar el Documento ${recordName}? ${recordCode} .`
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
				const url = `${base_url}/Facturacion/destroy`;
				$.ajax({
					url: url, // URL de tu script de eliminación en el servidor
					method: "POST",
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
								var tableMain = $('#tblIndexMain').DataTable();
								tableMain.ajax.reload(null, false);
							}
						});
					},
					error: function (xhr, status, error) {
						loader.hide();
						console.error(xhr.responseText);
					},
				});
			}
		});
	});
});
//Nuevo registro
function dat_form_new() {
	$(".foranea").hide();
	$(".local").hide();
	$(".consignado").hide();
	if ($("#tipo_fac").val() != undefined) {
		tipo_fac = $("#tipo_fac").val();
	}
	listar_empresas();
	listar_descuentos(0, "id_des_enca");
	$("#fecha_comp").val(GetTodayDate(0));
	$("#fecha_venci").val(GetTodayDate(0));
}
//Consultar Registro
function show_row(id, tipo = "") {
	let url = "", ttipo = "", onlyread = "";
	url = `${base_url}/Delnot/consultar_factura`;
	//Buscar el registro
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: { id_cot: id, tipo: tipo },
		dataType: 'json',
		beforeSend: function () {
			loader.show();
		},
		complete: function () {
			loader.hide();
		},
		error: function (PDOException) {
			loader.hide();
			console.log('Ha ocurrido el siguiente error:', PDOException.responseText);
		},
		success: function (data) {
			// 1. CARGAR EL ENCABEZADO
			var fila = data[0];
			var v = {};
			$.each(fila, function (key, value) {
				//Asignaciones de inputs del encabezado
				v[key] = value;
				// =========================================================
				// 2. FILTRAR Y LIMPIAR EL ARRAY PARA EL DETALLE
				// =========================================================				
				detalleLimpio = data.map(function (item) {
					return {
						// Estructura: 'llave_que_espera_tu_datatable': item.campo_del_array_original
						id_prod: item.id_prod,
						nom_prod: item.nom_prod,
						cant: item.can_det,
						stock: item.stock,
						uni_ven_prod: item.uni_vta,
						ventas_prod: item.pre_unit,
						ventas_prod1: item.pre_vta,
						iva_prod: item.iva_prod,
						total: item.sub_total
						// Agrega aquí solo los campos que definiste en las 'columns' de tu DataTable
					};
				});
			});
			id_emp = v.id_emp;
			id_cli = v.id_cli;
			listar_empresas(v.id_emp, true);
			listar_tipos_documentos(v.id_emp, ttipo, v.id_tdo, true);
			listar_monedas(v.id_moneda, true);
			listar_vendedores(v.id_vend, true);
			listar_descuentos(v.id_des_enca, "id_des_enca", true);
			$("#num_tdo").val(v.num_tdo);
			$("#num_tdo").attr("readonly", "readonly");
			$("#fecha_comp").val(v.fecha_comp);
			$("#fecha_venci").val(v.fecha_venci);
			$("#id_cli").val(v.id_cli);
			$("#nom_cli").val(v.nom_ent);
			$("#id_moneda").val(v.id_moneda);
			$("#tasa_cambio").val(format_number_with_dec_new(v.tasa_cambio, 4));
			$("#oc_cliente").val(v.oc_cliente);
			$("#descrip_cot").val(v.descrip_cot);
			//Información referente a los Almacenes de Salida y Entrada						
			if (v.alm_out || v.alm_in) {
				if (v.alm_out) {
					alm_out = v.alm_out.split("|");
					id_alm_def = alm_out[0];
					id_ubi_def = alm_out[1];
					$("#id_alm_def").val(id_alm_def);
					$("#id_ubi_def").val(id_ubi_def);
				}
				if (v.alm_in) {
					alm_in = v.alm_in.split("|");
					id_alm_cli = alm_in[0];
					id_ubi_cli = alm_in[1];
					$("#id_alm_cli").val(id_alm_cli);
					$("#id_ubi_cli").val(id_ubi_cli);
				} else {
					id_alm_cli = '';
					id_ubi_cli = '';
					$("#id_alm_cli").val(id_alm_cli);
					$("#id_ubi_cli").val(id_ubi_cli);
					$("#hide-in").prop('checked', true);
					$("#hide-in").trigger('change');
				}
				if (v.c_consig == 1) {
					mostar_data_inv();
				}
			}
			// =========================================================
			// 2. CARGAR EL DETALLE EN EL DATATABLE EXISTENTE
			// =========================================================
			// Supongamos que tu DataTable se inicializó en una variable llamada 'tabla_detalle'
			// Primero, limpiamos cualquier residuo que haya quedado en la tabla
			//table.clear();

			// Añadimos de golpe todo el arreglo de filas (data) que vino de la consulta PHP			
			table.rows.add(detalleLimpio);
			// Redibujamos el DataTable para que renderice los productos en pantalla
			table.draw();
			show_tasa(v.id_emp, v.id_moneda, v.tasa_cambio);
		}
	});
}
async function fetcingData(id_cli) {
	const datosFetched = await tid_vend(id_cli);
	cod_diascre = datosFetched["cod_diascre"] ?? 0;
	$("#fecha_venci").val($("#fecha_comp").val());
	if (cod_diascre) {
		fecha_venci = GetTodayDate(cod_diascre);
		$("#fecha_venci").val(fecha_venci);
	}
	id_ven_cli = datosFetched["id_vend"];
	id_mon_cli = datosFetched["id_moneda"];
	nom_cli = datosFetched["nom_ent"];
	handling_conver = datosFetched["handling_conver"];
	c_consig = datosFetched["c_consig"];
	id_ubi_cli = datosFetched["id_ubi"];
	id_alm_cli = datosFetched["id_alm"];

	id_ent = id_cli;
	$("#nom_cli").val(nom_cli);
	listar_vendedores(id_ven_cli);
	listar_monedas(id_mon_cli);
	fecha_comp = $("#fecha_comp").val();
	xTasaCambio = await getExchangerate(fecha_comp, id_mon_cli);
	if (!id) {
		$("#tasa_cambio").val(xTasaCambio);
		tasa_fac = financial(xTasaCambio, 8);
	}
	tasa_cambio = xTasaCambio;
	//Validar ubicación deli clietne si es consignado y si esta vacia mostrar el modal de Ubicacion
	if (id_alm_cli && c_consig == 1) {
		if (!id) {
			if (id_alm_cli) {
				$("#id_alm_cli").val(id_alm_cli);
			}
			if (id_ubi_cli) {
				$("#id_ubi_cli").val(id_ubi_cli);
			}
			$(".consignado").hide();
			if (c_consig == 1) {
				$(".consignado").show();
			}
			$("#modal-ubicaciones").modal('show');
			mostar_data_inv();
		}
	}
	show_tasa(id_emp, id_moneda, tasa_cambio);
}
//Guardar y/o Actualizarregistro
$("#my_form").on("submit", function (e) {
	e.preventDefault();
	if ($(this).valid()) {
		var formData = $(this).serialize();
		$(this).prop("disabled", true);
		const url = `${base_url}/Delnotnotfis/store`;
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
				loader.show();
			},
			complete: function () {
				loader.hide();
			},
			error: function (PDOException) {
				loader.hide();
				console.log('Ha ocurrido el siguiente error:', PDOException.statusText)
			},
			success: function (data) {
				Swal.fire({
					title: data.title,
					text: data.msg,
					icon: data.icon,
				}).then((result) => {
					if (data.icon != "error") {
						window.location.href = `${base_url}/Delnotnotfis`;
					}
				})
			},
		});
		$(this).prop("disabled", false);
	} else {
		return false;
	}
})
//Imprimir Nota de Entrega Fiscaul, con y sin membrete
function print_Delnotfis(e) {
	let num_cot = e.dataset.code;
	let nombre = e.dataset.name;
	let id_code = e.dataset.id;
	Swal.fire({
		icon: "question",
		title:
			"¿Está seguro que desea imprimir la " +
			nombre +
			" número " +
			num_cot +
			"?",
		showDenyButton: true,
		showCancelButton: true,
		confirmButtonText: "Con Logo ",
		denyButtonText: "Sin Logo",
	}).then((result) => {
		if (result.isConfirmed) {
			Swal.fire({
				icon: "question",
				title:
					"¿Desea imprimir solo cantidades la " +
					nombre +
					" número " +
					num_cot +
					"?",
				showCancelButton: true,
				confirmButtonText: "Si solo cantidades",
			}).then((result) => {
				if (result.isConfirmed) {
					window.open(
						`${base_url}/Delnotnotfis/print_Delnotfis_cant_con/` +
						id_code,
						"_blank"
					);
				} else {
					window.open(
						`${base_url}/Delnotnotfis/print_Delnotfis_con/` +
						id_code,
						"_blank"
					);
				}
			});
		} else if (result.isDenied) {
			Swal.fire({
				icon: "question",
				title:
					"¿Desea imprimir solo cantidades la " +
					nombre +
					" número " +
					num_cot +
					"?",
				showCancelButton: true,
				confirmButtonText: "Si solo cantidades",
			}).then((result) => {
				if (result.isConfirmed) {
					window.open(
						`${base_url}/Delnotnotfis/print_Delnotfis_cant_sin/` +
						id_code,
						"_blank"
					);
				} else {
					window.open(
						`${base_url}/Delnotnotfis/print_Delnotfis_sin/` +
						id_code,
						"_blank"
					);
				}
			});
		}
	});
}