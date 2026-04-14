//Variables
let item = 0;
let tipo_fac = '';
let handling_conver = '';
stock = '';
equivale = true;
stock = 0;
//Validaciones
$(function(){
    $("form[name='my_form']").validate({
        rules:{
            id_emp: "required",
            nom_cli: "required",
            fecha: "required",
            format : "required",
            item: "required",
            status: "required",
        },
        messages:{
            id_emp: "Debe especificar una empresa",
            nom_cli: "Debe indicar un Cliente",
            fecha: "Debe indicar una fecha de vigencia",
            format : "Debe indicar un fomato",
            item: "Debe indicar al menos un producto",
            status: "Debe indicar un Status",
        },
    })
})
//Validar formulario dependiendo de lo solicitado nuevo/editar
$(document).ready(function(){
    id = $("#id_empr").val();
    if(id){
        dat_form(id);
    }else{
        listar_empresas(0);
        listar_status(1);
    }
})
//Mostrar registro existente
function dat_form(id_emp){
    id_ent = $('#id_entr').val();
    fecha = $('#fechar').val();

    var url = `${base_url}/Equivale/show_row`;
    $.ajax({
        url: url,
        method: 'POST',
        dataSrc: '',
        data: {id_emp:id_emp, id_ent:id_ent, fecha:fecha},
        beforeSend: function(){
            $('.loader').show();
        },
        success: function(response){
            data = JSON.parse(response);
            //
            id_emp = data[0].id_emp;
            id_cli = data[0].id_ent;
            nom_cli = data[0].nom_ent;
            fecha = data[0].fecha;
            format = data[0].format;
            status = data[0].status;
            //
            listar_empresas(id_emp,true);
            $('#id_cli').val(id_cli);
            $('#nom_cli').val(nom_cli);
            $('#fecha').val(fecha);
            $('#format').val(format);
            listar_status(status);

            $.each(data, function(i,xitem){
                item++;
                var htmlTags = 
                '<tr class="text-xs" id="fila'+xitem.item+'">' + 
                '<td class="text-right text-xs">'+xitem.item+'</td>' +
                '<td class="text-xs"><input type="text" class="form-control text-xs" name="cod_prod_ent[]" id="cod_prod_ent'+item+'" value="'+xitem.cod_prod_ent+'"/></td>' + 
                '<td class="text-xs"><input type="hidden" name="id_prod[]" id="id_prod'+item+'" class="text-xs" value="'+xitem.id_prod+'"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_prod'+item+'" name="nom_prod[]" readonly value="'+xitem.nom_prod+'"><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>' +
                '<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></button></div></td>' +
                '</tr>';
                $("#tblDetalle").append(htmlTags);
                $("#item").val(item);
            });
        }, 
        complete: function(){
            $('.loader').hide();
        },
        error: function(){
            $('.loader').hide();
        }
    })
}
//Buscar valroes de la empresa
$('#id_emp').on('change', async function(){
    id_emp = $("#id_emp").val();
	id_tdo_cfg = await tip_doc_fac(id_emp);
    stock = id_tdo_cfg['stock'];
})
//Seleccioanr por defecto el vendedor al momento de escoger el cliente
$(document).on('change', '#id_cli', function(event) {
	event.preventDefault();
	id_ent = $(this).val();
	fetcingData(id_ent);
	
});
async function fetcingData(id_cli) {
	const datosFetched = await tid_vend(id_cli);
	nom_cli = datosFetched['nom_ent'];
	$("#nom_cli").val(nom_cli);
}
//Agregar productos
function agregarProductos(){
    item++;
    var htmlTags = 
    '<tr class="text-xs" id="fila'+item+'">' + 
    '<td class="text-right text-xs">'+item+'</td>' +
    '<td class="text-xs"><input type="text" class="form-control text-xs" name="cod_prod_ent[]" id="cod_prod_ent'+item+'"/></td>' + 
    '<td class="text-xs"><input type="hidden" name="id_prod[]" id="id_prod'+item+'" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_prod'+item+'" name="nom_prod[]" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>' +
    '<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></button></div></td>' +
    '</tr>';
    $("#tblDetalle").append(htmlTags); 
}
//funcion para elimnar una fila
$(document).on('click', '.borrar', function(event) {
    event.preventDefault();
    $(this).closest('tr').remove();
});
//Funcion para eliminar un registro Bancarios
function eliminarBtn(id){
    Swal.fire({
        title: "¿Está usted seguro de eliminar este registro?",
        text: "¡No podrá revertir esta eliminación!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, borrar este registro!",
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
            borrar(id);
        }
      });
}
//Funcion para borrar
async function borrar(id){
    const datos = new FormData();
    id_emp = id.dataset.id;
    id_ent = id.dataset.name;
    fecha = id.dataset.code;
    datos.append('id_emp', id_emp);
    datos.append('id_ent', id_ent);
    datos.append('fecha', fecha);
    try{
        const url =  `${base_url}/Equivale/delete_row`;
        const repuesta = await fetch(url, {
            method:"POST",
            body: datos,
        });
        const resulta = await repuesta.json();
        Swal.fire({
            icon: `${resulta.icon}`,
            title: `${resulta.title}`,
            text: `${resulta.msg}`,
        }).then((result) => { 
            if (result.isConfirmed){
                window.location.href = `${base_url}/Equivale`;
            };
        });
    }catch(error){
         Swal.fire({
            icon: 'error',
            title: 'Error.....',
            text: 'No se pudo eliminar el registro ya que se encuentra asociado'
        });
    }
}
$('body').on('click', '#tblDetalle tr', function() {
    selectFile = $(this).attr('id');
    item = (selectFile.substring(4));
    let selects = $('.mi-select');
    id_prod_img =  $(this).find('option:selected').val();
});