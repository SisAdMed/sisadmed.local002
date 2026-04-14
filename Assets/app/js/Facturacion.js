//Variables
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
id_tip_doc = "";
let alltext = [];
//Validar si requiere autorizacíon el descuento a aplicar
let appreq = 0;
let disapp = "";
handling_conver = 0;
let id_ent;
mov_inv = true;
let ytasa_cambio = 0;
tipo_fac = GetURLParameter("tipo");
almSalPpal = false;
tip_doc_fuente = "";
xtasa_ori = 0;
let tasa_fac_cli = false;
let invoice = 0;
let xTasaCambio;
//
$(document).ready(async function () {
	id_cot = $("#id").val();
	if ($("#tipo_fact").val() != undefined) {
		tipo_fac = $("#tipo_fact").val();
	}
	$(".consignado").hide();
	if (id_cot) {
		showrowupdate_fac_cus(id_cot, tipo_fac);
	} else {
		listar_empresas();
		listar_descuentos(0, "id_des_enca");
		listar_motivo_cambio(0);
		$("#fecha_comp").val(GetTodayDate(0));
		$("#fecha_venci").val(GetTodayDate(0));
		$("#nro_control").css("display", "none");
	}
});

async function showrowupdate_fac_cus(id, tipo) {
	const datos = new FormData();
	let url, ttipo;
	datos.append("id_cot", id);
	try {
		if (tipo == "C") {
			url = `${base_url}/Facturacion/consultar_factura`;
			ttipo = "C";
		} else if (tipo == "N" || tipo == "F") {
			url = `${base_url}/Facturacion/consultar_factura`;
			ttipo = "F";
		} else {
			url = `${base_url}/Delnot/consultar_factura`;
			ttipo = $("#tipo_fat").val();
		}
		const respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const resultado = await respuesta.json();
		id_emp = resultado[0]["id_emp"];
		num_tdo = resultado[0]["num_tdo"];
		id_cli = resultado[0]["id_cli"];
		$("#id_cli").val(id_cli);
		$("#id_cli").trigger("change");
		listar_empresas(id_emp, true);
		id_tdo = resultado[0]["id_tdo"];

		listar_tipos_documentos(id_emp, ttipo, id_tdo, true);

		fecha_comp = resultado[0]["fecha_comp"];
		id_moneda = resultado[0]["id_moneda"];
		listar_monedas(id_moneda);
		$("#num_tdo").val(num_tdo);
		//$('#num_tdo').attr('readonly', 'readonly');
		$("#fecha_comp").val(fecha_comp);
		$("#fecha_comp").attr("readonly", "readonly");

		$("#id_moneda").css("pointer-events", "none");
		$("#id_vend").css("pointer-events", "none");
		$("#tasa_cambio").val(
			format_number_with_dec_new(resultado[0]["tasa_cambio"], 2)
		);
		$("#id_des_enca").val(resultado[0]["id_des"]);
		$("#oc_cliente").val(resultado[0]["oc_cliente"]);
		$("#descrip_cot").val(resultado[0]["descrip_cot"]);
		$("#nro_control").val(resultado[0]["nro_control"]);
		$("#nro_control").css("display", "block");
		handling_conver = resultado[0]["handling_conver"];
		xTasaCambio = resultado[0]["tasa_cambio"];
		id_ent = 0;
		if (handling_conver == 1) {
			id_ent = $("#id_cli").val();
		}
		id_cont = resultado[0]["id_cont"];
		
		
		if (id_cont && id_cont.length > 3) {
			id_cont = id_cont.split("-");
			id_tdo_cfg = await tip_doc_fac(id_emp);
			
			
			id_tdoc_pre = await get_id_tipo_doc_fuente(id_cont[1]);
			if (id_tdoc_pre) {

			
			
			listar_tipos_documentos(
				id_emp,
				id_tdoc_pre.tipo_tdoc,
				id_tdoc_pre.id_tdoc,
				false,
				"fuente"
			);
			invoice = "FAC-FA-" + num_tdo;
			listar_notas(id_emp, "0", "origen", id_cli, invoice);

			$("#fuente").css("pointer-events", "none");
			$("#fuente").trigger("change");
		}}
		console.log(resultado);
		for (x of resultado) {
			item = item + 1;
			max_item = item;
			nameid = "id_pro" + item;
			//nameid = nameid + item;
			//Variables valor
			id_prod = x["id_prod"];
			nom_prod = x["nom_prod"];
			nom_prod_tit = x['nom_prod'] + ' Marca: ' + x['nom_fab'];
			cant_det = x["can_det"];
			uni_vta = x["uni_vta"];
			pre_unit = x["pre_unit"];
			pre_vta = x["pre_vta"];
			iva_prod = x["iva_prod"];
			sub_total = x["sub_total"];
			stock = 0;
			var htmlTags =
				'<tr id="fila-' +
				item +
				'">' +
				'<td class="text-right text-xs">' +
				item +
				"</td>" +
				'<td style="width:30%"><input type="hidden" name="id_prod[]" id="id_prod' +
				item +
				'" class="text-xs photo" value="' +
				id_prod +
				'"><div class="input-group"><input type="text"  class="form-control text-xs" id="nom_prod' +
				item +
				'" name="nom_prod" readonly value="' +
				nom_prod +
				'" title="' + nom_prod_tit +'"><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>' +
				'<td style="width:8%"><input type="number" name="cant[]" id="cant' +
				item +
				'"  class="form-control text-right text-xs tcant reCalcular" style="width:80%" value="' +
				cant_det +
				'" ></td>' +
				'<td style="width:8%"><input type="number" name="stock[]" id="stock' +
				item +
				'" class="form-control text-right text-xs stock" style="width:100%" disabled value="' +
				stock +
				'"></td>' +
				'<td style="width:7%"><input type="text" name="uni_ven_prod[]" id="uni_ven_prod' +
				item +
				'" readonly class="form-control text-right text-xs"  style="width:100%" value="' +
				uni_vta +
				'"></td>' +
				'<td style="width:8%"><input type="text" name="ventas_prod[]"  id="ventas_prod' +
				item +
				'"  readonly class="form-control text-right text-xs"  style="width:100%" value="' +
				format_number_with_dec_new(pre_unit, 4) +
				'"></td>' +
				'<td style="width:8%"><input type="text" name="ventas_prod1[]" id="ventas_prod1' +
				item +
				'" class="form-control text-right text-xs reCalcular camponumero"  style="width:100%" value="' +
				format_number_with_dec_new(pre_vta, 4) +
				'"></td>' +
				'<td style="width:10%"><input type="hidden" name="id_des[]" id="id_des' +
				item +
				'" class="text-xs"><div class="input-group"><input  type="text"  class="form-control text-xs text-right" id="nom_des' +
				item +
				'" name="nom_des" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-descuentos" title="Buscar y seleccionar descuento"><i class="fas fa-search"></i></a></span></div></div></td>' +
				'<td style="width:10%"><select name="iva_prod[]" id="iva_prod' +
				item +
				'" class="form-control text-xs reCalcular input-iva" style="width:60%"></ select></td>' +
				'<td style="width:10%"><input type="text" name="total[]" id="total' +
				item +
				'" class="form-control text-right text-xs sub-total input-fila" readonly value="' +
				format_number_with_dec_new(sub_total, 4) +
				'"></td>' +
				'<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>' +
				"</tr>";
			$("#tblDetalle").append(htmlTags);
			listar_si_no(iva_prod, "iva_prod" + item);
			StockProducto(id_prod, item, id_ent);
			val_col = 1;
			if (tipo == "C") {
				val_vol = -1;
			}
			recorreTable_fac(val_col, xTasaCambio, tipo);
		}
	} catch (err) {
		console.log(err);
	}
}
//Lleno el combo de clientes dependiendo de la empresa seleccionadak
$(document).on("change", "#id_emp", async function (event) {
	event.preventDefault();
	id = $("#id").val();
	$("#id_tdo").val("");
	$("#fuente").val("");
	$("#origen").val("");
	id_tdo_val = "";
	id_tdo_cfg = "";
	id_emp = $("#id_emp").val();
	//
	id_emp_cfg = await tip_doc_com(id_emp);
	id_moneda_cia = id_emp_cfg["id_moneda"];
	//
	id_tdo_cfg = await tip_doc_fac(id_emp);
	id_tip_doc = tipo_fac;
	locked_invoice = id_tdo_cfg["locked_invoice"];
	loc_pri_inv = id_tdo_cfg["loc_pri_inv"];
	if (!id) {
		if (id_tdo_cfg) {
			if (id_tip_doc == "F") {
				id_tdo_val = id_tdo_cfg["id_tdoc_fac"];
				id_tdoc_pre = id_tdo_cfg["id_tdoc_pre"];
				listar_tipos_documentos(id_emp, "P,Z,N", 0, true, "fuente");
			} else if (id_tip_doc == "C") {
				id_tdo_val = id_tdo_cfg["id_tdoc_cre"];
				id_tdoc_pre = id_tdo_cfg["id_tdoc_fac"];
				listar_tipos_documentos(
					id_emp,
					"F",
					id_tdoc_pre,
					true,
					"fuente"
				);
			} else if (id_tip_doc == "N") {
				id_tdo_val = id_tdo_cfg["id_tdoc_not"];
				id_tdoc_pre = id_tdo_cfg["id_tdoc_pre"];
				listar_tipos_documentos(
					id_emp,
					"P",
					id_tdoc_pre,
					true,
					"fuente"
				);
			} else if (id_tip_doc == "Z") {
				id_tdo_val = id_tdo_cfg["id_tdoc_not_no_fis"];
				id_tdoc_pre = id_tdo_cfg["id_tdoc_pre"];
				listar_tipos_documentos(
					id_emp,
					"P",
					id_tdoc_pre,
					true,
					"fuente"
				);
			}
			listar_tipos_documentos(id_emp, id_tip_doc, id_tdo_val);
			$("#id_tdo").css("pointer-events", "none");
		}
		//$("#fuente").css("pointer-events","none");
		//$("#fuente").trigger( "change" );
	}
	stock = id_tdo_cfg["fac_stock"];
});
//Lleno el combo de origen de facturacion por la fuente seleccionada
$(document).on("change", "#fuente", async function (event) {
	fuente = $(this).val();
	$("#origen").empty();
	if (fuente != 0) {
		event.preventDefault();
		id_empr = $("#id_emp").val();
		id_emp = id_empr;
		id_cli = $("#id_cli").val();
		$("#origen").empty();
		if (id_tip_doc == "F") {
			const url = `${base_url}/TipoDocCXC/tipo_doc_name`;
			var datos = new FormData();
			datos.append("id_tip_doc", fuente);
			var respuesta = await fetch(url, {
				method: "POST",
				body: datos,
			});
			var resultado = await respuesta.json();
			if (resultado) {
				tip_doc_fuente = resultado.tipo_tdoc;
				if (resultado.tipo_tdoc == "P") {
					listar_cotizacones(id_emp, "0", "origen");
				} else if (resultado.tipo_tdoc == "Z") {
					listar_notas(id_emp, "0", "origen", id_cli, invoice);
				} else if (resultado.tipo_tdoc == "N") {
					listar_notas(id_emp, "0", "origen", id_cli, "N");
				}
			}
		}
	}
});
//Completar datos del encabezado y detalle de la factura al seleccioanr un origen
$(document).on("change", "#origen", function (event) {
	event.preventDefault();
	id_cotiza = $("#origen").val();
	if (
		(id_tip_doc == "F" && tip_doc_fuente == "P") ||
		(id_tip_doc == "F" && tip_doc_fuente == "Z") ||
		(id_tip_doc == "F" && tip_doc_fuente == "N")
	) {
		$("#cuerpoTablaDetalle").html("");
		show_data_cotiza(id_cotiza);
	} else if (id_tip_doc == "C") {
		$("#id_doc_afec").val(id_cotiza);
		show_data_factura(id_cotiza);
	}
});
//Muestra los datos de la factura para emitir NC
async function show_data_factura($id_cot) {
	var url = "";
	datos = new FormData();
	datos.append("id_cot", $id_cot);
	url = `${base_url}/Facturacion/consultar_factura_nc`;
	try {
		const respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const resultado = await respuesta.json();
		tasa_cambio = format_number_with_dec_new(
			resultado[0]["tasa_cambio"],
			8
		);
		$("#tasa_cambio").val(tasa_cambio);
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
			sub_total = x["sub_total"];
			//pre_unit = pre_unit * -1;
			//pre_vta = pre_vta * -1;
			sub_total = sub_total * -1;
			pre_unit = format_number_with_dec_new(
				sub_total / cant_det / uni_vta,
				4
			);
			pre_vta = format_number_with_dec_new(sub_total / cant_det, 4);

			iva_prod = x["iva_prod"];
			sub_total = format_number_with_dec_new(sub_total, 4);
			var htmlTags =
				'<tr class="text-xs" id="fila-' +
				item +
				'">' +
				'<td class="text-right text-xs">' +
				item +
				"</td>" +
				'<td style="width:30%"><input type="hidden" name="id_prod[]" id="id_prod' +
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
				'"  class="form-control text-right text-xs reCalcular  tcant" step="-1" style="width:80%" value="' +
				cant_det +
				'" onchange="CalculateTotalFac()" ></td>' +
				'<td style="width:8%"><input type="number" name="stock[]" id="stock' +
				item +
				'" class="form-control text-right text-xs stock" style="width:100%" disabled value="' +
				stock +
				'"></td>' +
				'<td style="width:7%"><input type="text" name="uni_ven_prod[]" id="uni_ven_prod' +
				item +
				'" readonly class="form-control text-right text-xs"  style="width:100%" value="' +
				uni_vta +
				'"></td>' +
				'<td style="width:8%"><input type="text" name="ventas_prod[]" id="ventas_prod' +
				item +
				'" readonly class="form-control text-right text-xs" disable style="width:100%" value="' +
				pre_unit +
				'" </td>' +
				'<td style="width:8%"><input type="text" name="ventas_prod1[]" id="ventas_prod1' +
				item +
				'" readonly class="form-control text-right text-xs " disable style="width:100%" value="' +
				pre_vta +
				'" </td>' +
				'<td style="width:10%"><input type="hidden" name="id_des_item[]" id="id_des_item' +
				item +
				'" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs text-right" id="nom_des' +
				item +
				'" name="nom_des" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-descuentos" title="Buscar y seleccionar descuento"><i class="fas fa-search"></i></a></span></div></div></td>' +
				'<td style="width:10%"><select name="iva_prod[]" id="iva_prod' +
				item +
				'" class="form-control text-xs reCalcular input-iva" style="width:100%"></select>' +
				'<td style="width:10%"><input type="text" name="total[]" id="total' +
				item +
				'" class="form-control text-right text-xs sub-total input-fila" step="0.0001" readonly value="' +
				sub_total +
				'" style="width:100%"></td>' +
				'<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>' +
				"</tr>";
			$("#tblDetalle").append(htmlTags);
			listar_si_no(iva_prod, "iva_prod" + item);
			$(".stock").trigger("change");
		}
		if (tipo_fac == "F") {
			$("#id_moneda").trigger("change");
		}
		recorreTable_fac(1, tasa_cambio.replace(",", "."));
	} catch (error) {
		console.log(error);
	}
}
// Mostar Datos cuando se selecciona una cotizacion
async function show_data_cotiza(id_cot, dcto = 0) {
	var url = "";
	datos = new FormData();
	datos.append("id_cot", id_cot);
	try {
		if (tip_doc_fuente == "P") {
			url = `${base_url}/Cotizaciones/consultar_cotizacion`;
			doc_orig = "Cotización";
		} else {
			url = `${base_url}/Delnotnotfis/consultar_nota`; 
			doc_orig = "Nota de Entrega No Fiscal";
		}
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
		if (origen && !tasa_fac_cli) {
			//console.log(resultado);
			//id_cli = resultado[0]["id_cli"];
			//$("#id_cli").val(id_cli);
			//fetcingData(id_cli);
			$("id_moneda").trigger("change");
			xtasa_coti = resultado[0]["tasa_cambio"];
			xtasa_coti = format_number_with_dec_new(xtasa_coti, 8);
			$title = "";
			if (xtasa_coti) {
				Swal.fire({
					title:
						"Desea utilizar la Tasa de Cambio de la  " +
						doc_orig +
						" " +
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
						recorreTable_fac(1, tasa_cambio.replace(",", "."));
					} else {
						tasa_cambio = xtasa_ori;
						$("#tasa_cambio").val(tasa_cambio);
						recorreTable_fac(1, tasa_cambio.replace(",", "."));
					}
				});
			}
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
				'<td style="width:30%"><input type="hidden" name="id_prod[]" id="id_prod' +
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
				'<td style="width:8%"><input type="number" name="stock[]" id="stock' +
				item +
				'" class="form-control text-right text-xs stock" style="width:100%" disabled value="' +
				stock +
				'"></td>' +
				'<td style="width:7%"><input type="text" name="uni_ven_prod[]" id="uni_ven_prod' +
				item +
				'" readonly class="form-control text-right text-xs"  style="width:100%" value="' +
				uni_vta +
				'"></td>' +
				'<td style="width:8%"><input type="text" name="ventas_prod[]" id="ventas_prod' +
				item +
				'" readonly class="form-control text-right text-xs" disable style="width:100%" value="' +
				pre_unit +
				'" </td>' +
				'<td style="width:8%"><input type="text" name="ventas_prod1[]" id="ventas_prod1' +
				item +
				'" readonly class="form-control text-right text-xs " disable style="width:100%" value="' +
				pre_vta +
				'" </td>' +
				'<td style="width:10%"><input type="hidden" name="id_des_item[]" id="id_des_item' +
				item +
				'" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs text-right" id="nom_des' +
				item +
				'" name="nom_des" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-descuentos" title="Buscar y seleccionar descuento"><i class="fas fa-search"></i></a></span></div></div></td>' +
				'<td style="width:10%"><select name="iva_prod[]" id="iva_prod' +
				item +
				'" class="form-control text-xs reCalcular input-iva" style="width:100%"></select>' +
				'<td style="width:10%"><input type="text" name="total[]" id="total' +
				item +
				'" class="form-control text-right text-xs sub-total input-fila" step="0.0001" readonly value="' +
				sub_total +
				'" style="width:100%"></td>' +
				'<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>' +
				"</tr>";
			$("#tblDetalle").append(htmlTags);
			listar_si_no(iva_prod, "iva_prod" + item);
			$(".stock").trigger("change");
		}
		recorreTable_fac(1, tasa_cambio.replace(",", "."));
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
		const url = `${base_url}/Facturacion/aprobacion`;
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
$(document).on("change", "#id_moneda, #fecha_comp", async function (event) {
	event.preventDefault();
	id_moneda = $("#id_moneda").val();
	fecha_comp = $("#fecha_comp").val();
	xtasa = await getExchangerate(fecha_comp, id_moneda);
	$("#tasa_cambio").val(xtasa);
	tasa_fac = financial(xtasa, 8);
	tasa_cambio = xtasa;
	show_tasa();
});
//Seleccioanr por defecto el vendedor al momento de escoger el cliente
$(document).on("change", "#id_cli", function (event) {
	event.preventDefault();
	id_ent = $(this).val();
	fetcingData(id_ent);
});

async function fetcingData(id_cli) {
	const datosFetched = await tid_vend(id_cli);
	if (!id_ent) {
		id_ent = id_cli;
	}
	handling_conver = datosFetched["handling_conver"];
	c_consig = datosFetched["c_consig"] ?? 0;
	id_ubi_cli = datosFetched["id_ubi"];
	id_alm_cli = datosFetched["id_alm"];
	print_special = datosFetched["print_special"];
	cod_diascre = datosFetched["cod_diascre"] ?? 0;
	req_exc_rat = datosFetched["req_exc_rat"] ?? 0;
	if (req_exc_rat == 1 && !id) {
		tasa_fac_cli = true;
	}
	$("#fecha_venci").val($("#fecha_comp").val());
	if (cod_diascre) {
		fecha_venci = GetTodayDate(cod_diascre);
		$("#fecha_venci").val(fecha_venci);
	}
	id_ven_cli = datosFetched["id_vend"];
	nom_cli = datosFetched["nom_ent"];
	id_ubi = datosFetched["id_ubi"];
	if (id_ubi) {
		id_ubi_consig = id_ubi;
		id_ubi = "";
		id_alm = datosFetched["id_alm"];
	}
	id_motcam = datosFetched["id_motcam"];
	$(".consignado").hide();
	if (c_consig == 1) {
		id_alm = datosFetched["id_alm"];
		$("#c_consig").val(c_consig);
		$(".consignado").show();
		id_ubi = datosFetched["id_ubi"];
		$("#id_alm").val(id_alm);
		$("#id_ubi").val(id_ubi);
	}
	listar_motivo_cambio(id_motcam);
	$("#id_motcam").css("pointer-events", "none");
	$("#nom_cli").val(nom_cli);
	$("#handling_conver").val(handling_conver);
	listar_vendedores(id_ven_cli);
	if (!id_cot) {
		fecha_comp = $("#fecha_comp").val();
		id_mon_cli = datosFetched["id_moneda"];
		xTasaCambio = await getExchangerate(fecha_comp, id_mon_cli);
		listar_monedas(id_mon_cli);
		$("#tasa_cambio").val(xTasaCambio);
		tasa_fac = financial(xTasaCambio, 8);
		xtasa_ori = xTasaCambio;
	}
	tasa_cambio = xTasaCambio;

	if (id_ubi == null && handling_conver == 1) {
		$("#modal-ubicaciones").modal("show");
	}
	if (id_tip_doc) {
		if (id_tip_doc == "C") {
			//listar_cotizacones(id_emp, '0', 'origen');
			listar_facturas_clientes(id_emp, "0", "origen", id_ent);
		}
	}

	if (tasa_fac_cli) {
		const resultado = await Swal.fire({
			title: "Indique la fecha para obtener el cambio a facturar",
			input: "date",
			inputValidator: (fecha) => {
				if (!fecha) {
					return "Debe indicar una fecha válida";
				} else {
					return undefined;
				}
			},
			html: `
					<h1>Cambio: <small id="cambio" style="color:#007bff;"></small></h1>
					`,
			didOpen: async () => {
				const input = await Swal.getInput();
				const $cambio = await Swal.getHtmlContainer().querySelector(
					"#cambio"
				);
				input.oninput = async () => {
					$cambio.textContent = "";
					const cambio = await getExchangerate(input.value, 2);
					if (cambio != "") {
						ytasa_cambio = `${cambio}`;
						$cambio.textContent = `${cambio}`;
					}
				};
			},
			showCancelButton: true,
			confirmButtonText: "Ok",
			cancelButtonText: "Cancelar",
		});
		if (resultado.value) {
			$("#tasa_cambio").val(ytasa_cambio);
		}
	}

	show_tasa();
}

$("body").on("click", "#tblDetalle tr", function () {
	var fila = $(this).attr("id");
	item = fila.substring(5);
	id_prod_img = $(this).find("option:selected").val();
});
//Validar solo numeros
$(document).on("keydown", ".validar", function (event) {
	if (event.shiftKey) {
		event.preventDefault();
	}
	if (event.keyCode == 46 || event.keyCode == 8) {
	} else {
		if (event.keyCode < 95) {
			if (event.keyCode < 48 || event.keyCode > 57) {
				event.preventDefault();
			}
		} else {
			if (event.keyCode < 96 || event.keyCode > 105) {
				event.preventDefault();
			}
		}
	}
});
//Recalcular en caso de cambiar el IVA a si o No
$(document).on("change", ".reCalcular", function (event) {
	CalculateTotalFac();
});
//Al seleccionar una producto
//Buscar registros de la cotización a editar

$("#confirmar").on("click", function () {
	recuperar_selects();
});

//funcion para elimnar una fila
$(document).on("click", ".borrar", function (event) {
	event.preventDefault();
	$(this).closest("tr").remove();

	recorreTable_fac(1, tasa_cambio.replace(",", "."));
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

function recuperar_selects() {
	let selects = $(".mi-select");

	selects.each(function () {
		let select = $(this);
		id_pro = select.val();
	});
	ConsultarProducto(id_pro, item);
	id_ent = 0;
	if (handling_conver == 1) {
		id_ent = $("#id_cli").val();
	}
	StockProducto(id_pro, item, id_ent);
}

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
	let url = `${base_url}/Facturacion/destroy`;
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
		window.location.href = `${base_url}/Facturacion`;
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
//Cargar prodcutos desde el archivo cargado a la tabla
$("#pdf_file").change(function (e) {
	//Actualizar MOneda con de la Empresa
	//listar_monedas(id_moneda_cia);
	//$("#tasa_cambio").val(format_number_with_dec_new(1, 4));
	//tasa_cambio = 1
	show_tasa();
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
			$("#cuerpoTablaDetalle").empty();
			loadAndRenderPDF(e);
		}
	} else {
		$("#cuerpoTablaDetalle").empty();
	}
	$(".loader").hide();
});
async function printer_invoice(id) { 
	id_cot = id.dataset.id_cot;
	id_moneda = id.dataset.moneda;
	print_special = id.dataset.print;
	tasa = id.dataset.tasa;
	if (print_special == 1 && id_moneda == 1) {
		if (id_moneda == 1 && tasa == 1) {
			const resultado = await Swal.fire({
				title: "Indique la fecha para obtener el cambio a facturar",
				input: "date",
				inputValidator: (fecha) => {
					if (!fecha) {
						return "Debe indicar una fecha válida";
					} else {
						return undefined;
					}
				},
				html: `
					<h1>Cambio: <small id="cambio" style="color:#007bff;"></small></h1>
					`,
				didOpen: async () => {
					const input = await Swal.getInput();
					const $cambio = await Swal.getHtmlContainer().querySelector(
						"#cambio"
					);
					input.oninput = async () => {
						$cambio.textContent = "";
						const cambio = await getExchangerate(input.value, 2);
						if (cambio != "") {
							ytasa_cambio = `${cambio}`;
							$cambio.textContent = `${cambio}`;
						}
					};
				},
				showCancelButton: true,
				confirmButtonText: "Ok",
				cancelButtonText: "Cancelar",
			});
			if (resultado.value) {
				const url = `${base_url}/Facturacion/update_tasa`;
				tasa_cambio = ytasa_cambio.replace(",", ".");
				try {
					var datos = new FormData();
					datos.append("id_cot", id_cot);
					datos.append("tasa_cambio", tasa_cambio);
					let url = `${base_url}/Facturacion/update_tasa`;
					let repuesta = await fetch(url, {
						method: "POST",
						body: datos,
					});
					const resultado = await repuesta.json();
					window.open(
						`${base_url}/Facturacion/print_factura_metro/` + id_cot,
						"_blank"
					);
				} catch (error) {
					console.log(error);
				}
			}
		} else {
			window.open(
				`${base_url}/Facturacion/print_factura_metro/` + id_cot,
				"_blank"
			);
		}
	} else {
		window.open(
			`${base_url}/Facturacion/print_factura/` + id_cot,
			"_blank"
		);
	}
}
function loadAndRenderPDF(e) {
	$(".loader").show();
	var file = e.target.files[0];
	let fr = new FileReader();
	fr.readAsDataURL(file);
	fr.onload = () => {
		let res = fr.result;
		extractText(res);
	};
}
async function extractText(url) {
	$(".loader").show();
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
	$(".loader").show();
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
			$(".loader").show();
			val = $.trim(text[i].items[x]["str"]);
			if (val == "CORPORACION MMQ, C.A.") {
				titem = 0;
				tlistarray++;
				tencontre = true;
			}
			titem++;
			if (tencontre && titem != 0) {
				if (titem == 2) {
					//console.log('titem == 2 ' + val);
					arrayCodCli[tlistarray] = val;
					url = `${base_url}/Facturacion/equivale`;					
					datos = new FormData();
					datos.append("id_prod", val);
					datos.append("id_ent", id_ent);
					datos.append("id_emp", id_emp);
					datos.append("format", 1);
					datos.append("id_alm", id_alm);
					datos.append("id_ubi", id_ubi_consig);
					datos.append("fecha", $("#fecha_comp").val());

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
				} else if (titem == 8) {
					if (val.indexOf("-") >= 0) {
						val1 = format_number_with_out_dec(val) * -1;
					} else {
						val1 = format_number_with_out_dec(val);
					}
					arrayCant[tlistarray] = val1;
				} else if (titem == 9) {
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
		$(".loader").show();
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
			if (xcosto != 0) {
				if (xitem.iva != 0) {
					iva_prod = "S";
					xcosto = xcosto * (100 / (xtasa_iva + 100));
				}
				pre_unit = xcosto / cant_det;
				pre_vta = xcosto / cant_det;
				sub_total = xcosto;
				item++;
				var htmlTags = `
				<tr	${xcolor} id="fila-${item}"> 
					<td class="text-right text-xs">${item}</td>
					<td style="width:30%"><input type="hidden" name="id_prod[]" id="id_prod${item}" class="text-xs photo" value="${id_prod}"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_prod${item}" name="nom_prod" readonly value="${nom_prod}" title="${nom_prod_title}"><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>
					<td style="width:8%"><input type="number" name="cant[]" id="cant${item}" class="form-control text-right text-xs reCalcular tcant" step="-1" style="width:80%" value="${cant_det}" onchange="CalculateTotalFac()"></td>
					<td style="width:8%"><input type="number" name="stock[]" id="stock${item}" class="form-control text-right text-xs" style="width:100%" disabled value="${saldo}"></td>
					<td style="width:7%"><input type="text" name="uni_ven_prod[]" id="uni_ven_prod${item}" readonly class="form-control text-right text-xs" style="width:100%" value="${uni_vta}"></td>
					<td style="width:8%"><input type="text" name="ventas_prod[]" id="ventas_prod${item}" readonly class="form-control text-right text-xs" disable style="width:100%" value="${format_number_with_dec_new(pre_unit, 4)}" </td>
					<td style="width:8%"><input type="text" name="ventas_prod1[]" id="ventas_prod1${item}" class="form-control text-right text-xs sub-total reCalcular camponumero" style="width:100%" value="${format_number_with_dec_new(pre_vta, 4)}" </td>
					<td style="width:10%"><input type="hidden" name="id_des_item[]" id="id_des_item${item}" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs text-right" id="nom_des${item}" name="nom_des" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-descuentos" title="Buscar y seleccionar descuento"><i class="fas fa-search"></i></a></span></div></div></td>
					<td style="width:10%"><select name="iva_prod[]" id="iva_prod${item}" class="form-control text-xs reCalcular input-iva" style="width:100%"></select></td>
					<td style="width:10%"><input type="text" name="total[]" id="total${item}" class="form-control text-right text-xs sub-total input-fila" readonly value="${format_number_with_dec_new(sub_total, 4)}" style="width:100%"></td>
					<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>
				</tr>`;
				$("#cuerpoTablaDetalle").append(htmlTags);
				listar_si_no(iva_prod, "iva_prod" + item);
				recorreTable_fac(1, tasa_cambio);
			}
		//}
	});	
	$(".loader").hide();
}