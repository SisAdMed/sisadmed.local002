//Variables
let item = 0;
let item_old = 0;
table;
let rows_selected = [];
especial_contrib = "0";
let id_bancon_RETIVA;
let nom_bancon_RETIVA;
let id_bancon_efec;
let nom_bancon_efec;

let xtasa_for;
let tabla_name = "";

let table1 = "";
let id_moneda_mov;
TMod = "B";

//Validar el comprobante de retencion, tanto numero como asignar el monto de la retencion de forma automatica
$(document).ready(function () {
	//Iniciar Index
	const url = `${base_url}/BanMovim/cargar_screen_main`;
	tableIndex = $("#tblTablebanMovim").DataTable({
		destroy: true,
		responsive: true,
		ajax: {
			url: url,
			method: "POST",
			dataSrc: "",
			data: {},
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			complete: function () {
				$(".loader").hide();
			},
			error: function (xhr, status, error) {
				$(".loader").hide();
			},
		},
		columns: [
			{
				data: "id_banmov",
				title: "Id",
				className: "text-right",
				visible: false,
			},
			{ data: "nombre_emp", title: "Empresa" },
			{ data: "nom_bantmo", title: "Tipo Mov." },
			{ data: "bancue", title: "Cuenta" },
			{
				data: "num_banmov",
				title: "Número",
				className: "text-right",
			},
			//{ data: "cont", title: "Cont" },
			{
				data: "fecha_comp",
				title: "Fecha",
				render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
			},
			{
				data: "id_moneda",
				title: "Moneda",
				className: "text-center",
			},
			{
				data: null,
				title: "Status",
				render: function (data, type) {
					if (data.status == 1) {
						return `<td class="text-center"><span class="badge badge-success">Activo</span><td>`;
					} else {
						return `<td class="text-center"><span class="badge badge-danger">Inactivo</span><td>`;
					}
				},
			},
			{
				data: null,
				title: "Acciones",
				className: "text-center",
				render: function (data, type) {
					return (
						`<td><a type="button" class="btn btn-warning btn-xs" href="${base_url}/BanMovim/edit/${data.id_banmov}"><i class="fa fa-edit"></i></a><td>` +
						`     <td><button id="Data" data-id="${data.id_banmov}" data-name="${data.nombre_emp}" data-code = "${data.nombre_emp}" type="button" class="btn btn-danger btn-xs btn-delete" ><i class="fa fa-trash"></i></button></td>` +
						`     <td><button id="Data" data-id="${data.id_banmov}" data-code="${data.num_banmov}" type="button" class="btn btn-primary btn-xs" onclick="print_mov(this)"><i class="fa fa-print" title="Imprimir"></i></button></td>`
					);
				},
			},
		],
		language: {
			url: `${base_url}/Assets/json/es-ES.json`,
		},
	});

	//Validacion de Formulario
	jQuery.validator.setDefaults({
		ignore: [],
	});
	$("form[name='my_form']").validate({
		rules: {
			id_emp: "required",
			id_bantmo: "required",
			id_bancue: "required",
			fecha_comp: "required",
			num_banmov: "required",
			id_moneda: "required",
			des_banmov: "required",
			item: "required",
		},
		messages: {
			id_emp: "Debe especificar una Empresa",
			id_bantmo: "Debe especificar uan Tipo de Movimiento",
			id_bancue: "Debe especificar una Cuenta Bancaria",
			fecha_comp: "Debe indicar una Fecha",
			num_banmov: "Debe indicar un Número de Movimiento",
			id_moneda: "Debe indicar un tipo de Moneda",
			des_banmov: "Debe especificar una descripción",
			item: "Debe especificar al menos un concepto",
		},
	});
	//Cargar formularios
	$(".benef_banmov").hide();
	//Formulario y registro
	form = $("form").attr("id");
	id = $("#id").val();
	//Ver y/o modificar
	//Ocultar Tabs
	hide_show_tabs("0");
	if (id) {
		dat_form(id);
	} else if (form != undefined) {
		//Nuevo registro
		dat_form_new();
	}
});
//Consultar registro
function dat_form(id) {
	var url = `${base_url}/BanMovim/show_row`;
	$.ajax({
		url: url,
		method: "POST",
		dataSrc: "",
		data: { id: id },
		beforeSend: function () {
			$(".loader").show();
		},
		success: async function (response) {
			data = JSON.parse(response);
			id_emp = data[0]["id_emp"];
			//Validar Fechas de Contabilidad
			id_emp_cfg = get_empresa_config(id_emp);
			fec_ctb_ban = id_emp_cfg["fec_ban"];
			id_tdo_cfg = await tip_doc_com(id_emp); 			
			id_moneda_cia = id_tdo_cfg["id_moneda"];
			id_bantmo = data[0]["id_bantmo"];
			efecto = data[0]["efe_bantmo"];
			$("#efecto").val(efecto);
			accion = data[0]["acc_bantmo"];
			id_bancue = data[0]["id_bancue"];
			benef_banmov = data[0]["benef_banmov"];
			listar_empresas(id_emp, true);
			listar_tipomov_bancos(id_bantmo, false);
			listar_cuentas_ban(id_emp, id_bancue, true);
			fecha_comp = data[0]["fecha_comp"];
			$("#fecha_comp").val(fecha_comp);
			//$("#fecha_comp").css("pointer-events", "none");
			$("#num_banmov").val(data[0]["num_banmov"]);
			//$("#num_banmov").css("pointer-events", "none");
			$("#benef_banmov").val(benef_banmov);
			id_moneda = data[0]["id_moneda"];
			id_moneda_mov = id_moneda;
			listar_monedas(id_moneda);
			//$("#id_moneda").css("pointer-events", "none");
			tasa_cambio = format_number_with_dec_new(data[0]["tasa_cambio"], 8);
			$("#tasa_cambio").val(tasa_cambio);
			//$("#tasa_cambio").css("pointer-events", "none");
			$("#des_banmov").val(decodeHTMLEntities(data[0]["des_banmov"]));
			status = data[0]["status"];
			listar_status(status);
			if (accion == "D") {
				$(".benef_banmov").show();
			} else {
				$(".benef_banmov").hide();
			}

			item = 0;
			var mon_mov = 0;
			$.each(data, function (i, xitem) {
				item++;
				id_aux = xitem.id_aux;
				var htmlTags = `
					'<tr id="fila-${item}"> 
						<td>${item}</td>
						<td><input type="hidden" name="id_bancon[]" id="id_bancon${item}" class="text-xs id_bancon" value="${xitem.id_bancon}"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_con${item}'" name="nom_con[]" readonly value="${xitem.nom_bancon}"><div class="input-group-append"><span class="input-group-text nom_con"><a href="#" data-toggle="modal" data-target="#modal-BanConceptos" title="Buscar y seleccionar Conceptos Bancarios"><i class="fas fa-search"></i></a></span></div></div></td>
						<td><input type="hidden" name="id_aux[]" id="id_aux${item}'" class="text-xs" value="${xitem.id_aux}"><div class="input-group"><input type="text" class="form-control text-xs id_aux" id="nom_aux${item}" name="nom_aux[]" readonly value="${xitem.nom_aux}"><div class="input-group-append"  id="div_aux"><span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i class="fas fa-search"></i></a></span></div></div></td>
						<td><input type="text" name="monto[]" id="monto${item}" class="form-control text-right text-xs monto camponumero tot_movim" value="${format_number_with_dec_new(xitem.monto_nac, 2)}"></td> +
						<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt text-xs"></i></div></td>
					</tr>`;
				$("#tbl_banmovin").append(htmlTags);
				if (!isEmpty(id_aux)) {
					$(".id_aux").trigger("change");
				}
				$("#item").val(item);
				//Total Movimiento
				mon_mov += xitem.monto_nac;
			});
			str_mon_mov = format_number_with_dec_new(mon_mov, 2);

			$("#tbl_banmovin tfoot").remove();
			htmlTags = `<tfoot >
				<th></th>
				<th></th>
				<th class="text-right">Total:</th>
				<th><input type="text" class="form-control text-right text-xs" id="tmon_mov" name="tmon_mov" readonly /> </th>>
				<th><center><button type="button" id="btn_agregate" class="btn btn-primary text-xs" title="Agregar detalle">Agregar</button></center></th>
			</tfoot>`;

			$("#tbl_banmovin").append(htmlTags);

			var miTabla = $("#tbl_banmovin").DataTable;	
			

			//Efecto
			efe_bantmo = data[0]["efe_bantmo"];
			hide_show_tabs(efe_bantmo);
			//Cargas detalle de documentos
			id_ent = data[0]["id_ent"];
			if (id_ent) {
				if (efe_bantmo == "C") {
					$("#id_cli").val(id_ent);
					$("#id_cli").trigger("change");
					show_doc_cli(id, 1);
				} else {
					$("#id_ent").val(id_ent);
					$("#id_ent").trigger("change");
					show_doc_cli(id, 2);
				}
			}
			UpdateDataTable("#tbl_banmovin");
			//Validar que la fecha del Movimiento no sea menor a la fecha de contabilidad de la empresa
			if (fecha_comp <= fec_ctb_ban) {
				$("#btnok").prop("disabled", true);
			}
		},
		complete: function () {
			$(".loader").hide();
		},
		error: function () {
			$(".loader").hide();
		},
	});
	//
	
}
//Mostrar documentos del registro a consultar y/o Modificar
function show_doc_cli(id, ori) {
	
	var url = "";
	if (ori == 1) {
		url = `${base_url}/CXCMovement/show_row_det`;
		table_det = "#tblSeatDetail";
	} else {
		url = `${base_url}/CXPMovement/show_row_det`;  
		table_det = "#tblSeatDetail_cxp";
	}
	$.ajax({
		url: url,
		method: "POST",
		data: { id: id },
		dataSrc: "",
		dataType: "json",
		beforeSend: function () {
			loader.show();
		},
		complete: function () {
			loader.hide();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			loader.hide();
			console.error(
				"Error en la solicitud AJAX:",
				textStatus,
				errorThrown
			);
		},
		success: function (det_doc) {
			$(table_det).empty();
			if (efe_bantmo == "C") {
				table_det_doc = $(table_det).DataTable({
					destroy: true,
					data: det_doc,
					responsive: true,
					processing: true,
					paging: false,
					fnCreatedRow: function (row, data, dataIndex) {
						let rowId = `fila${data.item}`;
						item_doc = data.item;
						$(row).attr("id", rowId);
					},
					columns: [
						{
							data: null,
							title: "Id",
							className: "text-right",
							render: function (data, type) {
								return `<input type="text" id="id_cot${data.item}" name="id_cot[]" class="form-control text-xs text-right rid_cot" value="${data.id_cot}" readonly />`;
							},
						},
						{ data: "item", title: "item", className: "text-right", },
						{ data: "tipo_codigo", title: "Tipo", className: "text-center", },
						{ data: "nom_tdoc", title: "Descripción" },
						{ data: "num_tdo", title: "Número", className: "text-right", },
						{ data: "fecha_emi", title: "Fec. Emis.", className: "text-center",
							render: $.fn.dataTable.render.moment(
								FROM_PATTERN,
								TO_PATTERN
							),
						},
						{ data: "fecha_venci", title: "Fec. Venc.", className: "text-center",
							render: $.fn.dataTable.render.moment(
								FROM_PATTERN,
								TO_PATTERN
							),
						}, 
						{ data: null, title: "Moneda", className: "text-center", with: "15px",
							render: function (data, type) {
								return `<input type="text" id="id_moneda_doc${data.item}" name="id_moneda_doc[]" class="form-control text-xs text-center" value="${data.codigo_moneda}" readonly />`;
							},
						},
						{ data: "tasa_cambio", title: "Tasa", className: "text-right",
							render: DataTable.render.number(".", ",", 4),
						},
						{ data: null, title: "Monto", className: "text-right",
							render: function (data, type) {
								var xmonto = data.mon_doc_dom;
								id_moneda = $("#id_moneda").val();
								if (id_moneda != id_moneda_cia) {
									xmonto = data.mon_doc_for;
								}
								return `${format_number_with_dec_new(
									xmonto,
									2
								)}`;
							},
						},
						{ data: null, title: "Saldo", className: "text-right", render: function (data, type) {
								var xmonto = data.sal_doc_dom;
								var xmon_can = data.mont_doc_dom;
								id_moneda = $("#id_moneda").val();
								if (id_moneda != id_moneda_cia) {
									xmonto = parseFloat(data.sal_doc_for);
									xmon_can = parseFloat(data.mont_doc_for);
								}
								xmonto += xmon_can;
								return `<input type='text' id='sal_doc${
									data.item
								}' name='sal_doc[]' class='form-control text-right sal_doc text-xs fila' value='${format_number_with_dec_new(
									xmonto,
									2
								)}' readonly/>`;
							},
						},
						{ data: null, title: "Cancelar", className: "text-right",
							render: function (data, type) {
								var xmonto = parseFloat(data.mont_doc_dom);
								id_moneda = $("#id_moneda").val();
								if (id_moneda != id_moneda_cia) {
									xmonto = parseFloat(data.mont_doc_for);
								}
								return `<input type='text' id='mon_can${
									data.item
								}' name='mon_can[]' class='form-control text-right fila-input text-xs' value='${format_number_with_dec_new(
									xmonto,
									2
								)}'/>`;
							},
						},
						{ data: null, title: "Ret IVA", className: "text-right",
							render: function (data, type) {
								var xmonto = parseFloat(data.mon_ret_dom);
								id_moneda = $("#id_moneda").val();
								if (id_moneda != id_moneda_cia) {
									xmonto = parseFloat(data.mon_ret_for);
								}
								return `<input type='text' id='mon_ret${
									data.item
								}' name='mon_ret[]' class='form-control text-right fila-input-ret text-xs' value='${format_number_with_dec_new(
									xmonto,
									2
								)}'/>`;
							},
						},
						{ data: null, title: "Comprobante", className: "text-right",
							render: function (data, type) {
								return `<input type='number' id='num_ret${data.item}' name='num_ret[]' class='form-control text-right fila-input-num text-xs' value='${data.num_ret}'/>`;
							},
						},
						{ data: null, title: "Acción", className: "text-center",
							render: function (data, type) {
								return `     <input type="checkbox" name="id_check[]" class="form-check-input check-row text-xs" data-input-id="mon_can${item}" data-input-ret="mon_ret${item}" data-input-num="num_ret${item}" checked/> <button type="button" class="btn btn-danger btn-xs borrar_doc" title="Eliminar item"><i class="fa fa-trash"></i></button>`;
							},
						},
					],					
					fnFooterCallback: function (
						row,
						data,
						start,
						end,
						display
					) {
						var api = this.api();
						var footer = $(this).append("<tfoot><tr></tr></tfoot>");
						$(footer).append(
							'<th colspan="11" class="text-right">Total:</th>'
						);
						var xmon_can = 0;
						var xmon_ret = 0;
						id_moneda = $("#id_moneda").val();
						$.each(data, function (x, value) {
							var tmon_can = parseFloat(value.mont_doc_dom);
							var tmon_ret = parseFloat(value.mon_ret_dom);
							if (id_moneda != id_moneda_cia) {
								tmon_can = parseFloat(value.mont_doc_for);
								tmon_ret = parseFloat(value.mon_ret_for);
							}

							xmon_can += tmon_can;
							xmon_ret += tmon_ret;
						});
						$(footer).append(
							`<th class="text-right"><input type="text" id="tot_mon_can" class="form-control text-right text-xs" value="${format_number_with_dec_new(
								xmon_can,
								2
							)}" readonly /></th>`
						);
						$(footer).append(
							`<th class="text-right"><input type="text" id="tot_mon_ret" class="form-control text-right text-xs" value="${format_number_with_dec_new(
								xmon_ret,
								2
							)}" readonly /></th>`
						);
						$(footer).append(`<th colspan="2"></th>`);
					},
					language: {
						url: `${base_url}/Assets/json/es-ES.json`,
					},
				});
			} else {
				table_det_doc = $(table_det).DataTable({
					destroy: true,
					data: det_doc,
					responsive: true,
					processing: true,
					paging: false,
					fnCreatedRow: function (row, data, dataIndex) {
						let rowId = `fila${data.item}`;
						item_doc = data.item;
						$(row).attr("id", rowId);
					},
					columns: [
						{
							data: null,
							title: "Id",
							className: "text-right",
							render: function (data, type) {
								return `<input type="text" id="id_cot${data.item}" name="id_cot[]" class="form-control text-xs text-right" value="${data.id_cot}" readonly />`;
							},
						},
						{
							data: "item",
							title: "item",
							className: "text-right",
						},
						{
							data: "tipo_codigo",
							title: "Tipo",
							className: "text-center",
						},
						{ data: "nom_tdoc", title: "Descripción" },
						{
							data: "num_tdo",
							title: "Número",
							className: "text-right",
						},
						{
							data: "fecha_emi",
							title: "Fec. Emis.",
							className: "text-center",
							render: $.fn.dataTable.render.moment(
								FROM_PATTERN,
								TO_PATTERN
							),
						},
						{
							data: "fecha_venci",
							title: "Fec. Venc.",
							className: "text-center",
							render: $.fn.dataTable.render.moment(
								FROM_PATTERN,
								TO_PATTERN
							),
						},
						{
							data: null,
							title: "Moneda",
							className: "text-center",
							with: "15px",
							render: function (data, type) {
								return `<input type="text" id="id_moneda_doc${data.item}" name="id_moneda_doc[]" class="form-control text-xs text-center" value="${data.codigo_moneda}" readonly />`;
							},
						},
						{
							data: "tasa_cambio",
							title: "Tasa",
							className: "text-right",
							render: DataTable.render.number(".", ",", 2),
						},
						{
							data: null,
							title: "Monto",
							className: "text-right",
							render: function (data, type) {
								id_moneda = $("#id_moneda").val();
								var xmonto = data.mon_doc_dom;
								if (id_moneda != id_moneda_cia) {
									xmonto = data.mon_doc_for;
								}
								return `<input type='text' id='mon_doc${
									data.item
								}' name='mon_doc[]' class='form-control text-right sal_doc text-xs fila' value='${format_number_with_dec_new(
									xmonto,
									2
								)}' readonly/>`;
							},
						},
						{
							data: null,
							title: "Saldo",
							className: "text-right",
							render: function (data, type) {
								var xmonto = parseFloat(data.sal_doc_dom) + parseFloat(data.mon_can_dom);
								if (id_moneda != id_moneda_cia) {
									xmonto = parseFloat(data.sal_doc_for) + parseFloat(data.mon_can_for);
								}
								return `<input type='text' id='sal_doc${data.item}' name='sal_doc[]' class='form-control text-right sal_doc text-xs fila' value='${format_number_with_dec_new(xmonto,2)}' readonly/>`;
							},
						},
						{
							data: null,
							title: "Cancelar",
							className: "text-right",
							render: function (data, type) {
								var xmonto = data.mon_can_dom;
								if (id_moneda != id_moneda_cia) {
									xmonto = data.mon_can_for;
								}
								return `<input type='text' id='mon_can${
									data.item
								}' name='mon_can[]' class='form-control text-right fila-input text-xs' value='${format_number_with_dec_new(
									xmonto,
									2
								)}'/>`;
							},
						},
						{
							data: null,
							title: "Acción",
							className: "text-center",
							render: function (data, type) {
								return `     <input type="checkbox" name="id_check[]" class="form-check-input check-row text-xs" data-input-id="mon_can${item}" data-input-ret="mon_ret${item}" data-input-num="num_ret${item}" checked/> <button type="button" class="btn btn-danger btn-xs borrar_doc" title="Eliminar item"><i class="fa fa-trash"></i></button>`;
							},
						},
					],
					fnFooterCallback: function (
						row,
						data,
						start,
						end,
						display
					) {
						var api = this.api();
						var footer = $(this).append("<tfoot><tr></tr></tfoot>");
						$(footer).append(
							'<th colspan="11" class="text-right">Total:</th>'
						);
						var xmon_can = 0;
						id_moneda = $("#id_moneda").val();
						$.each(data, function (x, value) {
							var tmon_can = value.mon_can_dom;
							if (id_moneda != id_moneda_cia) {
								tmon_can = value.mon_can_for;
							}
							xmon_can += tmon_can;
						});
						$(footer).append(
							`<th class="text-right"><input type="text" id="tot_mon_can" class="form-control text-right text-xs" value="${format_number_with_dec_new(
								xmon_can,
								2
							)}" readonly /></th>`
						);
						$(footer).append(`<th colspan="2"></th>`);
					},
					language: {
						url: `${base_url}/Assets/json/es-ES.json`,
					},
				});
			}

			UpdateDataTableMovBan();
		},
	});
}
//al actualziar la variable monto
$(document).on("change", ".monto", function () {
	totalizar_tabla_mov();
});
function dat_form_new() {
	listar_empresas(0);
	$("#fecha_comp").val(GetTodayDate(0));
	listar_monedas();
	listar_status(1);
}
//Al seleccionar empresa
$(document).on("change", "#id_emp", async function (e) {
	id_emp = $(this).val();
	listar_tipomov_bancos();
	listar_cuentas_ban(id_emp);
	id_tdo_cfg = await tip_doc_com(id_emp);
	id_moneda_cia = id_tdo_cfg["id_moneda"];
});
// al seleccioanr fecha y/p moneda 
$(document).on("change", "#fecha_comp", function (e) {
	id_emp = $("#id_emp").val();	
	//Validar Fechas de Contabilidad
	id_emp_cfg = get_empresa_config(id_emp);
	fec_ctb_ban = id_emp_cfg["fec_ban"];
	fecha_comp = $(this).val();
	if (fecha_comp <= fec_ctb_ban) {
		$("#fecha_comp").addClass("is-invalid");
		$("#fecha_comp").attr("title", "La fecha del movimiento no puede ser menor a la fecha de contabilidad de la empresa");
		$("#btnok").prop("disabled", true);
	} else {
		$("#fecha_comp").removeClass("is-invalid");
		id_moneda = $("#id_moneda").val();
		$("#tasa_cambio").val("");
		if (id_moneda && fecha_comp) {
			obtener_cambio(fecha_comp, id_moneda);
		}
		$("#btnok").prop("disabled", false);
	}	
});
$(document).on("change", "#id_moneda", function (e) {
	fecha_comp = $("#fecha_comp").val();
	id_moneda = $(this).val();
	$("#tasa_cambio").val("");
	if (id_moneda && fecha_comp) {
		obtener_cambio(fecha_comp, id_moneda);
	}
});
async function obtener_cambio(fecha_comp, id_moneda) {
	xtasa = await getExchangerate(fecha_comp, id_moneda);
	$("#tasa_cambio").val(xtasa);
	if ((id_moneda = id_moneda_cia)) {
		xtasa_for = await getExchangerate(fecha_comp, 2);
	}
}
$(document).on("change", ".id_bancon", async function (e) {
	$("id_bancon" + item).val(id_bancon);
	var nombre = await nom_con_ban(id_bancon);
	if (nombre) {
		nombre_con = nombre["cod_bancon"] + " - " + nombre["nom_bancon"];
		$("#nom_con" + item).val(nombre_con);
		aux_cta = nombre["aux_cta"];
		if (aux_cta == "N") {
			$("#div_aux").css("display", "none");
		}
	}
});
//Validar el efecto del Tipo de Movimiento
$(document).on("change", "#id_bantmo", function (e) {
	id_bantmo = $(this).val();
	id_emp = $("#id_emp").val();
	const url = `${base_url}/BanTipoMov/showrow`;
	hide_show_tabs(0);
	$.ajax({
		url: url,
		type: "POST",
		data: { id: id_bantmo, id_emp: id_emp },
		dataType: "json",
		success: function (resultado) {
			if (!id) {
				$("#tbl_banmovin_det").empty();
				item = 0;
			}
			acc_bantmo = resultado[0]["acc_bantmo"];
			efe_bantmo = resultado[0]["efe_bantmo"];
			if (efe_bantmo) {
				//Efecto y Movimiento de CXC y/o CXP
				efecto = efe_bantmo;
				$("#efecto").val(efe_bantmo);
				$("#cxtmo").val(resultado[0]["id_cxtmo"]);
				hide_show_tabs(efe_bantmo);
				id_bancon_efec = resultado[0]["id_bancon"];
				nom_bancon_efec = resultado[0]["nom_bancon"];
				agregarDetalle(efe_bantmo, id_bancon_efec, nom_bancon_efec);
				id_bancon_RETIVA = resultado[0]["id_bancon_RETIVA"];
				nom_bancon_RETIVA = resultado[0]["nom_bancon_RETIVA"];
			}
			if (acc_bantmo == "D") {
				$(".benef_banmov").show();
			} else {
				$(".benef_banmov").hide();
			}
		},
		failed: function (error) {
			console.log(error);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.log(xhr.responseText);
		},
	});
});
$(document).on("click", "#btn_agregate", function (event) {
	event.preventDefault();
	agregarDetalle();
});
//Agregar detalles al Movimiento Bancario
function agregarDetalle(ori = 0, id = "", nom = "") {
	id_bancon = "";
	if (item_old != 0) {
		item = item_old;
		item_old = 0;
	}
	item++;
	$("#item").val(item);
	if (ori == "C" || ori == "P") {
		id_bancon = id_bancon_efec;
		var htmlTags =
			'<tr id="fila-' +
			item +
			'">' +
			"<td>" +
			item +
			"</td>" +
			'<td><input type="hidden" name="id_bancon[]" id="id_bancon' +
			item +
			'" class="text-xs id_bancon" value="' +
			id_bancon_efec +
			'"><div class="input-group"><input type="text" class="form-control text-xs id_bancon" id="nom_con' +
			item +
			'" name="nom_con[]" value="' +
			nom_bancon_efec +
			'" readonly ><div class="input-group-append"><span class="input-group-text nom_con"><a href="#" data-toggle="modal" data-target="#modal-BanConceptos" title="Buscar y seleccionar Conceptos Bancarios"><i class="fas fa-search"></i></a></span></div></div></td>' +
			'<td><input type="hidden" name="id_aux[]" id="id_aux' +
			item +
			'" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs id_aux" id="nom_aux' +
			item +
			'" name="nom_aux[]" readonly><div class="input-group-append"  id="div_aux"><span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i class="fas fa-search"></i></a></span></div></div></td>' +
			'<td><input type="text" name="monto[]" id="monto' +
			item +
			'" class="form-control text-right text-xs camponumero tot_movim" value="" onkeyup="format(this)"></td>' +
			'<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt text-xs"></i></div></td>' +
			"</tr>";
	} else if (ori == "R") {
		id_bancon = id_bancon_RETIVA;
		var htmlTags =
			'<tr id="fila-' +
			item +
			'">' +
			"<td>" +
			item +
			"</td>" +
			'<td><input type="hidden" name="id_bancon[]" id="id_bancon' +
			item +
			'" class="text-xs id_bancon" value="' +
			id_bancon_RETIVA +
			'"><div class="input-group"><input type="text" class="form-control text-xs id_bancon" id="nom_con' +
			item +
			'" name="nom_con[]" value="' +
			nom_bancon_RETIVA +
			'" readonly ><div class="input-group-append"><span class="input-group-text nom_con"><a href="#" data-toggle="modal" data-target="#modal-BanConceptos" title="Buscar y seleccionar Conceptos Bancarios"><i class="fas fa-search"></i></a></span></div></div></td>' +
			'<td><input type="hidden" name="id_aux[]" id="id_aux' +
			item +
			'" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs id_aux" id="nom_aux' +
			item +
			'" name="nom_aux[]" readonly><div class="input-group-append"  id="div_aux"><span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i class="fas fa-search"></i></a></span></div></div></td>' +
			'<td><input type="text" name="monto[]" id="monto' +
			item +
			'" class="form-control text-right text-xs camponumero tot_movim" value=""></td>' +
			'<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt text-xs"></i></div></td>' +
			"</tr>";
	} else {
		var htmlTags =
			'<tr id="fila-' +
			item +
			'">' +
			"<td>" +
			item +
			"</td>" +
			'<td><input type="hidden" name="id_bancon[]" id="id_bancon' +
			item +
			'" class="text-xs id_bancon" ><div class="input-group"><input type="text" class="form-control text-xs id_bancon" id="nom_con' +
			item +
			'" name="nom_con[]"  readonly ><div class="input-group-append"><span class="input-group-text nom_con"><a href="#" data-toggle="modal" data-target="#modal-BanConceptos" title="Buscar y seleccionar Conceptos Bancarios"><i class="fas fa-search"></i></a></span></div></div></td>' +
			'<td><input type="hidden" name="id_aux[]" id="id_aux' +
			item +
			'" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs id_aux" id="nom_aux' +
			item +
			'" name="nom_aux[]" readonly><div class="input-group-append"  id="div_aux"><span class="input-group-text nom_aux"><a href="#" data-toggle="modal" data-target="#modal-AuxiliaresCtb" title="Buscar y seleccionar Auxiliares Contables"><i class="fas fa-search"></i></a></span></div></div></td>' +
			'<td><input type="text" name="monto[]" id="monto' +
			item +
			'" class="form-control text-right text-xs camponumero tot_movim" value=""></td>' +
			'<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt text-xs"></i></div></td>' +
			"</tr>";
	}
	$("#tbl_banmovin").append(htmlTags);
	//Inicializar autonumerico despues de agregar el nuevo campo
	//new AutoNumeric(`#monto${item}`, AutoConfig);
	if (id_bancon) {
		$(".id_bancon").trigger("change");
	}
	id_bancon = "";
}
function hide_show_tabs(mode = "0") {
	if (mode == "0") {
		$("#customer-bancxc-tab").hide(); //Ocultar todo el contenido
		$("#customer-bancxp-tab").hide(); //Ocultar todo el contenido
	} else if (mode == "C") {
		$("#customer-bancxc-tab").show(); //Mostrar todo el contenido
		$("#customer-bancxp-tab").hide(); //Ocultar todo el contenido
	} else if (mode == "P") {
		$("#customer-bancxp-tab").show(); //Mostrar todo el contenido
		$("#customer-bancxc-tab").hide(); //Ocultar todo el contenido
	}
}
//Seleccioanr por defecto el vendedor al momento de escoger el cliente
$(document).on("change", "#id_cli", async function (event) {
	id_cli = $(this).val();
	if (id_cli) {
		fecha_comp = $("#fecha_comp").val();
		id_moneda = $("#id_moneda").val();
		const datosFetched = await tid_vend(id_cli);
		especial_contrib = datosFetched["contr_esp"];
		nom_cli = datosFetched["nom_ent"];
		$("#nom_cli").val(nom_cli);
		//Validar si se muestran los documentos pendientes
		id_config_cxc = await show_config_cxc(id_emp);
		show_doc = id_config_cxc["show_doc"];
		$("#tbody").empty();
	}
});
//Seleccioanr por defecto el vendedor al momento de escoger el proveedor
$(document).on("change", "#id_ent", async function (event) {
	id_ent = $(this).val();
	if (id_ent) {
		fecha_comp = $("#fecha_comp").val();
		id_moneda = $("#id_moneda").val();
		const datosFetched = await tid_vend(id_ent); 
		nom_cli = decodeHTMLEntities(datosFetched["nom_ent"]);
		$("#nom_ent").val(nom_cli);
		//Validar si se muestran los documentos pendientes
		id_config_cxp = await show_config_cxp(id_emp);
		show_doc = id_config_cxp["show_doc"];
		$("#tbody").empty();
		if (show_doc == "S" && id) {
			//Mostra los documentos cancelados
		} else if (show_doc == "S") {
			//Mostra los documentos pendientes del cliente
			//show_doc_cli(id_ent, "P");
			totalizar_tabla_mov();
		}
	}
});
//Imprimir Mov. bancarios
function print_mov(e) {
	let num_cot = e.dataset.code;
	let id_code = e.dataset.id;
	Swal.fire({
		icon: "question",
		title:
			"¿Está seguro que desea imprimir el Movimiento Bancario " +
			num_cot +
			"?",
		showCancelButton: true,
		confirmButtonText: "Imprimir",
	}).then((result) => {
		if (result.isConfirmed) {
			window.open(
				`${base_url}/BanMovim/print_movement/` + id_code,
				"_blank"
			);
		}
	});
}
//Nuevo detalle de documentos a cancelar
$(".newdetail").on("click", function () {
	if (efe_bantmo == "C") {
		$("#modal_doc_pen_cxc").modal("show");
	} else {
		$("#modal_doc_pen_cxp").modal("show"); 
	}
});
//funcion para elimnar una fila de detalle de movimientos
$(document).on("click", ".borrar", function (event) {
	event.preventDefault();
	$(this).closest("tr").remove();
});
//Actualizar Tabla de Movimiento Bancarios
$("#tbl_banmovin").on("blur", ".tot_movim", function () {
	var total = 0;
	$("#tbl_banmovin tbody tr").each(function () {
		var valor = (($(this).find(".tot_movim").val()));
		if(!$.isNumeric(valor)){
			valor = parseFloat(formatoMoneda($(this).find(".tot_movim").val()));
		}
		if (!isNaN(valor) && valor != "") {
			total += parseFloat(valor);
		}
	});
	$("#tmon_mov").val(format_number_with_dec_new(total, 2));
});
$("#tblTablebanMovim").on("click", ".btn-delete", function () {
	var recordId = $(this).data("id"); // Obtiene el ID del registro
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
			const url = `${base_url}/BanMovim/delete_row`;
			$.ajax({
				url: url, // URL de tu script de eliminación en el servidor
				type: "POST",
				data: { id: recordId },
				dataType: "json",
				success: function (resulta) {
					// La respuesta del servidor debe indicar si fue exitoso
					Swal.fire({
						icon: `${resulta.icon}`,
						title: `${resulta.title}`,
						text: `${resulta.msg}`,
					}).then((result) => {
						if (result.isConfirmed) {
							// Recarga el DataTable
							tableIndex.draw(); // El 'false' previene que se reajuste la paginación a la página 1.
							tableIndex.ajax.reload(null, true);
							//window.location.href = `${base_url}/BanMovim`;
						}
					});
				},
				error: function (xhr, status, error) {
					alert("Hubo un error en la solicitud.");
					console.error(xhr.responseText);
				},
			});
		}
	});
});
$(document).on("click", ".borrar_doc", function (event) {
	event.preventDefault(); 
	$(this).closest('tr').remove();
	UpdateDataTableMovBan();
});
$(document).on("change", ".fila-input", function () {
	const fila = $($(this)).closest("tr");
	const valorDeLaFila = fila.find($(".sal_doc")); // Obtiene el valor del input en esa fila
	var saldo_actual = parseFloat(formatoMoneda($($(this)).val()));
	var saldo_original = parseFloat(formatoMoneda(valorDeLaFila.val()));
	if (saldo_actual > saldo_original) {
		Swal.fire({
			icon: "warning",
			title: "Monto a cancelar mayor al saldo del documento",
			text: "El monto a cancelar no puede ser mayor al saldo del documento.",
		});
		$($(this)).val(format_number_with_dec_new(saldo_original, 2));
	}
	if (saldo_actual < 0 && saldo_original > 0) {
		Swal.fire({
			icon: "warning",
			title: "Monto a cancelar no puede ser menor al saldo del documento.",
			text: "El monto a cancelar no puede ser menor al saldo del documento.",
		});
		$($(this)).val(format_number_with_dec_new(saldo_original, 2));
	}
	$($(this)).val(format_number_with_dec_new(saldo_actual, 2));
	UpdateDataTableMovBan();
});
$(document).on("change", ".fila-input-ret", function () {
	const fila = $(this).closest("tr");
	const valorDeLaFila = fila.find($(".sal_doc")); // Obtiene el valor del input en esa fila
	var saldo_actual = parseFloat(formatoMoneda($(this).val()));
	var saldo_original = parseFloat(formatoMoneda(valorDeLaFila.val()));
	if (saldo_actual > saldo_original) {
		Swal.fire({
			icon: "warning",
			title: "Monto a cancelar mayor al saldo del documento",
			text: "El monto a cancelar no puede ser mayor al saldo del documento.",
		});
		$(this).val(format_number_with_dec_new(saldo_original, 2));
	}
	if (saldo_actual * -1 < 0 && saldo_original > 0 && saldo_actual != 0) {
		Swal.fire({
			icon: "warning",
			title: "Monto a cancelar no puede ser menor al saldo del documento.",
			text: "El monto a cancelar no puede ser menor al saldo del documento.",
		});
		$(this).val(format_number_with_dec_new(saldo_original, 2));
	}
	if (saldo_actual == 0 || isNaN(saldo_actual)) {
		saldo_actual = 0;
		targetInputNumRet.prop("readonly", true);
		targetInputNumRet.removeAttr("required");
	}
	$(this).val(format_number_with_dec_new(saldo_actual, 2));
	UpdateDataTableMovBan();
});
function UpdateDataTableMovBan() {
	//Actualizar totales de tabla
	var tmon_can = 0;
	var tmon_ret = 0;
	var tmon_mov = 0;
	var tmon_mov_det = 0;
	$(".fila-input").each(function (index, value) {
		var mon_can = parseFloat(formatoMoneda($(this).val()));
		if (!isNaN(mon_can)) {
			tmon_can += mon_can;
		}
		$("#monto1").val(format_number_with_dec_new(tmon_can, 2));
	});

	$(".monto").each(function (index, value) {
		var mon_can = parseFloat(formatoMoneda($(this).val()));
		if (!isNaN(mon_can)) {
			tmon_mov_det += mon_can;
		}
		$("#tot_mov").val(format_number_with_dec_new(tmon_mov_det, 2));
	});

	tmon_mov += tmon_can;
	$(".fila-input-ret").each(function (index, value) {
		var mon_can = parseFloat(formatoMoneda($(this).val()));
		if (!isNaN(mon_can)) {
			tmon_ret += mon_can;
		}
	});

	$("#tot_mon_can").val(format_number_with_dec_new(tmon_can, 2));
	$("#tot_mon_ret").val(format_number_with_dec_new(tmon_ret, 2));

	tmon_mov += tmon_ret;

	$("#tmon_mov").val(format_number_with_dec_new(tmon_mov, 2));
}