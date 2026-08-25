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
	$('#consumo-content').hide();
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
	$("#id_vend").empty();
	listar_vendedores();
	listar_grupos('', 'id_gru');
	$('#consumo-content').hide();
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
			let tcols = 15; //Número de columnas total de la tabla
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
					{ title: 'Cot', data: "total_cot", className: 'text-right' },
					{ title: 'Fac.', data: "facturadas", className: 'text-right' }
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
						if (i == 3) {
							total = sumarColumna(parseFloat(1)) + sumarColumna(parseFloat(2));
						} else if (i == 4) {
							const totalCosto = sumarColumna(parseFloat(1));
							const totalUtilidad = sumarColumna(parseFloat(2));
							total = (totalUtilidad / (totalCosto + totalUtilidad));
						} else if (i == 6) {
							const totalAdicional = sumarColumna(parseFloat(5));
							const totalCosto = sumarColumna(parseFloat(1));
							const totalUtilidad = sumarColumna(parseFloat(2));
							total = (totalAdicional / (totalCosto + totalUtilidad));
						} else if (i == 7) {
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
				for (let i = 1; i < 13; i++) 	footerHtml += '<th></th>';
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
						{ title: "Vendedor", data: "nom_vend" },
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
						for (let i = 3; i < 13; i++) {
							total = sumarColumna(parseFloat(i));
							if (i == 5) {
								total = sumarColumna(parseFloat(2)) + sumarColumna(parseFloat(3))
							} else if (i == 6) {
								total = sumarColumna(parseFloat(4)) / sumarColumna(parseFloat(3));
							} else if (i == 8) {
								total = sumarColumna(parseFloat(3)) + sumarColumna(parseFloat(4)) + sumarColumna(parseFloat(7));
							} else if (i == 9) {
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
			legend: {
				display: false,
			},
			responsive: true,
			maintainAspectRatio: false,
		},
	});
}
//Ejecutar el Buscar del Reporte de Consumo
//Ejecutar busquedas del formulario
$(document).on("click", "#btnSearchConsumo", async function () {
	ReportexConsumo();
	$(".excel").show();
	$('#consumo-content').show();
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
	//Llenar el select de años para la gráfica
	actualizarComboAnios();
	actualizarComboMarca();
	actualizarComboTiposClientes();
	listar_consumo_x_clientes();

}
function actualizarComboAnios() {
	var inicio = $('#fec_ini').val();
	var fin = $('#fec_fin').val();

	if (inicio && fin) {
		var anioInicio = new Date(inicio).getFullYear() - 2000;
		var anioFin = new Date(fin).getFullYear() - 2000;

		// Ordenar años si fin es menor que inicio
		var min = Math.min(anioInicio, anioFin) + 1; // +1 para no incluir el año de inicio
		var max = Math.max(anioInicio, anioFin);

		var $combo = $('#sel_fecha');
		$combo.empty(); // Limpiar opciones anteriores
		$combo.append('<option value="">Seleccione año</option>');

		// Bucle para agregar años
		for (var i = anioInicio; i <= anioFin; i++) {
			$combo.append('<option value="' + i + '">' + i + '</option>');
		}
	}
}
function actualizarComboMarca() {
	$.ajax({
		url: `${base_url}/Perfil/listar_marcas`,
		method: "POST",
		dataType: "json",
		success: function (response) {
			var $combo = $('#sel_marca');
			$combo.empty();
			$combo.append('<option value="">Seleccione marca(s)</option>');
			response.forEach(marca => {
				$combo.append('<option value="' + marca.id_fab + '">' + marca.nom_fab + '</option>');
			});
			//Crear Grafica de Utilidad por marca
			var titulo = [];
			var cantidad = [];
			var colores = [];
			for (i = 0; i < response.length; i++) {
				titulo.push(response[i]["nom_fab"]);
				cantidad.push(response[i]["total_utilidad"]);
				colores.push(colorRGB());
			}
			CrearGraficoConsumo(
				titulo,
				cantidad,
				colores,
				'pie',
				'Utilidad por marca',
				'marcaChart',
				response,
				'custom-legend-marca'
			);
		},
		error: function (xhr, errmsg, err) {
			console.log(xhr.status + ": " + xhr.responseText);
		}
	});
}
function actualizarComboTiposClientes() {
	id_emp = $("#id_emp").val();
	fec_ini = $("#fec_ini").val();
	fec_fin = $("#fec_fin").val();
	id_fab = $("#id_fab").val();
	id_cli = $("#id_cli").val();
	id_gru = $("#id_gru").val();
	id_vend = $("#id_vend").val();
	id_tipocliente = $("#id_tipocliente").val();
	var $combo = '';
	$.ajax({
		url: `${base_url}/Perfil/listar_tipos_clientes`,
		method: "POST",
		data: { id_emp: id_emp, fec_ini: fec_ini, fec_fin: fec_fin, id_fab: id_fab, id_cli: id_cli, id_gru: id_gru, id_vend: id_vend, id_tipocliente: id_tipocliente },
		dataType: "json",
		success: function (response) {
			//Tipo de Clientes
			$combo = $('#sel_tipo_cliente');
			$combo.empty();
			$combo.append('<option value="">Seleccione tipo(s) de cliente(s)</option>');
			response.forEach(tipo => {
				$combo.append('<option value="' + tipo.id + '">' + tipo.description + '</option>');
			});
			//Crear Grafica de Utilidad por marca	
			var titulo = [];
			var cantidad = [];
			var colores = [];
			for (i = 0; i < response.length; i++) {
				titulo.push(response[i]["description"]);
				cantidad.push(response[i]["utilidad"]);
				colores.push(colorRGB());
			}
			CrearGraficoConsumo(
				titulo,
				cantidad,
				colores,
				'pie',
				'Utilidad por Tipo de Cliente',
				'tipoChart',
				response,
				'custom-legend-tipo-cliente'
			);
		},
		error: function (xhr, errmsg, err) {
			console.log(xhr.status + ": " + xhr.responseText);
		}
	});
	$.ajax({
		url: `${base_url}/Perfil/listar_vendedores`,
		method: "POST",
		data: { id_emp: id_emp, fec_ini: fec_ini, fec_fin: fec_fin, id_fab: id_fab, id_cli: id_cli, id_gru: id_gru, id_vend: id_vend, id_tipocliente: id_tipocliente },
		dataType: "json",
		success: function (response) {
			//Vendedores	
			$combo = $('#sel_vendedor');
			$combo.empty();
			$combo.append('<option value="">Seleccione vendedor(es)</option>');
			response.forEach(tipo => {
				$combo.append('<option value="' + tipo.id + '">' + tipo.nom_vend + '</option>');
			});
			//Crear Grafica de Utilidad por marca
			var titulo = [];
			var cantidad = [];
			var colores = [];
			for (i = 0; i < response.length; i++) {
				titulo.push(response[i]["nom_fab"]);
				cantidad.push(format_number_with_dec_new(response[i]["total_utilidad"], 2));
				colores.push(colorRGB());
			}
			/*
			CrearGrafico(
				titulo,
				cantidad,
				colores,
				"pie",
				"Utilidad por marca",
				"tipoChart",
	
			);
			*/
		},
		error: function (xhr, errmsg, err) {
			console.log(xhr.status + ": " + xhr.responseText);
		}
	});
}
function listar_consumo_x_clientes() {
	id_emp = $("#id_emp").val();
	fec_ini = $("#fec_ini").val();
	fec_fin = $("#fec_fin").val();
	id_fab = $("#id_fab").val();
	id_cli = $("#id_cli").val();
	id_gru = $("#id_gru").val();
	id_vend = $("#id_vend").val();
	id_tipocliente = $("#id_tipocliente").val();
	let footerHtml = '<tr><th>TOTALES</th>';
	for (let i = 1; i < 4; i++) footerHtml += '<th></th>';
	footerHtml += '</tr>';
	$('#ReportexGrafica').find('tfoot').remove(); //Limpiar si existe
	$('#ReportexGrafica').append('<tfoot>' + footerHtml + '</tfoot>');
	$.ajax({
		url: `${base_url}/Perfil/listar_consumo_x_clientes`,
		method: "POST",
		data: { id_emp: id_emp, fec_ini: fec_ini, fec_fin: fec_fin, id_fab: id_fab, id_cli: id_cli, id_gru: id_gru, id_vend: id_vend, id_tipocliente: id_tipocliente },
		dataType: "json",
		success: function (response) {
			//Clientes				
			$("#ReportexGrafica").DataTable({
				data: response,
				destroy: true,
				info: false,
				columns: [
					{ title: "Cliente", data: "cliente" },
					{ title: "Consumo", data: "unidades", render: DataTable.render.number('.', ',', 0), className: 'text-right' },
					{ title: "Ventas", data: "ventas", render: DataTable.render.number('.', ',', 2), className: 'text-right' },
					{ title: "Utilidad", data: "utilidad", render: DataTable.render.number('.', ',', 2), className: 'text-right' }
				],
				footerCallback: function (row, data, start, end, display) {
					var api = this.api();
					var intVal = function (i) {
						return typeof i === 'string' ?
							i.replace(/[\$,]/g, '') * 1 :
							typeof i === 'number' ?
								i : 0;
					};
					let totalGeneralUni = api
						.column(1)
						.data()
						.reduce(function (a, b) {
							return intVal(a) + intVal(b);
						}, 0);

					// 2. Calcular el Total de la Página Actual
					let totalPaginaUni = api
						.column(1, { page: 'current' })
						.data()
						.reduce(function (a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					//
					let totalGeneralVen = api
						.column(2)
						.data()
						.reduce(function (a, b) {
							return intVal(a) + intVal(b);
						}, 0);

					// 2. Calcular el Total de la Página Actual
					let totalPaginaUti = api
						.column(3, { page: 'current' })
						.data()
						.reduce(function (a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					//
					let totalGeneralUti = api
						.column(3)
						.data()
						.reduce(function (a, b) {
							return intVal(a) + intVal(b);
						}, 0);

					// 2. Calcular el Total de la Página Actual
					let totalPaginaVen = api
						.column(2, { page: 'current' })
						.data()
						.reduce(function (a, b) {
							return intVal(a) + intVal(b);
						}, 0);
					// Actualizar el pie de página
					$(api.column(1).footer()).html(
						'<div style="border-bottom: 1px solid #ccc">Pag: ' + totalPaginaUni.toLocaleString('es-VE', { minimumFractionDigits: 2 }) + '</div>' +
						'<div>Total: ' + totalGeneralUni.toLocaleString('es-VE', { minimumFractionDigits: 2 }) + '</div>'
					);
					//				
					$(api.column(2).footer()).html(
						'<div style="border-bottom: 1px solid #ccc">Pag: ' + totalPaginaVen.toLocaleString('es-VE', { minimumFractionDigits: 2 }) + '</div>' +
						'<div>Total: ' + totalGeneralVen.toLocaleString('es-VE', { minimumFractionDigits: 2 }) + '</div>'
					);
					//				
					$(api.column(3).footer()).html(
						'<div style="border-bottom: 1px solid #ccc">Pag: ' + totalPaginaUti.toLocaleString('es-VE', { minimumFractionDigits: 2 }) + '</div>' +
						'<div>Total: ' + totalGeneralUti.toLocaleString('es-VE', { minimumFractionDigits: 2 }) + '</div>'
					);

				},
				language: {
					url: `${base_url}/Assets/json/es-ES.json`,
				},
			});
		},
		error: function (xhr, errmsg, err) {
			console.log(xhr.status + ": " + xhr.responseText);
		}

	});
}
function CrearGraficoConsumo(titulo, cantidad, colores, tipo, head, id, data = null, id_leyenda = null) {
	// 1. La Data (puedes traerla de tu AJAX de DataTables)
	const dataConsumo = {
		labels: titulo,
		datasets: [{
			data: cantidad,
			backgroundColor: colores,
			borderWidth: 0
		}]
	};
	// 2. Configuración del Gráfico
	const config = {
		type: tipo,
		data: dataConsumo,
		options: {
			responsive: true,
			plugins: {
				legend: {
					display: false // Desactivamos la leyenda original
				}
			},
		}
	};
	// 3. Renderizar	
	const existingChart = Chart.getChart(id);
	if (existingChart) {
		existingChart.destroy(); // Destroy it before making a new one
	}
	const myChart = new Chart(
		document.getElementById(id),
		config
	);
	//Limpiar leyendo
	$("#" + id_leyenda).empty();
	// 4. Generar Leyenda Dinámica
	const legendContainer = document.getElementById(id_leyenda);

	dataConsumo.labels.forEach((label, i) => {
		const value = dataConsumo.datasets[0].data[i];
		const color = dataConsumo.datasets[0].backgroundColor[i];

		legendContainer.innerHTML += `
		<div style="display: flex; justify-content: space-between; align-items: center; padding: 8px; border-bottom: 1px solid #eee;">
			<div style="display: flex; align-items: center;">
				<span style="width: 12px; height: 12px; background-color: ${color}; border-radius: 50%; display: inline-block; margin-right: 10px;"></span>
				<span style="font-size: 14px; color: #555;">${label}</span>
			</div>
			<strong style="font-size: 14px;">${value}</strong>
		</div>
	`;
	});
}
$("#sel_fecha, #sel_marca, #sel_tipo_cliente, #sel_vendedor").on("change", function () {
	//Obtener valores seleccionados		
	id_emp = $("#id_emp").val();
	fec_ini = $("#fec_ini").val();
	fec_fin = $("#fec_fin").val();
	id_fab = $("#id_fab").val();
	id_cli = $("#id_cli").val();
	id_gru = $("#id_gru").val();
	id_vend = $("#id_vend").val();
	id_tipocliente = $("#id_tipocliente").val();
	//
	anio = '';
	anio = $("#sel_fecha").val();
	id_fab = $("#sel_marca").val();
	id_tipocliente = $("#sel_tipo_cliente").val();
	id_vend = $("#sel_vendedor").val();

	//Año
	var url1 = `${base_url}/Perfil/listar_marcas`;
	var url2 = `${base_url}/Perfil/listar_marcas`;
	var url3 = `${base_url}/Perfil/listar_marcas`;
	var url4 = `${base_url}/Perfil/listar_marcas`;
	//
	//Ajax para Año
	$.ajax({
		url: url1,
		method: 'POST',
		dataSrc: '',
		data: { id_fab: id_fab, anio: anio, id_tipocliente: id_tipocliente, id_vend: id_vend },
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
			marcaChart = $("#marcaChart");
			//Actualizar grafica de marcas			
			var titulo = [];
			var cantidad = [];
			var colores = [];
			for (i = 0; i < data.length; i++) {
				titulo.push(data[i]["nom_fab"]);
				cantidad.push(data[i]["total_utilidad"]);
				colores.push(colorRGB());
			}
			CrearGraficoConsumo(
				titulo,
				cantidad,
				colores,
				'pie',
				'Utilidad por marca',
				'marcaChart',
				data,
				'custom-legend-marca'
			);
		},
	});
})
/* Fin Reporte por Consumo */
/*Funcion para buscar el detallde de Cotizaciones
* Creado el 04-05-2026 a las 09:05:00 por José Vargas
*Para mostrar la tabla y grafica de cotizaciones
*/
$(document).on("click", "#btnSearchCotizaciones", function () {
	id_emp = $("#id_emp").val();
	fec_ini = $("#fec_ini").val();
	fec_fin = $("#fec_fin").val();
	var url = `${base_url}/Perfil/grafica_cotizaciones`;
	GraficaCotizaciones(url, id_emp, fec_ini, fec_fin);
})

/**
 * Description Function para Crear Tabla y Grafica de Cotizaciones
 * Creado el 04-05-2026 a las 09:23:00 por José Vargas
 *
 * @param {*} url 
 * @param {*} id_emp 
 * @param {*} fec_ini 
 * @param {*} fec_fin 
 */
function GraficaCotizaciones(url, id_emp, fec_ini, fec_fin) {
	$.ajax({
		url: url,
		method: 'POST',
		data: { id_emp: id_emp, fec_ini: fec_ini, fec_fin: fec_fin },
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
			//1. Si la tabla ya existe, se destruye y se limpia el HTML
			if ($.fn.DataTable.isDataTable("#ReportexCotizaciones")) {
				$('#ReportexCotizaciones').DataTable().destroy();
			}
			//2. Mapeo para ordenamiento cronologico
			const mesesorder = { "Ene": 1, "Feb": 2, "Mar": 3, "Abr": 4, "May": 5, "Jun": 6, "Jul": 7, "Ago": 8, "Sep": 9, "Oct": 10, "Nov": 11, "Dic": 12 };
			//2. Identificar añio y meses
			const periodosUnicos = [...new Set(data.map(item => `${item.anio}-${item.mes}`))].sort((a, b) => {
				const [anioA, mesA] = a.split('-');
				const [anioB, mesB] = b.split('-')
				return anioA - anioB || mesesorder[mesA] - mesesorder[mesB];
			});
			mesesKey = periodosUnicos.map(p => p.split('-')[1]);			
			//3. Construir el THEAD (Encabezado doble)
			let thead = `<thead><tr><th rowspan="2" class="align-middle">Vendedor</th>`;
			let tfoot = `<tfoot><tr class="bg-light text-bold"><td>TOTAL GENERAL</td>`;
			periodosUnicos.forEach(p => {
				const [anio, mes] = p.split('-');
				thead += `<th colspan="2" class="text-center bg-lightblue">Año ${anio} ${mes}</th>`;
				tfoot += `<th class="text-center"></th><th class="text-center"></th>`;
			});
			thead += `<th colspan="2" class="text-center bg-navy">Total General</th></tr><tr>`;
			tfoot += `<th class="text-center text-primary"></th><th class="text-center text-success"</th></tr></tfoot>`;
			periodosUnicos.forEach(() => {
				thead += `<th class="text-center">Cotiz.</th><th class="text-center">Fact.</th>`;
			});
			thead += `<th class="text-center">Cotiz.</th><th class="text-center">Fact.</th></tr></thead>`;
			//4. Procesar datos de los vendedores
			vendedores = {};
			data.forEach(item => {
				if (!vendedores[item.id_user]) {
					vendedores[item.id_user] = { vendedor: item.name_user, total_c: 0, total_f: 0 };
					mesesKey.forEach(m => {
						vendedores[item.id_user][m] = { c: 0, f: 0 }
					});
				}
				if (!vendedores[item.id_user][item.mes]) {
					vendedores[item.id_user][item.mes] = { c: 0, f: 0 };
				}
				const c = parseInt(item.total_cot || 0);
				const f = parseInt(item.facturadas || 0);
				vendedores[item.id_user][item.mes].c += c;
				vendedores[item.id_user][item.mes].f += f;
				vendedores[item.id_user].total_c += c;
				vendedores[item.id_user].total_f += f;

			});
			generarGraficas(vendedores);
			$("#GrafCoti").show();
			//5. Construiir el tbody
			let tbody = '<tbody>';
			Object.values(vendedores).forEach(v => {
				tbody += `<tr class="fila-seleccionada"><td>${v.vendedor}</td>`;
				mesesKey.forEach(m => {
					const cotizacionMes = (v[m] && v[m].c) ? v[m].c : 0;
					const facturaMes = (v[m] && v[m].f) ? v[m].f : 0;
					tbody += `<td class="text-center">${cotizacionMes}</td><td class="text-center">${facturaMes}</td>`;
				});
				tbody += `<td class="text-center text-bold">${v.total_c}</td><td class="text-center text-bold">${v.total_f}</td></tr>`;
			});
			tbody += '</tbody>';
			//6. Intectar HTML e iniciar Datatables
			$("#ReportexCotizaciones").html(thead + tbody + tfoot);
			$("#ReportexCotizaciones").DataTable({
				destroy: true,
				clear: true,
				responsive: true,
				language: {
					url: `${base_url}/Assets/json/es-ES.json`,
				},
				footerCallback: function (row, data, start, end, display) {
					var api = this.api();
					//sumar cada columna numérica automaticamente
					api.columns('.text-center', { page: 'current' }).every(function () {
						var sum = this.data().reduce(function (a, b) {
							var x = parseInt($(a).text()) || parseInt(a) || 0;
							var y = parseInt($(b).text()) || parseInt(b) || 0;
							return x + y;
						}, 0);
						$(this.footer()).html(sum);
					});
				},
				dom: 'Bfrtip',
				buttons: [
					{
						extend: 'excelHtml5',
						text: '<i class="fas fa-file-excel"></i>',
						className: 'btn btn-success',
						title: 'Reporte de Cotizaciones Dinámico',
						// Esta es la clave para encabezados múltiples:
						exportOptions: {
							columns: ':visible',
							header: true,
							footer: true, //
							format: {
								header: function (data, columnIdx, node) {
									// Forzamos a que mantenga el texto tal cual aparece en el nodo
									return node.innerText;
								}
							},
							// Asegura que incluya todas las filas del header
							orthogonal: 'export'
						},
						customize: function (xlsx) {
							var sheet = xlsx.xl.worksheets['sheet1.xml'];
							// Aplicar estilos a celdas específicas (esto requiere manejo de selectores XML de DataTables)
							// '20' suele ser el estilo azul y '28' verde en el esquema por defecto
							$('row:first c', sheet).attr('s', '32'); // Estilo negrita y centrado para la fila 1
							$('row:nth-child(2) c', sheet).attr('s', '32'); // Para la fila 2
						}
					}
				]
			});
		}
	});
}
// Definimos variables globales para poder destruir y recrear las gráficas
var chartBarras = null;
var chartTorta = null;

function generarGraficas(usuarios) {
    const nombres = Object.values(usuarios).map(u => u.vendedor);
    const totalCotizaciones = Object.values(usuarios).map(u => u.total_c);
    const totalFacturadas = Object.values(usuarios).map(u => u.total_f);

    // --- GRÁFICO DE BARRAS ---
    const ctxBar = document.getElementById('barChart').getContext('2d');
    if (chartBarras) chartBarras.destroy();

    chartBarras = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: nombres,
            datasets: [
                {
                    label: 'Cotizaciones',
                    backgroundColor: '#3c8dbc', // Color azul AdminLTE
                    data: totalCotizaciones
                },
                {
                    label: 'Facturadas',
                    backgroundColor: '#28a745', // Color verde AdminLTE
                    data: totalFacturadas
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // --- GRÁFICO DE TORTA (Basado en Facturación) ---
    const ctxPie = document.getElementById('pieChart').getContext('2d');
    if (chartTorta) chartTorta.destroy();

    chartTorta = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: nombres,
            datasets: [{
                data: totalFacturadas,
                backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right' }
            }
        }
    });
}
// Asegúrate de que tu tabla tenga el ID #ReportexCotizaciones
$(document).on('click', '#ReportexCotizaciones tbody tr', function () {
   // 1. Verificar si la tabla tiene datos (evitar clics en "No hay datos")
    if ($(this).find('td').hasClass('dataTables_empty')) return;

    // 2. Obtener la instancia de la DataTable
    var table = $('#ReportexCotizaciones').DataTable();
    
    // 3. Obtener los datos de la fila
    // Si usaste objetos: table.row(this).data()
    // Si usaste HTML plano, sacamos el nombre del primer TD:
    var nombreVendedor = $(this).find('td:first').text().trim();

    // 4. Visual: Resaltar la fila
    $(this).addClass('fila-seleccionada').siblings().removeClass('fila-seleccionada');

    // 5. Buscar los datos en tu objeto global 'vendedores'
    // Asegúrate de que 'vendedores' sea accesible (global o pasada por parámetro)
    if (typeof vendedores !== 'undefined' && vendedores) {
        // Buscamos al vendedor por nombre
        var datosVendedor = Object.values(vendedores).find(v => v.vendedor === nombreVendedor);
        
        if (datosVendedor) {
            console.log("Vendedor seleccionado:", datosVendedor);
            actualizarGraficasPorVendedor(datosVendedor);
        }
    } else {
        console.error("El objeto 'vendedores' no está definido.");
    }
});
function actualizarGraficasPorVendedor(vendedor) {
    // Obtenemos los meses (claves que no son 'vendedor', 'total_c', etc.)
    const meses = mesesKey; // Usamos el array de meses que definimos antes
    const datosCot = meses.map(m => vendedor[m] ? vendedor[m].c : 0);
    const datosFac = meses.map(m => vendedor[m] ? vendedor[m].f : 0);

    // Actualizar Gráfico de Barras (Evolución Mensual)
    if (chartBarras) {
        chartBarras.data.labels = meses;
        chartBarras.data.datasets[0].label = `Cotizaciones de ${vendedor.vendedor}`;
        chartBarras.data.datasets[0].data = datosCot;
        chartBarras.data.datasets[1].label = `Facturadas de ${vendedor.vendedor}`;
        chartBarras.data.datasets[1].data = datosFac;
        chartBarras.update();
    }

    // Actualizar Gráfico de Torta (Proporción Cotizado vs Facturado del Vendedor)
    if (chartTorta) {
        chartTorta.data.labels = ['Cotizaciones Totales', 'Facturadas Totales'];
        chartTorta.data.datasets[0].data = [vendedor.total_c, vendedor.total_f];
        chartTorta.data.datasets[0].backgroundColor = ['#3c8dbc', '#28a745'];
        chartTorta.update();
    }
}
$(document).on('click', '#ReportexCotizaciones thead', function() {
    $('#ReportexCotizaciones tbody tr').removeClass('fila-seleccionada');
    generarGraficas(vendedores);
});