/*!
 * Funciones CXPDocument
 * Copyright 2025-2025
 * 14-11-2025 creación de Archivo José vargas 15:18:00
*/
//Variables
let item = 0;
let table;
//Al iniciar la aplicación
$().ready(function(){
	//Validaciones
	$("form#my_form").validate({
		rules: {
			id_emp: "required",
			id_tdo: "required",
			id_cli: "required",
			num_tdo: {
				required: function () { 
					return $("#num_tdo").prop("readonly") == false;
				}
			},
			id_moneda: "required",
			fecha_comp: "required",
			fecha_venci: "required",
			num_control: "required",
			descrip_cot: "required",
			item: "required",
			status: "required",
		},
		messages: {
			id_emp: "Debe especificar una empresa",
			id_tdo: "Debe especificar un Tipo de Documento",
			id_cli: "Debe especificar un Cliente",
			num_tdo: "Debe especificar el numero de Documento",
			id_moneda: "Debe especificar una Moneda",
			fecha_comp: "Debe indicar una Fecha para el Documento",
			fecha_venci:
				"Debe especificar una Fecha de Vencimiento para el Documento",
			num_control: "Debe especificar un numero de control",
			descrip_cot: "Debe especificar una descripción para el documento",
			item: "Debe existir al menos un detalle de Documento",
			status: "Debe especificar un status",
		},
	});
	//Cargar el Index
	form = $("form").attr("id");
	if (form === undefined) {
		initCXPDocument();
	} else {
		id = $("#id").val();
		if (id) {
			show_row(id);
		} else {
			dat_form_new();
		}
	}
})
//Mostrar registros
function show_row(id){
	var formData = $(this).serialize();
	const url = `${base_url}/CXPDocument/show_row`;
	//Ajax para 
	$.ajax({
		url: url,
		method: 'POST',
		data: {id: id},
		dataType: 'json',
		beforeSend: function() {
			loader.show();
		},
		complete: function() {
			loader.hide();
		},
		error: function(PDOException) {
			loader.hide();
			console.log('Ha ocurrido el siguiente error:', PDOException.responseText)
		},
		success: function(data) {
			id_emp = data[0]["id_emp"];
			listar_empresas(id_emp,true);
			id_tdo = data[0]["id_tdo"];
			listar_tipos_documentos_cxp(id_emp, "", id_tdo, true, "id_tdo");
			id_cli = data[0]["id_cli"];
			$("#id_cli").val(id_cli);
			$("#id_cli").trigger("change");
			$("#num_tdo").val(data[0]["num_tdo"]);
			$("#num_control").val(data[0]["num_control"]);
			$("#fecha_comp").val(data[0]["fecha_comp"]);
			$("#fecha_venci").val(data[0]["fecha_venci"]);
			id_moneda = data[0]["id_moneda"];
			listar_monedas(id_moneda);
			tasa_cambio = format_number_with_dec_new(data[0]["tasa_cambio"],2);
			$("#tasa_cambio").val(tasa_cambio);
			$("#tasa_cambio").prop("readonly", true);
			show_tasa();
			$("#descrip_cot").val(data[0]["descrip_cot"]);
			$("#id_retiva").empty();
			id_retiva = data[0]["id_retiva"];
			listar_retiva(id_retiva, "id_retiva");
			status = data[0]["status"];
			listar_status(status);
			$.each(data, function(index, row){
				item = last_item_table_cxp() + 1;
				var htmlTags = `
					<tr id="fila-${item}">
						<td class="text-right text-xs" style="width:5%">${item}</td>
						<td><input type="hidden" name="id_con[]" id="id_con${item}" class="text-xs" value="${row.id_concxp}"><div class="input-group"><input type="text" class="form-control text-xs id_con_cxp" id="nom_con${item}" name="nom_con[]" readonly value="${row.nombre_con}"><div class="input-group-append"><span class="input-group-text nom_con_cxp"><a href="#" data-toggle="modal" data-target="#modal-ConcepCXP" title="Buscar y seleccionar Cocneptos"><i class="fas fa-search"></i></a></span></div></div></td>
						<td><input type="hidden" name="id_aux[]" id="id_aux${item}" class="text-xs" value="${row.id_aux ?? ""}"><div class="input-group"><input type="text" class="form-control text-xs id_aux" id="nom_aux${item}" name="nom_aux[]" readonly value="${row.nom_aux ?? ""}"><div class="input-group-append"><span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i id="div_aux${item}" name="div_aux[]" class="fas fa-search"></i></a></span></div></div></td>
						<td><input type="text" name="mon_con[]" id="mon_con${item}" class="form-control text-right text-xs sub-total camponumero input-fila" value="${format_number_with_dec_new(row.monto, 2)}"></td>
						<td><select name="iva[]" id="iva${item}" class="form-control text-xs caliva input-iva"</select></td>
						<td><input type="text" name="mon_iva[]" id="mon_iva${item}" class="form-control text-right text-xs sub-total" readonly value="${format_number_with_dec_new(row.mon_iva, 2)}"></td>
						<td><input type="text" name="total[]" id="total${item}" class="form-control text-right text-xs sub-total" readonly value="${format_number_with_dec_new(row.monto + row.mon_iva, 2)}"></td>
						<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt"></i></button></div></td>
					</tr>`;
				$("#tblCxcDocument").append(htmlTags);
				listar_si_no(row.iva, `iva${item}`);
				$("#item").val(item);
				recorreTable_fac();
			});
			//Vlidar si el documento esta cancelado y/o posee abono
			doc_abo = data[0]["doc_abo"];
			if (doc_abo != 0) {
				$("#btnok").prop("disabled", true);
			}
		},
	});
}
//Registro Nuevo
function dat_form_new(){
	listar_empresas();
	listar_monedas();
	$("#fecha_comp").val(GetTodayDate(0));
	$("#fecha_venci").val(GetTodayDate(0));
	$("#tasa_cambio").prop("readonly", true);
	listar_retiva("", "id_retiva");
	listar_status(1);
}
//Al seleccionar empresa
$("#id_emp").on("change", function(e){
	e.preventDefault();
	id_emp = $(this).val();
	listar_tipos_documentos_CXP(id_emp, "", "", false, "id_tdo");
})
//Al selecionar el tipo de documento, para valdiar si usa consecutivo
$("#id_tdo").on("change", function(e){
	e.preventDefault();
	id_tdo = $(this).val();
	const url = `${base_url}/CXPDocument/val_tdo`;
	//Ajax para Validar el Tipo de Documento
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: {id: id_tdo},
		dataType: 'json',
		beforeSend: function() {
			loader.show();
		},
		complete: function() {
			loader.hide();
		},
		error: function(PDOException) {
			loader.hide();
			console.log('Ha ocurrido el siguiente error:', PDOException.responseText)
		},
		success: function(data) {
			if(data){
				if(data.con_tdoc == 0){
					$("#num_tdo").prop("readonly", false);
				}else{
					$("#num_tdo").prop("readonly", true);
				}
			}
		},
	});
})
//Al seleccionar proveedor
$("#id_cli").on('change', async function(e){
	e.preventDefault();
	id_cli = $(this).val();
	const datosProvee = await tid_vend(id_cli);
	nom_cli = datosProvee["nom_ent"];
	cod_diascre = datosProvee["cod_diascre"];
	$("#fecha_venci").val(GetTodayDate(cod_diascre));
	$("#nom_cli").val(nom_cli);
	//Retención de IVA
	listar_retiva("", "id_retiva");
	id_por_ret_iva = null;
	id_por_ret_iva = datosProvee["id_por_ret_iva"];
	if (id_por_ret_iva) {
		listar_retiva(id_por_ret_iva, "id_retiva");
	}
	$("#num_tdo").trigger("focus");
})
//Al cambiar la fecha de emisión
$("#fecha_comp").on('change', async function(e){
	fecha_comp = $(this).val();
	id_cli = $("#id_cli").val();
	if(id_cli){
		const datosProvee = await tid_vend(id_cli);
		cod_diascre = datosProvee["cod_diascre"] + 1;
		fecha_venci = GetTodayDate(cod_diascre, fecha_comp);
		$("#fecha_venci").val(fecha_venci);
	}
	//Validar cambio
	$("#id_moneda").trigger("change");
})
//AL cambiar la moneda
$("#id_moneda").on("change", async function(e){
	e.preventDefault();
	fecha_comp = $("#fecha_comp").val();
	id_moneda = $(this).val();
	if(id_moneda){
		xTasaCambio = await xTasa(fecha_comp, id_moneda)
		if(xTasaCambio){
			$("#tasa_cambio").val(xTasaCambio);
		}
	}
	show_tasa();
})
//Obtener el ultimo item de la tabla
function last_item_table_cxp() {
	var max_item = 0;
	last_row = $("#tblCxcDocument tr:last");
	if (last_row[0]) {
		id = last_row[0];
		last_id = id["id"];
		max_item = parseInt(last_id.substring(5));
	}
	return max_item;
}
//Agregar un nuevo registro
$("#btnAddRow").on("click", function(e){
	e.preventDefault();
	item = last_item_table_cxp() + 1;
	var htmlTags = `
		<tr id="fila-${item}">
			<td class="text-right text-xs" style="width:5%">${item}</td>
			<td><input type="hidden" name="id_con[]" id="id_con${item}" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs id_con_cxp" id="nom_con${item}" name="nom_con[]" readonly><div class="input-group-append"><span class="input-group-text nom_con_cxp"><a href="#" data-toggle="modal" data-target="#modal-ConcepCXP" title="Buscar y seleccionar Cocneptos"><i class="fas fa-search"></i></a></span></div></div></td>
			<td><input type="hidden" name="id_aux[]" id="id_aux${item}" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs id_aux" id="nom_aux${item}" name="nom_aux[]" readonly><div class="input-group-append"><span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i id="div_aux${item}" name="div_aux[]" class="fas fa-search"></i></a></span></div></div></td>
			<td><input type="text" name="mon_con[]" id="mon_con${item}" class="form-control text-right text-xs sub-total camponumero input-fila"></td>
			<td><select name="iva[]" id="iva${item}" class="form-control text-xs caliva input-iva"</select></td>
			<td><input type="text" name="mon_iva[]" id="mon_iva${item}" class="form-control text-right text-xs sub-total" readonly></td>
			<td><input type="text" name="total[]" id="total${item}" class="form-control text-right text-xs sub-total" readonly></td>
			<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt"></i></button></div></td>
		</tr>`;
	$("#tblCxcDocument").append(htmlTags);
	$("#item").val(item);
	listar_si_no(0, `iva${item}`);
})

