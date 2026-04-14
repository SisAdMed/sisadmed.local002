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
let id_ven_cli =0;
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
let id_cli_glb
let xtasa_coti = "";
let tasa_cambio = 0;
//Validar si requiere autorizacíon el descuento a aplicar
let appreq = 0;
let disapp ="";
let c_consig = 0;
//
$(document).ready(async function(){

	$(".foranea").hide();
	$(".local").hide();
	id_cot = $("#id").val();
	if(id_cot){
		showrowupdate(id_cot);
	}else{
		listar_empresas();
		listar_descuentos(0, 'id_des_enca');
		$("#fecha_comp").val(GetTodayDate(0));
		$("#fecha_venci").val(GetTodayDate(0));
		$("#nro_control").css("display", "none");
	}
});

//Lleno el combo de clientes dependiendo de la empresa seleccionada
$(document).on('change', '#id_emp', async function(event) {
	event.preventDefault();
	id = $("#id").val();
	$("#id_tdo").val('');
	$("#fuente").val('');
	$("#origen").val('');
	id_tdo_val = '';
	id_tdo_cfg = '';
	id_emp = $("#id_emp").val();
	if(!id){
		id_tdo_cfg = await tip_doc_fac(id_emp);
		if(id_tdo_cfg){
			id_tdo_val = id_tdo_cfg['id_tdoc_cre']
			id_tdoc_pre = id_tdo_cfg['id_tdoc_fac']
			listar_tipos_documentos(id_emp, 'C', id_tdo_val);
			$("#id_tdo").css("pointer-events","none");
			$("#id_tdo").trigger( "change" );
		}
		listar_tipos_documentos(id_emp, 'F', id_tdoc_pre, false, "fuente");
		$("#fuente").css("pointer-events","none");
		$("#fuente").trigger( "change" );
	}
});
//Validar si el Tipo de Documento usa consecutivo o no para poder aisgnar el número del documento
$(document).on('change', '#id_tdo', async function(e){
	const datos = new FormData();
	datos.append('id', id_tdo_val);
	try{
		const url = `${base_url}/CXCDocument/val_tdo`;
		const respuesta = await fetch(url, {
			method: 'POST',
			body: datos
		})
		const resultado = await respuesta.json();
		if(resultado){
			if(resultado[0]['con_tdoc'] == 0){
				$('#num_tdo').prop('readonly', false);
			}else{
				$('#num_tdo').prop('readonly', true);
			}
		}
	}catch(error){
		console.log(error);
	}
})
//Seleccionar registro marcado del Modal de clietnes y mostrarlo en el formulario
$('body').on('click', '#tblModal tr', function() {
	id_cli = $(this).attr('id');
	$("#id_cli").val(id_cli);
	$("#id_cli").trigger( "change" );
	$('#modal-clientes').modal('hide')
});
//Lleno el combo de origen de facturacion por la fuente seleccionada
$(document).on('change', '#fuente', function(event){
	event.preventDefault();
	id_empr = $("#id_emp").val();
	id_emp = id_empr;
	listar_fac_facturas(id_emp, '0', 'origen');
})
//Completar datos del encabezado y detalle de la factura al seleccioanr un origen
$(document).on('change', '#origen', function(event){
	event.preventDefault();
	id_cotiza = $("#origen").val();
	$("#cuerpoTablaDetalle").html('');
	show_data_cotiza(id_cotiza);
})
// Mostar Datos cuando se selecciona una cotizacion
async function show_data_cotiza(id_cot, dcto=0){
	datos = new FormData();
	datos.append("id_cot", id_cot);
	try{
		const url = `${base_url}/Cotizaciones/consultar_cotizacion`;
		const respuesta = await fetch (url, {
			method: "POST",
			body: datos,
		});
		const resultado = await respuesta.json();
//Validar si hay descuento
		if(dcto != 0){
			x_val_dct = (dcto/100);
		}
		item = 0;
		origen = $("#origen").val()
		if(origen){
			id_cli = (resultado[0]['id_cli']);
			$("#id_cli").val(id_cli);
			fetcingData(id_cli);
			$("id_moneda").trigger('change');
			xtasa_coti = resultado[0]['tasa_cambio'];
			xtasa_coti = format_number_with_dec(xtasa_coti,8);
			if(xtasa_coti){
				Swal.fire({
					title: "Desea utilizar la Tasa de Cambio de la Cotización "+ xtasa_coti +"?",
					icon: "question",
					showDenyButton: true,
					confirmButtonText: "Si",
					denyButtonText: `No`
				}).then((result) => {
					if (result.isConfirmed) {
						$("#tasa_cambio").val(xtasa_coti);
						tasa_cambio = xtasa_coti;
					}else{
						tasa_cambio = $("#tasa_cambio").val();
					}
					for (x of resultado){
						item = item + 1;
						nameid = "id_pro" + item;
//nameid = nameid + item;
//Variables valor
						id_prod = x['id_prod'];
						nom_prod = x['nom_prod'];
						cant_det = x['can_det'];
						stock = x['stock'];
						uni_vta = x['uni_vta'];
						pre_unit = parseFloat(x['pre_unit']);
						pre_vta = parseFloat(x['pre_vta']);
						iva_prod = x['iva_prod'];
						sub_total = parseFloat(x['sub_total']);
						if(dcto!=0){
							pre_unit = pre_unit - (pre_unit*x_val_dct);
							pre_vta = pre_vta - (pre_vta*x_val_dct);
							sub_total = sub_total - (sub_total*x_val_dct)
						}
						pre_unit = format_number_with_dec(pre_unit,4);
						pre_vta = format_number_with_dec(pre_vta,4);
						iva_prod = x['iva_prod'];
						sub_total = format_number_with_dec(sub_total,4);
						var htmlTags =
						'<tr class="text-xs" id="fila'+item+'">' +
						'<td class="text-right text-xs">'+item+'</td>' +
						'<td style="width:20%"><input type="hidden" name="id_prod[]" id="id_prod'+item+'" class="text-xs photo" value="'+id_prod+'"><div class="input-group"><input type="text"  class="form-control text-xs" id="nom_prod'+item+'" name="nom_prod[]" readonly value="'+nom_prod+'" title="'+nom_prod+'"><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>' +
						'<td style="width:8%"><input type="number" name="cant[]" id="cant'+item+'" readonly class="form-control text-right text-xs tcant" min="1"style="width:80%" value="'+cant_det+'" onchange="CalculateTotalFac()" ></td>' +
						'<td style="width:10%"><input type="number" name="stock[]" id="stock'+item+'" class="form-control text-right text-xs stock" style="width:100%" disabled value="'+stock+'"></td>' +
						'<td style="width:7%"><input type="text" name="uni_ven_prod[]" id="uni_ven_prod'+item+'" readonly class="form-control text-right text-xs"  style="width:100%" value="'+uni_vta+'"></td>' +
						'<td style="width:10%"><input type="text" name="ventas_prod[]" id="ventas_prod'+item+'" readonly class="form-control text-right text-xs" disable style="width:100%" value="'+pre_unit+'" </td>' +
						'<td style="width:10%"><input type="text" name="ventas_prod1[]" id="ventas_prod1'+item+'" readonly class="form-control text-right text-xs " disable style="width:100%" value="'+pre_vta+'" </td>' +
						'<td hidden style="width:10%"><input type="hidden" name="id_des_item[]" id="id_des_item'+item+'" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs text-right" id="nom_des'+item+'" name="nom_des" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-descuentos" title="Buscar y seleccionar descuento"><i class="fas fa-search"></i></a></span></div></div></td>' +
						'<td><select name="iva_prod[]" id="iva_prod'+item+'" readonly class="form-control text-xs reCalcular" style="width:100%"></select>' +
						'<td style="width:10%"><input type="text" name="total[]" id="total'+item+'" class="form-control text-right text-xs sub-total" step="0.0001" readonly value="'+sub_total+'" style="width:100%"></td>' +
						'<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>' +
						'</tr>';
						$("#tblDetalle").append(htmlTags);
						listar_si_no(iva_prod, "iva_prod"+item);
						$(".stock").trigger('change');
						$("#iva_prod"+item).css("pointer-events","none");
					}
					recorreTable_fac(1, tasa_cambio.replace(",", "."));
				});
			}
		}
	}catch(error){
		console.log(error)
	}
}
//Aplicar descuentos general
$(document).on('change', '#id_des_enca', async function(e){
	id_des = $(this).val();
	xdesglb = await getRateDes(id_des);
	desdes = "";
	$("#cuerpoTablaDetalle").html('');
	if(id_des){
		show_data_cotiza(id_cotiza, parseFloat((xdesglb['valor_tipdes'])));
	}else{
		show_data_cotiza(id_cotiza);
	}
/*appreq = await xdesglb['appreq'];
desdes = await xdesglb['codigo_tipdes'];
if(appreq == 1){
reqapp = await ReqApp(desdes);
Swal.fire({
position: "top-end",
icon: "success",
title: "Your work has been saved",
showConfirmButton: false,
timer: 1500000
});
}*/
})
//Solicitar aprobación para aplicar el descuento solicitado
async function ReqApp(desdes){
	title = "Aprobación aplicación de descuentos";
	message = "Se solicita aprobación de descuentos para facturar la cotizacion del " + desdes;
	const datos = new FormData();
	datos.append("title", title);
	datos.append("message", desdes);
	try{
		const url = `${base_url}/Facturacion/aprobacion`;
		var respuesta = await fetch (url, {
			method: 'POST',
			body: datos
		})
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 200);
		});
	}catch(error){
		console.log(error)
	}
}
//Busqueda del valor del porcentaje de descuento
async function getRateDes(id_des){
	var datos = new FormData();
	datos.append('id', id_des);
	try{
		const url = `${base_url}/TipoDcto/show_row`;
		var respuesta = await fetch (url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 200);
		}) ;
	}catch (err){
		console.log(err);
	}
}
//Combo de monedas
$(document).on('change', '#id_moneda', async function(event) {
	event.preventDefault();
	fecha_comp = $("#fecha_comp").val();
	id_moneda = $("#id_moneda").val();
	fecha_comp = $("#fecha_comp").val();
	xtasa = await getExchangerate(fecha_comp, id_moneda);
	$("#tasa_cambio").val(financial(xtasa,8));
	tasa_fac = financial(xtasa,8);
	tasa_cambio = xtasa;

	show_tasa();
});
//Seleccioanr por defecto el vendedor al momento de escoger el cliente
$(document).on('change', '#id_cli', function(event) {
	event.preventDefault();
	fetcingData($(this).val());
});

