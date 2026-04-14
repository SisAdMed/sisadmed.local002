//Variables
id = $("#id").val();
origen_COM = 1;
tipo_fac = 'OI'; 
id_alm = ''
item = 0;
let unidades = 0;
tasa_cambio_Val = 1; 
let xitem;
//Validaciones del formulario
$(function(){
    $("#my_form").validate({
        rules:{
            fecha_comint: "required",
            id_provint: "required",
            item: {
                required: function(element){
                    return $("#tblCompInt tbody tr").length == 0;
                }
            },
            status: "required",
        },
        messages:{
            fecha_comint: "Debe especificar una fecha",
            id_provint: "Debe especificar un proveedor",
            item: "Se requiere al menos un item",
            status: "Debe especificar un status",
        },
    });
    id_emp = 2;
    $('#id_emp').val(id_emp);
    $("#id_moneda").val(id_emp);
})
function validateForm(){
    let mensage = ""
    let x = $("#tblCompInt tbody tr").length;
    if(x==0){
        mensage = "Debe especificar al menos un registro!";
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: mensage,
        });
        return false;
    }
}
jQuery(document).ready(function(){
    if(id){
        cargar_data(id);
    }else{
        var todayDate = new Date().toLocaleDateString('en-CA', {timeZone: "America/Caracas"});
        $("#fecha_comint").val(todayDate);
        listar_status(1);
        listar_proveeint('', 'id_provint');
    }
})
async function cargar_data(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url = `${base_url}/ComprasInt/cargar_data`;
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos,
        });
        const resultado = await respuesta.json();
        if(resultado){
            //Datos del encabezado
            $("#id_comint").val(resultado['id_comint']);
            $("#fecha_comint").val(resultado['fecha_comint']);
            listar_proveeint(resultado['id_provint'], 'id_provint');
            $("#descrip_compint").val(resultado['descrip_compint']);
            listar_status(resultado['status']);
            //Datos del detalle
            showrowupdate(id);
        }
    }catch(error){
        console.log(error);
    }
}
async function showrowupdate(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url = `${base_url}/ComprasInt/show_row`;
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos,
        });
        const resultado = await respuesta.json();
        $("#tblCompIntDet").html('');
        for(x of resultado){
            item = item + 1;
            consultar_producto(x.id_prod);
            var htmlTags =
                `<tr id="fila${item}"> +
                    <td><input type="hidden" name="id_prod[]" id="id_prod${item}" class="text-xs" value="${x.id_prod}"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_prod${item}" name="nom_prod" readonly value="${x.nom_prod}" title="${x.nom_prod}"><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>
		            <td><input type="text" name="ref_prod[]" id="ref_prod${item}" readonly class="form-control text-xs" style="width:100%" value="${x.ref_prod}"></td>
                    <td><input type="text" name="nom_fab[]" id="nom_fab${item}" readonly class="form-control text-xs" style="width:100%" value="${x.nom_fab}"></td>
		            <td><input type="text" name="nom_pre[]" id="nom_pre${item}" readonly class="form-control text-xs" value="${x.nom_pre}"></td>
		            <td><input type="text" name="cantidad[]" id="cantidad${item}" class="form-control text-xs text-right total-unidad" style="width:100%" min="1" value="${x.cantidad}" required></td>
		            <td><input type="text" name="precio[]" id="precio${item}" class="form-control text-xs text-right calc-precio camponumero" style="width:100%" value="${format_number_with_dec_new(x.costo,4)}"></td>
		            <td><input type="text" name="total_uni[]" id="total_uni${item}" readonly class="form-control text-xs text-right" style="width:100%" value="${x.uni_ven_prod * x.cantidad}"></td>
		            <td><input type="text" name="precio_uni[]" id="precio_uni${item}" readonly class="form-control text-xs text-right" style="width:100%" value="${format_number_with_dec_new((x.cantidad * x.costo) / (x.uni_ven_prod * x.cantidad),4)}"></td>
		            <td><input type="text" name="precio_tot[]" id="precio_tot${item}" readonly class="form-control text-xs text-right fila-input" style="width:100%" value="${format_number_with_dec_new((x.cantidad * x.costo),4)}"></td>
		            <td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></div></td>
                </tr>`;
            $("#tblCompIntDet").append(htmlTags);
        }
        UpdateDataTable("#tblCompInt");
    }catch(error){
        console.log(error);
    }
}
$(document).on('click', '#btnAgregateCompInt', function(e){
    e.preventDefault();
    agregarDetalleComInt()
})
async function agregarDetalleComInt(){
    if(xitem){
        item = xitem;
    }
    item++;
    var htmlTags =
		'<tr id="fila' +
		item +
		'">' +
		`<td><input type="hidden" name="id_prod[]" id="id_prod${item}" class="text-xs photo"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_prod${item}" name="nom_prod" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>` +
		'<td><input type="text" name="ref_prod[]" id="ref_prod' + item + '" readonly class="form-control text-xs" style="width:100%"></td>' +
        '<td><input type="text" name="nom_fab[]" id="nom_fab' + item + '" readonly class="form-control text-xs" style="width:100%"></td>' +
		'<td><input name="nom_pre[]" id="nom_pre' + item + '" readonly class="form-control text-xs"></td>' +
		'<td><input type="text" name="cantidad[]" id="cantidad' + item + '" class="form-control text-xs text-right total-unidad" style="width:100%" min="1" required></td>' +
		'<td><input type="text" name="precio[]" id="precio' + item + '" class="form-control text-xs text-right calc-precio camponumero" style="width:100%"></td>' +
		'<td><input type="text" name="total_uni[]" id="total_uni' + item + '" readonly class="form-control text-xs text-right" style="width:100%"></td>' +
		'<td><input type="text" name="precio_uni[]" id="precio_uni' + item + '" readonly class="form-control text-xs text-right" style="width:100%"></td>' +
		'<td><input type="text" name="precio_tot[]" id="precio_tot' + item + '" readonly class="form-control text-xs text-right fila-input" style="width:100%"></td>' +
		'<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></div></td>' +
		"</tr>";
    $("#tblCompIntDet").append(htmlTags);

}
$(document).on("keyup", ".total-unidad", function () {
    id_prod = $(`#id_prod${item}`).val();
    consultar_producto(id_prod, item)
	cantidad = $("#cantidad" + item).val();
    unidades = uni_vta_gbl;
	cantidad_x_unidades = cantidad * unidades;
	$("#total_uni" + item).val(cantidad_x_unidades);
	precio = formatoMoneda($("#precio" + item).val());
	precio_tot = cantidad * precio;
    precio_uni = precio_tot / cantidad_x_unidades;
    $("#precio_uni" + item).val(format_number_with_dec_new(precio_uni, 4));
	$("#precio_tot" + item).val(format_number_with_dec_new(precio_tot, 4));
});

