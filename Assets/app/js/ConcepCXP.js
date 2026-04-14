/*!
 * Funciones ConcepCXP
 * Copyright 2025-2025
 * 08-11-2025 Creación de Archivo José Vargas 18:54:00
 */
// AL Iniciar la aplicación
$().ready(function(){
    //Validaciones
    $("form#my_form").validate({
		rules: {
			codigo_con: {
				required: true,
				minlength: 2,
				maxlength: 10,
			},
			nombre_con: {
				required: true,
				minlength: 5,
				maxlength: 100,
			},
			agrupa_con: "required",
			nom_ctb: "required",
			status: "required",
		},
	});
    //Cargar el Index
    form = $("form").attr("id");
    if(form === undefined){
        initConcepCXP();
    }else{
        id = $("#id").val();
        if(id){
            show_row(id);
        }else{
            dat_form_new();
        }
    }
})
$(".refresh-button").on("click", function () {
	tableIndex.ajax.reload(null, false);
});
function show_row(id){
	var formData = $(this).serialize();
	const url = `${base_url}/ConcepCXP/show_row`;
	//Ajax para Mostrar los registros
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
			if(data){
				$("#codigo_con").val(data.codigo_con);
				$("#codigo_con").css("pointer-events", "none");
				$("#nombre_con").val(data.nombre_con);
				agrupa_con = data.agrupa_con;
				listar_si_no(agrupa_con, "agrupa_con");
				$("#agrupa_con").css("pointer-events", "none");
				$("#agrupa_con").trigger("change");
				status = data.status;
				id_ctb = data.id_ctb;
				$("#id_ctb").val(id_ctb);
				$("#id_ctb").trigger("change");

				id_aux = data.id_aux;
				$("#id_aux").val(id_aux);
				$("#id_aux").trigger("change");
				id_retislr = data.id_retislr;
				listar_retislr(id_retislr, "id_retislr");
				listar_status(status);
			}
		},
	});
}
function dat_form_new(){
    listar_si_no("", "agrupa_con");
    listar_retislr("", "id_retislr");
    listar_status(1);
}
$("#agrupa_con").on("change", function(){
    agrupa_con = $(this).val();
    $(".agrupa_con").show();
    if(agrupa_con == "S"){
        $(".agrupa_con").hide();
    }
})
$("#codigo_con").on('keyup', function(e){
    codigo_con = $(this).val();
    if(e.key == "."){
        const url = `${base_url}/ConcepCXP/val_con`;
        //Ajax para 
        $.ajax({
            url: url,
            method: 'POST',
            dataSrc: '',
            data: {id: codigo_con},
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
                console.log("Success", data);
				codigo_con = codigo_con.slice(0, -1);
				msg = `El concepto agrupador ${codigo_con} no existe, primero cree el concepto agrupador y luego puede crear los conceptos hijos`
				if(!data){
					Swal.fire({
						title: " Error",
						text: msg,
						icon: "error",
					});
				}
            },
        });
    }
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
			const url = `${base_url}/ConcepCXP/destroy`;
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
		const url = `${base_url}/ConcepCXP/store`;
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
						window.location.href = `${base_url}/ConcepCXP`;
					}
				})
			},
		});
	}else{
		return false;
	}
})