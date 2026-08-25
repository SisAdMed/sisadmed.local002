/*
 * Funciones CXCDocument
 * Copyright 2025-2025
 * 24-11-2025 Creación de Archivo José Vargas 12:03:00
 */
//Variables
let item = 0;
let table;
tabla = 'tblCxcDocument';
// AL Iniciar la aplicación
$().ready(function () {
	//Validaciones
	$("form#my_form").validate({
		ignore: null,
		rules: {
			id_emp: "required",
			id_tdo: "required",
			nom_cli: "required",
			fecha_comp: "required",
			fecha_venci: "required",
			id_moneda: "required",
			tasa_cambio: "required",
			descrip_cot: "required",
			motivo: {
				required: function (element) {
					return $("#status").val() == 9;
				},
				minlength: 20,
			},
			status: "required",
			item: {
				min: 1
			},
		},
		messages: {
			id_emp: "Debe especificar una Empresa",
			id_tdo: "Debe especificar un Tipo de Documento",
			nom_cli: "Debe especificar un Cliente",
			fecha_comp: "Debe especificar una Fecha de Emisión",
			fecha_venci: "Debe especificar una Fecha de Vencimiento",
			id_moneda: "Debe especificar una Moneda",
			tasa_cambio: "Debe especificar una Tasa de Cambio",
			descrip_cot: "Debe especificar una Descripción",
			motivo: {
				required: "Debe indicar un motivo de la Nota",
				minlength: "Debe especificar al menos 20 carácteres",
			},
			status: "Debe especificar un Status",
			item: {
				min: "Debe poseer al menos {0} Detalle de Documento"
			},
		}
	});
	/**Validar si se usará la isma Tasa del Documento para cuando es una Nota de Crédito */
	//Cargar el Index
	form = $("form").attr("id");
	if (form === undefined) {
		initCXCDocument();
	} else {
		id = $("#id").val();
		if (id) {
			show_row(id);
		} else {
			dat_form_new();
		}
	}
	//Verificar si estoy usando la vista prelimianr de un documento para su aprobacion
	if ($("embed#visor_pdf".length > 0)) {
		id_ado_apro = getCookie('id_ado_apro');
		var urlPdf = `${base_url}/CXCDocument/print_CXCDocument/${id_ado_apro}`;
		$('#visor_pdf').attr('src', urlPdf);
	}
	$("#btnAprobar").on('click', function (e) {
		fnc_aprobar_doc();
	});
})
//Aprobar Documento y notificar al Usuario
function fnc_aprobar_doc() {
	Swal.fire({
		title: "¿Está seguro de aprobar este documento?",
		icon: 'question',
		showDenyButton: true,
		showCancelButton: true,
		confirmButtonText: "Aprobar",
		denyButtonText: `No aprobar`,
		cancelButtonText: `Cancelar`
	}).then((result) => {
		if (result.isConfirmed) {
			const url = `${base_url}/CXCDocument/approved`;
			$.ajax({
				url: url, // URL de tu script de eliminación en el servidor
				type: "POST",
				data: { id: id_ado_apro },
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
							window.location.href = `${base_url}`;
						}
					});
				},
				error: function (jqXHR) {
					loader.hide();
					let err = JSON.parse(jqXHR.responseText);
					console.log("Database Error: " + err.message);
				},
			});
		} else if (result.isDenied) {
			Swal.fire("Documento no aprobado", "", "info");
		}
	});
}
//Nuevo registro
function dat_form_new() {
	listar_empresas();
	$("#fecha_comp").val(GetTodayDate(0));
	$("#fecha_venci").val(GetTodayDate(0));
	muestra_oculta("afectado");
	listar_monedas();
	listar_status(1);
}
//Consultar registro
function show_row(id) {
	var formData = $(this).serialize();
	tipo_fac = "C";
	const url = `${base_url}/CXCDocument/show_row`
	//Ajax para 
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: { id: id },
		dataType: 'json',
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
			id_emp = data[0].id_emp
			listar_empresas(id_emp, true);
			id_tdo = data[0].id_tdo;
			listar_tipos_documentos(id_emp, "", id_tdo, true, "id_tdo");
			$("#id_tdo").prop("readonly", true);
			$("#num_tdo").val(data[0].num_tdo);
			$("#num_tdo").prop("readonly", true);
			$("#nro_control").val(data[0].nro_control);
			id_cli = data[0].id_cli;

			$("#id_cli").val(id_cli);
			$("#nom_cli").val(data[0].nom_ent)
			id_moneda = data[0].id_moneda;
			listar_monedas(id_moneda, true);
			$("#fecha_comp").val(data[0].fecha_comp);
			$("#fecha_venci").val(data[0].fecha_venci)
			$("#tasa_cambio").val(format_number_with_dec_new(data[0].tasa_cambio, 2));
			$("#descrip_cot").val(data[0].descrip_cot)
			show_tasa(id_emp, id_moneda, data[0].tasa_cambio, 'C');
			status = data[0].status;
			id_doc_afec = data[0].id_doc_afec;
			$("#id_afectado").val(id_doc_afec);
			//if (id_doc_afec == 0) {
			doc_afectado = data[0].doc_afectado;
			$("#doc_afectado").val(doc_afectado);
			//}
			listar_status(status);
			if (status == 9) {
				$(".motivo").show();
				$("#motivo").val(data[0].motivo);
				$("#status").prop('disabled', true).val(9).trigger('change.select2');
			}
			item = 0;
			$.each(data, function (index, row) {
				item = item + 1;
				var htmlTags = `
					<tr id="fila-${item}">
						<td class="text-right text-xs">${item}</td>
						<td><input type="hidden" name="id_con[]" id="id_con${item}" class="text-xs" value="${row.id_concxc}"><div class="input-group"><input type="text" class="form-control text-xs id_con_cxc" id="nom_con${item}" name="nom_con[]" readonly value="${row.nombre_con}"><div class="input-group-append"><span class="input-group-text nom_con_cxp"><a href="#" data-toggle="modal" data-target="#modal-ConcepCXC" title="Buscar y seleccionar Cocneptos"><i class="fas fa-search"></i></a></span></div></div></td>
						<td><input type="hidden" name="id_aux[]" id="id_aux${item}" class="text-xs" value="${row.id_aux ?? ""}"><div class="input-group"><input type="text" class="form-control text-xs id_aux" id="nom_aux${item}" name="nom_aux[]" readonly value="${row.nom_aux ?? ""}"><div class="input-group-append"><span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i id="div_aux${item}" name="div_aux[]" class="fas fa-search"></i></a></span></div></div></td>
						<td><input type="text" name="mon_con[]" id="mon_con${item}" class="form-control text-right text-xs sub-total camponumero input-fila" value="${format_number_with_dec_new(row.monto, 2)}"></td>
						<td><select name="iva[]" id="iva${item}" class="form-control text-xs caliva input-iva"</select></td>
						<td><input type="text" name="mon_iva[]" id="mon_iva${item}" class="form-control text-right text-xs sub-total" readonly value="${format_number_with_dec_new(row.mon_iva, 2)}"></td>
						<td><input type="text" name="total[]" id="total${item}" class="form-control text-right text-xs sub-total" readonly value="${format_number_with_dec_new(row.monto + row.mon_iva, 2)}"></td>
						<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt"></i></button></div></td>
					</tr>`;
				$("#tblDetalle").append(htmlTags);
				listar_si_no(row.iva, `iva${item}`);
				$("#item").val(item);
				recorreTable_fac(0, data[0].tasa_cambio, 'C', 'tblCxcDocument');
			})

		},
	});
}
//Al seleccionar empresa, llebar los campos respectivos
$("#id_emp").on("change", function (e) {
	e.preventDefault();
	id_emp = $(this).val();
	listar_tipos_documentos(id_emp);
})
//Al seleccionar cliente
$("#id_cli").on("change", async function () {
	id_cli = $(this).val();
	const datosCli = await tid_vend(id_cli);
	nom_cli = datosCli["nom_ent"];
	cod_diascre = datosCli["cod_diascre"];
	id_moneda = datosCli["id_moneda"];
	listar_monedas(id_moneda, true);
	$("#id_moneda").val(id_moneda);
	$("#id_moneda").trigger("change");
	$("#fecha_venci").val(GetTodayDate(cod_diascre));
	$("#nom_cli").val(nom_cli);
})
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
})
//Agregar un nuevo registro
$("#btnAddRow").on("click", function (e) {
	e.preventDefault();
	item++;
	var htmlTags = `
		<tr id="fila-${item}">
			<td class="text-right text-xs" style="width:5%">${item}</td>
			<td><input type="hidden" name="id_con[]" id="id_con${item}" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs id_con_cxc" id="nom_con${item}" name="nom_con[]" readonly><div class="input-group-append"><span class="input-group-text nom_con_cxc"><a href="#" data-toggle="modal" data-target="#modal-ConcepCXC" title="Buscar y seleccionar Cocneptos"><i class="fas fa-search"></i></a></span></div></div></td>
			<td><input type="hidden" name="id_aux[]" id="id_aux${item}" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs id_aux" id="nom_aux${item}" name="nom_aux[]" readonly><div class="input-group-append"><span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i id="div_aux${item}" name="div_aux[]" class="fas fa-search"></i></a></span></div></div></td>
			<td><input type="text" name="mon_con[]" id="mon_con${item}" class="form-control text-right text-xs sub-total camponumero input-fila"></td>
			<td><select name="iva[]" id="iva${item}" class="form-control text-xs caliva input-iva"</select></td>
			<td><input type="text" name="mon_iva[]" id="mon_iva${item}" class="form-control text-right text-xs sub-total" readonly></td>
			<td><input type="text" name="total[]" id="total${item}" class="form-control text-right text-xs sub-total" readonly></td>
			<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt"></i></button></div></td>
		</tr>`;
	$('#tblDetalle').append(htmlTags);
	$("#item").val(item);
	listar_si_no(0, `iva${item}`);
})
//Obtener el ultimo item de la tabla
function last_item_table_cxp(table_id = "tblDetalle") {
	var max_item = 0;
	last_row = $("#" + table_id + " tr:last");
	if (last_row[0]) {
		id = last_row[0];
		last_id = id["id"];
		max_item = parseInt(last_id.substring(5));
	}
	return max_item;
}
//funcion para elimnar una fila de detalle de cotización
$(document).on("click", ".borrar", function (event) {
	event.preventDefault();
	$(this).closest("tr").remove();
	xtasa = formatoMoneda($("#tasa_cambio").val());
	recorreTable_fac(1, tasa_cambio, '', tabla);
});
//Seleccionar registro de detalle
$("#tblDetalle").on("click", "tr", function () {
	var filaId = $(this).attr("id");
	item = filaId.substring(5);
});
//Dar formato al campo monto al salir del mismo
$(document).on("blur", ".sub-total", function (e) {
	e.preventDefault();
	base = $(`#mon_con${item}`).val();
	base = formatoMoneda(base) * accion;
	$(`#mon_con${item}`).val(format_number_with_dec_new(base, 2));
	$(".caliva").trigger("change");
});
//Calcular IVA y totalizar
$(document).on("change", ".caliva", async function (e) {
	e.preventDefault();
	iva = $(`#iva${item}`).val();

	mon_iva = 0;
	if (iva == "S") {
		fecha_comp = $("#fecha_comp").val();
		const xtasavatTax = await xvatTax(fecha_comp, "IVA");
		xtasaIVA = parseFloat(xtasavatTax[0]["txr1_iva"]);
		mon_iva = parseFloat(base * (xtasaIVA / 100));
	}

	$(`#mon_iva${item}`).val(format_number_with_dec_new(mon_iva, 2));
	$(`#total${item}`).val(format_number_with_dec_new((base + mon_iva), 2));
	//Actualizar Saldos
	tasa_cambio = formatoMoneda($("#tasa_cambio").val());
	recorreTable_fac(1, tasa_cambio, '', tabla);
});
//Eliminar un registro
$("#tblIndexMain").on("click", ".btn-delete-index", function (e) {
	e.preventDefault();
	var table = $("#tblIndexMain").DataTable();
	var row = $(this).closest('tr');
	if (row.hasClass('child')) {
		row.row.prev();
	}
	var data = table.row(row).data();
	var descrip = `¿Está seguro de eliminar la ${data.nom_tdoc} número ${data.num_tdo}?.`
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
			const url = `${base_url}/CXCDocument/destroy`;
			$.ajax({
				url: url,
				type: "POST",
				data: data,
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
						text: `${resulta.msg}`
					}).then((result) => {
						if (result.isConfirmed) {
							var tableMain = $('#tblIndexMain').DataTable();
							tableMain.ajax.reload(null, false);
						}
					});
				},
				error: function (xhr, status, error) {
					loader.hide();
				},
			});
		}
	});
});
//Guardar y/o Actualizarregistro
$("#my_form").on("submit", function (e) {
	e.preventDefault();
	if ($(this).valid()) {
		$('#status').prop('disabled', false);
		status = $("#status").val();
		var formData = $(this).serialize();
		const url = `${base_url}/CXCDocument/store`;
		//Activar status		
		//Ajax para Guardar y/o Actualizar
		$.ajax({
			url: url,
			method: 'POST',
			dataSrc: '',
			data: formData,
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
				// Si todo salió bien y es estatus de aprobación (9), personalizamos la alerta
				if (data.icon != "error" && status == 9) {
					tituloAlert = 'Documento enviado a aprobación';
					textoAlert = 'Su documento ha sido enviado a revisión. Se le notificará cuando sea aprobado.';
					iconoAlert = 'info';
				}
				Swal.fire({
					title: tituloAlert,
					text: textoAlert,
					icon: iconoAlert,
				}).then((result) => {
					if (data.icon != "error") {
						window.location.href = `${base_url}/CXCDocument`;
					}
				})
			},
		});
	} else {
		$('#status').prop('disabled', true);
		return false;
	}
})

