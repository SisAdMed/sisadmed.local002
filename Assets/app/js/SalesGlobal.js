//Variables
table = "#tblSalesGlobal";
//Al cargar la aplicación
$().ready(function(){
    div_loading();
    $(".loader").show();
    refrescar();
    $(".loader").hide();
});
//Limpiar con valores por Defecto
function refrescar(){
    listar_empresas(0, false,  'Todas');
    $("#fec_ini").val(getFirstDateofMonth());
	$("#fec_fin").val(getLastDateofMonth());
}
//Limpiar campos
$("#btnClear").on('click', function(){
    refrescar();
});
//Buscar registros
$('#btnSearch').on('click', function(){
    div_loading();
    const url = `${base_url}/Dashboard/sales_global_data`;
    id_emp = $('#id_emp').val();
    fec_ini = $("#fec_ini").val();
    fec_fin = $("#fec_fin").val();
    $.ajax({
        url: url,
        method: 'POST',
        data: {'id_emp': id_emp, 'fec_ini': fec_ini, 'fec_fin': fec_fin},
        dataType: 'json',
        dataSrc: '',
        beforSend: function(){
            $('.loader').show();
        },
        success: function(data){
            console.log(data);
            if(data){
				var htmlTags = "";
				//Encabezado de Tabla, nombre del reporte
				//Tabla
				const num_files = data[0]['num_mes']; //Número de columnas
                console.log(num_files);

                //Agrupar JSON por cliente
                var result = data.reduce(function (r, a) {
                    r[a.nombre_emp] = r[a.nombre_emp] || [];
                    r[a.nom_ent] = r[a.nom_ent] || [];
                    r[a.description] = r[a.description] || [];
                });
                console.log('result');
                console.log(result);
				let table = $(`<table></table>`);
				table.attr("id", "tblSalesGlobal");
				table.addClass("display responsive nowrap table table-hover");
				table.css("width", "100%");
				//Encabezado de la tabla
				let thead = $("<thead></thead>");
				// Crear fila de encabezado
				let tr = $("<tr></tr>");
				// Crear columna de encabezado
				var nom_emp = $("#id_emp option:selected").text();
				if (nom_emp == "Todas") {
					nom_emp = "Todas las Empresas";
				}
				let th1 =
					$(`<th class="text-center" colspan="${num_files}" >VENTAS GLOBALES - ${nom_emp} <br>
                    Desde: ${fec_ini} Hasta: ${fec_fin}</th>`);
				th1.css("font-size", "20px");
				tr.append(th1);
				thead.append(tr);
				table.append(thead);
				//Crear titulos de columnas
				//Recorrer los datos y crear las filas
				let tbody = $("<tbody></tbody>");
				tr = $("<tr></tr>");
                var key = [];
                var values = []
				let tit_col_1 = $(`<th>CLIENTE</th>`);
				let tit_col_2 = $(`<th>TIPO <br> CLIENTE</th>`);
				tr.append(tit_col_1);
				tr.append(tit_col_2);
                tbody.append(tr);
				$.each(data, function (index, item) {
					let tr = $("<tr></tr>");
					let td1 = $(`<td>${item.nom_ent}</td>`);
					let td2 = $(`<td>${item.description}</td>`);
					tr.append(td1);
					tr.append(td2);
					tbody.append(tr);
				});
				table.append(tbody);

				$("#tble_Data").empty();
				$("#tble_Data").append(table);
			}

        },
        complete: function(){
            $('.loader').hide();
        },
        error: function(xhr, status, error){
            $('.loader').hide();
        }
    })
});