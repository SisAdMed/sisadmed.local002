let id_emp = "";
let pageTotal = 0;
let total = 0;
let sub_tot_fab = 0;
$().ready(function () {
	$("#fec_fin").val(GetTodayDate(0));
	listar_empresas(0, false, "Todas");
	id_emp = $("#id_emp").val();
	if (!id_emp) {
		listar_almacenes_ppal("");
		listar_ubicaciones("id_ubi", 0, "N", id_emp);
	}
});
$(document).on("change", "#id_emp", async function () {
	id_emp = $(this).val();
	if (id_emp) {
		config_fac = await tip_doc_fac(id_emp);
		id_alm_exc = config_fac["id_alm"];
		listar_almacenes_ppal(id_emp, id_alm_exc, id_alm_exc);
		listar_ubicaciones("id_ubi", 0, "N", id_emp);
	} else {
		listar_almacenes_ppal("");
		listar_ubicaciones("id_ubi", 0, "N", id_emp);
	}
	listar_marcas("", "id_fab");
});
$(document).on("change", "#id_alm", function () {
	listar_ubicaciones("id_ubi", 0, "N", id_emp);
	
});
function btn_search() {
	id_emp = $("#id_emp").val();
	id_alm = $("#id_alm").val();
	id_ubi = $("#id_ubi").val();
	fec_fin = $("#fec_fin").val();
	id_fab = $("#id_fab").val();
	//Nombre de Empresa
	nom_empresa = $('select[name="id_emp"] option:selected').text();
	//Nombre de Almacen
	var selectedText = "";
	$("#id_alm option:selected").each(function () {
		selectedText = $(this).text();
	});
	if (!fec_fin) {
		Swal.fire({
			icon: "error",
			title: "Oops...",
			text: "Debe especificar una Fecha de corte!",
		}).then((result) => {
			$("#fec_fin").focus();
		});
	} else if (id_alm.length == 0) {
		Swal.fire({
			icon: "error",
			title: "Oops...",
			text: "Debe especificar un Almacén!",
		}).then((result) => {
			$("#id_alm").focus();
		});
	} else {
		const url = `${base_url}/Productos/stock_ppal`;
		$.ajax({
			url: url,
			method: "POST",
			dataSrc: "",
			data: {
				id_emp: id_emp,
				id_alm: id_alm,
				id_ubi: id_ubi,
				fec_fin: fec_fin,
				id_fab: id_fab,
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (resultado) {
				data = JSON.parse(resultado);
				var tblControl = $("#tblTableConMovInv").DataTable({
					aProcessing: true,
					aServerSide: true,
					deferRender: true,
					data: data,
					destroy: true,
					clear: true,
					responsive: true,
					columns: [
						{
							data: "fec_fin",
							render: $.fn.dataTable.render.moment(
								FROM_PATTERN,
								TO_PATTERN
							),
							title: "Fecha",
						},
						{ data: "nom_prod", title: "Nombre Producto" },
						{ data: "cod2_prod", title: "Código" },
						{ data: "ref_prod", title: "Referencia" },
						{ data: "nom_fab", title: "Marca" },
						{
							data: "stock",
							title: "Stock",
							className: "text-right",
						},
						{
							data: "costo1",
							title: "Costo",
							className: "text-right",
						},
						{
							data: "valor",
							title: "Valoración",
							className: "text-right",
						},
						{
							data: null,
							title: "Ori",
							className: "text-right",
							visible: false,
							render: function (data, type, row) {
								return data.valor;
							},
						},
					],
					columnDefs: [
						{
							targets: [6, 7],
							render: $.fn.dataTable.render.number(".", ",", 2),
						},
					],
					drawCallback: function (setteings) {
						var api = this.api();
						let rows = api.rows({ page: "current" }).nodes();
						var last_fab = null;
						var sub_tot_fab = 0;
						//Total grupado por marca
						api.column(4, { page: "current" })
							.data()
							.each(function (group, i) {
								if (last_fab !== group) {
									if (i !== 0) {
										//Agregar fila de subtotal
										$(rows)
											.eq(i - 1)
											.after(
												'<tr class="group-subtotal">' +
													'<th class="text-right" colspan="7">Subtotal marca: ' +
													last_fab +
													"</th>" +
													'<th class="text-right">' +
													format_number_with_dec_new(
														sub_tot_fab,
														2
													) +
													"</th>" +
													"</tr>"
											);
									}
									sub_tot_fab = 0;
									last_fab = group;
								}
								//Sumar valor de la columna actual
								sub_tot_fab += parseFloat(
									api.column(8).data()[i]["valor"]
								);
								pageTotal += sub_tot_fab;
								total += sub_tot_fab;
							});
						//Agregar subtotal para el ultimo grupo
						$(rows)
							.eq(rows.length - 1)
							.after(
								'<tr class="group-subtotal">' +
									'<th class="text-right" colspan="7">Subtotal marca: ' +
									last_fab +
									"</th>" +
									'<th class="text-right">' +
									format_number_with_dec_new(sub_tot_fab, 2) +
									"</th>" +
									"</tr>"
							);
					},
					footerCallback: function (row, data, start, end, display) {
						//var grand_total = total

						var api = this.api();

						//Total de paginas y total gneral del total de los productos
						let intval = function (i) {
							return typeof i === "string"
								? i.replace(/[\$,]/g, "") * 1
								: typeof i === "number"
								? i
								: 0;
						};
						total = api
							.column(7)
							.data()
							.reduce((a, b) => intval(a) + intval(b), 0);

						pageTotal = api
							.column(7, { page: "current" })
							.data()
							.reduce((a, b) => intval(a) + intval(b), 0);

						//$(api.column(6).footer()).html('Total esta página: ' + pageTotal);
						$(api.column(7).footer()).html(
							"Total general:       " +
								format_number_with_dec_new(total, 2)
						);
					},
					// mostrar botones de exportacion
					dom: "lBfrtip",
					buttons: [
						{
							extend: "copyHtml5",
							text: "<i class='fa fa-copy'></i>",
							titleAttr: "Copiar",
							className: "btn btn-secondary",
						},
						{
							extend: "excelHtml5",
							text: "<i class='fa fa-file-excel'></i>",
							titleAttr: "Exportar a Excel",
							className: "btn btn-warning",
							filename:
								"Inventario_de_Consignados_" +
								nom_empresa +
								"_" +
								selectedText +
								"_" +
								new Date()
									.toISOString()
									.replace(/[\-\:\.]/g, ""),
						},
						{
							extend: "pdfHtml5",
							text: "<i class='fa fa-file-pdf'></i>",
							titleAttr: "Exportar a PDF",
							className: "btn btn-danger",
							filename:
								"Inventario_de_Consignados_" +
								nom_empresa +
								"_" +
								selectedText +
								"_" +
								new Date()
									.toISOString()
									.replace(/[\-\:\.]/g, ""),
						},
						{
							extend: "csvHtml5",
							text: "<i class='fa fa-file-text'></i>",
							titleAttr: "Exportar a CSV",
							className: "btn btn-primary",
							filename:
								"Inventario_de_Consignados_" +
								nom_empresa +
								"_" +
								selectedText +
								"_" +
								new Date()
									.toISOString()
									.replace(/[\-\:\.]/g, ""),
						},
					],
					lengthMenu: [
						[5, 10, 25, 50, -1],
						[5, 10, 25, 50, "Todos"],
					],
					iDisplayLength: -1,
					language: {
						url: `${base_url}/Assets/json/es-ES.json`,
					},
				});
			},
			complete: function () {
				$(".loader").hide();
			},
			error: function (xhr, status, error) {
				$(".loader").hide();
				console.log(error);
			},
		});
	}
}
function btn_clear() {
	$("#id_emp").val("");
	$("#id_alm").html("");
	$("#id_ubi").val("");
	$("#id_fab").val("");
	$("#id_prod").val("");
	$("#tblTableConMovInv").DataTable().clear();
	$("#tblTableConMovInv").DataTable().destroy();
}
function action(accion) {
	id = accion.dataset.id;
	if (id == "btn-search") {
		btn_search();
	} else if (id == "btn-clear") {
		btn_clear();
	}
}
