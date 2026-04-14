//Cuentas por Pagar - Tipos de Documentos
function initTipoDocCXP(){
    const title = "Cuentas por Pagar - Tipos de Documentos ";
    const origen = "TipoDocCXP";
    const id_menu = 104;
    get_permiso(id_menu);    
    IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_upd == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_tdoc}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_tdoc}" data-name="${row.nom_tdoc}" data-code = "${row.tipo_codigo}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_tdoc", title: "Id", className: "text-right" },
		{ data: "nombre_emp", title: "Empresa" },
		{ data: "tipo_codigo", title: "Código", className: "text-center" },
		{ data: "nom_tdoc", title: "Nombre" },
		{ data: "tipo_tdoc", title: "Tipo Doc." },
		{
			data: null,
			title: "Usa Consec.",
			className: "text-center",
			render: function (data, type, row) {
				if (row.con_tdoc == 1) {
					return `<input type="checkbox" checked disabled>`;
				} else {
					return `<input type="checkbox" unchecked disabled>`;
				}
			},
		},
		{
			data: null,
			title: "Req. Aprob.",
			className: "text-center",
			render: function (data, type, row) {
				if (row.sol_aprob == 1) {
					return `<input type="checkbox" checked disabled>`;
				} else {
					return `<input type="checkbox" unchecked disabled>`;
				}
			},
		},
		{ data: "num_tdoc", title: "Próximo", className: "text-right" },
		{ data: "cod_cta", title: "Cuenta Contable" },
		{ data: "cod_aux", title: "Auxiliar Contable" },
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
//Cuentas por Pagar - Tipos de Movimientos
function initTipoMovCXP(){
	const title = "Cuentas por Pagar - Tipos de Movimientos ";
	const origen = "TipoMovCXP";
	const id_menu = 105;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_upd == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_tmocxc}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_tmocxc}" data-name="${row.des_tmocxc}" data-code = "${row.cod_tmocxc}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_tmocxc", title: "Id", className: "text-right" },
		{ data: "nombre_emp", title: "Empresa" },
		{ data: "cod_tmocxc", title: "Código" },
		{ data: "des_tmocxc", title: "Descripción" },
		{ data: "acc_tmocxc", title: "Acción" },
		{ data: "rec_tmocxc", title: "Rel. Caja" },
		{
			data: null,
			title: "Usa Conse.",
			className: "text-center",
			render: function (data, type, row) {
				if (row.con_tmocxc == "S") {
					return "<input type='checkbox' checked disabled>";
				} else {
					return "<input type='checkbox' unchecked disabled>";
				}
			},
		},
		{ data: "next_tmocxc", title: "Próximo", className: "text-right" },
		{ data: "nombre_cta", title: "Cuenta Contable" },
		{ data: "nombre_aux", title: "Auxiliar Contable" },
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
//Cuentas por Pagar - Conceptos
function initConcepCXP(){
	const title = "Cuentas por Pagar - Conceptos ";
	const origen = "ConcepCXP";
	const id_menu = 107;
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
					t_menu += `<button id="Data" data-id="${row.id}" data-name="${row.nombre_con}" data-code = "${row.codigo_con}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id", title: "Id", className: "text-right" },
		{ data: "codigo_con", title: "Código" },
		{ data: "nombre_con", title: "Nombre" },
		{ data: "agrupa_con", title: "Agrupador", className: "text-center" },
		{ data: "descrip", title: "Retención" },
		{ data: "por_reten", title: "porcentaje", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 2) },
		{ data: "id_ctb", title: "Cuenta Contable"},
		{ data: "id_aux", title: "Auxiliar Contable"},
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
//Cuentas por Pagar - Proveedores
function initProveedores(){
	const title = "Cuentas por Pagar - Proveedores ";
	const origen = "Proveedores";
	const id_menu = 108;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_upd == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_ent}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_ent}" data-name="${row.nom_ent}" data-code = "${row.rif_ent}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_ent", title: "Id"},
		{ data: "rif_ent", title: "RIF"},
		{ data: "nom_ent", title: "Nombre"},
		{ data: "nombre_pais", title: "Pais"},
		{ data: "nombre_edo", title: "Estado"},
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
//Cuentas por Pagar - Documentos
function initCXPDocument(){
	const title = "Cuentas por Pagar - Documentos ";
	const origen = "CXPDocument";
	const id_menu = 109;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_upd == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_cot}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_cot}" data-name="${row.nom_tdoc} del Proveedor ${row.nom_ent}" data-code = "${row.tipo_codigo} ${row.num_tdo}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>     `;
				}
				if(row.ret_iva != 0){
					t_menu += `<a type="button" class="btn btn-primary btn-xs" data-code="${row.num_tdo}" href="${base_url}/CXPDocument/print_RetIva/${row.id_cot}" target="_blank"><i class="fa-solid fa-print" title="Imprimir Retención de IVA"></i></a>     `;
				}
				if(row.ret_islr != 0){
					t_menu += `<a type="button" class="btn btn-info btn-xs" data-code="${row.num_tdo}" href="${base_url}/CXPDocument/print_RetISLR/${row.id_cot}" target="_blank"><i class="fa-solid fa-print" title="Imprimir Retención de ISLR"></i></a>     `;
				}	
				t_menu += `<a type="button" class="btn btn-success btn-xs" data-code="${row.num_tdo}" href="${base_url}/CXPDocument/print_CXPDocument/${row.id_cot}" target="_blank"><i class="fa-solid fa-print" title="Imprimir Documento"></i></a>`;
				return t_menu;
			},
		},
		{ data: "id_cot", title: "Id", className: "text-right"},
		{ data: "nombre_emp", title: "Empresa" },
		{ data: "tipo_codigo", title: "Código", className: "text-center" },
		{ data: "nom_tdoc", title: "Descripción" },
		{ data: "num_tdo", title: "Número", className: "text-right" },
		{ data: "fecha_comp", title: "Fecha", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN)},
		{ data: "nom_ent", title: "Proveedor"},
		{ data: "codigo_moneda", title: "Moneda", className: "text-center"},
		{ data: "tasa_cambio", title: "Tasa", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 2)},
		{ data: "mon_doc", title: "Monto", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 2)},
		{ data: "sal_doc", title: "Saldo", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 2)},
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
//Cuentas por Pagar - Movimientos
function initCXPMovement(){
	const title = "Cuentas por Pagar Movimientos";
	const origen = "CXPMovement";
	const id_menu = 110;
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
				if (permisos_del == 1 && row.movem_origen == "CXP") {
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
		{ data: "nom_ent", title: "Proveedor" },
		{ data: "codigo_moneda", title: "Moneda", className: "text-center" },
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