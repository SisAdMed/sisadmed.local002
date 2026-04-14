//Variables
tipo_fac = "P";
item = "";
itemSelected = "";
id_cot = "";
let fecha_comp = "";
$(document).ready(function () {
	//Validar datos del Formulario
	jQuery.validator.setDefaults({
		debug: false,
		success: "valid",
	});
	$("form#my_form").validate({
		rules: {
			id_emp: "required",
			id_tdo: "required",
			fecha_comp: "required",
			nom_cli: "required",
			id_moneda: "required",
			id_vend: "required",
			item: "required",
		},
		messages: {
			id_emp: "Debe especificar una Empresa",
			id_tdo: "Debe especificar un Tipo de Documentos",
			fecha_comp: "Desbe especificar una Fecha válida",
			nom_cli: "Debe especificar un Cliente",
			id_moneda: "Debe especificar una Moneda",
			id_vend: "Debe especificar un Vendedor",
			item: "Debe especificar al menos un detalle",
		},
	});
	//Cargar el index
	form = $("form").attr("id");
	if (form === undefined) {
		initCotizaciones();
	} else {
		id = $("#id").val();
		if (id) {
			dat_form(id);
		} else {
			dat_form_new();
		}
	}
});
//Formulario Nuevo
function dat_form_new() {
	listar_empresas();
	$("#fecha_comp").val(GetTodayDate(0));
	fecha_comp = $("#fecha_comp").val();
	listar_status(1);
}
//Consultar registro
function dat_form(id_cot) {
	const url = `${base_url}/Cotizaciones/consultar_cotizacion`;
	$.ajax({
		url: url,
		method: "POST",
		dataSrc: "",
		data: { id_cot, id_cot },
		dataType: "json",
		beforeSend: function () {
			loader.show();
		},
		complete: function () {
			loader.hide();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			loader.hide();
			console.log(
				"Ha ocurrido el siguiente error: ",
				textStatus,
				errorThrown
			);
		},
		success: function (data) {
			//Encabezado
			id_emp = data[0].id_emp;
			id_tdo = data[0].id_tdo;
			listar_empresas(id_emp, true);
			listar_tipos_documentos(id_emp, "P", id_tdo, true);
			$("#num_tdo").val(data[0].num_tdo);
			$("#num_tdo").css("pointer-events", "none");
			fecha_comp = data[0].fecha_comp;
			$("#fecha_comp").val(fecha_comp);
			$("#fecha_comp").css("pointer-events", "none");
			id_cli = data[0].id_cli;
			$("#id_cli").val(id_cli);
			$("#nom_cli").val(data[0].nom_ent);
			id_moneda = data[0].id_moneda;

			listar_monedas(id_moneda, true);
			xtasa = data[0].tasa_cambio;
			$("#tasa_cambio").val(format_number_with_dec_new(xtasa, 2));
			xtasa = data[0].tasa_cambio;
			id_vend = data[0].id_vend;
			listar_vendedores(id_vend, true);
			id_fab = data[0].id_fab;
			listar_marcas(id_fab);
			$("#observa").val(data[0].observa);
			//
			update_tasa();
			//Detalle
			onlyread = "";
			id_emp = data[0].id_emp;
			id_tdo_cfg = tip_doc_fac(id_emp);
			loc_pri_cot = id_tdo_cfg["loc_pri_cot"];
			locked_invoice = id_tdo_cfg["locked_invoice"];
			if (loc_pri_cot == 1) {
				onlyread = " readonly ";
			}
			stock = id_tdo_cfg["cot_stock"];
			item = 0;
			htmlTags = "";
			$.each(data, function (key, value) {
				var pre_vta = value.sub_total / value.can_det
				
				//Precio de Venta
				if (pre_vta === value.pre_vta) {
					pre_vta = value.pre_vta;
				}
				//Precio Unitario
				var pre_unit = pre_vta / value.uni_vta;
				if (pre_unit === value.pre_unit) {
					pre_unit = value.pre_unit;
				}
				item++;
				htmlTags = `
					<tr id="fila-${item}">
						<td class="text-right text-xs">${item}</td>
						<td style="width:30%"><input type="hidden" name="id_prod[]" id="id_prod${item}" class="text-xs photo" value="${
					value.id_prod
				}"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_prod${item}" name="nom_prod" readonly value="${
					value.nom_prod
				}"><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>
						<td style="width:8%"><input type="number" name="cant[]" id="cant${item}" class="form-control text-right text-xs tcant" min="1"style="width:80%" value="${
					value.can_det
				}" onchange="CalculateTotalFac()" ></td>
						<td style="width:8%"><input type="number" name="stock[]" id="stock${item}" class="form-control text-right text-xs stock" style="width:100%" disabled value="${
					value.stock
				}"></td>
						<td style="width:7%"><input type="text" name="uni_ven_prod[]" id="uni_ven_prod${item}" readonly class="form-control text-right text-xs"  style="width:100%" value="${
					value.uni_vta
				}"></td>
						<td style="width:8%"><input type="text" name="ventas_prod[]"  id="ventas_prod${item}" readonly class="form-control text-right text-xs"  style="width:100%" value="${format_number_with_dec_new(
					pre_unit,
					2
				)}"></td>
						<td style="width:8%"><input type="text" name="ventas_prod1[]" id="ventas_prod1${item}" class="form-control text-right text-xs reCalcular camponumero"  style="width:100%" ${onlyread} value="${format_number_with_dec_new(
					pre_vta,
					2
				)}" onchange="CalculateTotalFac()"></td>
						<td style="width:10%"><input type="hidden" name="id_des[]" id="id_des${item}" class="text-xs"><div class="input-group"><input type="text"class="form-control text-xs text-right" id="nom_des${item}" name="nom_des" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-descuentos" title="Buscar y seleccionar descuento"><i class="fas fa-search"></i></a></span></div></div></td>
						<td style="width:10%"><select name="iva_prod[]" id="iva_prod${item}" class="form-control text-xs reCalcular input-iva" style="width:60%"></select></td>
						<td style="width:10%"><input type="text" name="total[]" id="total${item}" class="form-control text-right text-xs sub-total input-fila" readonly value="${format_number_with_dec_new(
					value.sub_total,
					2
				)}"></td>
						<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>
					</tr>
				`;
				$("#cuerpoTablaDetalle").append(htmlTags); 
				listar_si_no(value.iva_prod, `iva_prod${item}`);
			});
			recorreTable_fac(1, xtasa, "P");
		},
	});
}
//Validar empresa
$("#id_emp").on("change", async function (e) {
	e.preventDefault();
	onlyread = "";
	id_emp = $(this).val();
	$("#id_tdo").val("");
	id_tdo_cfg = "";
	$("#id_cli").val("");
	$("#nom_cli").val("");
	$("#id_moneda").empty();
	$("#id_vend").empty();
	$("#id_fab").empty();
	$("#tasa_cambio").val("");
	if (id_emp) {
		id_tdo_cfg = await tip_doc_fac(id_emp);
		loc_pri_cot = id_tdo_cfg["loc_pri_cot"];
		locked_invoice = id_tdo_cfg["locked_invoice"];
		stock = id_tdo_cfg["cot_stock"];
		listar_monedas();
		listar_vendedores();
		listar_marcas();
		if (loc_pri_cot == 1) {
			onlyread = " readonly ";
		}
	}
	if (!id) {
		if (id_tdo_cfg) {
			id_tdo_val = id_tdo_cfg["id_tdoc_pre"];
			listar_tipos_documentos(id_emp, tipo_fac, id_tdo_val);
			id_tdo = id_tdo_val;
			$("#id_tdo").trigger("change");
		}
	}
});
//Validar si el Tipo de Documento usa consecutivo o no para poder aisgnar el número del documento
$(document).on("change", "#id_tdo", function (e) {
	const url = `${base_url}/CXCDocument/val_tdo`;
	if (!id_tdo) {
		id_tdo = $(this).val();
	}
	try {
		$.ajax({
			url: url,
			method: "POST",
			data: { id: id_tdo },
			dataType: "json",
			dataSrc: "",
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
			success: function (data) {
				if (data.con_tdoc == 0) {
					$("#num_tdo").prop("readonly", false);
				} else {
					$("#num_tdo").prop("readonly", true);
				}
				if (data.sol_aprob == 1) {
					sol_aprob = true;
					$("#status").val(9);
					$("#status").css("pointer-events", "none");
				}
				$("#id_tdo").css("pointer-events", "none");
			},
		});
	} catch (error) {
		console.log("Ha ocurrido el siguiente error: " + error);
	}
});
//Al cambiar la fecha
$(document).on("change", "#fecha_comp", function (event) {
	event.preventDefault();
	fecha_comp = $(this).val();
	update_tasa();
});
//Combo de monedas
$(document).on("change", "#id_moneda", function (event) {
	event.preventDefault();
	id_moneda = $(this).val();
	update_tasa();
});
//Actualizar tasa y mostrar campos de valores de moneda
async function update_tasa() {
	$("#tasa_cambio").val("");
	$(".local").hide();
	$(".foranea").hide();

	if (id_moneda && fecha_comp) {
		xtasa = await getExchangerate(fecha_comp, id_moneda);
		$("#tasa_cambio").val(xtasa);
		tasa_cambio = formatoMoneda(xtasa);
		show_tasa();
	}
	$("#tasa_cambio").css("pointer-events", "none");
}
//Seleccioanr por defecto el vendedor al momento de escoger el cliente
$(document).on("change", "#id_cli", function (e) {
	e.preventDefault();
	id_cli = $(this).val();
	fetcingData(id_cli);
});
async function fetcingData(id_cli) {
	const datosFetched = await tid_vend(id_cli);
	id_ven_cli = datosFetched["id_vend"];
	id_mon_cli = datosFetched["id_moneda"];
	nom_cli = datosFetched["nom_ent"];
	handling_conver = datosFetched["handling_conver"];
	$("#nom_cli").val(nom_cli);
	listar_vendedores(id_ven_cli);
	listar_monedas(id_mon_cli);
	$("#id_moneda").val(id_mon_cli);
	id_moneda = id_mon_cli;
	update_tasa();
}
//Guardar y/o Actualizar
$("#my_form").on("submit", function (e) {
	e.preventDefault();
	if ($(this).valid()) {
		var formData = $(this).serialize();
		const url = `${base_url}/Cotizaciones/store`;
		$.ajax({
			type: "POST",
			url: url,
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
					"Ha ocurrido el siguiente error: " +
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
						window.location.href = `${base_url}/Cotizaciones`;
					}
				});
			},
		});
	} else {
		return false;
	}
});
//Imprimir cotización segun la moneda indicada
function print_cotiza(e, ori) {
	//ori = 1 pdf, ori = 2 excel
	let num_cot = e.dataset.code;
	let id_moneda = e.dataset.name;
	let id_code = e.dataset.id;
	if (ori) {
		id_code = id_code + "|" + ori;
	}
	if (id_moneda == "USD") {
		Swal.fire({
			icon: "question",
			title:
				"¿Está seguro que desea imprimir la Cotización número " +
				num_cot +
				"?",
			showDenyButton: true,
			showCancelButton: true,
			confirmButtonText: "Solo en " + id_moneda,
			denyButtonText: "Ambas monedas",
		}).then((result) => {
			if (result.isConfirmed) {
				window.open(
					`${base_url}/Cotizaciones/print_cotiza_foranea/` + id_code,
					"_blank"
				);
			} else if (result.isDenied) {
				window.open(
					`${base_url}/Cotizaciones/print_cotiza/` + id_code,
					"_blank"
				);
			}
		});
	} else {
		Swal.fire({
			icon: "question",
			title:
				"¿Está seguro que desea imprimir la Cotización número " +
				num_cot +
				"?",
			showCancelButton: true,
			confirmButtonText: "¿Imrprimir ?",
		}).then((result) => {
			if (result.isConfirmed) {
				window.open(
					`${base_url}/Cotizaciones/print_cotiza/` + id_code,
					"_blank"
				);
			}
		});
	}
}
//Imprimir cotización segun la moneda indicada
function print_cotiza_excel(e, ori) {
	//ori = 1 pdf, ori = 2 excel
	let num_cot = e.dataset.code;
	let id_moneda = e.dataset.name;
	let id_code = e.dataset.id;
	if (ori) {
		id_code = id_code + "|" + ori;
	}
	if (id_moneda == "USD") {
		Swal.fire({
			icon: "question",
			title:
				"¿Está seguro que desea imprimir la Cotización número " +
				num_cot +
				" en Excel?",
			showDenyButton: true,
			showCancelButton: true,
			confirmButtonText: "Solo en " + id_moneda,
			denyButtonText: "Ambas monedas",
		}).then((result) => {
			if (result.isConfirmed) {
				window.open(
					`${base_url}/Cotizaciones/print_cotiza_excel/` + id_code,
					"_blank"
				);
			} else if (result.isDenied) {
				window.open(
					`${base_url}/Cotizaciones/print_cotiza_excel/` + id_code,
					"_blank"
				);
			}
		});
	} else {
		Swal.fire({
			icon: "question",
			title:
				"¿Está seguro que desea imprimir la Cotización número " +
				num_cot +
				"en Excel?",
			showCancelButton: true,
			confirmButtonText: "¿Imrprimir ?",
		}).then((result) => {
			if (result.isConfirmed) {
				window.open(
					`${base_url}/Cotizaciones/print_cotiza_excel/` + id_code,
					"_blank"
				);
			}
		});
	}
}
//funcion para elimnar una fila de detalle de cotización
$(document).on("click", ".borrar", function (event) {
	event.preventDefault();
	$(this).closest("tr").remove();
	xtasa = formatoMoneda($("#tasa_cambio").val());
	recorreTable_fac(1, xtasa, "P");
});
//Recalcular en caso de cambiar el IVA a si o No
$(document).on("change", ".reCalcular", function (event) {
	event.preventDefault();
	xtasa = formatoMoneda($("#tasa_cambio").val());
	recorreTable_fac(1, xtasa, "P");
});
//
$("#id_fab").on("change", function (e) {
	e.preventDefault();
	id_fab = $(this).val();
	$("#cuerpoTablaDetalle").html("");
	if (Object.keys(id_fab).length) {
		create_express();
	}
});
function create_express() {
	const url = `${base_url}/Cotizaciones/create_express`;
	id_ent = $("#id_cli").val();

	$.ajax({
		url: url,
		method: "POST",
		dataSrc: "",
		data: { id_fab: id_fab, id_ent: id_ent },
		dataType: "json",
		beforeSend: function () {
			loader.show();
		},
		complete: function () {
			loader.hide();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			loader.hide();
			console.log(
				"Ha ocurrido el siguiente error: ",
				textStatus,
				errorThrown
			);
		},
		success: function (data) {
			var htmlTags = "";
			item = 0;
			$.each(data, function (i, xitem) {
				item++;
				id_prod = xitem.id_prod;
				nom_prod = xitem.nom_prod;
				stock = xitem.stock;
				ventas_prod = xitem.pv1;
				uni_ven_prod = xitem.uni_ven_prod;
				precio_unit = ventas_prod / uni_ven_prod;
				//precio_unit = xitem.precio_unit;
				iva_prod = xitem.iva_prod;
				iva_prod_SI = "";
				iva_prod_NO = "";
				if (iva_prod == "1") {
					iva_prod_SI = "selected";
				} else {
					iva_prod_NO = "selected";
				}
				htmlTags = `
					<tr id="fila-${item}">
						<td class="text-right text-xs">${item}</td>
						<td style="width:30%"><input type="hidden" name="id_prod[]" id="id_prod${item}" class="text-xs photo" value="${id_prod}"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_prod${item}" name="nom_prod" readonly value="${nom_prod}"><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>
						<td style="width:8%"><input type="number" name="cant[]" id="cant${item}" class="form-control text-right text-xs tcant" min="1"style="width:80%" value="1" onchange="CalculateTotalFac()" ></td>
						<td style="width:8%"><input type="number" name="stock[]" id="stock${item}" class="form-control text-right text-xs stock" style="width:100%" disabled value="${stock}"></td>
						<td style="width:7%"><input type="text" name="uni_ven_prod[]" id="uni_ven_prod${item}" readonly class="form-control text-right text-xs"  style="width:100%" value="${uni_ven_prod}"></td>
						<td style="width:8%"><input type="text" name="ventas_prod[]"  id="ventas_prod${item}" readonly class="form-control text-right text-xs"  style="width:100%" value="${format_number_with_dec_new(
					precio_unit,
					2
				)}"></td>
						<td style="width:8%"><input type="text" name="ventas_prod1[]" id="ventas_prod1${item}" class="form-control text-right text-xs reCalcular input-fila camponumero"  style="width:100%" ${onlyread} value="${format_number_with_dec_new(
					ventas_prod,
					2
				)}" onchange="CalculateTotalFac()"></td>
						<td style="width:10%"><input type="hidden" name="id_des[]" id="id_des${item}" class="text-xs"><div class="input-group"><input type="text"class="form-control text-xs text-right" id="nom_des${item}" name="nom_des" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-descuentos" title="Buscar y seleccionar descuento"><i class="fas fa-search"></i></a></span></div></div></td>
						<td style="width:10%"><select name="iva_prod[]" id="iva_prod${item}" class="form-control text-xs reCalcular input-iva" style="width:60%"><option value="S" ${iva_prod_SI} >Si</option><option value="N" ${iva_prod_NO}>No</option></select></td>
						<td style="width:10%"><input type="text" name="total[]" id="total${item}" class="form-control text-right text-xs sub-total input-fila" readonly value="${format_number_with_dec_new(
					ventas_prod,
					2
				)}"></td>
						<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>
					</tr>
				`;
				$("#cuerpoTablaDetalle").append(htmlTags);
			});
			xtasa = formatoMoneda($("#tasa_cambio").val());
			recorreTable_fac(1, xtasa, "P");
		},
	});
}
$("#tblDetalle").on("click", "tr", function () {
	var filaId = $(this).attr("id");
	item = filaId.substring(5);
});
//Eliminar un registro
$("#tblIndexMain").on("click", ".btn-delete", function () {
	var recordId = $(this).data("id"); // Obtiene el ID del registro
	var num_tdo = $(this).data("code");
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
			const url = `${base_url}/Cotizaciones/destroy`;
			$.ajax({
				url: url, // URL de tu script de eliminación en el servidor
				type: "POST",
				data: { id: recordId, num_tdo, num_tdo },
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
							tableIndex.draw(); // El 'false' previene que se reajuste la paginación a la página 1.
							tableIndex.ajax.reload(null, true);
							//window.location.href = `${base_url}/BanMovim`;
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