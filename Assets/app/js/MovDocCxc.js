let origen = GetURLParameter("tipo");
//Llenar select de empresas
$(document).ready(function () {
	//Listar Empresas
	listar_empresas(0);
	//Origen de los movimientos
	
});
//Al seleccionar una empresa
$("#id_emp").on("change", function () {
	id_emp = $(this).val();
	$("id_cli").empty();
	$("id_tdoc").empty();
	if( origen === "P"){
		listar_clientes("P", id_emp);
		listar_tipos_documentos_cxp(id_emp, "", "", "", "id_tdoc");
	}else{
		listar_clientes("C", id_emp);
		listar_tipos_documentos(id_emp, "", "", "", "id_tdoc");
	}
});
//Al seleccionar un Tipo de Documento
$("#num_tdo").on("change", function () {
	$("#id_cli").prop("disabled", true);
	$("#fec_ini").prop("disabled", true);
	$("#fec_fin").prop("disabled", true);
});
//Dependiendo del boton pulsado
function action_btn(a) {
	id = a.dataset.id;
	if (id === "btn-clear") {
		limpiar_campos();
	} else if (id === "btn-search") {
		show_rows();
	} else {
		expor_row_excel();
	}
}
//Limpiar campos del formulario
function limpiar_campos() {
	$("form")[0].reset();
	listar_empresas(0);
	$("#id_cli").empty();
	$("#id_tdoc").empty();
	$("#num_tdo").val("");
	$("#id_cli").prop("disabled", false);
	$("#fec_ini").prop("disabled", false);
	$("#fec_fin").prop("disabled", false);
	$("#fec_ini").val("");
	$("#fec_fin").val("");
}
//Mostrar registros
function show_rows() {
	$("#show_rows").html("");
	//Loading
	//Obtener valores
	var id_emp = $("#id_emp").val();
	var id_tdoc = $("#id_tdoc").val();
	var num_tdo = $("#num_tdo").val();
	var id_cli = $("#id_cli").val();
	var fec_ini = $("#fec_ini").val();
	var fec_fin = $("#fec_fin").val();
	const url = `${base_url}/MovDocCxc/show_rows`;
	//Obtener data
	$.ajax({
		url: url,
		method: "POST",
		dataType: "json",
		dataSrc: "",
		data: {
			id_emp: id_emp,
			id_tdoc: id_tdoc,
			num_tdo: num_tdo,
			id_cli: id_cli,
			fec_ini: fec_ini,
			fec_fin: fec_fin,
			origen: origen,
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			//Variables de rompimiento
			nombre_emp = "";
			nom_ent = "";
			id_cot = "";
			var tot_col = "7";
			//Crear tabla
			var table = $(
				'<table id="tbl_MovDocCxc" class="table2excel_with_colors">'
			);
			table.addClass(
				"table table-striped table-bordered table-condensed table-hover text-xs"
			);
			//Crear el encabezado de tabla (thead)
			var thead = $("<thead>");
			var headerRow = $('<tr class="text-center">');
			headerRow.append(
				'<th colspan="' +
					tot_col +
					'">Movimientos por Documento(s)</th>'
			);
			thead.append(headerRow);
			table.append(thead);
			if (fec_ini) {
				headerRow = $('<tr class="text-center">');
				headerRow.append("<th>Peíodo del al </th>");
				thead.append(headerRow);
				table.append(thead);
			}
			//Agregar encabezado de empresa
			var tbody = $("<tbody>");
			var row = "";
			var tot_col = "7";
			var sal_doc = 0;
			$.each(data, function (item, rows) {
				if (nombre_emp != rows.nombre_emp) {
					row = $("<tr>");
					row.append('<th colspan="' + tot_col + '" class="text-center" style="background-color:#00FFFF">' + rows.nombre_emp + "</th>" );
					tbody.append(row);
				}
				if (nom_ent != rows.nom_ent) {
					row = $("<tr>");
					row.append('<th colspan="' + tot_col + '"  class="text-center">' + rows.nom_ent + "</th>" );
					tbody.append(row);
				}
				if (id_cot != rows.id_cot) {
					//Titulos
					row = $("<tr>");
					row.append('<th class="text-center">Fecha</th>');
					row.append('<th class="text-center">Tipo</th>');
					row.append("<th class='text-right'>Número</th>");
					row.append("<th>Descripción</th>");
					row.append("<th class='text-right'>Debe</th>");
					row.append("<th class='text-right'>Haber</th>");
					row.append("<th class='text-right'>Saldo</th>");
					tbody.append(row);
					row = $('<tr style="background-color:#00FFFF">');
					var cli_pro = origen === "P" ? " Proveedor: " : " Cliente: ";
					var detRow ="Documento: " + rows.tipo_codigo + " - " + rows.num_tdo + cli_pro + rows.nom_ent;
					row.append("<th colspan='" + (tot_col - 1) + "' >" + detRow + "</th>" );
					mon_doc = format_number_with_dec_new(rows.mon_doc, 2);
					row.append("<th class='text-right' >" + mon_doc + "</th>");
					tbody.append(row);
					sal_doc = rows.mon_doc;
				}
				//Mostrar registros
				//Formato de Fecha del Movimiento
				var xfecha_mov = rows.fecha_mov.split("-");
				var xfecha_mov_show = xfecha_mov[2] + "-" + xfecha_mov[1] + "-" + xfecha_mov[0];
				//Calculo del Saldo del documento
				if(origen === "C"){
					sal_doc += rows.debe;
					sal_doc -= rows.haber;
				}else{
					sal_doc -= rows.debe;
					sal_doc += rows.haber;
				}
				row = $("<tr>");
				row.append(`<td class="text-center">${xfecha_mov_show}</th>`);
				row.append(`<td>${rows.cod_tmocxc}</th>`);
				row.append(`<td class="text-right">${rows.movem_number}</th>`);
				if (rows.movem_origen != "CXC" && rows.movem_origen != "CXP") {
					row.append(`<td><a href="${base_url}/BanMovim/edit/${rows.movem_origen}" target="_blank">${rows.movem_descrip}</a></th>`);
				} else if(rows.movem_origen === "CXC"){ 
					row.append(`<td><a href="${base_url}/CXCMovement/edit/${rows.id_movement}" target="_blank">${rows.movem_descrip}</a></th>`);
				} else {
					row.append(`<td><a href="${base_url}/CXPMovement/edit/${rows.id_movement}" target="_blank">${rows.movem_descrip}</a></th>`);
				}
				if (rows.debe != 0) {
					row.append(`<td class="text-right">${format_number_with_dec_new(rows.debe,2)}</th>`);
				} else {
					row.append(`<td class="text-right"></th>`);
				}
				if (rows.haber != 0) {
					row.append(`<td class="text-right">${format_number_with_dec_new(rows.haber,2)}</th>`);
				} else {
					row.append(`<td class="text-right"></th>`);
				}

				row.append(`<td class="text-right">${format_number_with_dec_new(sal_doc,2)}</th>`);
				tbody.append(row);
				//Actualzar nom_empresa
				nombre_emp = rows.nombre_emp;
				//Actualizar nom_ent
				nom_ent = rows.nom_ent;
				//Acutalizar id_cot
				id_cot = rows.id_cot;
			});
			//Agregar row al body
			table.append(tbody);
			//Agregar la tabla al div show_row
			$("#show_rows").append(table);
		},
		complete: function () {
			$(".loader").hide();
		},
		error: function (xhr) {
			$(".loader").hide();
			console.log(xhr.responseText);
		},
	});
}
function expor_row_excel() {
	var table = $("#tbl_MovDocCxc");
	if (table && table.length) {
		var preserveColors = table.hasClass("table2excel_with_colors")
			? true
			: false;
		$(table).table2excel({
			exclude: ".noExl",
			name: "Excel Document Name",
			filename:
				"Movimientos por Documentos" +
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
