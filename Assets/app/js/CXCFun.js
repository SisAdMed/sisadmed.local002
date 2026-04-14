//Cuentas por Cobrar 
//Tipos de Documentos
function initTipoDocCXCTable() {
	const title = "Cuentas por Cobrar - Tipos de Documentos ";
	const origen = "TipoDocCXC";
	const id_menu = 49;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_cre == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_tdoc}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_tdoc}" data-name="${row.nom_tdoc}" data-code = "${row.tipo_tdoc}" type="button" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_tdoc", title: "Id", className: "text-right", },
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
					return '<input type="checkbox" checked disabled></input>';
				} else {
					return '<input type="checkbox" unchecked disabled></input>';
				}
			},
		},
		{ data: "num_tdoc", title: "Próximo", className: "text-right" },
		{ data: "cod_cta", title: "Cuenta" },
		{ data: "cod_aux", title: "Auxiliar" },
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
//Tipos de Movimientos
function initTipoMovCXC() {
	const title = "Cuentas por Cobrar - Tipos de Movimientos ";
	const origen = "TipoMovCXC";
	const id_menu = 85;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_cre == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_tmocxc}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_tmocxc}" data-name="${row.des_tmocxc}" data-code = "${row.cod_tmocxc}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_tmocxc", title: "Id", className: "text-right", },
		{ data: "nombre_emp", title: "Empresa" },
		{ data: "cod_tmocxc", title: "Código", className: "text-center" },
		{ data: "des_tmocxc", title: "Descripción" },
		{ data: "acc_tmocxc", title: "Acción" },
		{ data: "rec_tmocxc", title: "Rel.Caja" },
		{
			data: null, title: "Usa Consec.", className: "text-center",
			render: function (data, type, row) {
				if (row.con_tmocxc == "S") {
					return '<input type="checkbox" checked disabled></input>';
				} else {
					return '<input type="checkbox" unchecked disabled></input>';
				}
			}
		},
		{ data: "next_tmocxc", title: "Próximo", className: "text-right" },
		{ data: "cod_cta", title: "Cuenta" },
		{ data: "cod_aux", title: "Auxiliar" },
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
//Conceptos
function initConcepCXC() {
	const title = "Cuentas por Cobrar - Conceptos ";
	const origen = "ConcepCXC";
	const id_menu = 87;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_cre == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id}" data-name="${row.nombre_con}" data-code = "${row.codigo_con}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id", title: "Id", className: "text-right" },
		{ data: "nombre_emp", title: "Empresa" },
		{ data: "codigo_con", title: "Código" },
		{ data: "nombre_con", title: "Nombre" },
		{ data: "agrupa_con", title: "Agrupa", className: "text-center" },
		{ data: "id_ctbcue", title: "Cuenta Contable" },
		{ data: "id_ctbaux", title: "Cuenta Auxiliar" },
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
};
//Clientes
function initClientes() {
	const title = "Cuentas por Cobrar - Clientes ";
	const origen = "Clientes";
	const id_menu = 53;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null, title: "Acciones", className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_cre == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_ent}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_ent}" data-name="${row.nom_ent}" data-code = "${row.rif_ent}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			}
		},
		{ data: "id_ent", title: "Id", className: "text-right" },
		{ data: "rif_ent", title: "RIF" },
		{ data: "nom_ent", title: "Nombre" },
		{ data: "nombre_zona", title: "Zona" },
		{ data: "vendedor", title: "Vendedor" },
		{ data: "nombre_pais", title: "País" },
		{ data: "nombre_edo", title: "Estado" },
		{ data: "nombre_emp", title: "Empresa" },
		{ data: "nom_motcam", title: "Motivo de Cambio" },
		{ data: "codigo_moneda", title: "Moneda" },
		{
			data: null, title: "Status", className: "text-center",
			render: function (data, type, row) {
				var status = "";
				var clase = "";
				if (row.status == 1) {
					status = "Activo"; clase = "badge-success";
				} else if (row.status == 0) {
					status = "Inactivo"; clase = "badge-danger";
				} else if (row.status == 9) {
					status = "Por aprobar"; clase = "badge-warning";
				}
				return `<span class="badge ${clase}">${status}</span>`;
			}
		},
	]);
};
//Credit Days
function initCreditDays() {
	const title = "Cuentas por Cobrar - Días de Crédito ";
	const origen = "CreditDays";
	const id_menu = 91;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_cre == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_diascre}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_diascre}" data-name="${row.des_diascre}" data-code = "${row.cod_diascre}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_diascre", title: "Id", className: "text-right" },
		{ data: "cod_diascre", title: "Días de Crédito", className: "text-right" },
		{ data: "des_diascre", title: "Descripción" },
		{
			data: null,
			title: "Status",
			className: "text-center",
			render: function (data, type, row) {
				var status = "";
				var clase = "";
				if (row.status == 1) {
					status = "Activo";
					clase = "badge-success";
				} else if (row.status == 0) {
					status = "Inactivo";
					clase = "badge-danger";
				} else if (row.status == 9) {
					status = "Por aprobar";
					clase = "badge-warning";
				}
				return `<span class="badge ${clase}">${status}</span>`;
			},
		},
	]);
}
//Documentos
function initCXCDocument() {
	const title = "cuentas por Cobrar - Documentos ";
	const origen = "CXCDocument";
	const id_menu = 90;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_cre == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_cot}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_cot}" data-name="${row.nom_tdoc}" data-code = "${row.num_tdo}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				t_menu += `     <a type="button" class="btn btn-primary btn-xs" href="${base_url}/${origen}/print_CXCDocument/${row.id_cot}" target="_blank" title='Imprimir asiento'><i class="fa fa-print"></i> </a>`;
				return t_menu;
			},
		},
		{ data: "id_cot", title: "Id", className: "text-right" },
		{ data: "nombre_emp", title: "Emrpresa" },
		{ data: "tipo_codigo", title: "Código" },
		{ data: "nom_tdoc", title: "Descripción" },
		{ data: "num_tdo", title: "Número", className: "text-right" },
		{
			data: null, title: "Control", className: "text-right",
			render: function (row, display, data) {
				return `00-${data.nro_control}`;
			}
		},
		{ data: "fecha_comp", title: "Fecha", className: "text-center", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN) },
		{ data: "nom_ent", title: "Cliente" },
		{ data: "codigo_moneda", title: "Moneda", className: "text-center" },
		{ data: "tasa_cambio", title: "Tasa", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 2) },
		{ data: "mon_doc", title: "Monto", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 2) },
		{ data: "sal_doc", title: "Saldo", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 2) },
		{
			data: null,
			title: "Status",
			className: "text-center",
			render: function (data, type, row) {
				var status = "";
				var clase = "";
				if (row.status == 1) {
					status = "Activo";
					clase = "badge-success";
				} else if (row.status == 0) {
					status = "Inactivo";
					clase = "badge-danger";
				} else if (row.status == 9) {
					status = "Por aprobar";
					clase = "badge-warning";
				}
				return `<span class="badge ${clase}">${status}</span>`;
			},
		},
	]);
}
//Configuración
function initConfigCXCTable() {
	const title = "Cuentas por Cobrar - Configuración ";
	const origen = "ConfigCXC";
	const id_menu = 94;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_cre == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_config}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_config}" data-name="${row.nom_empresa}" data-code = "${row.nom_empresa}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_config", title: "Id", className: "text-right" },
		{ data: "nom_empresa", title: "Empresa" },
		{
			data: null,
			title: "Status",
			className: "text-center",
			render: function (data, type, row) {
				var status = "";
				var clase = "";
				if (row.status == 1) {
					status = "Activo";
					clase = "badge-success";
				} else if (row.status == 0) {
					status = "Inactivo";
					clase = "badge-danger";
				}
				return `<span class="badge ${clase}">${status}</span>`;
			},
		},
	]);
}
//Mostrar Modal de Documentos para elaboración de nOta de Credito y/o Debito
$("#modal_DocAfectadoCXC").on("show.bs.modal", function () {
	const url = `${base_url}/CXCDocument/getDocCXC`;
	var id_cli = $("#id_cli").val();
	var id_moneda = $("#id_moneda").val();
	$.ajax({
		url: url,
		method: "POST",
		data: { id_cli: id_cli, id_moneda: id_moneda },
		dataType: "json",
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			$("#tblmodal_DocAfectadoCXC").DataTable({
				destroy: true,
				data: data,
				responsive: true,
				processing: true,
				columns: [
					{ data: "id_cot", title: "Id" },
					{ data: "tipo_codigo", title: "Código" },
					{ data: "nom_tdoc", title: "Descripción" },
					{ data: "num_tdo", title: "Número", className: "text-right" },
					{ data: "nro_control", title: "Control", className: "text-right" },
					{ data: "fecha_comp", render: $.fn.dataTable.render.moment( FROM_PATTERN, TO_PATTERN ),title: "Fecha Emi."},
					{ data: "fecha_venci", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN), title: "Fecha Venc."},
					{ data: "codigo_moneda", title: "Moneda", className: "text-center"},
					{ data: "tasa_cambio", className: "text-right", render: DataTable.render.number(".", ",", 8), title: "Tasa Cambio"},
					{ data: "mon_doc", className: "text-right", title: "Monto Doc.", render: DataTable.render.number(".", ",", 2)},						
					{ data: "sal_doc", className: "text-right", title: "Saldo Doc.", render: DataTable.render.number(".", ",", 2)},
				],
				language: {
					url: `${base_url}/Assets/json/es-ES.json`,
				},
			});
		},
		complete: function () {
			$(".loader").hide();
		},
		error: function (error) {
			$(".loader").hide();
			console.log("Error al cargar los datos: ", error);
		},
	});
});
$("body").on("click", "#tblmodal_DocAfectadoCXC tr", function () {
	var row_select = $(this).closest("tr");
	var datosFila = row_select
			.find("td")
			.map(function () {
				return $(this).text();
			})
			.get();
			docAfectado = datosFila[3] + "/" + datosFila[5] + "/" + datosFila[9] + "/" + datosFila[4] + "/" + datosFila[7] ;
	$("#doc_afectado").val(docAfectado);
	$("#id_afectado").val( datosFila[0]);
	$("#modal_DocAfectadoCXC").modal("hide");
});