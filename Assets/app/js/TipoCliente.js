//Declaración de variables
let tbl_tabla = "#tblTable_TipoCliente";
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
//Al ingresar a la aplicacion
$().ready(function (e) {
	id = $("#id").val();
	if (id) {
        show_dat_form(id);
	} else {
		listar_status(1);
	}
});
//Mostrar datos del registro
function show_dat_form($id){
    div_loading();
    const url = `${base_url}/TipoCliente/show_row`;
    $.ajax({
		url: url,
		method: "POST",
		dataSrc: "",
		data: { id: id },
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data_dat) {
			if (data_dat) {
				data = JSON.parse(data_dat);
				//Valores a Variablas
				description = data["description"];
				status_dat = data["status"];
				//Variables a Formulario
				$("#description").val(description);
				listar_status(status_dat);
			}
		},
		complete: function () {
			$(".loader").hide();
		},
		error: function () {
			$(".loader").hide();
		},
	});
}
//Cargar pantalla del index
$().ready(function (e) {
	load_screan_main();
});
//Index
function load_screan_main() {
	div_loading();
	const url = `${base_url}/TipoCliente/cargar_screen_main`;
	$.ajax({
		url: url,
		method: "POST",
		dataSrc: "",
		data: {},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (resultado) {
			if (resultado) {
				filename =
					"Listado de Tipos_de_Clientes_" +
					new Date().toISOString().replace(/[\-\:\.]/g, "");
				data = JSON.parse(resultado);
				var tblControl = $(tbl_tabla).DataTable({
					fnInitComplete: function () {
						$("thead tr").clone().appendTo($("tfoot"));
					},
					responsive: true,
					aProcessing: true,
					aServerSide: true,
					data: data,
					destroy: true,
					clear: true,
					autowith: true,
					//Display registros
					lengthMenu: [
						[5, 10, 25, 50, -1],
						[5, 10, 25, 50, "Todos"],
					],
					iDisplayLength: 10,
					//Lenguaje
					language: {
						url: `${base_url}/Assets/json/es-ES.json`,
					},
					columns: [
						{ data: "id", title: "Id" , "className": "text-right", "width": "10px"},
						{ data: "description", title: "Descripción" },
						{ data: null, title: "Status", className: "text-center",
							render: function (data, type, row) {
								if (data.status == "1") {
									return `<span class="badge badge-success">Activo</span>`;
								} else {
									return `<span class="badge badge-danger">Inactivo</span>`;
								}
							},
						},
                        {data: "status", title: 'Acciones', width: "100px", "className": "text-center",
                            render: function(data, type, row){
								return '<a type="button" class="btn btn-warning btn-xs" href="'+base_url+'/TipoCliente/edit/'+row.id+'" title="Editar"><i class="fa fa-edit"></i></a>' + ' ' + 
								'<button id="Data" data-id="'+row.id+'" data-name="'+row.description+'" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)" title="Eliminar"><i class="fa fa-trash"></i></button>'
								; 
                            },
                        }
					],
					// mostrar botones de exportacion
					dom: "lBfrtip",
					buttons: [
						{
							extend: "copyHtml5",
							text: "<i class='fa fa-copy'></i>",
							titleAttr: "Copiar",
							className: "btn btn-secondary",
						},
						{
							extend: "excelHtml5",
							text: "<i class='fa fa-file-excel'></i>",
							titleAttr: "Exportar a Excel",
							className: "btn btn-warning",
							filename: filename,
						},
						{
							extend: "pdfHtml5",
							text: "<i class='fa fa-file-pdf'></i>",
							titleAttr: "Exportar a PDF",
							className: "btn btn-danger",
							filename: filename,
						},
						{
							extend: "csvHtml5",
							text: "<i class='fa fa-file-text'></i>",
							titleAttr: "Exportar a CSV",
							className: "btn btn-primary",
							filename: filename,
						},
					],
				});
			}
		},
		complete: function () {
			$(".loader").hide();
		},
		error: function (xhr, status, error) {
			$(".loader").hide();
		},
	});
}
//Validar de que no existe el Tipo de Cliente en la BD
$(document).on("keyup", "#description", async function (e) {
	e.preventDefault();
	let datos = new FormData();
	let description = $(this).val();
	description.replace(/ /g, "");
	datos.append("description", description);
	try {
		const url = `${base_url}/TipoCliente/description`;
		const respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const data = await respuesta.json();
		if (data) {
			validateExists(data, e);
		}
	} catch (error) {
		console.log(error);
	}
});
//Eliminar Registro Agregado el 04-07-2025 a las 11:38:00 por José Vargas
function eliminarBtn(id){
	description = id.dataset.name;
    id = id.dataset.id;
    Swal.fire({
        title: "¿Está usted seguro de eliminar este registro?",
        text: "¡No podrá revertir esta eliminación!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, borrar este registro!",
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
            borrar(id, description);
        }
    });
}
//Funcion para borrar
async function borrar(id, description){
    const datos = new FormData();
    datos.append('id', id);
	datos.append('description', description);
    try{
        const url = `${base_url}/TipoCliente/destroy`;
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
                window.location.href = `${base_url}/TipoCliente`;
            };
        });
    }catch(error){
         Swal.fire({
            icon: 'error',
            title: 'Error.....',
            text: 'El Tipo de Cliente ' + description + ' no se pudo eliminar ya que el mismo se encuentra asociado a un Cliente'
        });
    }
}