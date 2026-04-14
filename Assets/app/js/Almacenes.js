//Validar campos
$(document).ready(function(){
	jQuery.validator.setDefaults({
		debug: true,
		success: "valid",
	});
	$("#my_form").validate({
		rules: {
			id_emp: "required",
			cod_alm: "required",
			nom_alm: {
				required: true,
				minlength: 5,
			},
			status: "required",
		},
		messages: {
			id_emp: "Debe especificar una Empresa",
			cod_alm: "Debe especificar un Código de Almacén",
			nom_alm: {
				required: "Debe especificar un Nombre para el Almacén",
				minlength:
					"El Nombre del Almacén, debe poseer al menos 5 carácteres",
			},
			status: "Debe especificar un Status",
		},
	});
	//Cargar el Index
	form = $("form").attr("id");
	if(form === undefined){
		initAlmacenes()
	}else{
		//Cuando es un registro nuevo
		id = $("#id").val();
		if(id){
			show_row(id);
		}else{
			dat_form_new();
		}
	}
});
//Consultar registro
function show_row(id){
	const url = `${base_url}/Almacen/show_row`;
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: {id: id},
		dataType: 'json',
		beforeSend: function(){
			loader.show();
		}, 
		complete: function(){
			loader.hide();
		},
		error: function(jqXHR, textStatus, errorThrown){
			loader.hide();
			console.log('Ha ocurrido el siguiente error: ', textStatus);
		},
		success: function(data){
			$.each(data, function( key, value){
				window[key] = value;
			});
			//Asignar variables a Formulario
			listar_empresas(id_emp, true, '', 'id_emp');
			$('#cod_alm').val(cod_alm);
			$("#cod_alm").css("pointer-events", "none");
			$('#nom_alm').val(nom_alm);
			$('#con_alm').val(con_alm);
			$('#email_alm').val(email_alm);
			$('#tel_alm').val(tel_alm);
			$("#dir_alm").val(dir_alm);
			listar_status(status);
			if(id_cli){
				$("#id_cli").val(id_cli);
				$("#id_cli").trigger("change");
			}
		}

	})

}
//Nuevo registro
function dat_form_new(){
	listar_empresas();
	listar_status(1);
}
//Al seleccioanr empresa
$('#id_emp').on('change', function(e){
	e.preventDefault();
	id_emp = $(this).val();
	format_almacen();
})
//Cliente
$('#id_cli').on('change', async function(){
	id_cli = $(this).val()
	const datosFetched = await tid_vend(id_cli);
	nom_cli = datosFetched["nom_ent"];
	$('#nom_cli').val(nom_cli);
})
//Guardar y/o Actualizar
$("#my_form").on("submit", function (e) {
	e.preventDefault();
	if($(this).valid()){
		var formData = $(this).serialize();
		const url = `${base_url}/Almacen/store`;
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
				console.log('Ha ocurrido el siguiente error: ', PDOException.ResponseText);
			},
			success: function(data){
				Swal.fire({
					title: data.title,
					text: data.msg,
					icon: data.icon,
				}).then((result) => {
					if(data.ico != "error"){
						window.location.href = `${base_url}/Almacen`;
					}
				})
			}
		});
	}else{
		return false;
	}
})
//Eliminar un registro
$("#tblIndexMain").on("click", ".btn-delete", function () {
	var recordId = $(this).data("id"); // Obtiene el ID del registro
	var recordCod = $(this).data("code");
	var recordNam = $(this).data("name");
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
			const url = `${base_url}/Almacen/destroy`;
			$.ajax({
				url: url,
				method: "POST",
				data: { id: recordId, code: recordCod, name: recordNam },
				dataType: "json",
				beforeSend: function () {
					loader.show();
				},
				complete: function () {
					loader.hide();
				},
				success: function (resulta) {
					// La respuesta del servidor debe indicar si fue exitoso
					Swal.fire({
						icon: `${resulta.icon}`,
						title: `${resulta.title}`,
						text: `${resulta.msg}`,
					}).then((result) => {
						if (result.isConfirmed) {
							// Recarga el DataTable
							tableIndex.draw(); // El 'false' previene que se reajuste la paginación a la página 1.
							tableIndex.ajax.reload(null, true);
						}
					});
				},
				error: function (xhr, status, error) {
					loader.hide();
					alert("Hubo un error en la solicitud.");
					console.error(xhr.responseText);
				},
			});
		}
	});
});