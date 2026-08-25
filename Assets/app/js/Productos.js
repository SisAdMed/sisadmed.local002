//Variables 
item = '';
//Al iniciar la aplicación
$(document).ready(function () {
    //Validar datos del formulario   
    $("#my_form").validate({
        ignore: [],
        rules: {
            cod_prod: {
                required: true,
                minlength: 5,
                maxlength: 150,
            },
            cod2_prod: {
                required: true,
                minlength: 5,
                maxlength: 150,
            },
            nom_prod: {
                required: true,
                minlength: 5,
                maxlength: 255,
            },
            id_presen1: "required",
            id_presen2: "required",
            id_pre: "required",
            id_fab: "required",
            id_grupo: "required",
            id_sub_grupo: "required",
            origen: "required",
            alto: "required",
            ancho: "required",
            largo: "required",
            uni_com_prod: "required",
            uni_ven_prod: "required",
            costo_prod: "required",
            door_costo: {
                required: function (element) {
                    return $("#door_prod").is(":checked");
                }
            }
        },
        messages: {},
        invalidHandler: function (event, validator) {
            // 1. Limpiamos todos los asteriscos previos
            $(".error-star").text("");
            // 2. Recorremos los campos con error
            $.each(validator.errorList, function (index, error) {
                // Buscamos el tab-pane más cercano al input con error
                var tabId = $(error.element).closest('.tab-pane').attr('id');
                // Buscamos el enlace (tab) que apunta a ese ID y le ponemos el asterisco
                $('a[href="#' + tabId + '"]').find(".error-star").text(" *");
            });
        },
        success: function (label, element) {
            // Opcional: Quitar el asterisco si el campo ya es válido
            var tabPane = $(element).closest('.tab-pane');
            var tabId = tabPane.attr('id');
            // Si ya no hay más inputs con clase 'error' en este panel, quitamos el asterisco
            if (tabPane.find("input.error").length === 0) {
                $('a[href="#' + tabId + '"]').find(".error-star").text("");
            }
        }
    })
    //Cargar el index
    form = $("form").attr("id");
    if (form === undefined) {
        initProductosTable();
    } else {
        //Cuando es un registro nuevo
        id = $("#id").val();
        $(".creado_por").hide();
        $(".modificado_por").hide();
        if (id) {
            dat_form(id);
        } else {
            //Si es copia cde un producto                
            var jsonClonar = localStorage.getItem('data_copiar');
            if (jsonClonar) {
                var data = JSON.parse(jsonClonar);
                id = data.id_prod;
                dat_form(id, true);
                localStorage.removeItem('data_copiar');
            } else {
                dat_form_new()
                $('form:first *:input[type!=hidden]:visible:first').focus();
                $('#door_costo').prop('readonly', true);
            }

        }
    }
    /*Converti en Mayusculas todos los inputs**/
    $('#my_form input[type="text"]').on('input', function () {
        $(this).val(function (_, val) {
            return val.toUpperCase();
        });
    });
    //Clonar producto  
    $(document).on('click', '.btn-clonar', function (e) {
        e.preventDefault();
        e.stopPropagation(); // Evita que el evento suba a otros elementos
        var table = $('#tblIndexMain').DataTable();
        var row = $(this).closest('tr');
        if (row.hasClass('child')) {
            row = row.prev();
        }
        var data = table.row(row).data();
        let name = data.nom_prod;
        let id = data.id_prod;
        Swal.fire({
            title: `Desea copiar el producto ${name} como un nuveo producto?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Copiar',
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.setItem('data_copiar', JSON.stringify(data));
                //Redireccionamos            
                window.location.href = `${base_url}/Productos/nuevo`;


            }
        });
    });
    /**Al momento de seleccionar un Grupo se actualiza el select de SubGrupo */
    $("#id_grupo").on("change", function () {
        id_grupo = $(this).val();
        getSubgrupos(id_grupo, 'id_sub_grupo');
    });
    /*Validar si Usa Cargos Door to Door*/
    $("#door_prod").on("change", function () {
        if ($(this).is(":checked")) {
            $('#door_costo').prop('readonly', false);
        } else {
            $('#door_costo').val('').trigger('input');
            $('#door_costo').prop('readonly', true);
        }

    });
    /*Calcular Precio de Venta desde la Utilidad*/
    $("#recar_prod").on("input", function () {
        var v_coma = $(this).val();
        if (v_coma.includes(',')) {
            var valor = formatoMoneda(v_coma);
        } else {
            var valor = parseFloat(v_coma);
        }
        if (valor > 0) {
            var costo1 = formatoMoneda($("#costo1").val());
            var ventas_prod = costo1 / valor
            $("#ventas_prod").val(format_number_with_dec_new(ventas_prod, 4));
        }
    });
    /*Calcular Precio de venta Consignacions desde la Utilidad Consignacion*/
    $("#recar2_prod").on("input", function () {
        var v_coma = $(this).val();
        if (v_coma.includes(',')) {
            var valor = formatoMoneda(v_coma);
        } else {
            var valor = parseFloat(v_coma);
        }
        if (valor > 0) {
            var costo1 = formatoMoneda($("#costo1").val());
            var venta2_prod = costo1 / valor
            $("#venta2_prod").val(format_number_with_dec_new(venta2_prod, 4));
        }
    });
    /**Calcular el % de utilida de consginacion en caso de ambiar el valor de ventas2 */
    $("#venta2_prod").on("input", function () {
        var v_coma = $(this).val();
        var valor = 0;
        if (v_coma.includes(',')) {
            valor = formatoMoneda(v_coma);
        } else {
            var valor = parseFloat(v_coma);
        }
        if (valor > 0) {
            var costo1 = formatoMoneda($("#costo1").val());
            var recar2_prod = costo1 / valor;
            $("#recar2_prod").val(format_number_with_dec_new(recar2_prod, 4));
        }
    });
    /**Calcular el % de utilidad en caso de cambiar el valor de ventas */
    $("#ventas_prod").on("input", function () {
        var v_coma = $(this).val();
        var valor = 0;
        if (v_coma.includes(',')) {
            valor = formatoMoneda(v_coma);
        } else {
            var valor = parseFloat(v_coma);
        }
        if (valor > 0) {
            var costo1 = formatoMoneda($("#costo1").val());
            var recar_prod = costo1 / valor;
            $("#recar_prod").val(format_number_with_dec_new(recar_prod, 4));
        }
    });
    /**Guardar y/o Actualizar */
    $("#my_form").on("submit", function (e) {
        e.preventDefault();
        var boton = $("#btnok");
        boton.prop('disabled', true);
        if ($(this).valid()) {
            var formData = new FormData(this);
            const url = `${base_url}/Productos/store`;
            $.ajax({
                type: 'POST',
                url: url,
                dataSrc: '',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function () {
                    $(".loader").show();
                },
                complete: function () {
                    boton.prop('disabled', false);
                    $(".loader").hide();
                },
                error: function (PDOException) {
                    $(".loader").hide();
                    console.log(PDOException);
                },
                success: function (data) {
                    Swal.fire({
                        title: data.title,
                        text: data.msg,
                        icon: data.icon,
                    }).then((result) => {
                        if (data.icon != 'error') {
                            window.location.href = `${base_url}/Productos`;
                        }
                    });
                }
            });
        } else {
            boton.prop('disabled', false);
            return false
        }
    });
    /**Elimina registro */
    $(document).on('click', '.btn-delete', function (e) {
        //Capturar datos del producto
        e.preventDefault();
        e.stopPropagation(); // Evita que el evento suba a otros elementos
        var table = $('#tblIndexMain').DataTable();
        var row = $(this).closest('tr');
        if (row.hasClass('child')) {
            row = row.prev();
        }
        var data = table.row(row).data();
        let id = data.id_prod;
        let name = data.nom_prod;
        let code = data.code;
        Swal.fire({
            icon: 'question',
            title: 'Está seguro de desactivar este registro?',
            showConfirmButton: true,
            confirmButtonText: 'ELIMINAR',
            confirmButtonColor: '#3085d6',
            showCancelButton: true,
            cancelButtonText: 'CANCELAR',
            cancelButtonColor: '#d33',
            buttonsStyling: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${base_url}/Productos/destroy`,
                    method: 'POST',
                    data: { id: id, name: name, code: code },
                    dataType: 'json',
                    success: function (data) {
                        Swal.fire({
                            icon: `${data.icon}`,
                            title: `${data.title}`,
                            text: `${data.msg}`,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                table.ajax.reload(null, false);
                            };
                        });
                    }
                })
            };
        });
    });
    //Validar que el código ingresado no este duplicado
    $("#cod_prod").on('input', function (e) {
        var datos = new FormData();
        var cod_prod = $(this).val();
        var id = $("#id").val();
        cod_prod.replace(/ /g, "");
        datos.append('cod_prod', cod_prod);
        datos.append('id', id);
        var url = `${base_url}/Productos/val_cod_pro`;
        fetch(url, {
            method: 'POST',
            body: datos
        }).then(response => response.json())
            .then(data => {
                if (data.success == 1) {
                    Swal.fire({
                        icon: "warning",
                        title: "Oops...",
                        text: "Código " + cod_prod + " ya existe en otro producto",
                    });
                }
            })
            .catch(err => console.log(err))
    });
    /**Mostratr modal de Fotos */
    $(document).on('click', '.btn-ver-fotos', function (e) {
        e.preventDefault();
        e.stopPropagation();
        // Capturamos el ID directamente del atributo data-id del botón
        var idProducto = $(this).data('id');
        var code = $(this).data('code');
        var name = $(this).data('name');
        // 1. Limpiar el contenido viejo del modal inmediatamente
        $('#sortable-galeria').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>');
        // 2. Actualizar el título del modal con el nombre del producto
        $('#tituloModalFotos').html(`<i class="fas fa-images"></i> Fotos de: <span class="text-warning">${name} ${code}</span>`);
        // 1. Cargar las fotos vía AJAX
        $.ajax({
            url: `${base_url}/Productos/showImg`,
            type: 'POST',
            data: { id: idProducto },
            dataType: 'json',
            success: function (fotos) {
                let html = '';
                fotos.forEach(foto => {
                    html += `
                <div class="col-md-3 col-sm-4 col-6 mb-4 item-foto" data-id="${foto.id_prod}">
                    <div class="card h-100">
                        <div class="card-body p-1 text-center">
                            <img src="${foto.url_photo}" class="img-fluid rounded">
                        </div>
                    </div>
                </div>`;
                });
                $('#sortable-galeria').html(html);

                // 2. Activar la función de "Arrastrar y Soltar" (Sortable)
                $("#sortable-galeria").sortable({
                    placeholder: "ui-sortable-placeholder col-md-3 col-sm-4 col-6 mb-4",
                    update: function (event, ui) {
                        // Si el usuario mueve algo, mostramos el botón de guardar orden
                        $('#btnGuardarOrden').fadeIn();
                    }
                });
                $("#modalGaleria").modal("show");
            }
        });
    });
    $("#utilidad").on("blur", function () {
        cargarTablaDinamica();
    })
    // Escuchamos los cambios en el input de monto de recargo
    $('#contenedor-tabla').on('blur', '.txt-monto-recarga', function () {
        let $input = $(this);
        let $fila = $input.closest('tr'); // Obtenemos la fila (TR) actual    

        // 1. Obtener los valores numéricos de forma segura
        let costo1 = parseFloat($input.data('costo')) || 0;
        let montoRecargo = parseFloat($input.val()) || 0;

        // 2. Realizar los cálculos matemáticos
        let pre_new = costo1 / montoRecargo;

        // 4. Actualizar los valores en los textos de las columnas correspondientes    
        $fila.find('.col-prec-new').text(pre_new.toLocaleString('es-ES', { minimumFractionDigits: 4, maximumFractionDigits: 4 }));
    });
    $("#btnGuardar").off('click').on('click', function (e) {
        e.preventDefault();
        let tabla = $('#tablaDinamica').DataTable();
        let registros = [];
        // Recorrer las filas de la tabla
        tabla.rows().every(function (rowIdx, tableLoop, rowLoop) {
            let dataOriginal = this.data(); // Contiene el objeto original (id, costo1, descripcion, etc.)
            let $nodoFila = $(this.node()); // Convierte la fila HTML (TR) en objeto jQuery        
            // Extraemos los valores calculados dinámicamente que están en los textos de las celdas
            let util_new = $nodoFila.find('.txt-monto-recarga').val();
            let prec_new = $nodoFila.find('.col-prec-new').text();

            // Armamos un nuevo objeto combinado
            registros.push({
                id_prod: dataOriginal.id_prod,
                nom_prod: dataOriginal.nom_prod,
                costo1: dataOriginal.costo1,
                ref_prod: dataOriginal.ref_prod,
                util_new: parseFloat(util_new.replace(",", ".")) || 0,
                prec_new: parseFloat(prec_new.replace(",", ".")) || 0,
            });
        });
        var boton = $("#btnGuardar");
        boton.prop('disabled', true);
        formData = new FormData();
        formData.append('id_prod', $("#id_prod").val());
        formData.append('id_fab', $("#id_fab").val());
        formData.append('registros', JSON.stringify(registros));
        const url = `${base_url}/Productos/store_utilidad`;
        $.ajax({
            type: 'POST',
            url: url,
            dataSrc: '',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function () {
                $(".loader").show();
            },
            complete: function () {
                boton.prop('disabled', false);
                $(".loader").hide();
            },
            error: function (PDOException) {
                $(".loader").hide();
                console.log(PDOException);
            },
            success: function (data) {
                console.log(data);
                Swal.fire({
                    title: data.title,
                    text: data.msg,
                    icon: data.icon,
                }).then((result) => {
                    if (data.icon != 'error') {
                        window.location.href = `${base_url}/Productos/change_utility`;
                    }
                });
            }
        });
    });
    getMarcas(0, 'id_fab');
    sumar_costo_prod();
});

