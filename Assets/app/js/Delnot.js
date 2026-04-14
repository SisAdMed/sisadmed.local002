let item = 0;
let id_prod_img = 0;
let nameid = "id_pro";
let selectFile = "";
let itemSelected = 0;
let subTotal = 0;
let subTotaliva = 0;
let total = 0;
let monto = 0;
let board = [];
let titem = 0;
let id_ven_cli = 0;
let id_vend2 = [];
let tasa_fac = 0;
id_clir = $("#id_clir").val();
id_empr = $("#id_empr").val();
fecha_comp = $("#fecha_comp").val();
id_moneda = $("#id_monedar").val();
id_vend = $("#id_vendr").val();
id_des = $("#id_des").val();
id_cot = $("#id").val();
t_fuente = false;
let id_tdo_cfg;
let id_tdo_val;
let id_cli_glb;
let xtasa_coti = "";
let tasa_cambio = 0;
//Validar si requiere autorizacíon el descuento a aplicar
let appreq = 0;
let disapp = "";
tipo_fac = "N";
loc_pri_inv = 0;
//
$(document).ready(function () {
	$(".foranea").hide();
	$(".local").hide();
	if ($("#tipo_fac").val() != undefined) {
		tipo_fac = $("#tipo_fac").val();
	}

	id_cot = $("#id").val();
	if (id_cot) {
		showrowupdate_fac(id_cot, 'NOL');   
	} else {
		listar_empresas();
		listar_descuentos(0, "id_des_enca");
		$("#fecha_comp").val(GetTodayDate(0));
		$("#fecha_venci").val(GetTodayDate(0));
		$("#nro_control").css("display", "none");
	}
});

//Lleno el combo de clientes dependiendo de la empresa seleccionada
$(document).on("change", "#id_emp", async function (event) {
	event.preventDefault();
	id = $("#id").val();
	$("#id_tdo").val("");
	id_tdo_val = "";
	id_tdo_cfg = "";
	id_emp = $("#id_emp").val();
	id_tdo_cfg = await tip_doc_fac(id_emp);
	loc_pri_inv = id_tdo_cfg["loc_pri_inv"];
	if (!id) {
		id_alm_def = id_tdo_cfg["id_alm"];
		id_ubi_def = id_tdo_cfg["id_ubi"];
		$("#id_alm_def").val(id_alm_def);
		$("#id_ubi_def").val(id_ubi_def);
		if (id_tdo_cfg) {
			id_tdo_val = id_tdo_cfg["id_tdoc_not"];
			id_tdoc_pre = id_tdo_cfg["id_tdoc_pre"];
			stock = id_tdo_cfg["fac_stock"];
			listar_tipos_documentos(id_emp, "N", id_tdo_val);
			$("#id_tdo").css("pointer-events", "none");
		}
		listar_tipos_documentos(id_emp, "P", id_tdoc_pre, false, "fuente");
		$("#fuente").css("pointer-events", "none");
		$("#fuente").trigger("change");
	}
});
//Validar si el Tipo de Documento usa consecutivo o no para poder aisgnar el número del documento
$(document).on("change", "#id_tdo", async function (e) {
	const datos = new FormData();
	datos.append("id", id_tdo_val);
	try {
		const url = `${base_url}/CXCDocument/val_tdo`;
		const respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const resultado = await respuesta.json();
		if (resultado) {
			if (resultado[0]["con_tdoc"] == 0) {
				$("#num_tdo").prop("readonly", false);
			} else {
				$("#num_tdo").prop("readonly", true);
			}
		}
	} catch (error) {
		console.log(error);
	}
});

