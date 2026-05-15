//Inventario
//Productos
function initProductosTable(){
    const title = "Inventaios - Prodcutos";
    const origen = "Productos";
    const id_menu = 52;
    get_permiso(id_menu);
    IndexDataTable(origen, tblIndexMain, title, 
        [
        {
			data: null,
			title: "Acciones",
			className: "text-center",
			render: function (data, type, row) {
				var t_menu = "";
				if (permisos_cre == 1 && permisos_cre == 1) {
					t_menu += `<a type="button" data-toggle="tooltip" data-placement="top" title="Consultar registro" class="btn btn-warning btn-xs all" href="${base_url}/${origen}/gestion/${row.token_edit}"><i class="fa fa-edit"></i></a>     `;
				}
				if (permisos_del == 1) {
					t_menu += `<button id="Data" data-toggle="tooltip" data-placement="top" title= "Eliminar registro" data-id="${row.id_prod}" data-name="${row.nom_prod}" data-code = "${row.cod_prod}" type="button" class="btn btn-danger btn-xs btn-delete all"><i class="fa fa-trash"></i></button>     `;
				}
                if(row.fotos >0){
                    t_menu += `<a type="button" data-toggle="tooltip" data-placement="top" title="Ver fotos de producto" data-id="${row.id_prod}" data-code="${row.cod_prod}" data-name="${row.nom_prod}" class="btn btn-success btn-xs btn-ver-fotos all"><i class="fa fa-eye"></i></a>     `;
                }
				//Copiar producto
				if (permisos_cre == 1) {
					t_menu += `<button id="Data" data-toggle="tooltip" data-placement="top" title= "Copiar registro" data-id="${row.id_prod}" data-name="${row.nom_prod}" data-code = "${row.id_prod}" type="button" class="btn btn-primary btn-xs btn-clonar all"><i class="fa fa-copy"></i></button>     `;
				}
				return t_menu;
			},
		},        
        { data: "id_prod", title: "Id", className: "text-right", visible: true},
        { data: "cod_prod", title: "Código"},
        { data: "cod2_prod", title: "Código 2"},
        { data: "nom_prod", title: "Descripción"},
        { data: "ref_prod", title: "Referencia"},
        { data: "nom_fab", title: "Marca"}, 
        { data: null, title: "Fotos", className: "text-center",
            render: function(data, type, row){
                if(row.fotos > 0){
                    return '<span class="badge badge-primary">Si</span>';
                }else{
                    return '<span class="badge badge-danger">No</span>';
                }
            }
        },
        { data: "fotos", title: "Cant.", className: "text-center"},
        { data: "costo_prod", title: "Costo", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4)}, 
        { data: "flete_prod", title: "Flete", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4)},
        { data: "otros_prod", title: "Ot.Cargos", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4)},
        { data: "door_costo", title: "Costo Door to Door", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4)},
        { data: null, title: "Costo 1", className: "text-right", 
            render: function (data, type, row){
                return format_number_with_dec_new(row.costo_prod + row.flete_prod + row.otros_prod + row.door_costo, 4);
            }
        },
        { data: "recar_prod", title: "% Util.", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4)},
        { data: "ventas_prod", title: "Venta", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4)},
        { data: "bultos", title: "Bul.Emp"},
        { data: "nom_pre", title: "Unidades"},
        { data: "empaque", title: "Empaque"},
        { data: "grupo_nombre", title: "Grupo"},
        { data: "gen_prod", title: "Nombre Genérico"},
        { data: "des_prod", title: "Descripción del Producto"},
        { data: "uni_com_prod", title: "Un. Compra", className: "text-right"},
        { data: "uni_ven_prod", title: "Un. Venta", className: "text-right"},
        { data: "con_cons_prod", title: "Util. Consig", className: "text-right"},
        { data: "conv_prod_cons", title: "Vta. Consig", className: "text-right"},
        { data: null, title: "IVA", className: "text-center", 
            render: function(data, type, row){
                if(row.iva_prod == 1){
                    return 'Si';
                }else{
                    return 'No';
                }
            }
        },
        { data: null, title: "Lote", className: "text-center", 
            render: function(data, type, row){
                if(row.lote_prod == 1){
                    return 'Si';
                }else{
                    return 'No';
                }
            }
        },
        { data: null, title: "Interno", className: "text-center", 
            render: function(data, type, row){
                if(row.interno_prod == 1){
                    return 'Si';
                }else{
                    return 'No';
                }
            }
        },
        { data: null, title: "Door to Door", className: "text-center", 
            render: function(data, type, row){
                if(row.door_prod == 1){
                    return 'Si';
                }else{
                    return 'No';
                }
            }
        },
        { data: "recar2_prod", title: "% Utilidad Consig", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4)},
        { data: "venta2_prod", title: "Venta Consignación", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4)},
        { data: null, title: "Origen", className: "text-center", 
            render: function(data, type, row){
                if(row.origen == "I"){
                    return 'Importado';
                }else{
                    return 'Nacional';
                }
            }
        },
        { data: "alto", title: "Alto", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4)},
        { data: "ancho", title: "Ancho", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4)},
        { data: "largo", title: "Largo", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4)},
        { data: "adicional", title: "Adicional", className: "text-center"},
        { data: "stock", title: "Stock", className: "text-right"},
		{ data: "creado_por", title: "Creado por"},
		{ data: "create_date", title: "Creado el"},
		{ data: "modificado_por", title: "Modificado por"},
		{ data: "modify_date", title: "Modificado el"},
        {
			data: null,
			title: "Status",
			className: "text-center",
			render: function (data, type, row) {
				if (row.status == 1) {
					return '<span class="badge badge-success">Activo</span>';
				} else if (row.status == 0) {
					return '<span class="badge badge-danger">Inactivo</span>';
				} else if (row.status == 9) {
					return '<span class="badge badge-warning">Por aprobar</span>';
				}
			},
		},
    ],
    "",
    function(settings, json){        
        var api = this.api();
        if(json[0].admin != 1){
            for(var i=9; i<= 36; i++){
                api.column(i).visible(false);
            }
            
        }
    })
}

