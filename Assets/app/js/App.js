//Mostrar registros de tablas
let mostrar_rows = -1;
let stock = 0;
let id_alm_res;
let origen_COM = 0;
let mov_inv = false;
c_consig = 0;
let print_special = 0;
let id_ubi;
let id_alm = "";
let ori;
let myConsulproduc = {};
let max_item = 0;
let id_bancon;
let efecto = "";
let almSalPpal = false;
let id_alm_ppal = "";
let id_alm_fac = "";
let mov_inv_sal = false;
let equivale = false;
//Unidd de Venta Global
let uni_vta_gbl = 0;
//Bloquear precio de ventas en cotizaciones Optimizado por Jose Vargas el 27-05-2025 a las 09:47:00
let loc_pri_cot = 0;
let loc_pri_inv = 0;
//Bloquear que se puedan hacer facturas y/o cotizacion Solicitado por Nelson Guerra el 28-05-2005 a las 10:00:00
let locked_invoice = 0;
table = "";
let id_ubi_consig = "";
//Consecutivos de registros a cancelar en Bancos Movimientos y en Cuentas por Cobrar Movimientos. José Vargas 28-08-2025 10:21:00
let item_doc = 0;
let total_mov = 0;
let tpase_ret = false;
let TMod = "";
let targetInputNumRet = "";
let id_alm_def;
let id_ubi_def;
let id_aux = "";
let loader = "";
let sol_aprob = false;
let totmsj = 0;
let especial_contrib = "";
item = 0;
let id_moneda_cia;
//Valor del IVA
let xtasavatTax_val = false;
// Variable global para almacenar el ID del textbox y Nombre de destino 
let targetInputId = '';
let targetInputName = '';
//
//AutoNumeric
let myNumberFormatDom = null;
let myNumberFormatFor = null;
//Formatos de Fecha
const FROM_PATTERN = "YYYY-MM-DD";
const TO_PATTERN = "DD-MM-YYYY";

const FROM_PATTERNHH = "YYYY-MM-DD hh:mm:ss";
const TO_PATTERNHH = "DD-MM-YYYY hh:mm:ss";
//
//Total de Imagenes de un producto
let total_img_prod = 0;
let nom_table = "";
let efe_bantmo = "X";
//
//Contador de Contactos
let item_det = 0;
let miTabledet_con;
//
let AutoConfig = {
	decimalPlaces: 2,
	decimalCharacter: ",",
	digitGroupSeparator: ".",
	dotDecimalCharCommaSeparator: ",",
	decimalCharacterAlternative: ".",
};
let AutoConfigTasaCambio = {
	decimalPlaces: 8,
	decimalCharacter: ",",
	digitGroupSeparator: ".",
	dotDecimalCharCommaSeparator: ",",
	decimalCharacterAlternative: ".",
};
//Variable para saber si el tipo de documento a utilizar es Credito para cambiar el signo de forma automatica
let accion = 1;
//Mascaras
$(document).ready(function() {
    // Configuración del Widget PushMenu
    $('[data-widget="pushmenu"]').PushMenu({
        expandOnHover: true,
        screenReaderAnnouncementMenu: "Menu expandido",
        showImplicit: true
    });

    // Truco extra: Si el mouse entra al sidebar y no abre, forzamos la clase
    $('.main-sidebar').hover(
        function() {
            if ($('body').hasClass('sidebar-collapse')) {
                $(this).addClass('sidebar-focused');
                $('body').addClass('sidebar-open').removeClass('sidebar-collapse');
            }
        }, function() {
            if ($(this).hasClass('sidebar-focused')) {
                $(this).removeClass('sidebar-focused');
                $('body').removeClass('sidebar-open').addClass('sidebar-collapse');
            }
        }
    );
});
$(function () {
	//Mascara para el RIF
	const $rifInput = $('.rif');
	// 1. Definición de la traducción para la letra inicial (J, P, V, E)
	let options = {
		translation: {
			'S': {
				pattern: /[JjVvEeGgPpCc]/, // Patrón que acepta minúsculas o mayúsculas
				uppercase: true        // Fuerza la conversión a mayúscula
			}
		}
	};
	// 2. Aplicación inicial de la máscara
	$rifInput.mask('S-00000000-0', options)
	// 3. Manejar el evento 'blur' para rellenar con ceros
	$rifInput.on('blur', function () {
		let currentValue = $rifInput.val().toUpperCase();
		// Expresión regular para capturar la letra, los 8 dígitos y el último dígito
		// Ejemplo: J-123-4
		const regex = /^([JPVE])-(\d+)-(\d)$/;
		const match = currentValue.match(regex);
		if (match) {
			let initialLetter = match[1]; // J, P, V, o E
			let middleDigits = match[2];  // Los dígitos ingresados (ej. 123)
			let lastDigit = match[3];     // El último dígito (ej. 4)            
			// Función para rellenar con ceros a la izquierda hasta 8 caracteres
			// '00000000' + middleDigits).slice(-8)
			let paddedDigits = ('00000000' + middleDigits).slice(-8);
			// Reconstruir el valor completo y aplicarlo al input
			let completeValue = `${initialLetter}-${paddedDigits}-${lastDigit}`;
			// Quitar temporalmente la máscara para establecer el valor completo
			$rifInput.unmask().val(completeValue);
			// Volver a aplicar la máscara para mantener el formato
			$rifInput.mask('S-00000000-0', options);
		}
	});
	//Mascara Número de Telefono 000-00-00
	mobileMaskBehavior = function (val) {
		return val.replace(/\D/g, '').length === 7 ? '000-00-00' : '000-00-00';
	},
		mobileOptions = {
			onKeyPress: function (val, e, field, options) {
				field.mask(mobileMaskBehavior.apply({}, arguments), options);
			}
		};
});
//Activar Notificaciones
document.addEventListener("DOMContentLoaded", function () {
	if (Notification.permission !== "granted") {
		Notification.requestPermission();
	}
});
//Mostrar Notificaciones
function show_notification() {
	const url = `${base_url}/Usuarios/show_notification_win`;
	$.ajax({
		url: url,
		type: "POST",
		data: {},
		dataSrc: "",
		dataType: "json",
		success: function (data) {
			$.each(data, function (key, value) {
				customnotify(
					value.title,
					value.message,
					value.url,
					value.id_fgenmsg
				);
			});
		},
	});
}
//Actualizar notificaciones
function actualizarNotificaciones() {
	const url = `${base_url}/Usuarios/show_notification`;
	$.ajax({
		url: url,
		type: "POST",
		data: { tipo: 1 },
		dataSrc: "",
		dataType: "json",
		success: function (data) {
			if (data.totmsj > 0) {
				totmsj = data.totmsj;
				if (totmsj > 0) {
					$("#tot_not").html(totmsj);
					$("#tot_not").attr("title", `${totmsj} Notificación(es)`);
					show_details_notify();
				}
			}
		},
	});
}

//Mostrar el detalle de las notificaciones 
function show_details_notify() {
	const url = `${base_url}/Usuarios/show_notification_win`;
	$.ajax({
		url: url,
		type: "POST",
		data: {},
		dataSrc: "",
		dataType: "json",
		success: function (data) {
			console.log(data);
			let det_notify = $(".tot_notify");
			det_notify.empty();
			let det_notify_show = "";
			let det_det = "";
			det_notify_show = `
				<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
					<span class="dropdown-item dropdown-header text-xs">${totmsj} Notificación(es)</span>
					<div class="dropdown-divider"></div>`;
			$.each(data, function (key, value) {
				det_notify_show += `
					 <a href="#" class="dropdown-item text-xs">
                    	<i class="fas fa-envelope mr-2 text-xs"></i>${value.title} 
					</a>
					<div class="dropdown-divider"></div>
				`;
			});
			det_notify_show += `<div class="dropdown-divider"></div>
				<a href="#" class="dropdown-item dropdown-footer text-xs">Ver todas las Notificaciones</a>`;
			det_notify_show += `</div>`;
			det_notify.append(det_notify_show);
		},
	});
}
//Enviar notificaciones
function customnotify(title, desc, url, id) {
	if (Notification.permission !== "granted") {
		Notification.requestPermission();
		var notification = new Notification(title, {
			icon: `${base_url}/Assets/img/notify_img.png`,
			body: desc,
		});
	} else {
		var notification = new Notification(title, {
			icon: `${base_url}/Assets/img/notify_img.png`,
			body: desc,
		});

		/* Remove the notification from Notification Center when clicked.*/
		notification.onclick = function () {
			window.open(url);
			//Actualizar notifación como leida
			$.ajax({
				url: `${base_url}/Usuarios/read_notify`,
				method: "POST",
				data: { id: id },
				dataSrc: "",
				dataType: "json",
			}).done(function (response) {
				show_notification();
				actualizarNotificaciones();
			});
		};

		/* Callback function when the notification is closed. */
		notification.onclose = function () { };
	}
}

$(document).ready(async function () {
	//Perparar Loading
	div_loading();
	//Evitar la ejecución de la techa enter
	$(document).on("keydown", function (e) {
		// Verifica si la tecla presionada es Enter (keyCode 13)
		if (e.keyCode === 13) {
			// Evita el comportamiento por defecto de enviar el formulario
			e.preventDefault();
		}
	});
	//Mostrar Notificaciones y alertas el Sistema al iniciar el sistema
	//show_notification();
	//actualizarNotificaciones();
	//Mostrar Notificaciones y alertas el Sistema al iniciar el sistema cada minuto
	//setInterval(function () {
	//	show_notification();
	//	actualizarNotificaciones();
	//}, 60000);
	//AutoNumeric Domestico
	//Formatear camppos numericos, positovos o negativos y solo coma como decimal
	//anElement1 = new AutoNumeric('#tasa_cambio', AutoConfigTasaCambio);
	$(".foranea").hide();
	$(".local").hide();
	if (efe_bantmo == "C") {
		nom_table = "";
	} else {
		nom_table = "tblSeatDetail_cxp";
	}
});
//Obtener el primer dia del mes
function getFirstDateofMonth(xDate = Date()) {
	var date = new Date(xDate);
	var primerDia = new Date(date.getFullYear(), date.getMonth(), 1);
	var mesactual = date.getMonth() + 1;
	var anioactual = date.getFullYear();
	var tfecha =
		anioactual +
		"-" +
		String(mesactual).padStart(2, "0") +
		"-" +
		String(primerDia.getDate()).padStart(2, "0");
	return tfecha;
}
//Obtener el ultimo dia del mes
function getLastDateofMonth(xDate = Date()) {

	var date = new Date(xDate);
	date.setDate(date.getDate() + 1)
	var ultimoDia = new Date(date.getFullYear(), date.getMonth() + 1, 0);
	var mesactual = date.getMonth() + 1;
	var anioactual = date.getFullYear();
	var getLastDateofMonth =
		anioactual +
		"-" +
		String(mesactual).padStart(2, "0") +
		"-" +
		String(ultimoDia.getDate()).padStart(2, "0");
	return getLastDateofMonth;
}

