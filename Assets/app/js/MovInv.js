//Al iniciar la aplicación
$(document).ready(function () {
    //Validar datos del formulairo
    $('#my_form').validate({
        ifnore: [],
        rules: {
            id_emp: "required",
            id_tmovinv: "required",
            num_movinv: {
                required: {
                    depends: function (element) {
                        return !$(element).prop('readOnly');
                    }
                }
            },
            fecha_comp: "required",
            id_alm: "required",
            descrip_movinv: {
                required: true,
                minlength: 10,
            },
            status: "required",
            item: "required",
        },
        messages: {
            id_emp: "Debe especificar una Empresa",
            id_tmovinv: "Debe especificar un Tipo de Movimiento",
            num_movinv: "Debe especificar un Núumero de Movimiento",
            fecha_comp: "Debe especificar una Fecha del Movimiento",
            id_alm: "Debe especificar un Almacén",
            descrip_movinv: {
                required: "Debes especificar una descripción para el Movimiento",
                minlength: "Debe contenedor al menos {0} carácteres",
            },
            status: "Debe especificar un Status",
            item: "Debe contener al menos un detalle"
        },

    })
    //Cargar el index
    form = $("form").attr("id");
    if (form === undefined) {
        initMovInvTable();
    } else {
        id = $("#id").val();
        if (id) {
            dat_form(id);
        } else {
            dat_form_new();
        }
    }
    //Validar empresa seleccionada
    $("#id_emp").on('change', async function (e) {
        e.preventDefault();
        id_emp = $(this).val();
        //Limpiar campos
        $("#id_tmovinv").empty();
        $("#id_alm").empty();
        //Llenar campos
        if (id_emp) {
            listar_InvTipoMov(id_emp, '0', 'id_tmovinv');
            listar_almacenes(id_emp);
            id_tdo_cfg = await tip_doc_fac(id_emp);
            id_alm_cfg = id_tdo_cfg['id_alm'];
            id_ubi_cfg = id_tdo_cfg['id_ubi'];
            nom_ubi_cfg = id_tdo_cfg['nom_ubi'];
            $("#id_alm").val(id_alm_cfg);
            stock = id_tdo_cfg['fac_stock'];
            $("#id_moneda").trigger('change');
        }

    });
    //Validar si el tipo de movimiento utiliza consecutivo o no
    $("#id_tmovinv").on('change', function (e) {
        e.preventDefault();
        id_tmovinv = $(this).val();
        const url = `${base_url}/InvTipoMov/val_InvTipoMov`;
        $.ajax({
            url: url,
            method: 'POST',
            data: { id: id_tmovinv },
            dataSrc: '',
            dataType: 'json',
            success: function (data) {
                if (data) {
                    $("#num_movinv").prop('readonly', false);
                    if (data.consecutiv__tmoinv == "1") {
                        $("#num_movinv").prop('readonly', true);
                    }
                }
            }
        });

    });
    //Actualizar Tasa de cambio al cambiar la fecha
    $("#fecha_comp").on('change', function (e) {
        $("#id_moneda").trigger('change');
        id_emp = $("#id_emp").val();
        //Validar Fechas de Contabilidad
        id_emp_cfg = get_empresa_config(id_emp);
        fec_ctb = id_emp_cfg["fec_cxc"];
        fecha_comp = $(this).val();
        if (fecha_comp <= fec_ctb) {
            $("#fecha_comp").addClass("is-invalid");
            $("#fecha_comp").attr("title", "La fecha del documento no puede ser menor a la fecha de contabilidad de la empresa");
            $("#btnok").prop("disabled", true);
        } else {
            $("#fecha_comp").removeClass("is-invalid");
            $("#btnok").prop("disabled", false);
        }
    });
    //Guardar y/o Actualizar registro
    $("#my_form").on("submit", function (e) {
        e.preventDefault();
        // 1. Validar el formulario principal con jQuery Validate
        if (!$(this).valid()) {
            return false
        }
        // 2. Extraer los datos del detalle (recorriendo cada fila)
        var detalle = [];
        var table = $('.tblEncaMov').DataTable();
        // Usamos table.$('tr') para incluir todas las filas (incluso con paginación)
        table.$('tbody tr').each(function () {
            var $row = $(this);

            var id_prod = $row.find("input[id^='id_prod']").val();
            var id_ubi = $row.find("input[id^='id_ubi']").val();
            var lote = $row.find("input[id^='lote']").val() || '';
            var fec_venc = $row.find("input[id^='fec_venc']").val() || null;
            var cantidad = parseFloat($row.find("input[id^='cantidad']").val()) || 0;

            // Solo incluir si tiene producto seleccionado y cantidad mayor a 0
            if (id_prod && cantidad > 0) {
                detalle.push({
                    id_prod: id_prod,
                    id_ubi: id_ubi,
                    lote: lote,
                    fec_venc: fec_venc,
                    cantidad: cantidad
                });
            }
        });
        // Validar que exista al menos un artículo en el detalle
        if (detalle.length === 0) {
            Swal.fire('Error', 'Debe agregar al menos un producto válido al detalle.', 'warning');
            return false;
        }
        // 3. Empaquetar cabecera y detalle
        var formData = new FormData(this);
        formData.append('detalle', JSON.stringify(detalle));
        const url = `${base_url}/MovInv/store`;
        //Ajax para Guardar y/o Actualizar
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,       // Prevent jQuery from trying to convert the FormData to a string
            contentType: false,       // Prevent jQuery from setting a default Content-Type header
            dataType: 'json',
            beforeSend: function () {
                $("#btnGuardar").prop("disabled", true);
                // Muestra una ventana de SweetAlert2 con spinner activo
                Swal.fire({
                    title: 'Guardando...',
                    text: `Guardando el Movimiento de Inventario, por favor espere.`,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading(); // Inicia la animación de carga
                    }
                });
            },
            complete: function () {
                $("#btnGuardar").prop("disabled", false);
            },
            error: function (PDOException) {
                $("#btnGuardar").prop("disabled", false);
                Swal.close();
                console.log('Ha ocurrido el siguiente error:', PDOException.statusText)
            },
            success: function (data) {
                $("#btnGuardar").prop("disabled", false);
                Swal.fire({
                    title: data.title,
                    text: data.msg,
                    icon: data.icon,
                }).then((result) => {
                    if (data.icon != "error") {
                        window.location.href = `${base_url}/MovInv`;
                    }
                })
            },
        });
    });
    // Eliminar un registro
    $("#tblIndexMain").on("click", ".btn-delete", function () {
        var recordId = $(this).data("id");
        var recordCode = $(this).data("code");
        var recordName = $(this).data("name");
        var recordNumbre = $(this).data("number");
        var descrip = `¿Está seguro de eliminar el Movimiento de Inventario ${recordCode} - ${recordName} - número ${recordNumbre}?`;

        Swal.fire({
            title: descrip,
            text: "¡No podrá revertir esta eliminación!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, borrar este registro!",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                const url = `${base_url}/MovInv/destroy`;
                $.ajax({
                    url: url,
                    method: "POST",
                    data: { id: recordId, recordCode: recordCode, recordName: recordName, recordNumbre: recordNumbre },
                    dataType: "json",
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Eliminando...',
                            text: `Eliminando el Movimiento de Inventario ${recordCode} - ${recordName} - número ${recordNumbre}...`,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    complete: function () {
                        Swal.close();
                    },
                    success: function (resulta) {
                        Swal.fire({
                            icon: `${resulta.icon}`,
                            title: `${resulta.title}`,
                            text: `${resulta.msg}`,
                        }).then((res) => {
                            if (res.isConfirmed || res.isDismissed) {
                                // 1. Recargar DataTable con callback para asegurar que oculte el loader al terminar
                                $("#tblIndexMain").DataTable().ajax.reload(function () {
                                    if (typeof loader !== 'undefined') {
                                        Swal.close();
                                    }
                                }, false);
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        if (typeof loader !== 'undefined') {
                            loader.hide();
                        }
                        console.error(xhr.responseText);
                    },
                });
            }
        });
    });
})
/**Nuevo Registro */
function dat_form_new() {
    listar_empresas();
    $("#fecha_comp").val(GetTodayDate(0));
    listar_monedas(2);
    listar_status(1);
}