/**
 * Obtener Datos de Presentaciones
 *
 * @param {*} id id del producto seleccionado (opcional)
 * @param {string} [tag='']  Etiqueta del formulario
 */
function getPresentacion(id, tag ){
    const url = `${base_url}/Presentaciones/getPresentacion`;
	//Ajax para 
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: '',
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
			var $combo = $(`#${tag}`);
			// Limpiar combo antes de rellenar
			$combo.empty();
			// Añadir opción por defecto
            $combo.append('<option value="">Seleccione...</option>');
			// Iterar y añadir opciones
			$.each(data, function (index, valor) {
				selected = valor.id_pre == id ? 'selected' : '';
				$combo.append(`<option ${selected} value=${valor.id_pre}> ${valor.nom_pre}</option>`);
            });
		},
	});
}
/**
 * Obtener Datos de Marcas
 *
 * @param {*} id id de la marca seleccionada (opcional)
 * @param {*} tag Etiqueta del formulario
 */
function getMarcas(id, tag){
    const url = `${base_url}/Fabricantes/getMarcas`;	
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: '',
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
			var $combo = $(`#${tag}`);
			// Limpiar combo antes de rellenar
			$combo.empty();
			// Añadir opción por defecto
            $combo.append('<option value="">Seleccione...</option>');
			// Iterar y añadir opciones
			$.each(data, function (index, valor) {
				selected = valor.id_fab == id ? 'selected' : '';
				$combo.append(`<option ${selected} value=${valor.id_fab}> ${valor.nom_fab}</option>`);
            });
		},
	});
}
/**
 * Obtener Grupos
 *
 * @param {*} id id del grupo seleccionado (opcional)
 * @param {*} tag Etiqueta del formulario
 */
function getGrupos(id, tag){
    const url = `${base_url}/Grupos/getGrupos`;	
	$.ajax({
		url: url,
		method: 'POST',
		dataSrc: '',
		data: '',
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
			var $combo = $(`#${tag}`);
			// Limpiar combo antes de rellenar
			$combo.empty();
			// Añadir opción por defecto
            $combo.append('<option value="">Seleccione...</option>');
			// Iterar y añadir opciones
			$.each(data, function (index, valor) {
				selected = valor.id_grupo == id ? 'selected' : '';
				$combo.append(`<option ${selected} value=${valor.id_grupo}> ${valor.grupo_nombre}</option>`);
            });
		},
	});
}

/**
 * Obtener Origen
 *
 * @param {*} id Id del Origen seleccionado
 */
function getorigen(id){
    miCombo = $("#origen");
    var data = [        
        { id: 'N', name: 'Nacional'},
        { id: 'I', name: 'Importado'}
    ]  
    miCombo.empty();
    miCombo.append('<option value="">Seleccione...</option>');  
    $.each(data, function(index, valor){
        selected = valor.id == id ? 'selected' : '';
        miCombo.append(`<option ${selected} value=${valor.id}> ${valor.name}</option>`);
    });    
}

/**
 * Obtener Sub Grupos
 *
 * @param {*} id id del subgrupo seleccionado 
 * @param {*} tag Etiqueta del Formulario
 */
function getSubgrupos(id, tag, id_sub_grupo = ''){
    const url = `${base_url}/SubGrupos/getSubgrupos`;	
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
			var $combo = $(`#${tag}`);
			// Limpiar combo antes de rellenar
			$combo.empty();
			// Añadir opción por defecto
            $combo.append('<option value="">Seleccione...</option>');
			// Iterar y añadir opciones
			$.each(data, function (index, valor) {
				selected = valor.id  == id_sub_grupo ? 'selected' : '';
				$combo.append(`<option ${selected} value=${valor.id}> ${valor.sub_grupo_nombre}</option>`);
            });
		},
	});
}