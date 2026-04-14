//Variables
let item = 0;
let c_consig = 0;
let tipo_fac = '';
let id_cli;
mov_inv = true;
mov_inv_sal = false;

//Validación de campos
$().ready(function(){
    $.validator.setDefaults({
        ignore: [],
    });
    $("form[name='my_form']").validate({
        rules: {
            id_emp: "required",
            id_tmovinv: "required",
            fecha_comp: "required",
            id_moneda: "required",
            tasa_cambio: "required",
            id_alm: "required",
            status: "required",
            item: "required",
        },
        messages: {
            id_emp: "Debe seleccionar una Empresa",
            id_tmovinv: "Debe seleccionar un Tipo de Movimiento",
            fecha_comp: "Debe seleccionar una Fecha",
            id_moneda: "Debe seleccionar una Moneda",
            tasa_cambio: "Debe indicar un Tipo de Cambio",
            id_alm: "Debe seleccionar un Almacén",
            status: "Debe seleccionar un Status",
            item: "Debe existir al menos un registro en el Detalle de Movimiento",
        },
        submitHandler: function(form){
            form.submit();
        }
    });
})
//Validar el tipo de formulario para sus acciones
$(document).ready(function(){
    id = $("#id").val();
    if(id){
        showrow(id);
    }else{
        listar_empresas(0);
        $("#fecha_comp").val(GetTodayDate(0));
        listar_monedas();
        listar_status(1);
    }
})
//Mostrar valores del registro
async function showrow(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url = `${base_url}/MovInv/showrow`; 
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();
        if(resultado){
            //Encabezado de Movimiento
            id_emp = resultado[0]['id_emp'];
            listar_empresas(id_emp);
            listar_InvTipoMov(id_emp, resultado[0]['id_tmovinv'], 'id_tmovinv');
            $("#num_movinv").val(resultado[0]['num_movinv']);
            $("#fecha_comp").val(resultado[0]['fecha_comp']);
            listar_monedas(resultado[0]['id_moneda']);
            xTasaCambio = number_format(resultado[0]['tasa_cambio'], 8);
            xTasaCambio = xTasaCambio.replace(".", ",");
            $("#tasa_cambio").val(xTasaCambio);
            listar_almacenes(id_emp, resultado[0]['id_alm']);
            $("#descrip_movinv").val(resultado[0]['descrip_movinv']);
            status = resultado[0]['status'];
            listar_status(status);
            origen = resultado[0]['origen'];
            //Detalle de Movimiento
            item = 0;
            nameid = "id_pro";
            $.each(resultado, async function(i, xitem){
                cantidad = xitem.cantidad;
                fec_venc = xitem.fec_venc;
                lote = xitem.lote;
                id_ubi = xitem.id_ubi
                id_prod = xitem.id_prod
                nom_prod = xitem.nom_prod
                nom_ubi = xitem.nom_ubi;
                item += 1;
                 var htmlTags =
                '<tr id="fila'+item+'" class="text-xs">' +
                '<td class="text-right text-xs" style="width:5%">'+item+'</td>' +
                '<td><input type="hidden" name="id_prod[]" id="id_prod'+item+'" class="text-xs" value="'+id_prod+'"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_prod'+item+'" name="nom_prod[]" readonly value="'+nom_prod+'"><div class="input-group-append"><span class="input-group-text text-xs"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search text-xs"></i></a></span></div></div></td>' +
                '<td><input type="hidden" name="id_ubi[]" id="id_ubi'+item+'" class="text-xs id_ubi" value="'+id_ubi+'"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_ubi'+item+'" name="nom_ubi[]" readonly value="'+nom_ubi+'"><div class="input-group-append text-xs"><span class="input-group-text text-xs"><a href="#" data-toggle="modal" data-target="#modal-ubicaciones" title=" Buscar y seleccionar Ubicaciones"><i class="fas fa-search text-xs"></i></a></span></div></div></td>' +                
                '<td><input type="text" name="lote[]" id="lote'+item+'" class="form-control text-xs" value="'+lote+'"></td>' +
                '<td><input type="date" name="fec_ven[]" id="fec_ven'+item+'" class="form-control text-xs" value="'+fec_venc+'"></td>' +
                '<td><input type="number" name="cant[]" id="cant'+item+'" class="form-control text-right text-xs" required value="'+cantidad+'"></td>' +
                '<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt"></i></button></div></td>' +
                '</tr>' ;
                $("#tblInvMovDet").append(htmlTags)
                $('.select-search').select2();
                $("#item").val(item);
            });
           

            //Bloquear el boton de actulizar si el movimiento es automatico
            if(origen){
				//Verificar si se puede actualizar el Lote y Fecha de Vencimiento
                const url = `${base_url}/MovInv/update_lotes`;
                 const datos = new FormData();
				datos.append("origen", origen);
				const respuesta = await fetch(url, {
					method: "POST",
					body: datos,
				});
                const resultado = await respuesta.json();
                if(!resultado){
                    $("#btnok").prop("disabled", true);
                }
			}
        }
    }catch(err){
        console.log(err)
    }
}
//Validar al seleccionar empresa
$(document).on('change', '#id_emp', async function(e){
    id_emp = $(this).val();
    //Limpiar campos
    $('#id_tmovinv').empty();
    $('#id_alm').empty();
    //Lllenar campos
    listar_InvTipoMov(id_emp, 0, 'id_tmovinv');
    listar_almacenes(id_emp);
    id_tdo_cfg = await tip_doc_fac(id_emp);
    id_alm_cfg = id_tdo_cfg['id_alm'];
    stock = id_tdo_cfg['fac_stock'];
    stock = 0;
})
//Validar si el tipo de movimiento utiliza consecutivo
$(document).on('change', '#id_tmovinv', async function(e){
    id_tmovinv = $(this).val();
    const datos = new FormData();
    datos.append('id', id_tmovinv);
    try{
        const url = `${base_url}/InvTipoMov/val_InvTipoMov`;
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();
        if(resultado){
            mov_inv_sal = false;
            if(resultado['consecutiv__tmoinv'] != 1){
                $("#num_movinv").prop( "readonly", false );
                $("#num_movinv").prop( "required", true );
            }
            if(resultado['tipo_tmoinv'] == 'S'){
                mov_inv_sal = true;
            }
        }
        id_alm_tra = resultado['id_alm'];
        if(id_alm_tra > 0){
            listar_almacenes(id_emp);
        }
        
    }catch(err){
        console.log(err);
    }
})
//Validar el Tipo de Cambio dependiendo de la moneda seleccionada
$(document).on('change', '#id_moneda', async function(e){
    id_moneda = $(this).val();
    fecha_comp = $("#fecha_comp").val();
    xTasaCambio = await xTasa(fecha_comp, id_moneda);
    if(xTasaCambio){
        $("#tasa_cambio").val(xTasaCambio);
    }
})
//Detalles del Movimiento
//document.querySelector('#btnAddRow').addEventListener('click', addNewRow);
$(document).on('click', '#btnAddRow', function(e){
    addNewRow();
})

