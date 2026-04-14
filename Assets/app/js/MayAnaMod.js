/*
 * Funciones MayAnaMod
 * Copyright 2025-2025
 * 22-12-2025 Creación de Archivo José Vargas 10:57:00
 */
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
$.validator.addMethod( "validarEntreFechas", function (value, element, param) {
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
	$("form#my_form").trigger("reset"); 
	$("#tbl_analitico").html(""); 
});
//AL pulsar el boton Consultar
$("#my_form").on("submit", function (e) {
    e.preventDefault();
    if($(this).valid()){
          var formData = $(this).serialize();
        const url = `${base_url}/MayAnaMod/AnaliticoBan`;
        //Ajax para 
        $.ajax({
            url: url,
            method: 'POST',
            dataSrc: '',
            data: formData,
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
                var tabla = $("#tbl_analitico");
				if (data) {
					$("#tbl_analitico tfoot tr").remove();
                    tabla.DataTable({
                        destroy: true,
                        data: data,
                        responsive: true,
					    processing: true,
						paging: false,
						clear: true,
						autowidth: false,
                        columns: [
                            { data: "fecha_comp", title: "Fecha", render: $.fn.dataTable.render.moment( FROM_PATTERN, TO_PATTERN)},
                            { data: "cod_bantmo", title: "Tipo" },
                            { data: "num_banmov", title: "Número", className: "text-right" },
                            { data: "cuenta_bancue", title: "Cuenta"},
                            { data: "des_banmov", title: "Descripción" },
                            { data: "mon_debe", title: "Debe", className: "text-right", render: $.fn.dataTable.render.number( '.', ',', 2, '' ) },
                            { data: "mon_habe", title: "Haber", className: "text-right", render: $.fn.dataTable.render.number( '.', ',', 2, '' ) },
						],
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
                            if($footer.length === 0) {
                              $("#tbl_analitico tfoot").append("<tr></tr>");                              
                              $footer = $("#tbl_analitico tfoot tr");
                                //Actualizar el pie de tabla
                                $footer.append("<th colspan='5' class='text-right'>TOTALES</th>");                                
                                $footer.append(`<th class='text-right'>${format_number_with_dec_new(tot_debe, 2)}</th>`);
								$footer.append(`<th class='text-right'>${format_number_with_dec_new(tot_haber, 2)}</th>`);                               
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
                            }
                        }
                    })
                }   
            },
        });
    }
})