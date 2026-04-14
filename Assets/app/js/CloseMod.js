let fec_ini, fec_fin, id_emp, fec_ctb;
let URL_INICIO = `${base_url}/CloseMod/close_modules`;
const URL_PROGRESO = `${base_url}/CloseMod/consultar_progreso`;
let intervaloProgreso = null;
ori = GetURLParameter("ori");
//Inicializar la barra de progreso
const $progressBar = $("#progressBar");
const $progressText = $("#progressText");
const $btnIniciar = $("#btn_close");
$(document).ready(function () {
	  $("form#my_form").validate({
       rules:{
            id_emp: "required",
            date_close: "required"
        },
        messages:{
            id_emp: "Debe especificar una empresa",
            date_close: "Debe especificar un Fecha de Cierre de Módulos"
        },
    });
	if (!id_emp) {
		listar_empresas();
	}
});
$btnIniciar.on("click", function (e) {
	// Desactivar el botón y resetear el estado
	e.preventDefault();
	$(this).prop("disabled", true).text("Procesando...");
	$progressBar
		.css("width", "0%")
		.attr("aria-valuenow", 0)
		.removeClass("bg-success");
	$progressText.text("Iniciando proceso...");
	// 1. Iniciar el proceso en el backend
	if (ori == 'A') {
		URL_INICIO = `${base_url}/CloseMod/open_modules`;
	} 
	$.ajax({
		url: URL_INICIO,
		type: "POST",
		dataType: "json",
		data: { id_emp: id_emp, fec_ini: fec_ini, fec_fin: fec_fin },
		success: function (response) {
			if (response.status === "INICIADO") {
				//console.log("Proceso iniciado en el servidor. Comenzando polling...");
				// 2. Iniciar la consulta periódica (polling)				
				iniciarPollingProgreso();	
			} else {
				$progressText.text("Error al iniciar el proceso.");
				$btnIniciar.prop("disabled", false).text("Iniciar Cierre");
			}
		},
		error: function (error) {
			$progressText.text("Se ha presentado el siguiente error." + error.responseText);
			$btnIniciar.prop("disabled", false).text("Iniciar Cierre de Módulos");
		},
	});
	
});
$("#id_emp").on("change", async function (e) {
	e.preventDefault();
	id_emp = $(this).val();
	if (id_emp) {
		dat_emp = await tfechas_emp(id_emp);
		if (ori == "C") {
			fec_ini = dat_emp["fec_ctb"];
			fec_ctb = new Date(dat_emp["fec_ctb"]);
			fec_ctb.setDate(fec_ctb.getDate() + 1);
			fec_ctb = getLastDateofMonth(fec_ctb);
			fec_fin = fec_ctb;				
			$("#date_close").val(fec_ctb);
		} else {
			fec_ini = dat_emp["fec_ctb"];
			fechaActual = new Date(dat_emp["fec_ctb"]);
			fec_mes_ant = new Date(fechaActual);
			fec_mes_ant.setMonth(fec_mes_ant.getMonth() - 1);
			fec_ctb = getLastDateofMonth(fec_mes_ant);
			fec_fin = fec_ctb;	
			$("#date_close").val(fec_fin);
		}
	} else {
		$("#date_close").val("");
		fec_ctb = "";
		fec_ini = "";
		fec_fin = "";
	}
});

function iniciarPollingProgreso() {
	// Limpiar cualquier intervalo anterior
	if (intervaloProgreso) {
		clearInterval(intervaloProgreso);
	}
	// Configurar el intervalo para consultar cada 2 segundos
	intervaloProgreso = setInterval(function () {
		$.ajax({
			url: URL_PROGRESO,
			type: "POST",
			dataType: "json",
			success: function (data) {
				actualizarBarra(data.progreso, data.mensaje);
				// 3. Detener el polling si ha terminado
				if (data.status === "Completado") {
					clearInterval(intervaloProgreso);
					$progressBar.addClass("bg-success");
					origen = "Cierre"
					if (ori == 'A') {
						origen = "Apertura"
					}
					$btnIniciar.prop("disabled", false).text(`Iniciar ${origen} de Módulos`);
					//Actualizar Fecha de Cierre / Apertura de Modulos
					var formData = $(this).serialize();
					const url = `${base_url}/CloseMod/upd_fec_cie`
					//Ajax para actualizar la fecha de cierre
					console.log('id_emp', id_emp);
					console.log('fec_fin', fec_fin);
					
					
					$.ajax({
						url: url,
						method: 'POST',
						dataSrc: '',
						data: {id_emp: id_emp, fec_fin: fec_fin},
						dataType: 'json',
						beforeSend: function() {
							loader.show();
						},
						complete: function() {
							loader.hide();
						},
						error: function (jqXHR, textStatus, errorThrown) {
							loader.hide();
							console.error("Error en la solicitud AJAX:");
							console.error("Estado de texto:", textStatus); // Por ejemplo: "timeout", "error", "Not Found", "Internal Server Error"
							console.error("Error lanzado:", errorThrown); // Mensaje de error detallado del servidor
							console.error("Código de estado HTTP:", jqXHR.status); // Código numérico (ej: 404, 500)
							console.error("Respuesta del servidor:", jqXHR.responseText); // Contenido de la respuesta del servidor
						},
						success: function (data) {
							Swal.fire({
								title: "Proceso Finalizado!",
								text: "Proceso de cierre de modulos finalizado satisfactoriamente!",
								icon: "success",
							}).then((result) => {
								if (result.isConfirmed) {
									window.location.href = `${base_url}/CloseMod?ori=ori`;									
								}
							});;
						},
					});
					
				}
				if (data.status === "ERROR") {
					clearInterval(intervaloProgreso);
					$progressBar.removeClass("bg-primary").addClass("bg-danger");
					$btnIniciar.prop("disabled", false).text("Iniciar Cierre de Módulos");
					Swal.fire({
						title: "Proceso con error!",
						text: "Proceso de cierre de modulos terminado en error, consulta con el Administrador del sistema!",
						icon: "error",
					});
				}
			},
		});
	}, 2000); // 2000 ms = 2 segundos
}
function actualizarBarra(progreso, mensaje) {
	$progressBar.css("width", progreso + "%").attr("aria-valuenow", progreso);
	$progressText.html(mensaje + " (" + progreso + "%)");
}