//Lleno el combo de origen de Delnot por la fuente seleccionada
$(document).on("change", "#fuente", function (event) {
	event.preventDefault();
	id_empr = $("#id_emp").val();
	id_emp = id_empr;
	listar_cotizacones(id_emp, "0", "origen");
});
//Completar datos del encabezado y detalle de la factura al seleccioanr un origen
$(document).on("change", "#origen", function (event) {
	event.preventDefault();
	id_cotiza = $("#origen").val();
	$("#cuerpoTablaDetalle").html("");
	show_data_cotiza(id_cotiza);
});
// Mostar Datos cuando se selecciona una cotizacion
async function show_data_cotiza(id_cot, dcto = 0) {
	datos = new FormData();
	datos.append("id_cot", id_cot);
	try {
		const url = `${base_url}/Cotizaciones/consultar_cotizacion`;
		const respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const resultado = await respuesta.json();
		//Validar si hay descuento
		if (dcto != 0) {
			x_val_dct = dcto / 100;
		}
		item = 0;
		origen = $("#origen").val();
		if (origen) {
			id_cli = resultado[0]["id_cli"];
			$("#id_cli").val(id_cli);
			fetcingData(id_cli);
			$("id_moneda").trigger("change");
			xtasa_coti = resultado[0]["tasa_cambio"];
			xtasa_coti = format_number_with_dec_new(xtasa_coti, 8);
			if (xtasa_coti) {
				Swal.fire({
					title:
						"Desea utilizar la Tasa de Cambio de la Cotización " +
						xtasa_coti +
						"?",
					icon: "question",
					showDenyButton: true,
					confirmButtonText: "Si",
					denyButtonText: `No`,
				}).then((result) => {
					if (result.isConfirmed) {
						$("#tasa_cambio").val(xtasa_coti);
						tasa_cambio = xtasa_coti;
					} else {
						tasa_cambio = $("#tasa_cambio").val();
					}
					for (x of resultado) {
						item = item + 1;
						nameid = "id_pro" + item;
						//nameid = nameid + item;
						//Variables valor
						id_prod = x["id_prod"];
						nom_prod = x["nom_prod"];
						cant_det = x["can_det"];
						stock = x["stock"];
						uni_vta = x["uni_vta"];
						pre_unit = parseFloat(x["pre_unit"]);
						pre_vta = parseFloat(x["pre_vta"]);
						iva_prod = x["iva_prod"];
						sub_total = parseFloat(x["sub_total"]);
						if (dcto != 0) {
							pre_unit = pre_unit - pre_unit * x_val_dct;
							pre_vta = pre_vta - pre_vta * x_val_dct;
							sub_total = sub_total - sub_total * x_val_dct;
						}
						pre_unit = format_number_with_dec_new(pre_unit, 4);
						pre_vta = format_number_with_dec_new(pre_vta, 4);
						iva_prod = x["iva_prod"];
						sub_total = format_number_with_dec_new(sub_total, 4);
						var htmlTags =
							'<tr class="text-xs" id="fila' +
							item +
							'">' +
							'<td class="text-right text-xs">' +
							item +
							"</td>" +
							'<td style="width:25%"><input type="hidden" name="id_prod[]" id="id_prod' +
							item +
							'" class="text-xs photo" value="' +
							id_prod +
							'"><div class="input-group"><input type="text"  class="form-control text-xs" id="nom_prod' +
							item +
							'" name="nom_prod" readonly value="' +
							nom_prod +
							'"><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>' +
							'<td style="width:8%"><input type="number" name="cant[]" id="cant' +
							item +
							'"  class="form-control text-right text-xs tcant" min="1"style="width:80%" value="' +
							cant_det +
							'" onchange="CalculateTotalFac()" ></td>' +
							'<td style="width:10%"><input type="number" name="stock[]" id="stock' +
							item +
							'" class="form-control text-right text-xs stock" style="width:100%" disabled value="' +
							stock +
							'"></td>' +
							'<td style="width:7%"><input type="text" name="uni_ven_prod[]" id="uni_ven_prod' +
							item +
							'" readonly class="form-control text-right text-xs"  style="width:100%" value="' +
							uni_vta +
							'"></td>' +
							'<td style="width:10%"><input type="text" name="ventas_prod[]" id="ventas_prod' +
							item +
							'" readonly class="form-control text-right text-xs" disable style="width:100%" value="' +
							pre_unit +
							'" </td>' +
							'<td style="width:10%"><input type="text" name="ventas_prod1[]" id="ventas_prod1' +
							item +
							'" readonly class="form-control text-right text-xs " disable style="width:100%" value="' +
							pre_vta +
							'" </td>' +
							'<td style="width:10%"><input type="hidden" name="id_des_item[]" id="id_des_item' +
							item +
							'" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs text-right" id="nom_des' +
							item +
							'" name="nom_des" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-descuentos" title="Buscar y seleccionar descuento"><i class="fas fa-search"></i></a></span></div></div></td>' +
							'<td style="width:7%"><select name="iva_prod[]" id="iva_prod' +
							item +
							'" class="form-control text-xs reCalcular" style="width:100%"></select>' +
							'<td style="width:10%"><input type="text" name="total[]" id="total' +
							item +
							'" class="form-control text-right text-xs sub-total" step="0.0001" readonly value="' +
							sub_total +
							'" style="width:100%"></td>' +
							'<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>' +
							"</tr>";
						$("#tblDetalle").append(htmlTags);
						listar_si_no(iva_prod, "iva_prod" + item);
						$(".stock").trigger("change");
					}
					recorreTable_fac(
						1,
						tasa_cambio.replace(",", "."),
						tipo_fac
					);
				});
			}
		}
	} catch (error) {
		console.log(error);
	}
}
//Aplicar descuentos general
$(document).on("change", "#id_des_enca", async function (e) {
	id_des = $(this).val();
	xdesglb = await getRateDes(id_des);
	desdes = "";
	$("#cuerpoTablaDetalle").html("");
	if (id_des) {
		show_data_cotiza(id_cotiza, parseFloat(xdesglb["valor_tipdes"]));
	} else {
		show_data_cotiza(id_cotiza);
	}
});
//Solicitar aprobación para aplicar el descuento solicitado
async function ReqApp(desdes) {
	title = "Aprobación aplicación de descuentos";
	message =
		"Se solicita aprobación de descuentos para facturar la cotizacion del " +
		desdes;
	const datos = new FormData();
	datos.append("title", title);
	datos.append("message", desdes);
	try {
		const url = `${base_url}/Delnot/aprobacion`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 200);
		});
	} catch (error) {
		console.log(error);
	}
}
//Busqueda del valor del porcentaje de descuento
async function getRateDes(id_des) {
	var datos = new FormData();
	datos.append("id", id_des);
	try {
		const url = `${base_url}/TipoDcto/show_row`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 200);
		});
	} catch (err) {
		console.log(err);
	}
}
//Combo de monedas
$(document).on("change", "#id_moneda", async function (event) {
	event.preventDefault();
	fecha_comp = $("#fecha_comp").val();
	id_moneda = $("#id_moneda").val();
	fecha_comp = $("#fecha_comp").val();
	xtasa = await getExchangerate(fecha_comp, id_moneda);
	$("#tasa_cambio").val(financial(xtasa, 8));
	tasa_fac = financial(xtasa, 8);
	tasa_cambio = xtasa;

	show_tasa();
});
//Seleccioanr por defecto el vendedor al momento de escoger el cliente
$(document).on("change", "#id_cli", function (event) {
	event.preventDefault();
	fetcingData($(this).val());
});

