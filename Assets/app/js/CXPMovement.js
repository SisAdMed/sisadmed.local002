//Variables
item = 0;
Tmod = "P";
efe_bantmo = "P";
efecto = "P";
//Validar campos del formulario
$().ready(function () {
	$.validator.setDefaults({
		ignore: [],
	});
	$("#my_form").validate({
		rules: {
			id_emp: "required",
			id_tmocxp: "required",
			id_ent: "required",
			fecha_comp: "required",
			id_moneda: "required",
			movem_descrip: "required",
			status: "required",
			item: "required",
		},
		messages: {
			id_emp: "Debe especificar una empresa",
			id_tmocxp: "Debe especificar un tipo de Movimiento",
			id_ent: "Debe especificar un Proveedor",
			fecha_comp: "Debe especificar una Fecha válida",
			id_moneda: "Debe especificar una Moneda",
			movem_descrip: "Debe especificar una Descripción",
			status: "Debe especificar un Status",
			item: "Debe existir al menois un detalle en el Movimiento",
		},
	});
	//Cargar el Index
	form = $("form").attr("id");
	if (form === undefined) {
		initCXPMovement();
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
	table = $("#tblSeatDetail_cxp").DataTable({
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
	listar_monedas();
}
//al seleccionar Empresa
$("#id_emp").on("change", async function (e) {
	e.preventDefault();
	id_emp = $(this).val();
	id_tmocxp = $("#id_tmocxp");
	id_tmocxp.empty();
	$("#tbody").empty();
	if (id_emp) {
		listar_tipos_mov_CXP(0, "id_tmocxp");
		id_tdo_cfg = await tip_doc_com(id_emp);
		id_moneda_cia = id_tdo_cfg["id_moneda"];
	}
});
//Validar Tipo de Movimiento
$("#id_tmocxp").on("change", async function (e) {
	id_tmocxp = $(this).val();
	$("#tbody").empty();
	const datos = new FormData();
	datos.append("id", id_tmocxp);
	$("#movem_number").prop("readonly", false);
	if (id_tmocxp) {
		try {
			const url = `${base_url}/CXPMovement/val_tmo`;
			const respuesta = await fetch(url, {
				method: "POST",
				body: datos,
			});
			const resultado = await respuesta.json();
			if (resultado) {
				if (resultado[0]["con_tmocxp"] == "N") {
					$("#movem_number").prop("readonly", false);
				} else {
					$("#movem_number").prop("readonly", true);
				}
			}
		} catch (error) {
			console.log("Ha ocurrido el siguiente error: " + error);
		}
	}
});
//Seleccionar proveedor
$("#id_ent").on("change", async function (e) {
	e.preventDefault();
	id_ent = $(this).val();
	$("#tbody").empty();
	if (id_ent) {
		const datosFetched = await tid_vend(id_ent);
		nom_cli = datosFetched["nom_ent"];
		$("#nom_ent").val(nom_cli);
	}
});
//Mostrar cambio al seleccionar Tipo de Moneda
$(document).on("change", "#id_moneda", async function (e) {
	e.preventDefault();
	fecha_comp = $("#fecha_comp").val();
	id_moneda = $(this).val();
	$("#tasa_cambio").val("");
	$("#tbody").empty();
	if (id_moneda) {
		//
		xTasaCambio = await xTasa(fecha_comp, id_moneda);
		$("#tasa_cambio").val(xTasaCambio);
	}
	$("#tasa_cambio").prop("readonly", true);
});
//Mostrar cambio al seleccionar Tipo de Moneda
$(document).on("change", "#fecha_comp", async function (e) {
	e.preventDefault();
	fecha_comp = $(this).val();
	id_moneda = $("#id_moneda").val();
	//
	xTasaCambio = await xTasa(fecha_comp, id_moneda);
	$("#tasa_cambio").val(xTasaCambio);
	$("#tasa_cambio").prop("readonly", true);
});
//Nuevo detalle de documentos a cancelar
$("#newdetail").on("click", function () {
	$("#modal_doc_pen_cxp").modal("show");
});
//Mostar los Documentos pendientes de Cuentas por Cobrar, tanto desde Banco Movimientos como desde Movimientos de Cuenas por Cobrar. José Vargas 28-08-2025 a las 10:10:00
$("#modal_doc_pen_cxp").on("show.bs.modal", function () {
	var url = "";
	id_emp = $("#id_emp").val();
	id_ent = $("#id_ent").val();
	fecha_comp = $("#fecha_comp").val();
	id_moneda = $("#id_moneda").val();

	url = `${base_url}/CXPDocument/doc_ped_cli`;
	datos = {
		id_emp: id_emp,
		id_cli: id_ent,
		fecha_comp: fecha_comp,
		id_moneda: id_moneda,
	};

	$.ajax({
		url: url,
		method: "POST",
		data: datos,
		dataType: "json",
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			$("#tblModalDocPend_cxp").DataTable({
				destroy: true,
				data: data,
				responsive: true,
				processing: true,
				columns: [
					{ data: "id_doc", title: "Id" },
					{ data: "tipo_codigo", title: "Código" },
					{ data: "nom_tdoc", title: "Descripción" },
					{ data: "num_tdo", title: "Número", className: "text-right", },
					{ data: "nro_control", title: "Control", className: "text-right", },
					{ data: "fecha_comp", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN), title: "Fecha Emi.", },
					{ data: "fecha_venci", render: $.fn.dataTable.render.moment(FROM_PATTERN,TO_PATTERN), title: "Fecha Venc.", },
					{ data: "codigo_moneda", title: "Moneda", className: "text-center", },
					{ data: "tasa_cambio", className: "text-right", render: DataTable.render.number(".", ",", 2), title: "Tasa Cambio",},
					{ data: null, className: "text-right", title: "Monto Doc.",
						render: function (row, type) {
							if (id_moneda_cia == id_moneda) {
								return `${format_number_with_dec_new(
									row.mon_doc_dom,
									2
								)}`;
							} else {
								return `${format_number_with_dec_new(
									row.mon_doc_for,
									2
								)}`;
							}
						},
					},
					{ data: null, className: "text-right", title: "Saldo Doc.",
						render: function (row, type) {
							if (id_moneda_cia == id_moneda) {
								return `${format_number_with_dec_new(
									row.sal_doc_dom,
									2
								)}`;
							} else {
								return `${format_number_with_dec_new(
									row.sal_doc_for,
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
			$(".loader").hide();
		},
		error: function (error) {
			$(".loader").hide();
			console.log("Error al cargar los datos: ");
		},
	});
});
//Function para imprimir Movimiento
function print_mov(e) {
	id = e.dataset.id;
	tipo = e.dataset.tipo;
	des = e.dataset.name;
	number = e.dataset.code;
	const url = `${base_url}/CXPMovement/print_movement`;
	title = "¿Está seguro que desea Imprimir el Movimiento " + des + "?";
	Swal.fire({
		icon: "question",
		title: title,
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
	}).then((result) => {
		if (result.isConfirmed) {
			window.open(
				`${base_url}/CXPMovement/print_movement/` + id,
				"_blank"
			);
		}
	});
}
//Guardar y/o Actualizar
$("#my_form").on("submit", function (e) {
	e.preventDefault();
	if ($(this).valid()) {
		var formData = $(this).serialize();
		const url = `${base_url}/CXPMovement/store`;
		$.ajax({
			url: url,
			type: "POST",
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
				console.log(PDOException.responseText);
			},
			success: function (data) {
				Swal.fire({
					title: data.title,
					text: data.msg,
					icon: data.icon,
				}).then((result) => {
					if (data.icon != "error") {
						window.location.href = `${base_url}/CXPMovement`;
					}
				});
			},
		});
	}
});
//Eliminar un registro
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
			const url = `${base_url}/CXPMovement/delete_row`;
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
//Mostrar registro
function show_row(id) {
	$("#tbody").empty();
	const url = `${base_url}/CXPMovement/showrow`;
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
			id_tmocxc = data[0]["id_tmocxp"];
			listar_tipos_mov_CXP(id_tmocxc, "id_tmocxp", true);
			movem_number = data[0]["movem_number"];
			$("#movem_number").val(movem_number);
			$("#movem_number").prop("readonly", true);
			id_ent = data[0]["id_cli"];
			$("#id_ent").val(id_ent);
			nom_cli = data[0]["nom_ent"];
			$("#nom_ent").val(nom_cli);
			$("#fecha_comp").val(data[0]["fecha_comp"]);
			$("#fecha_comp").prop("readonly", true);
			id_moneda = data[0]["id_moneda"];
			listar_monedas(id_moneda, true);
			xtasa = format_number_with_dec_new(data[0]["tasa_cambio"], 2);
			$("#tasa_cambio").val(xtasa);
			$("#tasa_cambio").prop("readonly", true);
			$("#movem_descrip").val(data[0]["movem_descrip"]);
			status = data[0]["status"];
			listar_status(status);
			movem_origen = data[0]["movem_origen"];
			if (movem_origen != "CXP") {
				$("#btnok").prop("disabled", true);
			}
			item = 0;
			x_mon_doc = 0;
			htmlTags = "";
			console.log(data);
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
				<td class='text-right'>${format_number_with_dec_new(xitem.monto_doc, 2)}</td>
				<td class='text-right'><input type="text" id="sal_doc${item}" name="sal_doc[]" class="form-control text-right text-xs sal_doc" readonly value="${format_number_with_dec_new(
					xitem.sal_doc + xitem.monto_doc,
					2
				)}"/></td>
				<td class='text-right'><input type="text" id="mon_can${item}" name="mon_can[]" class="form-control text-right text-xs fila-input" value="${format_number_with_dec_new(
					xitem.monto_doc,
					2
				)}"/></td>
				<td class="text-center"><input type="checkbox" name="id_check[]" class="form-check-input check-row-cxc" data-input-id="mon_can${item}" data-input-ret="mon_ret${item}" data-input-num="num_ret${item}" checked/> <button type="button" class="btn btn-danger btn-xs borrar_doc" title="Eliminar item"><i class="fa fa-trash"></i></button></td>`);
				//$("#tbody").append(htmlTags);
				table.row.add(htmlTags).draw();
				$("#item").val(item);
				UpdateDataTable("#tblSeatDetail_cxp");
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
