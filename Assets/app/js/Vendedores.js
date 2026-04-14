//Validar formulario
let table;
item = 0;
$().ready(function(){
    jQuery.validator.setDefaults({
		debug: false,
		success: "valid",
	});
    $("form[name='my_form']").validate({
        rules:{
        ced_vend: "required",
           nom_vend: {
                required: true,
                minlength: 4,
                maxlength: 50,
            },
             ape_vend: {
                required: true,
                minlength: 4,
                maxlength: 50,
            },
            email_vend: "required",
            fecing_vend: "required",
            comi_vend: {
                required: true,
            
            },
            id_pais: "required",
            id_edo: "required",
            id_ciudad: "required",
            dir_vend: "required",
            status: "required",
        },
        messages:{
            ced_vend: "Debe especificar una Çédula para el vendedor",
            nom_vend: {
                required: "Debe especificar un Nombre ppar el Vendedro",
                minlength: "El nombre debe contener al menos 4 carácteres",
                maxlength: "El nombre de contener máximo 50 carácteres",
            },
            ape_vend: {
                required: "Debe especificar un Apellido para el Vendedor",
                minlength: "El apellido debe contener al menos 4 carácteres",
                maxlength: "El apellido debe contener máximo 50 carácteres",
            },
            email_vend: "Debe espicicar un correo electrónico",
            fecing_vend: "Debe especificar la fecha de ingreso del Vendedor",
            comi_vend:{
                required: "Debe especificar una monto de comisión",
            },
            id_pais: "Debe especificar un país",
            id_edo: "Debe especificar un Estado",
            id_ciudad: "Debe especificar una ciudad",
            dir_vend: "Debe especificar una dirección",
            status: "Debe especificar un status",
        }
    });
    //CArgar el index
    form = $("form").attr("id");
    if(form === undefined){
        initVendedoresTable();
    }else{
        //Cuando es un registro nuevo
        id = $("#id").val();
		if (id) {
			showrow(id);
		} else {
			listar_paises();
			listar_status(9);
		}
    }
});
async function showrow(id){
    const url = `${base_url}/Vendedores/showrow`;
    $.ajax({
        url: url,
        type: 'POST',
        data: {id: id},
        dataSrc: '',
        dataType: 'json',
        beforeSend: function(){
            loader.show();
        },
        complete: function(){
            loader.hide();
        },
        error: function(error){
            loader.hide();
            console.log('Ha ocurrido el siguiente error: ', error.responseText());
        }, 
        success: function(resultado){
            if(resultado){
                $("#ced_vend").val(resultado[0]["ced_vend"]);
                $("#ced_vend").prop("readonly", true);
                $("#nom_vend").val(resultado[0]["nom_vend"]);
                $("#ape_vend").val(resultado[0]["ape_vend"]);
                $("#email_vend").val(resultado[0]["email_vend"]);
                $("#fecing_vend").val(resultado[0]["fecing_vend"]);
                comi_vend = format_number_with_dec_new(resultado[0]["comi_vend"],2)
                $("#comi_vend").val(comi_vend);
                id_pais = resultado[0]["id_pais"];
                listar_paises(id_pais);
                id_edo = resultado[0]["id_edo"];
                listar_estados(id_pais, id_edo);
                id_ciudad = resultado[0]["id_ciudad"];
                listar_ciudades(id_edo, id_ciudad);
                $("#dir_vend").val(resultado[0]["dir_vend"]);
                status = resultado[0]["status"];
                listar_status(status);
            }
        }
    })
}
$("#id_pais").change(function(){
    id_pais = $("#id_pais").val();
    listar_estados(id_pais, '');
});
$("#id_edo").change(function(){
    var id_edo = $("#id_edo").val();
    listar_ciudades(id_edo, '');
});
//Guardar y/o Actualizar
$("#my_form").on('submit', function(e){
    e.preventDefault();
    if($(this).valid()){
        var formData = $(this).serialize();
        const url = `${base_url}/Vendedores/store`;
        console.log(url);
        $.ajax({
            method: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            beforeSend: function(){
                loader.show();
            },
            complete: function(){
                loader.hide();
            },
            error: function (error) {
                loader.hide();
                console.log('Ha ocurrido el siguiente error: ' + error.responseText());
            }, 
            success: function(data){
                Swal.fire({
                    title: data.title,
                    text: data.msg,
                    icon: data.icon,
                }).then((result) => {
                    if(data.icon != 'error'){
                        window.location.href = `${base_url}/Vendedores`;
                    }
                })
            }
        })
    }
})
//Eliminar un registro
$("#tblIndexMain").on("click", ".btn-delete", function () {
	var recordId = $(this).data("id"); // Obtiene el ID del registro
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
			const url = `${base_url}/Vendedores/destroy`;
			$.ajax({
				url: url,
				method: "POST",
				data: { id: recordId, name: recordNam },
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
				error: function (jqXHR, textStatus, errorThrown) {            
					Swal.fire({
						icon: "error",
						title: "Error.....",
						text: "No se puede eliminar el Vendedor ya que se encuentra asociado en Cotizaciones y/o Factura"
					});
				},
			});
		}
	});
});