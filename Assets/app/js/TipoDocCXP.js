	/*!
	* Funciones TipoDocCXP
	* Copyright 2025-2025
	* 07-11-2025 Creación de Archivo José Vargas 09:18:00
	*/
	// AL Iniciar la aplicación
$(document).ready(function () {
	//Validaciones
	$("form#my_form").validate({
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
				maxlength: 100,
			},
			tipo_tdoc: "required",
			num_tdoc: {
				required: function () {
					return $("#con_tdoc").is(":checked");
				},
			},
			nom_ctb: "required",
			status: "required"
		},
		messages:{
			id_emp: "Debe especificar una Empresa",
			tipo_codigo:{
				required: "Debe Especificar un Código",
				minlength: "Debe especificar al menos 2 carácteres",
				maxlength: "Debe especificar máximo 2 carácteres",
			},
			nom_tdoc:{
				required: "Debe especificar un nombre",
				minlength: "Debe especificar al menos 5 carácteres",
				maxlength: "Debe especificar máximo 100 caracteres"
			},
			tipo_tdoc: "Debe especificar un origen del Tipo de Documento",
			num_tdoc: "Debe especificar el próximo número de Tipo de Documento",
			nom_ctb: "Debe especificar una Cuenta Contable",
			status: "Debe especificar un Status",
		}
		
	});
	//Cargar el index
	form = $("form").attr("id");
	if (form === undefined) {
		initTipoDocCXP();
	}else{
		id = $("#id").val();
		if(id){
			show_row(id)
		}else{
			dat_form_new()
		}
	}
});
$(".refresh-button").on('click', function(){
		tableIndex.ajax.reload(null, false);
});
function show_row(id){
	const url = `${base_url}/TipoDocCXP/show_row`
	//Ajax para Consultar Registro
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
			id_emp = data.id_emp;
			listar_empresas(id_emp, true);
			$("#tipo_codigo").val(data.tipo_codigo);
			$("#tipo_codigo").css("pointer-events", "none");
			$("#nom_tdoc").val(data.nom_tdoc);
			tipo_tdoc = data.tipo_tdoc;
			listar_ori_tipo_doc_cxp(tipo_tdoc);
			con_tdoc = data.con_tdoc;
			if(con_tdoc == 1){
				$("#con_tdoc").prop("checked", true);
			}
			sol_aprob = data.sol_aprob;
			if (sol_aprob == 1) {
				$("#sol_aprob").prop("checked", true);
			}
			$("#num_tdoc").val(data.num_tdoc);
			id_ctb = data.id_ctb; 
			$("#id_ctb").val(id_ctb);
			$("#id_ctb").trigger('change');
			id_aux = data.id_aux;
			$("#id_aux").val(id_aux);
			$("#id_aux").trigger("change");
			id_tmoinv = data.id_tmoinv;
			listar_InvTipoMov(id_emp, id_tmoinv);
			status = data.status;
			listar_status(status);
		},
	});
}
function dat_form_new(){
	listar_empresas();
	listar_ori_tipo_doc_cxp("");
	listar_status(1);
}
$("#id_emp").on('change', function(){
	id_emp = $(this).val()
	listar_InvTipoMov(id_emp)
})
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
			const url = `${base_url}/TipoDocCXP/destroy`;
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
		const url = `${base_url}/TipoDocCXP/store`
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
						window.location.href = `${base_url}/TipoDocCXP`;
					}
				})
			},
		});
	}else{
		return false;
	}
})