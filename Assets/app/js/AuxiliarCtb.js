//Validar campos del formulario
$(function () {
	$("form[name='my_form']").validate({
		rules: {
			cod_aux: "required",
			nombre_aux: "required",
			agrupa_aux: "required",
			status: "required",
		},
		messages: {
			cod_aux: "Debe especificar una Código",
			nombre_aux: "Debe especificar un Nombre ",
			agrupa_aux: "Debe especificar si Agrupa o no",
			status: "Debe especificar un Estado",
		},
	});
	//Cargar el index
	form = $("form").attr("id");
	if (form === undefined) {
		initAuxilirCTB();
	} else {
		//Cuando es un registro nuevo
		id = $("#id").val();
		if (id) {
			show_row(id);
		} else {
			dat_form_new();
		}
	}
});
//Nuevo Retgistro
function dat_form_new() {
	listar_status(1, "status_aux");
	listar_si_no(0, "agrupa_aux");
}
//Consultar Registro
function show_row(id) {
	var formData = $(this).serialize();
	const url = `${base_url}/AuxiliarCtb/show_row`;
	//Ajax para Consulta registro
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
			if (data) {
				$("#cod_aux").val(data.cod_aux);
				$("#cod_aux").prop("readonly", true);
				$("#nombre_aux").val(data.nombre_aux);
				listar_si_no(data.agrupa_aux, "agrupa_aux");
				listar_status(data.status_aux, "status_aux");
			}
		},
	});

}
//Validar si existe el código
$("#cod_aux").on("keyup", function (e) {
	let cod_aux = $(this).val();
	if (e.key == ".") {
		const url = `${base_url}/AuxiliarCTB/val_agrupador`;
		$.ajax({
			url: url,
			type: "POST",
			data: { codigo: cod_aux },
			dataType: "json",
			beforeSend: function () {
				loader.show();
			},
			complete: function () {
				loader.hide();
			},
			error: function (error) {
				console.log("Ha ocurrido el siguiente error: ", error.responseText);
			},
			success: function (data) {
				//cod_aux = cod_aux.slice(0, 1);
				msg = `El Auxiliar agrupador ${cod_aux} no existe, primero cree el concepto agrupador y luego puede crear los auxiliares hijos`;
				if (!data) {
					Swal.fire({
						title: "Error",
						icon: "error",
						text: msg,
					});
				}
			},
		});
	}
});

//Eliminar un registro
$("#tblIndexMain").on("click", ".btn-delete-index", function () {
	var Id = $(this).data("id"); // Obtiene el ID del registro
	var cod_aux = $(this).data("code");
	var name = $(this).data("name");
	var recordId = Id; // Asegúrate de que 'recordId' tenga el valor correcto
	const url = `${base_url}/AuxiliarCtb/delete_row`;
	// Mostrar el cuadro de diálogo de confirmación
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
			$.ajax({
				url: url, // URL de tu script de eliminación en el servidor
				method: "POST",
				data: { id: recordId, cod_aux: cod_aux, name: name }, // Envía el ID del registro a eliminar
				dataType: "json",
				beforeSend: function () {
					loader.show();
				},
				complete: function () {
					loader.hide();
				},
				success: function (resulta) {
					console.log(resulta);
					// La respuesta del servidor debe indicar si fue exitoso
					Swal.fire({
						icon: `${resulta.icon}`,
						title: `${resulta.title}`,
						text: `${resulta.msg}`,
					}).then((result) => {
						if (result.isConfirmed) {
							// Recarga el DataTable
							 var table = $('#tblIndexMain').DataTable(); 
    						table.ajax.reload(null, false);

							//tableIndex.draw(); // El 'false' previene que se reajuste la paginación a la página 1.
							//tableIndex.ajax.reload(null, false);
							//window.location.href = `${base_url}/BanMovim`;
						}
					});
				},
				error: function (xhr, status, error) {
					loader.hide();
					var errorMessage = xhr.status + ": " + xhr.statusText;
					console.log(errorMessage, status, error);
				},
			});
		}
	});
});
//Guardar el formulario
$("#my_form").on("submit", function (e) {
	e.preventDefault();
	if ($(this).valid()) {
		var formData = $(this).serialize();
		const url = `${base_url}/AuxiliarCtb/store`;
		$.ajax({
			type: "POST",
			url: url,
			dataSrc: "",
			data: formData,
			dataType: "json",
			beforeSend: function () {
				loader.show();
			},
			success: function (data) {
				Swal.fire({
					title: data.title,
					text: data.msg,
					icon: data.icon,
				}).then((result) => {
					if (data.success != 0) {
						window.location.href = `${base_url}/AuxiliarCtb`;
					}
				});
			},
			complete: function () {
				loader.hide();
			},
			error: function (PDOException) {
				loader.hide();
				var errorMessage = PDOException;
				console.log(errorMessage);
			},
		});
	} else {
		return false;
	}
});