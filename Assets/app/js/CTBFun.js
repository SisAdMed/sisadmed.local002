//Contabilidad
//Auxiliares Contables
function initAuxilirCTB() {
	const title = "Contabilidad - Auxiliares Contables ";
	const origen = "AuxiliarCtb";
	const id_menu = 11;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_cre == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_aux}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_aux}" data-name="${row.nombre_aux}" data-code = "${row.cod_aux}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_aux", title: "Id", className: "text-right" },
		{ data: "cod_aux", title: "Código" },
		{ data: "nombre_aux", title: "Nombre" },
		{ data: "agrupa_aux", title: "Agrupa", className: "text-center" },
		{
			data: null,
			title: "Status",
			className: "text-center",
			render: function (data, type, row) {
				if (row.status_aux == 1) {
					return '<span class="badge badge-success">Activo</span>';
				} else if (row.status_aux == 0) {
					return '<span class="badge badge-danger">Inactivo</span>';
				} else if (row.status_aux == 9) {
					return '<span class="badge badge-warning">Por aprobar</span>';
				}
			},
		},
	]);
}
//Cuentas Contables
function initCuentasCtb() {
	const title = "Contabilidad - Cuentas Contables ";
	const origen = "CuentasCtb";
	const id_menu = 12;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{ data: null, title: "Acciones", className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_cre == 1) {
					t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_cta}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-id="${row.id_cta}" data-name="${row.nombre_cta}" data-code = "${row.cod_cta}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
				}
				return t_menu;
			},
		},
		{ data: "id_cta", title: "Id", className: "text-right" },
		{ data: "cod_cta", title: "Código" },
		{ data: "nombre_cta", title: "Nombre" },
		{ data: "agrupa_cta", title: "Agrupador", className: "text-center" }, 
		{ data: "aux_cta", title: "Auxiliar", className: "text-center" },
		{ data: "tip_cta", title: "Tipo"},
		{ data: null, title: "Status", className: "text-center",
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
//Asientos Contables
function initAsientos() {
	const title = "Contabilidad - Asientos Contables";
	const origen = "Asientos";
	const id_menu = 23;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{ data: null, title: "Acciones", className: "text-center",
				render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_cre == 1) {
				t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_comp}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1 && row.ori_comp == "CTB") {
				t_menu += `<button id="Data" data-id="${row.id_comp}" data-name="${row.nombre_tipcom}" data-code = "${row.num_comp}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
					}
					t_menu += `     <a type="button" class="btn btn-primary btn-xs" href="${base_url}/Asientos/print/${row.id_comp}" target="_blank" title='Imprimir asiento'><i class="fa fa-print"></i> </a>`;
				return t_menu;
			}
		},
		{ data: "id_comp", title: "Id", className: "text-right"},
		{ data: "nombre_emp", title: "Empresa" },
		{ data: "nombre_tipcom", title: "Tipo Cbte." },
		{ data: "num_comp", title: "Nro. Cbte.", className: "text-right" },
		{ data: "fecha_comp", title: "Fecha", className: "text-center", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN) },
		{ data: "codigo_moneda", title: "Moneda", className: "text-center" },
		{ data: "tasa_cambio", title: "Cambio", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 2) },
		{ data: "ori_comp", title: "Origen"},
		{ data: null, title: "Status", className: "text-center",
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
//Listar Tipos de Cuentas
function listar_seltipoCuenta(id) {
	//Data
	var data = [
		{ id: "A", name: "Activo" },
		{ id: "P", name: "Pasivo" },
		{ id: "C", name: "Capital" },
		{ id: "I", name: "Ingreso" },
		{ id: "S", name: "Costo"},
		{ id: "E", name: "Egreso" },
		{ id: "T", name: "Contra" },
		{ id: "O", name: "Per Contra"},
	];
	//Seleccionar el dstino
	var $selectElement = $("#tip_cta");
	//Limpiar en caso de que este lleno
	$selectElement.empty();
	//Agrgar registro de Seleccione
	$selectElement.append($("<option>", { value: "", text: "Selecione..." }));
	//Cargar data
	$.each(data, function (index, item) {
		$selectElement.append($("<option>", {
			value: item.id,
			text: item.name,
		}));
	});
	if (id) {
		$(`#tip_cta option[value="${id}"]`).prop('selected', true);
	}
}
