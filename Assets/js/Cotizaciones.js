//Variables
tipo_fac = 'P';
item = '';
itemSelected = '';
id_cot = ''; 
$(document).ready(function(){
    //Validar datos del Formulario
    jQuery.validator.setDefaults({
		debug: false,
		success: "valid",
	});
    $("form#my_form").validate({
		rules: {
			id_emp: "required",
			id_tdo: "required",
			fecha_comp: "required",
			nom_cli: "required",
			id_moneda: "required",
			id_vend: "required",
			observa: "required",
			item: "required",
		},
		messages: {
			id_emp: "Debe especificar una Empresa",
			id_tdo: "Debe especificar un Tipo de Documentos",
			fecha_comp: "Desbe especificar una Fecha válida",
			nom_cli: "Debe especificar un Cliente",
			id_moneda: "Debe especificar una Moneda",
			id_vend: "Debe especificar un Vendedor",
			observa: "Debe indicar una Observación",
			item: "Debe especificar al menos un detalle",
		},
	});
    //Cargar el index
    form = $('form').attr('id');
    if(form === undefined){
        initCotizaciones();
    }else{
        id = $('#id').val();
        if(id){
            dat_form();
        }else{
            dat_form_new();
        }
    }
})
//Formulario Nuevo
function dat_form_new(){
    listar_empresas();
	$("#fecha_comp").val(GetTodayDate(0));
	listar_status(1);
}
//Consultar registro
function dat_form(id){
	div_loading();
	console.log(id);
	const url = `${base_url}/Cotizaciones/consultar_cotizacion`;
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: {id_cot, id},
		dataType: 'json',
		beforeSend: function(){
			loader.show();
		},
		complete: function(){
			loader.hide();
		}, 
		error: function(jqXHR, textStatus, errorThrown){
			loader.hide();
			console.log('Ha ocurrido el siguiente error: ', textStatus, errorThrown );
		},
		success: function(data){
			console.log(data);
		}
	})
}
//Validar empresa
$('#id_emp').on('change', async function(e){
	e.preventDefault();
	id_emp = $(this).val();
	$("#id_tdo").val("");
	id_tdo_cfg = '';
	$("#id_cli").val("");
	$("#nom_cli").val("");
	$("#id_moneda").empty();
	$("#id_vend").empty();
	$("#id_fab").empty();
	$("#tasa_cambio").val('');
	if(id_emp){
		id_tdo_cfg = await tip_doc_fac(id_emp);
		loc_pri_cot = id_tdo_cfg["loc_pri_cot"];
		locked_invoice = id_tdo_cfg["locked_invoice"];
		stock = id_tdo_cfg["cot_stock"];
		listar_monedas();
		listar_vendedores();
		listar_marcas(0, 'id_fab');
	}
	if (!id) {
		if (id_tdo_cfg) {
			id_tdo_val = id_tdo_cfg["id_tdoc_pre"];
			listar_tipos_documentos(id_emp, tipo_fac, id_tdo_val);
			id_tdo = id_tdo_val;
			$("#id_tdo").trigger("change");
		}
	}
})
//Validar si el Tipo de Documento usa consecutivo o no para poder aisgnar el número del documento
$(document).on("change", "#id_tdo", function (e) {
	div_loading();
	const url = `${base_url}/CXCDocument/val_tdo`;
	if(!id_tdo){
		id_tdo = $(this).val();
	}
	try {
		$.ajax({
			url: url,
			method: 'POST',
			data: {id: id_tdo},
			dataType: 'json',
			dataSrc: '',
			beforeSend: function(){
				loader.show();
			},
			complete: function(){
				loader.hide();
			},
			error: function(jqXHR, textStatus, errorThrown){
				loader.hide();
				console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
			},
			success: function(data){
				if(data.con_tdoc == 0){
					$('#num_tdo').prop('readonly', false);
				}else{
					$("#num_tdo").prop("readonly", true);
				}
				if(data.sol_aprob == 1){
					sol_aprob = true;
					$("#status").val(9);
					$("#status").css("pointer-events", "none");
				}
				$("#id_tdo").css("pointer-events", "none");
			}
		})
	} catch (error) {
		console.log('Ha ocurrido el siguiente error: ' + (error));
	}
});
//Al cambiar la fecha
$(document).on('change', '#fecha_comp', function(event) {
    event.preventDefault();
  	update_tasa()
});
//Combo de monedas
$(document).on('change', '#id_moneda', function(event) {
    event.preventDefault();
	update_tasa()
    
});
//Actualizar tasa y mostrar campos de valores de moneda
async function update_tasa(){
	fecha_comp = $("#fecha_comp").val();
    id_moneda = $("#id_moneda").val();
	$("#tasa_cambio").val("");
	$(".local").hide();
	$(".foranea").hide();
	if(id_moneda && fecha_comp){
		xtasa = await getExchangerate(fecha_comp, id_moneda);
		$("#tasa_cambio").val(financial(xtasa, 8));
		tasa_cambio = xtasa;
		show_tasa();
	}
	$("#tasa_cambio").css("pointer-events", "none");
}
//Seleccioanr por defecto el vendedor al momento de escoger el cliente
$(document).on('change', '#id_cli', function(e) {
	e.preventDefault();
    id_cli = $(this).val();
    fetcingData(id_cli);
});
async function fetcingData(id_cli) {
	const datosFetched = await tid_vend(id_cli);
	id_ven_cli = datosFetched["id_vend"];
	id_mon_cli = datosFetched["id_moneda"];
	nom_cli = datosFetched["nom_ent"];
	handling_conver = datosFetched["handling_conver"];
	$("#nom_cli").val(nom_cli);
	listar_vendedores(id_ven_cli);
	listar_monedas(id_mon_cli);
	$("#id_moneda").val(id_mon_cli);
	update_tasa();
}
//Guardar y/o Actualizar
$("#my_form").on("submit", function (e) {
	e.preventDefault();
	if($(this).valid()){
		div_loading();
		var formData = $(this).serialize();
		const url = `${base_url}/Cotizaciones/store`;
		$.ajax({
			type: 'POST',
			url: url,
			dataSrc: '',
			data: formData,
			dataType: 'json',
			beforeSend: function(){
				loader.show();
			},
			complete: function(){
				loader.hide();
			},
			error: function(PDOException){
				loader.hide();
				console.log('Ha ocurrido el siguiente error: ' + PDOException.responseText);
			}, 
			success: function(data){
				Swal.fire({
					title: data.title,
					text: data.msg,
					icon: data.icon,
				}).then((result) => {
					if (data.icon != "error") {
						window.location.href = `${base_url}/Cotizaciones`;
					}
				});
			}
		})
	}else{
		return false;
	}
});
//Imprimir cotización segun la moneda indicada  
function print_cotiza(e, ori){
    //ori = 1 pdf, ori = 2 excel
    let num_cot = e.dataset.code;
    let id_moneda = e.dataset.name;
    let id_code = e.dataset.id;
    if(ori){
        id_code = id_code + '|' + ori;
    }
    if(id_moneda == "USD"){
        Swal.fire({
            icon: 'question',
            title: "¿Está seguro que desea imprimir la Cotización número "+num_cot+"?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Solo en " + id_moneda,
            denyButtonText: "Ambas monedas"
        }).then((result) => {
            if (result.isConfirmed) {
                window.open(`${base_url}/Cotizaciones/print_cotiza_foranea/` + id_code, '_blank');
            } else if (result.isDenied) {
                window.open(`${base_url}/Cotizaciones/print_cotiza/` + id_code, '_blank');
            }
        });
    }
    else{
        Swal.fire({
            icon: 'question',
            title: "¿Está seguro que desea imprimir la Cotización número "+num_cot+"?",
            showCancelButton: true,
            confirmButtonText: "¿Imrprimir ?",
        }).then((result) => {
            if (result.isConfirmed) {
                window.open(`${base_url}/Cotizaciones/print_cotiza/` + id_code, '_blank');
            }
        });
    }
}