//Variables 
let table;
item = 0;
//Al iniciar la Aplicacion
$(document).ready(function () {
	//Validar datos del formulario
	// just for the demos, avoids form submit
	jQuery.validator.setDefaults({
  		debug: false,
  		success: "valid"
	});
	$("#my_form").validate({
		rules: {
			id_emp: "required",
			tipo_codigo: {
				required: true,
				minlength: 2,
				maxlength: 2,
			},
			nom_tdoc: {
				required: true,
				minlength: 5,
			},
			tipo_tdoc: "required",
			num_tdoc: {
				required: "#con_tdoc:checked",
			},
			nom_ctb: "required",
			status: "required",
		},
		messages: {
			id_emp: "Debe especificar una Empresa",
			tipo_codigo: {
				required: "Debe especificar un Código de Tipo de Documento",
				minlength: "Debe contener al menos dos carácteres",
				maxlength: "Debe contener máximo dos carácteres",
			},
			nom_tdoc: {
				required:
					"Debe especificar un Nombre para el Tipo de Documento",
				minlength: "Debe contener al menos cinco carácteres",
			},
			tipo_tdoc: "Debe especificar un Tipo de Documento",
			num_tdoc: "Debe especificar el Próximo Número",
			nom_ctb: "Debe especificar una Cuenta Contable",
			status: "Debe especiicar un staus",
		},
	});
	//Cargar el index
	form = $("form").attr("id");
	if (form === undefined) {
		initTipoDocCXCTable();
	} else {
		//Cuando es un registro nuevo
		id = $("#id").val();
		if (id) {
			dat_form(id);
		} else {
			dat_form_new();
		}
	}
});
//Mostrar datos del regitros
function dat_form(id) {
	const url = `${base_url}/TipoDocCXC/show_row`
	$.ajax({
		type: 'POST',
		url: url,
		dataSrc: '',
		data:{id: id},
		dataType: 'json',
		beforeSend: function(){
			loader.show();
		}, 
		complete: function(){
			loader.hide();
		},
		error: function(xhr){
			loader.hide();
			console.log(xhr.statustext + ' ' + xhr.responseText);
		},
		success: function(data){
			id_emp = data.id_emp;
			listar_empresas(id_emp, true, '', 'id_emp');
			$("#tipo_codigo").val(data.tipo_codigo);
			$("#tipo_codigo").css("pointer-events", "none");
			$("#nom_tdoc").val(data.nom_tdoc);
			tipo_tdoc = data.tipo_tdoc;
			selTipDocCxC(tipo_tdoc);
			$("#tipo_tdoc").css("pointer-events", "none");
			con_tdoc = data.con_tdoc;
			if(con_tdoc == 1){
				$("#con_tdoc").prop('checked', true);
			}
			sol_aprob = data.sol_aprob;
			if(sol_aprob == 1){
				$("#sol_aprob").prop('checked', true);
			}
			$("#num_tdoc").val(data.num_tdoc);
			id_tmoinv = data.id_tmoinv;
			listar_InvTipoMov(id_emp, id_tmoinv, "id_tmoinv");
			id_ctb = data.id_cta;
			if (id_ctb) {
				$("#id_ctb").val(id_ctb);
				$('#id_ctb').trigger('change');
			}
			id_aux = data.id_aux;
			if(id_aux && id_aux != 0){
				$("#id_aux").val(id_aux);
				$('#id_aux').trigger('change');
			}
			status = data.status
			listar_status(status);
		}
	})
}
//Nuevo registro
function dat_form_new() {
	listar_empresas(0);
	selTipDocCxC("");
	listar_status(1);
}
$("#id_emp").on("change", function () {
	id_emp = $(this).val();
	if (id_emp) {
		listar_InvTipoMov(id_emp, 0, "id_tmoinv");
	}
});
$("#tipo_codigo").on("input", function () {
	// Obtiene el valor actual y lo convierte a mayúsculas
	var valorMayusculas = this.value.toUpperCase();
	// Actualiza el valor del input con la versión en mayúsculas
	this.value = valorMayusculas;
});
$("#nom_tdoc").on("input", function () {
	// Obtiene el valor actual y lo convierte a mayúsculas
	var valorMayusculas = this.value.toUpperCase();
	// Actualiza el valor del input con la versión en mayúsculas
	this.value = valorMayusculas;
});
//Refresacar Index
$(".refresh-button").on("click", function () {
	tableIndex.ajax.reload(null, false);
});
//Guardar y/o Actualizar
$("#my_form").on("submit", function (e) {
	e.preventDefault();
	if($(this).valid()){
		var formData = $(this).serialize();
		const url = `${base_url}/TipoDocCXC/store`;
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
				Swal.fire({
					title: data.title,
					text: data.msg,
					icon: data.icon,
				}).then((result) => {
					if (data.icon != "error") {
						window.location.href = `${base_url}/TipoDocCXC`;
					}
				});
			},
			complete: function () {
				$(".loader").hide();
			},
			error: function (PDOException) {
				$(".loader").hide();
				var errorMessage = PDOException;
				console.log(errorMessage);
			},
		});
	}else{
		return false;
	}
});
//Eliminar un registro de la Tabla Principal
$("#tblIndexMain").on("click", ".btn-delete", function () {
	var recordId = $(this).data("id"); // Obtiene el ID del registro
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
			const url = `${base_url}/TipoDocCXC/delete_row`;
			$.ajax({
				url: url, // URL de tu script de eliminación en el servidor
				type: "POST",
				data: { id: recordId },
				dataType: "json",
				success: function (resulta) {
					// La respuesta del servidor debe indicar si fue exitoso
					Swal.fire({
						icon: `${resulta.icon}`,
						title: `${resulta.title}`,
						text: `${resulta.msg}`,
					}).then((result) => {
						if (result.isConfirmed) {
							// Recarga el DataTable
							tableIndex = $(tblIndexMain).DataTable();
							tableIndex.draw(); // El 'false' previene que se reajuste la paginación a la página 1.
							tableIndex.ajax.reload(null, true);
						}
					});
				},
				error: function () {
					Swal.fire({
						icon: `error`,
						title: `Se generó un error al eliminar el registro.`,
						text: `No se ha podido elimianr el registro, verifique que le mismo no tenga documentos existentes`,
					});
				},
			});
		}
	});
});
