//Al Inicial la aplicacion
$().ready(function(){
    //Validaciones
    $("form#my_form").validate({
		ignore: null, // Valida todos los campos
		rules: {
			id_emp: "required",
			cod_tmocxc: {
				required: true,
				minlength: 2,
				maxlength: 2,
			},
			des_tmocxc: {
				required: true,
				minlength: 5,
				maxlength: 100,
			},
			acc_tmocxc: "required",
			rec_tmocxc: "required",
			con_tmocxc: "required",
			next_tmocxc: {
				required: function () {
					return $("#con_tmocxc").val() === "S";
				},
			},
			id_ctb: "required",
			status: "required",
		},
		messages: {
			id_emp: "Debe especificar una Empresa",
			cod_tmocxc: {
				required: "Debe especificar un Código",
				minlength: "Debe especificar al menos 2 carácteres",
				maxlength: "Debe especificar máximo 2 carácteres",
			},
			des_tmocxc: {
				required: "Debe especificar una descripción",
				minlength: "Debe especificar al menos 5 carácteres",
				maxlength: "Debe especificar máximo 100 carácteres",
			},
			acc_tmocxc: "Debe especificar una Acción",
			rec_tmocxc: "Debe especificar si usa Relación de Caja",
			con_tmocxc: "Debe indicar si usa consecutivo",
			next_tmocxc: "Debe especificar el próximo número",
			id_ctb: "Debe especificar una Cuenta Contable",
			status: "Debe especificar un Status",
		},
	});
    //Cargar el Index
    form = $("form").attr("id");
    if(form === undefined){
        initTipoMovCXC();
    }else{
        id = $("#id").val();
        if(id){
            show_row(id);
        }else{
            dat_form_new();
        }
    }
});
function show_row(id){
	var formData = $(this).serialize();
	const url = `${base_url}/TipoMovCXC/show_row`
	//Ajax para 
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: {id:id},
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
			cod_tmocxc = data.cod_tmocxc;
			$("#cod_tmocxc").val(cod_tmocxc);
			des_tmocxc = data.des_tmocxc;
			$("#des_tmocxc").val(des_tmocxc);	
			acc_tmocxc = data.acc_tmocxc;
			listar_accion(acc_tmocxc, "acc_tmocxc");
			rec_tmocxc = data.rec_tmocxc;
			listar_si_no(rec_tmocxc, "rec_tmocxc");
			con_tmocxc = data.con_tmocxc;
			listar_si_no(con_tmocxc, "con_tmocxc");
			next_tmocxc = data.next_tmocxc;
			$("#next_tmocxc").val(next_tmocxc);
			id_ctb = data.id_ctbcue;
			$("#id_ctb").val(id_ctb);
			$("#id_ctb").trigger("change");
			nom_ctb = data.nom_ctb;
			id_aux = data.id_aux;
			nom_aux = data.nom_aux;
			status = data.status;
			listar_status(status);
		},
	});
}
//Cuando es un formulario nuevo
function dat_form_new(){
	listar_empresas();
	listar_accion("", "acc_tmocxc");
	listar_si_no("", "rec_tmocxc");
	listar_si_no("", "con_tmocxc");
	listar_status("1");
}
//Refrescar el datatable del index
$(".refresh-button").on("click", function () {
	tableIndex.ajax.reload(null, false);
});
//Eliminar un registro
$("#tblIndexMain").on("click", ".btn-delete-index", function () {
	var recordId = $(this).data("id"); // Obtiene el ID del registro
	var recordCode = $(this).data("code"); // Obtine el Tipo Doc
	var recordName = $(this).data("name"); // Obtine el nombre
	var descrip = `¿Está seguro de eliminar el Tipo de Movimiento ${recordCode} ${recordName}?.`
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
			const url = `${base_url}/TipoMovCXC/destroy`;
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
		const url = `${base_url}/TipoMovCXC/store`;
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
						window.location.href = `${base_url}/TipoMovCXC`;
					}
				})
			},
		});
	}else{
		return false;
	}
})