//Inventarios
//Variables
let table;
let alltext = [];
let id_cli = '';
tipo_fac = 'X';
let c_comsig = 0;
mov_inv = true;
mov_inv_sal = false;
$(document).ready(function () {
	table = $(".tblEncaMov").DataTable({
		paging: false,
		info: false,
		searching: false,
		autoWidth: false, // Esencial para que respete los anchos en porcentaje
		columns: [
			// 1. Ítem correlativo
			{
				data: null,
				title: "Item",
				width: "4%",
				className: "text-center align-middle dt-item text-xs",
				orderable: false,
				searchable: false,
				render: function (data, type, row, meta) {
					return meta.row + 1;
				}
			},
			// 2. Producto (Hidden + Input + Lupa Modal)
			{
				data: null,
				title: "Producto",
				width: "30%",
				render: function (data, type, row, meta) {
					var item = meta.row + 1;
					var id = row.id_prod || '';
					var nom = row.nom_prod || '';
					return `
                <input type="hidden" name="id_prod${item}" id="id_prod${item}" value="${id}" class="text-xs photo id_prod">
                <div class="input-group">
                    <input type="text" class="form-control text-xs" id="nom_prod${item}" name="nom_prod${item}" value="${nom}" title="${nom}" readonly required>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <a href="#" class="btn-buscar-producto" data-item="${item}" data-id_prod="${id}" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a>
                        </span>
                    </div>
                </div>`;
				}
			},
			// 3. Ubicación
			{
				data: null,
				title: "Ubicación",
				width: "24%",
				render: function (data, type, row, meta) {
					var item = meta.row + 1;
					var id = row.id_ubi || '';
					var nom = row.nom_ubi || '';
					return `
                <input type="hidden" name="id_ubi${item}]" id="id_ubi${item}" value="${id}" class="text-xs photo id_ubi">
                <div class="input-group">
                    <input type="text" class="form-control text-xs" id="nom_ubi${item}" name="nom_ubi${item}" value="${nom}" title="${nom}" readonly required>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <a href="#" class="btn-buscar-ubicacion" data-item="${item}" data-id_ubi="${id}" title="Buscar y seleccionar ubicaciones"><i class="fas fa-search"></i></a>
                        </span>
                    </div>
                </div>`;
				}
			},
			// 4. Lote
			{
				data: null,
				title: "Lote",
				width: "14%",
				render: function (data, type, row, meta) {
					var item = meta.row + 1;
					var val = (row.lote !== null && row.lote !== undefined) ? String(row.lote).trim() : '';

					// Si el objeto tiene un producto asignado y usa lote (o ya tiene texto en lote)
					var usaLote = (parseInt(row.usa_lote, 10) === 1) || (val !== '');

					var readonlyAttr = usaLote ? '' : 'readonly';
					var requiredAttr = usaLote ? 'required' : '';

					return `<input type="text" name="lote_${item}" id="lote${item}" value="${val}" class="form-control text-xs text-uppercase txt-lote" ${readonlyAttr} ${requiredAttr}>`;
				}
			},
			// 5. Fecha Vencimiento
			{
				data: null,
				title: "Fecha Venc.",
				width: "12%",
				render: function (data, type, row, meta) {
					var item = meta.row + 1;
					var rawVal = row.fec_venc;
					var val = (rawVal && rawVal !== '0000-00-00') ? String(rawVal).trim() : '';
					var loteVal = (row.lote !== null && row.lote !== undefined) ? String(row.lote).trim() : '';

					var usaLote = (parseInt(row.usa_lote, 10) === 1) || (val !== '') || (loteVal !== '');

					var readonlyAttr = usaLote ? '' : 'readonly';
					var requiredAttr = usaLote ? 'required' : '';

					return `<input type="date" name="fec_venc_${item}" id="fec_venc${item}" value="${val}" class="form-control text-xs txt-fec-venc" ${readonlyAttr} ${requiredAttr}>`;
				}
			},
			// 6. Cantidad
			{
				data: null,
				title: "Cantidad",
				width: "10%",
				className: "text-right align-middle",
				render: function (data, type, row, meta) {
					var item = meta.row + 1;
					var val = row.cantidad || '';
					return `<input type="number" name="cantidad${item}" id="cantidad${item}" value="${val}" class="form-control text-xs text-right required" min="1">`;
				}
			},
			// 7. Acciones
			{
				data: null,
				title: "Acciones",
				width: "6%",
				className: "text-center align-middle",
				orderable: false,
				searchable: false,
				render: function (data, type, row, meta) {
					return `<button type="button" class="btn btn-danger btn-xs btn-borrar text-xs" title="Eliminar item"><i class="far fa-trash-alt"></i></button>`;
				}
			},
			{ data: "lote_prod", title: "Usa Lote", visible: false },

		],
		drawCallback: function (settings) {
			var api = this.api();

			// Recorrer cada fila renderizada
			api.rows().every(function (rowIdx, tableLoop, rowLoop) {
				var data = this.data();
				var $row = $(this.node());

				// Determinar si el producto usa lote
				// (Verifica si la bandera usa_lote es 1, o si el campo lote trae contenido)
				var manejaLote = (parseInt(data.lote_prod, 10) === 1) || (data.lote && data.lote.toString().trim() !== '');

				var $inputLote = $row.find("input[id^='lote']");
				var $inputFecVenc = $row.find("input[id^='fec_venc']");

				if (manejaLote) {
					// Habilitar y hacer requerido
					$inputLote.prop('readonly', false).prop('required', true);
					$inputFecVenc.prop('readonly', false).prop('required', true);
				} else {
					// Bloquear y remover requerido
					$inputLote.prop('readonly', true).prop('required', false).val('');
					$inputFecVenc.prop('readonly', true).prop('required', false).val('');
				}
			});
		},
		language: {
			url: `${base_url}/Assets/json/es-ES.json`,
		},
	});
	// Agregar un nuevo registro
	$("#btnAddRow").on('click', function (e) {
		e.preventDefault();
		// 1. Sincronizar inputs del DOM con la data interna de DataTables
		table.rows().every(function () {
			var d = this.data();
			var $row = $(this.node());

			d.id_prod = $row.find("input[id^='id_prod']").val() || d.id_prod;
			d.nom_prod = $row.find("input[id^='nom_prod']").val() || d.nom_prod;
			d.id_ubi = $row.find("input[id^='id_ubi']").val() || d.id_ubi;
			d.nom_ubi = $row.find("input[id^='nom_ubi']").val() || d.nom_ubi;
			d.lote = $row.find("input[id^='lote']").val();
			d.fec_venc = $row.find("input[id^='fec_venc']").val();
			d.cantidad = $row.find("input[id^='cantidad']").val();
			// Si el lote no está bloqueado, marcamos que usa lote
			d.lote_prod = !$row.find("input[id^='lote']").prop('readonly') ? 1 : 0;

			this.data(d); // Actualiza la memoria interna sin redibujar aún
		});
		var newRow = {
			id_prod: '',
			nom_prod: '',
			id_ubi: '',
			nom_ubi: '',
			lote: '',
			fec_venc: '',
			cantidad: '',
			lote_prod: 0,
		};
		// 1. Agregar a DataTables y redibujar (esto crea los inputs en el DOM)
		table.row.add(newRow).draw(false);
	})
	// Delegación de eventos para capturar el clic en el botón de eliminar
	$('.tblEncaMov tbody').on('click', '.btn-borrar', function () {
		var filaHTML = $(this).closest('tr');
		// Removemos de la API y redibujamos la tabla de inmediato
		table.row(filaHTML).remove().draw(false);
	});
	// ==========================================
	// 3. RE-INDEXADO DE CORRELATIVOS E IDS (EVENTO DRAW CORREGIDO)
	// ==========================================
	table.on('draw.dt', function () {
		// Buscamos y recorremos las filas basándonos estrictamente en el orden visual aplicado en pantalla
		table.rows({ order: 'applied', search: 'applied' }).every(function (rowIdx) {

			// El 'rowIdx' interno de DataTables ya no nos sirve para el correlativo visual,
			// así que calculamos la posición real de la fila en el DOM actual:
			var nodoTR = this.node(); // El elemento <tr>
			var nuevoItem = $(nodoTR).index() + 1; // Su posición física en el tbody (1, 2, 3...)			
			$("#item").val(nuevoItem);
			// 1. Re-indexamos el atributo del TR y el texto visible de la primera columna (item)
			$(nodoTR).attr('data-item', nuevoItem);
			$('td', nodoTR).eq(0).html(nuevoItem);

			// 2. Re-indexamos dinámicamente cada uno de los IDs de los inputs internos
			$(nodoTR).find('.id_prod').attr('id', 'id_prod' + nuevoItem);
			$(nodoTR).find('input[name="nom_prod[]"]').attr('id', 'nom_prod' + nuevoItem);
			$(nodoTR).find('.id_ubi').attr('id', 'id_ubi' + nuevoItem);
			$(nodoTR).find('input[name="nom_ubi[]"]').attr('id', 'nom_ubi' + nuevoItem);
			$(nodoTR).find('.lote').attr('id', 'lote' + nuevoItem);
			$(nodoTR).find('.fec_venc').attr('id', 'fec_venc' + nuevoItem);
			$(nodoTR).find('.cantidad').attr('id', 'cantidad' + nuevoItem);
			$(nodoTR).find('.lote_prod').attr('id', 'cantilote_proddlote_prodad' + nuevoItem);
		});
	});

});
//Inventario - Productos
function initProductosTable() {
	const title = "Inventarios - Productos";
	const origen = "Productos";
	const id_menu = 52;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title,
		[
			{
				data: null,
				title: "Acciones",
				className: "text-center",
				render: function (data, type, row) {
					var t_menu = "";
					if (permisos_cre == 1 || permisos_rea == 1) {
						t_menu += `<a type="button" data-toggle="tooltip" data-placement="top" title="Consultar registro" class="btn btn-warning btn-xs all" href="${base_url}/${origen}/gestion/${row.token_edit}"><i class="fa fa-edit"></i></a>     `;
					}
					if (permisos_del == 1) {
						t_menu += `<button id="Data" data-toggle="tooltip" data-placement="top" title= "Eliminar registro" data-id="${row.id_prod}" data-name="${row.nom_prod}" data-code = "${row.cod_prod}" type="button" class="btn btn-danger btn-xs btn-delete all"><i class="fa fa-trash"></i></button>     `;
					}
					if (row.fotos > 0) {
						t_menu += `<a type="button" data-toggle="tooltip" data-placement="top" title="Ver fotos de producto" data-id="${row.id_prod}" data-code="${row.cod_prod}" data-name="${row.nom_prod}" class="btn btn-success btn-xs btn-ver-fotos all"><i class="fa fa-eye"></i></a>     `;
					}
					//Copiar producto
					if (permisos_cre == 1) {
						t_menu += `<button id="Data" data-toggle="tooltip" data-placement="top" title= "Copiar registro" data-id="${row.id_prod}" data-name="${row.nom_prod}" data-code = "${row.id_prod}" type="button" class="btn btn-primary btn-xs btn-clonar all"><i class="fa fa-copy"></i></button>     `;
					}
					return t_menu;
				},
			},
			{ data: "cod_prod", title: "Código" },
			{ data: "cod2_prod", title: "Código 2" },
			{ data: "nom_prod", title: "Descripción" },
			{ data: "ref_prod", title: "Referencia" },
			{ data: "nom_fab", title: "Marca" },
			{
				data: null, title: "Fotos", className: "text-center",
				render: function (data, type, row) {
					if (row.fotos > 0) {
						return '<span class="badge badge-primary">Si</span>';
					} else {
						return '<span class="badge badge-danger">No</span>';
					}
				}
			},
			{ data: "fotos", title: "Cant.", className: "text-center" },
			{
				data: "costo_prod", title: "Costo", className: "text-right", render: function (data, type, row) {
					// Si DataTables está pidiendo la data para el botón de Excel ('export'),
					// le devolvemos el número puro original sin tocarlo
					if (type === 'export') {
						return data;
					}

					// Para la pantalla ('display'), le aplicamos tu formato de siempre
					return $.fn.dataTable.render.number(".", ",", 4).display(data);
				}
			},
			{
				data: "flete_prod", title: "Flete", className: "text-right", render: function (data, type, row) {
					// Si DataTables está pidiendo la data para el botón de Excel ('export'),
					// le devolvemos el número puro original sin tocarlo
					if (type === 'export') {
						return data;
					}

					// Para la pantalla ('display'), le aplicamos tu formato de siempre
					return $.fn.dataTable.render.number(".", ",", 4).display(data);
				}
			},
			{
				data: "otros_prod", title: "Ot.Cargos", className: "text-right", render: function (data, type, row) {
					// Si DataTables está pidiendo la data para el botón de Excel ('export'),
					// le devolvemos el número puro original sin tocarlo
					if (type === 'export') {
						return data;
					}

					// Para la pantalla ('display'), le aplicamos tu formato de siempre
					return $.fn.dataTable.render.number(".", ",", 4).display(data);
				}
			},
			{
				data: "door_costo", title: "Costo Door to Door", className: "text-right", render: function (data, type, row) {
					// Si DataTables está pidiendo la data para el botón de Excel ('export'),
					// le devolvemos el número puro original sin tocarlo
					if (type === 'export') {
						return data;
					}

					// Para la pantalla ('display'), le aplicamos tu formato de siempre
					return $.fn.dataTable.render.number(".", ",", 4).display(data);
				}
			},
			{
				data: null, title: "Costo 1", className: "text-right",
				render: function (data, type, row) {
					if (type === 'export') {
						return row.costo_prod + row.flete_prod + row.otros_prod + row.door_costo;
					}

					// Para la pantalla ('display'), le aplicamos tu formato de siempre
					return $.fn.dataTable.render.number(".", ",", 4).display(row.costo_prod + row.flete_prod + row.otros_prod + row.door_costo);

				}
			},
			{
				data: "recar_prod", title: "% Util.", className: "text-right", render: function (data, type, row) {
					// Si DataTables está pidiendo la data para el botón de Excel ('export'),
					// le devolvemos el número puro original sin tocarlo
					if (type === 'export') {
						return data;
					}

					// Para la pantalla ('display'), le aplicamos tu formato de siempre
					return $.fn.dataTable.render.number(".", ",", 4).display(data);
				}
			},
			{
				data: "ventas_prod", title: "Venta", className: "text-right", render: function (data, type, row) {
					// Si DataTables está pidiendo la data para el botón de Excel ('export'),
					// le devolvemos el número puro original sin tocarlo
					if (type === 'export') {
						return data;
					}

					// Para la pantalla ('display'), le aplicamos tu formato de siempre
					return $.fn.dataTable.render.number(".", ",", 4).display(data);
				}
			},
			{ data: "bultos", title: "Bul.Emp" },
			{ data: "nom_pre", title: "Unidades" },
			{ data: "empaque", title: "Empaque" },
			{ data: "grupo_nombre", title: "Grupo" },
			{ data: "gen_prod", title: "Nombre Genérico" },
			{ data: "des_prod", title: "Descripción del Producto" },
			{ data: "uni_com_prod", title: "Un. Compra", className: "text-right" },
			{ data: "uni_ven_prod", title: "Un. Venta", className: "text-right" },
			{ data: "con_cons_prod", title: "Util. Consig", className: "text-right" },
			{ data: "conv_prod_cons", title: "Vta. Consig", className: "text-right" },
			{
				data: null, title: "IVA", className: "text-center",
				render: function (data, type, row) {
					if (row.iva_prod == 1) {
						return 'Si';
					} else {
						return 'No';
					}
				}
			},
			{
				data: null, title: "Lote", className: "text-center",
				render: function (data, type, row) {
					if (row.lote_prod == 1) {
						return 'Si';
					} else {
						return 'No';
					}
				}
			},
			{
				data: null, title: "Interno", className: "text-center",
				render: function (data, type, row) {
					if (row.interno_prod == 1) {
						return 'Si';
					} else {
						return 'No';
					}
				}
			},
			{
				data: null, title: "Door to Door", className: "text-center",
				render: function (data, type, row) {
					if (row.door_prod == 1) {
						return 'Si';
					} else {
						return 'No';
					}
				}
			},
			{
				data: "recar2_prod", title: "% Utilidad Consig", className: "text-right", render: function (data, type, row) {
					// Si DataTables está pidiendo la data para el botón de Excel ('export'),
					// le devolvemos el número puro original sin tocarlo
					if (type === 'export') {
						return data;
					}

					// Para la pantalla ('display'), le aplicamos tu formato de siempre
					return $.fn.dataTable.render.number(".", ",", 4).display(data);
				}
			},
			{
				data: "venta2_prod", title: "Venta Consignación", className: "text-right", render: function (data, type, row) {
					// Si DataTables está pidiendo la data para el botón de Excel ('export'),
					// le devolvemos el número puro original sin tocarlo
					if (type === 'export') {
						return data;
					}

					// Para la pantalla ('display'), le aplicamos tu formato de siempre
					return $.fn.dataTable.render.number(".", ",", 4).display(data);
				}
			},
			{
				data: null, title: "Origen", className: "text-center",
				render: function (data, type, row) {
					if (row.origen == "I") {
						return 'Importado';
					} else {
						return 'Nacional';
					}
				}
			},
			{
				data: "alto", title: "Alto", className: "text-right", render: function (data, type, row) {
					// Si DataTables está pidiendo la data para el botón de Excel ('export'),
					// le devolvemos el número puro original sin tocarlo
					if (type === 'export') {
						return data;
					}

					// Para la pantalla ('display'), le aplicamos tu formato de siempre
					return $.fn.dataTable.render.number(".", ",", 4).display(data);
				}
			},
			{
				data: "ancho", title: "Ancho", className: "text-right", render: function (data, type, row) {
					// Si DataTables está pidiendo la data para el botón de Excel ('export'),
					// le devolvemos el número puro original sin tocarlo
					if (type === 'export') {
						return data;
					}

					// Para la pantalla ('display'), le aplicamos tu formato de siempre
					return $.fn.dataTable.render.number(".", ",", 4).display(data);
				}
			},
			{
				data: "largo", title: "Largo", className: "text-right", render: function (data, type, row) {
					// Si DataTables está pidiendo la data para el botón de Excel ('export'),
					// le devolvemos el número puro original sin tocarlo
					if (type === 'export') {
						return data;
					}

					// Para la pantalla ('display'), le aplicamos tu formato de siempre
					return $.fn.dataTable.render.number(".", ",", 4).display(data);
				}
			},
			{ data: "adicional", title: "Adicional", className: "text-center" },
			{ data: "stock", title: "Stock", className: "text-right" },
			{ data: "creado_por", title: "Creado por" },
			{ data: "create_date", title: "Creado el" },
			{ data: "modificado_por", title: "Modificado por" },
			{ data: "modify_date", title: "Modificado el" },
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
		],
		"",
		function (settings, json) {
			var api = this.api();
			if (json['data'][0].admin != 1) {
				for (var i = 8; i <= 35; i++) {
					api.column(i).visible(false);
				}

			}
		},
		3, "ASC")
}
//Inventario - Estado de productos
function initStateProducts() {
	const title = 'Estado de productos';
	const origen = 'StateProducts';
	const id_menu = 205;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null, title: "Acciones", className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 || permisos_rea == 1) {
					t_menu += `<a type="button" data-toggle="tooltip" data-placement="top" title="Consultar registro" class="btn btn-warning btn-xs all" href="${base_url}/${origen}/gestion/${row.token_edit}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-toggle="tooltip" data-placement="top" title= "Eliminar registro" data-id="${row.id}" data-code = "${row.estado}" type="button" class="btn btn-danger btn-xs btn-delete all"><i class="fa fa-trash"></i></button>     `;
				}
				return t_menu;
			}
		},
		{ data: "estado", title: "Estado del producto" },
		{
			data: "icono", title: "Icono", className: "text-center",
			render: function (data, type, row) {
				// Validación: si no hay imagen en la BD, usamos una por defecto				
				let imagenUrl = data ? data : `no_picture.jpg`;
				return `
                    <div class="text-center">
                        <img src="${base_url}/Assets/img/${imagenUrl}"
                            alt="${row.estado}" 
                            class="img-thumbnail rounded" 
							title="${row.estado}" 
                            style="width: 32px; height: 32px; object-fit: cover;"
                            onerror="this.onerror=null; this.src='${base_url}/Assets/img/no_picture.jpg';">
                    </div>
                `;
			},
			orderable: false, // Desactiva ordenar por la columna de imagen
			searchable: false // Desactiva buscar texto en esta columna
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
//Inventario - Movimientos de Inventario
function initMovInvTable() {
	const title = 'Movimientos de Inventario';
	const origen = 'MovInv';
	const id_menu = 97;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{ data: "id_movinv", title: "Id", className: "text-right", visible: false },
		{
			data: null, title: "Acciones", className: "text-center",
			render: function (data, type, row) {				
				var t_menu = "";
				if (permisos_cre == 1 || permisos_rea == 1) {
					t_menu += `<a type="button" data-toggle="tooltip" data-placement="top" title="Consultar registro" class="btn btn-warning btn-xs all" href="${base_url}/${origen}/gestion/${row.token_edit}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1 && $.trim(row.origen) === '') {
					t_menu += `<button id="Data" data-toggle="tooltip" data-placement="top" title= "Eliminar registro" data-id="${row.id_movinv}" data-code = "${row.cod_tmoinv}" data-name="${row.nom__tmoinv}" data-number="${row.num_movinv}" type="button" class="btn btn-danger btn-xs btn-delete all"><i class="fa fa-trash"></i></button>     `;
				}
				t_menu += `<button id="Data" data-id="${row.id_movinv}" data-name="${row.cod_tmoinv} - ${row.nom__tmoinv}  - ${row.num_movinv}" type="button" class="btn btn-primary btn-xs" onclick="printer_movement(this)" title="Imprimir"><i class="fa-solid fa-print"></i></button>`;
				return t_menu;
			}
		},
		{ data: "nombre_emp", title: "Empresa" },
		{ data: "cod_tmoinv", title: "Tipo" },
		{ data: "nom__tmoinv", title: "Descripción" },
		{ data: "num_movinv", title: 'Número', className: "text-right" },
		{ data: "fecha_comp", title: "Fecha", className: "text-center", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN) },
		{ data: "origen", title: "Origen" },
		{ data: "nom_ent", title: "Cliente / Proveedor" },
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
	],
		"",
		"",
		"0",
		"DESC"
	)
}
//Change Utility
function initChangeUtility() {
	const title = 'Cambio de Utilidad Masiva';
	const origen = 'ChangeUtility';
	const id_menu = 204;
	get_permiso(id_menu);
	IndexDataTable(origen, tblIndexMain, title, [
		{
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 || permisos_rea == 1) {
					t_menu += `<a type="button" data-toggle="tooltip" data-placement="top" title="Consultar registro" class="btn btn-warning btn-xs all" href="${base_url}/${origen}/gestion/${row.token_edit}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1 && data.aprobado === null) {
					t_menu += `<button id="Data" data-toggle="tooltip" data-placement="top" title= "Eliminar registro" data-id="${row.id}" data-code = "${row.fecha}" type="button" class="btn btn-danger btn-xs btn-delete all"><i class="fa fa-trash"></i></button>     `;
				}
				return t_menu;
			},
		},
		{
			data: "fecha", title: "Fecha",
			render: function (data, type, row) {
				if (!data) return "";
				if (type === 'sort' || type === 'type') {
					return moment(data).isValid() ? moment(data).format('YYYYMMDDHHmmss') : data;
				}
				return moment(data).format(TO_PATTERNHH);
			},
		},
		{ data: "creado_por", title: "Creado por" },
		{ data: "create_date", title: "Creado el" },
		{ data: "modificado_por", title: "Modificado por" },
		{ data: "modify_date", title: "Modificado el" },
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
		{
			data: null,
			title: "Aprobado",
			className: "text-center",
			render: function (data, type, row) {
				if (row.aprobado == 1) {
					return '<input type="checkbox" class="check-item" checked disabled> ';
				} else {
					return `<input type="checkbox" class="check-item btn-aprobar" unchecked >`;
				}
			}
		}
	])
}
/**
 * Obtener Datos de Presentaciones
 *
 * @param {*} id id del producto seleccionado (opcional)
 * @param {string} [tag='']  Etiqueta del formulario
 */