/** Nuevo Registro */
function dat_form_new() {
    //Estado del producto
    getStateProducts('', 'estado_id');
    //Empaque
    getPresentacion('', 'id_presen1');
    //Presentacin por empaque
    getPresentacion('', 'id_presen2');
    //Presentacón Final
    getPresentacion('', 'id_pre');
    //Marca
    getMarcas('', 'id_fab');
    //Grupo
    getGrupos('', 'id_grupo');
    //Origen
    getorigen('0');
    //Marca para Facturacion    
    getMarcas('', 'id_fab_fac');
    //Status
    listar_status(1);

}
/**Consultar Producto */
function dat_form(id, clonar = false) {
    const url = `${base_url}/Productos/show_row`;
    //Llenar Detalles de Productos    
    $.ajax({
        url: url,
        method: 'POST',
        dataSrc: '',
        data: { id: id },
        dataType: 'json',
        beforeSend: function () {
            // Muestra una ventana de SweetAlert2 con spinner activo
				Swal.fire({
					title: 'Cargando...',
					text: `Cargando datos del producto, por favor espere.`,
					allowOutsideClick: false,
					allowEscapeKey: false,
					showConfirmButton: false,
					didOpen: () => {
						Swal.showLoading(); // Inicia la animación de carga
					}
				});
        },        
        complete: function () {
            Swal.close();
        },
        error: function (PDOException) {
            loader.hide();
            console.log('Ha ocurrido el siguiente error:', PDOException.responseText);
        },
        success: function (data) {
            $("#cod_prod").val(data[0]['cod_prod']);
            $("#cod2_prod").val(data[0]['cod2_prod']);
            $("#nom_prod").val(decodificarHTML(data[0]['nom_prod']));
            $("#gen_prod").val(decodificarHTML(data[0]['gen_prod']));
            $("#ref_prod").val(data[0]['ref_prod']);
            //Estado del producto
            getStateProducts(data[0]['estado_id'], 'estado_id');
            //Empaque
            getPresentacion(data[0]['id_presen1'], 'id_presen1');
            //Presentacin por empaque
            getPresentacion(data[0]['id_presen2'], 'id_presen2');
            //Presentacón Final
            getPresentacion(data[0]['id_pre'], 'id_pre');
            //Marca
            getMarcas(data[0]['id_fab'], 'id_fab');
            //Grupo
            getGrupos(data[0]['id_grupo'], 'id_grupo');
            //Sub-Grupo
            getSubgrupos(data[0]['id_grupo'], 'id_sub_grupo', data[0]['id_sub_grupo']);
            //Origen
            getorigen(data[0]['origen']);
            $("#alto").val(data[0]['alto']);
            $("#ancho").val(data[0]['ancho']);
            $("#largo").val(data[0]['largo'])
            iva_prod = data[0]['iva_prod'];
            $('#iva_prod').prop('checked', false);
            if (iva_prod == 1) {
                $('#iva_prod').prop('checked', true);
            }
            lote_prod = data[0]['lote_prod'];
            $('#lote_prod').prop('checked', false);
            if (lote_prod == 1) {
                $('#lote_prod').prop('checked', true);
            }
            interno_prod = data[0]['interno_prod'];
            $('#interno_prod').prop('checked', false);
            if (interno_prod == 1) {
                $('#interno_prod').prop('checked', true);
            }
            $("#uni_com_prod").val(data[0]['uni_com_prod']);
            $("#uni_ven_prod").val(data[0]['uni_ven_prod']);
            $("#con_cons_prod").val(data[0]['con_cons_prod']);
            $("#conv_prod_cons").val(data[0]['conv_prod_cons']);
            costo_prod = data[0]['costo_prod'];
            flete_prod = data[0]['flete_prod'];
            otros_prod = data[0]['otros_prod'];
            door_costo = data[0]['door_costo'];
            $("#costo_prod").val(format_number_with_dec_new(costo_prod, 4));
            $("#flete_prod").val(format_number_with_dec_new(flete_prod, 4));
            $("#otros_prod").val(format_number_with_dec_new(otros_prod, 4));
            $("#door_costo").val(format_number_with_dec_new(door_costo, 4));
            costo1 = data[0]['costo1'];
            recar_prod = data[0]['recar_prod'];
            ventas_prod = data[0]['ventas_prod'];
            recar2_prod = data[0]['recar2_prod'];
            venta2_prod = data[0]['venta2_prod'];
            status = data[0]['status'];
            $("#costo1").val(format_number_with_dec_new(costo1, 4));
            $("#recar_prod").val(format_number_with_dec_new(recar_prod, 4));
            $("#ventas_prod").val(format_number_with_dec_new(ventas_prod, 4));
            $("#recar2_prod").val(format_number_with_dec_new(recar2_prod, 4));
            $("#venta2_prod").val(format_number_with_dec_new(venta2_prod, 4));
            listar_status(status);
            $("#stock_minimo").val(data[0]['stock_minimo']);
            $("#stock").val(data[0]['stock']);
            $("#des_prod").val(decodificarHTML(data[0]['des_prod']));
            $("#commet_prod").val(decodificarHTML(data[0]['commet_prod']))
            id_fab_fac = data[0]['id_fab_fac'];
            //Marca para Facturacion    
            getMarcas(id_fab_fac, 'id_fab_fac');
            //Datos de Etiqueta
            $("#regsan_prod").val(decodificarHTML(data[0]['regsan_prod']));
            $("#cpe_prod").val(decodificarHTML(data[0]['cpe_prod']));
            $("#nomcor_prod").val(decodificarHTML(data[0]['nomcor_prod']));
            $("#marcom_prod").val(decodificarHTML(data[0]['marcom_prod']));
            $("#fabpor_prod").val(decodificarHTML(data[0]['fabpor_prod']));
            $("#connetpro_prod").val(decodificarHTML(data[0]['connetpro_prod']));
            $("#connetcaj_prod").val(decodificarHTML(data[0]['connetcaj_prod']));
            $("#uso_prod").val(decodificarHTML(data[0]['uso_prod']));
            creado_por = data[0]['creado_por'];
            if (creado_por) {
                $(".creado_por").show();
                $("#creado_por").val(creado_por);
                $("#create_date").val(data[0]['create_date']);
            }
            modificado_por = data[0]['modificado_por'];
            if (modificado_por) {
                $(".modificado_por").show();
                $("#modificado_por").val(modificado_por);
                $("#modify_date").val(data[0]['modify_date']);
            }
        }
    })
    if (!clonar) {
        //Llenar Historico de Precios
        if ($.fn.DataTable.isDataTable('#tblTableHis')) {
            $('#tblTableHis').DataTable().destroy();
        }
        $("#tblTableHis").DataTable({
            ajax: {
                url: `${base_url}/Productos/charge_history`,
                method: 'POST',
                data: { id: id },
                dataSrc: '',
                dataType: 'json'
            },
            columns: [
                {
                    data: "fecha", title: "Fecha",
                    render: function (data, type, row) {
                        if (!data) return "";
                        if (type === 'sort' || type === 'type') {
                            return moment(data).isValid() ? moment(data).format('YYYYMMDDHHmmss') : data;
                        }
                        return moment(data).format(TO_PATTERNHH);
                    }
                },
                { data: "id", title: "Id", className: "text-right", visible: false },
                { data: "costo_prod", title: "Costo", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4) },
                { data: "flete_prod", title: "Flete", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4) },
                { data: "otros_prod", title: "Otros Cargos", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4) },
                { data: "door_costo", title: "Door Cargos", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4) },
                { data: "costo1", title: "Total Costo", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4) },
                { data: "recar_prod", title: "Recarga 1", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4) },
                { data: "ventas_prod", title: "Ventas 1", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4) },
                { data: "recar2_prod", title: "Recarga Consign.", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4) },
                { data: "venta2_prod", title: "Ventas Consig.", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4) },
                { data: "usuario", title: "Usuario" },
            ],
            order: [[0, 'desc']],
            language: {
                url: `${base_url}/Assets/json/es-ES.json`,
            },
        });
        //Cargar Imaganes del producto
        $.ajax({
            url: `${base_url}/Productos/showImg`,
            method: 'POST',
            data: { id: id },
            dataSrc: '',
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    let tagHtml = '';
                    let i = 0;
                    imgPreview = $("#imgPreview");
                    data.forEach(img => {
                        i++;
                        tagHtml += `
                              <div class="card" id="cardimg${i}" style="display:inline-block;">
                                <img id="img${i}" name="img${i}" width="200px" height="200px" src="${img.url_photo}" title="${img.filename}">
                                <button id="detimg" data-id="${img.id_photo}" data-name="${img.url_photo}" data-code="${img.url_photo}" type="button" title="Eliminar imagén" class="btn btn-danger" onclick="deleteimg(this)"><i class="fa-sharp fa-solid fa-xmark"></i></i></button>
                              </div>
                        `;
                    })
                    imgPreview.html(tagHtml);
                }
            }
        })
    }
}
/*Actualizar el campo Costo 1, con la suma de costo, fletete, otros carrgos, cargos door*/
function sumar_costo_prod() {
    $(".costo_prod").on("input", function () {
        var total = 0;
        $(".costo_prod").each(function () {
            var v_coma = $(this).val();
            if (v_coma.includes(',')) {
                var valor = formatoMoneda(v_coma);
            } else {
                var valor = parseFloat(v_coma);
            }
            if (!isNaN(valor)) {
                total += valor;
            }
        });
        var costo1 = total;
        $("#costo1").val(format_number_with_dec_new(costo1, 4));
        recar_prod = $("#recar_prod").val();
        if (recar_prod.includes(',')) {
            recar_prod = formatoMoneda(recar_prod);
            ventas_prod = format_number_with_dec_new(costo1 / recar_prod, 4);
            $('#ventas_prod').val(ventas_prod).trigger('input');
            $('#venta2_prod').val(ventas_prod).trigger('input');
        }
    });
}
function cargarTablaDinamica() {
    let columnas = [];
    let urlDestino = "";
    // 1. Definir las columnas
    columnas = [
        { id: "item", title: "# item", className: "text-right" }, // <--- Columna para la numeración
        { data: "id_prod", title: "Id", className: "text-right", visible: false },
        { data: "cod_prod", title: "Código" },
        { data: "ref_prod", title: "Referencia" },
        { data: "nom_prod", title: "Nombre" },
        { data: "costo1", title: "Costo", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4) },
        { data: "recar_prod", title: "% Utilidad Actual", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4) },
        { data: "ventas_prod", title: "Precio Actual", className: "text-right", render: $.fn.dataTable.render.number(".", ",", 4) },
        // --- NUEVAS COLUMNAS DINÁMICAS ---        
        { id: "util_new", title: "% Utilidad Nuevo", className: "text-right col-util-new", render: $.fn.dataTable.render.number(".", ",", 4) },
        { id: "prec_new", title: "Precio Nuevo", className: "text-right col-prec-new", render: $.fn.dataTable.render.number(".", ",", 4) },

    ];
    urlDestino = `${base_url}/Productos/change_utility_data`;
    // 2. Destruir la instancia de DataTable si ya existía una tabla renderizada
    if ($.fn.DataTable.isDataTable('#tablaDinamica')) {
        $('#tablaDinamica').DataTable().destroy();
    }
    // 3. Construir el esqueleto HTML de la nueva tabla
    let htmlTabla = `
        <table id="tablaDinamica" class="table table-bordered table-stiped table-hover no-wrap compact" style="width:100%">
        <thead>
            <tr>`;
    // Agregamos las cabeceras dinámicamente
    columnas.forEach(col => {
        htmlTabla += `<th>${col.title}</th>`;
    });
    htmlTabla += `
            </tr>
        </thead>
        <tbody></tbody>
    </table>`;
    // Inyectamos el HTML en el contenedor
    $("#contenedor-tabla").html(htmlTabla);
    // 4. Mapear las columnas para la configuración de DataTables
    let dtColumns = columnas.map(col => {
        if (col.id === "item") {
            return {
                "data": null, // No viene del JSON del servidor
                "sortable": false, // Evita que el usuario ordene por esta columna
                "render": function (data, type, row, meta) {
                    // meta.row es el índice base 0 de la fila, le sumamos 1
                    return meta.row + 1;
                }
            };
        }

        // 2. Columna que calculará el monto excedente del recargo
        if (col.id === "util_new") {
            return {
                "data": null,
                "sortable": false,
                "className": "text-right col-util-new",
                "render": function (data, type, row) {
                    let recargo = formatoMoneda($("#utilidad").val());
                    let new_recargo = (parseFloat(row.recar_prod) + parseFloat(recargo));
                    return `<input id="util_new" name="util_new" type="text" class="form-control-xs text-xs text-right col-util-new txt-monto-recarga" value="${format_number_with_dec_new(new_recargo, 4)}" data-costo="${row.costo1}">`;
                }
            };
        }
        if (col.id === "prec_new") {
            return {
                "data": null,
                "sortable": false,
                "className": "text-right col-prec-new",
                "render": function (data, type, row) {
                    let recargo = parseFloat($("#utilidad").val().replace(',', '.')) || 0;
                    let new_recargo = parseFloat(row.recar_prod) + recargo;
                    let new_precio = parseFloat(row.costo1) / new_recargo;
                    return format_number_with_dec_new(new_precio, 4); // Suma del recargo actual y el nuevo
                }
            };
        }
        return { "data": col.data, "title": col.title, "className": col.className || "", "visible": col.visible !== undefined ? col.visible : true, "render": col.render || null };
    });

    // 5. Inicializar el DataTable con AJAX
    id_prod = $("#id_prod").val();
    id_fab = $("#id_fab").val();
    //
    $('#tablaDinamica').DataTable({
        "ajax": {
            "url": urlDestino,
            "type": "POST",
            "dataSrc": "", // Si el JSON devuelve un array directo []
            "dataType": "json",
            "data": { id_prod: id_prod, id_fab: id_fab },
            processData: true,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        },
        "columns": dtColumns,
        "responsive": true,
        "autoWidth": false,
        // --- AQUÍ EXIGES MOSTRAR TODOS POR DEFECTO ---
        "pageLength": -1, // -1 le indica a DataTables que muestre "Todos" los registros
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]], // Opciones que tendrá el usuario
        "language": {
            url: `${base_url}/Assets/json/es-ES.json`,
        }
    });
}