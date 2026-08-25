let table = 'tblDetalle';
let alltext = [];
$(document).ready(function () {
	//Inicializar variables
	tipo_fac = 'P';
	//Validar datos del Formulario
	jQuery.validator.setDefaults({
		debug: false,
		success: "valid",
	});
	$("form#my_form").validate({
		ignore: [],
		rules: {
			id_emp: "required",
			id_tdo: "required",
			fecha_comp: "required",
			nom_cli: "required",
			id_moneda: "required",
			id_vend: "required",
			item: "required",
		},
		messages: {
			id_emp: "Debe especificar una Empresa",
			id_tdo: "Debe especificar un Tipo de Documentos",
			fecha_comp: "Desbe especificar una Fecha válida",
			nom_cli: "Debe especificar un Cliente",
			id_moneda: "Debe especificar una Moneda",
			id_vend: "Debe especificar un Vendedor",
			item: "Debe especificar al menos un detalle",
		},
		submitHandler: function (form) {
			let formData = new FormData(form);
			var boton = $("#btnok");
			boton.prop('disabled', true);
			$.ajax({
				url: `${base_url}/Cotizaciones/store`,
				type: 'POST',
				data: formData,
				contentType: false,
				proccessData: false,
				dataType: 'json',
				beforeSend: function () {
					Swal.fire({
						title: 'Procesando',
						text: 'Guardando registro',
						allowOutsideClick: false,
						didOpen: () => { Swal.shoeLoading(); }
					});
				},
				success: function (data) {
					if (data.icon === 'success') {
						Swal.fire({
							icon: data.icon,
							title: data.title,
							text: data.msg
						}).then(() => {
							window.location.href = `${base_url}\Carousel`;
						});
					} else {
						Swal.fire({
							icon: data.icon,
							title: data.title,
							text: data.msg
						});
					}
				},
				error: function (xhr, status, error) {
					let mensajeDetalle = 'Ocurrió un problema inesperado en el servidor';
					if (xhr.responseJSON && xhr.responseJSON.message) {
						mensajeDetalle = xhr.responseJSON.message
					} else if (xhr.responseText) {
						console.error('Respuesta cruda del servidor:', xhr.responseTexr);
					}
					Swal.fire({
						icon: 'error',
						title: 'Error de Validación',
						html: mensajeDetalle
					});
				}
			});
			boton.prop('disabled', false);
		}
	});
	// Crear la regla personalizada llamada "minimoUnItem"
	jQuery.validator.addMethod("minimoUnItem", function (value, element) {
		// 1. Contamos las filas que tiene la tabla usando la API nativa de DataTables
		let totalFilas = tableDetalle.rows().count();

		// 2. Si hay al menos 1 fila, la validación pasa (true), si está vacía, falla (false)
		return totalFilas > 0;
	}, "Debe especificar al menos un detalle en la cotización.");
	//Cargar el index
	form = $("form").attr("id");
	if (form === undefined) {
		initCotizaciones();
	} else {
		id = $("#id").val();
		if (id) {
			dat_form(id);
		} else {
			dat_form_new();
		}
	}
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
	// Variable para recordar qué item se va a modificar
	$("body").on("click", "#tblModalProd tr", function () {
		// 1. Recuperamos con jQuery el ítem que la lupa guardó en el contenedor del modal
		var sel_item = $('#modal-productos').data('item-target');
		id_prod = $(this).attr("id");
		if (!item && item != 0) {
			$("#id_prod").val(id_prod);
		}
		$("#id_prod" + sel_item).val(id_prod);
		ConsultarProducto(id_prod, sel_item, "", "", "Z", c_consig, tipo_fac);
		$("#modal-productos").modal("hide");
		$(".id_prod").trigger("change");
	});
	//Recalcular en caso de cambiar el IVA a si o No
	$(document).on("change", ".reCalcular", function (event) {
		if (tipo_fac != "COM") {
			item = $(this).closest("tr").data("item");
			CalculateTotalFac();
		}
	});
	//Validar empresa
	$("#id_emp").on("change", async function (e) {
		e.preventDefault();
		onlyread = "";
		id_emp = $(this).val();
		$("#id_tdo").val("");
		id_tdo_cfg = "";
		$("#id_cli").val("");
		$("#nom_cli").val("");
		$("#id_moneda").empty();
		$("#id_vend").empty();
		$("#id_fab").empty();
		$("#tasa_cambio").val("");
		if (id_emp) {
			id_tdo_cfg = await tip_doc_fac(id_emp);
			loc_pri_cot = id_tdo_cfg["loc_pri_cot"];
			locked_invoice = id_tdo_cfg["locked_invoice"];
			stock = id_tdo_cfg["cot_stock"];
			listar_monedas();
			listar_vendedores();
			listar_marcas();
			if (loc_pri_cot == 1) {
				onlyread = " readonly ";
			}
		}
		if (!id) {
			if (id_tdo_cfg) {
				id_tdo_val = id_tdo_cfg["id_tdoc_pre"];
				listar_tipos_documentos(id_emp, tipo_fac, id_tdo_val);
				id_tdo = id_tdo_val;
				//$("#id_tdo").trigger("change");
				$("#id_tdo").prop("disabled", true);
				$("#num_tdo").css("pointer-events", "none");
			}
		}
	});
	//Seleccioanr por defecto el vendedor al momento de escoger el cliente
	$(document).on("change", "#id_cli", function (e) {
		e.preventDefault();
		id_cli = $(this).val();
		fetcingData(id_cli);
	});
	//Buscar productos por Marcas
	$(document).on('change', '#id_fab', function (e) {
		e.preventDefault;
		id_fab = $(this).val()
		consultarProductoCoti(id_fab, consig);
	});
})
//Formulario Nuevo
function dat_form_new() {
	listar_empresas();
	$("#fecha_comp").val(GetTodayDate(0));
	fecha_comp = $("#fecha_comp").val();
	listar_status(1);
}
async function fetcingData(id_cli) {
	const datosFetched = await tid_vend(id_cli);
	consig = datosFetched['consig'];
	id_ven_cli = datosFetched["id_vend"];
	id_mon_cli = datosFetched["id_moneda"];
	nom_cli = datosFetched["nom_ent"];
	handling_conver = datosFetched["handling_conver"];
	$("#nom_cli").val(nom_cli);
	listar_vendedores(id_ven_cli);
	listar_monedas(id_mon_cli);
	$("#id_moneda").val(id_mon_cli);
	id_moneda = id_mon_cli;
	tasa_cambio = await getExchangerate(fecha_comp, id_moneda);
	$("#tasa_cambio").val(tasa_cambio);
	$("#tasa_cambio").css("pointer-events", "none");
	show_tasa(id_emp, id_moneda, tasa_cambio);
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
async function recorreTable_fac(verstock = 0, tasa_cambio = 1, tipo, tabla = 'tblDetalle') {
	if (!xtasavatTax_val) {
		fecha = $("#fecha_comp").val();
		xtasavatTax = await xvatTax(fecha, "IVA");
		xtasaIVA = parseFloat(xtasavatTax[0]["txr1_iva"]);
		xtasavatTax_val = true;
	}
	subTotal = 0.0;
	iva = 0.0;
	xbase = 0.0;
	xtotal_form = 0.0;
	if (tipo == "C") {
		verstock = 1;
	} else if (tipo == "F" || tipo == "N" || tipo == "NF") {
		verstock = 1;
	}
	//Recorrer tabla dinamica
	$("#tblDetalle tbody tr").each(function () {
		var xIVA = $(this).find(".input-iva").val();
		var xmonto = 0;
		var xmonto_str = $(this).find(".input-fila").val();
		if (xmonto_str) {
			xmonto = formatoMoneda(xmonto_str);
			if (!isNaN(xmonto)) {
				subTotal += parseFloat(xmonto);
			}
			if (xIVA == "S") {
				xbase += parseFloat(xmonto);
			}
		}
	});
	//Si es en dolares mostrar el contravalor en Bs
	xtasa = tasa_cambio;
	//Calcular el IVA

	if (xbase != 0) {
		iva = parseFloat(xbase * (xtasaIVA / 100));
	}
	xtotal_form = subTotal + iva;
	if (xtasa > 1) {
		$("#sub_total").val(format_number_with_dec_new(subTotal, 2));
		$("#iva").val(format_number_with_dec_new(iva, 2));
		$("#total_frm").val(format_number_with_dec_new(xtotal_form, 2));
		//
		$("#sub_totalBs").val(format_number_with_dec_new(subTotal * xtasa, 2));
		$("#ivaBs").val(format_number_with_dec_new(iva * xtasa, 2));
		$("#total_frmBs").val(
			format_number_with_dec_new(xtotal_form * xtasa, 2)
		);
	} else {
		$("#sub_totall").val(format_number_with_dec_new(subTotal, 2));
		$("#ival").val(format_number_with_dec_new(iva, 2));
		$("#total_frml").val(format_number_with_dec_new(xtotal_form, 2));
	}
}
async function consultarProductoCoti( id_fab, consig ) {	
	var datos = new FormData()
	datos.append('id_fab', id_fab);
	try {
		// 1. Obtener los registros principales
		url01 = `${base_url}/Cotizaciones/create_express`;
		const respuestaprincipal = await $.ajax({url: url01, method: 'GET', data: datos});
	} catch (error) {
		console.error("Error en la petición principal:", error);
	}
	/*
		url = `${base_url}/Cotizaciones/create_express`;
		id_fab = $("#id_fab").val();
		id_cli = $("#id_cli").val();
		$.ajax({
			url: url,
			method: 'POST',
			data: { id_fab: id_fab, id_cli: id_cli },
			dataType: 'json',
			beforeSend: function () {
				loader.show();
			},
			complete: function () {
				loader.hide();
			},
			error: function (xhr, status, error) {
				let mensajeDetalle = 'Ocurrió un problema inesperado en el servidor';
				if (xhr.responseJSON && xhr.responseJSON.message) {
					mensajeDetalle = xhr.responseJSON.message
				} else if (xhr.responseText) {
					console.error('Respuesta cruda del servidor:', xhr.responseText);
				}
				Swal.fire({
					icon: 'error',
					title: 'Error de Validación',
					html: mensajeDetalle
				});
			},
			success: function (data) {
				console.log(data);
				$.each(data, function (item, valor) {							
					data_prod = ConsultarProductoCotiza(valor.id, consig);					
					/*
					var nuevoRegistro = {
						id_producto: valor.id_prod,
						nom_prod: valor.nom_prod,
						cantidad: 1,
						stock: valor.stock,
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
			}
		});
	})
	const datos = new FormData();	
	const url = `${base_url}/Productos/consulta_presu`	
	datos.append("id_prod", id);	
	$.ajax({
		url: url,
		method: 'POST',
		data: datos,
		dataSrc: '',
		dataType: 'json',
		beforeSend: function(){
			loader.show();
		}, 
		complete: function(){
			loader.hide();
		},
		success: function(data){			
			console.log('Consultar data', data);
		}
	})	
	
	try {
		// 3. Determinar URL del endpoint de manera limpia
		const url = (tipo_fac === "P") 			? `${base_url}/Productos/consulta_presu`
			: `${base_url}/Productos/consulta`;

		const respuesta = await fetch(url, { method: "POST", body: datos });
		const resultado = await respuesta.json();

		if (!resultado) return;

		let monto = 0;
		let noadic = resultado["noadic"];
		let selectorItem = "#" + item;

		// 4. Asignación de Nombre y Tooltip de Producto
		let nom_prod_decode = decodeEntities(resultado["nom_prod"]);
		let nom_marca = decodeEntities(resultado["nom_fab"]);
		let referencia = decodeEntities(resultado["ref_prod"]);
		$("#nom_prod" + item).val(nom_prod_decode)
			.prop("title", `${nom_prod_decode} Marca: ${nom_marca} Referencia: ${referencia}`);

		if (typeof equivale !== 'undefined' && equivale) return;

		// 5. Asignar cantidad y unidades correspondientes
		$("#cant" + item).val(cant);

		if (mod === "V" || mod === "Z") {
			$("#uni_ven_prod" + item).val(resultado["uni_ven_prod"]);
		} else {
			$("#uni_com_prod" + item).val(resultado["uni_com_prod"]);
		}

		// 6. Lógica de asignación del IVA
		let xiva = (resultado["iva_prod"] == 1 && tipo_fac !== "NF") ? "S" : "N";
		$("#iva_prod" + item).val(xiva);

		// Stock
		$("#stock" + item).val(resultado["stock"]);

		let xtotal = 0;
		let local_origen_COM = typeof origen_COM !== 'undefined' ? origen_COM : 0;

		// ==========================================
		// SECCIÓN A: PROCESAMIENTO DE VENTAS
		// ==========================================
		if ((mod === "V" || mod === "Z") && local_origen_COM === 0) {
			let xventas_prod = parseFloat(resultado["ventas_prod"]) || 0;
			let uni_ven_prod = parseFloat(resultado["uni_ven_prod"]) || 1;

			if (consig === 1 && tipo_fac === "F") {
				xventas_prod = xventas_prod / uni_ven_prod;
				if (typeof c_consig !== 'undefined' && c_consig == 1) {
					let conv_prod_cons = parseFloat(resultado["conv_prod_cons"]) || 1;
					let venta2_prod = parseFloat(resultado["venta2_prod"]) || 0;
					xventas_prod = (venta2_prod / conv_prod_cons) || (resultado["ventas_prod"] / uni_ven_prod);
				}
			}

			// Descuentos adicionales por fabricante
			if (typeof id_cli !== 'undefined' && id_cli && noadic == 0) {
				const xValAdicionalFAB = await mot_cam_adicional(id_cli);
				let xid_fab = resultado["id_fab"];
				if (xValAdicionalFAB) {
					for (const x of xValAdicionalFAB) {
						if (xid_fab == x["id_fab"] && x["adicional"] > 0) {
							xventas_prod = xventas_prod / x["adicional"];
						}
					}
				}
			}

			// Datos de Empresa y Tasas de Cambio
			let id_empr = $("#id_emp").val();
			let fecha_comp = $("#fecha_comp").val();
			const datosFetched = await xAdditional01(id_empr, fecha_comp);

			let xValorAdic01 = datosFetched["tasa"];
			let xMonedaCia = datosFetched["id_moneda"];
			let id_moneda = $("#id_moneda").val();

			// Descuento adicional por empresa
			if (noadic == 0 && xValorAdic01 && xValorAdic01 > 0 && xMonedaCia == id_moneda) {
				xventas_prod = xventas_prod / parseFloat(xValorAdic01);
			}

			// Descuentos adicionales por cliente
			let current_id_cli = $("#id_cli").val();
			if (current_id_cli) {
				const datosFetchedCli = await xAdditional02(current_id_cli);
				if (noadic == 0) {
					if (datosFetchedCli["adic_01"] > 0) {
						xventas_prod = xventas_prod / datosFetchedCli["adic_01"];
					}
					if (datosFetchedCli["adic_02"] > 0) {
						xventas_prod += xventas_prod * (parseFloat(datosFetchedCli["adic_02"]) / 100);
					}
				}
			}

			// Aplicar tasa de cambio final si las monedas coinciden
			let xTasaCambioDef = 1;
			if (xMonedaCia == id_moneda) {
				let xTasaCambio = await xTasa($("#fecha_comp").val(), 2);
				xTasaCambioDef = parseFloat(xTasaCambio.replace(",", "."));
				xventas_prod = xventas_prod * xTasaCambioDef;
			}

			// Setear inputs de precios y totales
			let xVentasxUnidad = xventas_prod / uni_ven_prod;
			$("#ventas_prod" + item).val(format_number_with_dec_new(xVentasxUnidad, 2));
			$("#ventas_prod1" + item).val(format_number_with_dec_new(xventas_prod, 2));

			xtotal = ($("#cant" + item).val() || 0) * xventas_prod;
			$("#total" + item).val(format_number_with_dec_new(xtotal, 2));

			// Foco interactivo en cantidad
			$("#cant" + item).show().focus();

			// ==========================================
			// SECCIÓN B: PROCESAMIENTO DE COMPRAS
			// ==========================================
		} else {
			let id_emp_comp = $("#id_emp").val();
			let fecha_comp = $("#fecha_comp").val() || $("#fecha_comint").val();
			const datosFetched = await xAdditional01(id_emp_comp, fecha_comp);

			if (tipo_fac !== 'OI') {
				// Validación de control de Lote asignado
				if (resultado["lote_prod"] == 0) {
					$(`#lote${item}`).val("SL").css("pointer-events", "none");
					$(`#fec_venc${item}`).val('0000-00-00').css("pointer-events", "none");
				}

				let xMonedaCia = datosFetched["id_moneda"];
				let id_moneda = $("#id_moneda").val();
				let xcompras_prod = parseFloat(resultado["costo_prod"]) || 0;

				if (xMonedaCia == id_moneda) {
					let xTasaCambio = await xTasa(fecha_comp, 2);
					let xTasaCambioDef = parseFloat(xTasaCambio.replace(",", "."));
					xcompras_prod = xcompras_prod * xTasaCambioDef;
				}

				let xComprasxUnidad = xcompras_prod / (resultado["uni_com_prod"] || 1);
				$("#uni_com_prod" + item).val(resultado["uni_com_prod"]);
				$("#costo_prod" + item).val(format_number_with_dec_new(xComprasxUnidad, 2));
				$("#costo_prod1" + item).val(format_number_with_dec_new(xcompras_prod, 2));

				xtotal = ($("#cant" + item).val() || 0) * xcompras_prod;
				$("#total" + item).val(format_number_with_dec_new(xtotal, 2));
			} else {
				// Compras Internacionales
				$(`#ref_prod${item}`).val(resultado['ref_prod']);
				$(`#nom_fab${item}`).val(resultado['nom_fab']);
				$(`#nom_pre${item}`).val(resultado['bultos']);
				$(`#precio${item}`).val(format_number_with_dec_new(resultado['costo_prod'], 4));
				$(`#total_uni${item}`).val(resultado['uni_ven_prod']);

				let precio = parseFloat(resultado['costo_prod']) || 0;
				let uni_vta_gbl = parseFloat(resultado['uni_ven_prod']) || 1;
				$(`#precio_uni${item}`).val(format_number_with_dec_new(precio / uni_vta_gbl, 4));
			}
		}

		// 7. Seteo masivo de Atributos 'Title' para AdminLTE tooltips
		const inputsToTitle = ["cod_prod", "cant", "uni_ven_prod", "ventas_prod", "ventas_prod1", "iva_prod", "stock", "nom_des"];
		inputsToTitle.forEach(selector => {
			let elem = $("#" + selector + item);
			elem.prop("title", elem.val());
		});
		$("#total" + item).prop("title", format_number_with_dec_new(xtotal, 2));

		// 8. Ejecución de recálculos de tabla nativos
		let tasa_cambio_Val = $("#tasa_cambio").val() ? formatoMoneda($("#tasa_cambio").val()) : 1;
		let val_col = (tipo_fac === "C") ? -1 : 0;

		if (local_origen_COM === 0) {
			// Pasamos 'tabla' si está definida en tu entorno, de lo contrario usa el objeto mapeado
			let tablaInstancia = typeof tabla !== 'undefined' ? tabla : $('#tablaDetalle').DataTable();
			recorreTable_fac(val_col, tasa_cambio_Val, tipo_fac, tablaInstancia);
		} else {
			recorreTable_com(tasa_cambio_Val);
		}

	} catch (err) {
		console.error("Error en ConsultarProducto:", err);
	}
	*/
}