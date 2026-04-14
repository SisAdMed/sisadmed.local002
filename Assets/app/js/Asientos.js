/*
 * Funciones Asientos
 * Copyright 2025-2025
 * 02-12-2025 Creación de Archivo José Vargas 09:31:00
 */
let item = 0;
//Al iniciar la aplicacción
$().ready(function () {
	//Validaciones
	$("form#my_form").validate({
		ignore: null,
		rules: {
			id_emp: "required",
			id_tipcom: "required",
			num_comp: {
				required: function () {
					var isReadonly = $("#num_comp").prop("readonly");
					return !isReadonly;
				},
			},
			fecha_comp: "required",
			id_moneda: "required",
			tasa_cambio: "required",
			desc_comp: "required",
			status: "required",
			item: {
				required: true,
				min: 1,
			},
			mondebe: {
				required: true,
				minlength: 4,
			},
			monhabe: {
				required: true,
				minlength: 4,
				equalTo: "#mondebe",
			},
		},
		messages: {
			id_emp: "Debe especificar una Empresa",
			id_tipcom: "Debe especificar un Tipo",
			num_comp: "Debe especificar un Número",
			fecha_comp: "Debe especificar una Fecha",
			id_moneda: "Debe especificar una Moneda",
			tasa_cambio: "Debe especificar la Tasa de Cambio",
			desc_comp: "Debe especificar una Descripción",
			status: "Debe especificar un Status",
			item: {
				required: "Debe especificar al menos un Detalle",
				min: "Debe especificar al menos {0} un Detalle",
			},
			mondebe: {
				required: "El Monto del Debe no puede estar vacío o en cero (0,00)",
			},
			monhabe: {
				required: "El Monto del Haber no puede estar vacío o en cero (0,00)",
			}
		},
	});
	//Cargar el index
	form = $("form").attr("id");
	if (form === undefined) {
		initAsientos();
	} else {
		id = $("#id").val();
		if (id) {
			show_row(id);
		} else {
			dat_form_new();
		}
	}
});
//Nuevo registro
function dat_form_new() {
	listar_empresas();
	$("#fecha_comp").val(GetTodayDate(0));
	listar_status(1);
}
//Consultar registro
function show_row(id) {
	const url = `${base_url}/Asientos/show_row`;
	//Ajax para
	$.ajax({
		url: url,
		method: "POST",
		dataSrc: "",
		data: { id: id },
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
			id_emp = data[0]["id_emp"];
			//Validar Fechas Abiertas
			id_emp_config = get_empresa_config(id_emp);
			gfec_ctb = id_emp_config["fec_ctb"];
			gfec_cierre = id_emp_config["fec_ini_fis"];
			listar_empresas(id_emp, true);
			id_tipcom = data[0]["id_tipcom"];
			listar_tipos_comprobantes(id_tipcom, "id_tipcom", true);
			$("#num_comp").val(data[0]["num_comp"]);
			$(`#num_comp`).css("pointer-events", "none");
			fecha_comp_ori = data[0]["fecha_comp"];
			$("#fecha_comp").val(data[0]["fecha_comp"]);
			$("#fecha_comp").trigger("change");
			id_moneda = data[0]["id_moneda"];
			listar_monedas(id_moneda, true);
			$("#tasa_cambio").val(format_number_with_dec_new(data[0]["tasa_cambio"], 2));
			$(`#tasa_cambio`).css("pointer-events", "none");
			$("#desc_comp").val(data[0]["desc_comp"]);
			estatus = data[0]["status"];
			listar_status(estatus, 'status');
			//Cargar detalles de Comprobantres
			item = 0;
			$.each(data, function (index, row) {
				item++;				
				ocultar = (row.id_aux == 0) ? 'hidden' : '';
				var htmlTags = `
				<tr id="fila-${item}">
					<td class="text-right">${item}</td>
					<td><input type="hidden" name="id_ctb[]" id="id_ctb${item}" class="text-xs" value="${row.id_cue}"> <div class="input-group">
					<input type="text" class="form-control text-xs id_ctb c-idcta" id="nom_ctb${item}" name="nom_ctb" value="${row.nom_cue}" readonly required><div class="input-group-append"><span class="input-group-text nom_ctb"><a href="#" data-toggle="modal" data-target="#modal-CuentasCtb" title="Buscar y seleccionar Cuentas Contables"> <i class="fas fa-search text-xs"></i></a></span></div></div></td>
					<td><input type="hidden" name="id_aux[]" id="id_aux${item}" class="text-xs" value="${row.id_aux}"><div class="input-group"><input type="text" class="form-control text-xs id_aux c-idaux" id="nom_aux${item}" name="nom_aux" value="${row.nom_aux}" readonly><div class="input-group-append"><span id="div_aux${item}" class="input-group-text" ${ocultar} ><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i class="fas fa-search text-xs"></i></a></span>
					</div></div></td>
					<td><input type="text" name="descrip_deta[]" id="descrip_deta${item}'" class="form-control text-xs" value="${row.det_observa}"></td>
					<td><select name="tipo[]" id="tipo${item}" class="form-control tipo text-xs" required></select></td>
					<td><input type="text" name="mon_debe[]" id="mon_debe${item}" class="form-control text-right xtotal_debe text-xs camponumero" onkeyup="total_asiento()" value="${format_number_with_dec_new(row.mon_debe,2)}"></td>
					<td><input type="text" name="mon_habe[]" id="mon_habe${item}" class="form-control text-right xtotal_habe text-xs camponumero" onkeyup="total_asiento()"value="${format_number_with_dec_new(row.mon_habe,2)}"></td>
					<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt"></i></div></td>
				</tr>`;
				$("#tblAsientoDet").append(htmlTags);
				$("#item").val(item);
				listar_tipoDH(row.det_tipo, `tipo${item}`);
				$(`#mon_habe${item}`).prop("readonly", true);
				$(`#mon_debe${item}`).prop("readonly", true);
				total_asiento();
			});
		},
	});
}
//Agregar detalle de Comprobante
$("#btnAgregate").on("click", function (e) { 
	e.preventDefault();
	item = last_item_table("tblAsientoDet") + 1;
	var htmlTags = `
		<tr id="fila-${item}">
			<td class="text-right">${item}</td>
			<td><input type="hidden" name="id_ctb[]" id="id_ctb${item}" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs id_ctb c-idcta" id="nom_ctb${item}" name="nom_ctb" readonly required><div class="input-group-append"><span class="input-group-text nom_ctb"><a href="#" data-toggle="modal" data-target="#modal-CuentasCtb" title="Buscar y seleccionar Cuentas Contables"><i class="fas fa-search text-xs"></i></a></span></div></div></td>
			<td><input type="hidden" name="id_aux[]" id="id_aux${item}"><div class="input-group"><input type="text" class="form-control text-xs id_aux" id="nom_aux${item}" name="nom_aux" readonly><div class="input-group-append"><span id="div_aux${item}" class="input-group-text nom_aux "><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i class="fas fa-search text-xs"></i></a></span></div></div></td>
			<td><input type="text" name="descrip_deta[]" id="descrip_deta${item}'" class="form-control text-xs"></td>
			<td><select name="tipo[]" id="tipo${item}" class="form-control tipo text-xs" required></select></td>
			<td><input type="text" name="mon_debe[]" id="mon_debe${item}" class="form-control text-right xtotal_debe text-xs camponumero" onkeyup="total_asiento()"></td>
        	<td><input type="text" name="mon_habe[]" id="mon_habe${item}" class="form-control text-right xtotal_habe text-xs camponumero" onkeyup="total_asiento()"></td>
			<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt"></i></div></td>
		</tr>`;
	$("#tblAsientoDet").append(htmlTags);
	$("#item").val(item);
	listar_tipoDH(0, `tipo${item}`);
	$(`#div_aux${item}`).css("display", "none");
	$(`#mon_habe${item}`).prop("readonly", true);
	$(`#mon_debe${item}`).prop("readonly", true);
	total_asiento();
});
//Al seleccionar un regsitro de detalle de Comprobante
$("body").on("click", "#tblAsientoDet tr", function () {
	selectFile = $(this).attr("id");
	if (selectFile) {
		item = selectFile.substring(5);
	}
});
//funcion para elimnar una fila de detalle de Comprobante
$(document).on("click", ".borrar", function (event) {
	event.preventDefault();
	$(this).closest("tr").remove();
	total_asiento();
});
//Al seleccionar el tipo de Asiento Debe/haber
$(document).on("change", ".tipo", function (e) {
	e.preventDefault();
	let tipo_dh = $("#tipo" + item).val();
	if (tipo_dh == "D") {
		$("#mon_habe" + item).prop("readonly", true);
		$("#mon_debe" + item).prop("readonly", false);
		$("#mon_habe" + item).val("");
		$("#mon_debe" + item).val((xTotHaber - xTotDebe).toFixed(2));
		$("#mon_debe" + item).focus();
		$("#mon_debe" + item).select();
	} else if (tipo_dh == "H") {
		$("#mon_debe" + item).prop("readonly", true);
		$("#mon_habe" + item).prop("readonly", false);
		$("#mon_debe" + item).val("");
		$("#mon_habe" + item).val((xTotDebe - xTotHaber).toFixed(2));
		$("#mon_habe" + item).focus();
		$("#mon_habe" + item).select();
	}
});
//Al cambior elmonto del Debe
$(".xtotal_debe").on("blur", function () {
	total_asiento();
});
//Al cambior elmonto del Haber
$(".xtotal_habe").on("blur", function () {
	total_asiento();
});
//Total Asiento contable
function total_asiento() {
	//
	xTotDebe = 0;
	xTotHaber = 0;
	$(".xtotal_debe").each(function () {
		var monto = $(this).val();
		if (monto.includes(",")) {
			monto = formatoMoneda(monto);
		}
		monto = parseFloat(monto);
		if (!isNaN(monto)) {
			xTotDebe += monto;
		}
	});
	$(".xtotal_habe").each(function () {
		var monto = $(this).val();
		if (monto.includes(",")) {
			monto = formatoMoneda(monto);
		}
		monto = parseFloat(monto);
		if (!isNaN(monto)) {
			xTotHaber += monto;
		}
	});
	$("#mondebe").addClass("text-bold");
	$("#monhabe").addClass("text-bold");
	if (xTotDebe - xTotHaber != 0) {
		$("#mondebe").addClass("text-danger");
		$("#monhabe").addClass("text-danger");
		$(".guardar").prop("disabled", true);
	} else {
		var Xclass = $("#mondebe").hasClass("text-danger");
		if (Xclass) {
			$("#mondebe").removeClass("text-danger");
			$("#monhabe").removeClass("text-danger");
			$(".guardar").prop("disabled", false);
		}
	}
	let for_monto_debe = format_number_with_dec_new(xTotDebe, 2);
	let for_monto_habe = format_number_with_dec_new(xTotHaber, 2);
	$("#mondebe").val(for_monto_debe);
	$("#monhabe").val(for_monto_habe);
}
//Al seleccionar una empresa
$("#id_emp").on("change", async function () {
	id_emp = $("#id_emp").val();
	$("#id_tipcom").empty();
	$("#id_tipcom").attr("readonly", false);
	$("#num_comp").attr("readonly", false);
	if (id_emp) {
		//Validar Fechas Abiertas
		id_emp_config = await get_empresa_config(id_emp);
		gfec_ctb = id_emp_config["fec_ctb"];
		gfec_cierre = id_emp_config["fec_ini_fis"];
		
		//Configuración Móudlo
		const xttipo_cbte = await ttipo_cbte(id_emp, "O", "id_tipcom");
		
		listar_tipos_comprobantes(xttipo_cbte["id_tipcom"], "id_tipcom", true);
		$("#num_comp").attr("readonly", true);
		if (
			xttipo_cbte["consecu_config"] == "N" &&
			xttipo_cbte["numdia_config"] == "N"
		) {
			$("#num_comp").attr("readonly", false);
		}
		//Configuración Empresa
		id_tdo_cfg = await tip_doc_com(id_emp);
		id_moneda = id_tdo_cfg["id_moneda"];
		listar_monedas(id_moneda);
		$("#id_moneda").trigger("change");
	}
});
//Al seleccioanr y/o cambiar Moneda
$("#id_moneda").on("change", async function () {
	id_moneda = $("#id_moneda").val();
	fecha_comp = $("#fecha_comp").val();
	xTasaCambio = await getExchangerate(fecha_comp, id_moneda);
	$("#tasa_cambio").val(xTasaCambio);
	$("#tasa_cambio").attr("readonly", true);
});
//Al cambiar fecha del Asiento
$("#fecha_comp").on("change", function () {
	$("#id_moneda").trigger("change");
	fecha_comp = $("#fecha_comp").val();	
	const xfecha = GetTodayDate(1, gfec_ctb);
	fecha = xfecha.split("-");
	// Formatear día, mes y año con ceros a la izquierda
	var dia = fecha[2];
	var mes = fecha[1];
	var anio = fecha[0];

	// Formato dd/mm/yyyy
	var fechaFormateada = dia + '-' + mes + '-' + anio;
	$(".guardar").prop("disabled", false);
	if (fecha_comp < gfec_cierre) {
		Swal.fire({
			icon: "warning",
			title: "Fecha no permitida",
			text: `La fecha del asiento no puede ser menor a la fecha de cierre contable ${fechaFormateada}`,
		});
		$(".guardar").prop("disabled", true);
		if (id){
			$("#fecha_comp").val(fecha_comp_ori);
		} else {
			$("#fecha_comp").val(GetTodayDate(0));
		}
	}
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
	var descrip = `¿Está seguro de eliminar el ${recordName} Número ${recordCode} ?.`;
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
			const url = `${base_url}/Asientos/destroy`;
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
//Guardar y/o Actualizarregistro
$("#my_form").on("submit", function (e) {
	e.preventDefault();
	if ($(this).valid()) {
		var formData = $(this).serialize();
		const url = `${base_url}/Asientos/store`;
		//Ajax para Guardar y/o Actualizar
		$.ajax({
			url: url,
			method: "POST",
			dataSrc: "",
			data: formData,
			dataType: "json",
			beforeSend: function () {
				loader.show();
			},
			complete: function () {
				loader.hide();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				loader.hide();
				console.error("Error en la solicitud AJAX:");
        		console.error("Estado de texto:", textStatus); // Por ejemplo: "timeout", "error", "Not Found", "Internal Server Error"
        		console.error("Error lanzado:", errorThrown); // Mensaje de error detallado del servidor
        		console.error("Código de estado HTTP:", jqXHR.status); // Código numérico (ej: 404, 500)
        		console.error("Respuesta del servidor:", jqXHR.responseText); // Contenido de la respuesta del servidor
			},
			success: function (data) {
			console.log(data);			
				Swal.fire({
					title: data.title,
					text: data.msg,
					icon: data.icon,
				}).then((result) => {
					if (data.icon != "error") {
						window.location.href = `${base_url}/Asientos`;
					}
				});
			},
		});
	} else {
		return false;
	}
});