function getPresentacion(id, tag) {
	const url = `${base_url}/Presentaciones/getPresentacion`;
	//Ajax para 
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: '',
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
			var $combo = $(`#${tag}`);
			// Limpiar combo antes de rellenar
			$combo.empty();
			// Añadir opción por defecto
			$combo.append('<option value="">Seleccione...</option>');
			// Iterar y añadir opciones
			$.each(data, function (index, valor) {
				selected = valor.id_pre == id ? 'selected' : '';
				$combo.append(`<option ${selected} value=${valor.id_pre}> ${valor.nom_pre}</option>`);
			});
		},
	});
}
/**
 * Obtener Datos de Marcas
 *
 * @param {*} id id de la marca seleccionada (opcional)
 * @param {*} tag Etiqueta del formulario
 */
function getMarcas(id, tag) {
	const url = `${base_url}/Fabricantes/getMarcas`;
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: '',
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
			var $combo = $(`#${tag}`);
			// Limpiar combo antes de rellenar
			$combo.empty();
			// Añadir opción por defecto
			$combo.append('<option value="">Seleccione...</option>');
			// Iterar y añadir opciones
			$.each(data, function (index, valor) {
				selected = valor.id_fab == id ? 'selected' : '';
				$combo.append(`<option ${selected} value=${valor.id_fab}> ${valor.nom_fab}</option>`);
			});
		},
	});
}
/**
 * Obtener Grupos
 *
 * @param {*} id id del grupo seleccionado (opcional)
 * @param {*} tag Etiqueta del formulario
 */