function addNewRow() {
    nameid = "id_pro";
    item = last_item_table("tblInvMovDet") + 1;
    var htmlTags =
    '<tr id="fila'+item+'" class="text-xs">' +
    '<td class="text-right text-xs" style="width:5%">'+item+'</td>' +
    '<td><input type="hidden" name="id_prod[]" id="id_prod'+item+'" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_prod'+item+'" name="nom_prod[]" readonly><div class="input-group-append"><span class="input-group-text text-xs"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search text-xs"></i></a></span></div></div></td>' +
    '<td><input type="hidden" name="id_ubi[]" id="id_ubi'+item+'" class="text-xs id_ubi"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_ubi'+item+'" name="nom_ubi[]" readonly><div class="input-group-append text-xs"><span class="input-group-text text-xs"><a href="#" data-toggle="modal" data-target="#modal-ubicaciones" title=" Buscar y seleccionar Ubicaciones"><i class="fas fa-search text-xs"></i></a></span></div></div></td>' +
    '<td><input type="text" name="lote[]" id="lote'+item+'" class="form-control text-xs"></td>' +
    '<td><input type="date" name="fec_ven[]" id="fec_ven'+item+'" class="form-control text-xs"></td>' +
    '<td><input type="number" name="cant[]" id="cant'+item+'" class="form-control text-right text-xs" required></td>' +
    '<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt"></i></button></div></td>' +
    '</tr>' ;
    $("#tblInvMovDet").append(htmlTags)
    $('.select-search').select2();
    $("#item").val(item);
}
//Consultar productos añadidos a la tabla de detalles
async function ConsultarProductoInv(id, item){
    let id_prod = id;
    const datos = new FormData();
    datos.append('id_prod', id_prod);
    try{
        const url = `${base_url}/Productos/consulta01`;
        const respuesta = await fetch (url, {
            method: "POST",
            body: datos,
        });
        const resultado = await respuesta.json();
        if(resultado){
            monto = 0;
            lote = resultado['lote_prod'];
            if(lote == 1){
                $("#lote"+item).prop( "readonly", false );
                $("#fec_ven"+item).prop( "readonly", false );
            }else{
                $("#lote"+item).prop( "readonly", true );
                $("#fec_ven"+item).prop( "readonly", true );
            }
        }
    }catch (err){
        console.log(err);
    }
}
//Funcion Boton Eliminar
function eliminarBtn(e) {
    let id = e.dataset.id;
    let name = e.dataset.name;
    let code = e.dataset.code
    let number = e.dataset.number
    const url =  `${base_url}/MovInv/destroy`;
    var message = "¿Está seguro de eliminar el Movimiento " + code + ' - ' + name + " Número " + number + "?";

    Swal.fire({
        icon: 'question',
        title: message,
        showConfirmButton: true,
        confirmButtonText: 'ELIMINAR',
        confirmButtonColor: '#3085d6',
        showCancelButton: true,
        cancelButtonText: 'CANCELAR',
        cancelButtonColor: '#d33',
        buttonsStyling: true,
        showLoaderOnConfirm: true,
        preConfirm: function(){
            return new Promise(function(resolve){
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {id: id, name: name, code: code, number: number},
                    dataType: 'json'
                }).done(function(response){
                    console.log(response);
                     Swal.fire({
                        icon: `${response.icon}`,
                        title: `${response.title}`,
                        text: `${response.msg}`,
                    });
                    window.location.href = `${base_url}/MovInv`;
                }).fail(function(e){
                    console.log(e);
                     Swal.fire({
                        icon: 'error',
                        title: 'Error.....',
                        text: 'No se pudo eliminar el registro, por favor intente luego'
                    });
                })
            })
        }
    });
    allowOutsideClick: false
}
//Borrar una fila de la tabla
$(document).on('click', '.borrar', function(){
    var table = $('.tblEncaMov').DataTable();
    let $tr = $(this).closest('tr');
    table.row($tr).remove().draw(false);

})
//Imprimir Movimiento
async function printer_movement(id) {
	id_mov = id.dataset.id;
    number_mov = id.dataset.number;
    tipo_mov = id.dataset.tipo;
    name_mov = id.dataset.name;
    Swal.fire({
		icon: "question",
		title: "¿Está seguro que desea imprimir el Movimiento de Inventario " + tipo_mov + " - " + name_mov + " número " + number_mov + "?",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",
   }).then((result) => {
		if (result.isConfirmed) {
			window.open(`${base_url}/MovInv/printer_movinv/` + id_mov, "_blank");
		}
   });
}
//Seleccionar registro de detalle
$(".tblEncaMov").on("click", "tr", function () {
	var filaId = $(this).attr("id");
	item = filaId.substring(4);
});