/*!
 * Funciones Empresas
 * Copyright 2025-2025
 * 24-11-2025 Creación de Archivo José Vargas 20:58:00
 */
targetInputId = '';
// AL Iniciar la aplicación
$().ready(function(){
    //Validaciones
    $("form#my_form").validate({
		rules: {
			cod_emp: "required",
			nombre_emp: {
				required: true,
				minlength: 10,
				maxlength: 100,
			},
			rif_empresa: "required",
			tel_emp: "required",
			email_emp: "required",
            dir_emp: {
                required: true,
                minlength: 50,
            },
            id_iva: "required",
            id_pais: "required",
            id_moneda: "required",
			fec_ini_fis: "required",
			fec_fin_fis: "required",
			fec_ctb: "required",
			fec_ban: "required",
			fec_cxc: "required",
			fec_cxp: "required",
			fec_nom: "required",
			id_iva: "required",
			especial_contrib: "required",
			status: "required",
		},
		messages: {
			cod_emp: "Debe esecificar un valor",
			nombre_emp: {
				required: "Debe especificar un Nombre de Empresa",
				minlength: "Debe especificar al menos {0} carácteres",
				maxlength: "Debe especificar máximo {0} caráteres",
			},
			rif_empresa: "Debe especificar un Rif",
			tel_emp: "Debe especificar un numero de teléfono",
			email_emp: "Debe especificar un correo electrónico",
            dir_emp: {
                required: "Debe especificar una Dirección",
                minlength: "Debe conteneor al mens {0} carácteres"
            },
            id_iva: "Debe especificar la Zona Fiscal",
            id_pais: "Debe especificar un País",
            id_moneda: "Debe especificar una Moneda",
			fec_ini_fis: "Debe especificar una Fecha de Inicio de Año Fiscal",
			fec_fin_fis: "Debe especificar una Fecha de Cierre de Año Fiscal",
			fec_ctb: "Debe indicar la úlitma fecha de cierre de Contabilidad",
			fec_ban: "Debe indicar la úlitma fecha de cierre de Bancos",
			fec_cxc: "Debe indicar la úlitma fecha de cierre de Cuentas por Cobrar",
			fec_cxp: "Debe indicar la úlitma fecha de cierre de Cuentas por Pagar",
			fec_nom: "Debe indicar la última fecha de cierre de Nóminas",
			id_iva: "Debe especificar una Zona Fiscal",
			especial_contrib: "Debe especificar si es un Contribuyente Especial",
            status: "Debe especificr un Status"
		},
	});
    //Cargar el Index
    form = $("form").attr("id");
    if(form === undefined){
        initEmpresas();
    }else{
        id = $("#id").val();
        if(id){
            show_row(id);
        }else{
            dat_form_new();
        }
    }
})
//Mostrar Registros
function show_row(id){
    const url = `${base_url}/Empresas/show_row`;
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
			//Empresas
            $("#cod_emp").val(data.cod_emp);
            $("#cod_emp").css("pointer-events", "none");
            $("#nombre_emp").val(data.nombre_emp);
            $("#rif_empresa").val(data.rif_empresa);
            $("#rif_empresa").css("pointer-events", "none");
            $("#tel_emp").val(data.tel_emp);
            $("#email_emp").val(data.email_emp);
            status = data.status;
            listar_status(status);
            $("#dir_emp").val(data.dir_emp);
            id_iva = data.id_iva;
            listar_retiva(id_iva, "id_iva");
            id_pais = data.id_pais;
            listar_paises(id_pais);
            id_moneda = data.id_moneda;
            listar_monedas(id_moneda);
            especial_contrib = data.especial_contrib;
			listar_si_no(especial_contrib, "especial_contrib");
			//IVA Débito Fiscal
			$("#iva_deb_fis").val(data.iva_deb_fis);
			$("#nom_ctb_iva_deb_fis").val(decodeHTMLEntities(data.nom_ctb_iva_deb_fis));			
			//IVA Crédito Fiscal
			$("#iva_cre_fis").val(data.iva_cre_fis);
			$("#nom_ctb_iva_cre_fis").val(decodeHTMLEntities(data.nom_ctb_iva_cre_fis));
            //Fechas
            $("#fec_ini_fis").val(data.fec_ini_fis);
            $("#fec_fin_fis").val(data.fec_fin_fis);
            $("#fec_ctb").val(data.fec_ctb);
            $("#fec_ban").val(data.fec_ban);
            $("#fec_cxc").val(data.fec_cxc);
			$("#fec_cxp").val(data.fec_cxp);
			$("#fec_nom").val(data.fec_nom);
            //Logo
			logo = data.logo;
			$("#imgPreview").attr('src', logo);
            //Correo
            $("#host").val(data.host);
            $("#usuario").val(data.usuario);
            $("#pass_email").val(data.pass_email);
            $("#puerto_send").val(data.puerto_send);
        },
    });
}
//Nuevo registro
function dat_form_new(){
    listar_zona_fiscal();
    listar_paises();    
    listar_monedas();
    listar_agrupador(0, "especial_contrib");
    listar_status(1);
}
//Foarmatear Código de Empresa
$("#cod_emp").on("keyup", function(e){
    e.preventDefault();
    format_emp();
    $("#nombre_emp").focus();
});
//Refrescar Index
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
			const url = `${base_url}/Empresas/destroy`;
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
		const url = `${base_url}/Empresas/store`;
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
						window.location.href = `${base_url}/Empresas`;
					}
				})
			},
		});
	}else{
		return false;
	}
})
//Al seleccionar un producto
$(".btn-open-modal").on('click', function(){
	// Capturamos el valor del atributo data-target-id y lo guardamos en la variable global
    targetInputId = $(this).data('target-id');
    targetInputName = $(this).data('target-name');
	//Abrir modal
   $("#modal-CuentasCtb").modal("show");
})
