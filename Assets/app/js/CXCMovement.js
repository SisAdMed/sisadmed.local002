//Varibles
item = 0;
TMod = "C";
efe_bantmo = "C";
efecto = "C";
//Validar campos del formulario
$().ready(function () {
	$.validator.setDefaults({
		ignore: [],
	});
	$("form[name='my_form']").validate({
		rules: {
			id_emp: "required",
			id_tmocxc: "required",
			id_ent: "required",
			id_moneda: "required",
			fecha_comp: "required",
			status: "required",
			movem_descrip: "required",
			item: {
				required: true,
				min: 1,
			},
		},
		messages: {
			id_emp: "Debe especificar una empresa",
			id_tmocxc: "Debe especificar un Tipo de Movimiento",
			id_ent: "Debe especificar un Cliente",
			id_moneda: "Debe especificar una Moneda",
			fecha_comp: "Debe indicar una Fecha para el Movimiento",
			status: "Debe especificar un status",
			movem_descrip: "Debe especificar una descripción",
			item:  {
				required: "Debe agregar al menos un documento a cancelar",
				min: "Debe agregar al menos un documento a cancelar",
			},
		},
	});
	//Cargar el index
	form = $("form").attr("id");
	if (form === undefined) {
		initCXCMovement(); 
	} else {
		//Cuando es un registro nuevo
		id = $("#id").val();
		if (id) {
			show_row(id);
		} else {
			dat_form_new();
		}
	}
	//
	table = $("#tblSeatDetail").DataTable({
		info: false,
		paging: false,
		searching: false,
		ordering: false,
		destroy: true,
		colResizable: true,
		language: {
			url: `${base_url}/Assets/json/es-ES.json`,
		},
	});
});
//Al seleccionar nuevo
function dat_form_new() {
	listar_empresas();
	listar_status(1);
	$("#fecha_comp").val(GetTodayDate(0));
}
//Mostrar registro
function show_row(id) {
	const url = `${base_url}/CXCMovement/showrow`;
	$.ajax({
		url: url,
		method: "POST",
		dataSrc: "",
		data: { id: id },
		dataType: "json",
		beforeSend: function () {
			loader.show();
		},
		success: function (response) {
			data = response;
			id_emp = data[0]["id_emp"];
			listar_empresas(id_emp, true);
			id_tmocxc = data[0]["id_tmocxc"];
			listar_tipos_mov_CXC(id_tmocxc, "id_tmocxc", true);
			movem_number = data[0]["movem_number"];
			$("#movem_number").val(movem_number);
			$("#movem_number").prop("readonly", true);
			id_cli = data[0]["id_cli"];
			$("#id_cli").val(id_cli);
			nom_cli = data[0]["nom_ent"];
			$("#nom_cli").val(nom_cli);
			$("#fecha_comp").val(data[0]["fecha_comp"]);
			$("#fecha_comp").prop("readonly", true);
			id_moneda = data[0]["id_moneda"];
			listar_monedas(id_moneda, true);
			xtasa = format_number_with_dec_new(data[0]["tasa_cambio"], 8);
			$("#tasa_cambio").val(xtasa);
			$("#tasa_cambio").prop("readonly", true);
			$("#movem_descrip").val(data[0]["movem_descrip"]);
			status = data[0]["status"];
			listar_status(status);
			movem_origen = data[0]["movem_origen"];
			if (movem_origen != "CXC") {
				$("#btnok").prop("disabled", true);
			}
			item = 0;
			x_mon_doc = 0;
			htmlTags = "";
			$.each(data, function (i, xitem) {
				item++;
				fecha_emi = xitem.fecha_emi.split("-");
				fecha_ven = xitem.fecha_venci.split("-");
				var tr = `<tr id="fila-${item}">`;
				var htmlTags = $(tr).append(`
				<td class='text-right'><input type="text" id="id_cot${
					xitem.id_cot
				}" name="id_cot[]" class="form-control text-right text-xs rid_cot" value="${
					xitem.id_cot
				}" readonly/></td>
				<td class='text-right'>${item}</td>
				<td>${xitem.tipo_codigo}</td>
				<td>${xitem.nom_tdoc}</td>
				<td class='text-center'>${xitem.num_tdo}</td>
				<td class='text-center'>${fecha_emi[2]}-${fecha_emi[1]}-${fecha_emi[0]}</td>
				<td class='text-center'>${fecha_ven[2]}-${fecha_ven[1]}-${fecha_ven[0]}</td>
				<td class='text-center'><input type="text" id="id_moneda_doc${item}" name="id_moneda_doc[]" class="form-control text-center text-xs" value="${
					xitem.codigo_moneda
				}" readonly/></td>
				<td class='text-right'>${format_number_with_dec_new(xitem.tasa_cambio, 2)}</td>
				<td class='text-right'>${format_number_with_dec_new(xitem.mon_doc, 2)}</td>
				<td class='text-right'><input type="text" id="sal_doc${item}" name="sal_doc[]" class="form-control text-right text-xs sal_doc" readonly value="${format_number_with_dec_new(
					xitem.sal_doc + xitem.monto_doc,
					2
				)}"/></td>
				<td class='text-right'><input type="text" id="mon_can${item}" name="mon_can[]" class="form-control text-right text-xs fila-input" value="${format_number_with_dec_new(
					xitem.monto_doc,
					2
				)}"/></td>
				<td class='text-right'><input type="text" id="mon_ret${item}" name="mon_ret[]" class="form-control text-right text-xs fila-input-ret" value="${format_number_with_dec_new(
					xitem.mon_ret,
					2
				)}"></td>
				<td class='text-right'><input type="text" id="num_ret${item}" name="num_ret[]" class="form-control text-right text-xs fila-input-num" value="${
					xitem.num_ret
				}" /></td>
				<td class="text-center"><input type="checkbox" name="id_check[]" class="form-check-input check-row-cxc" data-input-id="mon_can${item}" data-input-ret="mon_ret${item}" data-input-num="num_ret${item}" checked/> <button type="button" class="btn btn-danger btn-xs borrar_doc" title="Eliminar item"><i class="fa fa-trash"></i></button></td>`);
				$("#tbody").append(htmlTags);
				$("#item").val(item);
				UpdateDataTable();
			});
		},
		complete: function () {
			loader.hide();
		},
		error: function (error) {
			loader.hide();
			console.log("Ha ocurido el erro: " + error);
		},
	});
}
//Al seleccionar empresas llenar los combos respectivos
$("#id_emp").on("change", async function (e) {
	e.preventDefault();
	id_emp = $(this).val();
	$("#id_tmocxc").empty();
	if (id_emp) {
		id_tdo_cfg = await tip_doc_com(id_emp);
		id_moneda_cia = id_tdo_cfg["id_moneda"];
		especial_contrib = id_tdo_cfg["especial_contrib"];
		listar_tipos_mov_CXC(0, "id_tmocxc");
	}
});
//Validar si el Tipo de Documento usa consecutivo o no para poder aisgnar el número del documento
$(document).on("change", "#id_tmocxc", async function (e) {
	id_tmocxc = $(this).val();
	if (id_tmocxc) {
		const datos = new FormData();
		datos.append("id", id_tmocxc);
		try {
			const url = `${base_url}/CXCMovement/val_tmo`;
			const respuesta = await fetch(url, {
				method: "POST",
				body: datos,
			});
			const resultado = await respuesta.json();
			if (resultado) {
				if (resultado[0]["con_tmocxc"] == "N") {
					$("#movem_number").prop("readonly", false);
				} else {
					$("#movem_number").prop("readonly", true);
				}
			}
		} catch (error) {
			console.log(error);
		}
	}
});
//Seleccionar monedas segun el cliente
$(document).on("change", "#id_cli, #fecha_comp", function (e) {
	e.preventDefault();
	$("#tbody").empty();
	item = 0;
	$("#item").val(item);
	fetcingData();
});
//Mostrar tasa segun moneda y fecha
async function fetcingData() {
	id_cli = $("#id_cli").val();
	$("#tasa_cambio").val("");
	const datosFetched = await tid_vend(id_cli);
	if (!id) {
		id_mon_cli = datosFetched["id_moneda"];
		nom_cli = datosFetched["nom_ent"];
		$("#nom_cli").val(nom_cli);
		listar_monedas(id_mon_cli);
		if (datosFetched["id_moneda"]) {
			fecha_comp = $("#fecha_comp").val();
			xTasaCambio = await xTasa(fecha_comp, id_mon_cli);
			if (xTasaCambio) {
				$("#tasa_cambio").val(xTasaCambio, 2)
				$("#tasa_cambio").css("pointer-events", "none");
			}
		}
	}
}
//Mostrar cambio al seleccionar Tipo de Moneda
$(document).on("change", "#id_moneda", async function (e) {
	e.preventDefault();
	fecha_comp = $("#fecha_comp").val();
	id_moneda = $(this).val();
	$("#tbody").empty();
	//
	xTasaCambio = await xTasa(fecha_comp, id_moneda);
	$("#tasa_cambio").val(xTasaCambio);
});
//Nuevo detalle de documentos a cancelar
$("#newdetail").on("click", function () {
	$("#modal_doc_pen_cxc").modal("show");
});
//Mostar los Documentos pendientes de Cuentas por Cobrar, tanto desde Banco Movimientos como desde Movimientos de Cuenas por Cobrar. José Vargas 28-08-2025 a las 10:10:00
$("#modal_doc_pen_cxc").on("show.bs.modal", function () {
	var url = "";
	id_emp = $("#id_emp").val();
	fecha_comp = $("#fecha_comp").val();
	id_moneda = $("#id_moneda").val();
	id_cli = $("#id_cli").val();
	tabla_name = "#tblSeatDetail";
	url = `${base_url}/CXCDocument/doc_ped_cli`;
	datos = {
		id_emp: id_emp,
		id_cli: id_cli,
		fecha_comp: fecha_comp,
		id_moneda: id_moneda,
	};
	$.ajax({
		url: url,
		method: "POST",
		data: datos,
		dataType: "json",
		beforeSend: function () {
			loader.show();
		},
		success: function (data) {
			$("#tblModalDocPend_cxc").DataTable({
				destroy: true,
				data: data,
				responsive: true,
				processing: true,
				columns: [
					{ data: "id_doc", title: "Id" },
					{ data: "tipo_codigo", title: "Código" },
					{ data: "nom_tdoc", title: "Descripción" },
					{
						data: "num_tdo",
						title: "Número",
						className: "text-right",
					},
					{
						data: "nro_control",
						title: "Control",
						className: "text-right",
					},
					{
						data: "fecha_comp",
						render: $.fn.dataTable.render.moment(
							FROM_PATTERN,
							TO_PATTERN
						),
						title: "Fecha Emi.",
					},
					{
						data: "fecha_venci",
						render: $.fn.dataTable.render.moment(
							FROM_PATTERN,
							TO_PATTERN
						),
						title: "Fecha Venc.",
					},
					{
						data: "codigo_moneda",
						title: "Moneda",
						className: "text-center",
					},
					{
						data: "tasa_cambio",
						className: "text-right",
						render: DataTable.render.number(".", ",", 8),
						title: "Tasa Cambio",
					},
					{
						data: null,
						className: "text-right",
						title: "Monto Doc.",
						render: function (data, type, row) {
							if (id_moneda != id_moneda_cia) {
								return `${format_number_with_dec_new(
									row.mon_doc_for,
									2
								)}`;
							} else {
								return `${format_number_with_dec_new(
									row.mon_doc_dom,
									2
								)}`;
							}
						},
					},
					{
						data: null,
						className: "text-right",
						title: "Saldo Doc.",
						render: function (data, type, row) {
							if (id_moneda != id_moneda_cia) {
								return `${format_number_with_dec_new(
									row.sal_doc_for,
									2
								)}`;
							} else {
								return `${format_number_with_dec_new(
									row.sal_doc_dom,
									2
								)}`;
							}
						},
					},
				],
				language: {
					url: `${base_url}/Assets/json/es-ES.json`,
				},
			});
		},
		complete: function () {
			loader.hide();
		},
		error: function (error) {
			loader.hide();
			console.log("Error al cargar los datos: ");
		},
	});
});
//Al seleccionar un registro
$("body").on("click", "#tblModalDocPend_cxc tr", function () {
	item_doc++;
	var row_select = $(this).closest("tr");
	var adicional = "";
	var datosFila = row_select
		.find("td")
		.map(function () {
			return $(this).text();
		})
		.get();
	var tr = '<tr id="fila' + item_doc + '">';
	var htmlTags = $(tr).append(`
     		<td class='text-right'><input type="text" id="id_cot${datosFila[0]}" name="id_cot[]" class="form-control text-right text-xs rid_cot" value="${datosFila[0]}" readonly/></td>
            <td class='text-right'>${item_doc}</td>
            <td>${datosFila[1]}</td>
            <td>${datosFila[2]}</td>
            <td class='text-center'>${datosFila[3]}</td>
            <td class='text-center'>${datosFila[5]}</td>
            <td class='text-center'>${datosFila[6]}</td>
            <td class='text-center'><input type="text" id="id_moneda_doc${item_doc}" name="id_moneda_doc[]" class="form-control text-center text-xs" value="${datosFila[7]}" readonly/></td>
            <td class='text-right'>${datosFila[8]}</td>
            <td class='text-right'>${datosFila[9]}</td>
			<td class='text-right'><input type="text" id="sal_doc${item_doc}" name="sal_doc[]" class="form-control text-right text-xs sal_doc" readonly value="${datosFila[10]}"/></td>
            <td class='text-right'><input type="text" id="mon_can${item_doc}" name="mon_can[]" class="form-control text-right text-xs fila-input" readonly/></td>
			<td class='text-right'><input type="text" id="mon_ret${item_doc}" name="mon_ret[]" class="form-control text-right text-xs fila-input-ret" readonly></td>
            <td class='text-right'><input type="text" id="num_ret${item_doc}" name="num_ret[]" class="form-control text-right text-xs fila-input-num" value="" readonly/></td>
            <td class="text-center"><input type="checkbox" name="id_check[]" class="form-check-input check-row-cxc" data-input-id="mon_can${item_doc}" data-input-ret="mon_ret${item_doc}" data-input-num="num_ret${item_doc}"/> <button type="button" class="btn btn-danger btn-xs borrar_doc" title="Eliminar item"><i class="fa fa-trash"></i></button></td>`);
		table.row.add(htmlTags).draw(true);
	//$("#tbody").append(htmlTags);
	$("#item").val(item_doc);
	$("#modal_doc_pen_cxc").modal("hide");
});
//Imprimir Movimiento
function print_mov(e) {
	let num_cot = e.dataset.id;
	let name = e.dataset.name;
	Swal.fire({
		icon: "question",
		title: "¿Está seguro que desea imprimir el Movimiento " + name + "?",
		showCancelButton: true,
		confirmButtonText: "Imprimir",
	}).then((result) => {
		if (result.isConfirmed) {
			window.open(
				`${base_url}/CXCMovement/print_movement/` + num_cot,
				"_blank"
			);
		}
	});
}
//Al seleccionar un registro del modal y decir que se va a cobrar, para acutlizar montos en la tabla. Jose Vargas 28-08-2025 10:42:00
//$("#tblSeatDetail tbody").on("click", "input.check-row-cxc", function () {
$(document).on("click", "input.check-row", function (event) {
	//Cuando se marca un registro
	var row = table.row($(this).closest("tr")); // Encuentra la fila
	var data = row.data(); // Obtiene los datos de la fila
	var closeCheckbox = $(this)
		.closest("td")
		.parent()
		.find("input[type=checkbox]");
	var xmon_can = 0;
	var xmon_ret = 0;
	var row_select = table.row($(this).parent()).data();
	var checkbox = $(this);
	var inputId = closeCheckbox.data("input-id"); // Obtiene el ID del input del atributo data-*
	var inputIdRet = closeCheckbox.data("input-ret"); // Obtiene el ID del input del atributo data-*
	var inputIdNumRet = closeCheckbox.data("input-num"); // Obtiene el ID del input del atributo data-*
	var targetInput = $("#" + inputId); // Obtiene el ID del input del atributo data-*
	var targetInputRet = $("#" + inputIdRet); // Obtiene el ID del input del atributo data-*
	targetInputNumRet = $("#" + inputIdNumRet); // Obtiene el ID del input del atributo data-*
	if (closeCheckbox.prop("checked")) {
		var fila = $(this).closest("tr");
		var valorInput = fila.find(".rid_cot").val(); // O cualquier otro selector para tu input
		id_cot = valorInput;
		var url = "";
		url = `${base_url}/CXCDocument/get_doc_cxc`;
		$.ajax({
			url: url,
			type: "POST",
			data: { id_cot: id_cot, id_moneda: id_moneda },
			dataType: "json",
			beforeSend: function () {
				loader.show();
			},
			complete: function () {
				loader.hide();
			},
			error: function (xhr, ajaxOptions, thrownError) {
				loader.hide();
				console.log(xhr.responseText);
			},
			success: function (data) {
				console.log(data);
				if (data) {
					xmoneda = data["id_moneda"];
					xtasa_cambio = data["tasa_cambio"];
					xmon_iva = 0;
					xmon_can = data["sal_doc_dom"];
					xmon_iva = data["mon_iva_dom"];
					ymon_ret = data["mon_ret"];
					if(id_moneda != id_moneda_cia){
						xmon_can = data["sal_doc_for"];
						xmon_iva = data["mon_iva_for"];
					}
					
					//Si es contribuyente especial, se le aplica el 75% de la retencion
					//y si no tiene iva, no se le aplica retencion
					//Validar si es contribuyente especial
					if (
						(especial_contrib == "1" || especial_contrib == "S") &&
						xmon_iva != 0
					) {
						xmon_iva = parseFloat(
							xmon_iva * (75 / 100) * -1
						).toFixed(2);
					}
					if (xmon_can != 0) {
						targetInput.val(
							format_number_with_dec_new(xmon_can, 2)
						);
						targetInput.prop("readonly", false);
						targetInputRet.prop("readonly", false);
						targetInputNumRet.prop("readonly", false);
					}
					//Comentado, para que salga la retencion en cualquier caso, falta validar si ya se hizo la rentecion JV 02-09-2025
					if (xmon_iva != ymon_ret) {
						targetInputRet.val(
							format_number_with_dec_new(xmon_iva, 2)
						);
					} else {
						xmon_iva = 0;
						targetInputRet.prop("readonly", true);
						targetInputNumRet.prop("readonly", true);
					}
					if (xmon_iva != 0) {
						targetInputNumRet.prop("required", true);
						targetInputNumRet.attr(
							"title",
							"Debe ingresar el número Retención"
						);
						targetInputNumRet.attr("minlength", "14");
						targetInputNumRet.attr("maxlength", "14");
					} else {
						targetInputNumRet.removeProp("required");
						targetInputNumRet.removeAttr("title");
						targetInputNumRet.removeAttr("maxlength");
						targetInputNumRet.removeAttr("minlength");
					}
					targetInput.select();
					UpdateDataTable();
				}
			},
		});
	} else {
		xmon_can = 0;
		xmon_ret = 0;
		total_mov -= xmon_can;

		targetInput.prop("readonly", true);
		targetInputRet.prop("readonly", true);
		targetInputNumRet.prop("readonly", true);

		targetInput.val(format_number_with_dec_new(xmon_can, 2));
		targetInputRet.val(format_number_with_dec_new(xmon_ret, 2));

		UpdateDataTable();
	}
});
function UpdateDataTable() {
	//Actualizar totales de tabla
	var tmon_can = 0;
	var tmon_ret = 0;
	$(".fila-input").each(function (index, value) {
		var mon_can = parseFloat(formatoMoneda($(this).val()));
		if (!isNaN(mon_can)) {
			tmon_can += mon_can;
		}
	});
	$("#tot_can_tbl_cxc").val(format_number_with_dec_new(tmon_can, 2));

	$(".fila-input-ret").each(function (index, value) {
		var mon_can = parseFloat(formatoMoneda($(this).val()));
		if (!isNaN(mon_can)) {
			tmon_ret += mon_can;
		}
	});
	$("#tot_ret_tbl_cxc").val(format_number_with_dec_new(tmon_ret, 2));
}
//funcion para elimnar una fila de detalle de documentos
$(document).on("click", ".borrar_doc", function (event) {
	event.preventDefault();
	$(this).closest("tr").remove();
	UpdateDataTable();
});
$(document).on("change", ".fila-input", function () {
	const fila = $($(this)).closest("tr");
	const valorDeLaFila = fila.find($(".sal_doc")); // Obtiene el valor del input en esa fila
	var saldo_actual = parseFloat($($(this)).val());
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
	UpdateDataTable();
});
$(document).on("change", ".fila-input-ret", function () {
	const fila = $(this).closest("tr");
	const valorDeLaFila = fila.find($(".sal_doc")); // Obtiene el valor del input en esa fila
	var saldo_actual = parseFloat($(this).val());
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
	UpdateDataTable();
});
//Eliminar registros
function eliminarBtn(e) {
	id = e.dataset.id;
	name = e.dataset.name;
	Swal.fire({
		title: "¿Está usted seguro de eliminar esta " + name + "?",
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
$("#tblIndexMain").on("click", ".btn-delete", function () {
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
			const url = `${base_url}/CXCMovement/delete_row`;
			$.ajax({
				url: url, // URL de tu script de eliminación en el servidor
				type: "POST",
				data: { id: recordId },
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
//Función para recargar el datatable
$(".refresh-button").on("click", function () {
	tableIndex.ajax.reload(null, false);
});