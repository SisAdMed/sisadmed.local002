//Variables
let mod;
//Validación de campos
$().ready(function () {
	$("#my_form").validate({
		rules: {
			description: {
				required: true,
				minlength: 5,
			},
			status: "required",
		},
		mesagges: {
			description: {
				required: "Debe especificar una Descripción",
				minlength: "La descripción debe contener al menos 5 carácteres",
			},
			status: "Debe especificar un Status",
		},
	});
});
//Al iniciar el formulario
$().ready(function () {
	id = $("#id").val();
	if (id != undefined) {
		if (id) {
			show_row(id);
		} else {
			listar_modulos('"B":"P"');
			listar_status(1);
		}
	}
});
//Mostrar registro
function show_row(id){
	const url = `${base_url}/ConExcludeDash/show_row`;
	div_loading();
	$.ajax({
		type: 'POST',
		url:url,
		dataSrc: '',
		data: {'id': id},
		dataType: 'json',
		beforeSend:function(){
			$('.loader').show();
		},
		success: function(data){
			$('#id').val(data.id);
			listar_status(data.status);
			mod = data.module
			listar_modulos('"B":"P"', mod, true);
			$("#nom_mod").val(data.nombre);
			if (mod == "P") {
				listar_conceptos_CXP(data.id_concept, "id_concept");
			} else if (mod == "B") {
				listar_conceptos_BAN(data.id_concept, "id_concept");
			}
			
		}, 
		error: function(xhr){
			$('.loader').hide()
			console.log(xhr.statusText + ' ' + xhr.responseText);
		},
		complete: function(){
			$(".loader").hide();
		}
	})
}
//Al seleccionar el módulo
$("#mod").on("change", function () {
	$("#id_concept").empty();
	module = $(this).val();
	nom_mod = $("#mod option:selected").text();
	$("#nom_mod").val(nom_mod);
	if (module === "P") {
		listar_conceptos_CXP("", "id_concept");
	} else if (module === "B") {
		listar_conceptos_BAN_EXC("", "id_concept");
	}
});
//Al seleccionar Concepto
$("#id_concept").on("change", function () {
	nom_con = $("#id_concept option:selected").text();
	$("#nom_con").val(nom_con);
});
//Guardar y/o Actualizar
$("#my_form").on("submit", function (e) {
	e.preventDefault();
	div_loading();
	var formData = $(this).serialize();
	const url = `${base_url}/ConExcludeDash/store`;
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
					Swal.fire({
						icon: "question",
						title: "¿Desea agregar otro registro?",
						showDenyButton: true,
						confirmButtonText: "Si",
						denyButtonText: `No`,
					}).then((result) => {
						if (result.isConfirmed) {
							$("#my_form")[0].reset();
						} else if (result.isDenied) {  							
							window.location.href = `${base_url}/ConExcludeDash`;
						}
					});
					
				}
			});
		},
		complete: function () {
			$(".loader").hide();
		},
		error: function (xhr) {
			$(".loader").hide();
			console.log(xhr.statusText + " " + xhr.responseText);
		},
	});
});
//Eliminar Registro
$("#tblTable").on("click", ".delete-row", function () {
	var table = $("#tblTable").DataTable();
	var rowToDelete = $(this).closest("tr");
	var rowData = table.row(0).data();
	id = rowData[0];
	Swal.fire({
		title: "¿Está usted seguro de eliminar este registro?",
		text: "¡No podrá revertir esta eliminación!",
		icon: "question",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Si, borrar este registro!",
		cancelButtonText: "Cancelar",
	}).then((result) => {
		if (result.isConfirmed) {
			if(borrar(id)){
				table.row(rowToDelete).remove().draw(false);
			}
		}
	});
});
function eliminarBtn(id){
	
}
//Funcion para borrar
async function borrar(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url = `${base_url}/ConExcludeDash/delete_row`;
        const repuesta = await fetch(url, {
            method:"POST",
            body: datos,
        });
        const resulta = await repuesta.json();
        Swal.fire({
            icon: `${resulta.icon}`,
            title: `${resulta.title}`,
            text: `${resulta.msg}`,
        }).then((result) => {
            if (result.isConfirmed){
                //window.location.href = `${base_url}/ConExcludeDash`;
            };
        });
    }catch(error){
         Swal.fire({
            icon: 'error',
            title: 'Error.....',
            text: 'No se pudo eliminar el registro ya que se encuentra asociado'
        });
    }
}
