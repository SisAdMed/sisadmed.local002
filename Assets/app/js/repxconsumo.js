//Variables
//Para lograr mostrar el Modal de Productos
origen_COM = 1;
let tabla = '';
$().ready(function(){
    //Listar empresas
    listar_empresas();
    //Fecha Desde
    $('#fec_ini').val(GetTodayDate(0));
    $("#fec_ini").trigger("change");
    listar_marcas(0);
    listar_tipos_clientes(0);
});
//Calcular Fecha Hasta si cambia la Fecha desde
$('#fec_ini').on('change', function(){
    var fec_ini = $(this).val();
	var xfecha = fec_ini.split("-");
	var fec_fin = getLastDayOfMonth(xfecha[0], xfecha[1] - 1);
	fec_fin = xfecha[0] + "-" + xfecha[1] + "-" + fec_fin;
	$("#fec_fin").val(fec_fin);
})
//Buscar nombre del Cliente y mostrar
$('#id_cli').on('change', async function(){
    id_cli = $(this).val();
    const datosFetched = await tid_vend(id_cli);
    nom_cli = datosFetched['nom_ent'];
    $('#nom_cli').val(nom_cli);
})
//Al cambiar la empresa limpiar los campos
$('#id_emp').on('change', function(){
	//Fecha Desde
	$("#fec_ini").val(GetTodayDate(0));
	$("#fec_ini").trigger("change");
    $('#id_cli').val('');
    listar_marcas(0);
	listar_tipos_clientes(0);
    $('#id_prod').val('');
    $('#nom_prod').val('');
})
//Limpiar todos los campos del formulario
function refresh_form(){
    listar_empresas();
	//Fecha Desde
	$("#fec_ini").val(GetTodayDate(0));
	$("#fec_ini").trigger("change");
	$("#id_cli").val("");
    $("#nom_cli").val("");
	$("#id_fab").val("").trigger("change");
    listar_marcas(0);
	listar_tipos_clientes(0);
	$("#id_prod").val("");
	$("#nom_prod").val("");
}
//Acciones de botonoes
function action(e){
    accion = e.dataset.id;
    if (accion == "btn-clear") {
		refresh_form();
	}else{
        show_data()
    }
}
//Buscar data para mostrar
function show_data(){
	let report = 'Reporte por Consumo ';
    id_emp = $('#id_emp').val();
    fec_ini = $('#fec_ini').val();
    fec_fin = $('#fec_fin').val();
    id_cli = $('#id_cli').val();
    id_fab = $('#id_fab').val();
    id_tipocliente = $('#id_tipocliente').val();
	$("#tblresult").html("");
	//
	//
    if(!id_emp){
        if (!id_emp) {
            Swal.fire({
                title: "Error",
                text: "Debe especificar una Empresa",
                icon: "error",
            });
        }
        return false;
    }
    //Buscar datos luego de validar
    const url = `${base_url}/Productos/repxconsumo_data`;
	//Nombre del Reporte si se va a Exportar
	//Empresa
	report += $('#id_emp option:selected').text();
	//Período
	xfec_ini = fec_ini.split('-');
	xfec_fin = fec_fin.split('-');
	report += ` Desde ${xfec_ini[2]}-${xfec_ini[1]}-${xfec_ini[0]} Hasta ${xfec_fin[2]}-${xfec_fin[1]}-${xfec_fin[0]} ` ;
	//Cliente
	if(id_cli){
		report += ` Cliente ${nom_cli} `;
	}
	//Marca
	if(id_fab){
		 var nom_fab = $('#id_fab option:selected').map(function() {
      		return $(this).text();
    	}).get();
		report += ` Marca ${nom_fab} `;
	}
	//Tipo de Cliente
	if(id_tipocliente){
		report += ` Tipo de Cliente ${$('#id_tipocliente option:selected').text()} `;
	}
    $.ajax({
        url: url,
        method: 'POST',
        data: {id_emp: id_emp, fec_ini: fec_ini, fec_fin: fec_fin, id_cli: id_cli, id_fab: id_fab, id_tipocliente: id_tipocliente},
        dataSrc: '',
        dataType: 'json',
        beforeSend: function(){
            loader.show();
        },
        complete: function(){
            loader.hide();
        },
        error: function(error){
            loader.hide();
            console.log('Ha ocurrido el siguiente error: ', error.responseText);
        },
        success: function(data){
            if(data){
				const result = pivotDataForDataTable(data);
				const numColumns = result.columns.length;
				if ($.fn.DataTable.isDataTable("#tblresult")) {
					$("#tblresult").DataTable().clear().draw();
					$("#tblresult").dataTable().fnDestroy();
					$("#tblresult").empty();
				}
				//Crear foot dinamicamente
				let footerRow = '<tr>';
				for(let m = 0; m < numColumns; m++) {
					//Creamos un <th> por cada columna que tendrá la tabla
					footerRow += '<th></th>';
				}
				footerRow += '</tr>';
				//Agregamos el tfoot a la tabla anstes de inicializar el DataTable
				$("#tblresult").append('<tfoot>' + footerRow + '</tfoot>');
				//
				$("#tblresult").DataTable({
					data: result.data,
					columns: result.columns,
					responsive: true,
					paging: false,
					destroy: true,
					language: {
						url: `${base_url}/Assets/json/es-ES.json`,
					},
					//Agregar total por columnas
					footerCallback: function (row, data, start, end, display) {
						const api = this.api();
						const monthColums = result.monthColums;
						//Establecer etiqueta de "Total Generl" en la primera Columna
						$(api.column(3).footer()).html(
							"<strong>Totales:</strong>"
						);

						//Calcular los totales para cada columna de mes/año

						monthColums.forEach((montKey, index) => {
							const columnIndex = index + 4;
							const total = api
								.column(columnIndex)
								.data()
								.reduce((a, b) => {
									const val = parseFloat(b) || 0;
									return a + val;
								}, 0);
							//Insertar el total en la celda del footer correspondiente
							$(api.column(columnIndex).footer()).html(
								`<strong>${total.toFixed(0)}</strong>`
							);
						});
						//Dejar las celdas Total y Probeio de las fila vacia
						const totalColIndex = 3 + monthColums.length;
						const tot_api = api
							.column(totalColIndex + 1)
							.data()
							.reduce((a, b) => {
								const val = parseFloat(b) || 0;
								return a + val;
							}, 0);
						$(api.column(totalColIndex + 1).footer()).html(
							`<strong>${tot_api.toFixed(0)}</strong>`
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
        }
    })
}
function pivotDataForDataTable(data) {
	const pivotedData = {};
	const monthsSet = new Set();
	const headers = [];
	//
	data.forEach((item) => {
		const key = item.id_prod;
		monthsSet.add(item.mesano);
		//
		if (!pivotedData[key]) {
			// Inicializar la fila con las columnas fijas
			pivotedData[key] = {
				id: item.id_prod,
				codigo: item.cod_prod,
				descripcion: item.nom_prod,
				marca: item.nom_fab,
				referencia: item.ref_prod,
				rowTotal: 0,
				rowCount: 0,
				stock: item.stock
			};
		}
		//
		pivotedData[key][item.mesano] = item.tot_caj;
		//
		pivotedData[key].rowTotal += parseFloat(item.tot_caj);
		pivotedData[key].rowCount += 1;
	});
	//
	const sortedMonths = Array.from(monthsSet).sort();
	//
	const finalData = Object.values(pivotedData).map((row) => {
		//
		row.rowAverage = (row.rowTotal / sortedMonths.length).toFixed(0);
		//
		delete row.rowCount;
		return row;
	});

	headers.push({ title: "Código", data: "codigo", className: "text-left" });
	headers.push({ title: "Descripción", data: "descripcion" });
	headers.push({ title: "Marca", data: "marca" });
	headers.push({ title: "Referencia", data: "referencia" });

	sortedMonths.forEach((month) => {
		//
		headers.push({title: month, data: month, defaultContent: "0", className: "text-right"});
	});
	//Agregar columna de total
	headers.push({title: "Total", data: "rowTotal", className: "text-right"});
	headers.push({title: "Promedio", data: "rowAverage", className: "text-right"});
	headers.push({title: "Stock", data: "stock", className: "text-right"});
	//

	return {
		columns: headers,
		data: finalData,
		monthColums: sortedMonths, // Devolvemos los meses para el calculo del footer
	};
}