async function fetcingData(id_cli) {
	const datosFetched = await tid_vend(id_cli);
	c_consig = datosFetched['c_consig'];
	cod_diascre = datosFetched['cod_diascre'] ?? 0;
	$("#fecha_venci").val($("#fecha_comp").val());
	if(cod_diascre){
		fecha_venci = GetTodayDate(cod_diascre);
		$("#fecha_venci").val(fecha_venci);
	}
	id_ven_cli = datosFetched['id_vend'];
	id_mon_cli = datosFetched['id_moneda'];
	nom_cli = datosFetched['nom_ent'];
	$("#nom_cli").val(nom_cli);
	listar_vendedores(id_ven_cli);
	listar_monedas(id_mon_cli);
	fecha_comp = $("#fecha_comp").val();
	xTasaCambio = await getExchangerate(fecha_comp, id_mon_cli);
	if(!id_cot){
		$("#tasa_cambio").val(xTasaCambio);
		tasa_fac = financial(xTasaCambio,8);
	}
	tasa_cambio = xTasaCambio;
	show_tasa();
}

$('body').on('click', '#tblDetalle tr', function() {
	selectFile = $(this).attr('id');
	itemSelected = (selectFile.substring(4));
	let selects = $('.mi-select');
	id_prod_img =  $(this).find('option:selected').val();
});
// para agregar un detalle mas, una fila
function agregarDetalleFactura() {
	nameid = "id_pro";
	item = item + 1;
	nameid = nameid;
	var htmlTags =
	'<tr  id="fila'+item+'" >' +
	'<td class="text-right text-xs">'+item+'</td>' +
	'<td style="width:30%"><input type="hidden" name="id_prod[]" id="id_prod'+item+'" class="text-xs photo"><div class="input-group"><input type="text"  class="form-control text-xs" id="nom_prod'+item+'" name="nom_prod" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>' +
	'<td style="width:8%"><input type="number" name="cant[]" id="cant'+item+'" class="form-control text-right text-xs tcant" min="1" style="width:80%" onchange="CalculateTotalFac()" ></td>' +
	'<td style="width:8%"><input type="number" name="stock[]" id="stock'+item+'" class="form-control text-right text-xs stock" style="width:100%" disabled></td>' +
	'<td style="width:7%"><input type="text" name="uni_ven_prod[]" id="uni_ven_prod'+item+'" readonly class="form-control text-right text-xs"  style="width:100%"></td>' +
	'<td style="width:8%"><input type="text" name="ventas_prod[]" id="ventas_prod'+item+'" readonly class="form-control text-right text-xs" readonly style="width:100%" step="0.0001" > </td>' +
	'<td style="width:8%"><input type="text" name="ventas_prod1[]" id="ventas_prod1'+item+'"  class="form-control text-right text-xs reCalcular" style="width:100%" step="0.0001" > </td>' +
	'<td style="width:10%"><input type="hidden" name="id_des_item[]" id="id_des_item'+item+'" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs text-right" id="nom_des'+item+'" name="nom_des" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-descuentos" title="Buscar y seleccionar descuento"><i class="fas fa-search"></i></a></span></div></div></td>' +
	'<td style="width:10%"><select name="iva_prod[]" id="iva_prod'+item+'" class="form-control text-xs reCalcular" style="width:60%"></select>' +
	'<td style="width:10%"><input type="text" name="total[]" id="total'+item+'" class="form-control text-right text-xs sub-total" readonly step="0.0001"></td>' +
	'<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>' +
	'</tr>';
	$("#tblDetalle").append(htmlTags);
	listar_si_no("", "iva_prod"+item);
}
//Validar stock de producto
$(document).on('change', '.stock', function(){
	StockProducto(id_prod, item);
})
//Validar el motivo y las lineas con valores adicionales dependiendo del cliente
$('#modal-productos').on('show.bs.modal', function (e) {
//Preparar carga de la tabla
	url = `${base_url}/Productos/listar_productos_modal`;
	try{
		$.ajax({
			method: "POST",
			url: url,
			success: function (response){
				response = JSON.parse(response);
				$('#tblModalProd').DataTable().clear();
				$('#tblModalProd').DataTable().destroy();
				var tblModal = $('#tblModalProd').DataTable({
					fnCreatedRow: function( rowEl, response) {
						$(rowEl).attr('id', response[0]);
					},
					language: {
						"sProcessing":     "Procesando...",
						"sLengthMenu":     "Mostrar _MENU_ registros",
						"sZeroRecords":    "No se encontraron resultados",
						"sEmptyTable":     "Ningún dato disponible en esta tabla",
						"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
						"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
						"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
						"sSearch":         "Buscar:",
						"sInfoThousands":  ",",
						"sLoadingRecords": "Cargando...",
						"oPaginate": {
							"sFirst":    "Primero",
							"sLast":     "Último",
							"sNext":     "Siguiente",
							"sPrevious": "Anterior"
						},
						"oAria": {
							"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
							"sSortDescending": ": Activar para ordenar la columna de manera descendente"
						},
						"buttons": {
							"copy": "Copiar",
							"colvis": "Visibilidad"
						}
					}
				});
				response.forEach(function(e) {
					tblModal.row.add([
						e.id_prod,
						e.cod_prod,
						e.cod2_prod,
						e.nom_prod,
						e.ref_prod,
						e.nom_fab,
						e.stock,
						]).draw();
				});
			},
			error: function (xhr, status, error){
				console.log("error");
				toastr.error(error, "Error");
			}
		});
	}catch(error){
		console.log(error);
	}
});
//Seleccionar registro marcado del Modal de clietnes y mostrarlo en el formulario
$('body').on('click', '#tblModalProd tr', function() {
	id_prod = $(this).attr('id');
	$("#id_prod"+item).val(id_prod);
	ConsultarProducto(id_prod, item, '', '', 'V', c_consig);
	$(".stock").trigger( "change" );
	$('#modal-productos').modal('hide')
	tasa_cambio = $("#tasa_cambio").val();
	recorreTable_fac(1, tasa_cambio.replace(",", "."));
});

