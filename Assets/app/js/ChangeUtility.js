//Al inicial la aplicacin.
$(document).ready(function () {
    //Validar datos del formulario   
    //Cargar el index
    form = $('form').attr('id');
    if (form === undefined) {
        initChangeUtility();
    } else {
        id = $("#id").val();
        if (id) {
            dat_form(id);
        } else {
            $('#fecha').val(getLocalDateTime());
            getMarcas('', 'id_fab');
            listar_status(1);
        }
    }
    $("#utilidad").on("blur", function () {
        utilidad = formatoMoneda($("#utilidad").val());
        if(utilidad != 0) {
            cargarTablaDinamica('nuevo');
        }            
    })
    $('#btnChangeUtility').on('click', function () {        
        utilidad = formatoMoneda($("#utilidad").val());
        if(utilidad != 0) {
            cargarTablaDinamica('nuevo');
        }            
    });
    // Escuchamos los cambios en el input de monto de recargo
    $('#contenedor-tabla').on('blur', '.txt-monto-recarga', function () {
        let $input = $(this);
        let $fila = $input.closest('tr'); // Obtenemos la fila (TR) actual    

        // 1. Obtener los valores numéricos de forma segura
        let costo1 = parseFloat(formatoMoneda($input.data('costo'))) || 0;
        let montoRecargo = parseFloat(formatoMoneda($input.val())) || 0;

        // 2. Realizar los cálculos matemáticos
        let pre_new = costo1 / montoRecargo;

        // 4. Actualizar los valores en los textos de las columnas correspondientes    
        $fila.find('.col-prec-new').text(pre_new.toLocaleString('es-ES', { minimumFractionDigits: 4, maximumFractionDigits: 4 }));
    });
    /**Aprobar Registro */
    $(document).on('click', '.btn-aprobar', function (e) {
        e.preventDefault();
        e.stopPropagation(); // Evita que el evento suba a otros elementos
        var table = $('#tblIndexMain').DataTable();
        var row = $(this).closest('tr');
        if (row.hasClass('child')) {
            row = row.prev();
        }
        var data = table.row(row).data();
        let id = data.id;
        let code = moment(data.fecha).format('DD-MM-YYYY HH:mm:ss');;
        //
        Swal.fire({
            icon: 'question',
            title: 'Está seguro de aprobar el registro de fecha ' + code + '?',
            showConfirmButton: true,
            confirmButtonText: 'APROBAR',
            confirmButtonColor: '#3085d6',
            showCancelButton: true,
            cancelButtonText: 'CANCELAR',
            cancelButtonColor: '#d33',
            buttonsStyling: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${base_url}/ChangeUtility/approve`,
                    method: 'POST',
                    data: { id: id, code: code },
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
        let id = data.id;
        let code = data.fecha;
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
                    url: `${base_url}/ChangeUtility/destroy`,
                    method: 'POST',
                    data: { id: id, code: code },
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
    // 1. Define the custom validation method for datetime-local
    $.validator.addMethod("datetimeLocal", function (value, element) {
        // Return true if optional and empty
        if (this.optional(element)) {
            return true;
        }

        // Match the standard HTML5 datetime-local format: YYYY-MM-DDTHH:mm
        // Also optionally supports seconds format (YYYY-MM-DDTHH:mm:ss)
        const regex = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}(:\d{2})?$/;
        if (!regex.test(value)) {
            return false;
        }

        // Validate actual calendar dates (e.g., preventing Feb 30th)
        const date = new Date(value);
        return !isNaN(date.getTime());
    }, "Debe especificar una fecha y hora válida");
});
function cargarTablaDinamica(modo = 'nuevo', idConsulta = null) {
    let columnas = [];
    let urlDestino = "";
    let ajaxData = {};
    if (modo == 'consultar') {
        urlDestino = `${base_url}/ChangeUtility/show_row_det`;
        ajaxData = { id: idConsulta };
    } else {
        urlDestino = `${base_url}/Productos/change_utility_data`;
        ajaxData = {
            id_prod: $("#id_prod").val(),
            id_fab: $("#id_fab").val()
        }
    }
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
        { id: "util_new", title: "% Utilidad Nuevo", className: "text-right col-util-new camponumero", render: $.fn.dataTable.render.number(".", ",", 4) },
        { id: "prec_new", title: "Precio Nuevo", className: "text-right col-prec-new", render: $.fn.dataTable.render.number(".", ",", 4) },

    ];

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
                    if (modo === 'nuevo') {
                        let recargo = formatoMoneda($("#utilidad").val());
                        let new_recargo = (parseFloat(row.recar_prod) + parseFloat(recargo));
                        return `<input id="util_new" name="util_new" type="text" class="form-control-xs text-xs text-right col-util-new txt-monto-recarga" value="${format_number_with_dec_new(new_recargo, 4)}" data-costo="${row.costo1}">`;
                    } else {
                        return `<input id="util_new" name="util_new" type="text" class="form-control-xs text-xs text-right col-util-new txt-monto-recarga" value="${format_number_with_dec_new(row.util_new, 4)}" data-costo="${row.costo1}">`;
                    }
                }
            };
        }
        if (col.id === "prec_new") {
            return {
                "data": null,
                "sortable": false,
                "className": "text-right col-prec-new",
                "render": function (data, type, row) {
                    let new_precio = row.pre_new;
                    if (modo === 'nuevo') {
                        let recargo = parseFloat($("#utilidad").val().replace(',', '.')) || 0;
                        let new_recargo = parseFloat(row.recar_prod) + recargo;
                        new_precio = parseFloat(row.costo1) / new_recargo;
                    }

                    return format_number_with_dec_new(new_precio, 4); // Suma del recargo actual y el nuevo
                }
            };
        }
        return { "data": col.data, "title": col.title, "className": col.className || "", "visible": col.visible !== undefined ? col.visible : true, "render": col.render || null };
    });

    // 5. Inicializar el DataTable con AJAX

    $('#tablaDinamica').DataTable({
        "ajax": {
            "url": urlDestino,
            "type": "POST",
            "dataSrc": "", // Si el JSON devuelve un array directo []
            "dataType": "json",
            "data": ajaxData,
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
function getLocalDateTime() {
    const now = new Date();
    // Ajustar por el desfase de la zona horaria local
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    // Formato final: YYYY-MM-DDTHH:mm
    return now.toISOString().slice(0, 16);
}
function dat_form(id) {
    const url = `${base_url}/ChangeUtility/show_row`;
    $.ajax({
        url: url,
        method: 'POST',
        dataSrc: '',
        data: { id: id },
        dataType: 'json',
        beforeSend: function () {
            loader.show();
        },
        complete: function () {
            loader.hide();
        },
        error: function (PDOException) {
            loader.hide()
            console.log('Ha ocurrido el siguiente error:', PDOException.responsetext);
        },
        success: async function (data) {
            $("#fecha").val(data[0]['fecha']);
            $("#fecha").prop('readonly', true);
            id_prod = data[0]['id_prod'];
            if (id_prod != 0) {
                $("#id_prod").val(id_prod);
                dat_prod = await xdat_id_pro(id_prod);
                $("#nom_prod").val(dat_prod.nom_prod);
            }

            $('#btn-buscar-prod')
                .removeAttr('data-toggle') // Le quitamos la capacidad de abrir el modal
                .addClass('disabled pe-none') // Estilo deshabilitado y sin eventos de ratón
                .css('pointer-events', 'none') // Seguridad extra por si usas una versión antigua de Bootstrap
                .attr('tabindex', '-1');
            getMarcas(data[0]['id_fab'], 'id_fab');
            $("#id_fab").prop("disabled", true);
            $("#utilidad").val(format_number_with_dec_new(data[0]['utilidad'], 2));
            $("#utilidad").prop('readonly', true);
            status = data[0]['status'];
            listar_status(status);
            //Poblar DataTable
            cargarTablaDinamica('consultar', id);
            aprobado = data[0]['aprobado']
            if (aprobado == 1) {
                $("#btnok").prop('disabled', true);
            }
        }
    })
}
$('#modal-productos').on('show.bs.modal', function (e) {
    //Preparar carga de la tabla
    url = `${base_url}/Productos/listar_productos_modal`;
    try {
        $.ajax({
            method: "POST",
            url: url,
            success: function (response) {
                response = JSON.parse(response);
                $('#tblModalProd').DataTable().clear();
                $('#tblModalProd').DataTable().destroy();
                var tblModal = $('#tblModalProd').DataTable({
                    fnCreatedRow: function (rowEl, response) {
                        $(rowEl).attr('id', response['id_prod']);
                    },
                    data: response,
                    columns: [{
                        data: "id_prod",
                        title: "Id",
                        className: "text-right"
                    },
                    {
                        data: "cod_prod",
                        title: "Código"
                    },
                    {
                        data: "cod2_prod",
                        title: "Código 2"
                    },
                    {
                        data: "nom_prod",
                        title: "Nombre"
                    },
                    {
                        data: "ref_prod",
                        title: "Referencia"
                    },
                    {
                        data: "nom_fab",
                        title: "Fabricante"
                    },
                    {
                        data: "stock",
                        title: "Stock",
                        className: "text-right"
                    },
                    ],
                    language: {
                        url: `${base_url}/Assets/json/es-ES.json`,
                    }
                });
            },
            error: function (xhr, status, error) {
                console.log("error");
                toastr.error(error, "Error");
            }
        });
    } catch (error) {
        console.log(error);
    }
});
//Seleccionar registro marcado del Modal de clietnes y mostrarlo en el formulario
$('body').on('click', '#tblModalProd tr', async function () {
    id_prod = $(this).attr('id');
    $("#id_prod").val(id_prod);
    dat_prod = await xdat_id_pro(id_prod);
    $("#nom_prod").val(dat_prod.nom_prod);
    $('#modal-productos').modal('hide')
});
$("#my_form").on("submit", function (e) {
    e.preventDefault();
    var boton = $("#btnok");
    boton.prop('disabled', true);
    fecha = $("#fecha").val();
    utilidad = formatoMoneda($("#utilidad").val());
    status = $("#status").val();
    if (fecha && utilidad != 0 && status) {
        let tabla = $('#tablaDinamica').DataTable();
        let registros = [];
        // Recorrer las filas de la tabla
        tabla.rows().every(function (rowIdx, tableLoop, rowLoop) {
            let dataOriginal = this.data(); // Contiene el objeto original (id, costo1, descripcion, etc.)
            let $nodoFila = $(this.node()); // Convierte la fila HTML (TR) en objeto jQuery        
            // Extraemos los valores calculados dinámicamente que están en los textos de las celdas
            let util_new = $nodoFila.find('.txt-monto-recarga').val();
            let prec_new = $nodoFila.find('.col-prec-new').text();
            let fecha_enca = $("#fecha").val();
            let id_prod_enca = $("#id_prod").val();
            let id_fab_emca = $("#id_fab").val();
            let util_enca = formatoMoneda($("#utilidad").val());
            let status = $("#status").val();
            // Armamos un nuevo objeto combinado
            registros.push({
                fecha: fecha_enca,
                id_prod_enca: id_prod_enca,
                id_fab_emca: id_fab_emca,
                util_enca: util_enca,
                status: status,
                id_prod: dataOriginal.id_prod,
                nom_prod: dataOriginal.nom_prod,
                costo1: dataOriginal.costo1,
                ref_prod: dataOriginal.ref_prod,
                util_cur: dataOriginal.recar_prod,
                prec_cur: dataOriginal.ventas_prod,
                util_new: parseFloat(formatoMoneda(util_new)) || 0,
                prec_new: parseFloat(formatoMoneda(prec_new)) || 0,
            });
        });
        formData = new FormData();
        formData.append('fecha', $("#fecha").val());
        formData.append('id_prod', $("#id_prod").val());
        formData.append('id_fab', $("#id_fab").val());
        formData.append('registros', JSON.stringify(registros));
        formData.append('id', id);
        const url = `${base_url}/ChangeUtility/store_utilidad`;

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
            error: function (jqXHR, textStatus, errorThrown) {
                $(".loader").hide();
                boton.prop('disabled', false);
                console.error('Status:', jqXHR.status);
                console.error('Error Text:', jqXHR.responseText);
                console.error('Status Text:', textStatus);
            },
            success: function (data) {
                Swal.fire({
                    title: data.title,
                    text: data.msg,
                    icon: data.icon,
                }).then((result) => {
                    if (data.icon != 'error') {
                        window.location.href = `${base_url}/ChangeUtility`;
                    }
                });
            }
        });
    } else {
        boton.prop('disabled', false);
        return false
    }
});