async function fetcingData(id_cli) {
	const datosFetched = await tid_vend(id_cli);
	cod_diascre = datosFetched["cod_diascre"] ?? 0;
	handling_conver = datosFetched["handling_conver"];
	c_consig = datosFetched["c_consig"];
	id_ubi_cli = datosFetched["id_ubi"];
	id_alm_cli = datosFetched["id_alm"];

	if (handling_conver) {
		$("#handling_conver").val(handling_conver);
	}
	if (id_alm_cli) {
		$("#id_alm_cli").val(id_alm_cli);
	}
	if (id_ubi_cli) {
		$("#id_ubi_cli").val(id_ubi_cli);
	}

	$("#fecha_venci").val($("#fecha_comp").val());
	if (cod_diascre) {
		fecha_venci = GetTodayDate(cod_diascre);
		$("#fecha_venci").val(fecha_venci);
	}
	id_ven_cli = datosFetched["id_vend"];
	id_mon_cli = datosFetched["id_moneda"];
	nom_cli = datosFetched["nom_ent"];
	$("#nom_cli").val(nom_cli);
	listar_vendedores(id_ven_cli);
	listar_monedas(id_mon_cli);
	fecha_comp = $("#fecha_comp").val();
	xTasaCambio = await getExchangerate(fecha_comp, id_mon_cli);
	if (!id_cot) {
		$("#tasa_cambio").val(xTasaCambio);
		tasa_fac = financial(xTasaCambio, 8);
	}
	tasa_cambio = xTasaCambio;
	show_tasa();
	if (id_alm_cli && c_consig == 1 && id == "") {
		mostar_data_inv();
	}
}

