//Variables
status = $("#statusr").val();
//Llenar combo de Estatus de Historico de Precios de Producto
$(document).ready(function(){
	if(status){
		status = status;
	}else{
		status = "P";
	}
	listar_status_his_precios_productos(status, "status");

	if(status == 'V'){
		$("#status").find('[value="V"]').remove();
	}else if(status == "A"){
		$("#status").attr("readonly","readonly");
	}
})
//Verificar cuando cambie el status para habilitar los campos de fecha de vigencia y aprobado
$("#status").change(function(event) {
	event.preventDefault();
	status = $(this).val();
	var fecha_vigencia = new Date();
	if (status =='V'){
		$("#fecha_vigencia").removeAttr('readonly');
		//$("#fecha_vigencia").val(fecha_vigencia);
	}else if(status == 'A'){
		$("#fecha_aprobado").removeAttr('readonly');
	}else{
		$("#fecha_vigencia").attr("readonly","readonly");
		$("#fecha_aprobado").attr("readonly","readonly");
	}
})
//Cargar prodcutos desde el archivo cargado a la tabla
$("#archivo_histo").change(async function(event) {
	event.preventDefault();
	//Variables
	var archivo = $(this).val();
	if(archivo && archivo != ''){
		var extensiones = archivo.substring(archivo.lastIndexOf("."));
		//Validar que posea una extension valida para excel
		if(extensiones != ".xls" && extensiones != ".xlsx" && extensiones != ".xlsb"){
			Swal.fire({
				icon: 'error',
				title: 'Error...',
				text: 'El archivo de tipo ' + extensiones + ' no es válido',
			})
		}else{
			$('.loader').show();
		}
	}else{
		$("#tab_pre_pro").empty();
	}
	setTimeout(function(){
    	$('.loader').hide();
	}, 10000);
});
//Eliminar registro
function eliminarBtn(element) {
	let idm = element.dataset.id;
	let namem = element.dataset.name;
	const datos = new FormData();
	datos.append('id', idm);
	datos.append('name', namem)	;
}