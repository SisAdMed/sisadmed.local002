/*
 * Funciones ConcepCXC
 * Copyright 2025-2025
 * 01-12-2025 Creación de Archivo José Vargas 11:15:00
 */
// AL Iniciar la aplicación
$().ready(function () {
    //Validaciones
    $("form#my_form").validate({
      ignore: null,
      rules: {
        cod_cta: "required",
        nombre_cta: "required",
        aux_cta: "required",
        tip_cta: "required",
        agrupa_cta: "required",
        status: "required",
      },
      messages: {
        cod_cta: "Debe especificar una cuenta",
        nombre_cta: "Debe especificar un nombre",
        aux_cta: "Debe especificar si usa o no auxilair",
        tip_cta: "Debe especificar un tipo de cuenta",
        agrupa_cta: "Debe especificar si es agrupador o no",
        status: "Debe especificar un status",
      },
    });
    //Cargar el Index
    form = $("form").attr("id");
    if (form === undefined) {
        initCuentasCtb();
    } else {
        id = $("#id").val();
        if (id) {
            show_row(id);
        } else {
            dat_form_new();
        }
    }
})
//Nuevo Registro
function dat_form_new() {
    listar_si_no("", "agrupa_cta");
    listar_si_no("", "aux_cta");
    listar_seltipoCuenta("");
    listar_status(1);
}
//Consuotar registro
function show_row(id) {
    const url = `${base_url}/CuentasCtb/show_row`
    //Ajax para Consulta de Cuentas Contables
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
            $("#cod_cta").val(data.cod_cta);
            $("#cod_cta").prop("readonly", true);
            $("#nombre_cta").val(data.nombre_cta);
            //
			listar_seltipoCuenta(data.tip_cta);
			listar_si_no(data.aux_cta, "aux_cta");
			listar_si_no(data.agrupa_cta, "agrupa_cta");
			listar_status(data.status);
			//
			$("#agrupa_cta").css("pointer-events", "none");
			$("#aux_cta").css("pointer-events", "none");
			$("#tip_cta").css("pointer-events", "none");
        },
    });
}
//Validar que existe Concpeto Padre en caso de ser hijo
$("#cod_cta ").on("keyup", function (e) {
	cod_cta = $(this).val();
	if (e.key == ".") {
		const url = `${base_url}/CuentasCtb/val_con`;
		//Ajax para
		$.ajax({
			url: url,
			method: "POST",
			dataSrc: "",
			data: { id: cod_cta  },
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
				cod_cta = cod_cta.slice(0, -1);
				msg = `La Cuenta Contable agrupadora ${cod_cta} no existe, primero cree la Cuenta agrupadora y luego puede crear las Cuentas Contables hijos`;
				if (!data) {
					Swal.fire({
						title: " Error",
						text: msg,
						icon: "error",
					});
				}
			},
		});
	}
	//Buscar Tipo de Cuente y Rellenar Autoamticamente		
		url = `${base_url}/CuentasCtb/validar_tipo_cta`
		//Ajax para 
		$.ajax({
			url: url,
			method: 'POST',
			dataSrc: '',
			data: {cod_cta: cod_cta},
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
				console.log(data);
				 listar_seltipoCuenta(data.message);
         		//$("#tip_cta").css("pointer-events", "none");
			},
		});
});
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
			const url = `${base_url}/CuentasCtb/destroy`;
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
		const url = `${base_url}/CuentasCtb/store`;
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
						window.location.href = `${base_url}/CuentasCtb`;
					}
				})
			},
		});
	}else{
		return false;
	}
})