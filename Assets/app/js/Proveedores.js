/*
 * Funciones de Proveedores
 * Copyright 2025-2025
 * 12-11-2025 Creación de Archivo José Vargas 11:59:00
 */
// Al Iniciar la aplicación
$().ready(function () {
	//Validaciones
	$("form#my_form").validate({
		rules: {
			rif_ent: "required",
			nom_ent: {
				required: true,
				minlength: 5,
				maxlength: 200,
			},
			id_pais: "required",
			id_edo: "required",
			id_ciudad: "required",
			id_diascre: "required",
			dir_ent: {
				required: true,
				minlength: 20,
			},
			id_por_ret_iva: {
				required: function(){
					return $("#contr_esp").is(":checked");
				}
			},
			status: "required",
		},
		messages: {
			rif_ent: "Debe especificar un RIF",
			nom_ent: {
				required: "Debe especificar un nombre",
				minlength: "Debe contener al menos {0} carácteres",
				maxlength: "Debe contenedor máximo {0} carácteres",
			},
			id_pais: "Debe especificar un pais",
			id_edo: "Debe especificar un estado",
			id_ciudad: "Debe especificar una ciudad",
			id_diascre: "Debe especificar los días de crédito",
			dir_ent: {
				required: "Debe especificar una dirección",
				minlength: "Debe contener al menos {0} carácteres",
			},
			id_por_ret_iva: "Debe especificar un Porcentaje",
			status: "Debe especificar un status",
		},
	});

	//Cargar el Index
	form = $("form").attr("id");
	if (form === undefined) {
		initProveedores();
	} else {
		id = $("#id").val();
		if (id) {
			show_row(id);
		} else {
			dat_form_new();
		}
	}
});
//Consultar registro
//Registro nuevo
function show_row(id) {
	var formData = $(this).serialize();
	const url = `${base_url}/Proveedores/show_row`;
	//Ajax para
	$.ajax({
		url: url,
		method: "POST",
		dataSrc: "",
		data: { id: id },
		dataType: "json",
		beforeSend: function () {
			loader.show();
		},
		complete: function () {
			loader.hide();
		},
		error: function (PDOException) {
			loader.hide();
			console.log(
				"Ha ocurrido el siguiente error:",
				PDOException.responseText
			);
		},
		success: function (data) {
			if (data) {
				$("#rif_ent").val(data[0].rif_ent);
				$("#nom_ent").val(data[0].nom_ent);
				$("#cor_ent").val(data[0].cor_ent);
				$("#postal_ent").val(data[0].postal_ent);
				id_pais = data[0].id_pais;
				listar_paises(id_pais);
				id_edo = data[0].id_edo;
				listar_estados(id_pais, id_edo);
				id_ciudad = data[0].id_ciudad;
				listar_ciudades(id_edo, id_ciudad);
				id_diascre = data[0].id_diascre;
				listar_dias_credito(id_diascre);
				$("#dir_ent").val(data[0].dir_ent);
				contr_esp = data[0].contr_esp;

				if (contr_esp == 1) {
					$("#contr_esp").prop("checked", true);
					$("#contr_esp").trigger("change");
					id_por_ret_iva = data[0].id_por_ret_iva;
					listar_retiva(id_por_ret_iva, "id_por_ret_iva");
				}
				status = data[0].status;
				listar_status(status);
				//Poner el focus en el nombre del proveedor
				$("#nom_ent").focus();
				//Cargar contactos
				if (!miTabledet_con) {
					miTabledet_con = $("#det_con").DataTable({
						responsive: true,
						info: false,
						paging: false,
						searching: false,
						destroy: true,
						clear: true,
						language: {
							url: `${base_url}/Assets/json/es-ES.json`,
						},
					});
				}
				item_det = 0;
				$.each(data, function (index, xitem) {
					if (xitem.nom_con != null) {
						item_det++;
						var htmlTags = [
							`<input type="text" class="form-control text-xs" id="nom_con${item_det}" name="nom_con[]" placeholder="Nombre" style="text-transform:uppercase;" value="${xitem.nom_con}" >`,
							`<input type="text" class="form-control text-xs" id="ape_con${item_det}" name="ape_con[]" placeholder="Apellido" style="text-transform:uppercase;" value="${xitem.ape_con}">`,
							`<input type="email" class="form-control text-xs" id="${item_det}" name="email_con[]" placeholder="Correo" style="text-transform:lowercase;" value="${xitem.email_con}">`,
							`<select class="form-control text-xs" id="id_pre${item_det}" name="id_pre[]"></select>`,
							`<input type="text" class="form-control text-xs" id="num_tel_con${item_det}" name="num_tel_con[]" placeholder="000-00-00" pattern="[0-9]{3}-[0-9]{2}-[0-9]{2}" >`,
							`<select class="form-control text-xs" id="id_dep${item_det}" name="id_dep[]" id="id_dep"></select>`,
							`<button type="button" class="btn btn-danger btn-xs borrar" title="Eliminar contacto" ><i class="far fa-trash-alt"></i></button>`,
						];
						miTabledet_con.row.add(htmlTags);
						miTabledet_con.draw();
						//Poblar selects
						listar_codigos_area(xitem.id_pre, `id_pre${item_det}`);
						listar_dpto_ent(xitem.id_dep, `id_dep${item_det}`);
						$(`#num_tel_con${item_det}`).addClass(`number-phone`);
						$(".number-phone").mask(
							mobileMaskBehavior,
							mobileOptions
						);
						$(`#num_tel_con${item_det}`).val(xitem.num_tel_con);
						//Posicionarme en el campo Nombre
						$(`#nom_con${item_det}`).focus();
					}
				});
				//Cargar Estados de Cuenta
				const url = `${base_url}/CXPDocument/edo_cuenta_data`;
				id_ent = $("#id").val();
				nom_ent = $("#nom_ent").val();
				$("#tblCXCedo_cuenta").empty();
				var formData = $(this).serialize();
				//Ajax para
				$.ajax({
					url: url,
					method: "POST",
					dataSrc: "",
					data: { id_emp: "", id_cli: id_ent },
					dataType: "json",
					beforeSend: function () {
						loader.show();
					},
					complete: function () {
						loader.hide();
					},
					error: function (PDOException) {
						loader.hide();
						console.log("Ha ocurrido el siguiente error:", PDOException.responseText);
					},
					success: function (data) {
						var tabla = $("#tblCXCedo_cuenta");
						//crear agrupaor por Empresa con sus subtotales
						var groupColumn = 0; // Columna de Categoría
						var groupNomEnt =  1;
    					var totalColumn = 6; // Columna de Importe
						//Crear DataTable
						tabla.DataTable({
							responsive: true,
							data: data,
							dataType: "json",
							autoWidth: true,
							paging: false,
							info:false,
							columns: [
								{ data: "nombre_emp", title: "Empresa", visible: false},
								{ data: "nom_ent", title: "Proveedor", visible: false},
								{ data: "nom_tdoc", title: "Documento" },
								{ data: "fecha_comp", title: "Fecha", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN) },
								{ data: "cod_diascre", title: "Días Créd", className: "text-right"},
								{ data: "dias_calle", title: "Días Calle", className: "text-right"},
								{ data: "num_tdo", title: "Número", className: "text-right"},
								{ data: 'mon_exe_dom', title: 'Exento', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
								{ data: 'mon_base_dom', title: 'Base', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
								{ data: 'mon_iva_dom', title: 'Mon IVA', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
								{ data: 'sal_doc_dom', title: 'Por Pagar', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
								{ data: 'mon_exe_for', title: 'Exento', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
								{ data: 'mon_base_for', title: 'Base', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
								{ data: 'mon_iva_for', title: 'Mon IVA', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
								{ data: 'sal_doc_for', title: 'Por Pagar', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2)},
								{ data: null, title: 'Tasa', className: 'text-right', render: $.fn.dataTable.render.number(".", ",", 2),
									render: function(row, type, data){
										if(data.tasa_doc == 1){
											return data.tasa_dia;
										}else{
											return data.tasa_doc
										}
									}
								},
								{ data: null, title: 'Status', className: 'text-center',
									render: function(row, type, data){
										if(data.dias_calle > data.cod_diascre){
											return "VENCIDO";
										}else{
											return "VIGENTE";
										}
									}
								},
							],	
							order: [[groupColumn, "asc"], [groupNomEnt, "asc"]],
							//Logica de agrupación
							drawCallback: function(settings){
								var api = this.api();
								var rows = api.rows().nodes();
								var lastGroup = null
								var sub_mon_exe_dom = 0;
								var sub_mon_bas_dom = 0;
								var sub_mon_iva_dom = 0;
								var sub_mon_pag_dom = 0;
								var sub_mon_exe_for = 0;
								var sub_mon_bas_for = 0;
								var sub_mon_iva_for = 0;
								var sub_mon_pag_for = 0;
								var tot_mon_exe_dom = 0;
								var tot_mon_bas_dom = 0;
								var tot_mon_iva_dom = 0;
								var tot_mon_pag_dom = 0;
								var tot_mon_exe_for = 0;
								var tot_mon_bas_for = 0;
								var tot_mon_iva_for = 0;
								var tot_mon_pag_for = 0;
								//Funcion para obtener el valor numerico
								var intVal = function(i){
									return typeof i === "string" ?
									i.replace(/[\$,]/g, '') * 1 :
									typeof i === "number" ? i : 0;
								}
								//Iterar sobre los datos de la columna de agrupación (Nombre_emp)
								api.column(groupColumn).data().each(function(group, i){
									var rowData = api.row(i).data();
									//Domestico
									var imp_mon_exe_dom = intVal(rowData.mon_exe_dom);
									var imp_mon_bas_dom = intVal(rowData.mon_base_dom);
									var imp_mon_iva_dom = intVal(rowData.mon_iva_dom);
									var imp_mon_pag_dom = intVal(rowData.sal_doc_dom);
									//Foraneo
									var imp_mon_exe_for = intVal(rowData.mon_exe_for);
									var imp_mon_bas_for = intVal(rowData.mon_base_for);
									var imp_mon_iva_for = intVal(rowData.mon_iva_for);
									var imp_mon_pag_for = intVal(rowData.sal_doc_for);
									//Sub-Totales por grupo
									//Domestico
									sub_mon_exe_dom += imp_mon_exe_dom;
									sub_mon_bas_dom += imp_mon_bas_dom;
									sub_mon_iva_dom += imp_mon_iva_dom;
									sub_mon_pag_dom += imp_mon_pag_dom;
									//Foraneo
									sub_mon_exe_for += imp_mon_exe_for;
									sub_mon_bas_for += imp_mon_bas_for;
									sub_mon_iva_for += imp_mon_iva_for;
									sub_mon_pag_for += imp_mon_pag_for;
									//Acumula el totall general
									//Domestivo
									tot_mon_exe_dom += imp_mon_exe_dom;
									tot_mon_bas_dom += imp_mon_bas_dom;
									tot_mon_iva_dom += imp_mon_iva_dom;
									tot_mon_pag_dom += imp_mon_pag_dom;
									//Foraneo
									tot_mon_exe_for += imp_mon_exe_for;
									tot_mon_bas_for += imp_mon_bas_for;
									tot_mon_iva_for += imp_mon_iva_for;
									tot_mon_pag_for += imp_mon_pag_for;
									//Detectar cambio de Empresa
									if (lastGroup !== group) {
										//Si no es el primer grupo, inserta el subtotal del grupo anterior
										if (lastGroup !== null) {
											$(rows)
												.eq(i - 1)
												.after(
													`<tr>
													<th colspan="5">Subtotal ${lastGroup}</th>
													<th class="text-right">${format_number_with_dec_new(sub_mon_exe_dom, 2)}</th>
													<th class="text-right">${format_number_with_dec_new(sub_mon_bas_dom, 2)}</th>
													<th class="text-right">${format_number_with_dec_new(sub_mon_iva_dom, 2)}</th>
													<th class="text-right">${format_number_with_dec_new(sub_mon_pag_dom, 2)}</th>
													<th class="text-right">${format_number_with_dec_new(sub_mon_exe_for, 2)}</th>
													<th class="text-right">${format_number_with_dec_new(sub_mon_bas_for, 2)}</th>
													<th class="text-right">${format_number_with_dec_new(sub_mon_iva_for, 2)}</th>
													<th class="text-right">${format_number_with_dec_new(sub_mon_pag_for, 2)}</th>
													<th colspan="2"></th>
												</tr>`
												);
											//Reiniciar subtotal
											//Domestico
											sub_mon_exe_dom = 0;
											sub_mon_bas_dom = 0;
											sub_mon_iva_dom = 0;
											sub_mon_pag_dom = 0;
											//Foraneo
											sub_mon_exe_for = 0;
											sub_mon_bas_for = 0;
											sub_mon_iva_for = 0;
											sub_mon_pag_for = 0;
										}
										//Insertar la fila de encabezado del nuevo grupo
										$(rows)
											.eq(i)
											.before(
												`<tr><th colspan="15" style="font-weight: bold; background-color:#f2f2f2;">${group}</th></tr>`
											);
										lastGroup = group;
									}
								});
								//Insertar el ultimo subtotal
								if(lastGroup !== null){
									$(rows).eq(api.rows({ page: 'current' }).count() - 1).after(
                    					`<tr>
											<th colspan="5">Subtotal ${lastGroup}</th>
											<th class="text-right">${format_number_with_dec_new(sub_mon_exe_dom,2)}</th>
											<th class="text-right">${format_number_with_dec_new(sub_mon_bas_dom,2)}</th>
											<th class="text-right">${format_number_with_dec_new(sub_mon_iva_dom,2)}</th>
											<th class="text-right">${format_number_with_dec_new(sub_mon_pag_dom,2)}</th>
											<th class="text-right">${format_number_with_dec_new(sub_mon_exe_for,2)}</th>
											<th class="text-right">${format_number_with_dec_new(sub_mon_bas_for,2)}</th>
											<th class="text-right">${format_number_with_dec_new(sub_mon_iva_for,2)}</th>
											<th class="text-right">${format_number_with_dec_new(sub_mon_pag_for,2)}</th>
											<th colspan="2"></th>
										</tr>`
                					);
								}
							},
							language: {
								url: `${base_url}/Assets/json/es-ES.json`,
							},
						});
					}
				});
			}
		},
	});
}
function dat_form_new() {
	listar_paises(0);
	listar_dias_credito(0);
	listar_codigos_area();
	listar_dpto_ent(0, "id_dep");
	listar_status(1);
}

//Refrescar la tabla
$(".refresh-button").on("click", function () {
	tableIndex.ajax.reload(null, false);
});
//Eliminar un registro
$("#tblIndexMain").on("click", ".btn-delete-index", function () {
	var recordId = $(this).data("id"); // Obtiene el ID del registro
	var recordCode = $(this).data("code"); // Obtine el Tipo Doc
	var recordName = $(this).data("name"); // Obtine el nombre
	var descrip = `¿Está seguro de eliminar el Proveedor ${recordCode} - ${recordName}?.`;
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
			const url = `${base_url}/Proveedores/destroy`;
			$.ajax({
				url: url, // URL de tu script de eliminación en el servidor
				type: "POST",
				data: {
					id: recordId,
					recordCode: recordCode,
					recordName: recordName,
				},
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
							tableIndex.ajax.reload(null, false);
						}
					});
				},
				error: function (xhr, status, error) {
					loader.hide();
					console.log("Hubo un error en la solicitud.", error);
				},
			});
		}
	});
});
//funcion para borrar un contacto
$("#det_con").on("click", ".borrar", function (e) {
	e.preventDefault();
	var table = $("#det_con").DataTable();
	var index = $(this).closest("tr").index(); // Obtiene el índice de la fila en la tabla actual
	table.row(index).remove().draw(false); // Elimina
});
//Guardar y/o Actualizarregistro
$("#my_form").on("submit", function (e) {
	e.preventDefault();
	if ($(this).valid()) {
		var formData = $(this).serialize();
		const url = `${base_url}/Proveedores/store`;
		//Ajax para Guardar y/o Actualizar
		$.ajax({
			url: url,
			method: "POST",
			dataSrc: "",
			data: formData,
			dataType: "json",
			beforeSend: function () {
				loader.show();
			},
			complete: function () {
				loader.hide();
			},
			error: function (PDOException) {
				loader.hide();
				console.log(
					"Ha ocurrido el siguiente error:",
					PDOException.responseText
				);
			},
			success: function (data) {
				Swal.fire({
					title: data.title,
					text: data.msg,
					icon: data.icon,
				}).then((result) => {
					if (data.icon != "error") {
						window.location.href = `${base_url}/Proveedores`;
					}
				});
			},
		});
	} else {
		return false;
	}
});