/**
 * Description Consultar registro
 *
 * @param {*} id 
 */
function dat_form(id) {
    const url = `${base_url}/MovInv/show_row`;
    $.ajax({
        url: url,
        method: 'POST',
        data: { id: id },
        dataType: 'json',
        beforeSend: function () {
            // Muestra una ventana de SweetAlert2 con spinner activo
            Swal.fire({
                title: 'Consultando...',
                text: `Consultando el Movimiento de Inventario, por favor espere.`,
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
            Swal.close();
            console.log('Ha ocurrido el siguiente error:', PDOException.statusText)
        },
        success: async function (data) {
            row = data[0];
            id_emp = row['id_emp'];
            id_tmovinv = row['id_tmoinv'];
            listar_empresas(id_emp, true);
            if (id_emp) {
                listar_InvTipoMov(id_emp, id_tmovinv, 'id_tmovinv');
                $("#id_tmovinv").css("pointer-events", "none");
                id_alm = row['id_alm'];
                listar_almacenes(id_emp, id_alm);
                id_tdo_cfg = await tip_doc_fac(id_emp);
                id_ubi_cfg = id_tdo_cfg['id_ubi'];
                nom_ubi_cfg = id_tdo_cfg['nom_ubi'];
            }

            $("#num_movinv").val(row['num_movinv']);
            $("#num_movinv").css("pointer-events", "none");
            $("#fecha_comp").val(row['fecha_comp']);
            listar_monedas(row['id_moneda']);
            $("#tasa_cambio").val(row['tasa_cambio']);
            $("#descrip_movinv").val(row['descrip_movinv']);
            listar_status(row['status']);
            //Cargar detalle           
            var detalleLimpio = data.map(function (item) {
                var fec = item.fec_venc;
                // Si viene "0000-00-00", convertir a cadena vacía
                if (!fec || fec === '0000-00-00') {
                    fec = '';
                }
                return {
                    id_prod: item.id_prod,
                    nom_prod: item.nom_prod,
                    id_ubi: item.id_ubi,
                    nom_ubi: item.nom_ubi,
                    lote: item.lote,
                    fec_venc: fec,
                    cantidad: item.cantidad,
                    lote_prod: item.lote_prod || (item.lote ? 1 : 0)
                }
            });
            table.clear();
            table.rows.add(detalleLimpio);
            table.draw();

            // Forzar bloqueo inmediato en el DOM para cualquier campo vacío
            $('.tblEncaMov tbody tr').each(function () {
                var $row = $(this);
                var $lote = $row.find("input[id^='lote']");
                var $fec = $row.find("input[id^='fec_venc']");

                if ($.trim($lote.val()) === "") {
                    $lote.prop('readonly', true).removeAttr('required');
                    $fec.prop('readonly', true).removeAttr('required');
                }
            });
        }
    })
}
function printer_movement(id) {    
	id_mov = id.dataset.id;    
    name_mov = id.dataset.name;
    Swal.fire({
		icon: "question",
		title: `¿Está seguro que desea imprimir el Movimiento de Inventario ${name_mov}?.`,
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",
   }).then((result) => {
		if (result.isConfirmed) {
			window.open(`${base_url}/MovInv/printer_movinv/` + id_mov, "_blank");
		}
   });
}