//Inicialiar pagina
item = '';
$(document).ready(function () {
	tot_user();
	tot_prod();
	tot_cli();
	//

	CargarDatosGrafico_grafica_001();
	CargarDatosGrafico_grafica_002();
	CargarDatosGrafico_grafica_003();
	CargarDatosGrafico_grafica_004();

	//
	//Cargar Empresas
	listar_empresas("", "", "Todas");
	//Cargar fechas de inicio y fin del mes actual
	$("#fec_ini").val(getFirstDateofMonth());
	$("#fec_fin").val(getLastDateofMonth());
	//Ocultar Boton de Excel
	$(".excel").hide();
	//Cargar new frame
	//Cargar MArcas/Fabricante/Laboratorio
	listar_marcas();
	listar_grupos('', 'id_gru');
	listar_vendedores();
	listar_tipos_clientes(0);
});
$(document).on("change", "#fec_ini", function () {
	$("#tabla_grafica_prod_deta").empty();
	$(".excel").hide();
	xFec_ini = $(this).val();
});
$(document).on("change", "#fec_fin", function () {
	$(".excel").hide();
	$("#tabla_grafica_prod_deta").empty();
});
//Limpiar campos del formulario
$(document).on("click", "#btnClear", function (e) {
	//Cargar Empresas
	listar_empresas("", "", "Todas");
	$("#id_fab").empty();
	listar_marcas();
	$("#id_cli").val('');
	$("#nom_cli").val('');
	//Cargar fechas de inicio y fin del mes actual
	$("#fec_ini").val(getFirstDateofMonth());
	$(".excel").hide();
	listar_vendedores();
	listar_grupos('', 'id_gru');
});
//Al cambiar de empresa
$(document).on("change", "#id_emp", function (e) {
	$("#tabla_grafica_prod_deta").empty();
	$(".excel").hide();
});
//Al seleccionar un cliente
$(document).on("change", '#id_cli', async function (event) {
	event.preventDefault();
	id_cli = $(this).val();
	const datosFetched = await tid_vend(id_cli);
	nom_cli = datosFetched["nom_ent"];
	$("#nom_cli").val(nom_cli);
})
//Ejecutar busquedas del formulario
$(document).on("click", "#btnSearch", async function () {
	$("#tabla_grafica_prod_deta").empty();
	CargarDatosTabla_Producto_001();
	$(".excel").show();
});
//Grafico por Tabla de Productos
function CargarDatosTabla_Producto_001() {
	//Obtneer datos a buscar
	id_emp = $("#id_emp").val();
	if (!id_emp) {
		id_emp = 0;
	}
	fec_ini = $("#fec_ini").val();
	fec_fin = $("#fec_fin").val();

	const url = `${base_url}/Perfil/DatosTabla_001`;

	var promise = $.ajax({
		url: url,
		type: "POST",
		data: { id_emp: id_emp, fec_ini: fec_ini, fec_fin: fec_fin },
		dataType: "json",
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (response) {
			let title = ''
			var nom_emp = $("#id_emp option:selected").text();
			var fec_ini = $("#fec_ini").val();
			fec_ini = GetTodayDate(1, fec_ini, 1);
			var fec_fin = $("#fec_fin").val();
			fec_fin = GetTodayDate(1, fec_fin, 1);
			`<b>Empresa: ${nom_emp}<br>Fecha de inicio: ${fec_ini} Fecha de corte ${fec_fin}</b>`;
			//$('#tabla_grafica_prod_det').append('<caption style="caption-side: top-right">' + title + '</caption>');
			// 3. Inyección del Footer en el DOm ante de inicializar
			let tcols = 13; //Número de columnas total de la tabla
			let footerHtml = '<tr><th>TOTALES</th>';
			for (let i = 1; i < tcols; i++) 	footerHtml += '<th></th>';
			footerHtml += '</tr>';
			$('#tabla_grafica_prod').find('tfoot').remove(); //Limpiar si existe
			$('#tabla_grafica_prod').append('<tfoot>' + footerHtml + '</tfoot>');
			$('#tabla_grafica_prod').DataTable({
				data: response,
				paging: false,
				processing: true,
				destroy: true,
				clear: true,
				info: false,
				searching: false,
				columns: [
					{ title: "Empresa", data: "nombre_emp" },
					{ title: "Costo", data: "costo", render: DataTable.render.number('.', ',', 2), className: 'text-right' },
					{ title: "Utilidad", data: "utilidad", render: DataTable.render.number('.', ',', 2), className: 'text-right' },
					{
						title: "Venta Real", data: null, className: 'text-right',
						render: function (data, type, row) {
							const ventaReal = parseFloat(row.costo) + parseFloat(row.utilidad);
							return format_number_with_dec_new(ventaReal, 2);
						}
					},
					{
						title: "% Uti.Prom.", data: null, className: 'text-right',
						render: function (data, type, row) {
							const uti_prom = (parseFloat(row.utilidad) / (parseFloat(row.costo) + parseFloat(row.utilidad)));
							return format_number_with_dec_new(uti_prom, 2);
						}
					},
					{ title: "Adcional", data: "adicional", render: DataTable.render.number('.', ',', 2), className: 'text-right' },
					{
						title: "% Adi.Prom.", data: null, className: 'text-right',
						render: function (data, type, row) {
							const uti_prom = (parseFloat(row.adicional) / (parseFloat(row.costo) + parseFloat(row.utilidad)));
							return format_number_with_dec_new(uti_prom, 2);
						}
					},
					{
						title: "Facturado", data: null, className: 'text-right',
						render: function (data, type, row) {
							const facturado = parseFloat(row.costo) + parseFloat(row.utilidad) + parseFloat(row.adicional);
							return format_number_with_dec_new(facturado, 2);
						}
					},
					{ title: "Mon.CxC", data: "mon_cxc", render: DataTable.render.number('.', ',', 2), className: 'text-right' },
					{ title: "Mon.IVA", data: "mon_iva", render: DataTable.render.number('.', ',', 2), className: 'text-right' },
					{ title: "Gastos", data: "gastos", render: DataTable.render.number('.', ',', 2), className: 'text-right' },
					{ title: "Inventario PPAL.", data: "sal_inv_ppal", render: DataTable.render.number('.', ',', 2), className: 'text-right' },
					{ title: "Inventario Consig.", data: "sal_inv_consig", render: DataTable.render.number('.', ',', 2), className: 'text-right' },

				],
				language: {
					url: `${base_url}/Assets/json/es-ES.json`,
				},
				footerCallback: function (rw, data, start, end, display) {
					var api = this.api();
					//función para sumar columna
					const sumarColumna = (index) => {
						return api.column(index, { page: 'current' })
							.data()
							.reduce((a, b) => (parseFloat(a) || 0) + (parseFloat(b) || 0), 0);
					};
					//Aplicar suma a todas las columnas númericas (desde la 1 en adelante)						
					var total = 0;
					for (let i = 1; i < tcols; i++) {
						total = sumarColumna(parseFloat(i));
						if(i == 3) {
							total = sumarColumna(parseFloat(1)) + sumarColumna(parseFloat(2));							
						}else if(i == 4) {
							const totalCosto = sumarColumna(parseFloat(1));
							const totalUtilidad = sumarColumna(parseFloat(2));
							total = (totalUtilidad / (totalCosto + totalUtilidad));
						}else if(i == 6) {
							const totalAdicional = sumarColumna(parseFloat(5));
							const totalCosto = sumarColumna(parseFloat(1));
							const totalUtilidad = sumarColumna(parseFloat(2));
							total = (totalAdicional / (totalCosto + totalUtilidad));
						}else if(i == 7) {							
							total = sumarColumna(parseFloat(1)) + sumarColumna(parseFloat(2)) + sumarColumna(parseFloat(5));
						}
						$(api.column(i).footer()).html(format_number_with_dec_new(total, 2));					
					}					
				},
			});
		},
		error: function (xhr, errmsg, err) {
			loader.hide();
			console.log(xhr.status + ": " + xhr.responseText);
		},
		complete: function () {
			$(".loader").hide();
		},
	});
	promise.then(function () {
		const url = `${base_url}/Perfil/DatosTabla_001_det_cli`;
		//Ajax para 
		$.ajax({
			url: url,
			method: 'POST',
			dataSrc: '',
			data: { id_emp: id_emp, fec_ini: fec_ini, fec_fin: fec_fin },
			dataType: 'json',
			beforeSend: function () {
				loader.show();
			},
			complete: function () {
				loader.hide();
			},
			error: function (xhr, errmsg, err) {
				loader.hide();
				console.log(xhr.status + ": " + xhr.responseText);
			},
			success: function (data) {
				let title = ''
				var nom_emp = $("#id_emp option:selected").text();
				var fec_ini = $("#fec_ini").val();
				fec_ini = GetTodayDate(1, fec_ini, 1);
				var fec_fin = $("#fec_fin").val();
				fec_fin = GetTodayDate(1, fec_fin, 1);
				`<b>Empresa: ${nom_emp}<br>Fecha de inicio: ${fec_ini} Fecha de corte ${fec_fin}</b>`;
				//$('#tabla_grafica_prod_det').append('<caption style="caption-side: top-right">' + title + '</caption>');
				// 3. Inyección del Footer en el DOm ante de inicializar
				let footerHtml = '<tr><th>TOTALES</th>';
				for (let i = 1; i < 12; i++) 	footerHtml += '<th></th>';
				footerHtml += '</tr>';
				$('#tabla_grafica_prod_det').find('tfoot').remove(); //Limpiar si existe
				$('#tabla_grafica_prod_det').append('<tfoot>' + footerHtml + '</tfoot>');
				$('#tabla_grafica_prod_det').DataTable({
					data: data,
					paging: false,
					destroy: true,
					dataSrc: '',
					processing: true,
					columns: [
						{ title: "Empresa", data: "nombre_emp" },
						{ title: "Cliente", data: "nom_ent" },
						{ title: "Ventas", data: "sub_total", render: DataTable.render.number('.', ',', 2), className: 'text-right' },
						{ title: "Costo", data: "costo", render: DataTable.render.number('.', ',', 2), className: 'text-right' },
						{ title: "Utilidad", data: "utilidad", render: DataTable.render.number('.', ',', 2), className: 'text-right' },
						{
							title: "Venta Real", data: null, className: 'text-right', render: function (data, type, row) {
								const ventaReal = parseFloat(row.costo) + parseFloat(row.utilidad);
								return format_number_with_dec_new(ventaReal, 2);
							}
						},
						{
							title: "% Uti.Prom.", data: null, className: 'text-right', render: function (data, type, row) {
								const uti_prom = (parseFloat(row.utilidad) / parseFloat(row.costo));
								return format_number_with_dec_new(uti_prom, 2);
							}
						},
						{
							title: "Adicional", data: "adicional", render: DataTable.render.number('.', ',', 2), className: 'text-right'
						},
						{
							title: "Facturado", data: null, className: 'text-right', render: function (data, type, row) {
								const facturado = parseFloat(row.costo) + parseFloat(row.utilidad) + parseFloat(row.adicional);
								return format_number_with_dec_new(facturado, 2);

							}
						},
						{
							title: "%Adi.Prom.", data: null, className: 'text-right', render: function (data, type, row) {
								const adi_prom = (parseFloat(row.adicional) / parseFloat(row.costo));
								return format_number_with_dec_new(adi_prom, 2);
							}
						},
						{
							title: "Mon.CxC", data: "mon_cxc", render: DataTable.render.number('.', ',', 2), className: 'text-right'

						},
						{
							title: "Mon.IVA", data: "mon_iva", render: DataTable.render.number('.', ',', 2), className: 'text-right'
						}
					],
					footerCallback: function (rw, data, start, end, display) {
						var api = this.api();
						//función para sumar columna
						const sumarColumna = (index) => {
							return api.column(index, { page: 'current' })
								.data()
								.reduce((a, b) => (parseFloat(a) || 0) + (parseFloat(b) || 0), 0);
						};
						//Aplicar suma a todas las columnas númericas (desde la 1 en adelante)						
						var total = 0;
						for (let i = 2; i < 12; i++) {
							total = sumarColumna(parseFloat(i));
							if(i==5){
								total = sumarColumna(parseFloat(2)) + sumarColumna(parseFloat(3))
							}else if(i == 6){
								total = sumarColumna(parseFloat(4)) / sumarColumna(parseFloat(3));
							}else if(i == 8){
								total = sumarColumna(parseFloat(3)) + sumarColumna(parseFloat(4)) + sumarColumna(parseFloat(7));
							}else if(i == 9){
								total = sumarColumna(parseFloat(7)) / sumarColumna(parseFloat(3));
							}
							$(api.column(i).footer()).html(format_number_with_dec_new(total, 2));
						}

					},
					language: {
						url: `${base_url}/Assets/json/es-ES.json`,
					},
				});
			},
		});
	});
}
//Detalle en Excel Gráfica de Productos
$(document).on("click", "#btnExcel", function () {
	//Obtneer datos a buscar
	id_emp = $("#id_emp").val();
	if (!id_emp) {
		id_emp = 0;
	}
	fec_ini = $("#fec_ini").val();
	fec_fin = $("#fec_fin").val();

	const url = `${base_url}/Perfil/DatosTabla_001_det`;

	$.ajax({
		url: url,
		type: "POST",
		data: { id_emp: id_emp, fec_ini: fec_ini, fec_fin: fec_fin },
		dataType: "json",
		success: function (response) {
			// Convert JSON to worksheet
			const worksheet = XLSX.utils.json_to_sheet(response);
			// Create a new workbook and add the worksheet
			const workbook = XLSX.utils.book_new();
			XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");
			// Export the Excel file
			(filename =
				"Data detallada " +
				new Date().toISOString().replace(/[\-\:\.]/g, "") +
				".xlsx"),
				XLSX.writeFile(workbook, filename);
		},
	});
});
//Total Usuarios
async function tot_user() {
	const datos = new FormData();
	try {
		//Total Usuarios
		const url = `${base_url}/Usuarios/tot_user`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const resultado = await respuesta.json();
		if (resultado) {
			$("#tot_user").html(resultado["tot_user"]);
		}
	} catch (err) {
		console.log(err);
	}
}
//Total productos
async function tot_prod() {
	const datos = new FormData();
	try {
		const url = `${base_url}/Productos/tot_prod`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const resultado = await respuesta.json();
		if (resultado) {
			$("#tot_prod").html(resultado["tot_prod"]);
		}
	} catch (err) {
		console.log(err);
	}
}
//Total productos
async function tot_cli() {
	const datos = new FormData();
	try {
		const url = `${base_url}/Clientes/tot_cli`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const resultado = await respuesta.json();
		if (resultado) {
			$("#tot_cli").html(resultado["tot_cli"]);
		}
	} catch (err) {
		console.log(err);
	}
}
//Mostrar graficos
async function CargarDatosGrafico_grafica_001() {
	$.ajax({
		url: `${base_url}/Perfil/grafica_001`,
		type: "POST",
	}).done(function (resp) {
		if (resp.length > 0) {
			var titulo = [];
			var cantidad = [];
			var colores = [];
			var data = JSON.parse(resp);
			for (i = 0; i < data.length; i++) {
				titulo.push(data[i]["anio"]);
				cantidad.push(data[i]["can_det"]);
				colores.push(colorRGB());
			}
			CrearGrafico(
				titulo,
				cantidad,
				colores,
				"bar",
				"Ventas por año",
				"ventasxanio"
			);
		}
	});
}
//Mostrar graficos
async function CargarDatosGrafico_grafica_002() {
	$.ajax({
		url: `${base_url}/Perfil/grafica_002`,
		type: "POST",
	}).done(function (resp) {
		if (resp.length > 0) {
			var titulo = [];
			var cantidad = [];
			var colores = [];
			var data = JSON.parse(resp);
			for (i = 0; i < data.length; i++) {
				titulo.push(data[i]["title"]);
				cantidad.push(data[i]["can_det"]);
				colores.push(colorRGB());
			}
			CrearGrafico(
				titulo,
				cantidad,
				colores,
				"bar",
				"Ventas por mes por año",
				"myChart_2"
			);
		}
	});
}
//Mostrar graficos
async function CargarDatosGrafico_grafica_003() {
	$.ajax({
		url: `${base_url}/Perfil/grafica_003`,
		type: "POST",
	}).done(function (resp) {
		if (resp.length > 0) {
			var titulo = [];
			var cantidad = [];
			var colores = [];
			var data = JSON.parse(resp);
			for (i = 0; i < data.length; i++) {
				titulo.push(data[i]["title"]);
				cantidad.push(data[i]["can_det"]);
				colores.push(colorRGB());
			}
			CrearGrafico(
				titulo,
				cantidad,
				colores,
				"pie",
				"Ventas por producto",
				"myChart_3"
			);
		}
	});
}
//Mostrar graficos
async function CargarDatosGrafico_grafica_004() {
	$.ajax({
		url: `${base_url}/Perfil/grafica_004`,
		type: "POST",
	}).done(function (resp) {
		if (resp.length > 0) {
			var titulo = [];
			var cantidad = [];
			var colores = [];
			var data = JSON.parse(resp);
			for (i = 0; i < data.length; i++) {
				titulo.push(data[i]["nom_prod"]);
				cantidad.push(data[i]["sub_total"]);
				colores.push(colorRGB());
			}
			CrearGrafico(
				titulo,
				cantidad,
				colores,
				"pie",
				"Top 10 productos mas vendidos",
				"myChart_4"
			);
		}
	});
}
function CrearGrafico(titulo, cantidad, colores, tipo, head, id) {
	var ctx = $("#" + id);
	var myChart = new Chart(ctx, {
		type: tipo,
		data: {
			labels: titulo,
			datasets: [
				{
					label: head,
					data: cantidad,
					backgroundColor: colores,
					borderColor: colores,
					borderWidth: 1,
				},
			],
		},
		options: {
			scales: {
				yAxes: [
					{
						ticks: {
							beginAtZero: true,
						},
					},
				],
			},
		},
	});
}
//Ejecutar el Buscar del Reporte de Consumo
//Ejecutar busquedas del formulario
$(document).on("click", "#btnSearchConsumo", async function () {
	ReportexConsumo();
	$(".excel").show();
});
function ReportexConsumo() {
	id_emp = $("#id_emp").val();
	fec_ini = $("#fec_ini").val();
	fec_fin = $("#fec_fin").val();
	id_fab = $("#id_fab").val();
	id_cli = $("#id_cli").val();
	id_gru = $("#id_gru").val();
	id_vend = $("#id_vend").val();
	id_tipocliente = $("#id_tipocliente").val();
	const url = `${base_url}/Perfil/ReportexConsumo`;
	//Ajax para 
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: { id_emp: id_emp, fec_ini: fec_ini, fec_fin: fec_fin, id_fab: id_fab, id_cli: id_cli, id_gru: id_gru, id_vend: id_vend, id_tipocliente: id_tipocliente },
		dataType: 'json',
		beforeSend: function () {
			loader.show();
		},
		complete: function () {
			loader.hide();
		},
		error: function (PDOException) {
			loader.hide();
			console.log('Ha ocurrido el siguiente error:', PDOException.responseText)
		},
		success: function (data) {
			generarReporteConsumo(data);
		},
	});
}
//Reporte por Consumo
function generarReporteConsumo(rawData) {
	$.fn.dataTable.ext.errMode = 'none';
	report = "Reporte por Consumos_";
	// 1. ELIMINAR INSTANCIA ANTERIOR
	// Esto quita la lógica de búsqueda, paginación y eventos de la tabla vieja
	if ($.fn.DataTable.isDataTable('#ReportexConsumo')) {
		$('#ReportexConsumo').DataTable().destroy();
	}
	// 2. LIMPIAR EL HTML POR COMPLETO
	// Como las columnas cambian (ej. un rango de 3 meses vs 6 meses), 
	// debemos borrar los <thead> y <tbody> viejos.
	$('#ReportexConsumo').empty();
	const meseRef = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
	let productosMap = {};
	let yearSet = new Set();
	let periodosSet = new Set();
	// 1. Procesamiento y Cálculos de Fila
	if (!rawData) return;
	rawData.forEach(item => {
		const periodo = `${item.mes} ${item.anio}`;
		yearSet.add(item.anio);
		periodosSet.add(periodo);
		if (!productosMap[item.id_prod]) {
			productosMap[item.id_prod] = { id_prod: item.id_prod, cod_prod: item.cod_prod, nom_prod: item.nom_prod, nom_fab: item.nom_fab, ref_prod: item.ref_prod, totalGeneral: 0, stock: item.stock, utilidad: 0 };
		}
		//Guardar venta mensual
		productosMap[item.id_prod][periodo] = parseFloat(item.tot_row) || 0;
		//Sumar al total del año correspondiente
		const keyTotalAnio = `Total ${item.anio}`;
		productosMap[item.id_prod][keyTotalAnio] = (productosMap[item.id_prod][keyTotalAnio] || 0) + parseFloat(item.tot_row) || 0;
		//Guardar utilidad
		productosMap[item.id_prod].utilidad += parseFloat(item.utilidad) || 0;

		//Sumar al Total General del producto
		productosMap[item.id_prod].totalGeneral += parseFloat(item.tot_row) || 0;
	})
	//Construcción Dinámica de Columnas
	let columnas = [{ title: "Producto", data: "id_prod", visible: false, className: 'text-right' }];
	columnas.push({ title: "Código", data: "cod_prod", className: 'text-left' });
	columnas.push({ title: "Descripción", data: "nom_prod", className: 'text-left' });
	columnas.push({ title: "Marca", data: "nom_fab", className: 'text-left' });
	columnas.push({ title: "Referencia", data: "ref_prod", className: 'text-left' });
	const anosOrdenados = Array.from(yearSet).sort();
	anosOrdenados.forEach(anio => {
		//Filtrar y ordenar los meses de este año especifico
		const mesesDeEsteAnio = Array.from(periodosSet)
			.filter(p => p.includes(anio))
			.sort((a, b) => meseRef.indexOf(a.split(' ')[0]) - meseRef.indexOf(b.split(' ')[0]));
		mesesDeEsteAnio.forEach(p => {
			columnas.push({ title: p, data: p, defaulContent: "0", className: "text-right" });
		});
		// 🔥 AQUÍ AGREGAMOS LA COLUMNA DEL TOTAL DEL AÑO
		columnas.push({
			title: `Total ${anio}`,
			data: `Total ${anio}`,
			defaultContent: "0",
			className: "text-right font-weight-bold bg-secondary text-white" // Clase de AdminLTE para resaltar
		});
	});
	//Columna final: Total General
	columnas.push({
		title: "Total General",
		data: 'totalGeneral',
		className: "text-right font-weight-bold bg-secondary text-white"
	});
	//Columna de Stock
	columnas.push({
		title: "Inventario",
		data: 'stock',
		className: "text-right font-weight-bold bg-secondary text-white"
	});
	//Utilidad
	columnas.push({
		title: "Utilidad",
		data: "utilidad",
		render: DataTable.render.number('.', ',', 2),
		className: "text-right font-weight-bold bg-secondary text-white",
	})
	// 3. Inyección del Footer en el DOm ante de inicializar
	let footerHtml = '<tr><th>TOTALES</th>';
	for (let i = 1; i < columnas.length; i++) footerHtml += '<th></th>';
	footerHtml += '</tr>';
	$('#ReportexConsumo').find('tfoot').remove(); //Limpiar si existe
	$('#ReportexConsumo').append('<tfoot>' + footerHtml + '</tfoot>');
	// 4. Inicialización de DataTables
	//Armar Titulo de la Tabla
	let title = '';
	var nom_emp = $("#id_emp option:selected").text();
	var fec_ini = $("#fec_ini").val();
	fec_ini = GetTodayDate(1, fec_ini, 1);
	var fec_fin = $("#fec_fin").val();
	fec_fin = GetTodayDate(1, fec_fin, 1);
	var nom_fab = $('#id_fab option:selected').map(function () {
		return $(this).text();
	}).get();

	if (nom_fab.length > 0) {
		nom_fab = `<br>Marca(s): ${nom_fab}`;
	}
	var nom_cli = $("#nom_cli").val();
	if (nom_cli) {
		nom_cli = `<br>Cliente: ${nom_cli}`
	}
	id_grup = $("#id_gru").val();
	nom_gru = '';
	if (id_gru) {
		nom_gru = $("#id_gru option:selected").text();
		nom_gru = `<br>Grupo: ${nom_gru}`;
	}
	id_vend = $("#id_vend").val();
	nom_vend = '';
	if (id_vend) {
		nom_vend = $("#id_vend option:selected").text();
		nom_vend = `<br>Vendedor: ${nom_vend}`;
	}
	id_tipocliente = $("#id_tipocliente").val()
	nom_tipocliente = '';
	if (id_tipocliente) {
		nom_tipocliente = $("#id_tipocliente option:selected").text();
		nom_tipocliente = `<br>Tipo de Cliente: ${nom_tipocliente}`;
	}

	title = `<b>Empresa: ${nom_emp}<br>Fecha de inicio: ${fec_ini} Fecha de corte ${fec_fin} ${nom_cli} ${nom_fab} ${nom_gru} ${nom_vend} ${nom_tipocliente}</b>`;
	$('#ReportexConsumo').append('<caption style="caption-side: top-right">' + title + '</caption>');
	$('#ReportexConsumo').DataTable({
		scrollX: true,     // Lo manejamos nosotros con el DIV contenedor para más control
		fixedHeader: false,
		fixedColumns: {
			start: 4 // Número de columnas a fijar a la izquierda
		},
		responsive: false,
		data: Object.values(productosMap),
		columns: columnas,
		destroy: true,
		paging: false, //Recomendado para reportes de totales
		clear: true,
		scrollY: "300px",     // Lo manejamos nosotros con el DIV contenedor
		autoWidth: true,
		scrollCollapse: true,

		footerCallback: function (rw, data, start, end, display) {
			var api = this.api();
			//función para sumar columna
			const sumarColumna = (index) => {
				return api.column(index, { page: 'current' })
					.data()
					.reduce((a, b) => (parseFloat(a) || 0) + (parseFloat(b) || 0), 0);
			};
			//Aplicar suma a todas las columnas númericas (desde la 1 en adelante)
			$(api.column(2).footer()).html('TOTALES');
			for (let i = 5; i < columnas.length; i++) {
				const total = sumarColumna(parseFloat(i));
				if (i < columnas.length - 1) {
					$(api.column(i).footer()).html(total);
				} else {
					$(api.column(i).footer()).html(format_number_with_dec_new(total, 2));
				}
			}
		},
		language: {
			url: `${base_url}/Assets/json/es-ES.json`,
		},
		stateSave: true,
		layout: {
			topStart: 'buttons'
		},
		// mostrar botones de exportacion
		dom: "lBfrtip",
		buttons: [
			{ extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i>', titleAttr: "Exportar a Excel", className: 'btn-success btn btn-sm' }
		],

	});
}