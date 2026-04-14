//Variables
//Validar campos del formulario
$(function(){
    $("form[name='my_form']").validate({
		rules: {
			id_grupo: "required",
			sub_grupo_nombre: {
				required: true,
				minlength: 5,
				maxlength: 100,
			},
			status: "required",
		},
		messages: {
			id_grupo: "Debve especificar un Grupo",
			sub_grupo_nombre: {
				required: "Debe especificar un Nombre de Sub Grupo",
				minlength: "Debe contener al menos 5 carácteres",
				maxlength: "Debe contener máximo 100 carácteres",
			},
            status: "Debe especificar un status"
		},
	});
});
//Al ingresar al formulario
$(document).ready(function(e){
    id = $('#id').val();
    if(id){
        show_row(id);
    }else{
        listar_grupos();
        listar_status(1);
    }
})
function show_row(id){
    div_loading();
    const url = `${base_url}/SubGrupos/show_row`;
    $.ajax({
        type: 'POST',
        url: url,
        dataSrc: '',
        data: {id: id},
        dataType: 'json',
        beforeSend: function(){
            $('.loader').show();
        }, 
        success: function(data){
            id_grupo = data.id_grupo
            listar_grupos(id_grupo);
            nombre_grupo = data.grupo_nombre;
            $('#nombre_grupo').val(nombre_grupo);
            sub_grupo_nombre = data.sub_grupo_nombre;
            $('#sub_grupo_nombre').val(sub_grupo_nombre);
            status = data.status;
            listar_status(status);
        },
        complete: function(){
            $('.loader').hide();
        },
        error: function(xhr){
            $('.loader').hide();
            console.log(xhr.statusText + ' ' + xhr.responseTExt);
        }
    });
}
//Al Seleccionar el Grupo
$('#id_grupo').on('change', function(){
    nombre_grupo = $("#id_grupo option:selected").text();
    $('#nombre_grupo').val(nombre_grupo);
})
//Guardar y/o Actualizar
$('#my_form').on('submit', function(e){
    e.preventDefault();
    div_loading();
    var formData = $(this).serialize();
    const url = `${base_url}/SubGrupos/store`;
    $.ajax({
		type: "POST",
		url: url,
		dataSrc: "",
		data: formData,
		dataType: "json",
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			console.log(data);
			Swal.fire({
				title: data.title,
				text: data.msg,
				icon: data.icon,
			}).then((result) => {
				if (data.icon != "error") {
					window.location.href = `${base_url}/SubGrupos`;
				}
			});
		},
		complete: function () {
			$(".loader").hide();
		},
		error: function (PDOException) {
			$(".loader").hide();
            var errorMessage = PDOException; ;
			console.log(errorMessage);
		},
	});
})
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
        const url =  `${base_url}/SubGrupos/delete_row`;
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
                window.location.href = `${base_url}/SubGrupos`;
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