//Funcion para validar el padre de un registro que use agrupador
function Padre(valor) {
	let sTemp = "";
	let n = 0;
	let i = 0;
	let temp = "";
	let Punto = 0;
	let Punt = "";

	while (valor.indexOf(".") > -1) {
		n = valor.indexOf(".");
		sTemp = sTemp + valor.substring(0, n);
		valor = valor.substring(n + 1);
	}
	return sTemp;
}
//Buscar Parametros de un Modulo en especifico
async function getParam(mod) {
	var datos = new FormData();
	try {
		const url = `${base_url}/Parametros` + mod + `/getParam`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
}
//IFrame
$(".content-wrapper").IFrame({
	onTabClick(item) {
		return item;
	},
	onTabChanged(item) {
		return item;
	},
	onTabCreated(item) {
		return item;
	},
	autoIframeMode: true,
	autoItemActive: true,
	autoShowNewTab: true,
	allowDuplicates: false,
	loadingScreen: 750,
	useNavbarItems: false,
});
//Control de errores en Formularios
const validateEmptyField = (message, e) => {
	const field = e.target;
	const fieldValue = e.target.value;
	if (fieldValue.trim().length == 0) {
		field.classList.add("invalid");
		field.nextElementSibling.classList.add("error");
		field.nextElementSibling.innerText = message;
	} else {
		field.classList.remove("invalid");
		field.nextElementSibling.classList.remove("error");
		field.nextElementSibling.innerText = "";
	}
	const btnok = document.getElementById("btnok");
	if (document.getElementsByClassName("invalid")) {
		btnok.disabled = true;
	} else {
		btnok.disabled = false;
	}
};
const validateExists = (message, e) => {
	const field = e.target;
	const fieldValue = e.target.value;

	if (message.success == 1) {
		field.classList.add("invalid");
		field.nextElementSibling.classList.add("error");
		field.nextElementSibling.innerText = message.message;
	} else {
		if (document.getElementsByClassName("invalid")) {
			field.classList.remove("invalid");
			field.nextElementSibling.classList.remove("error");
			field.nextElementSibling.innerText = "";
		}
	}
	const btnok = document.getElementById("btnok");
	if (document.getElementsByClassName("invalid").length != 0) {
		btnok.disabled = true;
	} else {
		btnok.disabled = false;
	}
};
//Initialize Select2 Elements
$(".select2").select2();

//Initialize Select2 Elements
$(".select2bs4").select2({
	theme: "classic",
});

$("li>a").click(function (e) {
	$("li>ul>li>a").addClass("d-block");
});
function format_emp() {
	let cod_emp = document.getElementById("cod_emp").value;
	cod_emp = cod_emp.padStart(5, "0");
	document.getElementById("cod_emp").value = cod_emp;
}
function mayusculas(e) {
	e.value = e.value.toUpperCase();
}
function minisculas(e) {
	e.value = e.value.toLowerCase();
}
async function exchangeRate(fecha1, moneda1, tasa1 = "") {
	let fecha = $("#" + fecha1).val();
	let moneda = $("#" + moneda1).val();
	const datos = new FormData();
	datos.append("fecha", fecha);
	datos.append("moneda", moneda);
	try {
		if (moneda) {
			const url = `${base_url}/Cambios/rateExchange`;
			const respuesta = await fetch(url, {
				method: "POST",
				body: datos,
			});
			const resultado = await respuesta.json();
			if (resultado) {
				if (tasa1 && tasa1 != "") {
					$("#" + tasa1).val(resultado);
				} else {
					return resultado;
				}
			} else {
				//$("#tasa_cambio").val(1);
				xTasa = 1;
			}
		}
	} catch (err) {
		console.log(err);
	}
}
let tblTable;
document.addEventListener(
	"DOMContentLoaded",
	function () {
		tblTable = new DataTable("#tblTable", {
			aProcessing: true,
			aServerSide: true,
			//opcionesnes de lenguaje
			language: {
				url: `${base_url}/Assets/json/es-ES.json`,
			},
			// ocultar columnas
			columnDefs: [
				{
					targets: [0],
					visible: false,
					serchable: false,
				},
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
				},
				{
					extend: "pdfHtml5",
					text: "<i class='fa fa-file-pdf'></i>",
					titleAttr: "Exportar a PDF",
					className: "btn btn-danger",
				},
				{
					extend: "csvHtml5",
					text: "<i class='fa fa-file-text'></i>",
					titleAttr: "Exportar a CSV",
					className: "btn btn-primary",
				},
			],
			lengthMenu: [
				[5, 10, 25, 50, -1],
				[5, 10, 25, 50, "Todos"],
			],
			iDisplayLength: 10,
			order: [[1, "asc"]],
		});
	},
	false
);
let tblTableDetail;
document.addEventListener(
	"DOMContentLoaded",
	function () {
		tblTableDetail = new DataTable("#tblTableDetail", {
			aProcessing: true,
			aServerSide: true,
			//Ocultar control de mostrar x cantidad de registros, por defecto los muestra todos para los detalles
			dom: "rtip",
			paging: false,
			//opcionesnes de lenguaje
			language: {
				url: `${base_url}/Assets/json/es-ES.json`,
			},
			// ocultar columnas
			columnDefs: [
				{
					targets: [0],
					visible: false,
					serchable: false,
				},
				{
					targets: [1],
					visible: false,
					serchable: false,
				},
				{
					targets: [4],
					visible: false,
					serchable: false,
				},
			],
			lengthMenu: [
				[5, 10, 25, 50, -1],
				[5, 10, 25, 50, "Todos"],
			],
			iDisplayLength: 10,
			order: [[1, "asc"]],
		});
	},
	false
);
async function validaSelecCue_AuxSN(id_cta) {
	const datos = new FormData();
	datos.append("id_cta", id_cta);
	try {
		if (id_cta) {
			const url = `${base_url}/CuentasCtb/validaSelecCue_AuxSN`;
			const respuesta = await fetch(url, {
				method: "POST",
				body: datos,
			});
			const resultado = await respuesta.json();
			if (resultado[0]["aux_cta"] === "S") {
				$("#id_aux").prop("disabled", false);
			} else {
				listar_aux_ctbles();
				$("#id_aux").prop("disabled", true);
			}
		}
	} catch (err) {
		console.log(err);
	}
}
async function format_almacen() {
	let cod_alm = document.getElementById("cod_alm").value;
	let id_emp = document.getElementById("id_emp").value;
	if (id_emp != "") {
		const datos = new FormData();
		datos.append("id_emp", id_emp);
		try {
			const url = `${base_url}/Almacen/next_codigo`;
			const respuesta = await fetch(url, {
				method: "POST",
				body: datos,
			});
			const resultado = await respuesta.json();
			if (resultado[0]["codigo"] == null) {
				document.getElementById("cod_alm").value = "001";
			} else {
				var $codigo = parseInt(resultado[0]["codigo"]) + 1;
				document.getElementById("cod_alm").value = $codigo
					.toString()
					.padStart(3, "0");
			}
			$("#cod_alm").prop("readonly", true);
		} catch (err) {
			console.log(err);
			document.getElementById("cod_alm").value = " ";
		}
	} else {
		document.getElementById("cod_alm").value = " ";
	}
}
function mascara(o, f) {
	v_obj = o;
	v_fun = f;
	setTimeout("execmascara()", 1);
}
function execmascara() {
	v_obj.value = v_fun(v_obj.value);
}
function cpf(v) {
	v = v.replace(/([^0-9\.]+)/g, "");
	v = v.replace(/^[\.]/, "");
	v = v.replace(/[\.][\.]/g, "");
	v = v.replace(/\.(\d)(\d)(\d)/g, ".$1$2");
	v = v.replace(/\.(\d{1,2})\./g, ".$1");
	v = v
		.toString()
		.split("")
		.reverse()
		.join("")
		.replace(/(\d{3})/g, "$1,");
	v = v.split("").reverse().join("").replace(/^[\,]/, "");
	return v;
}
function print_r(arr, level) {
	var dumped_text = "";
	if (!level) level = 0;
	//The padding given at the beginning of the line.
	var level_padding = "";
	for (var j = 0; j < level + 1; j++) level_padding += "    ";

	if (typeof arr == "object") {
		//Array/Hashes/Objects
		for (var item in arr) {
			var value = arr[item];

			if (typeof value == "object") {
				//If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += print_r(value, level + 1);
			} else {
				dumped_text +=
					level_padding + "'" + item + "' => \"" + value + '"\n';
			}
		}
	} else {
		//Stings/Chars/Numbers etc.
		dumped_text = "===>" + arr + "<===(" + typeof arr + ")";
	}
	return dumped_text;
}
//Format de numeros
function toCurrency(string) {
	return string.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1");
}
function check(obj) {
	//Reemplaxamos la coma por un punto y le asignamos presicion de 4 decimales.
	let val = parseFloat(obj.value.replace(",", ".")).toFixed(2);
	//Aplicamos el formato deseado
	val = toCurrency(val);
	//Actualizamos el valor
	obj.value = val;
}
//Llenar combo de Paises
async function listar_paises(id) {
	const datos = new FormData();
	try {
		const url = `${base_url}/Clientes/listar_paises`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length > 0) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id_pais"]) {
					cadena +=
						"<option selected value='" +
						result[i]["id_pais"] +
						"'>" +
						result[i]["nombre_pais"] +
						"</option>";
				} else {
					cadena +=
						"<option value='" +
						result[i]["id_pais"] +
						"'>" +
						result[i]["nombre_pais"] +
						"</option>";
				}
			}
			var id_pais = $("#id_pais").val();
		} else {
			cadena += "<option value=''>No existen registros</option>";
		}
		//listar_estados(id_pais);
		$("#id_pais").html(cadena);
	} catch (err) {
		console.log(err);
	}
}
// Llenar combo de Estados
// Se pasa el parametro de Id de Pais
async function listar_estados(id_pais, id_edo) {
	let datos = new FormData();
	try {
		if (id_pais) {
			datos.append("id_pais", id_pais);
			let url = `${base_url}/Clientes/listar_estados`;
			let resp = await fetch(url, {
				method: "POST",
				body: datos,
			});
			let result = await resp.json();
			var cadena = "";
			if (result.length > 0) {
				cadena += "<option value = ''>Seleccione...</option>";
				for (var i = 0; i < result.length; i++) {
					if (id_edo && id_edo == result[i]["id_edo"]) {
						cadena +=
							"<option selected value='" +
							result[i]["id_edo"] +
							"'>" +
							result[i]["nombre_edo"] +
							"</option>";
					} else {
						cadena +=
							"<option value='" +
							result[i]["id_edo"] +
							"'>" +
							result[i]["nombre_edo"] +
							"</option>";
					}
				}
				var id_edo = $("#id_edo").val();
			} else {
				cadena += "<option value=''>No existen registros</option>";
			}
		} else {
			cadena += "<option value=''>No existen registros</option>";
		}
		//listar_ciudades(id_edo);
		$("#id_edo").html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Ciudades
//Se pasa el parametro de id de estado
async function listar_ciudades(id_edo, id_ciudad) {
	const datos = new FormData();
	try {
		if (id_edo) {
			datos.append("id_edo", id_edo);
			const url = `${base_url}/Clientes/listar_ciudades`;
			const resp = await fetch(url, {
				method: "POST",
				body: datos,
			});
			const result = await resp.json();
			var cadena = "";
			if (result.length > 0) {
				cadena += "<option value = ''>Seleccione...</option>";
				for (var i = 0; i < result.length; i++) {
					if (id_ciudad && id_ciudad == result[i]["id_ciudad"]) {
						cadena +=
							"<option selected value='" +
							result[i]["id_ciudad"] +
							"'>" +
							result[i]["nombre_ciudad"] +
							"</option>";
					} else {
						cadena +=
							"<option value='" +
							result[i]["id_ciudad"] +
							"'>" +
							result[i]["nombre_ciudad"] +
							"</option>";
					}
				}
			} else {
				cadena += "<option value=''>No existen registros</option>";
			}
		} else {
			cadena += "<option value=''>No existen registros</option>";
		}
		$("#id_ciudad").html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Vendedores
async function listar_vendedores(id, bloquear = false) {
	const datos = new FormData();
	try {
		const url = `${base_url}/Clientes/listar_vendedores`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length > 0) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id_vend"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_vend"] +
						"'>" +
						result[i]["nom_vend"] +
						" " +
						result[i]["ape_vend"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_vend"] +
						"'>" +
						result[i]["nom_vend"] +
						" " +
						result[i]["ape_vend"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value=''>No existen registros</option>";
		}
		$("#id_vend").html(cadena);
		if (bloquear) {
			$("#id_vend").css("pointer-events", "none");
		}
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Empresas
function listar_empresas(id = 0, bloquear = false, label = "Seleccione...", tag = "id_emp") {
	div_loading();
	const datos = new FormData();
	const url = `${base_url}/Empresas/listar_empresas`;
	// 1.- Seleccionar el <select>
	var $miSelect = $(`#${tag}`);
	// 2.- Deshabilita el <select> y muestra cargando
	$miSelect.prop('disable', true);
	$miSelect.empty(); //Vaciar Select
	$miSelect.append($("<option></option>").attr("value", "").text("Cargando..."))
	// 3.- Realziar la solicitud AJAX
	$.ajax({
		url: url,
		method: "POST",
		dataType: 'json',
		beforeSend: function () {
			loader.show();
		},
		complete: function () {
			loader.hide();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.error("Error al cargar las opciones:", textStatus, errorThrown);
			// Opcionalmente, muestra un mensaje de error al usuario
			$miSelect.empty();
			$miSelect.append($("<option></option>").attr("value", "").text("Error al cargar"));
			$miSelect.prop('disabled', true);
		},
		success: function (data) {
			// 4.- Se limpia la opción cargando
			$miSelect.empty();
			// 5.- Cargar el select
			$miSelect.append($("<option></option>").attr("value", "").text("Selecione..."));
			$.each(data, function (key, value) {
				if (value.id_emp == id) {
					$miSelect.append($("<option selected></option>").attr("value", value.id_emp).text(value.nombre_emp));
				} else {
					$miSelect.append($("<option></option>").attr("value", value.id_emp).text(value.nombre_emp));
				}
			});

			// 6.- Habilitar el <select>
			$miSelect.prop('disable', false);

			if (bloquear) {
				$(`#${tag}`).css("pointer-events", "none");
				//$miSelect.prop("readonly", true);
			}
		}
	});
}
//Buscar Tipos de Movimientos de Inventarios
async function listar_InvTipoMov(emp, id = 0, tag = "", tipo = "") {
	const datos = new FormData();
	try {
		datos.append("id_emp", emp);
		if (tipo !== "") {
			datos.append("tipo", tipo);
		}
		const url = `${base_url}/InvTipoMov/listar_InvTipoMov`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length != 0) {
			cadena += "<option value = '0'>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id_tmoinv"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_tmoinv"] +
						"'>" +
						result[i]["nom__tmoinv"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_tmoinv"] +
						"'>" +
						result[i]["nom__tmoinv"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = '0'>No existen registros</option>";
		}
		if (tag) {
			$(`#${tag}`).html(cadena);
		} else {
			$("#id_tmoinv").html(cadena);
		}
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Tipos de Docuemntos CXC
async function listar_tipos_documentos(
	id_emp,
	tipo = "",
	id_tdo = "",
	bloquear = false,
	tag
) {
	const datos = new FormData();
	let arr = tipo.split(",");
	//let arr  = tipo;
	datos.append("id_emp", id_emp);
	datos.append("tipo_tdoc", arr);
	try {
		const url = `${base_url}/TipoDocCXC/listar_tipos_documentos`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length > 0) {
			cadena += "<option value = '0'>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id_tdo == result[i]["id_tdoc"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_tdoc"] +
						"'>" +
						result[i]["nom_tdoc"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_tdoc"] +
						"'>" +
						result[i]["nom_tdoc"] +
						"</option>";
				}

			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		if (tag) {
			$("#" + tag).html(cadena);
		} else {
			$("#id_tdo").html(cadena);
		}
		if (bloquear) {
			$("#id_tdo").css("pointer-events", "none");
		}
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Origines de Tipos de Documentos de CXP
function listar_ori_tipo_doc_cxp(id) {
	const url = `${base_url}/TipoDocCXP/selTipDocCxP`
	$.ajax({
		url: url,
		data: {},
		dataType: 'json',
		beforeSend: function () {
			loader.show();
		},
		complete: function () {
			loader.hide();
		},
		error: function (err) {
			console.log('Se ha presentado el siguietne error', err);
		},
		success: function (data) {
			var selectTDO = $("#tipo_tdoc")
			selectTDO.empty();
			cadena = '';
			$.each(data, function (index, item) {
				if (id == item.id) {
					cadena += `<option selected value = "${item.id}" selected >${item.name}</option>`;
				} else {
					cadena += `<option value = "${item.id}" >${item.name}</option>`;
				}
			});
			selectTDO.append(cadena);
			if (id) {
				selectTDO.css("pointer-events", "none");
			}
		}
	})

}
//Llenar combo de Tipos de Docuemntos de CXP
async function listar_tipos_documentos_cxp(
	id_emp,
	tipo = "",
	id_tdo = "",
	bloquear = false,
	tag
) {
	const datos = new FormData();
	datos.append("id_emp", id_emp);
	datos.append("tipo_tdoc", tipo);
	try {
		const url = `${base_url}/TipoDocCXP/listar_tipos_documentos`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length > 0) {
			cadena += "<option value = '0'>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id_tdo == result[i]["id_tdoc"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_tdoc"] +
						"'>" +
						result[i]["nom_tdoc"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_tdoc"] +
						"'>" +
						result[i]["nom_tdoc"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		if (tag) {
			$("#" + tag).html(cadena);
		} else {
			$("#id_tdo").html(cadena);
		}
		if (bloquear) {
			$("#id_tdo").css("pointer-events", "none");
		}
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Tipos de documentos fuentes
async function listar_tipos_documentos_fuente(id_emp) {
	const datos = new FormData();
	datos.append("id_emp", id_emp);
	try {
		const url = `${base_url}/TipoDocCXC/listar_tipos_documentos_fuente`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length > 0) {
			cadena += "<option value = '0'>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				cadena +=
					"<option value = '" +
					result[i]["id_tdoc"] +
					"'>" +
					result[i]["nom_tdoc"] +
					"</option>";
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#fuente").html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Clientes
async function listar_clientes(tipo, emp = 0, cli = 0, bloquear = false) {
	const datos = new FormData();
	try {
		datos.append("tip_ent", tipo);
		datos.append("id_emp", emp);
		const url = `${base_url}/Clientes/listar_clientes`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length > 0) {
			cadena += "<option value = '0'>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (cli > 0) {
					if (cli == result[i]["id_ent"]) {
						cadena +=
							"<option selected value = '" +
							result[i]["id_ent"] +
							"'>" +
							result[i]["nom_ent"] +
							"</option>";
					} else {
						cadena +=
							"<option value = '" +
							result[i]["id_ent"] +
							"'>" +
							result[i]["nom_ent"] +
							"</option>";
					}
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_ent"] +
						"'>" +
						result[i]["nom_ent"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#id_cli").html(cadena);
		if (bloquear) {
			$("#id_cli").css("pointer-events", "none");
			//$('#id_cli').prop('readonly', true);
			$("#id_cli option:not(:selected)").attr("disabled", true);
		}
	} catch (err) {
		console.log(err);
	}
}
//Cargar modal de Entidades
//
//Llenar combo de Monedas
async function listar_monedas(id, bloquear = false) {
	const datos = new FormData();
	try {
		const url = `${base_url}/Monedas/listar_monedas`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length > 0) {
			cadena += "<option value = ''>Selecione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id_moneda"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_moneda"] +
						"'>" +
						result[i]["nombre_moneda"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_moneda"] +
						"'>" +
						result[i]["nombre_moneda"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#id_moneda").html(cadena);
		if (bloquear) {
			$("#id_moneda").css("pointer-events", "none");
		}
	} catch (err) {
		console.log(err);
	}
} //Lenar combo de Productos
async function listar_productos(nameid, id = 0) {
	const datos = new FormData();
	try {
		const url = `${base_url}/Productos/listar_productos`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length > 0) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id_prod"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_prod"] +
						"'>" +
						result[i]["nom_prod"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_prod"] +
						"'>" +
						result[i]["nom_prod"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + nameid).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Lenar combo de Zonas
async function listar_zonas(id) {
	const datos = new FormData();
	try {
		const url = `${base_url}/Zonas/listar_zonas`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id_zona"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_zona"] +
						"'>" +
						result[i]["nombre_zona"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_zona"] +
						"'>" +
						result[i]["nombre_zona"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#id_zona").html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Lenar combo de Motivos de cambio de facturación
async function listar_motivo_cambio(id) {
	const datos = new FormData();
	try {
		const url = `${base_url}/MotivoCambio/listar_motivo_cambio`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				var adic_01 =
					format_number_with_dec_new(result[i]["adic_01"], 2) + " %";
				if (id && id == result[i]["id_motcam"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_motcam"] +
						"'>" +
						result[i]["nom_motcam"] +
						" - " +
						adic_01 +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_motcam"] +
						"'>" +
						result[i]["nom_motcam"] +
						" - " +
						adic_01 +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#id_motcam").html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Validar formato monto
function financial(x, dec) {
	return Number.parseFloat(x).toFixed(dec);
}
//Llenar combo de Cuentas Contables
async function listar_ctas_ctbles(id, tag) {
	const datos = new FormData();
	try {
		const url = `${base_url}/CuentasCtb/listar_ctas_ctbles`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ' '>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id_cta"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_cta"] +
						"'>" +
						result[i]["cod_cta"].padStart(20) +
						" - " +
						result[i]["nombre_cta"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_cta"] +
						"'>" +
						result[i]["cod_cta"].padStart(20) +
						" - " +
						result[i]["nombre_cta"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		if (tag) {
			$("#" + tag).html(cadena);
		} else {
			$("#id_cta").html(cadena);
		}
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Cuentas Contables
async function listar_aux_ctbles(id = 0, tag, usa = true, mod = "1") {
	const datos = new FormData();
	//datos.append('id_emp', id_emp);
	datos.append("mod", mod);
	let url = "";
	try {
		if (mod == "1") {
			url = `${base_url}/AuxiliarCtb/listar_aux_ctbles`;
		} else if (mod == "CXC") {
			url = `${base_url}/AuxiliarCtb/listar_aux_ctbles_mod`;
		}
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		cadena = "<option value = ''>Seleccione...</option>";
		if (result) {
			if (usa) {
				for (var i = 0; i < result.length; i++) {
					if (id && id == result[i]["id_aux"]) {
						cadena +=
							"<option selected value = '" +
							result[i]["id_aux"] +
							"'>" +
							result[i]["nombre_aux"] +
							"</option>";
					} else {
						cadena +=
							"<option value = '" +
							result[i]["id_aux"] +
							"'>" +
							result[i]["nombre_aux"] +
							"</option>";
					}
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		if (tag) {
			$("#" + tag).html(cadena);
		} else {
			$("#id_aux").html(cadena);
		}
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo Debe/Haber
async function listar_tipoDH(id, tag) {
	var cadena = "";
	var marcard = "";
	var marcarh = "";
	if (id == "D") {
		marcard = "selected";
	} else if (id == "H") {
		marcarh = "selected";
	}
	cadena += "<option value = ''>Seleccione...</option>";
	cadena += "<option " + marcard + " value = 'D'>Debe</option>";
	cadena += "<option " + marcarh + " value = 'H'>Haber</option>";
	$("#" + tag).html(cadena);
}
//Llenar combo de Codigos de Areas de Venezuela
async function listar_codigos_area(codigo = 0, tag) {
	const datos = new FormData();
	try {
		loader.show()
		const url = `${base_url}/Clientes/listar_codigos_area`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id_pre"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_pre"] +
						"'>" +
						result[i]["id_cod_pre"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_pre"] +
						"'>" +
						result[i]["id_cod_pre"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		if (tag) {
			$("#" + tag).html(cadena);
		} else {
			$("#id_pre").html(cadena);
		}
	} catch (err) {
		console.log(err);
	} finally {
		loader.hide();
	}
}
//Llenar combo de Departamentos de Entidad
async function listar_dpto_ent(codigo = 0, tag) {
	const datos = new FormData();
	try {
		const url = `${base_url}/Clientes/listar_dpto_ent`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id_dep"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_dep"] +
						"'>" +
						result[i]["nom_dep"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_dep"] +
						"'>" +
						result[i]["nom_dep"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Status Historicio de precios de Productos
function listar_status_his_precios_productos(codigo = null, campo = null) {
	try {
		var cadena = " ";
		var selectedA = "";
		var selectedB = "";
		var selectedP = "";
		if (codigo && codigo == "A") {
			selectedA = " selected ";
		} else if (codigo && codigo == "V") {
			selectedB = " selected ";
		} else if (codigo && codigo == "P") {
			selectedP = " selected ";
		}
		cadena += "<option value=''>Seleccione...</option>";
		cadena += "<option " + selectedP + " value='P'>En proceso</option>";
		cadena += "<option " + selectedA + " value='A'>Aprobado</option>";
		cadena += "<option " + selectedB + " value='V'>Vigente</option>";
		$("#" + campo).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Marcas
async function listar_marcas(codigo = 0, tag = "id_fab") {
	const datos = new FormData();
	try {
		const url = `${base_url}/Fabricantes/listar_marcas`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id_fab"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_fab"] +
						"'>" +
						result[i]["nom_fab"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_fab"] +
						"'>" +
						result[i]["nom_fab"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Descuentos
async function listar_descuentos(codigo = 0, tag = "id_des") {
	const datos = new FormData();
	try {
		const url = `${base_url}/TipoDcto/listar_descuentos`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id"] +
						"'>" +
						result[i]["codigo_tipdes"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id"] +
						"'>" +
						result[i]["codigo_tipdes"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Grupos
async function listar_grupos(codigo = 0, tag = "id_grupo") {
	const datos = new FormData();
	try {
		const url = `${base_url}/Fabricantes/listar_grupos`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id_grupo"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_grupo"] +
						"'>" +
						result[i]["grupo_nombre"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_grupo"] +
						"'>" +
						result[i]["grupo_nombre"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Llenar combp de Status de Entidad (Clientes)
async function listar_statusEntidad(codigo, tag) {
	const datos = new FormData();
	datos.append("id", codigo);
	try {
		const url = `${base_url}/Clientes/statusEntidad`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id_sta"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_sta"] +
						"'>" +
						result[i]["nom_sta"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_sta"] +
						"'>" +
						result[i]["nom_sta"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Mostar logo en imagen previa al ser seleccionado en el input
function previewImage(event, querySelector) {
	//Recuperamos el input que desencadeno la acción
	const input = event.target;
	//Recuperamos la etiqueta img donde cargaremos la imagen
	imgPreview = document.querySelector(querySelector);
	// Verificamos si existe una imagen seleccionada
	if (!input.files.length) return;
	//Recuperamos el archivo subido
	file = input.files[0];
	//Creamos la url
	objectURL = URL.createObjectURL(file);
	//Modificamos el atributo src de la etiqueta img
	imgPreview.src = objectURL;
	//Crear boton de eliminar imagen
	const btnDelImg = document.createElement("div");
	btnDelImg.type = "button";
	btnDelImg.title = "Eliminar imagén";
	btnDelImg.setAttribute("class", "btn btn-danger");
	btnDelImg.innerHTML = "<i class='fa-sharp fa-solid fa-xmark'></i>";
}
//Eliminar imagenes de forma individual
function deleteimg(div) {
	//Borrar de base de datos
	let id = div.dataset.id;
	let name = div.dataset.name;
	let code = div.dataset.code;
	Swal.fire({
		title: "¿Está usted seguro de eliminar la foto?",
		text: "¡No podrás revertir esto!",
		icon: "question",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "¡Sí, eliminar foto!",
	}).then((result) => {
		if (result.isConfirmed) {
			delimg(id, name, code, div);
		}
	});
}
//Funcion para elimianr una foto de producto
async function delimg(id, name, code, div) {
	const datos = new FormData();
	datos.append("id", id);
	datos.append("ruta", name);
	datos.append("code", code);
	try {
		const url = `${base_url}/Productos/borrarimg`;
		const repuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const resultado = await repuesta;
		if (resultado) {
			div.parentNode.remove();
			//var table = $('#tblTable_prod').DataTable();
			//table.rows().draw();
			Swal.fire({
				title: "Eliminada!",
				text: "La foto ha sido eliminado.",
				icon: "success",
			});

			if (table) {
				$("#modal-showpicture").modal("hide");
				table.destroy();
				table = $("#tblTable_prod").dataTable();
				//$('#tblTable_prod').DataTable();
				//$('#tblTable_prod').DataTable().destroy();
				//$('#tblTable_prod').DataTable().draw();
				//$('#tblTable_prod').DataTable().ajax.reload();
				//cargar_screen_main()
				//table = $('#tblTable_prod').DataTable();
				//table.ajax.reload( true, response )
				//table.ajax.reload();
				//table.clear().draw()
				//table.DataTable({ajax:url});
				//table.ajax.url(url);
				//table.ajax.reload();

				//myTable.clear().rows.add(myTable.data).draw();
				//$('#tblTable_prod').DataTable().clear().draw();
				//var ref = $('#tblTable_prod').DataTable();
				//refresh_tab(ref)
				//window.location.href = `${base_url}/Productos`;
				//table.ajax.reload(null, false);
				//table.ajax.reload();
				//$('#tblTable_prod').DataTable().ajax.reload();
				//$('#tblTable_prod').DataTable()._fnAjaxUpdate();
				//table = $("#tblTable_prod").dataTable().fnDestroy()
				//$('#tblTable_prod').DataTable().ajax.reload();
				//table =$("#tblTable_prod").DataTable({responsive: true});
			}
		}
	} catch (err) {
		Swal.fire({
			title: "Ooops!",
			text:
				"No se pudo eliminar la foto, por favor intente luego. " + err,
			icon: "error",
		});
	}
}
//Mostrar imagenes con la opcion de borrar
function showImageHereFunc(event, input, view) {
	total_img_prod++;
	var total_file = document.getElementById(input).files.length;
	for (var i = 0; i < total_file; i++) {
		var item = i + total_img_prod;
		$("#" + view).append(
			"<div class='card' id='cardimg" +
			item +
			"' name='cardimg" +
			item +
			"' style='display:inline-block;'><img id='img" +
			item +
			"' name='img" +
			item +
			"' width='200px' height='200px' src='" +
			URL.createObjectURL(event.target.files[i]) +
			"' titile=''><button class='btn btn-danger' title='Eliminar foto' onclick='deleteimg(this)'><i class='fa-sharp fa-solid fa-xmark'></i></button></div>"
		);
	}
}

function borrar(div) {
	div.parentNode.remove();
}
//Listar Tipos de Movimientos de Inventarios
async function listar_mov_inv(id_emp, codigo = 0) {
	const datos = new FormData();
	try {
		datos.append("id_emp", id_emp);
		const url = `${base_url}/MovInv/listar_tipos_movin`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id_tmoinv"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_tmoinv"] +
						"'>" +
						result[i]["nom__tmoinv"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_tmoinv"] +
						"'>" +
						result[i]["nom__tmoinv"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#id_tmovinv").html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Listar Almacenes Principal
async function listar_almacenes_ppal(
	id_emp = "",
	id_alm = 0,
	id_alm_exc = 0,
	tag = "id_alm"
) {
	const datos = new FormData();
	datos.append("id_emp", id_emp);
	try {
		const url = `${base_url}/Almacen/listar_almacenes_ppal`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id_alm == result[i]["id_alm"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_alm"] +
						"'>" +
						result[i]["nom_alm"] +
						"</option>";
				} else {
					if (id_alm_exc != 0 && id_alm_exc == result[i]["id_alm"]) {
						cadena +=
							"<option selected value = '" +
							result[i]["id_alm"] +
							"'>" +
							result[i]["nom_alm"] +
							"</option>";
					}
					if (id_alm_exc == 0) {
						cadena +=
							"<option selected value = '" +
							result[i]["id_alm"] +
							"'>" +
							result[i]["nom_alm"] +
							"</option>";
					}
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
async function listar_almacenes(
	id_emp,
	id_alm = 0,
	id_alm_exc = 0,
	tag = "id_alm"
) {
	const datos = new FormData();
	datos.append("id_emp", id_emp);
	try {
		const url = `${base_url}/Almacen/listar_almacenes`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id_alm == result[i]["id_alm"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_alm"] +
						"'>" +
						result[i]["nom_alm"] +
						"</option>";
				} else {
					if (id_alm_exc != 0 && id_alm_exc != result[i]["id_alm"]) {
						cadena +=
							"<option value = '" +
							result[i]["id_alm"] +
							"'>" +
							result[i]["nom_alm"] +
							"</option>";
					} else if (id_alm_exc == 0) {
						cadena +=
							"<option value = '" +
							result[i]["id_alm"] +
							"'>" +
							result[i]["nom_alm"] +
							"</option>";
					}
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Listar Status
function listar_status(id, tag = "status") {
	cadena = "";
	if (id == 1) {
		cadena += "<option selected value='1'>Activo</option>";
		cadena += "<option value='0'>Inactivo</option>";
		cadena += "<option value='1'>Activo</option>'";
		cadena += "<option value='9'>Por aprobar</option>'";
	} else if (id == 0) {
		cadena += "<option selected value='0'>Inactivo</option>";
		cadena += "<option value='0'>Inactivo</option>";
		cadena += "<option value='1'>Activo</option>";
		cadena += "<option value='9'>Por aprobar</option>'";
	} else if (id == 9) {
		cadena += "<option selected value='9'>Por aprobar</option>";
		cadena += "<option value='0'>Inactivo</option>";
		cadena += "<option value='1'>Activo</option>";
		cadena += "<option value='9'>Por aprobar</option>'";
	} else {
		cadena += "<option value='' selected >Seleccione...</option>";
		cadena += "<option value='0'>Inactivo</option>";
		cadena += "<option value='1' >Activo</option>";
		cadena += "<option value='9'>Por aprobar</option>'";
	}
	$("#" + tag).html(cadena);
}
//Tipos de Documentos CxC
function selTipDocCxC(tipo) {
	var cadena = "";
	selected = "";
	selectedF = "";
	selectedP = "";
	selectedC = "";
	selectedB = "";
	selectedN = "";
	selectedR = "";
	selectedD = "";
	selectedE = "";
	selectedZ = "";

	if (tipo == "F") {
		selectedF = "selected";
	} else if (tipo == "P") {
		selectedP = "selected";
	} else if (tipo == "C") {
		selectedC = "selected";
	} else if (tipo == "B") {
		selectedB = "selected";
	} else if (tipo == "N") {
		selectedN = "selected";
	} else if (tipo == "R") {
		selectedR = "selected";
	} else if (tipo == "D") {
		selectedD = "selected";
	} else if (tipo == "E") {
		selectedE = "selected";
	} else if (tipo == "Z") {
		selectedZ = "selected";
	}

	cadena = `<option ${selected} value="">Seleccione..</option>
    <option ${selectedF} value ="F">Factura</option>
    <option ${selectedP} value ="P">Presupuesto</option>
    <option ${selectedC} value ="C">Nota de Crédito</option>
    <option ${selectedB} value ="B">Nota de Débito</option>
    <option ${selectedN} value ="N">Nota de Entrega</option>
    <option ${selectedR} value ="R">Recepción S.T.</option>
    <option ${selectedD} value ="D">Nota de Devolución</option>
    <option ${selectedE} value ="E">Entrega S.T.</option>
    <option ${selectedZ} value ="Z">Nota de Entrega No Fiscal</option>`;

	$("#tipo_tdoc").html(cadena);
}
//Listar Ubicaciones
async function listar_ubicaciones(
	tag = "",
	id_ubi = 0,
	agrupa = "",
	id_emp = ""
) {
	const datos = new FormData();
	datos.append("id_emp", id_emp);
	datos.append("agrupa", agrupa);
	try {
		const url = `${base_url}/Ubicaciones/listar_ubicaciones`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id_ubi && id_ubi == result[i]["id_ubi"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_ubi"] +
						"'>" +
						result[i]["cod_ubi"] +
						" - " +
						result[i]["nom_ubi"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_ubi"] +
						"'>" +
						result[i]["cod_ubi"] +
						" - " +
						result[i]["nom_ubi"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		if (tag) {
			$("#" + tag).html(cadena);
		} else {
			$("#id_ubi").html(cadena);
		}
	} catch (err) {
		console.log(err);
	}
}
//Listar Selects Tipos de Comprobantes Contables
function listar_tipos_comprobantes(id, tag, bloquear = false) {
	const url = `${base_url}/ParametrosCtb/listar_tipos_comprobantes`
	//Ajax para 
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: {},
		dataType: 'json',
		beforeSend: function () {
			loader.show();
		},
		complete: function () {
			loader.hide();
		},
		error: function (PDOException) {
			loader.hide();
			console.log('Ha ocurrido el siguiente error:', PDOException.responseText)
		},
		success: function (data) {
			//Seleccionar el dstino
			var $selectElement = $("#id_tipcom");
			//Limpiar en caso de que este lleno
			$selectElement.empty();
			//Agrgar registro de Seleccione
			$selectElement.append($("<option>", { value: "", text: "Selecione..." }));
			//Cargar data
			$.each(data, function (index, item) {
				$selectElement.append($("<option>", {
					value: item.id_tipcom,
					text: item.nombre_tipcom,
				}));
			});
			if (id) {
				$selectElement.val(id);
			}
			if (bloquear) {
				$(`#${tag}`).css("pointer-events", "none");
			}
		},
	});
}
//Buscar comprobante por defecto
var ttipo_cbte = async function (id, origen, id_tipcom) {
	const datos = new FormData();
	datos.append("id", id);
	datos.append("origen", origen);
	datos.append("id_tipcom", id_tipcom);
	try {
		const url = `${base_url}/Asientos/comp_x_defecto`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var result = await resp.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(result);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
};
//Buscar Cotizaciones pendientes
async function listar_cotizacones(emp, id = 0, tag) {
	const datos = new FormData();
	datos.append("id_emp", emp);
	try {
		const url = `${base_url}/Cotizaciones/listar_cotizacones`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = '0'>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id_tipcom"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_cot"] +
						"'>" +
						result[i]["cliente"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_cot"] +
						"'>" +
						result[i]["cliente"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Buscar Notas de entregas No Fiscales pendientes
async function listar_notas(emp, id = 0, tag, id_cli, fuente = 0) {
	const datos = new FormData();
	datos.append("id_emp", id_emp);
	datos.append("id_cli", id_cli);
	datos.append("fuente", fuente);
	try {
		const url = `${base_url}/Delnotnotfis/listar_notas`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = '0'>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id_tipcom"] || fuente != 0) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_cot"] +
						"'>" +
						result[i]["cliente"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_cot"] +
						"'>" +
						result[i]["cliente"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (error) {
		console.log(error);
	}
}
//Buscar Facturas pendientes para hacer Notas de Creditos
async function listar_fac_facturas(emp, id = 0, tag) {
	const datos = new FormData();
	datos.append("id_emp", emp);
	try {
		const url = `${base_url}/FacNotCre/listar_fac_facturas`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = '0'>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id_tipcom"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_cot"] +
						"'>" +
						result[i]["cliente"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_cot"] +
						"'>" +
						result[i]["cliente"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Listar Status
function listar_agrupador(id, tag) {
	cadena = "";
	if (id == "S") {
		cadena += "<option selected value='S'>Si</option>";
		cadena += "<option value='N'>No</option>";
	} else if (id == "N") {
		cadena += "<option selected value='N'>No</option>";
		cadena += "<option value='S'>Si</option>";
	} else {
		cadena += "<option value=''>Seleccione...</option>";
		cadena += "<option value='S'>Si</option>";
		cadena += "<option value='N'>No</option>";
	}
	$("#" + tag).html(cadena);
}
//Listar Status
function listar_si_no(id, tag) {
	cadena = "";
	if (id == "S") {
		cadena += "<option selected value='S'>Si</option>";
		cadena += "<option value='N'>No</option>";
	} else if (id == "N") {
		cadena += "<option selected value='N'>No</option>";
		cadena += "<option value='S'>Si</option>";
	} else {
		cadena += "<option value=''>Seleccione...</option>";
		cadena += "<option value='S'>Si</option>";
		cadena += "<option value='N'>No</option>";
	}
	$("#" + tag).html(cadena);
}
//Listar proveedores Internacionales
async function listar_proveeint(id = 0, tag) {
	const datos = new FormData();
	try {
		const url = `${base_url}/ComprasInt/listar_proveeint`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id_provint"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_provint"] +
						"'>" +
						result[i]["nombre_provint"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_provint"] +
						"'>" +
						result[i]["nombre_provint"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Listar accion Aumentar y/o Disminuir
function listar_accion(id, tag = "") {
	cadena = "";
	if (id == "A") {
		cadena += "<option selected value='A'>Aumento</option>";
		cadena += "<option value='D'>Disminución</option>";
	} else if (id == "D") {
		cadena += "<option value='D' selected >Disminución</option>";
		cadena += "<option value='A'>Aumento</option>";
	} else {
		cadena += "<option value=''>Seleccione...</option>";
		cadena += "<option value='A'>Aumento</option>";
		cadena += "<option value='D'>Disminución</option>";
	}
	if (tag) {
		$("#" + tag).html(cadena);
	} else {
		$("#acc_bantmo").html(cadena);
	}
}
//Efecto
function listar_efecto(id) {
	cadena = "";
	if (id == "C") {
		cadena += "<option selected value='C'>Cuentas por Cobrar</option>";
		cadena += "<option value='P'>Cuentas por Pagar</option>";
		cadena += "<option value='A'>Aquisición de Divisas</option>";
	} else if (id == "P") {
		cadena += "<option value='P' selected >Cuentas por Pagar</option>";
		cadena += "<option value='C'>Cuentas por Cobrar</option>";
		cadena += "<option value='A'>Aquisición de Divisas</option>";
	} else if (id == "A") {
		cadena += "<option value='A' selected >Aquisición de Divisas</option>";
		cadena += "<option value='P'>Cuentas por Pagar</option>";
		cadena += "<option value='C'>Cuentas por Cobrar</option>";
	} else {
		cadena += "<option value=''>Seleccione...</option>";
		cadena += "<option value='C'>Cuentas por Cobrar</option>";
		cadena += "<option value='P'>Cuentas por Pagar</option>";
		cadena += "<option value='A'>Aquisición de Divisas</option>";
	}
	$("#efe_bantmo").html(cadena);
}
//Llenar combo de Bancos
async function listar_bancos(id = 0) {
	const datos = new FormData();
	try {
		const url = `${base_url}/BanCuentas/listar_bancos`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length != 0) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id_banco"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_banco"] +
						"'>" +
						result[i]["nombre_banco"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_banco"] +
						"'>" +
						result[i]["nombre_banco"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#id_banco").html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Tipos de Movimientos de Bancos
async function listar_tipomov_bancos(id = 0, bloquear = false) {
	const datos = new FormData();
	try {
		const url = `${base_url}/BanTipoMov/listar_tipomov_bancos`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length != 0) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id_bantmo"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_bantmo"] +
						"'>" +
						result[i]["nom_bantmo"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_bantmo"] +
						"'>" +
						result[i]["nom_bantmo"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#id_bantmo").html(cadena);
		if (bloquear) {
			$("#id_bantmo").css("pointer-events", "none");
		}
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Tipos de Cuentas de Bancarias
async function listar_cuentas_ban(id_emp, id = 0, bloquear = false) {
	const datos = new FormData();
	datos.append("id_emp", id_emp);
	try {
		const url = `${base_url}/BanCuentas/listar_cuentas_ban`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length != 0) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id_bancue"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_bancue"] +
						"'>" +
						result[i]["cuenta_bancue"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_bancue"] +
						"'>" +
						result[i]["cuenta_bancue"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#id_bancue").html(cadena);
		if (bloquear) {
			$("#id_bancue").css("pointer-events", "none");
		}
	} catch (err) {
		console.log(err);
	}
}
function GetTodayDate(numday = 0, xdate = "", tipo_fecha = "") {
	if (xdate) {
		var tdate = new Date(xdate);
	} else {
		var tdate = new Date();
	}
	if (numday != 0) {
		tdate.setDate(tdate.getDate() + numday);
	}
	var dd = tdate.getDate(); //yields day
	var MM = tdate.getMonth(); //yields month
	var yyyy = tdate.getFullYear(); //yields year
	MM = MM + 1;
	var n = MM.toString();
	var d = dd.toString();
	if (n.length < 2) {
		MM = "0" + MM;
	}
	if (d.length < 2) {
		dd = "0" + dd;
	}
	if (tipo_fecha == "1") {
		currentDate = dd + "-" + MM + "-" + yyyy;
	} else {
		currentDate = yyyy + "-" + MM + "-" + dd;
	}
	return currentDate;
}
//Busqueda de vendedor desde el combo de clientes 
var tid_vend = async function (id_cli) {
	var datos = new FormData();
	datos.append("id_ent", id_cli);
	try {
		const url = `${base_url}/Clientes/consulta_vend`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
};
//Días de Credito del proveedor
var dias_cre_provee = async function (id_cli) {
	var datos = new FormData();
	datos.append("id_ent", id_cli);
	try {
		const url = `${base_url}/Proveedores/consulta_dias_cre_provee`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
};
var val_aux = async function (id) {
	var datos = new FormData();
	datos.append("id", id);
	try {
		if (id > 0) {
			const url = `${base_url}/CXCDocument/val_aux`;
			var respuesta = await fetch(url, {
				method: "POST",
				body: datos,
			});
			var resultado = await respuesta.json();
			return new Promise((resolve, reject) => {
				setTimeout(() => {
					resolve(resultado);
				}, 1);
			});
		}
	} catch (error) {
		console.log(error);
	}
};
//Buscar tasa de Cambio para cuando el valor sea Bolivares
var xTasa = async function (fecha, moneda) {
	var datos = new FormData();
	datos.append("fecha", fecha);
	datos.append("moneda", moneda);
	try {
		const url = `${base_url}/Cambios/rateExchange`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
};
//Buscar el porcentaje de IVA segun la fecha indicada
var xvatTax = async function (fecha, vatTax) {
	//if (!xtasavatTax_val) {
		var datos = new FormData();
		datos.append("fecha", fecha);
		datos.append("vatTax", vatTax);
		try {
			const url = `${base_url}/VatTax/ratevatTax`;
			var respuesta = await fetch(url, {
				method: "POST",
				body: datos,
			});
			const resultado = await respuesta.json();
			return new Promise((resolve, reject) => {
				setTimeout(() => {
					resolve(resultado);
				}, 1);
			});
		} catch (error) {
			console.log(error);
		}
	//}
};
//Llenar combo de Días de Crédito
async function listar_dias_credito(codigo = 0) {
	const datos = new FormData();
	try {
		const url = `${base_url}/Clientes/listar_dias_credito`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id_diascre"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_diascre"] +
						"'>" +
						result[i]["des_diascre"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_diascre"] +
						"'>" +
						result[i]["des_diascre"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#id_diascre").html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Conceptos de CXC
async function listar_conceptos_CXC(idemp, codigo = 0, tag) {
	const datos = new FormData();
	datos.append("id_emp", id_emp);
	try {
		const url = `${base_url}/CXCDocument/listar_conceptos`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id"] +
						"'>" +
						result[i]["nombre_con"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id"] +
						"'>" +
						result[i]["nombre_con"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Conceptos de Zona Fiscal
async function listar_zona_fiscal(codigo = 0) {
	const datos = new FormData();
	try {
		const url = `${base_url}/Empresas/listar_zona_fiscal`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id_iva"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_iva"] +
						"'>" +
						result[i]["cod_iva"] +
						" - " +
						result[i]["des_iva"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_iva"] +
						"'>" +
						result[i]["cod_iva"] +
						" - " +
						result[i]["des_iva"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#id_iva").html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Tipos de Movimientos de CXC
async function listar_tipos_mov_CXC(
	codigo = 0,
	tag,
	bloquear = false,
	efecto = ""
) {
	const datos = new FormData();
	if (efecto) {
		datos.append("efecto", efecto);
	}
	try {
		const url = `${base_url}/CXCMovement/listar_tipos_mov`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id_tmocxc"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_tmocxc"] +
						"'>" +
						result[i]["des_tmocxc"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_tmocxc"] +
						"'>" +
						result[i]["des_tmocxc"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
		if (bloquear) {
			$("#" + tag).css("pointer-events", "none");
		}
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Tipos de Movimientos de CXP
async function listar_tipos_mov_CXP(
	codigo = 0,
	tag,
	bloquear = false,
	efecto = ""
) {
	const datos = new FormData();
	if (efecto) {
		datos.append("efecto", efecto);
	}
	try {
		const url = `${base_url}/TipoMovCXP/listar_tipos_mov`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id_tmocxc"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_tmocxc"] +
						"'>" +
						result[i]["des_tmocxc"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_tmocxc"] +
						"'>" +
						result[i]["des_tmocxc"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
		if (bloquear) {
			$("#" + tag).css("pointer-events", "none");
		}
	} catch (err) {
		console.log(err);
	}
}
//Formatear campos numericos con sus respectivos separadores y x decimales
function format_number_with_dec_new(monto, dec) {
	//const locales = "es-VE";
	//const options = {
	//		style: "decimal",
	//	minimumFractionDigits: dec
	//};
	//const formatterBs = new Intl.NumberFormat(locales, options);
	//const priceFormatter = formatterBs.format(monto);
	return accounting.formatNumber(monto, dec, ".", ",");

}
//Formatear campos numericos con sus respectivos separadores y x decimales Modificdo para usar el local string
function format_number_with_dec(monto, dec) {
	const locales = "es-VE";
	const options = {
		style: "decimal",
		minimumFractionDigits: dec
	};
	const formatterBs = new Intl.NumberFormat(locales, options);
	const priceFormatter = formatterBs.format(monto);
	return priceFormatter;
}
function format_number_with_out_dec(xmonto) {
	return parseFloat(xmonto.replace(/\./g, "").replace(/,/g, "."));
}
//Busqueda de configuracion de CXC
var show_config_cxc = async function (id_emp) {
	var datos = new FormData();
	datos.append("id_emp", id_emp);
	try {
		const url = `${base_url}/ConfigCXC/show_config_cxc`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
};
//Busqueda de configuracion de CXP
var show_config_cxp = async function (id_emp) {
	var datos = new FormData();
	datos.append("id_emp", id_emp);
	try {
		const url = `${base_url}/ConfigCXP/show_config_cxp`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
};
//Busqueda de Documentos pendinetes de un cliente de CXC
var doc_ped_cli = async function (
	id_emp,
	id_cli,
	fecha_comp,
	tipo_doc = "",
	num_doc = ""
) {
	var datos = new FormData();
	datos.append("id_emp", id_emp);
	datos.append("id_cli", id_cli);
	datos.append("fecha_comp", fecha_comp);
	datos.append("tipo_doc", tipo_doc);
	datos.append("num_doc", num_doc);
	try {
		const url = `${base_url}/CXCDocument/doc_ped_cli`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
};
//Busqueda de un Documentos pendinetes de un cliente de CXC
var doc_ped_cli_one = async function (
	id_emp,
	id_cli,
	fecha_comp,
	tipo_doc,
	num_doc
) {
	var datos = new FormData();
	datos.append("id_emp", id_emp);
	datos.append("id_cli", id_cli);
	datos.append("fecha_comp", fecha_comp);
	datos.append("tipo_doc", tipo_doc);
	datos.append("num_doc", num_doc);
	try {
		const url = `${base_url}/CXCDocument/doc_ped_cli_one`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
};
function redondearDecimales(numero, decimales) {
	numeroRegexp = new RegExp("\\d\\.(\\d){" + decimales + ",}"); // Expresion regular para numeros con un cierto numero de decimales o mas
	if (numeroRegexp.test(numero)) {
		// Ya que el numero tiene el numero de decimales requeridos o mas, se realiza el redondeo
		return Number(numero.toFixed(decimales));
	} else {
		return Number(numero.toFixed(decimales)) === 0 ? 0 : numero; // En valores muy bajos, se comprueba si el numero es 0 (con el redondeo deseado), si no lo es se devuelve el numero otra vez.
	}
}
//Consultar Stock de Producto
async function StockProducto(id, item, id_ent) {
	const datos = new FormData();
	datos.append("id", id);
	datos.append("id_ent", id_ent);
	try {
		const url = `${base_url}/Productos/stockProducto`;
		const respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const resultado = await respuesta.json();
		if (resultado) {
			xStock = resultado["stock"];
			$("#stock" + item).val(xStock);
			$("#stock" + item).prop("title", resultado["stock"]);
			if (xStock == 0) {
				$("#stock" + item).css("background-color", "red");
			}
		}
	} catch (err) {
		console.log(err);
	}
}
//PAra transformar el código HTML a código legible
function decodeEntities(encodedString) {
	var textArea = document.createElement("textarea");
	textArea.innerHTML = encodedString;
	return textArea.value;
}
async function ConsultarOneProducto(
	id_prod,
	id_cli,
	id_emp,
	id_fab,
	fecha_comp,
	id_moneda
) {
	const datos = new FormData();
	datos.append("id_prod", id_prod);
	try {
		const url = `${base_url}/Productos/consulta`;
		const respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const resultado = await respuesta.json();
		if (resultado) {
			$(".loader").show();
			//llenar variables con valores del producto
			nom_prod = decodeEntities(resultado["nom_prod"]);
			cant = 1;
			uni_ven_prod = resultado["uni_ven_prod"];
			xiva = resultado["iva_prod"] = 1 ? "S" : "N";
			stock = resultado["stock"];
			let xventas_prod = 0;
			xventas_prod = resultado["ventas_prod"];
			//Valor adicional fabricante
			noadic = resultado["noadic"];
			if (noadic == 0) {
				const xValAdicionalFAB = await mot_cam_adicional(id_cli);
				xid_fab = resultado["id_fab"];
				if (xValAdicionalFAB) {
					for (x of xValAdicionalFAB) {
						if (xid_fab == x["id_fab"] && x["adicional" > 0]) {
							xventas_prod = xventas_prod / x["adicional"];
						}
					}
				}
			}
			//calcular precio de ventas con los adicionales
			//Adicional 01

			const datosFetched = await xAdditional01(id_emp, fecha_comp);
			//Adicional
			xValorAdic01 = datosFetched["tasa"];
			//Moneda Empresa
			xMonedaCia = datosFetched["id_moneda"];
			//
			if (xMonedaCia == id_moneda) {
				xTasaCambio = await xTasa(fecha_comp, 2);
				xTasaCambioDef = xTasaCambio.replace(",", ".");
				xventas_prod =
					parseFloat(xventas_prod) * parseFloat(xTasaCambioDef);
			}
			//Empresa
			if (noadic == 0) {
				if (xValorAdic01 && xValorAdic01 > 0) {
					if (xMonedaCia == id_moneda) {
						xTasaCambio = await xTasa(fecha_comp, 2);
						xTasaCambioDef = xTasaCambio.replace(",", ".");
						xventas_prod =
							(parseFloat(xventas_prod) /
								parseFloat(xValorAdic01)) *
							parseFloat(xTasaCambioDef);
					}
				}
			}
			//Adicional Cliente
			const datosFetchedCli = await xAdditional02(id_cli);
			if (noadic == 0) {
				//Adicional 01
				xValorAdic02 = datosFetchedCli["adic_01"];
				if (xValorAdic02 && xValorAdic02 > 0) {
					xventas_prod =
						parseFloat(xventas_prod) / parseFloat(xValorAdic02);
				}
				//Adicional 02
				xValorAdic02 = datosFetchedCli["adic_02"];
				if (xValorAdic02 && xValorAdic02 > 0) {
					xventas_prod =
						parseFloat(xventas_prod) +
						parseFloat(xventas_prod) *
						(parseFloat(xValorAdic02) / 100);
				}
			}
			var xVentasxUnidad =
				parseFloat(xventas_prod) / parseFloat(uni_ven_prod);
			//Llenar array
			myConsulproduc = {};
			myConsulproduc = {
				id_prod: id_prod,
				nom_prod: nom_prod,
				cant: 1,
				uni_ven_prod: uni_ven_prod,
				iva: xiva,
				stock: stock,
				xventas_prod: xventas_prod,
				precio_unit: xVentasxUnidad,
			};
		}
		setTimeout(function () {
			$(".loader").hide();
		}, 1);
		return myConsulproduc;
	} catch (error) {
		console.log(error);
	}
}
async function ConsultarProducto(
	id,
	xitem,
	ori = 0,
	tag = "",
	mod = "V",
	consig = 0,
	tipo_fac,
	cant = 1
) {
	let id_prod = id;
	const datos = new FormData();
	datos.append("id_prod", id_prod);
	if (tipo_fac == "NF" && consig == 1) {
		id_alm = "";
	} else {
		datos.append("id_alm", id_alm);
	}
	if (consig == 1) {
		if (id_ubi) {
			datos.append("id_ubi", id_ubi);
		}
		if (id_ubi_consig) {
			id_ubi = id_ubi_consig;
			datos.append("id_ubi", id_ubi);
		}
		if (id_alm_def) {
			if (id_alm_ppal) {
				if (id_alm_ppal == id_alm_def) {
					id_alm = "";
				} else {
					id_alm = id_alm_def;
				}
			}
			datos.append("id_alm", id_alm);
		}
		if (id_ubi_def) {
			if (id_alm_ppal == id_alm_def) {
				id_ubi = "";
			} else {
				id_ubi = id_ubi_def;
			}
			datos.append("id_ubi", id_ubi);
		}
		if (id_alm) {
			datos.append("id_alm", id_alm);
		}
	}
	if (tipo_fac == "P") {
		id_alm = "";
	}
	//datos.append('id_fab', id_fab);
	try {
		//Verificar si es mercansia consignada, para dividir entre el total de unidade sy el pecio de ventas
		var url = "";
		if (tipo_fac == "P") {
			url = `${base_url}/Productos/consulta_presu`;
		} else {
			url = `${base_url}/Productos/consulta`;
		}

		const respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const resultado = await respuesta.json();
		if (resultado) {
			monto = 0;
			noadic = resultado["noadic"];
			console.log(resultado);
			
			//Nombre Producto
			$("#nom_prod" + item).val(resultado["nom_prod"]);
			var nom_prod_encodeStr = (resultado["nom_prod"]);


			$("#nom_prod" + item).val(nom_prod_encodeStr);

			$("#nom_prod" + item).prop("title", resultado["nom_prod"] + ' Marca: ' + resultado["nom_fab"]);

			if (!equivale) {
				$("#cant" + item).val(cant);

				if (mod == "V" || mod == "Z") {
					//if (consig == 1 && tipo_fac == "F") {
					//	uni_ven_prod = 1;
					//} else {
					uni_ven_prod = resultado["uni_ven_prod"];
					//}
					$("#uni_ven_prod" + item).val(uni_ven_prod);
				} else {
					$("#uni_com_prod" + item).val(resultado["uni_com_prod"]);
				}

				if (resultado["iva_prod"] == 1) {
					xiva = "S";
					$("#iva_prod" + item).val("S");
				} else {
					xiva = "N";
					$("#iva_prod" + item).val("N");
				}
				if (tipo_fac == "NF") {
					xiva = "N";
					$("#iva_prod" + item).val("N");
				}				
				//Stock del Producto

				$("#stock" + item).val(resultado["stock"]);
				//$(".stock").trigger( "change" );

				let xtotal = 0;
				if ((mod == "V" || mod == "Z") && origen_COM == 0) {
					//Precio base del producto de Ventas
					let xventas_prod = 0;
					xventas_prod = resultado["ventas_prod"];
					if (consig == 1 && tipo_fac == "F") {
						//Error detectado por Alejandra, no etsba calculando bien el precio del producto cuando es consignado
						//xventas_prod = resultado['ventas_prod'] / uni_ven_prod;
						xventas_prod = resultado["ventas_prod"] / resultado["uni_ven_prod"];
						if (c_consig == 1) {
							xventas_prod = resultado["venta2_prod"];
							xventas_prod = xventas_prod / resultado["conv_prod_cons"];
							if (xventas_prod == 0) {
								xventas_prod = resultado["ventas_prod"] / resultado["uni_ven_prod"];
							}
						}
					}

					//Valor adicional por el fabricante
					if (id_cli) {
						if (noadic == 0) {
							const xValAdicionalFAB = await mot_cam_adicional(
								id_cli
							);
							xid_fab = resultado["id_fab"];
							if (xValAdicionalFAB) {
								for (x of xValAdicionalFAB) {
									if (
										xid_fab == x["id_fab"] &&
										x["adicional"] > 0
									) {
										xventas_prod =
											xventas_prod / x["adicional"];
									}
								}
							}
						}
					}

					//Calcular precio de Venta con los adicionales
					id_empr = $("#id_emp").val();
					fecha_comp = $("#fecha_comp").val();

					//No calcular adicional segun el Fabricante
					//

					const datosFetched = await xAdditional01(
						id_empr,
						fecha_comp
					);

					//Valor adicional
					xValorAdic01 = datosFetched["tasa"];
					//Moneda empresa
					xMonedaCia = datosFetched["id_moneda"];
					//Moneda Cotización
					id_moneda = $("#id_moneda").val();

					if (xMonedaCia == id_moneda) {
						fecha_comp = $("#fecha_comp").val();
						xTasaCambio = await xTasa(fecha_comp, 2);
						xTasaCambioDef = xTasaCambio.replace(",", ".");
						//xventas_prod =  resultado['ventas_prod']
						//xventas_prod = parseFloat(xventas_prod) * parseFloat(xTasaCambioDef);
						xventas_prod = parseFloat(xventas_prod);
					}

					//Valor Adicional por empresa
					if (noadic == 0) {
						if (xValorAdic01 && xValorAdic01 > 0) {
							if (xMonedaCia == id_moneda) {
								fecha_comp = $("#fecha_comp").val();
								xTasaCambio = await xTasa(fecha_comp, 2);
								xTasaCambioDef = xTasaCambio.replace(",", ".");
								//xventas_prod =  resultado['ventas_prod']
								//xventas_prod =(parseFloat(xventas_prod) / parseFloat(xValorAdic01)) * parseFloat(xTasaCambioDef);
								xventas_prod =
									parseFloat(xventas_prod) /
									parseFloat(xValorAdic01);
							}
						}
					}
					//Valores Adcionales
					id_cli = $("#id_cli").val();
					if (id_cli) {
						const datosFetchedCli = await xAdditional02(id_cli);
						if (noadic == 0) {
							//Valor Adicional por cliente 01
							xValorAdic02 = datosFetchedCli["adic_01"];
							if (xValorAdic02 && xValorAdic02 > 0) {
								xventas_prod = xventas_prod / xValorAdic02;
							}
							//Valor Adicional por cliente 02
							xValorAdic02 = datosFetchedCli["adic_02"];
							if (xValorAdic02 && xValorAdic02 > 0) {
								xventas_prod =
									parseFloat(xventas_prod) +
									parseFloat(xventas_prod) *
									(parseFloat(xValorAdic02) / 100);
							}
						}
					}

					if (xMonedaCia == id_moneda) {
						xventas_prod =
							parseFloat(xventas_prod) *
							parseFloat(xTasaCambioDef);
					}

					var xVentasxUnidad =
						parseFloat(xventas_prod) / parseFloat(uni_ven_prod);
					var formato = format_number_with_dec_new(xVentasxUnidad, 2);

					$("#ventas_prod" + item).val(formato);

					xventas_prod1 = format_number_with_dec_new(xventas_prod, 2);
					$("#ventas_prod1" + item).val(xventas_prod1);

					xtotal = $("#cant" + item).val() * xventas_prod;
					$("#total" + item).val(
						format_number_with_dec_new(xtotal, 2)
					);
					//Autofocus
					$("#cant" + item)
						.show()
						.focus();
					//$(".reCalcular").trigger("change");
				} else {
					//Precio Compra

					id_emp = $("#id_emp").val();
					fecha_comp = $("#fecha_comp").val();
					if (!fecha_comp) {
						fecha_comp = $("#fecha_comint").val();
					}

					const datosFetched = await xAdditional01(
						id_emp,
						fecha_comp
					);

					if (tipo_fac != 'OI') {
						//Modificado por Jose Vargas el 19-02-2026 a las 14:22 paa validar si usa o no lote
						$lote = resultado["lote_prod"];
						if ($lote == 0) {
							$(`#lote${item}`).val("SL");
							$(`#fec_venc${item}`).val('0000-00-00');
							$(`#lote${item}`).css("pointer-events", "none");
							$(`#fec_venc${item}`).css("pointer-events", "none");
						}
						//Valor
						//Moneda empresa
						xMonedaCia = datosFetched["id_moneda"];
						//Moneda Cotización
						id_moneda = $("#id_moneda").val();
						xTasaCambioDef = 1;
						//Precio base del producto de Compras
						let xcompras_prod = 0;
						xcompras_prod = resultado["costo_prod"];
						//
						if (xMonedaCia == id_moneda) {
							fecha_comp = $("#fecha_comp").val();
							if (!fecha_comp) {
								fecha_comp = $("#fecha_comint").val();
							}
							xTasaCambio = await xTasa(fecha_comp, 2);
							xTasaCambioDef = xTasaCambio.replace(",", ".");
							//xventas_prod =  resultado['ventas_prod']
							xcompras_prod = parseFloat(xcompras_prod) * parseFloat(xTasaCambioDef);
						}
						var xComprasxUnidad = xcompras_prod / resultado["uni_com_prod"];
						var formato = format_number_with_dec_new(xComprasxUnidad, 2);
						$("#uni_com_prod" + item).val(resultado["uni_com_prod"]);
						$("#costo_prod" + item).val(formato);
						xcompras_prod1 = format_number_with_dec_new(xcompras_prod, 2);
						$("#costo_prod1" + item).val(xcompras_prod1);
						xtotal = $("#cant" + item).val() * xcompras_prod;
						$("#total" + item).val(format_number_with_dec_new(xtotal, 2));
					} else {
						//Compras Internacionales
						//Referencia
						$(`#ref_prod${item}`).val(resultado['ref_prod']);
						//Marca
						$(`#nom_fab${item}`).val(resultado['nom_fab'])
						//Unidad de Empaque
						$(`#nom_pre${item}`).val(resultado['bultos']);
						//Precio
						$(`#precio${item}`).val(format_number_with_dec_new(resultado['costo_prod'], 4));
						precio = resultado['costo_prod']
						//Unidad de Venta
						$(`#total_uni${item}`).val(resultado['uni_ven_prod']);
						uni_vta_gbl = resultado['uni_ven_prod']
						//Precio Unitario
						pre_uni = format_number_with_dec_new(precio / uni_vta_gbl, 4)
						$(`#precio_uni${item}`).val(pre_uni);

					}


				}
				//Title
				$("#cod_prod" + item).prop(
					"title",
					$("#cod_prod" + item).val()
				);
				$("#cant" + item).prop("title", $("#cant" + item).val());
				$("#uni_ven_prod" + item).prop(
					"title",
					$("#uni_ven_prod" + item).val()
				);
				$("#ventas_prod" + item).prop(
					"title",
					$("#ventas_prod" + item).val()
				);
				$("#ventas_prod1" + item).prop(
					"title",
					$("#ventas_prod1" + item).val()
				);
				$("#iva_prod" + item).prop(
					"title",
					$("#iva_prod" + item).val()
				);
				$("#total" + item).prop(
					"title",
					format_number_with_dec_new(xtotal, 2)
				);

				tasa_cambio = $("#tasa_cambio").val();
				tasa_cambio_Val = 1;
				if (tasa_cambio) {
					tasa_cambio_Val = formatoMoneda(tasa_cambio);
				}
				val_col = 0;
				if (tipo_fac == "C") {
					val_vol = -1;
				}

				if (origen_COM == 0) {
					if (ori == 0 && mod == "Z") {
						recorreTable_fac(val_col, tasa_cambio_Val, tipo_fac);
					} else if (ori != " " && mod == "V") {
						recorreTable_fac(val_col, tasa_cambio_Val, tipo_fac);
					} else {
						recorreTable_fac(val_col, tasa_cambio_Val, tipo_fac);
					}
				} else {
					recorreTable_com(tasa_cambio_Val);
				}
			}
			//return myConsulproduc;
		}
	} catch (err) {
		console.log(err);
	}
}
var mot_cam_adicional = async function (id_cli) {
	var datos = new FormData();
	datos.append("id", id_cli);
	try {
		const url = `${base_url}/Clientes/consulta_motivo`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
};
async function recorreTable_cot(tag) {
	subTotal = 0.0;
	iva = 0.0;
	xbase = 0.0;
	x_iva = "";
	xtable = "#" + tag + " tr";
	//Recorrer tabla dinamica
	$("#cuerpoTablaDetalle tr").each(function (rowIndex) {
		$(this)
			.find("td")
			.each(function (cellIndex) {
				if (cellIndex == 6) {
					x_iva = $(this).find(":selected").val();
				}
				if (cellIndex == 7) {
					xx = $(this).find("input[type=text]").val();
					xx1 = xx.replace(".", "");
					xx2 = xx.replace(",", ".");
					x_base = parseFloat(xx2);
					if (x_iva && x_iva == "S") {
						xbase = parseFloat(xbase) + parseFloat(x_base);
					}
					subTotal = subTotal + x_base;
				}
			});
	});
	//Calcular el IVA
	if (xbase != 0) {
		if (!xtasavatTax_val) {
			fecha = $("#fecha_comp").val();
			xtasavatTax = await xvatTax(fecha, "IVA");
			xtasaIVA = parseFloat(xtasavatTax[0]["txr1_iva"]);
			iva = parseFloat(xbase * (xtasaIVA / 100));
			xtasavatTax_val = true;
		}
	}
	$("#sub_total").val(format_number_with_dec_new(subTotal, 2));
	$("#iva").val(format_number_with_dec_new(iva, 4));
	const xtotal_form = subTotal + iva;
	$("#total_frm").val(format_number_with_dec_new(xtotal_form, 2));
}
async function recorreTable_fac(verstock = 0, tasa_cambio = 1, tipo) {
	if (!xtasavatTax_val) {
		fecha = $("#fecha_comp").val();
		xtasavatTax = await xvatTax(fecha, "IVA");
		xtasaIVA = parseFloat(xtasavatTax[0]["txr1_iva"]);
		xtasavatTax_val = true;
	}
	subTotal = 0.0;
	iva = 0.0;
	xbase = 0.0;
	xtotal_form = 0.0;
	if (tipo == "C") {
		verstock = 1;
	} else if (tipo == "F" || tipo == "N" || tipo == "NF") {
		verstock = 1;
	}
	//Recorrer tabla dinamica
	$("#tblDetalle tbody tr").each(function () {
		var xIVA = $(this).find(".input-iva").val();
		var xmonto = 0;
		var xmonto_str = $(this).find(".input-fila").val();
		if (xmonto_str) {
			xmonto = formatoMoneda(xmonto_str);
			if (!isNaN(xmonto)) {
				subTotal += parseFloat(xmonto * accion) ;
			}
			if (xIVA == "S") {
				xbase += parseFloat(xmonto * accion);
			}
		}
	});
	//Si es en dolares mostrar el contravalor en Bs
	xtasa = tasa_cambio;
	//Calcular el IVA

	if (xbase != 0) {
		iva = parseFloat(xbase * (xtasaIVA / 100));
	}
	xtotal_form = subTotal + iva;
	if (xtasa > 1) {
		$("#sub_total").val(format_number_with_dec_new(subTotal, 2));
		$("#iva").val(format_number_with_dec_new(iva, 2));
		$("#total_frm").val(format_number_with_dec_new(xtotal_form, 2));
		//
		$("#sub_totalBs").val(format_number_with_dec_new(subTotal * xtasa, 2));
		$("#ivaBs").val(format_number_with_dec_new(iva * xtasa, 2));
		$("#total_frmBs").val(
			format_number_with_dec_new(xtotal_form * xtasa, 2)
		);
	} else {
		$("#sub_totall").val(format_number_with_dec_new(subTotal, 2));
		$("#ival").val(format_number_with_dec_new(iva, 2));
		$("#total_frml").val(format_number_with_dec_new(xtotal_form, 2));
	}
}
//Formato de Número
function number_format(amount, decimals) {
	amount += ""; // por si pasan un numero en vez de un string
	amount = parseFloat(amount.replace(/[^0-9\.]/g, "")); // elimino cualquier cosa que no sea numero o punto
	decimals = decimals || 0; // por si la variable no fue fue pasada
	// si no es un numero o es igual a cero retorno el mismo cero
	if (isNaN(amount) || amount === 0) return parseFloat(0).toFixed(decimals);
	// si es mayor o menor que cero retorno el valor formateado como numero
	amount = "" + amount.toFixed(decimals);
	var amount_parts = amount.split("."),
		regexp = /(\d+)(\d{3})/;
	while (regexp.test(amount_parts[0]))
		amount_parts[0] = amount_parts[0].replace(regexp, "$1" + "," + "$2");
	return amount_parts.join(".");
}
//Function que evalua si esta vacio un valor
function isEmpty(el) {
	return !$.trim(el);
}
//Busqueda de documento por defecto para facturacion
var tip_doc_fac = async function (id_emp) {
	var datos = new FormData();
	datos.append("id_emp", id_emp);
	try {
		const url = `${base_url}/Facturacion/tip_doc_fac`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return resultado;
	} catch (err) {
		console.log(err);
	}
};
//Llenar combo de Conceptos de CXP
async function listar_conceptos_CXP_EXC(codigo = 0, tag) {
	const datos = new FormData();
	//datos.append('id_emp', id_emp);
	try {
		const url = `${base_url}/ConcepCXP/listar_conceptos`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id"] +
						"'>" +
						result[i]["nombre_con"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id"] +
						"'>" +
						result[i]["nombre_con"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
async function listar_conceptos_CXP(codigo = 0, tag) {
	const datos = new FormData();
	//datos.append('id_emp', id_emp);
	try {
		const url = `${base_url}/ConcepCXP/listar_conceptos_exc`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id"] +
						"'>" +
						result[i]["nombre_con"] +
						"</option>";
				} else if (result[i]["active"] != 0) {
					cadena +=
						"<option disabled value = '" +
						result[i]["id"] +
						"'>" +
						result[i]["nombre_con"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id"] +
						"'>" +
						result[i]["nombre_con"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Tipos de Docuemntos CXP
async function listar_tipos_documentos_CXP(
	id_emp,
	tipo = "",
	id_tdo = "",
	bloquear = false,
	tag
) {
	const datos = new FormData();
	datos.append("id_emp", id_emp);
	datos.append("tipo_tdoc", tipo);
	try {
		const url = `${base_url}/TipoDocCXP/listar_tipos_documentos`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length > 0) {
			cadena += "<option value = '0'>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id_tdo == result[i]["id_tdoc"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_tdoc"] +
						"'>" +
						result[i]["nom_tdoc"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_tdoc"] +
						"'>" +
						result[i]["nom_tdoc"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		if (tag) {
			$("#" + tag).html(cadena);
		} else {
			$("#id_tdo").html(cadena);
		}
		if (bloquear) {
			if (tag) {
				$("#" + tag).css("pointer-events", "none");
			} else {
				$("#id_tdo").css("pointer-events", "none");
			}

		}
	} catch (err) {
		console.log(err);
	}
}
//Busqueda de documento por defecto para comrpas
var tip_doc_com = async function (id_emp) {
	var datos = new FormData();
	datos.append("id_emp", id_emp);
	try {
		const url = `${base_url}/PurInv/tip_doc_com`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return resultado;
	} catch (err) {
		return false;
	}
};
//Busqueda de Datos de proveedor
async function dat_provee(id_cli) {
	var datos = new FormData();
	datos.append("id_ent", id_cli);
	try {
		const url = `${base_url}/Proveedores/consulta_dias_cre_provee`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
}
async function recorreTable_com(tasa_cambio = 1) {
	subTotal = 0.0;
	iva = 0.0;
	xbase = 0.0;
	//Recorrer tabla dinamica
	$("#tblDetalle tr").each(function (rowIndex) {
		$(this)
			.find("td")
			.each(function (cellIndex) {
				if (cellIndex == 8) {
					x_iva = $(this).find(":selected").val();
				}
				if (cellIndex == 9) {
					xbase_text = $(this).find("input[type=text]").val();
					if (xbase_text) {
						//xbase_text1 = xbase_text.replace(".", "");
						//xbase_text2 = xbase_text1.replace(",", ".");
						xbase_text2 = formatoMoneda(xbase_text)
					} else {
						xbase_text2 = xbase_text;
					}
					x_base = parseFloat(xbase_text2);
					if (x_iva == "S") {
						xbase = parseFloat(xbase) + parseFloat(x_base);
					}
					subTotal = subTotal + x_base;
				}
			});
	});
	//Calcular el IVA
	if (isNaN(tasa_cambio)) {
		tasa_cambio = formatoMoneda(tasa_cambio);
	}
	xtasa = parseFloat(tasa_cambio);
	if (xbase != 0) {
		if (xtasavatTax_val){
			fecha = $("#fecha_comp").val();
			xtasavatTax = await xvatTax(fecha, "IVA");
			xtasaIVA = parseFloat(xtasavatTax[0]["txr1_iva"]);
			iva = parseFloat(xbase * (xtasaIVA / 100));
			xtasavatTax_val = true;
		}
		
	}
	const xtotal_form = subTotal + iva;

	if (xtasa > 1) {
		$("#sub_total").val(format_number_with_dec_new(subTotal, 2));
		$("#iva").val(format_number_with_dec_new(iva, 4));
		$("#total_frm").val(format_number_with_dec_new(xtotal_form, 2));
		//
		$("#sub_totalBs").val(format_number_with_dec_new(subTotal * xtasa, 2));
		$("#ivaBs").val(format_number_with_dec_new(iva * xtasa, 2));
		$("#total_frmBs").val(
			format_number_with_dec_new(xtotal_form * xtasa, 2)
		);
	} else {
		$("#sub_totall").val(format_number_with_dec_new(subTotal, 2));
		$("#ival").val(format_number_with_dec_new(iva, 2));
		$("#total_frml").val(format_number_with_dec_new(xtotal_form, 2));
	}
}
//Obtener la Tasa de Cambio de una moneda a una fecha
async function getExchangerate(fecha, moneda) {
	const xTasaCambio = await xTasa(fecha, moneda);
	return xTasaCambio;
}
//Mostar u Ocultar DIV
function muestra_oculta(id) {
	if (id) {
		//se obtiene el id
		var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
		el.style.display = el.style.display == "none" ? "block" : "none"; //damos un atributo display:none que oculta el div
	}
}
/*Módulo de Nóminas*/
//Listar tipo Asignacion y/o Deducción - NOM
function listar_tipo_asi_ded(id, tag = "") {
	cadena = "";
	if (id == "A") {
		cadena += "<option selected value='A'>Asignación</option>";
		cadena += "<option value='D'>Deducción</option>";
	} else if (id == "D") {
		cadena += "<option value='D' selected >Deducción</option>";
		cadena += "<option value='A'>Asignación</option>";
	} else {
		cadena += "<option value=''>Seleccione...</option>";
		cadena += "<option value='A'>Asignación</option>";
		cadena += "<option value='D'>Deducción</option>";
	}
	$("#" + tag).html(cadena);
}
//Listar Parámetros - NOM
function listar_param_nom(id, tag = "") {
	cadena = "";
	if (id == "B") {
		//Boívares
		cadena += "<option selected value='B'>Bolívares</option>";
		cadena += "<option value='P'>Porcentaje</option>";
		cadena += "<option value='C'>Cantidad</option>";
		cadena += "<option value='H'>Horas hombre</option>";
		cadena += "<option value='D'>Días</option>";
		cadena += "<option value='$'>Dolar</option>";
	} else if (id == "P") {
		//Porcentaje
		cadena += "<option value='P' selected >Porcentaje</option>";
		cadena += "<option value='B'>Bolivares</option>";
		cadena += "<option value='C'>Cantidad</option>";
		cadena += "<option value='H'>Horas hombre</option>";
		cadena += "<option value='D'>Días</option>";
		cadena += "<option value='$'>Dolar</option>";
	} else if (id == "C") {
		//Cantidad
		cadena += "<option value='C' selected >Cantidad</option>";
		cadena += "<option value='P'>Porcentaje</option>";
		cadena += "<option value='B'>Bolivares</option>";
		cadena += "<option value='H'>Horas hombre</option>";
		cadena += "<option value='D'>Días</option>";
		cadena += "<option value='$'>Dolar</option>";
	} else if (id == "H") {
		//Horas Hombre
		cadena += "<option value='H' selected >Horas hombre</option>";
		cadena += "<option value='P'>Porcentaje</option>";
		cadena += "<option value='C'>Cantidad</option>";
		cadena += "<option value='B'>Bolívares</option>";
		cadena += "<option value='D'>Días</option>";
		cadena += "<option value='$'>Dolar</option>";
	} else if (id == "D") {
		//Días
		cadena += "<option value='D' selected >Días</option>";
		cadena += "<option value='P'>Porcentaje</option>";
		cadena += "<option value='C'>Cantidad</option>";
		cadena += "<option value='H'>Horas hombre</option>";
		cadena += "<option value='B'>Bolívares</option>";
		cadena += "<option value='$'>Dolar</option>";
	} else if (id == "$") {
		//Dolar
		cadena += "<option value='$' selected >Dolar</option>";
		cadena += "<option value='P'>Porcentaje</option>";
		cadena += "<option value='C'>Cantidad</option>";
		cadena += "<option value='H'>Horas hombre</option>";
		cadena += "<option value='D'>Días</option>";
		cadena += "<option value='B'>Bolívares</option>";
	} else {
		cadena += "<option value=''>Seleccione...</option>";
		cadena += "<option value='B'>Bolívares</option>";
		cadena += "<option value='P'>Porcentaje</option>";
		cadena += "<option value='C'>Cantidad</option>";
		cadena += "<option value='H'>Horas hombre</option>";
		cadena += "<option value='D'>Días</option>";
		cadena += "<option value='$'>Dolar</option>";
	}
	$("#" + tag).html(cadena);
}
//Frecuencia de Nómina
function listar_frec_nom(id = "", tag = "", bloquear = false) {
	var cadena = "";
	if (id == "S") {
		//Semanal
		cadena += '<option value = "S" selected>Semanal</option>';
		cadena += '<option value = "Q">Quincenal</option>';
		cadena += '<option value = "M">Mensual</option>';
	} else if (id == "Q") {
		//Quincenal
		cadena += '<option value = "S">Semanal</option>';
		cadena += '<option value = "Q" selected>Quincenal</option>';
		cadena += '<option value = "M">Mensual</option>';
	} else if (id == "M") {
		//Mensual
		cadena += '<option value = "S">Semanal</option>';
		cadena += '<option value = "Q">Quincenal</option>';
		cadena += '<option value = "M" selected>Mensual</option>';
	} else {
		//Seleccione
		cadena += '<option value = "">Selecione..</option>';
		cadena += '<option value = "S">Semanal</option>';
		cadena += '<option value = "Q">Quincenal</option>';
		cadena += '<option value = "M">Mensual</option>';
	}
	if (tag) {
		$("#" + tag).html(cadena);
		if (bloquear) {
			$("#" + tag).css("pointer-events", "none");
		}
	} else {
		$("#freq").html(cadena);
		if (bloquear) {
			$("#freq").css("pointer-events", "none");
		}
	}
}
//Frecuencia de Nómina
function listar_tipo_nom(id = "", tag = "", bloquear = false) {
	var cadena = "";
	if (id == "E") {
		//Empleados
		cadena += '<option value = "E" selected>Empleados</option>';
		cadena += '<option value = "H">Honorarios</option>';
		cadena += '<option value = "O">Obreros</option>';
	} else if (id == "H") {
		//Honorarios
		cadena += '<option value = "E">Empleados</option>';
		cadena += '<option value = "H" selected>Honorarios</option>';
		cadena += '<option value = "O">Obreros</option>';
	} else if (id == "O") {
		//Obreros
		cadena += '<option value = "E">Empleados</option>';
		cadena += '<option value = "H">Honorarios</option>';
		cadena += '<option value = "O" selected>Obreros</option>';
	} else {
		//Seleccione
		cadena += '<option value = "">Selecione..</option>';
		cadena += '<option value = "E">Empleados</option>';
		cadena += '<option value = "H">Honorarios</option>';
		cadena += '<option value = "O">Obreros</option>';
	}
	if (tag) {
		$("#" + tag).html(cadena);
		if (bloquear) {
			$("#" + tag).css("pointer-events", "none");
		}
	} else {
		$("#tipo").html(cadena);
		if (bloquear) {
			$("#tipo").css("pointer-events", "none");
		}
	}
}
//Pantallas Modales
//Modal para Buscar Cuentas Contables
$(document).on("click", ".nom_ctb", function (e) {
	const url = `${base_url}/CuentasCtb/modal_CuentasCtb`;
	$.ajax({
		url: url,
		datatype: "json",
		success: function (response) {
			response_new = JSON.parse(response);
			$("#tblModal_cuentasCTB").DataTable().clear();
			$("#tblModal_cuentasCTB").DataTable().destroy();
			var tblModal_cuentasCTB = new DataTable("#tblModal_cuentasCTB", {
				aProcessing: true,
				aServerSide: true,
				columnDefs: [
					{
						targets: 0,
						visible: false,
						searchable: false,
					},
				],
				fnCreatedRow: function (rowEl, response_new) {
					$(rowEl).attr("id", response_new[0]);
				},
				order: [[1, "asc"]],
				language: {
					url: `${base_url}/Assets/json/es-ES.json`,
				},
			});
			response_new.forEach(function (e) {
				tblModal_cuentasCTB.row
					.add([
						e.id_cta,
						e.cod_cta,
						e.nombre_cta,
						e.agrupa_cta,
						e.aux_cta,
						e.tip_cta,
					])
					.draw();
			});
			$("#modal-CuentasCtb").modal("show");
		},
	});
});
//Seleccionar registro marcado del Modal de Cuentas Contables y mostrarlo en el formulario
$("body").on("click", "#tblModal_cuentasCTB tr", function () {
	id_ctb = $(this).attr("id");
	if (targetInputId) {
		$(".id_ctb").trigger("change");
	} else {
		if (item) {
			$("#id_ctb" + item).val(id_ctb);
			$("#id_aux" + item).val("");
			$("#nom_aux" + item).val("");
			$(".id_ctb").trigger("change");
		} else {
			$("#id_aux").val("");
			$("#nom_aux").val("");
			$("#id_ctb").val(id_ctb);
			$("#id_ctb").trigger("change");
		}
	}
	$("#modal-CuentasCtb").modal("hide");
	$("#tblModal_cuentasCTB").DataTable().clear();
	$("#tblModal_cuentasCTB").DataTable().destroy();
});
$(document).on("change", "#id_ctb", async function (e) {

	var nom_ctb = await nom_ctb_fun(id_ctb);
	var nombre = decodeHTMLEntities(
		nom_ctb["cod_cta"] + " - " + nom_ctb["nombre_cta"]
	);
	$("#nom_ctb").val(nombre);
	$("#nom_aux").val("");
	aux_cta = nom_ctb["aux_cta"];

	if (aux_cta == "S") {
		$("#id_aux").val("");
		$("#nom_aux").val("");
		//$(".div_aux").css("display", "block");
		$('.div_aux').show()
		$('#div_aux' + item).show()
	} else {
		//$(".div_aux").css("display", "none");
		$('.div_aux').hide()
		$('#div_aux' + item).hide()
	}
});
$(document).on("change", ".id_ctb", async function (e) {
	var nom_ctb = await nom_ctb_fun(id_ctb);
	var nombre = decodeHTMLEntities(nom_ctb["cod_cta"] + " - " + nom_ctb["nombre_cta"])
	if (targetInputId) {
		$('#' + targetInputId).val(id_ctb);
		$('#' + targetInputName).val(nombre);
	} else {
		$("#nom_ctb" + item).val(nombre);
		aux_cta = nom_ctb["aux_cta"];
		if (aux_cta == "S") {
			$('.div_aux').show()
			$('#div_aux' + item).show()
			//$(".div_aux").css("display", "block");
		} else {
			//$(".div_aux").css("display", "none");
			$('#div_aux' + item).hide()
		}
	}
});
//Busqueda de Cuentas Contables
var nom_ctb_fun = async function (id_ctb) {
	var datos = new FormData();
	datos.append("id_ctb", id_ctb);
	try {
		const url = `${base_url}/CuentasCtb/nom_ctb`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
};

//Modal para Buscar Conceptos de Nómina
$("#modal_ConceptosNom").on("show.bs.modal", function (e) {
	// Capturamos el valor del atributo data-target-id y lo guardamos en la variable global

	const url = `${base_url}/NomCon/modal_ConceptosNOM`;
	$.ajax({
		url: url,
		datatype: "json",
		success: function (response) {
			response_new = JSON.parse(response);
			$("#tblModal_conceptos_Nom").DataTable().clear();
			$("#tblModal_conceptos_Nom").DataTable().destroy();
			var tblModal_conceptos_Nom = new DataTable(
				"#tblModal_conceptos_Nom",
				{
					aProcessing: true,
					aServerSide: true,
					columnDefs: [
						{
							targets: 0,
							visible: false,
							searchable: false,
						},
					],
					fnCreatedRow: function (rowEl, response_new) {
						$(rowEl).attr("id", response_new[0]);
					},
					order: [[1, "asc"]],
					language: {
						url: `${base_url}/Assets/json/es-ES.json`,
					},
				}
			);
			response_new.forEach(function (e) {
				tblModal_conceptos_Nom.row
					.add([
						e.id_nomcue,
						e.codigo,
						e.nombre,
						e.tipo,
						e.parametro,
						format_number_with_dec_new(e.factop, 2),
					])
					.draw();
			});
			$("#modal_ConceptosNom").modal("show");
		},
	});
});
//Seleccionar registro marcado del Modal de Conceptos de Nómina y mostrarlo en el formulario
$("body").on("click", "#tblModal_conceptos_Nom tr", async function () {
	id_nomcue_int = $(this).attr("id");
	nom_con = await datos_ConceptoNom(id_nomcue_int);
	if (targetInputId) {
		$('#' + targetInputId).val(id_nomcue_int);
		$('#' + targetInputName).val(nom_con[0]["nombre"]);
	} else {
		id_nomcue_int = $(this).attr("id");
		$("#id_nomcue_int" + item).val(id_nomcue_int);
		nom_con = await datos_ConceptoNom(id_nomcue_int);
		$("#nom_nomcue_int" + item).val(nom_con[0]["nombre"]);
		$("#nomcue_codigo_int" + item).val(nom_con[0]["codigo"]);
		$("#nomcue_tipo_int" + item).val(nom_con[0]["tipo"]);
		$("#nomcue_parametro_int" + item).val(nom_con[0]["parametro"]);
	}
	$("#modal_ConceptosNom").modal("hide");
	targetInputId = '';
	targetInputName = '';

});
//Busqueda de Cuentas Contables
var datos_ConceptoNom = async function (id_nomcue_int) {
	var datos = new FormData();
	datos.append("id_nomcue_int", id_nomcue_int);
	try {
		const url = `${base_url}/NomCon/nom_conceptoNOM`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
};
//Poblar modal de clientes
$(document).ready(function () {
	$("#modal-clientes").on("show.bs.modal", function () {
		id_emp = $("#id_emp").val();
		url = `${base_url}/Cotizaciones/listar_entidad_modal`;
		$("#tblModalClientes").DataTable().clear();
		$("#tblModalClientes").DataTable().destroy();
		var tblModal = $("#tblModalClientes").DataTable({
			aProcessing: true,
			aServerSide: true,
			fnCreatedRow: function (rowEl, data) {
				$(rowEl).attr("id", data.id_ent);
			},
			processing: true,
			ajax: {
				url: url,
				type: "POST",
				deferRender: true,
				data: { id: id_emp, tipo: "C" },
				dataSrc: "",
			},
			columns: [
				{ data: "id_ent" },
				{ data: "rif_ent" },
				{ data: "nom_ent" },
				{ data: "nom_vend" },
				{ data: "nombre_zona" },
			],
			columnDefs: [
				{
					targets: 0,
					visible: false,
					searchable: false,
				},
			],
			language: {
				url: `${base_url}/Assets/json/es-ES.json`,
			},
		});
	});
});

//Poblar modal de Proveedores
$("#modal-Proveedores").on("show.bs.modal", function (e) {
	id_emp = $("#id_emp").val();
	url = `${base_url}/Proveedores/listar_entidad_modal`;
	$("#tblModalProveedor").DataTable().clear();
	$("#tblModalProveedor").DataTable().destroy();
	var tblModal = $("#tblModalProveedor").DataTable({
		aProcessing: true,
		aServerSide: true,
		fnCreatedRow: function (rowEl, data) {
			$(rowEl).attr("id", data.id_ent);
		},
		processing: true,
		ajax: {
			url: url,
			type: "POST",
			deferRender: true,
			data: { id: id_emp, tipo: "P" },
			dataSrc: "",
		},
		columns: [
			{ data: "id_ent" },
			{ data: "rif_ent" },
			{ data: "nom_ent" },
			{ data: "nombre_zona" },
		],
		columnDefs: [
			{
				targets: 0,
				visible: false,
				searchable: false,
			},
		],
		language: {
			url: `${base_url}/Assets/json/es-ES.json`,
		},
	});
});
//Seleccionar registro marcado del Modal de proveedors y mostrarlo en el formulario
$("body").on("click", "#tblModalProveedor tr", function () {
	id_cli = $(this).attr("id");
	if (efecto == "C") {
		id_cli = $(this).attr("id");
		$("#id_cli").val(id_cli);
		$("#id_cli").trigger("change");
	} else if (efecto == "P") {
		id_ent = $(this).attr("id");
		$("#id_ent").val(id_ent);
		$("#id_ent").trigger("change");
	} else {
		$("#id_cli").val(id_cli);
		$("#id_cli").trigger("change");
	}
	$("#modal-Proveedores").modal("hide");
});
//Seleccionar registro marcado del Modal de clietnes y mostrarlo en el formulario
$("body").on("click", "#tblModalClientes tr", function () {
	id_cli = $(this).attr("id");
	$("#id_cli").val(id_cli);
	$("#id_cli").trigger("change");
	$("#modal-clientes").modal("hide");
});

//Poblar Modal de productos
$().ready(function () {
	$("#modal-productos").on("shown.bs.modal", async function (e) {
		let url = "";
		let datos = "";
		if (tipo_fac != "N") {
			id_alm_res = $("#id_alm").val();
			if (id_alm_res == undefined) {
				id_alm_res = false;
			}
		}
		id_emp = $("#id_emp").val();
		if (!id_emp) {
			id_emp = 0;
		}
		id_alm_sal = $("#id_alm_sal").val();

		if (id_alm_sal) {
			id_alm = id_alm_sal;
		}
		id_ubi_sal = $("#id_ubi_sal").val();
		if (id_ubi_sal) {
			id_ubi = id_ubi_sal;
		}
		id_tdo_cfg = await tip_doc_fac(id_emp);
		id_alm_ppal = id_tdo_cfg["id_alm"];
		stock = id_tdo_cfg["cot_stock"];
		if (tipo_fac == "F" || tipo_fac == "NF" || tipo_fac == "N") {
			stock = id_tdo_cfg["fac_stock"];
		}
		if (id_alm_ppal == id_alm) {
			almSalPpal = true;
		}
		if (origen_COM == 0) {
			if (almSalPpal || equivale) {
				datos = { stock: stock, id_emp: "0" };
				url = `${base_url}/Productos/listar_productos_modal`;
			} else if (
				(c_consig == 1 && tipo_fac == "F") ||
				(c_consig == 1 && tipo_fac == "N") ||
				(c_consig == 1 && tipo_fac == "NF")
			) {
				id_alm = $("#id_alm").val();
				if ($("#id_alm_def").val()) {
					id_alm = $("#id_alm_def").val();
				}
				datos = { id_alm: id_alm, id_ubi: id_ubi };
				url = `${base_url}/Productos/listar_productos_modal_consig`;
			} else if (c_consig == 0 && tipo_fac == "F") {
				datos = { stock: stock, id_emp: "0" };
				url = `${base_url}/Productos/listar_productos_modal`;
			} else if (id_alm_res && mov_inv == false) {
				if (tipo_fac == "N") {
					id_cli = 0;
				}
				datos = { id_alm: id_alm_res, id_cli, id_cli };
				url = `${base_url}/Productos/listar_productos_modal_reserva`;
			} else {
				datos = { stock: stock, id_emp: "0" };
				url = `${base_url}/Productos/listar_productos_modal`;
			}
		} else {
			datos = { stock: 0, id_emp: id_emp };
			url = `${base_url}/Productos/listar_productos_modal`;
		}
		var tblModal = $("#tblModalProd").DataTable({
			aProcessing: true,
			aServerSide: true,
			clear: true,
			destroy: true,
			processing: true,
			fnCreatedRow: function (rowEl, data) {
				$(rowEl).attr("id", data.id_prod);
			},
			order: [[3, "asc"]],
			ajax: {
				url: url,
				type: "POST",
				deferRender: true,
				data: datos,
				dataSrc: "",
				select: true,
			},
			language: {
				url: `${base_url}/Assets/json/es-ES.json`,
			},
			columns: [
				{ data: "id_prod" },
				{ data: "cod_prod" },
				{ data: "cod2_prod" },
				{ data: "nom_prod" },
				{ data: "ref_prod" },
				{ data: "nom_fab" },
				{ data: "stock" },
			],
			columnDefs: [
				{
					targets: 0,
					visible: false,
					searchable: false,
				},
				{
					targets: 6,
					className: "text-right",
				},
			],
		});
	});
})

//Seleccionar registro marcado del Modal de clietnes y mostrarlo en el formulario
$("body").on("click", "#tblModalProd tr", function () {
	id_prod = $(this).attr("id");
	if (!item) {
		$("#id_prod").val(id_prod);
	}
	$("#id_prod" + item).val(id_prod);
	ConsultarProducto(id_prod, item, "", "", "Z", c_consig, tipo_fac);
	$("#modal-productos").modal("hide");
	$(".id_prod").trigger("change");
});

$("#tblDetalle tbody").on("change", ".id_prod", function () {
	ConsultarProducto(id_prod, item, "", "", "Z", c_consig, tipo_fac);
	$("#modal-productos").modal("hide");
})

$("#tblInvMovDet").on("change", ".id_prod", function () {
	ConsultarProducto(id_prod, item, "", "", "Z", c_consig, tipo_fac);
	$("#modal-productos").modal("hide");
})

// para agregar un detalle mas, una fila
function agregarDetalleProductos(tipo, metro = 0) {
	if (metro == 0) {
		onlyread = "";
		onlyreadIVA = "";
		//Bloquear precios en la cotizacion
		if (tipo == "P" && loc_pri_cot == 1) {
			onlyread = " readonly ";
		}
		//Bloquear precios en la Facutracion, Notas de Entregas, etc.
		if (tipo != "C" && tipo != "P" && loc_pri_inv == 1) {
			onlyread = " readonly";
		}
		//Validar stock solo si no es cotizacion
		tcant = "";
		if (tipo != "C") {
			tcant = " tcant ";
		}
		tiva = "";
		nameid = "id_pro";
		if (tipo == 'NF') {
			item = last_item_table() + 1;
			tiva = "N"
			onlyreadIVA = "readonly"

		} else {
			item = last_item_table() + 1;
		}

		//item = item + 1;
		nameid = nameid;
		zitem = item;
		var htmlTags = `<tr  id="fila-${zitem}">
				<td class="text-right text-xs">${item}</td>
				<td style="width:30%"><input type="hidden" name="id_prod[]" id="id_prod${item}" class="text-xs photo id_prod"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_prod${item}" name="nom_prod" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>
				<td style="width:8%"><input type="number" name="cant[]" id="cant${item}" class="form-control text-right text-xs reCalcular ${tcant}" min="1" style="width:80%" value="1"></td>
				<td style="width:8%"><input type="number" name="stock[]" id="stock${item}" class="form-control text-right text-xs stock" style="width:100%" disabled></td>
				<td style="width:7%"><input type="text" name="uni_ven_prod[]" id="uni_ven_prod${item}" readonly class="form-control text-right text-xs"  style="width:100%"></td>
				<td style="width:8%"><input type="text" name="ventas_prod[]"  id="ventas_prod${item}" readonly class="form-control text-right text-xs" style="width:100%" ></td>
				<td style="width:8%"><input type="text" name="ventas_prod1[]" id="ventas_prod1${item}" class="form-control text-right text-xs reCalcular camponumero" style="width:100%" ${onlyread}></td>
				<td style="width:10%"><input type="hidden" name="id_des_item[]" id="id_des_item${item}" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs text-right" id="nom_des${item}" name="nom_des" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-descuentos" title="Buscar y seleccionar descuento"><i class="fas fa-search"></i></a></span></div></div></td>
				<td style="width:10%" class="text-center"><select name="iva_prod[]" id="iva_prod${item}" class="form-control text-xs reCalcular input-iva" style="width:60%" ${onlyreadIVA} ></select></td>
				<td style="width:10%"><input type="text" name="total[]" id="total${item}" class="form-control text-right text-xs sub-total input-fila" readonly ></td>
				<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture text-xs" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>
			</tr>`;

		$("#tblDetalle").append(htmlTags);
		listar_si_no("", `iva_prod${item}`);
	} else {
		nameid = "id_pro";
		item = item + 1;
		nameid = nameid;
		var htmlTags =
			'<tr  id="fila' +
			item +
			'" >' +
			'<td class="text-right text-xs">' +
			item +
			"</td>" +
			'<td style="width:30%"><input type="hidden" name="id_prod[]" id="id_prod' +
			item +
			'" class="text-xs photo"><div class="input-group"><input type="text"  class="form-control text-xs" id="nom_prod' +
			item +
			'" name="nom_prod" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>' +
			'<td style="width:8%"><input type="number" name="cant[]" id="cant' +
			item +
			'" class="form-control text-right text-xs tcant" min="1" style="width:80%" onchange="CalculateTotalFac()" ></td>' +
			'<td style="width:8%"><input type="number" name="stock[]" id="stock' +
			item +
			'" class="form-control text-right text-xs stock" style="width:100%" disabled></td>' +
			'<td style="width:10%"><select name="iva_prod[]" id="iva_prod' +
			item +
			'" class="form-control text-xs reCalcular" style="width:60%"></select>' +
			'<td style="width:7%"><input type="text" name="uni_ven_prod[]" id="uni_ven_prod' +
			item +
			'" readonly class="form-control text-right text-xs"  style="width:100%"></td>' +
			'<td style="width:8%"><input type="text" name="ventas_prod[]" id="ventas_prod' +
			item +
			'" readonly class="form-control text-right text-xs" readonly style="width:100%" step="0.0001" > </td>' +
			'<td style="width:8%"><input type="text" name="ventas_prod1[]" id="ventas_prod1' +
			item +
			'"  class="form-control text-right text-xs reCalcular" style="width:100%" step="0.0001" > </td>' +
			'<td style="width:10%"><input type="hidden" name="id_des_item[]" id="id_des_item' +
			item +
			'" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs text-right" id="nom_des' +
			item +
			'" name="nom_des" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-descuentos" title="Buscar y seleccionar descuento"><i class="fas fa-search"></i></a></span></div></div></td>' +
			'<td style="width:10%"><input type="text" name="total[]" id="total' +
			item +
			'" class="form-control text-right text-xs sub-total" readonly step="0.0001"></td>' +
			'<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture text-xs" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>' +
			"</tr>";
		$("#tblDetalle").append(htmlTags);
		listar_si_no("", "iva_prod" + item);
	}
}
//Determinar el ultimo item agregado
function last_item_table(table = "cuerpoTablaDetalle") {
	var max_item = 0;
	table = "#" + table;
	last_row = $(table + " tr:last");
	if (last_row[0]) {
		id = last_row[0];
		last_id = id["id"];
		if (last_id.includes('-')) {
			max_item = parseInt(last_id.substring(5));
		} else {
			max_item = parseInt(last_id.substring(4));
		}

	}
	return max_item;
}
async function loafrowfromexcel(e) {
	const content = await readXlsxFile(e.target.files[0]);
	//$('.loader').show();
	try {
		$.each(content, function (i, v) {
			id_prod = v[0];
			nom_prod = v[3];
			cant = v[7];
			item = i;
			if (i > 0) {
				var htmlTags =
					'<tr  id="fila' +
					item +
					'" >' +
					'<td class="text-right text-xs">' +
					item +
					"</td>" +
					'<td style="width:30%"><input type="hidden" name="id_prod[]" id="id_prod' +
					item +
					'" value="' +
					id_prod +
					'" class="text-xs photo"><div class="input-group"><input type="text"  class="form-control text-xs" id="nom_prod' +
					item +
					'" name="nom_prod" value="' +
					nom_prod +
					'" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>' +
					'<td style="width:8%"><input type="number" name="cant[]" id="cant' +
					item +
					'" value="' +
					cant +
					'" class="form-control text-right text-xs tcant" min="1" style="width:80%" onchange="CalculateTotalFac()" ></td>' +
					'<td style="width:8%"><input type="number" name="stock[]" id="stock' +
					item +
					'" class="form-control text-right text-xs " style="width:100%" disabled></td>' +
					'<td style="width:7%"><input type="text" name="uni_ven_prod[]" id="uni_ven_prod' +
					item +
					'" readonly class="form-control text-right text-xs"  style="width:100%"></td>' +
					'<td style="width:8%"><input type="text" name="ventas_prod[]" id="ventas_prod' +
					item +
					'" readonly class="form-control text-right text-xs" readonly style="width:100%" step="0.0001" > </td>' +
					'<td style="width:8%"><input type="text" name="ventas_prod1[]" id="ventas_prod1' +
					item +
					'"  class="form-control text-right text-xs reCalcular" style="width:100%" step="0.0001" > </td>' +
					'<td style="width:10%"><input type="hidden" name="id_des_item[]" id="id_des_item' +
					item +
					'" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs text-right" id="nom_des' +
					item +
					'" name="nom_des" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-descuentos" title="Buscar y seleccionar descuento"><i class="fas fa-search"></i></a></span></div></div></td>' +
					'<td style="width:10%"><select name="iva_prod[]" id="iva_prod' +
					item +
					'" class="form-control text-xs reCalcular" style="width:60%"></select>' +
					'<td style="width:10%"><input type="text" name="total[]" id="total' +
					item +
					'" class="form-control text-right text-xs sub-total" readonly step="0.0001"></td>' +
					'<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture text-xs" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>' +
					"</tr>";
				$("#cuerpoTablaDetalle").append(htmlTags);
				ConsultarProducto(
					id_prod,
					item,
					"",
					"",
					"Z",
					c_consig,
					tipo_fac,
					cant
				);
				listar_si_no("", "iva_prod" + item);
			}
		});
	} catch (error) {
		console.log(error);
	}
}
function show_tasa() {
	if (!id_emp) {
		id_emp = $("#id_emp").val();
		id_moneda = $("#id_moneda").val();
	}
	tasa_cambio = $("#tasa_cambio").val();
	tasa_cambio = tasa_cambio.replace(",", ".");
	url = `${base_url}/Empresas/show_row`;
	try {
		$.ajax({
			url: url,
			method: "POST",
			data: { id: id_emp },
			success: function (respuesta) {
				respuesta = JSON.parse(respuesta);
				id_moneda_base = respuesta["id_moneda"];
				if (id_moneda != id_moneda_base) {
					$(".foranea").show();
					$(".local").hide();
				} else {
					$(".local").show();
					$(".foranea").hide();
				}
			},
		});
	} catch (error) {
		console.log(error);
	}
}
//Recalcular en caso de cambiar el IVA a si o No
$(document).on("change", ".reCalcular", function (event) {
	if (tipo_fac != "COM") {
		var fila = $(this).closest("tr").attr("id");
		item = fila.substring(5);
		CalculateTotalFac();
	}
});
function CalculateTotalFac() {
	let xcantidad;
	let xprecio_venta;
	let xunidad_venta;
	let iva = 1;
	if (itemSelected > 0) {
		xcantidad = $("#cant" + itemSelected).val();
		xprecio_venta = $("#ventas_prod1" + itemSelected).val();
		xprecio_venta = xprecio_venta.replace(".", "");
		xprecio_venta = xprecio_venta.replace(",", ".");
		xunidad_venta = $("#uni_ven_prod" + itemSelected).val();
		xunidad_venta = xunidad_venta.replace(".", "");
		xunidad_venta = xunidad_venta.replace(",", ".");
		$("#ventas_prod" + itemSelected).val(
			format_number_with_dec_new(xprecio_venta / xunidad_venta, 2)
		);
		$("#total" + itemSelected).val(
			format_number_with_dec_new(xcantidad * xprecio_venta, 2)
		);
		monto = xcantidad * xprecio_venta;
	} else {
		precio_unit = formatoMoneda($("#ventas_prod" + item).val());
		xcantidad = parseFloat($("#cant" + item).val());
		xprecio_venta = $("#ventas_prod1" + item).val()
		if (xprecio_venta.indexOf(",") !== -1) {
			xprecio_venta = parseFloat(formatoMoneda($(`#ventas_prod1${item}`).val()));
		} else {
			xprecio_venta = parseFloat(xprecio_venta);
		}
		xunidad_venta = parseFloat($("#uni_ven_prod" + item).val());
		$("#ventas_prod" + item).val(
			format_number_with_dec_new(xprecio_venta / xunidad_venta, 2)
		);
		$("#total" + item).val(
			format_number_with_dec_new(xcantidad * xprecio_venta, 2)
		);
		monto = xcantidad * xprecio_venta;
	}
	if (itemSelected > 0) {
		item = itemSelected;
		titem = item;
	}
	let modo = "N";
	if (id_cot) {
		modo = "M";
	}
	id_cot = $("#id").val();

	val_col = 0;
	if (tipo_fac == "C") {
		val_vol = -1;
	} else if (tipo_fac == "N") {
		val_vol = 1;
	}
	tasa_cambio = formatoMoneda($("#tasa_cambio").val());

	if (id_cot) {
		recorreTable_fac(val_col, tasa_cambio, tipo_fac);
	} else {
		recorreTable_fac(val_col, tasa_cambio, tipo_fac);
	}
	if (itemSelected > 0) {
		itemSelected = 0;
		item = titem;
	}
}
//Busqueda del valor adicional 01 por Compañia 
var xAdditional01 = async function (id_emp, xfecha) {
	var datos = new FormData();
	datos.append("id_emp", id_emp);
	datos.append("fecha_precio", xfecha);
	try {
		const url = `${base_url}/Cotizaciones/consulta_adic01`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
};
//Busqueda del valor adicional 02 por Cliente
var xAdditional02 = async function (id_cli) {
	var datos = new FormData();
	datos.append("id_cli", id_cli);
	try {
		if (id_cli > 0) {
			const url = `${base_url}/Cotizaciones/consulta_adic02`;
			var respuesta = await fetch(url, {
				method: "POST",
				body: datos,
			});
			var resultado = await respuesta.json();
			return new Promise((resolve, reject) => {
				setTimeout(() => {
					resolve(resultado);
				}, 1);
			});
		}
	} catch (err) {
		console.log(err);
	}
};
function showrowupdate_fac(id, tipo = '') {
	let url, ttipo;
	onlyread = "";
	if (tipo == "P") {
		url = `${base_url}/Cotizaciones/consultar_cotizacion`;
		ttipo = "P";		
	} else if (tipo == "N" || tipo == "F" || tipo == "C") {
		url = `${base_url}/Facturacion/consultar_factura`;
		ttipo = "F";
		if (tipo == "C") {
			ttipo = "C";
		}
	} else if (tipo == "NF") {
		url = `${base_url}/Delnot/consultar_factura`;
		ttipo = "Z";
	} else {
		url = `${base_url}/Delnot/consultar_factura`;
		ttipo = "N";
	}
	var formData = $(this).serialize();	
	//Ajax para 
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: {id_cot: id, tipo: tipo},
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
		success: async function(data) {
			id_emp = data[0]["id_emp"];
			listar_empresas(id_emp, true);
			id_cont = data[0]["id_cont"];
			if (id_cont) {
				id_tdo_cfg = await tip_doc_fac(id_emp);
				id_tdoc_pre = id_tdo_cfg["id_tdoc_pre"];
				listar_tipos_documentos(id_emp, "F", id_tdo_val);
				listar_tipos_documentos(id_emp, "P", id_tdoc_pre, false, "fuente");
				$("#fuente").css("pointer-events", "none");
				$("#fuente").trigger("change");
			}
			id_tdo = data[0]["id_tdo"];
			listar_tipos_documentos(id_emp, ttipo, id_tdo, true);
			num_tdo = data[0]["num_tdo"];
			fecha_comp = data[0]["fecha_comp"];
			id_cli = data[0]["id_cli"];
			id_moneda = data[0]["id_moneda"];
			listar_monedas(id_moneda);
			listar_empresas(id_emp, true);
			id_tdo_cfg = await tip_doc_fac(id_emp);
			$("#num_tdo").val(num_tdo);
			$("#num_tdo").attr("readonly", "readonly");
			$("#fecha_comp").val(fecha_comp);
			$("#fecha_comp").attr("readonly", "readonly");
			$("#id_cli").val(id_cli);
			$("#id_cli").trigger("change");
			$("#id_moneda").css("pointer-events", "none");
			$("#id_vend").css("pointer-events", "none");
			$("#tasa_cambio").val(
				format_number_with_dec_new(data[0]["tasa_cambio"], 8)
			);
			$("#id_des_enca").val(data[0]["id_des"]);
			$("#oc_cliente").val(data[0]["oc_cliente"]);
			$("#descrip_cot").val(data[0]["descrip_cot"]);
			$("#nro_control").val(data[0]["nro_control"]);
			$("#nro_control").css("display", "block");
			observa = decodeEntities(data[0]["observa"]);
			$("#observa").val(observa);
			c_consig = data[0]["c_consig"];
			$("#oc_cliente").val(data[0]["oc_cliente"]);
			if (data[0]["alm_out"]) {

				alm_def = data[0]["alm_out"].split('|');
				id_alm_def = alm_def[0];
				id_ubi_def = alm_def[1];
				$("#id_alm_def").val(id_alm_def);
				$("#id_ubi_def").val(id_ubi_def);

			}
			if (data[0]["alm_in"]) {

				alm_cli = data[0]["alm_in"].split('|');
				id_alm_cli = alm_cli[0];
				id_ubi_cli = alm_cli[0];
				$("#id_alm_cli").val(id_alm_cli);
				$("#id_ubi_cli").val(id_ubi_cli);


			}
			const datosFetched = await tid_vend(id_cli);
			c_consig = datosFetched["c_consig"];
			if (c_consig) {
				$("#c_consig").val(c_consig);
				if (c_consig == 1) {
					if (id_alm_def) {
						$("#id_alm_sal").val(id_alm_def[0]);
						$("#id_ubi_sal").val(id_alm_def[1]);
						$("#id_alm_ent").val(id_alm_cli[0]);
						$("#id_ubi_ent").val(id_alm_cli[1]);
						mostar_data_inv();
					} else {
						mostar_data_inv();
					}
				}
			}
			//Verificar cliente
			onlyread = "";
			if (tipo == "P" && loc_pri_cot == 1) {
				onlyread = " readonly ";
			}
			if (tipo != "F" && tipo != "P" && loc_pri_inv == 1) {
				onlyread = " readonly ";
			}

			if (tipo == "P" && loc_pri_cot == 1) {
				onlyread = " readonly ";
			}
			if (tipo != "P" && loc_pri_inv == 1) {
				onlyread = " readonly ";
			}
			//
			item = 0;
			for (x of data) {
				item++;
				max_item = item;
				nameid = "id_pro" + item;
				//nameid = nameid + item;
				//Variables valor
				id_prod = x["id_prod"];
				nom_prod = x["nom_prod"];
				cant_det = x["can_det"];
				uni_vta = x["uni_vta"];
				pre_unit = x["pre_unit"];
				pre_vta = x["pre_vta"];
				iva_prod = x["iva_prod"];
				sub_total = x["sub_total"];
				stock = x["stock"];
				var htmlTags = `
				<tr id="fila${item}">
					<td class="text-right text-xs">${item}</td>
					<td style="width:30%"><input type="hidden" name="id_prod[]" id="id_prod${item}" class="text-xs photo" value="${id_prod}"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_prod${item}" name="nom_prod" readonly value="${nom_prod}"><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search"></i></a></span></div></div></td>
					<td style="width:8%"><input type="number" name="cant[]" id="cant${item}"  class="form-control text-right text-xs tcant" min="1"style="width:80%" value="${cant_det}" onchange="CalculateTotalFac()" ></td>
					<td style="width:8%"><input type="number" name="stock[]" id="stock${item}" class="form-control text-right text-xs stock" style="width:100%" disabled value="${stock} "></td>
					<td style="width:7%"><input type="text" name="uni_ven_prod[]" id="uni_ven_prod${item}" readonly class="form-control text-right text-xs" style="width:100%" value="${uni_vta}"></td>
					<td style="width:8%"><input type="text" name="ventas_prod[]"  id="ventas_prod${item}" readonly class="form-control text-right text-xs"  style="width:100%" value="${format_number_with_dec_new(pre_unit, 4)}"></td>
					<td style="width:8%"><input type="text" name="ventas_prod1[]" id="ventas_prod1${item}" class="form-control text-right text-xs"  style="width:100%" ${onlyread} value="${format_number_with_dec_new(pre_vta, 4)}" onchange="CalculateTotalFac()"></td>
					<td style="width:10%"><input type="hidden" name="id_des[]" id="id_des${item}" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs text-right" id="nom_des${item}" name="nom_des" readonly><div class="input-group-append"><span class="input-group-text"><a href="#" data-toggle="modal" data-target="#modal-descuentos" title="Buscar y seleccionar descuento"><i class="fas fa-search"></i></a></span></div></div></td>
					<td style="width:10%"><select name="iva_prod[]" id="iva_prod${item}" class="form-control text-xs reCalcular input-iva" style="width:60%"></select></td>
					<td style="width:10%"><input type="text" name="total[]" id="total${item}" class="form-control text-right text-xs sub-total input-fila" readonly value="${format_number_with_dec_new(sub_total, 4)}"></td>
					<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm show-picture" data-toggle="modal" data-target="#modal-showpicture" title="Ver fotos" ><i class="fa fa-eye"></i></button></div></td>
				</tr>`;
				$("#cuerpoTablaDetalle").append(htmlTags);
				listar_si_no(iva_prod, "iva_prod" + item);
				xTasaCambio = data[0]["tasa_cambio"];
				val_col = 1;
				if (tipo == "C") {
					val_vol = -1;
				}
				recorreTable_fac(val_col, xTasaCambio, tipo);
				id_ent = 0;
				if (c_consig == 1) {
					id_ent = $("#id_cli").val();
				}
				//StockProducto(id_prod, item, id_ent);
				
			}
			stock = id_tdo_cfg["cot_stock"];
		},
	});
}
//Validar stock de producto
$(document).on("change", ".stock", function () {
	id_ent = 0;
	if (c_consig == 1) {
		id_ent = $("#id_cli").val();
	}
	StockProducto(id_prod, item, id_ent);
});
//Mostrar Modal de Tipos de Descuentos
$("#modal-descuentos").on("show.bs.modal", function (e) {
	//Preparar carga de la tabla
	url = `${base_url}/TipoDcto/listar_descuentos`;
	$("#tblModalDcto").DataTable().clear();
	$("#tblModalDcto").DataTable().destroy();
	var tblModal = $("#tblModalDcto").DataTable({
		aProcessing: true,
		aServerSide: true,
		fnCreatedRow: function (rowEl, data) {
			$(rowEl).attr("id", data.id);
		},
		processing: true,
		ajax: {
			url: url,
			type: "POST",
			deferRender: true,
			dataSrc: "",
		},
		language: {
			url: `${base_url}/Assets/json/es-ES.json`,
		},
		columns: [
			{ data: "id" },
			{ data: "codigo_tipdes" },
			{ data: "valor_tipdes" },
		],
		columnDefs: [
			{
				targets: 0,
				visible: false,
				searchable: false,
			},
			{
				targets: 2,
				render: $.fn.dataTable.render.number(".", ",", 2, ""),
				className: "text-right",
			},
		],
	});
});
//Seleccionar registro marcado del Modal de clietnes y mostrarlo en el formulario
$("body").on("click", "#tblModalDcto tr", function () {
	id_des = $(this).attr("id");
	$("#id_des" + item).val(id_des);
	//Buscar valor del Descuento
	url = `${base_url}/TipoDcto/show_row`;
	$.ajax({
		url: url,
		method: "POST",
		data: { id: id_des },
		success: function (respuesta) {
			respuesta = JSON.parse(respuesta);
			mon_des_val = parseFloat(respuesta["valor_tipdes"]);
			nom_des = format_number_with_dec_new(mon_des_val, 2);
			$("#nom_des" + item).val(nom_des);
			//Actualizar costos de linea
			ventas_prod = $("#ventas_prod" + item).val();
			ventas_prod = ventas_prod.replace(".", "");
			ventas_prod = ventas_prod.replace(",", ".");
			ventas_prod = ventas_prod - ventas_prod * (mon_des_val / 100);
			$("#ventas_prod" + item).val(
				format_number_with_dec_new(ventas_prod, 2)
			);
			//Actualizar costos de linea
			ventas_prod = $("#ventas_prod1" + item).val();
			ventas_prod = ventas_prod.replace(".", "");
			ventas_prod = ventas_prod.replace(",", ".");
			ventas_prod = ventas_prod - ventas_prod * (mon_des_val / 100);
			$("#ventas_prod1" + item).val(
				format_number_with_dec_new(ventas_prod, 2)
			);
			//Actualizar costos de linea
			ventas_prod = $("#total" + item).val();
			ventas_prod = ventas_prod.replace(".", "");
			ventas_prod = ventas_prod.replace(",", ".");
			ventas_prod = ventas_prod - ventas_prod * (mon_des_val / 100);
			$("#total" + item).val(format_number_with_dec_new(ventas_prod, 2));
			id_fact = $("#id").val();
			if (id_fact) {
				recorreTable_fac(1, tasa_cambio.replace(",", "."));
			} else {
				recorreTable_fac(1, tasa_cambio.replace(",", "."));
			}
		},
	});
	$("#modal-descuentos").modal("hide");
});

//Poblar Modal de Ubicaciones
$("#modal-ubicaciones").on("show.bs.modal", function (e) {
	let url = "";
	let datos = new FormData();
	url = `${base_url}/Ubicaciones/listar_ubicaciones`;
	$("#tblModalUbicaciones").DataTable().clear();
	$("#tblModalUbicaciones").DataTable().destroy();
	var tblModal = $("#tblModalUbicaciones").DataTable({
		aProcessing: true,
		aServerSide: true,
		fnCreatedRow: function (rowEl, data) {
			$(rowEl).attr("id", data.id_ubi);
		},
		processing: true,
		ajax: {
			url: url,
			type: "POST",
			deferRender: true,
			data: { id_emp: id_emp, agrupa: "" },
			dataSrc: "",
		},
		language: {
			url: `${base_url}/Assets/json/es-ES.json`,
		},
		columns: [{ data: "id_ubi" }, { data: "cod_ubi" }, { data: "nom_ubi" }],
		columnDefs: [
			{
				targets: 0,
				visible: false,
				searchable: false,
			},
		],
	});
});
//Seleccionar registro marcado del Modal de Ubicaciones y mostrarlo en el formulario
$("body").on("click", "#tblModalUbicaciones tr", function () {
	id_ubi = $(this).attr("id");
	$("#id_ubi").val(id_ubi);
	if (parseInt(item) > 0) {
		$("#id_ubi" + item).val(id_ubi);
		$(".id_ubi").trigger("change");
	} else {
		$("#id_ubi").val(id_ubi);
		$("#id_ubi").trigger("change");
	}
	if (tipo_fac) {

	}
	$("#modal-ubicaciones").modal("hide");
});
$(document).on("change", "#id_ubi", async function (event) {
	const id = $(this).val();
	const datos = new FormData();
	datos.append("id", id);
	try {
		if (id) {
			const url = `${base_url}/Ubicaciones/con_ubi`;
			const repuesta = await fetch(url, {
				method: "POST",
				body: datos,
			});
			const resulta = await repuesta.json();
			if (resulta) {
				$("#nom_ubi").val(resulta.nom_ubi);
			}
		}
	} catch (error) {
		console.log(error);
	}
});
$(document).on("change", ".id_ubi", async function (event) {
	const id = $(this).val();
	const datos = new FormData();
	datos.append("id", id);
	try {
		if (id) {
			const url = `${base_url}/Ubicaciones/con_ubi`;
			const repuesta = await fetch(url, {
				method: "POST",
				body: datos,
			});
			const resulta = await repuesta.json();
			if (resulta) {
				$("#nom_ubi" + item).val(resulta.nom_ubi);
				item = 0;
			}
		}
	} catch (error) {
		console.log(error);
	}
});
//Modal para Buscar Auxiliares Contables
$(document).on("click", ".nom_aux", function (e) {
	e.preventDefault();
	const url = `${base_url}/AuxiliarCtb/modal_AuxiliarCtb`;
	$.ajax({
		url: url,
		datatype: "json",
		success: function (response) {
			response_new = JSON.parse(response);
			$("#tblModal_auxiliarCTB").DataTable().clear();
			$("#tblModal_auxiliarCTB").DataTable().destroy();
			var tblModal_auxiliarCTB = new DataTable("#tblModal_auxiliarCTB", {
				aProcessing: true,
				aServerSide: true,
				columnDefs: [
					{
						targets: 0,
						visible: false,
						searchable: false,
					},
				],
				fnCreatedRow: function (rowEl, response_new) {
					$(rowEl).attr("id", response_new[0]);
				},
				order: [[1, "asc"]],
				language: {
					url: `${base_url}/Assets/json/es-ES.json`,
				},
			});
			response_new.forEach(function (e) {
				tblModal_auxiliarCTB.row
					.add([e.id_aux, e.cod_aux, e.nombre_aux])
					.draw();
			});
			$("#modal-AuxiliarCtb").modal("show");
		},
	});
});
//Seleccionar registro marcado del Modal de Auxiliares Contables y mostrarlo en el formulario
$("body").on("click", "#tblModal_auxiliarCTB tr", function () {
	id_aux = $(this).attr("id");
	if (item) {
		$("#id_aux" + item).val(id_aux);
		$(".id_aux").trigger("change");
	} else {
		$("#id_aux").val(id_aux);
		$("#id_aux").trigger("change");
	}
	$("#modal-AuxiliaresCtb").modal("hide");
	$("#tblModal_auxiliarCTB").DataTable().clear();
	$("#tblModal_auxiliarCTB").DataTable().destroy();
});
$(document).on("change", "#id_aux", async function (e) {
	var nom_aux = await nom_aux_fun(id_aux);
	if (nom_aux) {
		nombre_aux = nom_aux["cod_aux"] + " - " + nom_aux["nombre_aux"];
		$("#nom_aux").val(nombre_aux);
	}
});
$(document).on("change", ".id_aux", async function (e) {
	var nom_aux = await nom_aux_fun(id_aux);
	nombre_aux = nom_aux["cod_aux"] + " - " + nom_aux["nombre_aux"];
	$("#nom_aux" + item).val(nombre_aux);
});
//Busqueda de Auxiliar Contables
var nom_aux_fun = async function (id_aux) {
	var datos = new FormData();
	try {
		if (!isEmpty(id_aux)) {
			datos.append("id_aux", id_aux);
			const url = `${base_url}/AuxiliarCtb/nom_aux`;
			var respuesta = await fetch(url, {
				method: "POST",
				body: datos,
			});
			var resultado = await respuesta.json();
			return new Promise((resolve, reject) => {
				setTimeout(() => {
					resolve(resultado);
				}, 1);
			});
		}
	} catch (err) {
		console.log(err);
	}
};
//Fechas disponibles de Empresas
var tfechas_emp = async function (id_emp) {
	var datos = new FormData();
	datos.append("id_emp", id_emp);
	try {
		const url = `${base_url}/Empresas/tfechas_emp`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (error) {
		console.log(error);
	}
};
//Llenar Tipo de Movimientos de Inventario
function listar_tipMovINV(tipo = "", tag = "", bloquear = false) {
	let cadena = "";
	let selected = "";
	let selectedE = "";
	let selectedS = "";
	let selectedR = "";
	let selectedT = "";

	if (tipo == "E") {
		selectedE = "selected";
	} else if (tipo == "S") {
		selectedS = "selected";
	} else if (tipo == "R") {
		selectedR = "selected";
	} else if (tipo == "T") {
		selectedT = "selected";
	} else {
		selected = "selected";
	}

	cadena += "<option " + selected + ' value="">Seleccione..</option>';
	cadena += "<option " + selectedE + ' value ="E">Entrada</option>';
	cadena += "<option " + selectedS + ' value ="S">Salida</option>';
	cadena += "<option ".selectedR + ' value ="R">Reintegro</option>';
	cadena += "<option " + selectedT + ' value ="T">Transferencia</option>';
	if (tag) {
		$("#" + tag).html(cadena);
		if (bloquear) {
			$("#" + tag).css("pointer-events", "none");
		}
	} else {
		$("#tipo_tmoinv").html(cadena);
		if (bloquear) {
			$("#tipo_tmoinv").css("pointer-events", "none");
		}
	}
}
const decodeHTMLEntities = (text) => {
	// Create a new element or use one from cache, to save some element creation overhead
	const el = (decodeHTMLEntities.__cache_data_element =
		decodeHTMLEntities.__cache_data_element ||
		document.createElement("div"));

	const enc = text
		// Prevent any mixup of existing pattern in text
		.replace(/⪪/g, "⪪#")
		// Encode entities in special format. This will prevent native element encoder to replace any amp characters
		.replace(/&([a-z1-8]{2,31}|#x[0-9a-f]+|#\d+);/gi, "⪪$1⪫");

	// Encode any HTML tags in the text to prevent script injection
	el.textContent = enc;

	// Decode entities from special format, back to their original HTML entities format
	el.innerHTML = el.innerHTML
		.replace(/⪪([a-z1-8]{2,31}|#x[0-9a-f]+|#\d+)⪫/gi, "&$1;")
		.replace(/⪪#/g, "⪪");

	// Get the decoded HTML entities
	const dec = el.textContent;

	// Clear the element content, in order to preserve a bit of memory (in case the text is big)
	el.textContent = "";

	return dec;
};
function listar_nivel_detalle() {
	//Listar el nivel de detalle maximo de una cuenta
	const url = `${base_url}/CuentasCtb/listar_nivel_detalle`;
	const niv_det = 1;
	$.ajax({
		url: url,
		type: "POST",
		success: function (result) {
			const niv_det_fin = JSON.parse(result);
			for (
				var index = parseInt(niv_det_fin.Count);
				niv_det <= index;
				index--
			) {
				$("#niv_det").append(
					'<option value="' + index + '">' + index + "</option>"
				);
			}
		},
	});
}
//Colores de gráficas
function generarNumero(numero) {
	return (Math.random() * numero).toFixed(0);
}
function colorRGB() {
	var color =
		"(" +
		generarNumero(255) +
		"," +
		generarNumero(255) +
		"," +
		generarNumero(255) +
		")";
	return "rgb" + color;
}
//Modal para Buscar Conceptos de CXP
$(document).on("click", ".nom_con_cxp", function (e) {
	id_emp = $("#id_emp").val();
	const url = `${base_url}/ConcepCXP/listar_conceptos`;
	$.ajax({
		url: url,
		//data: {id_emp: id_emp},
		datatype: "json",
		dataSrc: "",
		type: "POST",
		success: function (response) {
			response_new = JSON.parse(response);
			$("#tblModalConcepCXP").DataTable().clear();
			$("#tblModalConcepCXP").DataTable().destroy();
			var tblModalConcepCXP = new DataTable("#tblModalConcepCXP", {
				aProcessing: true,
				aServerSide: true,
				columnDefs: [
					{
						targets: 0,
						visible: false,
						searchable: false,
					},
				],
				fnCreatedRow: function (rowEl, response_new) {
					$(rowEl).attr("id", response_new[0]);
				},
				order: [[1, "asc"]],
				language: {
					url: `${base_url}/Assets/json/es-ES.json`,
				},
			});
			response_new.forEach(function (e) {
				tblModalConcepCXP.row
					.add([
						e.id,
						e.codigo_con,
						e.nombre_con,
						e.id_ctb,
						e.id_aux,
						e.descrip,
						format_number_with_dec_new(e.por_reten, 2),
					])
					.draw();
			});
			$("#modal-ConcepCXP").modal("show");
		},
		fail: function (error) {
			console.log(error);
		},
	});
});
//Seleccionar registro marcado del Modal de CXP y mostrarlo en el formulario
$("body").on("click", "#tblModalConcepCXP tr", function () {
	id_con = $(this).attr("id");
	if (item) {
		$("#id_con" + item).val(id_con);
		$(".id_con_cxp").trigger("change");
	} else {
		//Retencion de IVA
		$("#id_con").val(id_con);
		$("#id_con").trigger("change");
	}
	$("#modal-ConcepCXP").modal("hide");
	$("#tblModalConcepCXP").DataTable().clear();
	$("#tblModalConcepCXP").DataTable().destroy();
});
$(document).on("change", ".id_con_cxp", function (e) {
	const url = `${base_url}/ConcepCXP/editar_row`;
	if (item) {
		id_con = $("#id_con" + item).val();
		$.ajax({
			url: url,
			data: { id: id_con },
			datatype: "json",
			dataSrc: "",
			type: "POST",
			success: function (response) {
				response_new = JSON.parse(response);
				codigo_con = response_new.codigo_con;
				nom_con = response_new.nombre_con;
				aux_cta = response_new.aux_cta;
				$("#nom_con" + item).val(codigo_con + " - " + nom_con);
				$(".div_aux" + item).css("display", "block");
				if (aux_cta == "N") {
					$(".div_aux" + item).css("display", "none");
				}
			},
		});
	}
});
//Modal para Buscar Conceptos de bancos
$(document).on("click", ".nom_con", function (e) {
	id_emp = $("#id_emp").val();
	const url = `${base_url}/BanConceptos/listar_conceptos`;
	$.ajax({
		url: url,
		datatype: "json",
		dataSrc: "",
		type: "POST",
		success: function (response) {
			response_new = JSON.parse(response);
			$("#tblModal_BanConceptos").DataTable().clear();
			$("#tblModal_BanConceptos").DataTable().destroy();
			var tblModal_BanConceptos = new DataTable(
				"#tblModal_BanConceptos",
				{
					aProcessing: true,
					aServerSide: true,
					columnDefs: [
						{
							targets: 0,
							visible: false,
							searchable: false,
						},
					],
					fnCreatedRow: function (rowEl, response_new) {
						$(rowEl).attr("id", response_new[0]);
					},
					order: [[1, "asc"]],
					language: {
						url: `${base_url}/Assets/json/es-ES.json`,
					},
				}
			);
			response_new.forEach(function (e) {
				tblModal_BanConceptos.row
					.add([e.id_bancon, e.cod_bancon, e.nom_bancon])
					.draw();
			});
			$("#modal-BanConceptos").modal("show");
		},
		fail: function (error) {
			console.log(error);
		},
	});
});
//Seleccionar registro marcado del Modal de Conceptos Bancarios y mostrarlo en el formulario
$("body").on("click", "#tblModal_BanConceptos tr", function () {
	id_bancon = $(this).attr("id");
	if (item) {
		$("#id_bancon" + item).val(id_bancon);
		$(".id_bancon").trigger("change");
	} else {
		$("#id_bancon").val(id_bancon);
		$("#id_bancon").trigger("change");
		//
	}
	$("#modal-BanConceptos").modal("hide");
	$("#tblModal_BanConceptos").DataTable().clear();
	$("#tblModal_BanConceptos").DataTable().destroy();
});
//Busqueda de Concepto Bancario
var nom_con_ban = async function (id_bancon) {
	var datos = new FormData();
	datos.append("id_bancon", id_bancon);
	try {
		if (id_bancon) {
			const url = `${base_url}/BanConceptos/nom_con_ban`;
			var respuesta = await fetch(url, {
				method: "POST",
				body: datos,
			});
			var resultado = await respuesta.json();
			return new Promise((resolve, reject) => {
				setTimeout(() => {
					resolve(resultado);
				}, 1);
			});
		}
	} catch (err) {
		console.log(err);
	}
};
//Validar si el Tipo de Documento usa consecutivo o no para poder aisgnar el número del documento
//Al seleccionar el Tipo de Documento
$("#id_tdo").on("change", function (e) {
	$("#afectado").hide();
	id_tdo = $(this).val();
	if (id_tdo) {
		const url = `${base_url}/CXCDocument/val_tdo`
		//Ajax para Validar el Tipo de Documento
		$.ajax({
			url: url,
			method: 'POST',
			dataSrc: '',
			data: { id: id_tdo },
			dataType: 'json',
			beforeSend: function () {
				loader.show();
			},
			complete: function () {
				loader.hide();
			},
			error: function (PDOException) {
				loader.hide();
				console.log('Ha ocurrido el siguiente error:', PDOException.responseText)
			},
			success: function (data) {
				if (data) {
					if (data.con_tdoc == 0) {
						$("#num_tdo").prop("readonly", false);
					} else {
						$("#num_tdo").prop("readonly", true);
					}
					//Visualizar el documento afectado
					tipo_tdoc = data.tipo_tdoc;
					if (tipo_tdoc == "B" || tipo_tdoc == "C") {
						$("#afectado").show();
					}
					if (data.sol_aprob == 1) {
						sol_aprob = true;
						$("#status").val(9);
						$("#status").css("pointer-events", "none");
					}
					if(data.tipo_tdoc == "C"){
						accion = -1;
					}
				}
			},
		});
	}

})
//Modal para Buscar Conceptos de CXC
/*
$('#modal-BanConceptos').on('show.bs.modal', function (e) {
	id_emp = $("#id_emp").val();
	const url = `${base_url}/BanConceptos/listar_conceptos`;
	$.ajax({
		url: url,
		data: {id_emp: id_emp},
		datatype: 'json',
		dataSrc : '',
		type: 'POST',
		success: function(response){
			response_new = JSON.parse(response);
			$("#tblModal_BanConceptos").DataTable().clear();
			$("#tblModal_BanConceptos").DataTable().destroy()
			var tblModal_BanConceptos = new DataTable("#tblModal_BanConceptos", {
				columnDefs: [
					{
						targets: 0,
						visible: false,
						searchable: false,
					},
				],
				fnCreatedRow: function(rowEl, response_new){
					$(rowEl).attr('id', response_new[0]);
				},
				order: [[1, 'asc']],
				language: {
					url: `${base_url}/Assets/json/es-ES.json`,
				},
			});
			response_new.forEach(function(e){
				tblModal_BanConceptos.row.add([
					e.id_bancon,
					e.cod_bancon,
					e.nom_bancon,
				]).draw();
			});
			$("#modal-BanConceptos").modal('show');
		},
		fail: function(error){
			console.log(error);
		}
	})
})
	*/
//Modal para Buscar Conceptos de CXC
$(document).on("click", ".nom_con_cxc", function (e) {
	id_emp = $("#id_emp").val();
	const url = `${base_url}/ConcepCXC/listar_conceptos`;
	$.ajax({
		url: url,
		data: { id_emp: id_emp },
		datatype: "json",
		dataSrc: "",
		type: "POST",
		success: function (response) {
			response_new = JSON.parse(response);
			$("#tblModalConcepCXC").DataTable().clear();
			$("#tblModalConcepCXC").DataTable().destroy();
			var tblModalConcepCXC = new DataTable("#tblModalConcepCXC", {
				aProcessing: true,
				aServerSide: true,
				columnDefs: [
					{
						targets: 0,
						visible: false,
						searchable: false,
					},
				],
				fnCreatedRow: function (rowEl, response_new) {
					$(rowEl).attr("id", response_new[0]);
				},
				order: [[1, "asc"]],
				language: {
					url: `${base_url}/Assets/json/es-ES.json`,
				},
			});
			response_new.forEach(function (e) {
				tblModalConcepCXC.row
					.add([e.id, e.codigo_con, e.nombre_con])
					.draw();
			});
			$("#modal-ConcepCXC").modal("show");
		},
		fail: function (error) {
			console.log(error);
		},
	});
});
//Seleccionar registro marcado del Modal de Conceptos de CXC y mostrarlo en el formulario
$("body").on("click", "#tblModalConcepCXC tr", function () {
	id_con = $(this).attr("id");
	if (item) {
		$("#id_con" + item).val(id_con);
		$(".id_con_cxc").trigger("change");
	} else {
		$("#id_con").val(id_con);
		$("#id_con").trigger("change");
	}
	$("#modal-ConcepCXC").modal("hide");
	$("#tblModalConcepCXC").DataTable().clear();
	$("#tblModalConcepCXC").DataTable().destroy();
});
$(document).on("change", ".id_con_cxc", function (e) {
	const url = `${base_url}/ConcepCXC/show_row`;
	if (item) {
		id_con = $("#id_con" + item).val();
		$.ajax({
			url: url,
			data: { id: id_con },
			datatype: "json",
			dataSrc: "",
			method: "POST",
			success: function (response) {
				try {
					const response_new = JSON.parse(response);
					codigo_con = response_new.codigo_con;
					nom_con = response_new.nombre_con;
					aux_cta = response_new.aux_cta;
					$("#nom_con" + item).val(codigo_con + " - " + nom_con);
					$(".div_aux" + item).css("display", "block");
					if (aux_cta == "N") {
						$(".div_aux" + item).css("display", "none");
					}
				} catch (error) {
					console.log("Error JSON: ", error);
				}
			},
		});
	}
});
//Modal para Buscar Almacenes
function mostar_almacens_modal() {
	id_emp = $("#id_emp").val();
	const url = `${base_url}/Almacen/listar_almacenes`;
	$.ajax({
		url: url,
		data: { id_emp: id_emp },
		datatype: "json",
		dataSrc: "",
		type: "POST",
		success: function (response) {
			response_new = JSON.parse(response);
			$("#tblModalAlmacenes").DataTable().clear();
			$("#tblModalAlmacenes").DataTable().destroy();
			var tblModalAlmacenes = new DataTable("#tblModalAlmacenes", {
				aProcessing: true,
				aServerSide: true,
				columnDefs: [
					{
						targets: 0,
						visible: false,
						searchable: false,
					},
				],
				fnCreatedRow: function (rowEl, response_new) {
					$(rowEl).attr("id", response_new[0]);
				},
				order: [[1, "asc"]],
				language: {
					url: `${base_url}/Assets/json/es-ES.json`,
				},
			});
			response_new.forEach(function (e) {
				tblModalAlmacenes.row
					.add([e.id_alm, e.cod_alm, e.nom_alm])
					.draw();
			});
			$("#modal-Almacenes").modal("show");
		},
		fail: function (error) {
			console.log(error);
		},
	});
}
//Modal para buscar Ubicaciones
function mostrar_ubicaciones_modal() {
	let url = "";
	let datos = new FormData();
	url = `${base_url}/Ubicaciones/listar_ubicaciones`;

	$.ajax({
		url: url,
		data: { id_emp: id_emp, agrupa: "N" },
		datatype: "json",
		dataSrc: "",
		type: "POST",
		success: function (response) {
			$("#tblModalUbicaciones").DataTable().clear();
			$("#tblModalUbicaciones").DataTable().destroy();
			var tblModal = $("#tblModalUbicaciones").DataTable({
				aProcessing: true,
				aServerSide: true,
				columnDefs: [
					{
						targets: 0,
						visible: false,
						searchable: false,
					},
				],
				fnCreatedRow: function (rowEl, data) {
					$(rowEl).attr("id", data.id_ubi);
				},
				language: {
					url: `${base_url}/Assets/json/es-ES.json`,
				},
				columns: [
					{ data: "id_ubi" },
					{ data: "cod_ubi" },
					{ data: "nom_ubi" },
				],
			});
			$("#modal-ubicaciones").modal("show");
		},
	});
}
//Seleccionar registro marcado del Modal de Almacen y mostrarlo en el formulario
$("body").on("click", "#tblModalAlmacenes tr", function () {
	id = $(this).attr("id");
	if (item) {
		$("#id_alm" + item).val(id);
		$(".id_alm").trigger("change");
	} else {
		$("#id_alm").val(id);
		//$("#id_alm").trigger( "change" );
	}
	$("#modal_Almacenes").modal("hide");
	$("#tblModalAlmacenes").DataTable().clear();
	$("#tblModalAlmacenes").DataTable().destroy();
	if (id_ubi == null && c_consig == 1 && tipo_fac == "N") {
		$("#modal-ubicaciones").modal("show");
	}
});
$(document).on("change", ".id_con_cxc", function (e) {
	const url = `${base_url}/ConcepCXC/editar_row`;
	if (item) {
		id_con = $("#id_con" + item).val();
		$.ajax({
			url: url,
			data: { id: id_con },
			datatype: "json",
			dataSrc: "",
			method: "POST",
			success: function (response) {
				try {
					//const response_new = JSON.parse(response);
					const response_new = JSON.parse(response);
					codigo_con = response_new.codigo_con;
					nom_con = response_new.nombre_con;
					aux_cta = response_new.id_ctbaux;
					$("#nom_con" + item).val(codigo_con + " - " + nom_con);
					$(".div_aux" + item).css("display", "block");
					if (aux_cta == "N" || aux_cta == 0 || aux_cta == null) {
						$(".div_aux" + item).css("display", "none");
					}
				} catch (error) {
					console.log("Error JSON: ", error);
				}
			},
		});
	}
});
//Consultar Tipo de Documentos de COmpras para validar si usa consecutivo
var tip_doc_cxp = function (id_tdo_cxp) {
	const datos = new FormData();
	datos.append("id_tdo_cxp", id_tdo_cxp);
	const url = `${base_url}/TipoDocCXP/edit_deta`;
	try {
		/* var respuesta = await fetch (url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		console.log(resultado);
		return resultado;*/
		$.ajax({
			url: url,
			data: { id_tdo_cxp: id_tdo_cxp },
			datatype: "json",
			dataSrc: "",
			method: "POST",
			success: function (response) {
				try {
				} catch (error) {
					console.log("Error JSON: ", error);
				}
			},
		});
	} catch (err) {
		console.log(err);
	}
};
//Colocar la fecha final al seleccionar la fecha inicial
$("#fec_ini").on("change", function () {
	var fec_ini = $(this).val();
	var xfecha = fec_ini.split("-");
	var last_day = getLastDateofMonth(fec_ini);
	xfecha = last_day.split("-");
	var fec_fin = xfecha[0] + "-" + xfecha[1] + "-" + xfecha[2];
	$("#fec_fin").val(fec_fin);
});
//Formatear numeros
function getChange() {
	// 48 - 57 (0-9)
	var str1 = valueRef.value;
	if (
		str1[str1.length - 1].charCodeAt() < 48 ||
		str1[str1.length - 1].charCodeAt() > 57
	) {
		valueRef.value = str1.substring(0, str1.length - 1);
		return;
	}

	// t.replace(/,/g,'')
	let str = valueRef.value.replace(/,/g, "");

	let value = +str;

	valueRef.value = value.toLocaleString();
}
//Llenar combo de Paises
async function listar_retiva(id, tag) {
	const datos = new FormData();
	try {
		loader.show();
		const url = `${base_url}/RetIva/listar_retiva`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length > 0) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id"]) {
					cadena +=
						"<option selected value='" +
						result[i]["id"] +
						"'>" +
						result[i]["desc_retiva"] +
						"</option>";
				} else {
					cadena +=
						"<option value='" +
						result[i]["id"] +
						"'>" +
						result[i]["desc_retiva"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value=''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	} finally {
		loader.hide();
	}
}
function format(input) {
	var num = input.value.replace(/\./g, "");
	if (!isNaN(num)) {
		num = num
			.toString()
			.split("")
			.reverse()
			.join("")
			.replace(/(?=\d*\.?)(\d{3})/g, "$1.");
		num = num.split("").reverse().join("").replace(/^[\.]/, "");
		input.value = num;
	} else {
		alert("Solo se permiten numeros");
		input.value = input.value.replace(/[^\d\.]*/g, "");
	}
} //Llenar combo de Retencion de ISLR
//Llenar combo de Paises
async function listar_retislr(id, tag) {
	const datos = new FormData();
	try {
		const url = `${base_url}/RetISLR/listar_retislr`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length > 0) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id"]) {
					cadena +=
						"<option selected value='" +
						result[i]["id"] +
						"'>" +
						result[i]["descrip"] +
						"</option>";
				} else {
					cadena +=
						"<option value='" +
						result[i]["id"] +
						"'>" +
						result[i]["descrip"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value=''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Busqueda de Concepto CXP
var nom_con_cxp = async function (id) {
	var datos = new FormData();
	datos.append("id", id);
	try {
		const url = `${base_url}/ConcepCXP/editar_row`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
};
//Buscar Id de Tipo de Documento Fuente en Facturación
var get_id_tipo_doc_fuente = async function (id_tdo) {
	var datos = new FormData();
	datos.append("tipo_codigo", id_tdo);
	try {
		const url = `${base_url}/TipoDocCXC/get_id_tipo_doc_fuente`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		return result;
	} catch (error) {

	}
}
//Buscar Facturas pendientes para emisión de Notas de Creditos
async function listar_facturas_clientes(emp, id = 0, tag, id_ent) {
	const datos = new FormData();
	datos.append("id_emp", emp);
	datos.append("id_ent", id_ent);
	try {
		const url = `${base_url}/CXCDocument/listar_facturas_clientes`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = '0'>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id && id == result[i]["id_cot"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_cot"] +
						"'>" +
						result[i]["cliente"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_cot"] +
						"'>" +
						result[i]["cliente"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Totalizar tablas dependiendo de la clase
function totalizar_tabla_mov() {
	//Tabla de Conceptos
	var sum = 0;
	$(".monto").each(function () {
		if ($(this).val() != "") {
			var monto = formatoMoneda($(this).val());
			sum += monto;
		}
	});
	$("#tmon_mov").val(format_number_with_dec_new(sum, 2));
}
//Solo permite numeros y solo un punto para decimal
$(document).on("keyup keypress", ".solo-numeros-punto", function () {
	let value = $(this).val();
	value = value.replace(/[^0-9.]/g, "");
	const parts = value.split(".");
	if (parts.length > 2) {
		value = parts[0] + "." + parts.slice(1).join("");
	}
	$(this).val(value);
});
//Languaje Select 2
$(".select-language").select2({
	language: "es",
});
//Para validar si esta bloqueado o no el Proceso de Facturación y Cotizaciones
$(document).on("click", ".new_row", async function () {
	let url = `${base_url}/ConfigFAC/val_fact`;
	let ruta = "";
	let texto = "";
	if (tipo_fac == "P") {
		ruta = "Cotizaciones";
		texto = "Cotizaciones";
	} else if (tipo_fac == "F") {
		ruta = "Facturacion";
		texto = "Facturación";
	} else if (tipo_fac == "N") {
		ruta = "Delnot";
		texto = "Notas de Entrega";
	} else if (tipo_fac == "Z") {
		ruta = "Delnotnotfis";
		texto = "Notas de Entrega no Fiscal";
	} else if (tipo_fac == "C") {
		ruta = "Facturacion";
		texto = "Notas de Crédito";
	}
	let repuesta = await fetch(url, {
		method: "POST",
	});
	const resultado = await repuesta.json();
	if (resultado) {
		locked_invoice = resultado[0]["locked_invoice"];
		if (locked_invoice == 1) {
			message =
				"<p><b class='text-red'>Actualmente el sistema se encuentra en mantenimiento, No se puede emitir " +
				texto +
				"  hasta nuevo aviso <br><br> En caso de alguna duda, por favor contacte a su Supervisor</b></p>";
			Swal.fire({
				title: "Notificación!",
				icon: "info",
				imageUrl: `${base_url}/Assets/img/mant_fac.gif`,
				imageWidth: 320,
				imageHeight: 226,
				imageAlt: "Custom image",
				html: message,
			});
		} else {
			window.location.href = `${base_url}/` + ruta + `/nuevo`;
		}
	}
});
//Obtener parametros de URL
function GetURLParameter(sParam) {
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split("&");
	for (var i = 0; i < sURLVariables.length; i++) {
		var sParameterName = sURLVariables[i].split("=");
		if (sParameterName[0] == sParam) {
			return sParameterName[1];
		}
	}
}
// CLase para cuando se modifiue la cantidad
$(document).on("change", ".tcant", async function () {
	var tcan_fact = parseInt($(this).val());
	id_emp = $("#id_emp").val();
	id_tdo_cfg = await tip_doc_fac(id_emp);
	stock = id_tdo_cfg["cot_stock"];
	if (stock == 1) {
		var tstoc = parseInt($("#stock" + item).val());
		if (tcan_fact > tstoc) {
			mensaje =
				"La cantidad La cantidad indicada <b>" +
				tcan_fact +
				"</b>, excede del stock que es de <b> " +
				tstoc +
				"</b>!";
			Swal.fire({
				icon: "error",
				title: "Oops...",
				html: mensaje,
			}).then((result) => {
				$("#cant" + item).val(1);
			});
		}
	}
});
//Crear Div para el loading
function div_loading() {
	// Crear el div
	let $loaderDiv = $("<div>")
		.addClass("loader")
		.attr("style", "display: none;");
	// Crear la imagen
	var $loadingImage = $("<img>")
		.attr("src", `${base_img}/ajax-loading.gif`)
		.attr("alt", "Cargando...");
	// Añadir la imagen al div
	$loaderDiv.append($loadingImage);
	// Añadir el div al body (o a otro elemento si lo necesitas)
	$("body").append($loaderDiv);
	$(".loader").hide();
	loader = $(".loader");
}
//Exportar a Excel tablas de Consultas
function report_to_excel(e, tbl = "tblTableConMovInv") {
	var table = $("#" + tbl);
	if (table && table.length) {
		var preserveColors = table.hasClass("table2excel_with_colors")
			? true
			: false;
		$(table).table2excel({
			exclude: ".noExl",
			name: "Excel Document Name",
			filename:
				"ConMovimientos" +
				new Date().toISOString().replace(/[\-\:\.]/g, "") +
				".xls",
			fileext: ".xls",
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true,
			preserveColors: preserveColors,
		});
	}
}
//Mostrar modal, para seleccionar origen y destino de los productos, tanto almacen como ubicación para los consignados sin ubicacion
function mostar_data_inv() {
	listar_almacenes(id_emp, id_alm_def, 0, "id_alm_sal");
	listar_ubicaciones("id_ubi_sal", id_ubi_def, "N", id_emp);
	listar_almacenes(id_emp, id_alm_cli, 0, "id_alm_ent");
	listar_ubicaciones("id_ubi_ent", id_ubi_cli, "N", id_emp);
	$("#modal-AlmUbi").modal("show");
}
//AL seleccionar al Almacen de Salida en el modal anterior
$(document).on("change", "#id_alm_sal", function () {
	id_alm_def = $("#id_alm_sal").val();
	$("#id_alm_def").val(id_alm_def);
});
//AL seleccionar la ubicación de Salida en el modal anterior
$(document).on("change", "#id_ubi_sal", function () {
	id_ubi_def = $("#id_ubi_sal").val();
	$("#id_ubi_def").val(id_ubi_def);
});
//AL seleccionar la Almacen de Entrada en el modal anterior
$(document).on("change", "#id_alm_ent", function () {
	id_alm_cli = $("#id_alm_ent").val();
	$("#id_alm_cli").val(id_alm_cli);
});
//AL seleccionar la ubicación de Entrada en el modal anterior
$(document).on("change", "#id_ubi_ent", function () {
	id_ubi_cli = $("#id_ubi_ent").val();
	$("#id_ubi_cli").val(id_ubi_cli);
});
//Consulta de un Productos
var xdat_id_pro = async function (id_prod) {
	var datos = new FormData()
	datos.append("id_prod", id_prod);
	try {
		const url_1 = `${base_url}/Productos/consulta`;
		var respuesta = await fetch(url_1, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
};
//Llenar combo de Tipos de Clientes Agregado el 04-07-2025 a las 10:42:00 por José Vargas
async function listar_tipos_clientes(codigo = 0) {
	const datos = new FormData();
	try {
		const url = `${base_url}/Clientes/listar_tipos_clientes`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id"] +
						"'>" +
						result[i]["description"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id"] +
						"'>" +
						result[i]["description"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#id_tipocliente").html(cadena);
	} catch (err) {
		console.log(err);
	}
}
function diferenciaMeses(fecha1, fecha2) {
	const años = fecha2.getFullYear() - fecha1.getFullYear();
	const meses = fecha2.getMonth() - fecha1.getMonth();
	return años * 12 + meses;
}
function leftPadWithZeros(number, length) {
	return String(number).padStart(length, "0");
}
function listar_sub_grupo(id_grupo, id_sub_grupo = "", tag = "") {
	const url = `${base_url}/SubGrupos/listar_sub_grupo`;
	$.ajax({
		type: "POST",
		url: url,
		data: { id_grupo: id_grupo },
		dataSrc: "",
		dataType: "json",
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			var cadena = "";
			if (data) {
				cadena += "<option value = ''>Seleccione...</option>";
				$.each(data, function (key, value) {
					if (id_sub_grupo == value.id) {
						cadena += "<option selected value = '" + value.id + "'>" + value.sub_grupo_nombre + "</option>";
					} else {
						cadena += "<option value = '" + value.id + "'>" + value.sub_grupo_nombre + "</option>";
					}
				});
			} else {
				cadena +=
					"<option value = ''>No existen registros para el Grupo especificado</option>";
			}
			if (tag) {
				$("#" + tag).html(cadena);
			} else {
				$("#id_sub_grupo").html(cadena);
			}
		},
		error: function (xhr) {
			console.log(xhr.statusText + " " + xhr.responseText);
			$(".loader").hide();
		},
		complete: function () {
			$(".loader").hide();
		},
	});
}
//Listar Modulos
function listar_modulos(mod = "", val = "", bloquear = false, tag = "mod") {
	var mymod = mod.split(":");
	const url = `${base_url}/Empresas/listar_modulos`;
	$.ajax({
		type: "POST",
		url: url,
		data: { mod: mymod },
		dataSrc: "",
		dataType: "json",
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			var cadena = "";
			if (data) {
				cadena += "<option value = ''>Seleccione...</option>";
				$.each(data, function (key, value) {
					if (val == value.module) {
						cadena +=
							"<option selected value = '" +
							value.module +
							"'>" +
							value.nombre +
							"</option>";
					} else {
						cadena +=
							"<option value = '" +
							value.module +
							"'>" +
							value.nombre +
							"</option>";
					}
				});
			} else {
				cadena += "<option value = ''>No existen registros</option>";
			}
			if (tag) {
				$("#" + tag).html(cadena);
				if (bloquear) {
					$("#" + tag).css("pointer-events", "none");
				}
			} else {
				$("#mod").html(cadena);
				if (bloquear) {
					$("#mod").css("pointer-events", "none");
				}
			}
			if (bloquear) {
			}
		},
		error: function (xhr) {
			console.log(xhr.statusText + " " + xhr.responseText);
			$(".loader").hide();
		},
		complete: function () {
			$(".loader").hide();
		},
	});
}
//Llenar combo de Conceptos de CXP
async function listar_conceptos_BAN_EXC(codigo = 0, tag) {
	const datos = new FormData();
	//datos.append('id_emp', id_emp);
	try {
		const url = `${base_url}/BanConceptos/listar_conceptos_exc`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result) {
			cadena += "<option value = ''>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (codigo && codigo == result[i]["id_bancon"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_bancon"] +
						"'>" +
						result[i]["nom_bancon"] +
						"</option>";
				} else if (result[i]["active"] != 0) {
					cadena +=
						"<option disabled value = '" +
						result[i]["id_bancon"] +
						"'>" +
						result[i]["nom_bancon"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_bancon"] +
						"'>" +
						result[i]["nom_bancon"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		$("#" + tag).html(cadena);
	} catch (err) {
		console.log(err);
	}
}
//Llenar combo de Tipos de Docuemntos CXC
async function listar_tipos_documentos(
	id_emp,
	tipo = "",
	id_tdo = "",
	bloquear = false,
	tag
) {
	const datos = new FormData();
	let arr = tipo.split(",");
	//let arr  = tipo;
	datos.append("id_emp", id_emp);
	datos.append("tipo_tdoc", arr);
	try {
		const url = `${base_url}/TipoDocCXC/listar_tipos_documentos`;
		const resp = await fetch(url, {
			method: "POST",
			body: datos,
		});
		const result = await resp.json();
		var cadena = "";
		if (result.length > 0) {
			cadena += "<option value = '0'>Seleccione...</option>";
			for (var i = 0; i < result.length; i++) {
				if (id_tdo == result[i]["id_tdoc"]) {
					cadena +=
						"<option selected value = '" +
						result[i]["id_tdoc"] +
						"'>" +
						result[i]["nom_tdoc"] +
						"</option>";
				} else {
					cadena +=
						"<option value = '" +
						result[i]["id_tdoc"] +
						"'>" +
						result[i]["nom_tdoc"] +
						"</option>";
				}
			}
		} else {
			cadena += "<option value = ''>No existen registros</option>";
		}
		if (tag) {
			$("#" + tag).html(cadena);
		} else {
			$("#id_tdo").html(cadena);
		}
		if (bloquear) {
			$("#id_tdo").css("pointer-events", "none");
		}
	} catch (err) {
		console.log(err);
	}
}

function formatoMoneda(valor) {
	// Elimina el punto y reemplaza la coma por punto
	return parseFloat(valor.replace(/\./g, "").replace(",", "."));
}
//Mostar los Documentos pendientes de Cuentas por Cobrar, tanto desde Banco Movimientos como desde Movimientos de Cuenas por Cobrar. José Vargas 28-08-2025 a las 10:10:00
$("#modal_doc_pen_cxc").on("show.bs.modal", function () {
	var url = "";
	fecha_comp = $("#fecha_comp").val();
	id_moneda = $("#id_moneda").val();
	if (efe_bantmo == "C") {
		tabla_name = "#tblSeatDetail";
		url = `${base_url}/CXCDocument/doc_ped_cli`;
		datos = {
			id_emp: id_emp,
			id_cli: id_cli,
			fecha_comp: fecha_comp,
			id_moneda: id_moneda,
		};
	} else {
		tabla_name = "#tblSeatDetail_cxp";
		url = `${base_url}/CXPDocument/doc_ped_cli`;
		datos = {
			id_emp: id_emp,
			id_cli: id_ent,
			fecha_comp: fecha_comp,
			id_moneda: id_moneda,
		};
	}

	$.ajax({
		url: url,
		method: "POST",
		data: datos,
		dataType: "json",
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			$("#tblModalDocPend_cxc").DataTable({
				destroy: true,
				data: data,
				responsive: true,
				processing: true,
				columns: [
					{ data: "id_doc", title: "Id" },
					{ data: "tipo_codigo", title: "Código" },
					{ data: "nom_tdoc", title: "Descripción" },
					{
						data: "num_tdo",
						title: "Número",
						className: "text-right",
					},
					{
						data: "nro_control",
						title: "Control",
						className: "text-right",
					},
					{
						data: "fecha_comp",
						render: $.fn.dataTable.render.moment(
							FROM_PATTERN,
							TO_PATTERN
						),
						title: "Fecha Emi.",
					},
					{
						data: "fecha_venci",
						render: $.fn.dataTable.render.moment(
							FROM_PATTERN,
							TO_PATTERN
						),
						title: "Fecha Venc.",
					},
					{
						data: "codigo_moneda",
						title: "Moneda",
						className: "text-center",
					},
					{
						data: "tasa_cambio",
						className: "text-right",
						render: DataTable.render.number(".", ",", 8),
						title: "Tasa Cambio",
					},
					{
						data: null,
						className: "text-right",
						title: "Monto Doc.",
						render: function (data, type, row) {
							if (id_moneda == id_moneda_cia) {
								return `${format_number_with_dec_new(
									row.mon_doc_dom,
									2
								)}`;
							} else {
								return `${format_number_with_dec_new(
									row.mon_doc_for,
									2
								)}`;
							}
						},
					},
					{
						data: null,
						className: "text-right",
						title: "Saldo Doc.",
						render: function (data, type, row) {
							if (id_moneda == id_moneda_cia) {
								return `${format_number_with_dec_new(
									row.sal_doc_dom,
									2
								)}`;
							} else {
								return `${format_number_with_dec_new(
									row.sal_doc_for,
									2
								)}`;
							}
						},
					},
				],
				language: {
					url: `${base_url}/Assets/json/es-ES.json`,
				},
			});
		},
		complete: function () {
			$(".loader").hide();
		},
		error: function (error) {
			$(".loader").hide();
			console.log("Error al cargar los datos: ");
		},
	});
});
//Al seleccionar un registro de un documento a cancelar, tanto desde banco Movimitnos, como desde Cuentas por Cobrar Movimientos. José Vargas 28-08-2025 a las 120:19:00
$("body").on("click", "#tblModalDocPend_cxc tr", function () {
	try {
		item_doc++;
		var row_select = $(this).closest("tr");
		var adicional = "";
		var datosFila = row_select
			.find("td")
			.map(function () {
				return $(this).text();
			})
			.get();
		if (efe_bantmo == "C") {
			nom_table = "tblSeatDetail";
			adicional = `<td class='text-right'><input type="text" id="mon_ret${item_doc}" name="mon_ret[]" class="form-control text-right text-xs fila-input-ret " readonly></td>
            <td class='text-right'><input type="text" id="num_ret${item_doc}" name="num_ret[]" class="form-control text-right text-xs fila-input-num" value="" readonly/></td>`;
		} else {
			nom_table = "tblSeatDetail_cxp";
		}

		var tr = '<tr id="fila' + item_doc + '">';
		var htmlTags = $(tr).append(`
            <td class='text-right'><input type="text" id="id_cot${datosFila[0]}" name="id_cot[]" class="form-control text-right text-xs rid_cot" value="${datosFila[0]}" readonly/>'</td>
            <td class='text-right'>${item_doc}</td>
            <td class='text-center'>${datosFila[1]}</td>
            <td>${datosFila[2]}</td>
            <td class='text-right'>${datosFila[3]}</td>
            <td class='text-center'>${datosFila[5]}</td>
            <td class='text-center'>${datosFila[6]}</td>
            <td class='text-center'><input type="text" id="id_moneda_doc${item_doc}" name="id_moneda_doc[]" class="form-control text-center text-xs" value="${datosFila[7]}" readonly/></td>
            <td class='text-right'>${format_number_with_dec_new(formatoMoneda(datosFila[8]), 4)}</td>
            <td class='text-right'>${datosFila[9]}</td>
			<td class='text-right'><input type="text" id="sal_doc${item_doc}" name="sal_doc[]" class="form-control text-right text-xs sal_doc" readonly value="${datosFila[10]}"/></td>
            <td class='text-right'><input type="text" id="mon_can${item_doc}" name="mon_can[]" class="form-control text-right text-xs fila-input " readonly/></td>
			${adicional}
            <td class="text-center"><div class="btn-group"><input type="checkbox" name="id_check[]" class="form-check-input check-row text-xs" data-input-id="mon_can${item_doc}" data-input-ret="mon_ret${item_doc}" data-input-num="num_ret${item_doc}"/> <button type="button" class="btn btn-danger btn-xs borrar_doc" title="Eliminar item"><i class="fa fa-trash"></i></button></div></td>
        `);
		if ($("#id").val()) {
			$("#" + nom_table).append(htmlTags);
		} else {
			$("#tbody").append(htmlTags);
		}
		$("#item").val(item_doc);
		$("#modal_doc_pen_cxc").modal("hide");
	} catch (error) {
		console.log("Ha ocurrido el siguiente error: " + error);
	}
});
$(document).ready(function () {
	//Al seleccionar un registro del modal y decir que se va a cobrar, para acutlizar montos en la tabla. Jose Vargas 28-08-2025 10:42:00
	/*
	$(document).on("change", ".check-row", function(){
		check_row("tblSeatDetail", this);
	});
	
	$(document).on("change", ".check-row", function(){
		check_row("tblSeatDetail_cxp", this);
	});
	*/
})

$(document).on("change", ".check-row", function () {
	check_row(nom_table, this);
});

//Cuando se marca un registro
function check_row(table_name, esto) {
	if (!table) {
		table = $("#" + table_name).DataTable({
			info: false,
			paging: false,
			searching: false,
			ordering: false,
			destroy: true,
			language: {
				url: `${base_url}/Assets/json/es-ES.json`,
			},
		});
	}
	var row = table.row($(esto).closest("tr")); // Encuentra la fila
	var data = row.data(); // Obtiene los datos de la fila
	var closeCheckbox = $(esto)
		.closest("td")
		.parent()
		.find("input[type=checkbox]");
	var xmon_can = 0;
	var xmon_ret = 0;
	var row_select = table.row($(esto).parent()).data();
	var checkbox = $(esto);
	var inputId = closeCheckbox.data("input-id"); // Obtiene el ID del input del atributo data-*
	var inputIdRet = closeCheckbox.data("input-ret"); // Obtiene el ID del input del atributo data-*
	var inputIdNumRet = closeCheckbox.data("input-num"); // Obtiene el ID del input del atributo data-*
	var targetInput = $("#" + inputId); // Obtiene el ID del input del atributo data-*
	var targetInputRet = $("#" + inputIdRet); // Obtiene el ID del input del atributo data-*
	targetInputNumRet = $("#" + inputIdNumRet); // Obtiene el ID del input del atributo data-*
	if (closeCheckbox.prop("checked")) {
		var fila = $(esto).closest("tr");
		var valorInput = fila.find(".rid_cot").val(); // O cualquier otro selector para tu input
		id_cot = valorInput;
		var url = "";
		if (efe_bantmo == "C") {
			url = `${base_url}/CXCDocument/get_doc_cxc`;
		} else {
			url = `${base_url}/CXPDocument/get_doc_cxc`;
		}

		$.ajax({
			url: url,
			type: "POST",
			data: { id_cot: id_cot, id_moneda: id_moneda },
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			complete: function () {
				$(".loader").hide();
			},
			error: function (xhr, ajaxOptions, thrownError) {
				$(".loader").hide();
				console.log(xhr.responseText);
			},
			success: function (data) {
				if (data) {
					xmoneda = data["id_moneda"];
					xtasa_cambio = parseFloat(data["tasa_cambio"]);
					xmon_iva = 0;
					if (id_moneda == id_moneda_cia) {
						xmon_can = parseFloat(data["sal_doc_dom"]);
						xmon_iva = parseFloat(data["mon_iva_dom"]) * -1;
					} else {
						xmon_can = parseFloat(data["sal_doc_for"]);
						xmon_iva = parseFloat(data["mon_iva_for"]) * -1;
					}
					ymon_ret = parseFloat(data["mon_ret_doc"]);

					//Si es contribuyente especial, se le aplica el 75% de la retencion
					//y si no tiene iva, no se le aplica retencion
					//Validar si es contribuyente especial
					especial_contrib = data['especial_contrib'];
					if (especial_contrib == 1 && xmon_iva != 0) {
						por_reten = data['por_reten']
						//xmon_iva = parseFloat(xmon_iva * (75 / 100)).toFixed(2);
						xmon_iva = parseFloat(xmon_iva * (por_reten / 100)).toFixed(2);
					}
					if (xmon_can != 0) {
						targetInput.val(
							format_number_with_dec_new(xmon_can, 2)
						);
						targetInput.prop("readonly", false);
						targetInputRet.prop("readonly", false);
						targetInputNumRet.prop("readonly", false);
					}
					//Comentado, para que salga la retencion en cualquier caso, falta validar si ya se hizo la rentecion JV 02-09-2025
					if (xmon_iva != ymon_ret) {
						targetInputRet.val(
							format_number_with_dec_new(xmon_iva, 2)
						);
					} else {
						xmon_iva = 0;
						targetInputRet.prop("readonly", true);
						targetInputNumRet.prop("readonly", true);
					}
					/*
					if (data["mon_doc"] == data["sal_doc"] && efe_bantmo == "C") {
						targetInputRet.val(format_number_with_dec_new(xmon_iva, 2));
					} else {
						xmon_iva = 0;
						targetInputRet.val(format_number_with_dec_new(xmon_iva, 2));
					}
					*/
					if (xmon_iva != 0) {
						targetInputNumRet.prop("required", true);
						targetInputNumRet.attr(
							"title",
							"Debe ingresar el número Retención"
						);
						targetInputNumRet.attr("minlength", "14");
						targetInputNumRet.attr("maxlength", "14");
					} else {
						targetInputNumRet.removeProp("required");
						targetInputNumRet.removeAttr("title");
						targetInputNumRet.removeAttr("maxlength");
						targetInputNumRet.removeAttr("minlength");
					}
					if (xmon_iva != 0 && tpase_ret == false) {
						tpase_ret = true;
						if (TMod == "B" && efe_bantmo == "C") {
							agregarDetalle(
								"R",
								id_bancon_RETIVA,
								nom_bancon_RETIVA
							);
						}
					}
					targetInput.select();
					UpdateDataTable("#" + table_name);
				}
			},
		});
	} else {
		xmon_can = 0;
		xmon_ret = 0;

		targetInput.val(format_number_with_dec_new(xmon_can, 2));
		targetInputRet.val(format_number_with_dec_new(xmon_ret, 2));
		targetInputNumRet.val('');

		targetInput.prop("readonly", true);
		targetInputRet.prop("readonly", true);
		targetInputNumRet.prop("readonly", true);

		UpdateDataTable("#" + table_name);
	}
}

//Actualizar totales de tabla
function UpdateDataTable(nom_table) {
	if (nom_table != "#tbl_banmovin") {
		var tmon_can = 0;
		var tmon_ret = 0;
		var tot_mon_can = 0;
		$(nom_table + " tbody tr").each(function () {
			var mon_can = parseFloat(
				formatoMoneda($(this).find(".fila-input").val())
			);
			if (!isNaN(mon_can)) {
				tmon_can += parseFloat(mon_can);
			}
			if (efe_bantmo == "C") {
				var mon_ret = parseFloat(
					formatoMoneda($(this).find(".fila-input-ret").val())
				);

				if (!isNaN(mon_ret)) {
					tmon_ret += parseFloat(mon_ret);
				}
				$("#tot_ret_tbl").val(format_number_with_dec_new(tmon_ret, 2));
			}
		});
		if (efe_bantmo == "C") {
			$("#tot_can_tbl").val(format_number_with_dec_new(tmon_can, 2));
		} else {
			$("#tot_can_tbl_cxp").val(format_number_with_dec_new(tmon_can, 2));

		}

		tot_mon_can += tmon_can + tmon_ret;
		//Total Movimientos Bancarios

		if (tmon_can != 0) {
			$("#monto1").val(format_number_with_dec_new(tmon_can, 2));
		} else {
			$("#monto1").val(format_number_with_dec_new(tmon_can, 2));
		}
		if (tmon_ret != 0) {
			$("#monto" + item).val(format_number_with_dec_new(tmon_ret, 2));
		} else {
			$("#monto" + item).val(format_number_with_dec_new(tmon_ret, 2));
		}
		$("#monto1").val(format_number_with_dec_new(tmon_can, 2));
		$("#tmon_mov").val(format_number_with_dec_new(tot_mon_can, 2));
		$("#monto1").val(format_number_with_dec_new(tmon_can, 2));
		//Total Sumatoria de documetnos
		$("#tot_mon_can").val(format_number_with_dec_new(tmon_can, 2));
		$("#tot_mon_ret").val(format_number_with_dec_new(tmon_ret, 2));
	} else {
		var tot_movin = 0;
		$(nom_table + " tbody tr").each(function () {
			var monto = parseFloat(
				formatoMoneda($(this).find(".tot_movim").val())
			);
			if (!isNaN(monto)) {
				tot_movin += monto;
				$("#tmon_mov").val(format_number_with_dec_new(tot_movin, 2));
			}
		});
	}
}
//Al modificar el monto a Cancelar
$("#tblSeatDetail tbody").on("change", ".fila-input", function () {
	filas_input("#tblSeatDetail", this);
});
//Al modificar el monto a Cancelar
$("#tblSeatDetail_cxp tbody").on("change", ".fila-input", function () {
	filas_input("#tblSeatDetail_cxp", this);
});
//Al recibir el focus
$(document).on("focus", ".fila-input", function () {
	this.select();
});
$(document).on("focus", ".fila-input-ret", function () {
	this.select();
});
$(document).on("focus", ".fila-input-num", function () {
	this.select();
});
$(document).on("focus", ".monto", function () {
	this.select();
});
function filas_input(nom_table, esto) {
	const fila = $(esto).closest("tr");
	const valorDeLaFila = fila.find($(".sal_doc")); // Obtiene el valor del input en esa fila
	var saldo_actual = parseFloat($(esto).val());
	var saldo_original = parseFloat(formatoMoneda(valorDeLaFila.val()));
	if (saldo_actual > saldo_original) {
		Swal.fire({
			icon: "warning",
			title: "Monto a cancelar mayor al saldo del documento",
			text: "El monto a cancelar no puede ser mayor al saldo del documento.",
		});
		$(esto).val(format_number_with_dec_new(saldo_original, 2));
	}
	if (saldo_actual < 0 && saldo_original > 0) {
		Swal.fire({
			icon: "warning",
			title: "Monto a cancelar no puede ser menor al saldo del documento.",
			text: "El monto a cancelar no puede ser menor al saldo del documento.",
		});
		$(esto).val(format_number_with_dec_new(saldo_original, 2));
	}
	$(esto).val(format_number_with_dec_new(saldo_actual, 2));
	UpdateDataTable(nom_table);
}
//Al modificar el monto de Retención
$("#tblSeatDetail tbody").on("change", ".fila-input-ret", function () {
	const fila = $(this).closest("tr");
	const valorDeLaFila = fila.find($(".sal_doc")); // Obtiene el valor del input en esa fila
	var saldo_actual = parseFloat($(this).val());
	var saldo_original = parseFloat(formatoMoneda(valorDeLaFila.val()));
	if (saldo_actual > saldo_original) {
		Swal.fire({
			icon: "warning",
			title: "Monto a cancelar mayor al saldo del documento",
			text: "El monto a cancelar no puede ser mayor al saldo del documento.",
		});
		$(this).val(format_number_with_dec_new(saldo_original, 2));
	}
	if (saldo_actual * -1 < 0 && saldo_original > 0 && saldo_actual != 0) {
		Swal.fire({
			icon: "warning",
			title: "Monto a cancelar no puede ser menor al saldo del documento.",
			text: "El monto a cancelar no puede ser menor al saldo del documento.",
		});
		$(this).val(format_number_with_dec_new(saldo_original, 2));
	}
	if (saldo_actual == 0 || isNaN(saldo_actual)) {
		saldo_actual = 0;
		targetInputNumRet.prop("readonly", true);
		targetInputNumRet.removeAttr("required");
	}
	$(this).val(format_number_with_dec_new(saldo_actual, 2));
	UpdateDataTable("#tblSeatDetail");
});
$(document).on("keydown", ".camponumero", function (e) {
	var valor = $(this).val();
	soloNumerosConSignoYDecimal(e, this.id);
	//if (!isNaN(valor)) {
	//		$(this).val(format_number_with_dec_new(valor, 2));
	//	}

});
$(document).on("blur", ".camponumero", function (e) {
	var valor = ($(this).val());
	if (!isNaN(valor)) {
		$(this).val(format_number_with_dec_new(valor, 2));
	}
});
$(document).on("blur", ".camponumero6", function (e) {
	var valor = ($(this).val());
	if (!isNaN(valor)) {
		$(this).val(format_number_with_dec_new(valor, 6));
	} else {
		$(this).val(format_number_with_dec_new(0, 6));
	}
});
/**
 * Permite solo números (positivos o negativos) y un punto decimal en un campo de entrada.
 * @param {object} event - El objeto de evento de la pulsación de tecla.
 * @param {string} elementId - El ID del elemento de entrada.
 */
function soloNumerosConSignoYDecimal(event, elementId) {
	const inputElement = document.getElementById(elementId);
	const value = inputElement.value;
	const key = event.key;
	const keyCode = event.keyCode;

	// 1. Permite teclas de control: Backspace, Tab, Flechas, Intro, etc.
	// Los códigos 8, 9, 37-40 son comunes para Backspace, Tab y flechas.
	// keyCodes 46 (Supr) también es útil.
	if (keyCode === 8 || keyCode === 9 || keyCode === 46 || (keyCode >= 37 && keyCode <= 40)) {
		return true;
	}

	// 2. Permite el signo de menos ('-') solo si es el primer carácter
	if (key === '-') {
		if (value.indexOf('-') === -1 && inputElement.selectionStart === 0) {
			return true;
		} else {
			event.preventDefault(); // Bloquea si ya hay un '-' o no está al principio
			return false;
		}
	}

	// 3. Permite el punto decimal ('.') solo si no existe ya
	if (key === '.') {
		if (value.indexOf('.') === -1) {
			return true;
		} else {
			event.preventDefault(); // Bloquea si ya hay un '.'
			return false;
		}
	}

	// 4. Permite solo dígitos numéricos (0-9)
	if (key >= '0' && key <= '9') {
		return true;
	}

	// Bloquea cualquier otra tecla
	event.preventDefault();
	return false;
}

// Opcional: Para pegar (paste)
$(document).on('paste', 'input.numerico-con-signo', function (e) {
	const element = this;
	// Permite que la acción de pegar ocurra primero y luego limpia el valor.
	setTimeout(function () {
		// Expresión regular para permitir un '-' opcional al principio,
		// luego dígitos, un '.' opcional, y más dígitos.
		const cleanValue = element.value.replace(/[^0-9.-]/g, ''); // Elimina caracteres no deseados
		let finalValue = cleanValue;

		// Limpieza adicional para el punto decimal y múltiples signos.
		if (cleanValue.match(/(\.)/g)?.length > 1) {
			finalValue = cleanValue.replace(/\./g, (match, offset, string) => string.indexOf(match) === offset ? match : '');
		}
		if (cleanValue.match(/(\-)/g)?.length > 1) {
			finalValue = cleanValue.replace(/\-/g, (match, offset, string) => string.indexOf(match) === offset ? match : '');
		}

		// Asegura que el '-' esté al principio o no exista.
		if (finalValue.indexOf('-') > 0) {
			finalValue = finalValue.replace(/\-/g, '');
		} else if (finalValue.startsWith('-')) {
			finalValue = '-' + finalValue.replace(/\-/g, '');
		}

		element.value = finalValue;
	}, 0);
});
//funcion para elimnar una fila de detalle de documentos
$(document).on("click", ".borrar_doc", function (event) {
	event.preventDefault();
	$(this).closest("tr").remove();
	UpdateDataTable("#" + nom_table);
});
//Mostar los Documentos pendientes de Cuentas por Cobrar, tanto desde Banco Movimientos como desde Movimientos de Cuenas por Cobrar. José Vargas 28-08-2025 a las 10:10:00
$("#modal_doc_pen_cxp").on("show.bs.modal", function () {
	var url = "";
	fecha_comp = $("#fecha_comp").val();
	id_moneda = $("#id_moneda").val();
	url = `${base_url}/CXPDocument/doc_ped_cli`;
	datos = {
		id_emp: id_emp,
		id_cli: id_ent,
		fecha_comp: fecha_comp,
		id_moneda: id_moneda,
	};
	$.ajax({
		url: url,
		method: "POST",
		data: datos,
		dataType: "json",
		beforeSend: function () {
			loader.show();
		},
		success: function (data) {
			$("#tblModalDocPend_cxp").DataTable({
				destroy: true,
				data: data,
				responsive: true,
				processing: true,
				columns: [
					{ data: "id_doc", title: "Id" },
					{ data: "tipo_codigo", title: "Código" },
					{ data: "nom_tdoc", title: "Descripción" },
					{
						data: "num_tdo",
						title: "Número",
						className: "text-right",
					},
					{
						data: "nro_control",
						title: "Control",
						className: "text-right",
					},
					{
						data: "fecha_comp",
						render: $.fn.dataTable.render.moment(
							FROM_PATTERN,
							TO_PATTERN
						),
						title: "Fecha Emi.",
					},
					{
						data: "fecha_venci",
						render: $.fn.dataTable.render.moment(
							FROM_PATTERN,
							TO_PATTERN
						),
						title: "Fecha Venc.",
					},
					{
						data: "codigo_moneda",
						title: "Moneda",
						className: "text-center",
					},
					{
						data: "tasa_cambio",
						className: "text-right",
						render: DataTable.render.number(".", ",", 8),
						title: "Tasa Cambio",
					},
					{
						data: null,
						className: "text-right",
						title: "Monto Doc.",
						render: function (data, type, row) {
							if (id_moneda == id_moneda_cia) {
								return `${format_number_with_dec_new(
									row.mon_doc_dom,
									2
								)}`;
							} else {
								return `${format_number_with_dec_new(
									row.mon_doc_for,
									2
								)}`;
							}
						},
					},
					{
						data: null,
						className: "text-right",
						title: "Saldo Doc.",
						render: function (data, type, row) {
							if (id_moneda == id_moneda_cia) {
								return `${format_number_with_dec_new(
									row.sal_doc_dom,
									2
								)}`;
							} else {
								return `${format_number_with_dec_new(
									row.sal_doc_for,
									2
								)}`;
							}
						},
					},
				],
				language: {
					url: `${base_url}/Assets/json/es-ES.json`,
				},
			});
		},
		complete: function () {
			loader.hide();
		},
		error: function (error) {
			loader.hide();
			console.log("Error al cargar los datos: ", error);
		},
	});
});
$("#modal_doc_pen_cxp").on("hidden.bs.modal", function () {
	// Destruye la DataTable con ID 'miDataTable'
	$("#tblModalDocPend_cxp").DataTable().destroy();
	// O si usas la sintaxis antigua:
	// $('#miDataTable').dataTable().fnDestroy();
});
//Al seleccionar un registro de un documento a cancelar, tanto desde banco Movimitnos, como desde Cuentas por Cobrar Movimientos. José Vargas 28-08-2025 a las 120:19:00
$("body").on("click", "#tblModalDocPend_cxp tr", function () {
	try {
		item_doc++;
		var row_select = $(this).closest("tr");
		var adicional = "";
		var datosFila = row_select
			.find("td")
			.map(function () {
				return $(this).text();
			})
			.get();
		nom_table = "tblSeatDetail_cxp";
		if (!table) {
			table = $("#" + nom_table).DataTable({
				info: false,
				paging: false,
				searching: false,
				ordering: false,
				destroy: true,
				language: {
					url: `${base_url}/Assets/json/es-ES.json`,
				},
				colResizable: true,
			});
		}
		var tr = '<tr id="fila' + item_doc + '">';
		var htmlTags = $(tr).append(`
            <td class='text-right'><input type="text" id="id_cot${datosFila[0]
			}" name="id_cot[]" class="form-control text-right text-xs rid_cot" value="${datosFila[0]
			}" readonly/>'</td>
            <td class='text-right'>${item_doc}</td>
            <td>${datosFila[1]}</td>
            <td>${datosFila[2]}</td>
            <td class='text-center'>${datosFila[3]}</td>
            <td class='text-center'>${datosFila[5]}</td>
            <td class='text-center'>${datosFila[6]}</td>
            <td class='text-center'><input type="text" id="id_moneda_doc${item_doc}" name="id_moneda_doc[]" class="form-control text-center text-xs" value="${datosFila[7]
			}" readonly/></td>
            <td class='text-right'>${datosFila[8]}</td>
            <td class='text-right'>${datosFila[9]}</td>
			<td class='text-right'><input type="text" id="sal_doc${item_doc}" name="sal_doc[]" class="form-control text-right text-xs sal_doc" readonly value="${datosFila[10]}"/></td>
            <td class='text-right'><input type="text" id="mon_can${item_doc}" name="mon_can[]" class="form-control text-right text-xs fila-input " readonly/></td>
            <td class="text-center"><input type="checkbox" name="id_check[]" class="form-check-input check-row check-row" data-input-id="mon_can${item_doc}" data-input-ret="mon_ret${item_doc}" data-input-num="num_ret${item_doc}"/> <button type="button" class="btn btn-danger btn-xs borrar_doc" title="Eliminar item"><i class="fa fa-trash"></i></button>
			</td>
        `);
		table.row.add(htmlTags).draw(true);
		//$("#tbody").append(htmlTags);
		$("#item").val(item_doc);
		$("#modal_doc_pen_cxp").modal("hide");
	} catch (error) {
		console.log("Ha ocurrido el siguiente error: " + error);
	}
});
//Convertir en mayusculas
$(".mayusculas").on("input", function () {
	// Obtiene el valor actual y lo convierte a mayúsculas
	var valorMayusculas = this.value.toUpperCase();
	// Actualiza el valor del input con la versión en mayúsculas
	this.value = valorMayusculas;
});
//Buscar Datos de un Fabricante/Marca/Laboratior
async function dat_fabricantes(id_fab) {
	var datos = new FormData();
	datos.append("id", id_fab);
	try {
		const url = `${base_url}/Fabricantes/showrowfab`;
		var respuesta = await fetch(url, {
			method: "POST",
			body: datos,
		});
		var resultado = await respuesta.json();
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				resolve(resultado);
			}, 1);
		});
	} catch (err) {
		console.log(err);
	}
}
$("#id_pais").change(function () {
	var id_pais = $("#id_pais").val();
	listar_estados(id_pais);
});
$("#id_edo").change(function () {
	var id_edo = $("#id_edo").val();
	listar_ciudades(id_edo);
});
//Llenar Combo Retencion de IVA si se marca como Contribuyente especial
$("#contr_esp").on("change", function () {
	if ($(this).is(":checked")) {
		listar_retiva(0, "id_por_ret_iva");
		$("#id_por_ret_iva").css("pointer-events", "");
	} else {
		$("#id_por_ret_iva").empty();
		$("#id_por_ret_iva").css("pointer-events", "none");
	}
});
//Nuevo contacto de Proveedores y Clientes
$("#btn_accion").on("click", function () {
	item_det++;
	var htmlTags = [
		`<input type="text" class="form-control text-xs" id="nom_con${item_det}" name="nom_con[]" placeholder="Nombre" style="text-transform:uppercase;" required>`,
		`<input type="text" class="form-control text-xs" id="ape_con${item_det}" name="ape_con[]" placeholder="Apellido" style="text-transform:uppercase;" required>`,
		`<input type="email" class="form-control text-xs" id="${item_det}" name="email_con[]" placeholder="Correo" style="text-transform:lowercase;" required>`,
		`<select class="form-control select-search select2bs4 text-xs" id="id_pre${item_det}" name="id_pre[]" required></select>`,
		`<input type="text" class="form-control text-xs" id="num_tel_con${item_det}" name="num_tel_con[]" placeholder="000-00-00" pattern="[0-9]{3}-[0-9]{2}-[0-9]{2}" required>`,
		`<select class="form-control select-search select2bs4 text-xs" id="id_dep${item_det}" name="id_dep[]" id="id_dep" required></select>`,
		`<button type="button" class="btn btn-danger btn-xs borrar" title="Eliminar contacto" ><i class="far fa-trash-alt"></i></button>`,
	];
	if (!miTabledet_con) {
		miTabledet_con = $("#det_con").DataTable({
			responsive: true,
			info: false,
			paging: false,
			searching: false,
		});
	}
	miTabledet_con.row.add(htmlTags);
	miTabledet_con.draw();
	//Poblar selects
	$(".select-search").select2();
	listar_codigos_area("", `id_pre${item_det}`);
	listar_dpto_ent(0, `id_dep${item_det}`);
	//Posicionarme en el campo Nombre
	$(`#num_tel_con${item_det}`).addClass(`number-phone`);
	$(".number-phone").mask(mobileMaskBehavior, mobileOptions);
	$(`#nom_con${item_det}`).focus();
});
//AL cambiar la moneda
$("#id_moneda").on("change", async function () {
	fecha_comp = $("#fecha_comp").val();
	id_moneda = $("#id_moneda").val();
	if (id_moneda) {
		xTasaCambio = await xTasa(fecha_comp, id_moneda)
		if (xTasaCambio) {
			$("#tasa_cambio").val(xTasaCambio);
			$("#tasa_cambio").prop("readonly", true);
		}
	}
	show_tasa();
})
//Listar Roles de usuarios  
function listar_roles(id) {
	const url = `${base_url}/Roles/listar_roles`
	//Ajax para 
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: {},
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
			var $combo = $('#id_rol');
			// Limpiar combo antes de rellenar
			$combo.empty();
			// Añadir opción por defecto
    		$combo.append('<option value="">Seleccione...</option>');
			// Iterar y añadir opciones
			$.each(data, function (index, valor) {
				selected = valor.id_rol == id ? 'selected' : '';
				$combo.append(`<option ${selected} value=${valor.id_rol}> ${valor.nombre_rol}</option>`);
    		});
		},
	});
}