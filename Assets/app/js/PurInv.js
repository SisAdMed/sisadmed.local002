//Variables
let item = 0;
let itemSelect;
let c_consig;
origen_COM = 1;
stock = 0;
let id_tdo;
myConsulproduc = {};
ori = "";
//Validar campos
$(function () {
	$("form[name='my_form']").validate({
		rules: {
			id_emp: "required",
			id_tdo: "required",
			num_tdo: "required",
			fecha_comp: "required",
			fec_fact: "required",
			id_moneda: "required",
			nom_cli: "required",
			sub_total: "required",
			status: "required",
			num_control: {
				required: function(element){
					return $("#ori").val() == 'M';
				},
			},
		},
		messages: {
			id_emp: "Debe especificar una empresa",
			id_tdo: "Debe especificar un Tipo de documento",
			num_tdo: "Debe especificar un Número de documento",
			fecha_comp: "Debe especificar un fecha del documento",
			fec_fact: "Debe especificar uan fecha de factura del proveedor",
			id_moneda: "Debe especificar un moneda del documento",
			nom_cli: "Debe especificar un proveedor del documento",
			sub_total: "Debe especificar al menos un item",
			status: "Debe especificar un Status",
			num_control: "Debe especificar un Número de Control"
		},
	});
});
//Al ingresar a la apliacion
$(document).ready(function () {
	$(".especial_contrib").hide();
	id = $("#id").val();
	tipo_fac = "COM";
	ori = $("#ori").val();
	if (id) {
		dat_form_com(id);
	} else {
		listar_empresas(0);
		$("#fecha_comp").val(GetTodayDate(0));
		$("#fecha_venci").val(GetTodayDate(0));
		$("#fec_fact").val(GetTodayDate(0));
		listar_InvTipoMov(0, "", "id_tmovinv");
		listar_monedas();
		listar_status(1);
	}
});
//Lleno el combo de proveedores dependiendo de la empresa seleccionada
$(document).on("change", "#id_emp", async function (event) {
	event.preventDefault();
	id = $("#id").val();
	$("#id_tdo").empty();
	id_tdo_val = "";
	id_tdo_cfg = "";
	id_emp = $(this).val();
	id_tdo_cfg = await tip_doc_com(id_emp);

	//Validar si es contribuyente especial
	especial_contrib = id_tdo_cfg["especial_contrib"];
	$(".especial_contrib").hide();
	if (especial_contrib == "S" && ori != "T") {
		$(".especial_contrib").show();
		listar_retiva(0, "id_retiva");
	}
	if (!id) {
		if (id_tdo_cfg) {
			if (ori == "M") {
				id_tdo_val = id_tdo_cfg["tdoc_pur"];
				id_tdoc_pre = id_tdo_cfg["tdoc_purord"];
				listar_tipos_documentos_CXP(
					id_emp,
					id_tdoc_pre,
					"",
					false,
					"fuente"
				);
			} else if (ori == "O") {
				id_tdo_val = id_tdo_cfg["tdoc_purord"];
				tip_doc_cxp_cfg = await tip_doc_cxp(id_tdo_val);
			} else if (ori == "A") {
				id_tdo_val = id_tdo_cfg["tdoc_purcrenot"];
			} else if (ori == "T") {
				id_tdo_val = id_tdo_cfg["tdoc_purdelnot"];
			} else if (ori == "V") {
				id_tdo_val = id_tdo_cfg["tdoc_purretnot"];
			}
			listar_tipos_documentos_CXP(id_emp, ori, id_tdo_val);

			$("#id_tdo").css("pointer-events", "none");
		}
	}
	stock = 0;
});
//Tipo de documento
$(document).on("change", "#id_tdo", function () {});
// para agregar un detalle mas, una fila
function agregarDetalleFactura() {
	nameid = "id_pro";
	if (itemSelect > 0) {
		item = itemSelect;
		itemSelect = 0;
	}
	item++;
	nameid = nameid;
	var htmlTags = `
		<tr id="fila${item}"> 
		<td class="text-right text-xs">${item}</td>
		<td style="width:20%"><input type="hidden" name="id_prod[]" id="id_prod${item}" class="text-xs photo"><div class="input-group"><input type="text"  class="form-control text-xs" id="nom_prod${item}" name="nom_prod[]" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>
		<td><input type="number" name="cant[]" id="cant${item}" class="form-control text-right text-xs tcant" min="1" style="width:80%"  onchange="CalculateTotalCom()"></td>
		<td><input type="text" name="lote[]" id="lote${item}" class="form-control text-right text-xs" style="width:100%"> </td>
		<td><input type="date" name="fec_venc[]" id="fec_venc${item}" class="form-control text-right text-xs reCalcular" style="width:100%"></td>
		<td><input type="text" name="uni_com_prod[]" id="uni_com_prod${item}" readonly class="form-control text-right text-xs" style="width:100%"></td>
		<td><input type="text" name="costo_prod[]" id="costo_prod${item}" readonly class="form-control text-right text-xs" readonly style="width:100%"></td>
		<td><input type="text" name="costo_prod1[]" id="costo_prod1${item}" class="form-control text-right text-xs reCalcular camponumero6" style="width:100%"></td>
		<td style="width:10%"><select name="iva_prod[]" id="iva_prod${item}" class="form-control text-xs reCalcular " style="width:60%"></select>
		<td><input type="text" name="total[]" id="total${item}" class="form-control text-right text-xs sub-total" readonly></td>
		<td class="text-center"><div class="btn-group"><button id="Data" type="button" class="btn btn-danger btn-sm borrar" data-id="${item}" title="Eliminar item" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>' 
		</tr>`;
	$("#tblDetalle").append(htmlTags);
	listar_si_no("", "iva_prod" + item);
}
$("body").on("click", "#tblDetalle tr", function () {
	selectFile = $(this).attr("id");
	itemSelect = parseInt(selectFile.substring(4), 10);
	if (itemSelect > 0) {
		xitem = item;
		item = itemSelect;
	}
	$("#tblModalProd").DataTable().clear();
});
//Borrar item
$("#tblDetalle").on("click", ".borrar", function (event) {
	$(this).closest("tr").remove();
	recorreTable_com(tasa_cambio); 
});
//Recalcular en caso de cambiar el IVA a si o No
$(document).on("change", ".reCalcular", function (event) {
	CalculateTotalCom();
});
function CalculateTotalCom() {
	
	costo_prod = $("#costo_prod1" + item).val();
	if(costo_prod.includes(",")){
		costo_prod = formatoMoneda(costo_prod);
	}else{
		costo_prod = parseFloat(costo_prod);
	}
	xcant = $("#cant" + item).val();
	uni_com_prod = $("#uni_com_prod" + item).val();
	$("#costo_prod" + item).val(format_number_with_dec_new(costo_prod / uni_com_prod, 4));
	$("#total" + item).val(format_number_with_dec_new(xcant * costo_prod, 4));
	monto = $("#total" + item).val();

	if (itemSelect > 0) {
		item = xitem;
	}
	recorreTable_com(tasa_cambio); 
}
//Combo de monedas
$(document).on("change", "#id_moneda", async function (e) {
	e.preventDefault();
	fecha_comp = $("#fecha_comp").val();
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
	id_cli = $("#id_cli").val();
	event.preventDefault();
	fetcingData(id_cli);
});
async function fetcingData(id_cli) {
	const datosFetched = await tid_vend(id_cli);
	cod_diascre = datosFetched["cod_diascre"] ?? 0;
	$("#fecha_venci").val($("#fecha_comp").val());
	if (cod_diascre) {
		fecha_venci = GetTodayDate(cod_diascre);
		$("#fecha_venci").val(fecha_venci);
	}
	nom_cli = datosFetched["nom_ent"];
	$("#nom_cli").val(nom_cli);
	show_tasa();
	//Cargar Documentos Fuentes y Numeros para emitir Nota de Credito
	if (ori == "A") {
		load_document_com(id_cli);
	}
}
//Cargar Documentos Fuentes y Numeros para emitir Nota de Credito
function load_document_com(id_cli) {
	listar_tipos_documentos_CXP(id_emp, "M", "", false, "fuente");
}
//Cargar los Numeros de Documentos segun la fuente seleccionada cuando es Nota de Credito
$("#fuente").on("change", function (e) {
	tipo_doc_ori = $(this).val();
	listar_doc_fuentes_cxp(id_emp, "0", "origen", ori, id_cli, tipo_doc_ori);
});
//Borrar - Se cambia es el status a inactivo para no perder el cosnecutivo
function eliminarBtn(element) {
	let id = element.dataset.id;
	let origen = element.dataset.name;
	let id_emp = element.dataset.code;
	let ori = $("#ori").val();
	const datos = new FormData();
	datos.append("id", id);
	datos.append("origen", origen);
	datos.append("id_emp", id_emp);
	datos.append("ori", ori);
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
	let url = `${base_url}/PurInv/destroy`;
	let repuesta = await fetch(url, {
		method: "POST",
		body: datos,
	});
	const resultado = await repuesta.json();
	try {
		
		Swal.fire({
			icon: resultado.ico,
			title: resultado.title,
			text: resultado.msg,
		}).then((result) =>{
			if (result.isConfirmed) {
				window.location.href = `${base_url}/PurInv`;
			}
		});
	
	} catch (error) {
		console.log(error);
	}
	
	
}
async function dat_form_com(id, fuente = false) {
	const url = `${base_url}/PurInv/showrow`;
	$.ajax({
		url: url,
		data: { id: id },
		method: "POST",
		dataType: "json",
		dataSrc: "",
		beforeSend: function () {
			$(".loader").show();
		},
		success: async function (resultado) {
			id_emp = resultado[0]["id_emp"];
			id_cli = resultado[0]["id_cli"];
			if ((ori != "A" && id) || (ori == "A" && !fuente)) {
				id_tdo = resultado[0]["id_tdo"];
				num_tdo = resultado[0]["num_tdo"];
				num_control = resultado[0]["num_control"];
				fecha_comp = resultado[0]["fecha_comp"];
				fec_fact = resultado[0]["fec_fact"];
				fecha_venci = resultado[0]["fecha_venci"];
				//
				listar_tipos_documentos_CXP(id_emp, "", id_tdo, true);
				$("#num_tdo").val(num_tdo);
				$("#num_control").val(num_control);
				$("#fecha_comp").val(fecha_comp);
				$("#fec_fact").val(fec_fact);
				$("#fecha_venci").val(fecha_venci);
			}
			
			if(resultado[0]['id_cont']){
				id_cont = resultado[0]['id_cont'].split('-');
				tipo_doc_ori = 'A';				
				listar_tipos_documentos_CXP(id_emp, "M", id_cont[1], true, "fuente");

				listar_doc_fuentes_cxp(id_emp, id_cont[2], 'origen', tipo_doc_ori, id_cli, id_cont[1], true);
				
			}
			id_moneda = resultado[0]["id_moneda"];
			tasa_cambio = format_number_with_dec_new(resultado[0]["tasa_cambio"], 2 );
			
			nom_cli = resultado[0]["nom_ent"];

			listar_empresas(id_emp, true);

			listar_monedas(id_moneda, true);
			$("#tasa_cambio").val(tasa_cambio);
			$("#id_cli").val(id_cli);
			$("#nom_cli").val(nom_cli);
			//Validar si es contribuyente especial
			id_tdo_cfg = await tip_doc_com(id_emp);
			especial_contrib = id_tdo_cfg["especial_contrib"];
			if (especial_contrib == "S") {
				id_retiva = resultado[0]["id_retiva"];
				listar_retiva(id_retiva, "id_retiva");
				$(".especial_contrib").show();
			}

			show_tasa();
			item = 0;
			xvalor = 1;
			
			if (ori == "A" && fuente) {
				xvalor = -1;
			}
			resultado.forEach(function (e) {
				item++;
				total = e.pre_vta * e.can_det;
				var htmlTags =
					'<tr id="fila' +
					item +
					'" >' +
					'<td class="text-right text-xs">' +
					item +
					"</td>" +
					'<td style="width:20%"><input type="hidden" name="id_prod[]" id="id_prod' +
					item +
					'" class="text-xs photo" value="' +
					e.id_prod +
					'"><div class="input-group"><input type="text"  class="form-control text-xs" id="nom_prod' +
					item +
					'" name="nom_prod[]" readonly value="' +
					e.nom_prod +
					'"><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>' +
					'<td><input type="number" name="cant[]" id="cant' +
					item +
					'" class="form-control text-right text-xs tcant" min="1" style="width:80%" onchange="CalculateTotalCom()" value="' +
					e.can_det +
					'"></td>' +
					'<td><input type="text" name="lote[]" id="lote' +
					item +
					'" value="' +
					e.lote +
					'" class="form-control text-right text-xs" tyle="width:100%"> </td>' +
					'<td><input type="date" name="fec_venc[]" id="fec_venc' +
					item +
					'" value="' +
					e.fec_venc +
					'" class="form-control text-right text-xs" style="width:100%"> </td>' +
					'<td><input type="text" name="uni_com_prod[]" id="uni_com_prod' +
					item +
					'" value="' +
					e.uni_vta +
					'" readonly class="form-control text-right text-xs"  style="width:100%"></td>' +
					'<td><input type="text" name="costo_prod[]" id="costo_prod' +
					item +
					'" value="' +
					format_number_with_dec_new(e.pre_unit * xvalor, 6) +
					'" readonly class="form-control text-right text-xs " readonly style="width:100%"> </td>' +
					'<td><input type="text" name="costo_prod1[]" id="costo_prod1' +
					item +
					'"  value="' +
					format_number_with_dec_new(e.pre_vta * xvalor, 6) +
					'" class="form-control text-right text-xs reCalcular camponumero6" style="width:100%"> </td>' +
					'<td style="width:10%"><select name="iva_prod[]" id="iva_prod' +
					item +
					'" class="form-control text-xs reCalcular" style="width:60%"></select></td>' +
					'<td><input type="text" name="total[]" id="total' +
					item +
					'" class="form-control text-right text-xs sub-total" value="' +
					format_number_with_dec_new(total * xvalor, 2) +
					'" readonly></td>' +
					'<td class="text-center"><div class="btn-group"><button id="Data" type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" data-id="' +item+ '" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>' +
					"</tr>";
				$("#tblDetalle").append(htmlTags);
				listar_si_no(e.iva_prod, "iva_prod" + item);
				CalculateTotalCom();
			});
		},
		complete: function () {
			loader.hide();
		},
		error: function (xhr, status, error) {
			$(".loader").hide();
		},
	});
}
//Listar Documentos Fuentes para emitir Nota de Crédito
function listar_doc_fuentes_cxp(id_emp, id, tag, tipo, id_cli, tipo_doc_ori, bloquear = false) {
	const url = `${base_url}/PurInv/listar_doc_fuentes`;
	if (tipo_doc_ori){
		$.ajax({
			url: url,
			method: "POST",
			data: {
				id_emp: id_emp,
				id: id,
				tipo: tipo,
				id_cli: id_cli,
				tipo_doc_ori: tipo_doc_ori,
			},
			dataSrc: "",
			dataType: "json",
			beforeSend: function () {
				loader.show();
			},
			complete: function () {
				loader.hide();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				loader.hide();
				// Handle the error
				console.error("AJAX Error: ", textStatus, errorThrown);
				console.error("Response Text: ", jqXHR.responseText);
			},
			success: function (data) {
				if (data) {
					var htmlTags = `<option>Selecione...</option>`;
					var selecTed = " selected ";
					$.each(data, function (index, item) {
						if (item.id_cot == id) {
							htmlTags += `<option ${selecTed} value=${item.id_cot}>${item.doc}</option>`;
						} else {
							htmlTags += `<option value=${item.id_cot}>${item.doc}</option>`;
						}
					});
					$("#origen").html(htmlTags);
					if (bloquear) {
						$("#origen").css("pointer-events", "none");
					}
				}
			},
		});
	}
}
//Mostrar datos del Documento Origen en pantalla
$("#origen").on("change", function (e) {
	e.preventDefault();
	id_cot_ori = $(this).val();
	dat_form_com(id_cot_ori, true);
});
