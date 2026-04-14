/*!
 * Funciones Zonas
 * Copyright 2025-2025
 * 08-11-2025 Creación de Archivo José Vargas 15:29:00
 */
// Al iniciar la aplicación
$().ready(function(){
    //Validaciones
    $("form#my_form").validate({
		rules: {
			cod_zona: {
				required: true,
				minlength: 3,
				maxlength: 3,
			},
			nombre_zona:{
                required: true,
                minlength: 5,
                maxlength: 100
            },
            status: "required"
		},
        messages: {
            cod_zona:{
                required: "Debe especificar un Código de Zona",
                minlength: "Debe especificar al menos 3 carácteres",
                maxlength: "Debe especificar máximo 3 carácteres"
            },
            nombre_zona:{
                required: "Debe especificar un Nombre de Zona",
                minlength: "Debe especificar al menos 5 carácteres",
                maxlength: "Debe especificar máximo 100 carácteres"
            }
        }
	});
    //Cargar el index
    form = $("form").attr("id");
    if(form === undefined){
        initZonas();
    }else{
        id = $("#id").val();
        if(id){
            show_row(id);
        }else{
            dat_form_new();
        }
    }
});
$(".refresh-button").on("click", function () {
	tableIndex.ajax.reload(null, false);
});
function dat_form_new() {
    $("#cod_zona").prop('readonly', true);
    //Buscar próximo código
    const url = `${base_url}/Zonas/next_zone`;
    //Ajax para 
    $.ajax({
        url: url,
        method: 'POST',
        dataSrc: '',
        data: {},
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
            cod_zona = parseInt(data.cod_zona) +1;
            $("#cod_zona").val(cod_zona.toString().padStart(3, "0"));
        },
    });
    $("#nombre_zona").focus();
	listar_status(1);
}
function show_row(id) {
	const url = `${base_url}/Zonas/show_row`;
	//Ajax para Mostrar los registros
	$.ajax({
		url: url,
		method: "POST",
		dataSrc: "",
		data: { id: id },
		dataType: "json",
		beforeSend: function () {
			loader.show();
		},
		complete: function () {
			loader.hide();
		},
		error: function (PDOException) {
			loader.hide();
			console.log(
				"Ha ocurrido el siguiente error:",
				PDOException.responseText
			);
		},
		success: function (data) {
            $("#cod_zona").val(data.cod_zona);
            $("#cod_zona").css("pointer-events", "none");
            $("#nombre_zona").val(data.nombre_zona);
            status = data.status;
			listar_status(status);
		},
	});
}
//Eliminar un registro
$("#tblIndexMain").on("click", ".btn-delete-index", function () {
	var recordId = $(this).data("id"); // Obtiene el ID del registro
	var recordCode = $(this).data("code"); // Obtine el Tipo Doc
	var recordName = $(this).data("name"); // Obtine el nombre
	var descrip = `¿Está seguro de eliminar la Zona ${recordCode} ${recordName}?.`;
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
			const url = `${base_url}/Zonas/destroy`;
			$.ajax({
				url: url, // URL de tu script de eliminación en el servidor
				type: "POST",
				data: {
					id: recordId,
					recordCode: recordCode,
					recordName: recordName,
				},
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
$("#my_form").on("submit", function (e) {
	e.preventDefault();
	if ($(this).valid()) {
		var formData = $(this).serialize();
		const url = `${base_url}/Zonas/store`;
		//Ajax para Guardar y/o Actualizar
		$.ajax({
			url: url,
			method: "POST",
			dataSrc: "",
			data: formData,
			dataType: "json",
			beforeSend: function () {
				loader.show();
			},
			complete: function () {
				loader.hide();
			},
			error: function (PDOException) {
				loader.hide();
				console.log(
					"Ha ocurrido el siguiente error:",
					PDOException.responseText
				);
			},
			success: function (data) {
				Swal.fire({
					title: data.title,
					text: data.msg,
					icon: data.icon,
				}).then((result) => {
					if (data.icon != "error") {
						window.location.href = `${base_url}/Zonas`;
					}
				});
			},
		});
	} else {
		return false;
	}
});