//Recalcular en caso de cambiar el IVA a si o No 
$(document).on('change', '.reCalcular', async function(event){
	CalculateTotalFac()
})
//Al seleccionar una producto
//Buscar registros de la cotización a editar
async function showrowupdate(id){
	const datos = new FormData();
	datos.append('id_cot', id);
	try{
		const url = `${base_url}/Facturacion/consultar_factura`;

		const respuesta = await fetch (url, {
			method: "POST",
			body: datos,
		});
		const resultado = await respuesta.json();
		id_emp = resultado[0]['id_emp']
		id_cont = resultado[0]['id_cont'];
		if(id_cont){
			id_tdo_cfg = await tip_doc_fac(id_emp);
			id_tdoc_pre = id_tdo_cfg['id_tdoc_pre']
			listar_tipos_documentos(id_emp, 'F', id_tdo_val);
/* if(id_tdo_cfg){
id_tdo_val = id_tdo_cfg['id_tdoc_fac']
$("#id_tdo").css("pointer-events","none");
$("#id_tdo").trigger( "change" );
}*/
			listar_tipos_documentos(id_emp, 'P', id_tdoc_pre, false, "fuente");
			$("#fuente").css("pointer-events","none");
			$("#fuente").trigger( "change" );

		id_tdo = resultado[0]['id_tdo']
		num_tdo = resultado[0]['num_tdo'];
		fecha_comp = resultado[0]['fecha_comp'];
		id_cli = resultado[0]['id_cli'];
		listar_empresas(id_emp, true);
		listar_tipos_documentos(id_emp, 'F', id_tdo, true);
		$("#num_tdo").val(num_tdo);
		$("#fecha_comp").val(fecha_comp);
		$('#fecha_comp').attr('readonly', 'readonly');
		$("#id_cli").val(id_cli);
		$("#id_cli").trigger('change');
		$('#id_moneda').attr('readonly', 'readonly');
		$('#id_vend').attr('readonly', 'readonly');
		$("#tasa_cambio").val(format_number_with_dec(resultado[0]['tasa_cambio'],8));
		$("#id_des_enca").val(resultado[0]['id_des'])
		$("#oc_cliente").val(resultado[0]['oc_cliente'])
		$("#descrip_cot").val(resultado[0]['descrip_cot'])
		$("#nro_control").val('00-' + resultado[0]['nro_control'])
		$("#nro_control").css("display", "block");
		for (x of resultado){
			item = item + 1;
			nameid = "id_pro" + item;
//nameid = nameid + item;
//Variables valor
			id_prod = x['id_prod'];
			nom_prod = x['nom_prod'];
			cant_det = x['can_det'];
			uni_vta = x['uni_vta'];
			pre_unit = x['pre_unit'];
			pre_vta = x['pre_vta'];
			iva_prod = x['iva_prod'];
			sub_total = x['sub_total'];
			stock = 0;
			var htmlTags =
			'<tr id="fila'+item+'">' +
			'<td class="text-right text-xs">'+item+'</td>' +
			'<td style="width:20%"><input type="hidden" name="id_prod[]" id="id_prod'+item+'" class="text-xs photo" value="'+id_prod+'"><div class="input-group"><input type="text"  class="form-control text-xs" id="nom_prod'+item+'" name="nom_prod" readonly value="'+nom_prod+'"><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>' +
			'<td style="width:8%"><input type="number" name="cant[]" id="cant'+item+'"  class="form-control text-right text-xs tcant" min="1"style="width:80%" value="'+cant_det+'" onchange="CalculateTotalFac()" ></td>' +
			'<td style="width:10%"><input type="number" name="stock[]" id="stock'+item+'" class="form-control text-right text-xs stock" style="width:100%" disabled value="'+stock+'"></td>' +
			'<td style="width:8%"><input type="text" name="uni_ven_prod[]" id="uni_ven_prod'+item+'" readonly class="form-control text-right text-xs"  style="width:100%" value="'+uni_vta+'"></td>' +
			'<td style="width:8%"><input type="text" name="ventas_prod[]" id="ventas_prod'+item+'" readonly class="form-control text-right text-xs" disable style="width:100%" value="'+format_number_with_dec(pre_unit,4)+'"></td>' +
			'<td style="width:8%"><input type="text" name="ventas_prod1[]" id="ventas_prod1'+item+'" readonly class="form-control text-right text-xs" readonly style="width:100%" value="'+format_number_with_dec(pre_vta,4)+'"></td>' +
			'<td style="width:10%"><input type="hidden" name="id_des[]" id="id_des'+item+'" class="text-xs"><div class="input-group"><input type="text"  class="form-control text-xs text-right" id="nom_des'+item+'" name="nom_des" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-descuentos" title="Buscar y seleccionar descuento"><i class="fas fa-search"></i></a></span></div></div></td>' +
			'<td style="width:12%"><select name="iva_prod[]" id="iva_prod'+item+'" class="form-control text-xs reCalcular" style="width:60%"></select></td>' +
			'<td style="width:8%"><input type="text" name="total[]" id="total'+item+'" class="form-control text-right text-xs sub-total" readonly value="'+format_number_with_dec(sub_total,4)+'"></td>' +
			'<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>' +
			'</tr>';
			$("#tblDetalle").append(htmlTags);
			listar_si_no(iva_prod, "iva_prod"+item);
			xTasaCambio = resultado[0]['tasa_cambio']
			$(".stock").trigger( "change" );
			recorreTable_fac(1,xTasaCambio);
		}
	}catch(err){
		console.log(err);
	}
}

$("#confirmar").on('click', function(){
	recuperar_selects();
})

//funcion para elimnar una fila
$(document).on('click', '.borrar', function(event) {
	event.preventDefault();
	$(this).closest('tr').remove();
/*if(itemSelected>0){
titem = itemSelected;
}else{
titem = item;
}board.map(function(dato){
if(dato.id == titem){
dato.total = 0;
}
});*/
	recorreTable_fac(1, tasa_cambio.replace(",", "."));
});

//funcion para confirmar una fila
$(document).on('click', '.confirmar', function(event) {
	event.preventDefault();
	recuperar_selects()
});

$(document).on('change', '.confirmar', function(event) {
	event.preventDefault();
	recuperar_selects()
});

function recuperar_selects () {
	let selects = $('.mi-select');

	selects.each(function () {
		let select = $(this);
		id_pro = select.val()
	});
	ConsultarProducto(id_pro, item);
	StockProducto(id_pro, item);
}

async function CalculateTotalFac(){
	let xcantidad;
	let xprecio_venta;
	let xunidad_venta;
	if(itemSelected >0){
		xcantidad = $("#cant"+itemSelected).val();
		xprecio_venta = $("#ventas_prod1"+itemSelected).val();
		xprecio_venta = xprecio_venta.replace(".", "");
		xprecio_venta = xprecio_venta.replace(",", ".");
		xunidad_venta = $("#uni_ven_prod"+itemSelected).val();
		xunidad_venta = xunidad_venta.replace(".", "");
		xunidad_venta = xunidad_venta.replace(",", ".");
		$("#ventas_prod"+itemSelected).val(format_number_with_dec(xprecio_venta/xunidad_venta,4));
		$("#total"+itemSelected).val(format_number_with_dec(xcantidad * xprecio_venta,4));
//$("#total"+itemSelected).val($("#cant"+itemSelected).val() * $("#ventas_prod1"+itemSelected).val() );
		monto = xcantidad * xprecio_venta;
	}else{
		xcantidad = $("#cant"+item).val();
		xprecio_venta = $("#ventas_prod1"+item).val();
		xprecio_venta = xprecio_venta.replace(".", "");
		xprecio_venta = xprecio_venta.replace(",", ".");
		console.log(xprecio_venta);
//xprecio_venta = $("#ventas_prod1"+item).val();
		xunidad_venta = $("#uni_ven_prod"+item).val();

		$("#ventas_prod"+item).val(xprecio_venta/xunidad_venta)

		$("#total"+item).val(xcantidad * xprecio_venta);
		monto = $("#total"+item).val();
	}
	if(itemSelected>0){
		titem = itemSelected;
	}else{
		titem = item;
	}
	let modo = 'N';
	if(id_cot){
		modo = 'M';
	}
	id_cot = $("#id").val();
	if(id_cot){
		recorreTable_fac(1,tasa_cambio.replace(",", "."));
	}else{
		recorreTable_fac(1,tasa_cambio.replace(",", "."));
	}
}


//Borrar - Se cambia es el status a inactivo para no perder el cosnecutivo
function eliminarBtn(element) {
	let id = element.dataset.id;
	let name = element.dataset.name;
	let codigo = element.dataset.code;
	const datos = new FormData();
	datos.append('id', id);
	datos.append('name', name);
	datos.append('codigo', codigo);
	Swal.fire({
		icon: 'warning',
		title: 'Está seguro de eliminar este registro?',
		showConfirmButton: true,
		confirmButtonText: 'ELIMINAR',
		confirmButtonColor: '#3085d6',
		showCancelButton: true,
		cancelButtonText: 'CANCELAR',
		cancelButtonColor: '#d33',
		buttonsStyling: true,
	}).then((result) => {
		if (result.isConfirmed){
			borrar(datos);
		};
	});
}
async function borrar(datos){
	let url =  `${base_url}/Facturacion/destroy`;
	let repuesta = await fetch(url, {
		method:"POST",
		body: datos,
	});
	const resultado = await repuesta.json();
	if (resultado){
		Swal.fire({
			icon: 'success',
			title: 'Registro eliminado satisfactoriamente',
			showConfirmButton: true,
		});
		window.location.href = `${base_url}/Facturacion`;
	}
}
//Al abrir modal para mostrar las fotos de los productos
$(document).on('click', '.show-picture', function(event) {
//Datos para mostrar las fotos
	show_picture();
});
async function show_picture(){
	try{
		var datos = new FormData();
		datos.append('id_prod_img', id_prod_img);
		let url = `${base_url}/Productos/showImg`;
		let repuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const resultado = await repuesta.json();
		$("#imgPreview").empty();
		if(resultado){
			$("#title-product").text(resultado[0]['nom_prod']);
			var show_photo = '';
			$.each(resultado, function(i, item) {
				show_photo = '<img width="200px" height="200px" title="'+item['filename']+'" src="'+base_url+item['url_photo']+'">' +
				'</img> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' ;
				$("#imgPreview").append(show_photo);
			});
		}
		id_prod_img = 0;
	}catch(err){
		console.log(err);
	}
}
//Validar el motivo y las lineas con valores adicionales dependiendo del cliente
$('#modal-clientes').on('show.bs.modal', function (e) {
	id_emp = $("#id_emp").val();
//Preparar carga de la tabla
//load_data_entidad(id_emp, 'C');
	url = `${base_url}/Cotizaciones/listar_entidad_modal`;
	try{
		$.ajax({
			method: "POST",
			url: url,
			data: { id: id_emp, tipo : 'C' },
			success: function (response){
				response = JSON.parse(response);
				$('#tblModal').DataTable().clear();
				$('#tblModal').DataTable().destroy();
				var tblModal = $('#tblModal').DataTable({
					columnDefs: [
					{
						target: 1,
						visible: false,
						searchable: false
					}
					],
					fnCreatedRow: function( rowEl, response) {
						$(rowEl).attr('id', response[0]);
					},
					language: {
						"sProcessing":     "Procesando...",
						"sLengthMenu":     "Mostrar _MENU_ registros",
						"sZeroRecords":    "No se encontraron resultados",
						"sEmptyTable":     "Ningún dato disponible en esta tabla",
						"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
						"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
						"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
						"sSearch":         "Buscar:",
						"sInfoThousands":  ",",
						"sLoadingRecords": "Cargando...",
						"oPaginate": {
							"sFirst":    "Primero",
							"sLast":     "Último",
							"sNext":     "Siguiente",
							"sPrevious": "Anterior"
						},
						"oAria": {
							"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
							"sSortDescending": ": Activar para ordenar la columna de manera descendente"
						},
						"buttons": {
							"copy": "Copiar",
							"colvis": "Visibilidad"
						}
					}
				});
				response.forEach(function(e) {
					tblModal.row.add([
						e.id_ent,
						e.rif_ent,
						e.nom_ent,
						e.nombre_zona,
						e.nom_vend
						]).draw();
				});
			},
			error: function (xhr, status, error){
				console.log("error");
				toastr.error(error, "Error");
			}
		});
	}catch(error){
		console.log(error);
	}
});
//Seleccionar registro marcado del Modal de clietnes y mostrarlo en el formulario
$('body').on('click', '#tblModal tr', function() {
	id_cli = $(this).attr('id');
	$("#id_cli").val(id_cli);
	$("#id_cli").trigger( "change" );
	$('#modal-clientes').modal('hide')
});
function show_tasa(){
	id_emp = $("#id_emp").val();
	id_moneda = $("#id_moneda").val();
	tasa_cambio = $("#tasa_cambio").val();
	url = `${base_url}/Empresas/showrow`;
	try{
		$.ajax({
			url: url,
			method: "POST",
			data: { id: id_emp},
			success: function(respuesta){
				respuesta = JSON.parse(respuesta);
				id_moneda_base = respuesta['id_moneda'];
				if(id_moneda != id_moneda_base){
					$(".foranea").show();
					$(".local").hide();
				}else{
					$(".local").show();
					$(".foranea").hide();
				}
			}
		})
	}catch(error){
		console.log(error);
	}
}
//Mostrar Modal de Tipos de Descuentos
$('#modal-descuentos').on('show.bs.modal', function (e) {
//Preparar carga de la tabla
	url = `${base_url}/TipoDcto/listar_descuentos`;
	try{
		$.ajax({
			method: "POST",
			url: url,
			success: function (response){
				response = JSON.parse(response);
				$('#tblModalDcto').DataTable().clear();
				$('#tblModalDcto').DataTable().destroy();
				var tblModal = $('#tblModalDcto').DataTable({
					fnCreatedRow: function( rowEl, response) {
						$(rowEl).attr('id', response[0]);
					},
					language: {
						"sProcessing":     "Procesando...",
						"sLengthMenu":     "Mostrar _MENU_ registros",
						"sZeroRecords":    "No se encontraron resultados",
						"sEmptyTable":     "Ningún dato disponible en esta tabla",
						"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
						"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
						"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
						"sSearch":         "Buscar:",
						"sInfoThousands":  ",",
						"sLoadingRecords": "Cargando...",
						"oPaginate": {
							"sFirst":    "Primero",
							"sLast":     "Último",
							"sNext":     "Siguiente",
							"sPrevious": "Anterior"
						},
						"oAria": {
							"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
							"sSortDescending": ": Activar para ordenar la columna de manera descendente"
						},
						"buttons": {
							"copy": "Copiar",
							"colvis": "Visibilidad"
						}
					}
				});
				response.forEach(function(e) {
					tblModal.row.add([
						e.id,
						e.codigo_tipdes,
						e.valor_tipdes
						]).draw();
				});
			},
			error: function (xhr, status, error){
				console.log("error");
				toastr.error(error, "Error");
			}
		});
	}catch(error){
		console.log(error);
	}
});
//Seleccionar registro marcado del Modal de clietnes y mostrarlo en el formulario
$('body').on('click', '#tblModalDcto tr', async function() {
	id_des = $(this).attr('id');
	$("#id_des"+item).val(id_des);
//Buscar valor del Descuento
	url = `${base_url}/TipoDcto/show_row`;
	try{
		$.ajax({
			url: url,
			method: "POST",
			data: { id: id_des},
			success: function(respuesta){
				respuesta = JSON.parse(respuesta);
				mon_des_val = parseFloat(respuesta['valor_tipdes']);
				nom_des = format_number_with_dec(mon_des_val,2);
				$("#nom_des"+item).val(nom_des);
//Actualizar costos de linea
				ventas_prod = $("#ventas_prod"+item).val();
				ventas_prod = ventas_prod.replace(".","");
				ventas_prod = ventas_prod.replace(",",".");
				ventas_prod = ventas_prod - (ventas_prod * (mon_des_val / 100));
				$("#ventas_prod"+item).val(format_number_with_dec(ventas_prod,4));
//Actualizar costos de linea
				ventas_prod = $("#ventas_prod1"+item).val();
				ventas_prod = ventas_prod.replace(".","");
				ventas_prod = ventas_prod.replace(",",".");
				ventas_prod = ventas_prod - (ventas_prod * (mon_des_val / 100));
				$("#ventas_prod1"+item).val(format_number_with_dec(ventas_prod,4));
//Actualizar costos de linea
				ventas_prod = $("#total"+item).val();
				ventas_prod = ventas_prod.replace(".","");
				ventas_prod = ventas_prod.replace(",",".");
				ventas_prod = ventas_prod - (ventas_prod * (mon_des_val / 100));
				$("#total"+item).val(format_number_with_dec(ventas_prod,4));
				id_fact = $("#id").val();
				if(id){
					recorreTable_fac(0, tasa_cambio.replace(",","."));
				}else{
					recorreTable_fac(1, tasa_cambio.replace(",","."));
				}

			}
		})
	}catch(error){
		console.log(error);
	}
	$('#modal-descuentos').modal('hide')
});