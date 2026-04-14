init();
function init() {
	$("#condi").hide();
	$("#btn-search").hide();
	listar_empresas(0);
}
//Limpiar campos
$(document).on("click", "#btn-clear", function () {
	$("form")[0].reset();
	$("#tblCXCedo_cuenta").empty();
});
//
$(document).on("change", "#id_emp", function () {
	id_emp = $(this).val();
	$("#id_cli").val("");
	$("#nom_cli").val("");
	if (id_emp) {
		$("#condi").show();
		$("#btn-search").show();
		$("#tblCXCedo_cuenta").empty();
	} else {
		$("#condi").hide();
	}
});
//Validar que se haya seleccionado una empresa
$(document).on("change", "#id_cli", async function () {
	const datosFetched = await tid_vend(id_cli);
	$("#nom_cli").val(datosFetched["nom_ent"]);
	$("#tblCXCedo_cuenta").empty();
});
//Buscar registros
$(document).on("click", "#btn-search", function () {
	const url = `${base_url}/CXPDocument/edo_cuenta_data`;
	nom_cli = $("#nom_cli").val();
	id_cli = $("#id_cli").val();
	$("#tblCXCedo_cuenta").empty();
	if (id_emp === "") {
		Swal.fire({
			icon: "error",
			title: "Oops...",
			text: `Debe indicar una Empresa`,
			footer: `<b class="text-danger">Estos datos son importantes para poder generar la consulta</b>`,
		});
	}
	//buscar los registros solicitados
	if (id_emp != "") {
		$.ajax({
			url: url,
			method: "POST",
			data: { id_emp: id_emp, id_cli: id_cli },
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				response = JSON.parse(data);
				if (response) {
					var tcliente_previus = "";
					var d_ent_previus = "";
					//Selleccionar tabla
					var tabla = $("#tblCXCedo_cuenta");
					//Crear la etiqueta <thead>
					var thead = $("<thead></thead>");
					var tr1 = $("<tr></tr>");
					//Creamos fila de Encabezado de Empresa
					tr1.append(
						'<th colspan="21" class="text-center" style="background-color:#00FFFF">' +
							response[0]["nombre_emp"] +
							"</th>"
					);
					//Agregar la fila al thead
					thead.append(tr1);
					tabla.append(thead);
					$(response).each(function (e, eitem) {
						var cuerpo = $("<tbody></tbody>");
						if (tcliente_previus != eitem.id_ent) {
							//Creamos Fila de Encabezado de Cliente
							tr1 = $("<tr></tr>");
							tr1.append(
								'<th colspan="21" class="text-center" style="background-color:#808d92">' +
									eitem.nom_ent +
									"</th>"
							);
							//Agregar la fila al thead
							cuerpo.append(tr1);
							//Creamos Fila de Encabezado de Moneda
							tr1 = $("<tr></tr>");
							tr1.append('<th colspan="7"></th>');
							tr1.append(
								'<th colspan="6" class="text-center" style="background-color:#00FFFF">Monto de Bolívares</th>'
							);
							tr1.append(
								'<th colspan="6" class="text-center" style="background-color:#808d92">Monto en Dólares</th>'
							);
							tr1.append('<th colspan="3"></th>');
							//Agregar la fila al thead
							cuerpo.append(tr1);
							//crear la fila de Detalles
							var tr = $("<tr></tr>");
							tr.append('<th class="text-tight">Ítem</th>');
							tr.append('<th class="tid">Id</th>');
							tr.append("<th>Tipo</th>");
							tr.append("<th>Nombre</th>");
							tr.append("<th>Fecha</th>");
							tr.append('<th class="text-right">Días Créd.</th>');
							tr.append('<th class="text-right">Días Calle</th>');
							tr.append('<th class="text-right">Número</th>');
							tr.append('<th class="text-right">Exento</th>');
							tr.append('<th class="text-right">Base</th>');
							tr.append('<th class="text-right">Mon IVA</th>');
							tr.append('<th class="text-right">Reten. IVA</th>');
							tr.append('<th class="text-right">Dif. IVA</th>');
							tr.append('<th class="text-right">Por Pagar</th>');
							tr.append('<th class="text-right">Exento</th>');
							tr.append('<th class="text-right">Base</th>');
							tr.append('<th class="text-right">Mon IVA</th>');
							tr.append('<th class="text-right">Reten. IVA</th>');
							tr.append('<th class="text-right">Dif. IVA</th>');
							tr.append('<th class="text-right">Por Pagar</th>');
							tr.append('<th class="text-right">Tasa</th>');
							tr.append('<th class="text-center">Status</th>');
							//Agregar la fila al thead
							cuerpo.append(tr);
							//Aagregar Thead a la tabla
							tabla.append(cuerpo);
							//Detalle de Cliente
							var total_por_cob_dom = 0;
							var total_por_cob_for = 0;
							$.ajax({
								url: url,
								method: "POST",
								data: { id_emp: id_emp, id_cli: eitem.id_ent },
								success: function (data_ent) {
									response_ent = JSON.parse(data_ent);
									$(response_ent).each(function (v, xitem) {
										var ret_iva_dom = 0;
										var por_cob_dom = 0;
										var ret_iva_for = 0;
										var por_cob_for = 0;
										var fecha;
										var xtasa = xitem.tasa_doc;
										if (xtasa == 1) {
											xtasa = xitem.tasa_dia;
										}
										if (xitem.fecha_comp) {
											fecha = xitem.fecha_comp.split("-");
										}
										if (xitem.especial_contrib == 1) {
											ret_iva_dom =
												xitem.mon_iva_dom * (75 / 100);
											ret_iva_for =
												xitem.mon_iva_for * (75 / 100);
										}
										if (xitem.abonado == 1) {
											por_cob_dom = parseFloat(
												xitem.sal_doc_dom
											);
											por_cob_for = parseFloat(
												xitem.sal_doc_for
											);
										} else {
											por_cob_dom = parseFloat(
												xitem.sal_doc_dom - ret_iva_dom
											);
											por_cob_for = parseFloat(
												xitem.sal_doc_for - ret_iva_for
											);
										}

										total_por_cob_dom += por_cob_dom;
										total_por_cob_for += por_cob_for;
										var status_doc = "VIGENTE";
										if (
											parseInt(xitem.dias_calle) >
											parseInt(xitem.cod_diascre)
										) {
											status_doc = "VENCIDO";
										}
										var fila = $("<tr></tr>");
										fila.append(
											'<td class="text-right">' +
												xitem.item +
												"</td>"
										);
										fila.append(
											'<td class="tid">' +
												xitem.id_cot +
												"</td>"
										);
										fila.append(
											"<td>" + xitem.tipo_codigo + "</td>"
										);
										fila.append(
											"<td>" + xitem.nom_tdoc + "</td>"
										);
										fila.append(
											"<td>" +
												fecha[2] +
												"-" +
												fecha[1] +
												"-" +
												fecha[0] +
												"</td>"
										);
										fila.append(
											'<td class="text-center">' +
												xitem.cod_diascre +
												"</td>"
										);
										fila.append(
											'<td class="text-center">' +
												xitem.dias_calle +
												"</td>"
										);
										fila.append(
											'<td class="text-right">' +
												xitem.num_tdo +
												"</td>"
										);
										fila.append(
											'<td class="text-right">' +
												format_number_with_dec_new(
													xitem.mon_exe_dom,
													2
												) +
												"</td>"
										);
										fila.append(
											'<td class="text-right">' +
												format_number_with_dec_new(
													xitem.mon_base_dom,
													2
												) +
												"</td>"
										);
										fila.append(
											'<td class="text-right">' +
												format_number_with_dec_new(
													xitem.mon_iva_dom,
													2
												) +
												"</td>"
										);
										fila.append(
											'<td class="text-right">' +
												format_number_with_dec_new(
													ret_iva_dom,
													2
												) +
												"</td>"
										);
										fila.append(
											'<td class="text-right">' +
												format_number_with_dec_new(
													xitem.mon_iva_dom -
														ret_iva_dom,
													2
												) +
												"</td>"
										);
										fila.append(
											'<td class="text-right">' +
												format_number_with_dec_new(
													por_cob_dom,
													2
												) +
												"</td>"
										);
										fila.append(
											'<td class="text-right">' +
												format_number_with_dec_new(
													xitem.mon_exe_for,
													2
												) +
												"</td>"
										);
										fila.append(
											'<td class="text-right">' +
												format_number_with_dec_new(
													xitem.mon_base_for,
													2
												) +
												"</td>"
										);
										fila.append(
											'<td class="text-right">' +
												format_number_with_dec_new(
													xitem.mon_iva_for,
													2
												) +
												"</td>"
										);
										fila.append(
											'<td class="text-right">' +
												format_number_with_dec_new(
													ret_iva_for,
													2
												) +
												"</td>"
										);
										fila.append(
											'<td class="text-right">' +
												format_number_with_dec_new(
													xitem.mon_iva_for -
														ret_iva_for,
													2
												) +
												"</td>"
										);
										fila.append(
											'<td class="text-right">' +
												format_number_with_dec_new(
													por_cob_for,
													2
												) +
												"</td>"
										);
										fila.append(
											'<td class="text-right">' +
												format_number_with_dec_new(
													xtasa,
													4
												) +
												"</td>"
										);
										fila.append(
											'<td class="text-center">' +
												status_doc +
												"</td>"
										);
										cuerpo.append(fila);
									});
									var tpie = $(
										'<tr style="background-color:#00FFFF"></t>'
									);
									tpie.append(
										'<th colspan="7" class="text-right">TOTAL</th>'
									);
									tpie.append(
										'<th colspan="6" class="text-right">' +
											format_number_with_dec_new(
												total_por_cob_dom,
												2
											) +
											"</th>"
									);
									tpie.append(
										'<th colspan="6" class="text-right">' +
											format_number_with_dec_new(
												total_por_cob_for,
												2
											) +
											"</th>"
									);
									tpie.append('<th colspan="3"></th>');
									cuerpo.append(tpie);
									$(".tid").hide();
								},
							});
						}
						tcliente_previus = eitem.id_ent;
					});
				} else {
					Swal.fire({
						icon: "error",
						title: "Oops...",
						html:
							'No existen registros para el cliente <b><span class="text-danger">' +
							nom_cli +
							"</span></b>",
					});
				}
			},
			complete: function (data) {
				$(".loader").hide();
			},
			error: function (xhr, status, error) {
				$(".loader").hide();
				console.log(error);
			},
		});
	}
});
function report_to_excel(e) {
	var table = $("#tblCXCedo_cuenta");
	if (table && table.length) {
		var preserveColors = table.hasClass("table2excel_with_colors")
			? true
			: false;
		$(table).table2excel({
			exclude: ".noExl",
			name: "Excel Document Name",
			filename:
				"Edo_de_Cuentas_" +
				new Date().toISOString().replace(/[\-\:\.]/g, "") +
				".xls",
			fileext: ".xls",
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true,
			preserveColors: preserveColors,
		});
	}
}
