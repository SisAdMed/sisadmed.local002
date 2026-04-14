//Variables
let item = 0;
id = $("#id").val();
$(document).ready(function(){
	id = $("#id").val();
    if(id){
        showrows(id);
    }
});
//Funcion Boton Eliminar
function eliminarBtn(element) {
	let idm = element.dataset.id;
	let name = element.dataset.name;
	const datos = new FormData();
	datos.append('id', idm);
	datos.append('name', name);
	Swal.fire({
		position: 'top-end',
		icon: 'warning',
		title: 'Está seguro de eliminar este registro?',
		showConfirmButton: true,
		confirmButtonText: 'ELIMINAR',
		confirmButtonColor: '#3085d6',
		showCancelButton: true,
		cancelButtonText: 'CANCELAR',
		cancelButtonColor: '#d33',
		buttonsStyling: true,
	}).then((result) => {
		if (result.isConfirmed){
			borrar(datos);
		};
	});
}
//Funcion para borrar
async function borrar(datos){
	try{
		let url =  `${base_url}/MotivoCambio/destroy`;
		let repuesta = await fetch(url, {
			method:"POST",
			body: datos,
		});
		const resulta = await repuesta.json();
		if(resulta == '1'){
			window.location.href = `${base_url}/MotivoCambio`;
			Swal.fire({
				position: 'top-center',
				icon: 'success',
				title: 'Registro eliminado satisfactoriamente.',
				showConfirmButton: true,
				timer: 5000,
			});
		}
	}catch(error){
		Swal.fire({
			position: 'top-center',
			icon: 'error',
			title: 'No se puede eliminar el resgistro motivado a que se encuentra asociado a un cliente.',
			showConfirmButton: true,
			timer: 5000,
		});
	}
}
//Agregar un nuevo registro a la tabla de FAbMarLabLin
function agregarFabMarLabLin(){
	item = item + 1;
	var htmlTags =
	'<tr id="fila'+item+'">' +
		'<td class="text-right">'+item+'</td>' +
		'<td><select name="id_fab[]" id="id_fab'+item+'" class="form-control select2 select2bs4 select-search confirmar" required></select></td>' +
		'<td><input type="number" name="adicional[]" class="form-control text-right" step="0.01" required></td>' +
		'<td><input type="date"name="vigencia[]" class="form-control" required></td>' +
		'<td class="text-center"><a type="button" class="btn btn-danger btn-xs" title="Eliminar item"><i class="fa fa-trash"></i></a></td>' +
	'</tr>';
	$("#cuerpoFabMarLabLin").append(htmlTags);
	$('.select-search').select2();
	listar_marcas(0, "id_fab" + item);
}
//funcion para confirmar una fila
$(document).on('click', '.confirmar', function(event) {
    event.preventDefault();
    recuperar_selects()
});

$(document).on('change', '.confirmar', function(event) {
    event.preventDefault();
    recuperar_selects()
});

function recuperar_selects () {
    let selects = $('.mi-select');

    selects.each(function () {
        let select = $(this);
        //id_fab = select.val()
    });
}
//Mostrar detalles al consultar un motivo
async function showrows(id){
	const datos = new FormData;
	datos.append('id', id);
	try{
		const url = `${base_url}/MotivoCambio/show_detalle`;
		const repuesta = await fetch (url, {
			method: 'POST',
			body: datos,
		})
		const resultado = await repuesta.json();
		$("#cuerpoFabMarLabLin").html('');
		item = 0;
		for(x of resultado){
			item = item + 1;
			var htmlTags =
				'<tr id="fila'+item+'">' +
				'<td class="text-right">'+item+'</td>' +
				'<td><select name="id_fab[]" id="id_fab'+item+'" class="form-control select2 select2bs4 select-search confirmar" required></select></td>' +
				'<td><input type="number" name="adicional[]" class="form-control text-right" step="0.01" value="'+x['adicional']+'" required></td>' +
				'<td><input type="date"name="vigencia[]" class="form-control" value="'+x['vigencia']+'" required></td>' +
				'<td class="text-center"><a type="button" class="btn btn-danger btn-xs del-deta" title="Eliminar item"><i class="fa fa-trash"></i></a></td>' +
			'</tr>';
			$("#cuerpoFabMarLabLin").append(htmlTags);
			$('.select-search').select2();
			listar_marcas(x['id_fab'], "id_fab" + item);
		}
	}catch(err){
		console.log(err);
	}
}
$(document).on('click', '.del-deta', function(event) {
  event.preventDefault();
  $(this).closest('tr').remove();
});