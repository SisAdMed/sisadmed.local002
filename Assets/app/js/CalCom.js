//Validar formulario
let table;
let tasa_cambio = "";
item = 0;
$().ready(function(){
        jQuery.validator.setDefaults({
            debug: false,
            success: "valid",
        });
      $("form[name='my_form']").validate({
			rules: {
				id_emp: "required",
                fec_ini: "required",
                fec_final: "required",
				status: "required",
			},
			messages: {
                id_emp: "Debe especificar una Empresa",
                fec_ini: "Debe especificar una Fecha de Inicio",
                fec_final: "Debe especificar una Fecha de Corte",
				status: "Debe especificar un status",
			},
		});
	//Cargar Index
	form = $("form").attr("id");
	if (form === undefined) {
		initCalComTable();
	} else {
		id = $("#id").val();
		if (id) {
			show_row(id);
		} else {
			listar_empresas();
			listar_vendedores();
			listar_status(1);
		}
	}
	
})
$("#id_vend").change( async function () {
    $("#tblCalCom").empty();
    tasa_cambio = "";
    id_emp = $("#id_emp").val();
    id_vend = $("#id_vend").val();
    fec_ini = $("#fec_ini").val();
    fec_fin = $("#fec_final").val();
    if (id_emp != "" && fec_ini != "" && fec_fin != "" && id_vend != "" && id_vend > 0) {
        fecha_comp = GetTodayDate(0);
		id_moneda = 2;
		tasa_cambio = await getExchangerate(fecha_comp, id_moneda);
        	const resultado = await Swal.fire({
				title: "Indique la fecha para obtener el cambio a facturar",
				input: "date",
				inputValidator: (fecha) => {
					if (!fecha) {
						return "Debe indicar una fecha válida";
					} else {
						return undefined;
					}
				},
				html: `
					<h1>Cambio: <small id="cambio" style="color:#007bff;"></small></h1>
					`,
				didOpen: async () => {
					const input = await Swal.getInput();
					const $cambio = await Swal.getHtmlContainer().querySelector(
						"#cambio"
					);
					input.oninput = async () => {
						$cambio.textContent = "";
						const cambio = await getExchangerate(input.value, 2);
						if (cambio != "") {
							ytasa_cambio = `${cambio}`;
							$cambio.textContent = `${cambio}`;
						}
					};
				},
				showCancelButton: true,
				confirmButtonText: "Ok",
				cancelButtonText: "Cancelar",
			});
            if (resultado.value) {
				tasa_cambio = ytasa_cambio;
				listar_tabla();
			}else{
                tasa_cambio =''; 
                listar_tabla();
            }
    }else{
        listar_tabla();
    }
    
});
//AL Cambiar la fecha final
$("#fec_final").change(function () {
	tasa_cambio = "";
	id_emp = $("#id_emp").val();
	id_vend = $("#id_vend").val();
	fec_ini = $("#fec_ini").val();
	fec_fin = $("#fec_final").val();
	if (id_emp != "" && fec_ini != "" && fec_fin != "") {
		listar_tabla();
	}
});
//Listar tabla de CalCom
function listar_tabla(){
    div_loading();
    const url = `${base_url}/CalCom/listar_tabla`; 
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: { id_emp: id_emp, fec_ini: fec_ini, fec_fin: fec_fin, id_vend: id_vend, id: id},
        beforeSend: function(){
            loader.show();
        },
        complete: function(){
            loader.hide();          
        },
        error: function(error){
            loader.hide();          
            console.log('Ha ocurrido el siguiente error: ', error.responseText);
        }, 
        success: function(resultado){
            if(resultado){  
                report = "Reporte_Calculo_Comisiones_";
                table = $("#tblCalCom").DataTable({
					data: resultado,
					destroy: true,
                    clear: true,
                    paging: false, // Generalmente se desactiva la paginación para subtotales
					columns: [
                        { data: "id_vend", title: "Id Vendedor", className: "text-right", visible: false },
                        { data: "vendedor", title: "Vendedor"},
                        { data: "nom_tdoc", title: "Tipo de Documento"},
                        { data: "num_tdo", title: 'Número de Documento', className: "text-right" },
                        { data: "fec_fact", title: "Fecha de Factura", className: "text-center", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN)},
                        { data: "fec_pag", title: "Fecha de Pago", className: "text-center", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN)},
                        { data: "nom_ent", title: "Cliente"},
                        { data: "sub_total", title: "Sub-total", className: "text-right", render: $.fn.dataTable.render.number( '.', ',', 2, '' ) },
                        { data: "comi_vend", title: "% Comisión", className: "text-right", render: $.fn.dataTable.render.number( '.', ',', 2, '' ) },
                        { data: "tot_comision", title: "Total Comisión", className: "text-right", render: $.fn.dataTable.render.number( '.', ',', 2, '' ) },
                        { data: "id_cot", title: "Id Doc", className: "text-right", visible: false },
                        { data: "id_ent", title: "Id Ent", className: "text-right", visible: false },
                        { data: null, title: "Tasa de Cambio", className: "text-right",
                            render: function(data, type, row){
                                if(tasa_cambio != ""){
                                    return (tasa_cambio);
                                }else{
                                    return format_number_with_dec_new(row.tasa_cambio, 2);
                                }
                            }
                        },
                        { data: null, title: "Total Comisión Bs.", className: "text-right",
                            render: function(data, type, row){
                                if(tasa_cambio != ""){
                                    return format_number_with_dec_new(formatoMoneda(tasa_cambio) * row.tot_comision, 2);
                                }else{
                                    return format_number_with_dec_new(row.tot_comision  * row.tasa_cambio, 2);
                                }
                            }
                        },
                        { data: null, title: "Acciones", className: "text-center", 
                            render: function (data, type, row) {
                                return `<button id="Data" type="button" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash" title="Elimianr registro"></i></button>`;
                            },
                        },
                    ],
                    rowGroup: {
                        dataSrc: 1
                    },
                    drawCallback: function (settings) {
                        const api = this.api();
                        const filas = api.rows({ page: "current" }).nodes();
                        const data = api.rows({ page: "current" }).data();
                        let ultimoVendedor = null;
                        let subtotalVenta = 0;
                        let subtotalComision = 0;
                        //Recorrer datos
                        data.each(function(d, index){
                            const vendedorCurrent = d.vendedor;
                            const isLastRow = (index === data.length - 1);
                            //Si es el tultimo vendedro cambia a o , es la ulitma fila, insertmaos sub-total
                            if(ultimoVendedor !== null && vendedorCurrent !== ultimoVendedor){
                                //Insertar la fiula de subtotal ANTES del registro actual
                                insertRowSubtotal(filas, index, ultimoVendedor, subtotalVenta, subtotalComision);
                                //Resetear los contadores
                                subtotalVenta = 0;
                                subtotalComision = 0;
                            }
                            //Acumular valores
                            subtotalVenta += d.tot_comision;
                            if(tasa_cambio != ""){
                                subtotalComision += d.tot_comision * formatoMoneda(tasa_cambio);
                            }else{
                                subtotalComision += d.tot_comision * d.tasa_cambio;
                            }
                            
                            ultimoVendedor = vendedorCurrent;
                            //Insertar el subtotal para el ultimo grupo si llegamos al final de los datos
                            if(isLastRow){
                                //Insertar la fila de subtoal despues del ultimo registro
                                insertRowSubtotal(filas, index + 1, ultimoVendedor, subtotalVenta, subtotalComision);
                            }
                        })
                    },
                    order: [[1, 'asc'], [5, 'asc']],
                    language: {
                        url: `${base_url}/Assets/json/es-ES.json`,
                    },
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "Todos"],
                    ],
                    iDisplayLength: -1,
				});
            }
        }
    });
}
//Funcion auxiliar para inyectar la fila de subtotal
function insertRowSubtotal(filas, index, vendedor, venta, comision){
    //Crear el HTML para la nueva fila
    const subtotalHtml = `<tr class="subtotal-row">
            <td colspan="8" style="font-weight: bold;" class="text-right">TOTAL ${vendedor}</td>
            <td style="text-align: right; font-weight: bold;">${format_number_with_dec_new(venta, 2)}</td>
            <td></td>
            <td style="text-align: right; font-weight: bold;">${format_number_with_dec_new(comision, 2)}</td>
        </tr>`;

        // Insertar la fila de subtotal en la posición correcta
        if (index === filas.length) {
            // Si es el último grupo, agregarlo al final del <tbody>
            $("#tblCalCom tbody").append(subtotalHtml);
        } else {
            // Insertar antes del <tr> en la posición actual
            $(filas[index]).before(subtotalHtml);
        }
}
//funcion para elimnar una fila de detalle de Calculo de Comisiones
$(document).on("click", ".btn-delete", function (event) {
	event.preventDefault();
    var t = $('#tblCalCom').DataTable();
    let $str = $(this).closest("tr");
    t.row($str).remove().draw();
});
//Guardar y/o Actualizar
$("#my_form").on("submit", function (e) {
	e.preventDefault();
    var tblObj = $("#tblCalCom").DataTable().rows().data().toArray();
	//var dattab = tblObj.rows().data().toArray();        
	if ($(this).valid()) {		
        var formData = new FormData(this);
        formData.append('dattab', JSON.stringify(tblObj));
        formData.append('tasa_cambio', formatoMoneda(tasa_cambio));        
		const url = `${base_url}/CalCom/store`;
		$.ajax({
			type: "POST",
			url: url,
			dataSrc: "",
			data: formData,
            dataType: "json",
            contentType: false, // Fundamental
            processData: false, // Fundamental
			beforeSend: function () {
				loader.show();
			},
			complete: function () {
				loader.hide();
			},
			error: function (PDOException) {
				loader.hide();
				console.log("Ha ocurrido el siguiente error: " + PDOException.responseText );
			},
			success: function (data) {
				Swal.fire({
					title: data.title,
					text: data.msg,
					icon: data.icon,
				}).then((result) => {
					if (data.icon != "error") {
						window.location.href = `${base_url}/CalCom`;
					}
				});
			},
		});
	} else {
		return false;
	}
});
//Mostrar fila para editar
function show_row(id) {
    const url = `${base_url}/CalCom/show_row`;
    $.ajax({
        url: url,
        type: 'POST', 
        dataType: 'json',
        data: {id: id},
        beforeSend: function(){ 
            loader.show();
        },
        complete: function(){ 
            loader.hide();     
        },
        error: function(error){ 
            loader.hide(); 
            console.log('Ha ocurrido el siguiente error: ', error.responseText);
        }, 
        success: function(resultado){ 
            if(resultado){     
                id_emp = resultado.id_emp;
                id_vend = resultado.id_vend;    
                fec_ini = resultado.fec_ini;
                fec_fin = resultado.fec_fin;    
                status = resultado.status;
                listar_empresas(id_emp, true);
                listar_vendedores(id_vend, true);
                listar_status(status);
                $("#fec_ini").val(resultado.fec_ini);
                $("#fec_final").val(resultado.fec_fin);
                listar_tabla(id);
            }   
        }
    });
}
//Borrar un registro de Calculo de Comisiones
$(document).on("click", ".btn-delete-index", function (event) {
    event.preventDefault();
    let id = $(this).data("id");
    let name = $(this).data("name");
    Swal.fire({ 
        title: `¿Está seguro de eliminar el registro ${name}?`,
        text: "Esta acción no se puede deshacer.",
        icon: "warning",    
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",  
        confirmButtonText: "Sí, eliminarlo",
        cancelButtonText: "Cancelar",
    }).then((result) => {   
        if (result.isConfirmed) {
            const url = `${base_url}/CalCom/destroy`;
            $.ajax({    
                url: url,
                type: "POST",
                dataType: "json",   
                data: { id: id },
                beforeSend: function () {
                    loader.show();
                },
                complete: function () {
                    loader.hide();
                },  
                error: function (error) {
                    loader.hide();
                    console.log("Ha ocurrido el siguiente error: ", error.responseText);
                },
                success: function (data) {
                    Swal.fire({
                        title: data.title,      
                        text: data.msg,
                        icon: data.icon,
                    }).then((result) => {
                        if (result.isConfirmed) { 
                            tblIndexMain.ajax.reload(null, false);
                        }   
                    });
                },
            });
        }       
    });
});
function print_excel(e){
    let row = e.dataset.id;
    let name = e.dataset.name;
    Swal.fire({
		icon: "question",
		title: `¿Está seguro que desea imprimir el Cálculo de Comisiones número ${row} del ${name}?`,
		showDenyButton: true,
	}).then((result) => {
		if (result.isConfirmed) {
			window.open(`${base_url}/CalCom/report/${row}`, "_blank");
        }
});
}