async function recorreTable_fac(verstock = 0, tasa_cambio = 1, tipo, tabla = 'tblDetalle') {
	if (!xtasavatTax_val) {
		fecha = $("#fecha_comp").val();
		xtasavatTax = await xvatTax(fecha, "IVA");
		xtasaIVA = parseFloat(xtasavatTax[0]["txr1_iva"]);
		xtasavatTax_val = true;
	}
	subTotal = 0.0;
	iva = 0.0;
	xbase = 0.0;
	xtotal_form = 0.0;
	if (tipo == "C") {
		verstock = 1;
	} else if (tipo == "F" || tipo == "N" || tipo == "NF") {
		verstock = 1;
	}
	//Recorrer tabla dinamica
	$(`#${tabla} tbody tr`).each(function () {
		var xIVA = $(this).find(".input-iva").val();
		var xmonto = 0;
		var xmonto_str = $(this).find(".input-fila").val();
		if (xmonto_str) {
			xmonto = formatoMoneda(xmonto_str);
			if (!isNaN(xmonto)) {
				subTotal += parseFloat(xmonto);
			}
			if (xIVA == "S") {
				xbase += parseFloat(xmonto);
			}
		}
	});
	//Si es en dolares mostrar el contravalor en Bs
	xtasa = tasa_cambio;
	//Calcular el IVA

	if (xbase != 0) {
		iva = parseFloat(xbase * (xtasaIVA / 100));
	}
	xtotal_form = subTotal + iva;
	if (xtasa > 1) {
		$("#sub_total").val(format_number_with_dec_new(subTotal, 2));
		$("#iva").val(format_number_with_dec_new(iva, 2));
		$("#total_frm").val(format_number_with_dec_new(xtotal_form, 2));
		//
		$("#sub_totalBs").val(format_number_with_dec_new(subTotal * xtasa, 2));
		$("#ivaBs").val(format_number_with_dec_new(iva * xtasa, 2));
		$("#total_frmBs").val(
			format_number_with_dec_new(xtotal_form * xtasa, 2)
		);
	} else {
		$("#sub_totall").val(format_number_with_dec_new(subTotal, 2));
		$("#ival").val(format_number_with_dec_new(iva, 2));
		$("#total_frml").val(format_number_with_dec_new(xtotal_form, 2));
	}
}

function getCookie(name) {
	let matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}

$(document).on('change', '#id_afectado', function (e) {	
	if (tasa_cambio != new_tasa_cambioNC && id_moneda != 1) {
		Swal.fire({
			title: "Desea utilizar la Tasa de Cambio del Documento original?",
			icon: "question",
			showDenyButton: true,
			confirmButtonText: "Si",
			denyButtonText: `No`,
		}).then((result) => {
			if (result.isConfirmed) {
				$("#tasa_cambio").val(new_tasa_cambioNC);
				tasa_cambio = new_tasa_cambioNC;
				listar_status(9);
				$("#status").prop('disabled', true).val(9).trigger('change.select2');
			}
		});
	}
})