function getGrupos(id, tag) {
	const url = `${base_url}/Grupos/getGrupos`;
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: '',
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
			var $combo = $(`#${tag}`);
			// Limpiar combo antes de rellenar
			$combo.empty();
			// Añadir opción por defecto
			$combo.append('<option value="">Seleccione...</option>');
			// Iterar y añadir opciones
			$.each(data, function (index, valor) {
				selected = valor.id_grupo == id ? 'selected' : '';
				$combo.append(`<option ${selected} value=${valor.id_grupo}> ${valor.grupo_nombre}</option>`);
			});
		},
	});
}
/**
 * Obtener Origen
 *
 * @param {*} id Id del Origen seleccionado
 */
function getorigen(id) {
	miCombo = $("#origen");
	var data = [
		{ id: 'N', name: 'Nacional' },
		{ id: 'I', name: 'Importado' }
	]
	miCombo.empty();
	miCombo.append('<option value="">Seleccione...</option>');
	$.each(data, function (index, valor) {
		selected = valor.id == id ? 'selected' : '';
		miCombo.append(`<option ${selected} value=${valor.id}> ${valor.name}</option>`);
	});
}
/**
 * Obtener Sub Grupos
 *
 * @param {*} id id del subgrupo seleccionado 
 * @param {*} tag Etiqueta del Formulario
 */
