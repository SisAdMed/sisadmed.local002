//Variables
let url;
let table_index;
let tableIndex;
let tblIndexMain = "#tblIndexMain";;
let permisos_cre = '';
let permisos_rea = '';
let permisos_upd = '';
let permisos_del = '';
let id_menu;
//Cargar confiuraciones generales
//Atributos de DataTable de Index

function IndexDataTable(url, table, report, xcolums, xcol_def = "") {
	//Crear DataTable
	var val_url = `${base_url}/${url}/cargar_screen_main`;
	tableIndex = $(table).DataTable({
		destroy: true,
		clear: true,		
		serverside: true,
		deferRender: true,
		colResize: true,
		responsive: true, 
  		autoWidth: true,  
		stateSave: true,
		ajax: {
			url: val_url,
			method: "POST",
			dataSrc: "",
			data: {},
			dataType: "json",
			beforeSend: function () {
				loader.show();
			},
			complete: function (data) {
				loader.hide();
			},
			error: function (xhr, status, error) {
				loader.hide();
			},
		},
		columns: xcolums,
		language: {
			url: `${base_url}/Assets/json/es-ES.json`,
		},
		// mostrar botones de exportacion
		dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
         		"<'row'<'col-sm-12'tr>>" +
         		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [
			{
				extend: "copyHtml5",
				text: "<i class='fa fa-copy'></i>",
				titleAttr: "Copiar",
				className: "btn btn-secondary",
				title:
					report + new Date().toISOString().replace(/[\-\:\.]/g, ""),
			},
			{
				extend: "excelHtml5",
				text: "<i class='fa fa-file-excel'></i>",
				titleAttr: "Exportar a Excel",
				className: "btn btn-warning",
				title:
					report + new Date().toISOString().replace(/[\-\:\.]/g, ""),
			},
			{
				extend: "pdfHtml5",
				text: "<i class='fa fa-file-pdf'></i>",
				titleAttr: "Exportar a PDF",
				className: "btn btn-danger",
				title:
					report + new Date().toISOString().replace(/[\-\:\.]/g, ""),
			},
			{
				extend: "csvHtml5",
				text: "<i class='fa fa-file-text'></i>",
				titleAttr: "Exportar a CSV",
				className: "btn btn-primary",
				title:
					report + new Date().toISOString().replace(/[\-\:\.]/g, ""),
			},
		],
		lengthMenu: [
			[5, 10, 25, 50, -1],
			[5, 10, 25, 50, "Todos"],
		],
		iDisplayLength: 5,
		order: [[1, "DESC"]],
	}).buttons().container().appendTo(table + '_wrapper .col-md-6:eq(0)');
}
//Inicio de Aplicaciones
//Cuentas por Cobrar - Movimientos
function initCXCMovement(){
	const title = "Cuentas por Cobrar Movimientos";
	const origen = "CXCMovement";
	const id_menu = 95;
	get_permiso(id_menu);
		IndexDataTable(origen, tblIndexMain, title, [
			{
				data: null,
				title: "Acciones",
				className: "text-center",
				render: function (data, type, row) {
					var t_menu = "";
					if (permisos_cre == 1 || permisos_upd == 1) {
						t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_movement}"><i class="fa fa-edit"></i></a>     `;
					}
					if (permisos_del == 1 && row.movem_origen === "CXC") {						
						t_menu += `<button id="Data" data-id="${row.id_movement}" data-name="${row.des_tmocxc}" data-code = "${row.cod_tmocxc}" type="button" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></button>     `;
					}
					if (permisos_cre == 1) {
						t_menu += `<button id="Data" data-id="${row.id_movement}" data-name="${row.cod_tmocxc} - ${row.des_tmocxc}  - ${row.movem_number}" type="button" class="btn btn-primary btn-xs" onclick="print_mov(this)" title="Imprimir"><i class="fa-solid fa-print"></i></button>`;
					}
					return t_menu;
				},
			},
			{
				data: "id_movement",
				title: "Id",
				className: "text-right",
				visible: false,
			},
			{ data: "nombre_emp", title: "Empresa" },
			{ data: "cod_tmocxc", title: "Código", className: "text-center" },
			{ data: "des_tmocxc", title: "Descripción" },
			{ data: "movem_number", title: "Número", className: "text-right" },
			{
				data: "fecha_comp",
				title: "Fecha",
				render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
			},
			{ data: "nom_ent", title: "Cliente" },
			{
				data: "codigo_moneda",
				title: "Moneda",
				className: "text-center",
			},
			{
				data: "tasa_cambio",
				title: "Tasa",
				className: "text-right",
				render: $.fn.dataTable.render.number(".", ",", 8),
			},
			{
				data: "movem_amount",
				title: "Monto",
				className: "text-right",
				render: $.fn.dataTable.render.number(".", ",", 2),
			},
			{
				data: null,
				title: "Status",
				className: "text-center",
				render: function (data, type, row) {
					if (row.status == 1) {
						return '<span class="badge badge-success">Activo</span>';
					} else if (row.status == 0) {
						return '<span class="badge badge-danger">Inactivo</span>';
					} else if (row.status == 9) {
						return '<span class="badge badge-warning">Por aprobar</span>';
					}
				},
			},
		]);
}
//Facturación - Cotizaciones
function initCotizaciones(){
	const title = "Cotizaciones";
	const origen = "Cotizaciones";
	const id_menu = 56;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 || permisos_upd == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_cot}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1 && row.id_cont == null) {
					t_menu += `<button id="Data" data-id="${row.id_cot}" data-name="${row.num_tdo}" data-code = "${row.num_tdo}" type="button" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></button>     `;
				}
				if (permisos_cre == 1) {
					t_menu += `<button id="Data" data-id="${row.id_cot}" data-code="${row.num_tdo}" data-name="${row.codigo_moneda}" type="button" class="btn btn-primary btn-xs" onclick="print_cotiza(this, 1)" title="Imprimir"><i class="fa-solid fa-print"></i></button>     `;
					t_menu += `<button id="Data" data-id="${row.id_cot}" data-name="${row.codigo_moneda}" data-code = "${row.num_tdo}" type="button" class="btn btn-success btn-xs" onclick="print_cotiza_excel(this, 2)" title="Imprimir Excel"><i class="fa-solid fa-file-excel"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_cot", title: "Id", className: "text-right" },
		{ data: "nombre_emp", title: "Empresa" },
		{ data: "nom_tdoc", title: "Tipo" },
		{ data: "num_tdo", title: "Número", className: "text-right" },
		{ data: "nom_ent", title: "Cliente" },
		{
			data: "fecha_comp",
			title: "Fecha",
			render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
		},
		{ data: "codigo_moneda", title: "Moneda", className: "text-center" },
		{
			data: "tasa_cambio",
			title: "Tasa",
			className: "text-right",
			render: $.fn.dataTable.render.number(".", ",", 8),
		},
		{ data: "nom_vend", title: "Vendedor" },
		{ data: "id_cont", title: "Factura" },
		{ data: "creado_por", title: "Creado por" },
		{ data: "modificdo_por", title: "Modificado por" },
		{
			data: null,
			title: "Status",
			className: "text-center",
			render: function (data, type, row) {
				if (row.status == 1) {
					return '<span class="badge badge-success">Activo</span>';
				} else if (row.status == 0) {
					return '<span class="badge badge-danger">Inactivo</span>';
				} else if (row.status == 9) {
					return '<span class="badge badge-warning">Por aprobar</span>';
				}
			},
		},
	]);
}
//Inventarios - Almacenes
function initAlmacenes(){
	const title = "Inventarios - Almacenes";
	const origen = "Almacen";
	const id_menu = 51;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null, title: "Acciones", className: "text-center",
			render: function(data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_cre == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_alm}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_alm}" data-name="${row.nom_alm}" data-code = "${row.cod_alm}" type="button" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_alm", title: "Id", className: "text-right"},
		{ data: "nombre_emp", title: "Empresa"},
		{ data: "nom_alm", title: "Nombre"},
		{ data: null, title: "Status", className: "text-center",
			render: function(data, type, row){
				if (row.status == 1) {
					return '<span class="badge badge-success">Activo</span>';
				} else if (row.status == 0) {
					return '<span class="badge badge-danger">Inactivo</span>';
				} else if (row.status == 9) {
					return '<span class="badge badge-warning">Por aprobar</span>';
				}
			}
		},
	]);
}
// Facturación Vendedores
function initVendedoresTable(){
	const title ="Facturación - Vendedores";
	const origen = "Vendedores";
	const id_menu = 57;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_upd == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_vend}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_vend}" data-name="${row.id_vend} ${row.ape_vend}" type="button" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_vend", title: "Id", className: "text-right" },
		{ data: "ced_vend", title: "Cédula", className: 'text-right'},
		{ data: "nom_vend", title: 'Nombre(s)'},
		{ data: "ape_vend", title: "Apellido(s)"},
		{ data: "email_vend", title: "Email"},
		{ data: "fecing_vend", title: 'Fec. Ing', render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN)},
		{ data: null, title: "Status", className: "text-center",
			render: function(data, type, row){
				if (row.status == 1) {
					return '<span class="badge badge-success">Activo</span>';
				} else if (row.status == 0) {
					return '<span class="badge badge-danger">Inactivo</span>';
				} else if (row.status == 9) {
					return '<span class="badge badge-warning">Por aprobar</span>';
				}
			}
		},
	]);
}
// Facturación - Cálculo de Comisiones
function initCalComTable(){
	const title ="Facturación - Cálculo de Comisiones";
	const origen = "CalCom";
	const id_menu = 184;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_upd == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id}"><i class="fa fa-edit"></i></a>     `;					
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id}" data-name="${row.fec_ini} - ${row.fec_fin}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>     `;
				}
				if(permisos_cre ==1){
					t_menu += `<button id="Data" data-id="${row.id}" data-name="${row.fec_ini} - ${row.fec_fin}" type="button" class="btn btn-success btn-xs" onclick="print_excel(this)" title="Imprimir Excel"><i class="fa-solid fa-file-excel"></i></button>     `;
				}
				return t_menu;
			},
		},
		{ data: "id", title: "Id", className: "text-right" },
		{ data: "nombre_emp", title: "Empresa" }, 
		{ data: "fec_ini", title: "Fec. Inicio", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN)},
		{ data: "fec_fin", title: "Fec. Fin", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN)},
		{ data: 'vendedor', title: 'Vendedor' },
		{
			data: null,
			title: "Status",
			className: "text-center",
			render: function (data, type, row) {
				if (row.status == 1) {
					return '<span class="badge badge-success">Activo</span>';
				} else if (row.status == 0) {
					return '<span class="badge badge-danger">Inactivo</span>';
				} else if (row.status == 9) {
					return '<span class="badge badge-warning">Por aprobar</span>';
				}
			},
		},
	]);
}
//Nómina - Conceptos
function initNomConTable(){
	const title ="Nómina - Conceptos";
	const origen = "NomCon";
	const id_menu = 144;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,	
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {	
				var t_menu = "";
				if (permisos_cre == 1 && permisos_upd == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_nomcue}"><i class="fa fa-edit"></i></a>     `;					
				}		
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_nomcue}" data-name="${row.nombre}" data-code = "${row.codigo}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_nomcue", title: "Id", className: "text-right" },
		{ data: "codigo", title: "Código", className: "text-center" },
		{ data: "nombre", title: "Nombre" },
		{ data: "tipo", title: "Tipo", className: "text-center" },
		{ data: "parametro", title: "Parámetro", className: "text-center" },
		{ data: "factop", title: "Fac./Tope/Monto", className: "text-center", render: $.fn.dataTable.render.number(".", ",", 2)},
		{ data: null, title: "Status", className: "text-center",
			render: function(data, type, row){
				if (row.status == 1) {
					return '<span class="badge badge-success">Activo</span>';
				} else if (row.status == 0) {
					return '<span class="badge badge-danger">Inactivo</span>';
				} else if (row.status == 9) {
					return '<span class="badge badge-warning">Por aprobar</span>';
				}
			}
		},
	]);
}
//Nómina - Tipo de Nómina
function initNomTipNomTable(){
	const title = 'Nómina - Tipo de Nóminas';
	const origen = 'NomTipNom';
	const id_menu = 138;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_upd == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_nomtip}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_nomtip}" data-name="${row.nombre}" data-code = "${row.codigo}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_nomtip", title: "Id", className: "text-right" },
		{ data: "nombre_emp", title: "Empresa"},
		{ data: "codigo", title: "Código", className: "text-center" },
		{ data: "nombre", title: "Nombre" },
		{ data: "freq", title: "Frecuencia" },
		{ data: "tipo", title: "Tipo" },
		{ data: "contrato", title: "Contrato" },
		{ data: "fecha", title: "Últ. Nómina", className: "text-center", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN) },
		{
			data: null,
			title: "Status",
			className: "text-center",
			render: function (data, type, row) {
				if (row.status == 1) {
					return '<span class="badge badge-success">Activo</span>';
				} else if (row.status == 0) {
					return '<span class="badge badge-danger">Inactivo</span>';
				} else if (row.status == 9) {
					return '<span class="badge badge-warning">Por aprobar</span>';
				}
			},
		},
	]);
}
//Nómina - Departamentos
function initNomDepTable(){
	const title = 'Nómina - Departamentos';
	const origen = 'NomDep';
	const id_menu = 140;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_upd == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_nomdep}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_nomdep}" data-name="${row.nombre}" data-code = "${row.codigo}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_nomdep", title: "Id", className: "text-right" },
		{ data: "codigo", title: "Código"},
		{ data: "nombre", title: "Nombre" },
		{ data: "agrupa", title: "Agrupa" },
		{ data: "nom_aux", title: "Auxiliar" },
		{
			data: null,
			title: "Status",
			className: "text-center",
			render: function (data, type, row) {
				if (row.status == 1) {
					return '<span class="badge badge-success">Activo</span>';
				} else if (row.status == 0) {
					return '<span class="badge badge-danger">Inactivo</span>';
				} else if (row.status == 9) {
					return '<span class="badge badge-warning">Por aprobar</span>';
				}
			},
		},
	]);
}
//General  - Zonas
function initZonas(){
	const title = "General - Zonas";
	const origen = "Zonas";
	const id_menu = 106;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_upd == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_zona}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_zona}" data-name="${row.nombre_zona}" data-code = "${row.cod_zona}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_zona", title: "Id", className: "text-right" },
		{ data: "cod_zona", title: "Código" },
		{ data: "nombre_zona", title: "Nombre" },
		{
			data: null,
			title: "Status",
			className: "text-center",
			render: function (data, type, row) {
				if (row.status == 1) {
					return '<span class="badge badge-success">Activo</span>';
				} else if (row.status == 0) {
					return '<span class="badge badge-danger">Inactivo</span>';
				} else if (row.status == 9) {
					return '<span class="badge badge-warning">Por aprobar</span>';
				}
			},
		},
	]);
}
//Configuraciónn General - Empresas
function initEmpresas(){
	const title = "Configuración General - Empresas ";
	const origen = "Empresas";
	const id_menu = 22;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_upd == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_emp}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_emp}" data-name="${row.nombre_emp}" data-code = "${row.cod_emp}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_emp", title: "Id", className: "text-right" },
		{ data: "cod_emp", title: "Código" },
		{ data: "nombre_emp", title: "Nombre" },
		{ data: "rif_empresa", title: "Rif" },
		{
			data: null,
			title: "Status",
			className: "text-center",
			render: function (data, type, row) {
				if (row.status == 1) {
					return '<span class="badge badge-success">Activo</span>';
				} else if (row.status == 0) {
					return '<span class="badge badge-danger">Inactivo</span>';
				} else if (row.status == 9) {
					return '<span class="badge badge-warning">Por aprobar</span>';
				}
			},
		},
	]);
}
//Obtener permisos de la aplicacion
function get_permiso(id_menu){
	//Preparar el Loading
	//Verificar Permisos de la Aplicación
	url_per = `${base_url}/Usuarios/get_permisos`;
	$.ajax({
		url: url_per,
		method: "POST",
		data: { id: id_menu },
		dataSrc: "",
		dataType: "json",
		async: false,
		beforeSend: function () {
			loader.show();
		},
		complete: function () {
			loader.hide();
		},
		error: function (xhr, status, error) {
			loader.hide();
		},
		success: function (data) {
			permisos_cre = data.c;
			permisos_rea = data.r;
			permisos_upd = data.u;
			permisos_del = data.d;
		},
	});
}
//Obtener Fechas Abiertas
function get_empresa_config(id_emp) {
	let config = {};
	const url = `${base_url}/Empresas/get_empresa_config`;
	try {
		$.ajax({
			url: url,
			method: "POST",
			dataSrc: "",
			data: { id_emp: id_emp },
			dataType: "json",
			async: false,
			success: function (data) {
				config = data;

			},
		});
	} catch (error) {
		console.log("Error al obtener configuración de la empresa", error);
	}
	return config;
}
//Función para recargar el datatable
$(document).on("click", ".refresh-button", function () {	
	// Busca la tabla por su ID de forma directa
    var table = $('#tblIndexMain').DataTable(); 
    table.ajax.reload(null, false);
});