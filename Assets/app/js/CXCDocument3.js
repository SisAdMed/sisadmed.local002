let tot_mon_exe = 0;
let tot_mon_bas = 0;
let tot_mon_iva = 0;
let tot_mon_ret_iva = 0;
let tot_mon_dif_iva = 0;
let tot_mon_cob = 0;
let rep_fac_pag = false; 
init();
function init() {
	$(".condi").hide();
	$(".rep_fac_pag").hide()
	$("#btn-search").hide();
	listar_empresas(0);
	$("#rangedate").daterangepicker({
		locale: {
			format: "DD/MM/YYYY",
			separator: " - ",
			applyLabel: "Aplicar",
			cancelLabel: "Cancelar",
			fromLabel: "Desde",
			toLabel: "Hasta",
			customRangeLabel: "Personalizado",
			daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
			monthNames: [
				"Enero",
				"Febrero",
				"Marzo",
				"Abril",
				"Mayo",
				"Junio",
				"Julio",
				"Agosto",
				"Septiembre",
				"Octubre",
				"Noviembre",
				"Diciembre",
			],
			firstDay: 1, // Monday as the first day of the week
		},
	});
}
//Saber si selecciono rango de fecha
$("#rangedate").on('change', function(){
	rep_fac_pag = true;
})
//Limpiar campos
$(document).on("click", "#btn-clear", function () {
	$("form")[0].reset();
	$(".rep_fac_pag").hide();
	$("#tblCXCedo_cuenta").empty();
	$("#tblRepFecPag").empty();
});
//
$(document).on("change", "#id_emp", function () {
	id_emp = $(this).val();
	$("#id_cli").val("");
	$("#nom_cli").val("");
	$(".rep_fac_pag").hide();
	$("#tblCXCedo_cuenta").empty();
	$("#tblRepFecPag").empty();
	if (id_emp) {
		$(".condi").show();
		$("#btn-search").show();
		
	} else {
		$(".condi").hide();
	}
});
//Validar que se haya seleccionado una empresa
$(document).on("change", "#id_cli", async function () {
	const datosFetched = await tid_vend(id_cli);
	$("#nom_cli").val(datosFetched["nom_ent"]);
	$(".rep_fac_pag").show();

	$("#tblCXCedo_cuenta").empty();
	$("#tblRepFecPag").empty();
});
//Buscar registros
$(document).on("click", "#btn-search", function () {
	const url = `${base_url}/CXCDocument/edo_cuenta_data`;
	nom_cli = $("#nom_cli").val();
	nom_empresa = $("#id_emp option:selected").text();
	TCapttion = nom_empresa + ' <br> ' + nom_cli;
	id_cli = $("#id_cli").val();
	$("#tblCXCedo_cuenta").empty();
	$("#tblRepFecPag").empty();
	if (id_emp === "") {
		Swal.fire({
			icon: "error",
			title: "Oops...",
			text: `Debe indicar una Empresa`,
			footer: `<b class="text-danger">Estos datos son importantes para poder generar la consulta</b>`,
		});
	}
	//buscar los registros solicitados
	if (id_emp != "" && id_cli) {
		ori = 0;
		$.ajax({
			url: url,
			method: "POST",
			data: { id_emp: id_emp, id_cli: id_cli, ori: ori },
			dataType: 'json',
			beforeSend: function () {
				loader.show();
			},
			success: function (data) {
				if (data && id_cli) {
					$("#tblCXCedo_cuenta").append(`<caption>${TCapttion}</caption>`);
					var table = $("#tblCXCedo_cuenta").DataTable({
						data: data,
						dataType: "json",
						paging: false,
						destroy: true,
						clear: true,
						order: [[1, "asc"]],
						columns: [
							{ data: "nom_tdoc", title: "Nombre" },
							{ data: "fecha_comp", title: 'Fecha', className: 'text-right', render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN)},
							{ data: 'cod_diascre', title: 'Días Créd.', className: 'text-right'},
							{ data: 'dias_calle', title: 'Días Calle', className: 'text-right'},
							{ data: 'num_tdo', title: 'Número', className: 'text-right'},
							{ data: 'mon_exe_dom', title: 'Exento', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
							{ data: 'mon_base_dom', title: 'Base', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
							{ data: 'mon_iva_dom', title: 'Mon IVA', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
							{ data: 'ret_iva_dom', title: 'Reten. IVA', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
							{ data: 'dif_iva_dom', title: 'Dif. IVA', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
							{ data: 'por_cob_dom', title: 'Por Cobrar', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
							{ data: 'mon_exe_for', title: 'Exento', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
							{ data: 'mon_base_for', title: 'Base', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
							{ data: 'mon_iva_for', title: 'Mon IVA', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
							{ data: 'ret_iva_for', title: 'Reten. IVA', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
							{ data: 'dif_iva_for', title: 'Dif. IVA', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
							{ data: 'por_cob_for', title: 'Por Cobrar', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
							{ data: 'tasa_cambio', title: 'Tasa', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
							{ data: 'status_doc', title: 'Status', className: 'text-center'},
							{ data: 'motivo', title: 'Motivo', className: 'text-center', render: $.fn.dataTable.render.number('.', ',', 2)}
						],
						fnFooterCallback: function (row, data, start, end, display) {
							var api = this.api();
							var intVal = function (i) {
								return typeof i === "string"
									? i.replace(/[\$,]/g, "") * 1
									: typeof i === "number"
									? i
									: 0;
							};
							var total2 = api
								.column(10)
								.data()
								.reduce(function (a, b) {
									return intVal(a) + intVal(b);
								}, 0);
							var total3 = api
								.column(16)
								.data()
								.reduce(function (a, b) {
									return intVal(a) + intVal(b);
								}, 0);
							var footer = $(this).append(
								`<tfoot>
									<tr>
										<th colspan="5" class="text-right">Total:</th>
										<th colspan="6" class="text-right">${format_number_with_dec_new(total2, 2)}</th>
										<th colspan="6" class="text-right">${format_number_with_dec_new(total3, 2)}</th>
										<th colspan="3" class="text-right"></th>
									</tr>
								</tfoot>`
							);
						},
						language: {
							url: `${base_url}/Assets/json/es-ES.json`,
						},
					});

					if(rep_fac_pag){
						const url1 = `${base_url}/CXCDocument/rep_fac_pag_data`;
						var tabla = $("#tblRepFecPag");
						var periodo = $("#rangedate").val();
						var picker = $("#rangedate").data("daterangepicker");
						if(picker){
							var fec_ini = picker.startDate.format("YYYY-MM-DD");
							var fec_fin = picker.endDate.format("YYYY-MM-DD");
						}
						var tot_col = 6;
						tabla.attr("style", "width:100%");
						tabla.attr("class", "display responsive nowrap table table-hover text-xs");
						//Crear la etiqueta <thead>
						var thead = $("<thead></thead>");
						var tr1 = $("<tr></tr>");
						tr1.append(`<th colspan=${tot_col} class="text-center" style="background-color:#00FFFF">Reporte de Promedio de Facturas Canceladas correspondiente al período <br>${periodo}</th>` );
						thead.append(tr1);
						tabla.append(thead);
						$.ajax({
							url: url1,
							method: 'POST',
							data: {id_emp: id_emp, fec_ini: fec_ini, fec_fin: fec_fin, id_cli: id_cli},
							dataType: 'json',
							beforeSend: function(){
								loader.show();
							}, 
							complete: function(){
								loader.hide();
							},
							success: function(rep_fac_pag_data){
								var cuerpo = $("<tbody></tbody>");
								//Crear Titulos
								tr1 = $("<tr></tr>");
								tr1.append('<th>Nombre</th>');
								tr1.append('<th class="text-right">Número</th>');
								tr1.append("<th class='text-center'>Fec. Fact.</th>");
								tr1.append("<th class='text-center'>Fec. Pago</th>");
								tr1.append("<th class='text-right'>Días Cred.</th>");
								tr1.append("<th class='text-right'>Días Pago.</th>");
								cuerpo.append(tr1);
								tabla.append(cuerpo);
								xrows = 0;
								xdias = 0;
								$(rep_fac_pag_data).each(function (e, yitem) {
									tr1 = $("<tr></tr>");
									tr1.append(`<td>${yitem.nom_tdoc}</td>`);
									tr1.append(
										`<td class="text-right">${yitem.num_tdo}</td>`
									);
									if (yitem.fec_fact) {
										fecha = yitem.fec_fact.split("-");
									}
									tr1.append(
										`<td class="text-center">${fecha[2]}-${fecha[1]}-${fecha[0]}</td>`
									);
									if (yitem.fec_cob) {
										fecha = yitem.fec_cob.split("-");
									}
									tr1.append(
										`<td class="text-center">${fecha[2]}-${fecha[1]}-${fecha[0]}</td>`
									);
									tr1.append(
										`<td class="text-right">${yitem.cod_diascre}</td>`
									);
									tr1.append(
										`<td class="text-right">${yitem.dias_pag}</td>`
									);
									xrows++;
									xdias += yitem.dias_pag;
									cuerpo.append(tr1);
								});
								tabla.append(cuerpo);
								//Dias promedio
								tr1 = $("<tr></tr>");
								tr1.append(`<th colspan="${tot_col-1}" class="text-right">Promedio en días</th>`);
								if(xrows > 0){
									tr1.append(`<th class="text-right">${parseInt(xdias / xrows)}</th>`);
								}else{
									tr1.append(`<th class="text-right">0</th>`);
								}
								
								cuerpo.append(tr1);
								tabla.append(cuerpo);
							}
						})

					}
					

					
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
				loader.hide();
			},
			error: function (xhr, status, error) {
				loader.hide();
				console.log(error);
			},
		});
	}else if(id_emp != "" && !id_cli){
		$.ajax({
			url: url,
			method: 'POST',
			data: {id_emp: id_emp, id_cli: 0, ori: 2},
			dataType: 'json',
			beforeSend: function(){
				loader.show();
			}, complete: function(){
				loader.hide();
			}, success: function(data){
				var detail = '';
				//var nombre_emp = data[0]["nombre_emp"];
				//Selleccionar tabla
				var tabla = $("#tblCXCedo_cuenta");
				tabla.append(
					$(
						"<tfoot><tr><th></th><th>TOTAL:</th><th></th><th></th><th></th><th></th><th></th><th></th></tfoot>"
					)
				);
				var report = "Estado de Cuentas Empresa " + nom_empresa;
				//Crear DataTable
				$("#tblCXCedo_cuenta").append(`<caption>${TCapttion}</caption>`);
				tabla.DataTable({
					data: data,
					dataType: "json",
					paging: false,
					order: [[7, "desc"]],
					destroy: true,
					clear: true,
					columns: [
						{ data: "nom_ent", title: "Cliente" },
						{ data: "des_diascre", title: "Días Créd" },
						{ data: "mon_exe_for", title: "Exento", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 2), },
						{ data: "mon_base_for", title: "Base", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 2), },
						{ data: "mon_iva_for", title: "Mon IVA", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 2), },
						{ data: "ret_iva_for", title: "Reten IVA", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 2), },
						{ data: "dif_iva_for", title: "Dif IVA", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 2), },
						{ data: "por_cob_for", title: "Por Cobrar", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 2), },
					],
					columnDefs: [
						{ orderable: false, targets: [1, 2, 3, 4, 5, 6] },
					],
					footerCallback: function (row, data, start, end, display) {
						var api = this.api();
						var total2 = api
							.column(2)
							.data()
							.reduce(function (a, b) {
								return parseFloat(a) + parseFloat(b);
							}, 0);
						var total3 = api
							.column(3)
							.data()
							.reduce(function (a, b) {
								return parseFloat(a) + parseFloat(b);
							}, 0);
						var total4 = api
							.column(4)
							.data()
							.reduce(function (a, b) {
								return parseFloat(a) + parseFloat(b);
							}, 0);
						var total5 = api
							.column(5)
							.data()
							.reduce(function (a, b) {
								return parseFloat(a) + parseFloat(b);
							}, 0);
						var total6 = api
							.column(6)
							.data()
							.reduce(function (a, b) {
								return parseFloat(a) + parseFloat(b);
							}, 0);
						var total7 = api
							.column(7)
							.data()
							.reduce(function (a, b) {
								return parseFloat(a) + parseFloat(b);
							}, 0);
						$(api.column(2).footer()).html(
							format_number_with_dec_new(total2, 2)
						);
						$(api.column(3).footer()).html(
							format_number_with_dec_new(total3, 2)
						);
						$(api.column(4).footer()).html(
							format_number_with_dec_new(total4, 2)
						);
						$(api.column(5).footer()).html(
							format_number_with_dec_new(total5, 2)
						);
						$(api.column(6).footer()).html(
							format_number_with_dec_new(total6, 2)
						);
						$(api.column(7).footer()).html(
							format_number_with_dec_new(total7, 2)
						);
					},
					language: {
						url: `${base_url}/Assets/json/es-ES.json`,
					},
					// mostrar botones de exportacion
					dom: "lBfrtip",
					buttons: [
						{
							extend: "copyHtml5",
							text: "<i class='fa fa-copy'></i>",
							titleAttr: "Copiar",
							className: "btn btn-secondary",
							footer: true,
							title:
								report +
								new Date()
									.toISOString()
									.replace(/[\-\:\.]/g, ""),
						},
						{
							extend: "excelHtml5",
							text: "<i class='fa fa-file-excel'></i>",
							titleAttr: "Exportar a Excel",
							className: "btn btn-warning",
							footer: true,
							title:
								report +
								new Date()
									.toISOString()
									.replace(/[\-\:\.]/g, ""),
						},
						{
							extend: "pdfHtml5",
							text: "<i class='fa fa-file-pdf'></i>",
							titleAttr: "Exportar a PDF",
							className: "btn btn-danger",
							footer: true,
							title:
								report +
								new Date()
									.toISOString()
									.replace(/[\-\:\.]/g, ""),
						},
						{
							extend: "csvHtml5",
							text: "<i class='fa fa-file-text'></i>",
							titleAttr: "Exportar a CSV",
							className: "btn btn-primary",
							footer: true,
							title:
								report +
								new Date()
									.toISOString()
									.replace(/[\-\:\.]/g, ""),
						},
					],
				});
			}
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
			footer: true
		});
	}
	/*
	var table = $("#tblRepFecPag");
	if (table && table.length) {
		var preserveColors = table.hasClass("table2excel_with_colors")
			? true
			: false;
		$(table).table2excel({
			exclude: ".noExl",
			name: "Excel Document Name",
			filename:
				"Promedio_dias_pago_" +
				new Date().toISOString().replace(/[\-\:\.]/g, "") +
				".xls",
			fileext: ".xls",
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true,
			preserveColors: preserveColors,
			footer: true,
		});
	}
		*/
}