$(document).on('keyup', '.calc-precio', function(){
    id_prod = $(`#id_prod${item}`).val();
	consultar_producto(id_prod, item);
    cantidad = formatoMoneda($("#cantidad" + item).val());
    precio = formatoMoneda($("#precio" + item).val());
	unidades = uni_vta_gbl;
	cantidad_x_unidades = cantidad * unidades;
	precio_tot = cantidad * precio;
	precio_uni = precio_tot / cantidad_x_unidades;
	$("#precio_uni" + item).val(format_number_with_dec_new(precio_uni, 4));
	$("#precio_tot" + item).val(format_number_with_dec_new(precio_tot,4));
})
//Consultar producto
async function consultar_producto(id_prod, item){
    const datos = new FormData();
    datos.append('id_prod', id_prod);
    datos.append('origen', "1");
    try{
        const url = `${base_url}/Productos/consulta`;
        const respuesta = await fetch (url, {
            method: 'POST',
            body: datos,
        })
        const resultado = await respuesta.json();
        if(resultado){
            $("#ref_prod"+item).text(resultado['ref_prod']);
            $("#nom_pre"+item).text(resultado['nom_pre']);
            $("#nom_fab" + item).text(resultado["NOM_FAB"]);
            UpdateDataTable("#tblCompInt");
            unidades = resultado['uni_ven_prod'];
            uni_vta_gbl = unidades
        }
    }catch(error){
        console.log(error);
    }
}
//Al seleccionar un registro de la tabla
$('body').on('click', '#tblCompInt tr', function() {
    selectFile = $(this).attr('id');
    itemSelected = (selectFile.substring(4));    
    xitem = item; 
    item = itemSelected;   
    id_prod = $(`#id_prod${item}`).val();
    consultar_producto(id_prod, item);
});
//funcion para elimnar una fila
$(document).on('click', '.borrar', function(event) {
    event.preventDefault();
    $(this).closest('tr').remove();
    if(itemSelected>0){
        titem = itemSelected;
    }else{
        titem = item;
    }
});
//Borrar un registro del index
//Borrar un Tipo de nomina
$("#tblCompInt tbody").on("click", ".btn-delete-index", function (event) {
    alert('pase');
	event.preventDefault();
	let id = $(this).data("id");
	let code = $(this).data("code");
	let name = $(this).data("name");
	Swal.fire({
		title: `¿Está seguro de eliminar el Concepto de Nómina ${code} - ${name}?`,
		text: "Esta acción no se puede deshacer.",
		icon: "question",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Sí, eliminarlo",
		cancelButtonText: "Cancelar",
	}).then((result) => {
		if (result.isConfirmed) {
			const url = `${base_url}/ComprasInt/destroy`;
			$.ajax({
				url: url,
				type: "POST",
				dataType: "json",
				data: { id: id },
				sync: false,
				beforeSend: function () {
					loader.show();
				},
				complete: function () {
					loader.hide();
				},
				error: function (error) {
					loader.hide();
					console.log(
						"Ha ocurrido el siguiente error: ",
						error.responseText
					);
				},
				success: function (data) {
					Swal.fire({
						title: data.title,
						text: data.msg,
						icon: data.icon,
					}).then((result) => {
						if (data.icon != "error") {
							// Recarga el DataTable
							tableIndex = $(tblIndexMain).DataTable();
							tableIndex.draw(); // El 'false' previene que se reajuste la paginación a la página 1.
							tableIndex.ajax.reload(null, true);
						}
					});
				},
			});
		}
	});
});
//Imprimir Comprar
function print_comint(e){
    id = e.dataset.id;
    code = e.dataset.code
    fuente = id  +'|' + code
    msg = `¿Está seguro que desea Imprimir la Compra Internacional Nro. ${id} en ${code}?`
    Swal.fire({
		icon: "question",
		title: msg,
	}).then((result) => {
		if (result.isConfirmed) {
                window.open(`${base_url}/ComprasInt/printer/${fuente}`, "_blank");
		}
	});
}