//Seleccionar registro de detalle
$("#tblDetalle").on("click", "tr", function () {
	var filaId = $(this).attr("id");
	item = filaId.substring(5);
});
//Dar formato al campo monto al salir del mismo
$(document).on("blur", ".sub-total", function (e) {
	e.preventDefault();
	$(".caliva").trigger("change");
});
//Calcular IVA y totalizar
$(document).on("change", ".caliva", async function (e) {
	e.preventDefault();
	iva = $(`#iva${item}`).val();
	base = $(`#mon_con${item}`).val();
	base = formatoMoneda(base);
	mon_iva = 0;
	if (iva == "S") {
		fecha_comp = $("#fecha_comp").val();
		const xtasavatTax = await xvatTax(fecha_comp, "IVA");
		xtasaIVA = parseFloat(xtasavatTax[0]["txr1_iva"]);
		mon_iva = parseFloat(base * (xtasaIVA/100));
	}
	$(`#mon_iva${item}`).val(format_number_with_dec_new(mon_iva, 2));
	$(`#total${item}`).val(format_number_with_dec_new(base + mon_iva, 2));
	//Actualizar Saldos
	tasa_cambio = formatoMoneda($("#tasa_cambio").val());
	recorreTable_fac(1, tasa_cambio);
});
//Refrescar DataTable del Index
$(".refresh-button").on("click", function () {
	tableIndex.ajax.reload(null, false);
});
//Eliminar un registro
$("#tblIndexMain").on("click", ".btn-delete-index", function () {
	var recordId = $(this).data("id"); // Obtiene el ID del registro
	var recordCode = $(this).data("code"); // Obtine el Tipo Doc
	var recordName = $(this).data("name"); // Obtine el nombre
	var descrip = `¿Está seguro de eliminar el Documento ${recordCode} ${recordName}?.`
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
			const url = `${base_url}/CXPDocument/destroy`;
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
	if ($(this).valid() && item > 0) {
		var formData = $(this).serialize();
		const url = `${base_url}/CXPDocument/store`;
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
			error: function (PDOException) {
				loader.hide();
				console.log(
					"Ha ocurrido el siguiente error:",
					PDOException.responseText
				);
			},
			success: function (data) {
				Swal.fire({
					title: data.title,
					text: data.msg,
					icon: data.icon,
				}).then((result) => {
					if (data.icon != "error") {
						window.location.href = `${base_url}/CXPDocument`;
					}
				});
			},
		});
	} else {
		if(!item){
			Swal.fire({
				title: "Error",
				text: "Debe especificar al menos un item para el documento",
				icon: "error"
			})
		}
		return false;
	}
});
