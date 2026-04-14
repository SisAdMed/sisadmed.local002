//Validar campos del formulario
$(function () {
	$("form[name='my_form']").validate({
		rules: {
			grupo_codigo: {
				required: true,
				minlength: 3,
				maxlength: 3,
			},
			grupo_nombre: {
				required: true,
				minlength: 5,
				maxlength: 100,
			},
			status: "required",
		},
		messages: {
			grupo_codigo: {
				required: "Debe especificar un Código",
				minlength: "Debe contener al menos 3 carácteres",
				maxlength: "Debe contener máximo 3 carácteres",
			},
			grupo_nombre: {
				required: "Debe especificar una Descripción",
				minlength: "Debe contener al menos 5 carácteres",
				maxlength: "Debe contener máximo 100 carácteres",
			},
			status: "Debe especificar un status",
		},
	});
});
//AL ingresar al formulario
$(document).ready(function(e){
	id = $('#id').val();
	if(id){
		show_row(id);
	}else{
		next_codigo = next_codigo();
		listar_status(1);
	}
})
//Mostrar datos de un registro en especifico
function show_row(id){
	div_loading();
	const url = `${base_url}/Grupos/show_row`;
	$.ajax({
		type: "POST",
		url: url,
		dataSrc: "",
		data: {'id': id},
		dataType: "json",
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			codigo = data.grupo_codigo;
			grupo_codigo = leftPadWithZeros(codigo, 3);
			$("#grupo_codigo").val(grupo_codigo);
			$('#grupo_nombre').val(data.grupo_nombre);
			listar_status(data.status);
		},
		error: function (xhr) {
			console.log(xhr.statusText + ' ' + xhr.responseTExt);
			$(".loader").hide();
		},
		complete: function () {
			$(".loader").hide();
		},
	});
}
//Próximo Código de Grupo, cuando es un registro nuevo
function next_codigo(){
	div_loading();
	const url = `${base_url}/Grupos/next_codigo`;
	$.ajax({
		type: 'POST',
		url: url,
		dataSrc: '',
		data: '',
		dataType: 'json',
		beforeSend: function(){
			$('.loader').show();
		},
		success: function(data){
			codigo = data.grupo_codigo;
			grupo_codigo = leftPadWithZeros(codigo, 3);
			$('#grupo_codigo').val(grupo_codigo);
		}, 
		error: function(xhr){
			$('.loader').hide();
			console.log(xhr.statusText + xhr.responseTExt);
		},
		complete: function(){
			$('.loader').hide();
		}
	})
}
//Guardar y/o Actualizar
$('#my_form').on('submit', function(e){
	e.preventDefault();
	div_loading();
	var formData = $(this).serialize();
	const url = `${base_url}/Grupos/store`;
	$.ajax({
		type: 'POST',
		url: url,
		dataSrc: '',
		data: formData,
		dataType: 'json',
		beforeSend: function(){
			$('.loader').show();
		},
		success: function(data){
			Swal.fire({
				title: data.title,
				text:  data.msg,
				icon: data.icon,
			}).then((result) => {
				if(data.icon != 'error'){
					window.location.href = `${base_url}/Grupos`;
				}
			});
		},
		complete: function(data){
			$(".loader").hide();
		},
		error: function(xhr){
			$(".loader").hide();
			console.log(xhr.statusText + ' ' + xhr.responseTExt);
		}
	})
});
//Eliminar Registro
function eliminarBtn(id){
	id = id.dataset.id;
	Swal.fire({
		title: "¿Está usted seguro de eliminar este registro?",
		text: "¡No podrá revertir esta eliminación!",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Si, borrar este registro!",
		cancelButtonText: "Cancelar",
	}).then((result) => {
		if (result.isConfirmed) {
			borrar(id);
		}
	});
}
//Funcion para borrar
async function borrar(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url =  `${base_url}/Grupos/delete_row`;
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
                window.location.href = `${base_url}/Grupos`;
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