function getSubgrupos(id, tag, id_sub_grupo = '') {
	const url = `${base_url}/SubGrupos/getSubgrupos`;
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: { id: id },
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
			var $combo = $(`#${tag}`);
			// Limpiar combo antes de rellenar
			$combo.empty();
			// Añadir opción por defecto
			$combo.append('<option value="">Seleccione...</option>');
			// Iterar y añadir opciones
			$.each(data, function (index, valor) {
				selected = valor.id == id_sub_grupo ? 'selected' : '';
				$combo.append(`<option ${selected} value=${valor.id}> ${valor.sub_grupo_nombre}</option>`);
			});
		},
	});
}
/**
 * Obtener Estado del prodcuto
 * @param {*} id id del Estado del pructo
 * @param {*} tag Etiqueta del Estado de producto
 */
function getStateProducts(id, tag) {
	const url = `${base_url}/StateProducts/getStateProducts`;
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: {},
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
			var $combo = $(`#${tag}`);
			// Limpiar combo antes de rellenar
			$combo.empty();
			// Añadir opción por defecto
			$combo.append('<option value="">Seleccione...</option>');
			// Iterar y añadir opciones
			$.each(data, function (index, valor) {
				selected = valor.id == id ? 'selected' : '';
				$combo.append(`<option ${selected} value=${valor.id}> ${valor.estado}</option>`);
			});
		},
	});
}