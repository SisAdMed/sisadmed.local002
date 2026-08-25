/*
 * Funciones MayAnaMod
 * Copyright 2025-2025
 * 22-12-2025 Creación de Archivo José Vargas 10:57:00
 */
tabla = null;
tipo = GetURLParameter("tipo");
let tituloColumna = '';
let report = 'Mayor Análitico Detallado del Módulo de ';

// AL Iniciar la aplicación
$().ready(function () {
    //Validaciones  
    $("form#my_form").validate({
        ignore: null,
        rules: {
            id_emp: "required",
            fec_ini: {
                required: true,
            },
            //nom_ctb: "required",
            fec_fin: {
                required: true,
                validarEntreFechas: "fec_ini",
            },
        },
        messages: {
            id_emp: "Debe especificar una empresa",
            fec_ini: {
                required: "Debe especificar una fecha desde",
                date: "Debe especificar una fecha válida",
                validarEntreFechas:
                    "La fecha inicial debe ser anterior o igual a la fecha final.",
            },
            //nom_ctb: "Debe especificar una Cuenta Contable",
        },
    });
    listar_empresas();
});
$.validator.addMethod("validarEntreFechas", function (value, element, param) {
    // 'value' es el valor del campo actual (fecha_final)
    // 'param' es el ID del campo de fecha inicial (fecha_inicial)
    const fechaInicial = new Date($("#" + param).val());
    const fechaFinal = new Date(value);

    // Compara las fechas: si la final es menor que la inicial, es inválida.
    // Los objetos Date se comparan directamente o puedes usar milisegundos
    return fechaFinal >= fechaInicial;
},
    "La fecha final debe ser posterior o igual a la fecha inicial."
);
$("#btnclear").on("click", function () {
    destruirTabla();

});
//AL pulsar el boton Consultar
$("#my_form").on("submit", function (e) {
    e.preventDefault();
    destruirTabla();
    if ($(this).valid()) {

        var formData = $(this).serialize();
        const url = `${base_url}/MayAnaMod/AnaliticoBan`;
        //Ajax para 
        $.ajax({
            url: url,
            method: 'POST',
            dataSrc: '',
            data: formData,
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
                    report = 'Mayor Análitico Detallado del Módulo de ';
                    if (tipo == 'B') {
                        tituloColumna = "Cuenta";
                        report += 'Bancos';
                    } else if (tipo == 'P') {
                        tituloColumna = "Proveedor";
                        report += 'Cuentas por Pagar';
                    } else if (tipo == 'C') {
                        tituloColumna = "Cliente";
                        report += 'Cuentas por Cobrar';
                    }
                    let nom_empresa = $("#id_emp option:selected").text();
                    let fec_ini = $("#fec_ini").val();
                    let fec_fin = $("#fec_fin").val();
                    let f_fec_ini = new Date(fec_ini.replace(/-/g, '\/'));
                    let f_fec_fin = new Date(fec_fin.replace(/-/g, '\/'));
                    let opciones = { year: 'numeric', month: '2-digit', day: '2-digit' };
                    let fec_ini_f = f_fec_ini.toLocaleDateString('es-ES', opciones);
                    let fec_fin_f = f_fec_fin.toLocaleDateString('es-ES', opciones);
                    let periodo = `Período: del ${fec_ini_f} al ${fec_fin_f}`;
                    let nom_cue = $("#nom_ctb").val() ? $("#nom_ctb").val() : '';
                    if ((nom_cue)) {
                        nom_cue = '<br>Cuenta Contable: ' + nom_cue;;
                    }
                    let nom_aux = $("#nom_aux").val() ? $("#nom_aux").val() : '';
                    if ((nom_aux)) {
                        nom_aux = '<br>Auxiliar Contable: ' + nom_aux;;
                    }
                    $('#contenedorTabla').html(`
                        <table id="tbl_analitico" class="display table table-hover text-xs w-100" style="width: 100%;">
                            <caption style="caption-side: top; font-weight: bold; font-size: 1.1rem;">
                                ${nom_empresa}<br>
                                ${report}<br>
                                ${periodo}
                                ${nom_cue}
                                ${nom_aux}
                            </caption>
                            <thead></thead>
                            <tfoot></tfoot>
                        </table>
                    `);
                    $("#tbl_analitico tfoot tr").remove();
                    tabla = $("#tbl_analitico").DataTable({
                        destroy: true,
                        clear: true,
                        data: data,
                        colResize: false,
                        responsive: true,
                        dataSrc: '',
                        columns: [
                            { data: "fecha_comp", title: "Fecha", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN) },
                            { data: "cod_bantmo", title: "Tipo" },
                            { data: "num_banmov", title: "Número", className: "text-right" },
                            { data: "cuenta_bancue", title: tituloColumna },
                            { data: "des_banmov", title: "Descripción" },
                            { data: "mon_debe", title: "Debe", className: "text-right", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                            { data: "mon_habe", title: "Haber", className: "text-right", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                            { data: 'ori', title: "Ori", className: 'text-center' },
                            { data: 'concepto', title: "Concepto" },
                        ],
                        autoWidth: false,
                        columnDefs: [
                            { width: '8%', targets: 0 }, // Primera columna (Fecha)
                            { width: '15%', targets: 3 }, // Primera columna (Fecha)                            
                        ],
                        lengthMenu: [
                            [5, 10, 25, 50, -1],
                            [5, 10, 25, 50, "Todos"],
                        ],
                        iDisplayLength: -1,
                        language: {
                            url: `${base_url}/Assets/json/es-ES.json`,
                        },
                        footerCallback: function () {
                            var api = this.api();
                            var tot_debe = api.column(5).data().sum();
                            var tot_haber = api.column(6).data().sum();
                            var diferencia = tot_debe - tot_haber;
                            //Accedeer al footer existente
                            var $footer = $("#tbl_analitico tfoot tr");
                            if ($footer.length === 0) {
                                $("#tbl_analitico tfoot").append("<tr></tr>");
                                $footer = $("#tbl_analitico tfoot tr");
                                //Actualizar el pie de tabla
                                $footer.append("<th colspan='5' class='text-right'>TOTALES</th>");
                                $footer.append(`<th class='text-right'>${format_number_with_dec_new(tot_debe, 2)}</th>`);
                                $footer.append(`<th class='text-right'>${format_number_with_dec_new(tot_haber, 2)}</th>`);
                                //Agregando las columans adicionales de Origen y Concepto
                                $footer.append(`<th class='text-right'></th>`);
                                $footer.append(`<th class='text-right'></th>`);
                                $("#tbl_analitico tfoot").append("<tr></tr>");
                                $footer = $("#tbl_analitico tfoot tr").last();
                                $footer.append("<th colspan='5' class='text-right'>SALDO</th>");
                                if (diferencia > 0) {
                                    $footer.append(`<th class='text-right'>${format_number_with_dec_new(diferencia, 2)}</th>`);
                                    $footer.append(`<th class='text-right'></th>`);
                                } else {
                                    $footer.append(`<th class='text-right'></th>`);
                                    $footer.append(`<th class='text-right'>${format_number_with_dec_new(Math.abs(diferencia), 2)}</th>`);

                                }
                                //Agregando las columans adicionales de Origen y Concepto
                                $footer.append(`<th class='text-right'></th>`);
                                $footer.append(`<th class='text-right'></th>`);
                            }
                        },
                        // mostrar botones de exportacion		
                        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                        buttons: [
                            {
                                extend: "copyHtml5",
                                text: "<i class='fa fa-copy'></i>",
                                titleAttr: "Copiar",
                                className: "btn btn-secondary",
                                title: report + new Date().toISOString().replace(/[\-\:\.]/g, ""),
                                exportOptions: { footer: true }
                            },
                            {
                                extend: "excelHtml5",
                                text: "<i class='fa fa-file-excel'></i>",
                                titleAttr: "Exportar a Excel",
                                className: "btn btn-warning",
                                title: report + new Date().toISOString().replace(/[\-\:\.]/g, ""),
                                exportOptions: {
                                    // 'orthogonal: export' le dice a DataTables: 
                                    // "Ignora el render.number de la pantalla y búscame el número puro original de la BD"
                                    orthogonal: 'export',
                                    footer: true
                                }
                            },
                            {
                                extend: "pdfHtml5",
                                text: "<i class='fa fa-file-pdf'></i>",
                                titleAttr: "Exportar a PDF",
                                className: "btn btn-danger",
                                title: report + new Date().toISOString().replace(/[\-\:\.]/g, ""),
                                exportOptions: { footer: true }
                            },
                            {
                                extend: "csvHtml5",
                                text: "<i class='fa fa-file-text'></i>",
                                titleAttr: "Exportar a CSV",
                                className: "btn btn-primary",
                                title: report + new Date().toISOString().replace(/[\-\:\.]/g, ""),
                                exportOptions: { footer: true }
                            },
                            {
                                extend: 'print',
                                text: '<i class="fas fa-print"></i>', // Texto e icono del botón
                                className: 'btn btn-success',                // Estilo CSS (ej. Bootstrap / AdminLTE)
                                title: report + new Date().toLocaleDateString(),          // Título que aparecerá en la hoja impresa
                                titleAttr: "Imprimir",
                                messageTop: 'Documento generado automáticamente por el sistema.', // Subtítulo o nota superior
                                messageBottom: 'Fin del reporte.',           // Nota al pie de página
                                exportOptions: {
                                    columns: ':visible',                    // Imprime solo las columnas visibles
                                    footer: true                            // Incluye el tfoot si lo usas
                                },
                                autoPrint: true,                            // Abre el cuadro de diálogo de impresión automáticamente
                                customize: function (win) {
                                    // Modifica el cuerpo de la vista de impresión
                                    $(win.document.body).css('font-size', '10pt');
                                    // Modifica la apariencia de la tabla impresa
                                    $(win.document.body).find('table')
                                        .addClass('compact')
                                        .css('font-size', 'inherit');
                                    // Forzar a que el caption sea visible y centrado en la vista de impresión
                                    $(win.document.body).find('caption').css({
                                        'caption-side': 'top',
                                        'font-size': '14pt',
                                        'font-weight': 'bold',
                                        'text-align': 'center',
                                        'margin-bottom': '12px',
                                        'color': '#000'
                                    });
                                }
                            }
                        ],

                    })
                }
            },
        });
    }
})
function destruirTabla() {
    if ($.fn.DataTable.isDataTable('#tbl_analitico')) {
        tabla.destroy(true);
        tabla = null;
         $('#my_form')[0].reset();
    }
    $('#contenedorTabla').empty();
};