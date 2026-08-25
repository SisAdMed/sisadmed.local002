let table;
let alltext = [];
$(document).ready(function () {
	// Escuchamos el evento dentro del tbody de tu tabla para que funcione con filas dinámicas
	table = $(`#tblDetalle`).DataTable({
		paging: false, // Usualmente los detalles no se paginan para no perder los inputs de vista
		info: false,
		searching: false,
		columns: [
			// 1. Ítem correlativo
			{
				data: null,
				title: "item",
				className: 'text-right text-xs dt-item',
				orderable: false, // <-- Súper importante para evitar que las flechas alteren el orden
				searchable: false,
				render: function (data, type, row, meta) {
					return meta.row + 1;
				}
			},
			// 2. Producto (Hidden + Input + Lupa Modal)
			{
				data: null,
				title: "Descripción",
				width: '30%',
				render: function (data, type, row, meta) {
					var item = meta.row + 1;
					var id = row.id_prod || '';
					var nom = row.nom_prod || '';
					return `
                    <input type="hidden" name="id_prod[]" id="id_prod${item}" value="${id}" class="text-xs photo id_prod">
                    <div class="input-group">
                        <input type="text" class="form-control text-xs" id="nom_prod${item}" name="nom_prod[]" value="${nom}" title="${nom}" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <a href="#" class="btn-buscar-producto" data-item="${item}" data-id_prod = "${id}" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a>
                            </span>
                        </div>
                    </div>`;
				}
			},
			// 3. Cantidad
			{
				data: null,
				title: "Cantidad",
				width: '8%',
				render: function (data, type, row, meta) {
					var item = meta.row + 1;
					var val = row.cant || 1;
					return `<input type="number" name="cant[]" id="cant${item}" value="${val}" class="form-control text-right text-xs reCalcular" min="1" style="width:100%">`;
				}
			},
			// 4. Stock (Disabled)
			{
				data: null,
				title: "Stock",
				width: '8%',
				render: function (data, type, row, meta) {
					var item = meta.row + 1;
					var val = row.stock || 0;
					return `<input type="number" name="stock[]" id="stock${item}" value="${val}" class="form-control text-right text-xs stock" style="width:100%" disabled>`;
				}
			},
			// 5. Unidad de Venta (Readonly)
			{
				data: null,
				title: "Uni.Vta.",
				width: '7%',
				render: function (data, type, row, meta) {
					var item = meta.row + 1;
					var val = row.uni_ven_prod || '';
					return `<input type="text" name="uni_ven_prod[]" id="uni_ven_prod${item}" value="${val}" readonly class="form-control text-right text-xs" style="width:100%">`;
				}
			},
			// 6. Ventas Prod (Readonly)
			{
				data: null,
				title: "PVP Unit.",
				width: '8%',
				render: function (data, type, row, meta) {
					var item = meta.row + 1;
					var val = row.ventas_prod || '0.00';
					return `<input type="text" name="ventas_prod[]" id="ventas_prod${item}" value="${format_number_with_dec_new(val, 4)}" readonly class="form-control text-right text-xs" style="width:100%">`;
				}
			},
			// 7. Ventas Prod 1 (Precio Modificable)
			{
				data: null,
				title: "Precio Vta.",
				width: '8%',
				className: 'text-right text-xs',
				render: function (data, type, row, meta) {
					var item = meta.row + 1;
					var val = row.ventas_prod1 || '0.00';
					return `<input type="text" name="ventas_prod1[]" id="ventas_prod1${item}" value="${format_number_with_dec_new(val, 4)}" class="form-control text-right text-xs reCalcular camponumero" style="width:100%">`;
				}
			},
			// 8. Descuento (Hidden + Input + Lupa Modal)
			{
				data: null,
				title: "Dcto.",
				width: '10%',
				render: function (data, type, row, meta) {
					var item = meta.row + 1;
					var idDes = row.id_des_item || '';
					var nomDes = row.nom_des || '';
					return `
                    <input type="hidden" name="id_des_item[]" id="id_des_item${item}" value="${idDes}" class="text-xs">
                    <div class="input-group">
                        <input type="text" class="form-control text-xs text-right" id="nom_des${item}" name="nom_des[]" value="${nomDes}" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <a href="#" class="btn-buscar-descuento" data-toggle="modal" data-target="#modal-descuentos" title="Buscar y seleccionar descuento"><i class="fas fa-search"></i></a>
                            </span>
                        </div>
                    </div>`;
				}
			},
			// 9. IVA (Select)
			{
				data: "iva_prod",
				title: "IVA",
				width: '10%',
				render: function (data, type, row, meta) {
					var item = meta.row + 1;
					// Determinamos si ya viene un valor guardado (por si la fila tenía datos)
					var seleccionN = (data === 'N') ? 'selected' : '';
					var seleccionS = (data === 'S') ? 'selected' : '';
					// Nota: El select se renderiza vacío; abajo te muestro cómo poblarlo con tus tasas (Exento, 16%, etc.)
					return `
					<select name="iva_prod[]" id="iva_prod${item}" class="form-control text-xs input-iva reCalcular" data-item="${item}">
							<option value="">Seleccione...</option>
							<option value="N" ${seleccionN}>NO</option>
							<option value="S" ${seleccionS}>SI</option>
					</select>
					`;
				}
			},
			// 10. Total Fila (Readonly)
			{
				data: null,
				title: "Sub-Total",
				width: '10%',
				render: function (data, type, row, meta) {
					var item = meta.row + 1;
					var val = row.total || '0.00';
					return `<input type="text" name="total[]" id="total${item}" value="${format_number_with_dec_new(val, 4)}" class="form-control text-right text-xs sub-total input-fila" readonly>`;
				}
			},
			// 11. Acciones (Botones Eliminar y Ver Foto)
			{
				data: null,
				title: "Acciones",
				className: "text-center",
				orderable: false,
				render: function (data, type, row, meta) {
					return `
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger btn-xs btn-borrar text-xs" title="Eliminar item"><i class="far fa-trash-alt"></i></button>
                        &nbsp;&nbsp;
                        <button type="button" class="btn btn-warning btn-xs show-picture text-xs" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos"><i class="fa fa-eye"></i></button>
                    </div>`;
				}
			}
		],
		language: {
			url: `${base_url}/Assets/json/es-ES.json`,
		},
		// Asignamos el atributo data-item al <tr> de forma automática al crearse
		createdRow: function (row, data, dataIndex) {
			$(row).attr('data-item', dataIndex + 1);
			if (parseFloat(data.stock) <= 0 || data.nom_pro == '') {
				$(row).addClass('table-danger'); // Pinta la fila en rojo tenue
			}
		}
	});
	listar_si_no("", `iva_prod${item}`);
	// Delegación de eventos para capturar el clic en el botón de eliminar
	$('#tblDetalle tbody').on('click', '.btn-borrar', function () {
		var filaHTML = $(this).closest('tr');
		// Removemos de la API y redibujamos la tabla de inmediato
		table.row(filaHTML).remove().draw(false);
		recorreTable_fac(1, xtasa, '', tabla);
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
			$(nodoTR).find('.reCalcular').filter('input[type="number"]').attr('id', 'cant' + nuevoItem);
			$(nodoTR).find('.stock').attr('id', 'stock' + nuevoItem);
			$(nodoTR).find('input[name="uni_ven_prod[]"]').attr('id', 'uni_ven_prod' + nuevoItem);
			$(nodoTR).find('input[name="ventas_prod[]"]').attr('id', 'ventas_prod' + nuevoItem);
			$(nodoTR).find('.camponumero').attr('id', 'ventas_prod1' + nuevoItem);
			$(nodoTR).find('input[name="id_des_item[]"]').attr('id', 'id_des_item' + nuevoItem);
			$(nodoTR).find('input[name="nom_des[]"]').attr('id', 'nom_des' + nuevoItem);
			$(nodoTR).find('.input-iva').attr('id', 'iva_prod' + nuevoItem);
			$(nodoTR).find('.sub-total').attr('id', 'total' + nuevoItem);
		});

		// Disparador de cálculos globales si existe
		if (typeof calcularTotalesGenerales === 'function') {
			calcularTotalesGenerales();
		}
	});
	$('#btnAgregarFila').on('click', function () {
		// Estructura limpia del objeto vacío
		var nuevoRegistro = {
			id_producto: '',
			nom_prod: '',
			cantidad: 1,
			stock: 0,
			uni_ven_prod: '',
			ventas_prod: '0,00',
			ventas_prod1: '0,00',
			id_des_item: '',
			nom_des: '',
			iva_prod: '',
			total: '0,00'
		};
		// Agregamos a la API y redibujamos la tabla
		table.row.add(nuevoRegistro).draw(false);

		// Opcional: Si necesitas poblar el select de IVA de la última fila creada
		var totalFilas = table.rows().count();
		if (typeof cargarOpcionesIva === 'function') {
			cargarOpcionesIva(totalFilas);
		}
	});

	// Cuando el usuario hace clic en la lupa de CUALQUIER fila
	$('#tblDetalle tbody').on('click', '.btn-buscar-producto', function (e) {
		e.preventDefault(); // Evita que la página salte al hacer clic en el '#'
		// 1. Capturamos el número exacto de ítem asignado a esa lupa
		var itemSeleccionadoTarget = $(this).attr('data-item') || $(this).closest('tr').attr('data-item');
		// 2. Se lo inyectamos directamente al contenedor del modal
		$('#modal-productos').data('item-target', itemSeleccionadoTarget);
	});
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
		var tblModal = $("#tblModalProd").DataTable({
			aProcessing: true,
			aServerSide: true,
			clear: true,
			destroy: true,
			processing: true,
			fnCreatedRow: function (rowEl, data) {
				$(rowEl).attr("id", data.id_prod)
					.attr("title", "Haga clic para seleccionar este producto")
					.addClass("btn-seleccionar-prod-modal")
					.css("cursor", "pointer") // Para que el mouse cambie a la manito y el usuario sepa que es cliqueable
					.data("id", data.id_prod)
					.data("nombre", data.nom_prod)
					.data("stock", data.stock)
					.data("univta", data.uni_ven_prod || 1) // Si el backend no la manda, por defecto 1
					.data("precio", data.pv1 || 0);

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
			],
		});
	});
	//Ocultar Almacén y Ubicación de Entrada en los consignados
	$("#hide-in").change(function (e) {
		e.preventDefault()
		if ($(this).is(':checked')) {
			$(".hide_entrada").hide();
			listar_almacenes(id_emp, 0, 0, "id_alm_ent");
			listar_ubicaciones("id_ubi_ent", 0, "N", id_emp);
		} else {
			listar_almacenes(id_emp, id_alm_cli, 0, "id_alm_ent");
			listar_ubicaciones("id_ubi_ent", id_ubi_cli, "N", id_emp);
			$(".hide_entrada").show();
		}
	});
	//funcion para elimnar una fila de detalle Nota de Entrega
	$(document).on("click", ".borrar", function (event) {
		event.preventDefault();
		$(this).closest("tr").remove();
		xtasa = formatoMoneda($("#tasa_cambio").val());
		recorreTable_fac(1, xtasa, '', tabla);
	});
	//Seleccionar registro marcado del Modal de clietnes y mostrarlo en el formulario

	//Recalcular en caso de cambiar el IVA a si o No
	$(document).on("change", ".reCalcular", function (event) {
		if (tipo_fac != "COM") {
			item = $(this).closest("tr").data("item");
			CalculateTotalFac();
		}
	});
	//Cargar prodcutos desde el archivo cargado a la tabla
	$("#pdf_file").change(function (e) {
		e.preventDefault();
		//Variables
		var archivo = $(this).val();
		if (archivo && archivo != "") {
			var extensiones = archivo.substring(archivo.lastIndexOf("."));
			//Validar que posea una extension valida para excel
			if (extensiones != ".pdf") {
				Swal.fire({
					icon: "error",
					title: "Error...",
					text: "El archivo de tipo " + extensiones + " no es válido",
				});
				$("#loadinvconsig").val("");
			} else {
				table.clear().draw();
				loadAndRenderPDF(e);
			}
		} else {
			$("#cuerpoTablaDetalle").empty();
		}
		loader.hide()
	});
})
//Facturación
//Notas de Entrega No Fiscal
function initFacturacion() {
	const title = "Facturación - Notas de Entrega No Fiscal";
	const origen = "Facturacion";
	const id_menu = 72;
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
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-toggle="tooltip" data-placement="top" title= "Eliminar registro" data-id="${row.id_cot}" data-code = "${row.num_tdo}" data-name="${row.nom_tdoc}" type="button" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></button>     `;
				}
				t_menu += `<a type="button" class="btn btn-success btn-xs" onclick="copiarBtn(this)" href="${base_url}/Delnotnotfis/nueva/${row.id_cot}" title="Copiar"><i class="fa fa-copy"></i></a>     `;
				t_menu += `<button id="Data" data-id="${row.id_cot}" data-name="${row.nom_tdoc}" data-code = "${row.num_tdo}" type="button" class="btn btn-primary btn-xs" onclick="print_Delnotfis(this)" title="Imprimirr"><i class="fa-solid fa-print"></i></button>`;
				return t_menu;
			},
		},
		{ data: "nombre_emp", tittle: "Empresa" },
		{ data: "nom_tdoc", title: "Tipo" },
		{ data: "num_tdo", title: "Número", className: "text-right" },
		{ data: "nom_ent", title: "Cliente" },
		{ data: "fecha_comp", title: "Fecha", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN) },
		{ data: "codigo_moneda", title: "Moneda", className: "text-center" },
		{ data: "tasa_cambio", title: "Tasa", className: "text-right", render: DataTable.render.number(".", ",", 2) },
		{ data: "nom_vend", title: "Vendedor" },
		{ data: "fuente", title: "Origen" },
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
	], '', '', 5)
}
function CalculateTotalFac() {
	let xcantidad;
	let xprecio_venta;
	let xunidad_venta;
	let iva = 1;
	precio_unit = formatoMoneda($("#ventas_prod" + item).val());
	xcantidad = parseFloat($("#cant" + item).val());
	xprecio_venta = $("#ventas_prod1" + item).val()
	if (xprecio_venta.indexOf(",") !== -1) {
		xprecio_venta = parseFloat(formatoMoneda($(`#ventas_prod1${item}`).val()));
	} else {
		xprecio_venta = parseFloat(xprecio_venta);
	}
	xunidad_venta = parseFloat($("#uni_ven_prod" + item).val());
	$("#ventas_prod" + item).val(
		format_number_with_dec_new(xprecio_venta / xunidad_venta, 2)
	);
	$("#total" + item).val(
		format_number_with_dec_new(xcantidad * xprecio_venta, 2)
	);
	monto = xcantidad * xprecio_venta;
	let modo = "N";
	id_cot = $("#id").val();
	if (id_cot) {
		modo = "M";
	}


	val_col = 0;
	if (tipo_fac == "C") {
		val_vol = -1;
	} else if (tipo_fac == "N") {
		val_vol = 1;
	}
	tasa_cambio = formatoMoneda($("#tasa_cambio").val());

	if (id_cot) {
		recorreTable_fac(val_col, tasa_cambio, tipo_fac, tabla);
	} else {
		recorreTable_fac(val_col, tasa_cambio, tipo_fac, tabla);
	}
}
// Mostar Datos cuando se selecciona una cotizacion
function show_data_origen(id_cot, origen) {
	var url = "";
	var doc_orig;
	if (origen == 'P') {
		url = `${base_url}/Cotizaciones/consultar_cotizacion`;
		doc_orig = "Cotización";
	} else {
		url = `${base_url}/Delnotnotfis/consultar_nota`;
		doc_orig = "Nota de Entrega No Fiscal";
	}

	$.ajax({
		url: url,
		method: 'POST',
		data: { id_cot: id_cot },
		dataSrc: '',
		dataType: 'json',
		beforeSend: function () {
			loader.show();
		},
		complete: function () {
			loader.hide();
		},
		error: function (error) {
			loader.hide();
		},
		success: function (data) {
			if (data) {
				id_moneda_ori = data[0]['id_moneda'];
				tasa_cambio_ori = data[0]['tasa_cambio'];
				num_cotiza = data[0]['num_tdo'];
				id_moneda = $("#id_moneda").val();
				tasa_cambio = formatoMoneda($("#tasa_cambio").val());
				if (id_moneda_ori == id_moneda && tasa_cambio_ori != tasa_cambio) {
					title = `¿Desea utilizar la Tasa de Cambio de la Cotización ${num_cotiza} con el valor de ${format_number_with_dec_new(tasa_cambio_ori, 4)}.?`
					Swal.fire({
						title: title,
						icon: 'question',
						showDenyButton: true,
						confirmButtonText: "Si",
						denyButtonText: `No`,
					}).then((result) => {
						if (result.isConfirmed) {
							tasa_cambio_ori = format_number_with_dec_new(tasa_cambio_ori, 4)
							tasa_cambio = tasa_cambio_ori
							$("#tasa_cambio").val(tasa_cambio_ori);
						}
						//Cargar detalles
						tasa_cambio = $("#tasa_cambio").val();
						$.each(data, function (i, xitem) {
							nuevoRegistro = {
								id_producto: xitem.id_prod,
								nom_prod: xitem.nom_prod,
								cant: xitem.cant_det,
								stock: xitem.stock,
								uni_ven_prod: xitem.uni_vta,
								ventas_prod: xitem.pre_unit,
								ventas_prod1: xitem.pre_vta,
								id_des_item: '',
								nom_des: '',
								iva_prod: xitem.iva_prod,
								total: xitem.sub_total
							};
							table.row.add(nuevoRegistro).draw(false);
							recorreTable_fac(1, formatoMoneda(tasa_cambio), "F", "tblDetalle");
						})
					})
				}
			}
		}
	})

}
function loadAndRenderPDF(e) {
	loader.show();
	var file = e.target.files[0];
	let fr = new FileReader();
	fr.readAsDataURL(file);
	fr.onload = () => {
		let res = fr.result;
		extractText(res);
	};
}
async function extractText(url) {
	loader.show();
	let pdf;
	pdfjsLib.GlobalWorkerOptions.workerSrc = `${base_url}/Assets/js/pdf.worker.js`;
	pdf = await pdfjsLib.getDocument(url).promise;
	let pages = pdf.numPages;
	for (let i = 1; i <= pages; i++) {
		let page = await pdf.getPage(i);
		let txt = await page.getTextContent();
		let text = txt.items.map((s) => s.str).join("");
		alltext.push(txt);
	}
	readpdf(alltext);
}
async function readpdf(text) {
	loader.show();
	//Variables
	var arrayCodCli = new Array();
	var arrayIdProd = new Array();
	var arrayNombre = new Array();
	var arrayCant = new Array();
	var arrayCosto = new Array();
	var arrayIva = new Array();
	var arrayUniVta = new Array();
	var arraySaldo = new Array();
	var arrayConver = new Array();
	var arrayNomFab = new Array();
	var list = {
		datos: [],
	};
	var tlistarray = 0;
	var titem = 0;
	var tencontre = false;
	id_ent = $("#id_cli").val();
	id_emp = $("#id_emp").val();
	//Recorrer Paginas
	for (i = 0; i < text.length; i++) {
		c = text[i].items.length;
		//Rrecorrer cada item

		for (x = 0; x < c; x++) {
			loader.show();
			val = $.trim(text[i].items[x]["str"]);
			if (val == "CORPORACION MMQ, C.A.") {
				titem = 0;
				tlistarray++;
				tencontre = true;
			}
			titem++;
			if (tencontre && titem != 0) {
				if (titem == 2) {
					arrayCodCli[tlistarray] = val;
					url = `${base_url}/Facturacion/equivale`;
					fecha_comp = $("#fecha_comp").val();
					datos = new FormData();
					datos.append("id_prod", val);
					datos.append("id_ent", id_ent);
					datos.append("id_emp", id_emp);
					datos.append("format", 1);
					datos.append("id_alm", id_alm);
					datos.append("id_ubi", id_ubi_consig);
					datos.append("fecha", fecha_comp);
					const respuesta = await fetch(url, {
						method: "POST",
						body: datos,
					});
					const resultado = await respuesta.json();
					if (resultado) {
						arrayIdProd[tlistarray] = resultado[0]["id_prod"];
						arrayNombre[tlistarray] = resultado[0]["nom_prod"];
						arrayUniVta[tlistarray] = resultado[0]["uni_ven_prod"];
						arraySaldo[tlistarray] = resultado[0]["stock"];
						arrayConver[tlistarray] = resultado[0]["conv_prod_cons"];
						arrayNomFab[tlistarray] = resultado[0]["nom_fab"];
					} else {
						console.log('Producto no existe', val);

					}
				} else if (titem == 7) {
					if (val.indexOf("-") >= 0) {
						val1 = format_number_with_out_dec(val) * -1;
					} else {
						val1 = format_number_with_out_dec(val);
					}
					arrayCant[tlistarray] = val1;
				} else if (titem == 8) {
					if (val.indexOf("-") >= 0) {
						val1 = format_number_with_out_dec(val) * -1;
					} else {
						val1 = format_number_with_out_dec(val);
					}
					arrayCosto[tlistarray] = val1;
				} else if (titem == 12) {
					arrayIva[tlistarray] = format_number_with_out_dec(val);
					tencontre = false;
				}
				//
				if (titem == 12) {
					list.datos.push({
						codcli: arrayCodCli[tlistarray],
						nombre: arrayNombre[tlistarray],
						id_prod: arrayIdProd[tlistarray],
						saldo: arraySaldo[tlistarray],
						cant: arrayCant[tlistarray],
						conver: arrayConver[tlistarray],
						nom_fab: arrayNomFab[tlistarray],
						costo: arrayCosto[tlistarray],
						iva: arrayIva[tlistarray],
					});
				}
			}
		}
	}
	json = JSON.stringify(list);
	var obj = JSON.parse(json);

	//Recorrer JSON y llenar tabla
	//Tasa IVA
	item = 0;
	tasa_iva = await xvatTax(fecha_comp, "IVA");
	xtasa_iva = parseFloat(tasa_iva[0]["txr1_iva"]);
	//Tasa Cambio
	if (tipo_fac == "F") {
		$("#id_moneda").trigger("change");
	}
	tasa_cambio = $("#tasa_cambio").val();
	tasa_cambio = formatoMoneda(tasa_cambio);
	tcontador = 0;
	var aggregatedObject = Enumerable.From(obj.datos)
		.GroupBy(
			"{codcli: $.codcli, id_prod: $.id_prod, nombre: $.nombre, saldo: $.saldo, conver: $.conver, nom_fab: $.nom_fab}",
			null,
			function (key, g) {
				var aggregatedObject = {
					item: ++tcontador,
					codcli: key.codcli,
					id_prod: key.id_prod,
					nombre: key.nombre,
					saldo: key.saldo,
					conver: key.conver,
					nom_fab: key.nom_fab,
					costo: g.Sum("$.costo"),
					iva: g.Sum("$.iva"),
					cant: g.Sum("$.cant"),
				};
				return aggregatedObject;
			},
			function (x) {
				return x.id_prod + ":" + x.nombre;
			}
		)
		.ToArray();

	//Ordernar el objeto agregado por nombre

	//aggregatedObject.toSorted((a, b) => a.item + b.item);
	aggregatedObject.sort((a, b) => a.codcli.localeCompare(b.codcli));
	$.each(aggregatedObject, async function (i, xitem) {
		loader.show();
		id_prod = xitem.id_prod;
		cant_det = xitem.cant;
		//Se descomento, para optimizar el proceso de la Facturación de la Metropolita
		//Jose Vargas 15-01-2026 11:09:00
		//xdat_id_pro_rel = await xdat_id_pro(id_prod);  
		//if (xdat_id_pro_rel && id_prod != undefined) { 
		id_prod = xitem.id_prod;
		if (xitem.conver != 1 && handling_conver == 1) {
			cant_det = xitem.cant / xitem.conver;
		}

		nom_prod = xitem.nombre;
		nom_prod_title = xitem.nombre + " Marca: " + xitem.nom_fab + " Cód. Cliente: " + xitem.codcli;
		saldo = xitem.saldo;
		xcolor = "";
		if (xitem.saldo <= 0) {
			saldo = 0;
		}
		if (cant_det > saldo) {
			xcolor = " style=background-color:red; ";
		}
		uni_vta = 1;
		iva_prod = "N";
		xcosto = xitem.costo;
		console.log(xitem);
		if (xcosto != 0) {
			if (xitem.iva != 0) {
				iva_prod = "S";
				xcosto = xcosto * (100 / (xtasa_iva + 100));
			}
			pre_unit = xcosto / cant_det;
			pre_vta = xcosto / cant_det;
			sub_total = xcosto;
			item++;
			nuevoRegistro = {
				id_producto: id_prod,
				nom_prod: nom_prod,
				cant: cant_det,
				stock: saldo,
				uni_ven_prod: uni_vta,
				ventas_prod: pre_unit,
				ventas_prod1: pre_vta,
				id_des_item: '',
				nom_des: '',
				iva_prod: '',
				total: sub_total
			};
			table.row.add(nuevoRegistro).draw(false);
			listar_si_no(iva_prod, "iva_prod" + item);
			recorreTable_fac(1, tasa_cambio, "F", "tblDetalle");
		}
		//}
	});
	loader.hide();
}