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
let admin = '';
//Cargar confiuraciones generales
//Atributos de DataTable de Index

function IndexDataTable(url, table, report, xcolums, xcol_def = "", xInit = "", xorder = 1, xdir = "DESC") {
	//Crear DataTable
	var val_url = `${base_url}/${url}/cargar_screen_main`;
	report = report + " ";
	tableIndex = $(table).DataTable({
		destroy: true,
		clear: true,
		serverSide: false,
		deferRender: true,
		colResize: true,
		responsive: true,
		autoWidth: true,
		ajax: {
			url: val_url,
			method: "POST",
			dataSrc: "",
			dataType: "json",
			beforeSend: function () {
				// Muestra una ventana de SweetAlert2 con spinner activo
				Swal.fire({
					title: 'Cargando...',
					text: `Procesando los registros de ${report}, por favor espere.`,
					allowOutsideClick: false,
					allowEscapeKey: false,
					showConfirmButton: false,
					didOpen: () => {
						Swal.showLoading(); // Inicia la animación de carga
					}
				});
			},
			complete: function (data) {
				loader.hide();
			},
			error: function (xhr, status, error) {
				loader.hide();
			},
		},
		// Este evento se dispara por cada fila (tr) generada
		createdRow: function (row, data, dataIndex) {
			// Evaluamos si el estatus es 'Inactivo'
			if (data.status === 0 || data.status === "0") {
				// Aplicamos la clase CSS a toda la fila (tr)
				$(row).addClass('registro-inactivo');
			}
		},
		columns: xcolums,
		columnDefs: xcol_def,
		initComplete: function (settings, json) {
			xInit,
				Swal.close();
		},
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
				exportOptions: {
					// 'orthogonal: export' le dice a DataTables: 
					// "Ignora el render.number de la pantalla y búscame el número puro original de la BD"
					orthogonal: 'export'
				}
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
		order: [[xorder, xdir.toLowerCase()]],
	}).buttons().container().appendTo(table + '_wrapper .col-md-6:eq(0)');
}
//Inicio de Aplicaciones
//Cuentas por Cobrar - Movimientos
function initCXCMovement() {
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
function initCotizaciones() {
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
function initAlmacenes() {
	const title = "Inventarios - Almacenes";
	const origen = "Almacen";
	const id_menu = 51;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null, title: "Acciones", className: "text-center",
			render: function (data, type, row) {
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
		{ data: "id_alm", title: "Id", className: "text-right" },
		{ data: "nombre_emp", title: "Empresa" },
		{ data: "nom_alm", title: "Nombre" },
		{
			data: null, title: "Status", className: "text-center",
			render: function (data, type, row) {
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
function initVendedoresTable() {
	const title = "Facturación - Vendedores";
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
		{ data: "ced_vend", title: "Cédula", className: 'text-right' },
		{ data: "nom_vend", title: 'Nombre(s)' },
		{ data: "ape_vend", title: "Apellido(s)" },
		{ data: "email_vend", title: "Email" },
		{ data: "fecing_vend", title: 'Fec. Ing', render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN) },
		{
			data: null, title: "Status", className: "text-center",
			render: function (data, type, row) {
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
function initCalComTable() {
	const title = "Facturación - Cálculo de Comisiones";
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
				if (permisos_cre == 1) {
					t_menu += `<button id="Data" data-id="${row.id}" data-name="${row.fec_ini} - ${row.fec_fin}" type="button" class="btn btn-success btn-xs" onclick="print_excel(this)" title="Imprimir Excel"><i class="fa-solid fa-file-excel"></i></button>     `;
				}
				return t_menu;
			},
		},
		{ data: "id", title: "Id", className: "text-right" },
		{ data: "nombre_emp", title: "Empresa" },
		{ data: "fec_ini", title: "Fec. Inicio", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN) },
		{ data: "fec_fin", title: "Fec. Fin", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN) },
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
function initNomConTable() {
	const title = "Nómina - Conceptos";
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
		{ data: "factop", title: "Fac./Tope/Monto", className: "text-center", render: $.fn.dataTable.render.number(".", ",", 2) },
		{
			data: null, title: "Status", className: "text-center",
			render: function (data, type, row) {
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
function initNomTipNomTable() {
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
		{ data: "nombre_emp", title: "Empresa" },
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
function initNomDepTable() {
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
		{ data: "codigo", title: "Código" },
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
function initZonas() {
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
function initEmpresas() {
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
function get_permiso(id_menu) {
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
	table.ajax.reload(function (json) {
		if (typeof Swal !== 'undefined') {
			Swal.close();
		}
	}, false);
});
//Decodificar valroes con caracteres especiales
function decodificarHTML(html) {
	var txt = document.createElement("textarea");
	txt.innerHTML = html;
	return txt.value;
}
//Mostrar Modal de Oroductos
$("#modal-productos").on("shown.bs.modal", async function (e) {
	let url = "";
	let datos = "";
	if (tipo_fac != "N") {
		id_alm_res = $("#id_alm").val();
		if (id_alm_res == undefined) {
			id_alm_res = false;
		}
	}
	id_emp = $("#id_emp").val();
	if (!id_emp) {
		id_emp = 0;
	}
	id_alm_sal = $("#id_alm_sal").val();

	if (id_alm_sal) {
		id_alm = id_alm_sal;
	}
	id_ubi_sal = $("#id_ubi_sal").val();
	if (id_ubi_sal) {
		id_ubi = id_ubi_sal;
	}
	id_tdo_cfg = await tip_doc_fac(id_emp);
	id_alm_ppal = id_tdo_cfg["id_alm"];
	stock = id_tdo_cfg["cot_stock"];
	if (tipo_fac == "F" || tipo_fac == "NF" || tipo_fac == "N") {
		stock = id_tdo_cfg["fac_stock"];
	}
	if (id_alm_ppal == id_alm) {
		almSalPpal = true;
	}
	if (origen_COM == 0) {
		if (almSalPpal || equivale) {
			datos = { stock: stock, id_emp: "0" };
			url = `${base_url}/Productos/listar_productos_modal`;
		} else if (
			(c_consig == 1 && tipo_fac == "F") ||
			(c_consig == 1 && tipo_fac == "N") ||
			(c_consig == 1 && tipo_fac == "NF")
		) {
			id_alm = $("#id_alm").val();
			if ($("#id_alm_def").val()) {
				id_alm = $("#id_alm_def").val();
			}
			datos = { id_alm: id_alm, id_ubi: id_ubi };
			url = `${base_url}/Productos/listar_productos_modal_consig`;
		} else if (c_consig == 0 && tipo_fac == "F") {
			datos = { stock: stock, id_emp: "0" };
			url = `${base_url}/Productos/listar_productos_modal`;
		} else if (id_alm_res && mov_inv == false) {
			if (tipo_fac == "N") {
				id_cli = 0;
			}
			datos = { id_alm: id_alm_res, id_cli, id_cli };
			url = `${base_url}/Productos/listar_productos_modal_reserva`;
		} else {
			datos = { stock: stock, id_emp: "0" };
			url = `${base_url}/Productos/listar_productos_modal`;
		}
	} else {
		datos = { stock: 0, id_emp: id_emp };
		url = `${base_url}/Productos/listar_productos_modal`;
	}
	$("#tblModalProd").DataTable().clear();
	$("#tblModalProd").DataTable().destroy();
	var tblModal = $("#tblModalProd").DataTable({
		aProcessing: true,
		aServerSide: true,
		fnCreatedRow: function (rowEl, data) {
			$(rowEl).attr("id", data.id_prod)
				.attr("title", "Haga clic para seleccionar este producto")
				.addClass("btn-seleccionar-prod-modal")
				.css("cursor", "pointer") // Para que el mouse cambie a la manito y el usuario sepa que es cliqueable
				.data("id", data.id_prod)
				.data("nombre", data.nom_prod)
				.data("stock", data.stock)
				.data("univta", data.uni_ven_prod || 1) // Si el backend no la manda, por defecto 1
				.data("precio", data.pv1 || 0)
				.data("lote", data.lote);

		},
		order: [[3, "asc"]],
		ajax: {
			url: url,
			type: "POST",
			deferRender: true,
			data: datos,
			dataSrc: "",
			select: true,
		},
		language: {
			url: `${base_url}/Assets/json/es-ES.json`,
		},
		columns: [
			{ data: "id_prod" },
			{ data: "cod_prod" },
			{ data: "cod2_prod" },
			{ data: "nom_prod" },
			{ data: "ref_prod" },
			{ data: "nom_fab" },
			{ data: "stock" },
			{ data: "lote" },
		],
		columnDefs: [
			{
				targets: 0,
				visible: false,
				searchable: false,
			},
			{
				targets: 6,
				className: "text-right",
			},
			{
				targets: 7,
				visible: false,
				searchable: false,
			},
		],
	});
});
//Seleccionar un producto en especifico
// Variable para recordar qué item se va a modificar
$(document).on("click", "#tblModalProd tbody tr", function () {
	// 1. Obtener la fila seleccionada en DataTables
	var tableModal = $('#tblModalProd').DataTable();
	var data = tableModal.row(this).data();
	if (!data) return;
	// 2. Extraer el valor del item guardado
	var item = $('#modal-productos').data('item-activo');
	var suffix = item ? item : ''; // Maneja selectores dinámicos (#id_prod1) o únicos (#id_prod)
	// 3. Extraer y normalizar datos
	var id_prod = data.id_prod || data[0];
	var nom_pro = typeof decodeHTMLEntities === "function" 
        ? decodeHTMLEntities(data.nom_prod || data[1]) 
        : (data.nom_prod || data[1]);
	var tieneLote = parseInt(data.lote || data[7], 10) === 1;
	// 4. Asignar valores a los inputs
	$("#id_prod" + suffix).val(id_prod);
    $("#nom_prod" + suffix).val(nom_pro).attr('title', nom_pro);
	$("#lote" + suffix).prop('readonly', !tieneLote).prop('required', tieneLote);
    $("#fec_venc" + suffix).prop('readonly', !tieneLote).prop('required', tieneLote);
	// 5. Configurar validaciones de Lote y Fecha de Vencimiento
    $("#lote" + suffix)
        .prop('readonly', !tieneLote)
        .prop('required', tieneLote);

    $("#fec_venc" + suffix)
        .prop('readonly', !tieneLote)
        .prop('required', tieneLote);

    // 6. Asignar ubicación por defecto si aplica
    if (typeof id_ubi_cfg !== 'undefined' && id_ubi_cfg) {
        $("#id_ubi" + suffix).val(id_ubi_cfg);
        $("#nom_ubi" + suffix).val(nom_ubi_cfg || '').attr('title', nom_ubi_cfg || '');
    }

    // 7. Consulta adicional si el tipo de factura lo requiere
    if (typeof tipo_fac !== 'undefined' && tipo_fac !== "X") {
        ConsultarProducto(id_prod, item, "", "", "Z", typeof c_consig !== 'undefined' ? c_consig : '', tipo_fac);
    }

    // 8. Cerrar modal y disparar evento solo en el campo modificado
    $("#modal-productos").modal("hide");
    $("#id_prod" + suffix).trigger("change");	
});
//Buscar Producto
$(document).on('click', '.btn-buscar-producto', function (e) {
	e.preventDefault();
	// Obtener el número de ítem de la fila actual (1, 2, 3...)
	var item = $(this).data('item');
	// Guardar el número de ítem en el modal (usando data o en el input hidden #item)
	$('#modal-productos').data('item-activo', item);
	// o $('#item').val(item);

	// Abrir el modal
	$('#modal-productos').modal('show');
});
//Poblar Modal de Ubicaciones
$("#modal-ubicaciones").on("show.bs.modal", function (e) {
	let url = "";
	let datos = new FormData();
	url = `${base_url}/Ubicaciones/listar_ubicaciones`;
	$("#tblModalUbicaciones").DataTable().clear();
	$("#tblModalUbicaciones").DataTable().destroy();
	var tblModal = $("#tblModalUbicaciones").DataTable({
		aProcessing: true,
		aServerSide: true,
		fnCreatedRow: function (rowEl, data) {
			$(rowEl).attr("id", data.id_ubi);
		},
		processing: true,
		ajax: {
			url: url,
			type: "POST",
			deferRender: true,
			data: { id_emp: id_emp, agrupa: "" },
			dataSrc: "",
		},
		language: {
			url: `${base_url}/Assets/json/es-ES.json`,
		},
		columns: [{ data: "id_ubi" }, { data: "cod_ubi" }, { data: "nom_ubi" }],
		columnDefs: [
			{
				targets: 0,
				visible: false,
				searchable: false,
			},
		],
	});
});
//Seleccionar registro marcado del Modal de Ubicaciones y mostrarlo en el formulario
$(document).on("click", "#tblModalUbicaciones tbody tr", function () {
	// 1. Obtener la fila seleccionada en DataTables
	var tableModal = $('#tblModalUbicaciones').DataTable();
	var data = tableModal.row(this).data();
	if (!data) {
		return;
	} else {
		// 2. Extraer el valor del item guardado
		var item = $('#modal-ubicaciones').data('item-activo');
		if (item) {
			// 3. Obtener los valores según las columnas de tu modal (por índice o por nombre de clave)
			// Ejemplo si data es array: data[0] = Código, data[1] = Nombre
			// Ejemplo si data es objeto: data.id_ubi / data.nom_ubi
			var idUbi = data.id_ubi || data[0];
			var nomUbi = data.nom_ubi || data[1];
			// 4. Asignar los valores a los inputs específicos de esa fila
			$('#id_ubi' + item).val(idUbi);
			$('#nom_ubi' + item).val(nomUbi).attr('title', nomUbi);
		} else {
			$("#id_ubi").val(id_ubi);
			$("#id_ubi").trigger("change");
		}
	}
	$("#modal-ubicaciones").modal("hide");
});
//Buscar Ubicacion
$(document).on('click', '.btn-buscar-ubicacion', function (e) {
	e.preventDefault();
	// Obtener el número de ítem de la fila actual (1, 2, 3...)
	var item = $(this).data('item');
	// Guardar el número de ítem en el modal (usando data o en el input hidden #item)
	$('#modal-ubicaciones').data('item-activo', item);
	// o $('#item').val(item);

	// Abrir el modal
	$('#modal-ubicaciones').modal('show');
});
// Coloca la clave PÚBLICA (Public Key) que generaste previamente
const VAPID_PUBLIC_KEY = 'BGhSxPWMmmWwhGtTvzaE_nCe6q96yIYZu10dpEAQWP5XFH1-Hdo7sQw-oFWSNF9aep8aOoMpeSC_A8T6TBedaes';

function urlBase64ToUint8Array(base64String) {
	const padding = '='.repeat((4 - base64String.length % 4) % 4);
	const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
	const rawData = window.atob(base64);
	const outputArray = new Uint8Array(rawData.length);
	for (let i = 0; i < rawData.length; ++i) {
		outputArray[i] = rawData.charCodeAt(i);
	}
	return outputArray;
}

if ('serviceWorker' in navigator && 'PushManager' in window) {
	window.addEventListener('load', function () {
		navigator.serviceWorker.register('/Assets/app/js/sw.js')
			.then(function (swReg) {
				//console.log('Service Worker de SisAdMed registrado con éxito:', swReg);
				solicitarPermisoNotificaciones(swReg);
			})
			.catch(function (error) {
				console.error('Error al registrar el Service Worker:', error);
			});
	});
}

function solicitarPermisoNotificaciones(swReg) {
	Notification.requestPermission().then(function (permission) {
		if (permission === 'granted') {
			swReg.pushManager.subscribe({
				userVisibleOnly: true,
				applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY)
			})
				.then(function (subscription) {
					// Enviar objeto de suscripción al backend PHP vía AJAX
					guardarSuscripcionEnServidor(subscription);
				})
				.catch(function (err) {
					console.error('Error al suscribir al servicio Push:', err);
				});
		} else {
			console.warn('El usuario denegó los permisos de notificación.');
		}
	});
}

function guardarSuscripcionEnServidor(subscription) {
	const url = `${base_url}/Usuarios?action=subscribe`;
	fetch(url, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(subscription)
	})
		.then(response => response.json())
		.then(data => {
			//console.log(data);
			//console.log('Suscripción guardada en el servidor:', data);
		})
		.catch(err => console.error('Error enviando suscripción al backend:', err));
}