/*
* Funciones CreditDays
* Copyright 2025-2025
* 24-11-2025 Creación de Archivo José Vargas 10:32:00
*/
//Al Iniciar la aplicación
$().ready(function(){
    //Validaciones
    $("form#my_form").validate({
		rules: {
			cod_diascre: "required",
			des_diascre: {
				required: true,
				minlength: 5,
				maxlength: 100,
			},
			status: "required",
		},
		messagges: {
			cod_diascre: "Debe especificar un valor",
			des_diascre: {
                required: "Debe espicificar una cantidad de días",
                minlength: "Debe especificar la menos {0} carácteres",
                maxlength: "Debe especificar máximo {0} caráctres"
            },
			status: "Debe espeficicar un status",
		},
	});
    //Cargar el Index
    form = $("form").attr("id");
    if(form === undefined){
        initCreditDays();
    }else{
        id = $("#id").val();
        if(id){
            show_row(id);
        }else{
            dat_form_new();
        }
    }
})
//Nuevo registro
function dat_form_new(){
    listar_status(1);
}
//Consultar registro
function show_row(id){
    var formData = $(this).serialize();
    const url = `${base_url}/CreditDays/show_row`
    //Ajax para 
    $.ajax({
        url: url,
        method: 'POST',
        dataSrc: '',
        data: {id: id},
        dataType: 'json',
        beforeSend: function() {
            loader.show();
        },
        complete: function() {
            loader.hide();
        },
        error: function(PDOException) {
            loader.hide();
            console.log('Ha ocurrido el siguiente error:', PDOException.responseText)
        },
        success: function(data) {
            $("#cod_diascre").val(data[0].cod_diascre);
            $("#des_diascre").val(data[0].des_diascre);
            status = data[0].status;
            listar_status(status);
        },
    });
}
//Función para recargar el datatable
$(".refresh-button").on("click", function () {
    tableIndex.ajax.reload(null, false);
});
//Eliminar un registro
$("#tblIndexMain").on("click", ".btn-delete-index", function () {
	var recordId = $(this).data("id"); // Obtiene el ID del registro
	var recordCode = $(this).data("code"); // Obtine el Tipo Doc
	var recordName = $(this).data("name"); // Obtine el nombre
	var descrip = `¿Está seguro de eliminar el Tipo de Documento ${recordCode} ${recordName}?.`
	Swal.fire({
		title: descrip,
		text: "¡No podrá revertir esta eliminación!",
		icon: "question",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Si, borrar este registro!",
		cancelButtonText: "Cancelar",
	}).then((result) => {
		if (result.isConfirmed) {
			const url = `${base_url}/CreditDays/destroy`;
			$.ajax({
				url: url, // URL de tu script de eliminación en el servidor
				type: "POST",
				data: { id: recordId, recordCode: recordCode, recordName: recordName },
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
							//tableIndex.draw(false); // El 'false' previene que se reajuste la paginación a la página 1.
							tableIndex.ajax.reload(null, false);
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
//Guardar y/o Actualizarregistro
$("#my_form").on("submit", function(e){
	e.preventDefault();
	if($(this).valid()){
		var formData = $(this).serialize();
		const url = `${base_url}/CreditDays/store`;
		//Ajax para Guardar y/o Actualizar
		$.ajax({
			url: url,
			method: 'POST',
			dataSrc: '',
			data: formData,
			dataType: 'json',
			beforeSend: function() {
				loader.show();
			},
			complete: function() {
				loader.hide();
			},
			error: function(PDOException) {
				loader.hide();
				console.log('Ha ocurrido el siguiente error:', PDOException.responseText)
			},
			success: function(data) {
				Swal.fire({
					title: data.title,
					text: data.msg,
					icon: data.icon,					
				}).then((result) =>{
					if(data.icon != "error"){
						window.location.href = `${base_url}/CreditDays`;
					}
				})
			},
		});
	}else{
		return false;
	}
})