$("body").on("click", "#tblDetalle tr", function () {
	selectFile = $(this).attr("id");
	itemSelected = selectFile.substring(4);
	let selects = $(".mi-select");
	id_prod_img = $(this).find("option:selected").val();
});

//Validar stock de producto
//Recalcular en caso de cambiar el IVA a si o No
$(document).on("change", ".reCalcular", function (event) {
	CalculateTotalFac();
});

$("#confirmar").on("click", function () {
	recuperar_selects();
});

//funcion para elimnar una fila
$(document).on("click", ".borrar", function (event) {
	event.preventDefault();
	$(this).closest("tr").remove();
	recorreTable_fac(1, tasa_cambio.replace(",", "."), tipo_fac);
});

//funcion para confirmar una fila
$(document).on("click", ".confirmar", function (event) {
	event.preventDefault();
	recuperar_selects();
});

$(document).on("change", ".confirmar", function (event) {
	event.preventDefault();
	recuperar_selects();
});

//Borrar - Se cambia es el status a inactivo para no perder el cosnecutivo
function eliminarBtn(element) {
	let id = element.dataset.id;
	let name = element.dataset.name;
	let codigo = element.dataset.code;
	const datos = new FormData();
	datos.append("id", id);
	datos.append("name", name);
	datos.append("codigo", codigo);
	Swal.fire({
		icon: "warning",
		title: "Está seguro de eliminar este registro?",
		showConfirmButton: true,
		confirmButtonText: "ELIMINAR",
		confirmButtonColor: "#3085d6",
		showCancelButton: true,
		cancelButtonText: "CANCELAR",
		cancelButtonColor: "#d33",
		buttonsStyling: true,
	}).then((result) => {
		if (result.isConfirmed) {
			borrar(datos);
		}
	});
}
async function borrar(datos) {
	let url = `${base_url}/Delnot/destroy`;
	let repuesta = await fetch(url, {
		method: "POST",
		body: datos,
	});
	const resultado = await repuesta.json();
	if (resultado) {
		Swal.fire({
			icon: "success",
			title: "Registro eliminado satisfactoriamente",
			showConfirmButton: true,
		});
		window.location.href = `${base_url}/Delnot`;
	}
}
//Al abrir modal para mostrar las fotos de los productos
$(document).on("click", ".show-picture", function (event) {
	//Datos para mostrar las fotos
	show_picture();
});
async function show_picture() {
	try {
		var datos = new FormData();
		datos.append("id_prod_img", id_prod_img);
		let url = `${base_url}/Productos/showImg`;
		let repuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const resultado = await repuesta.json();
		$("#imgPreview").empty();
		if (resultado) {
			$("#title-product").text(resultado[0]["nom_prod"]);
			var show_photo = "";
			$.each(resultado, function (i, item) {
				show_photo =
					'<img width="200px" height="200px" title="' +
					item["filename"] +
					'" src="' +
					base_url +
					item["url_photo"] +
					'">' +
					"</img> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				$("#imgPreview").append(show_photo);
			});
		}
		id_prod_img = 0;
	} catch (err) {
		console.log(err);
	}
}
