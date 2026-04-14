//Variables
var id_emp;
var item;
var tfec_ctb;
var tfec_ini_fis;
var tdias = 1;
//Validar Campos
$(function(){
    $("form[name='my_form']").validate({
        rules: {
            id_emp: "required",
            fec_ini: {
                required: true, 
                date: true,
            },
            fec_fin: {
                required: true, 
                date: true,
            },
        },
        messages: {
            id_emp: 'Debe especificar una empresa',
            fec_ini: {
                required : "Se requiere una fecha de inicio",
                date: "La fecha de inicio, debe ser una fecha válida",
            },
            fec_fin: {
                required: "Se requiere una fecha de fin",
                date: "La fecha fin, Debe ser una fecha válida",
            },
            groups: {
                dates: "ffec_ini, ffec_fin"
            },
            errorPlacement: function(error, element){
                if(element.attr("ffec_fin") == "fec_fin" ||  element.attr("ffec_ini") == "fen_ini"){
                    error.insertAfter("#fec_fin");
                }else{
                    error.insertAfter(element);
                }
            }
        }
    })
})
$().ready(function(){
    listar_empresas();
    listar_nivel_detalle();
})
$(document).on('change', '#id_emp', async function(){
    id_emp = $(this).val();
    //Buscar fechas para validar
    tfechas_emp = await tfechas_emp(id_emp);
    if(tfechas_emp){
        tfec_ctb = await tfechas_emp['fec_ctb'] 
        var tfec_ctb_for = new Date(tfec_ctb);
        tfec_ctb_for.setDate(tfec_ctb_for.getDate() + (tdias + 1) );

        var xano = tfec_ctb_for.getFullYear();
        var xmes =  (tfec_ctb_for.getMonth() + 1) < 10 ? '0' + (tfec_ctb_for.getMonth() + 1) : (tfec_ctb_for.getMonth() + 1);
        var xdia = tfec_ctb_for.getDate() < 10 ? '0' + tfec_ctb_for.getDate() : tfec_ctb_for.getDate();
        tfec_ctb =  xano + '-' + xmes + '-' + xdia ;
        //Fecha de Inicio
        $("#fec_ini").val(tfec_ctb);
        //Fecha Final
        var last_day = getLastDayOfMonth(xano, parseInt(xmes - 1));
        last_day = last_day < 10 ? '0' + last_day :last_day;
        tfec_ctb =  xano + '-' + xmes + '-' + last_day ;
        $("#fec_fin").val(tfec_ctb);
        tfec_ini_fis = tfechas_emp['fec_ini_fis'];
    }
})
$(document).on('change', '#fec_ini', function(){
    var tfec_ini = $(this).val()
    var tfec_ctb_for = new Date(tfec_ini);
    var xano = tfec_ctb_for.getFullYear();
    var xmes = tfec_ctb_for.getMonth()+1;
    var last_day = getLastDayOfMonth(xano, parseInt(xmes));
    var xmes = xmes < 10 ? '0' + (xmes + 1) : (xmes + 1);
    
    //Fecha Final
    last_day = last_day < 10 ? '0' + last_day :last_day;
    tfec_ctb =  xano + '-' + xmes + '-' + last_day ;
    $("#fec_fin").val(tfec_ctb);
})
$(document).on('change', '#fec_fin', function(){
    var mensaje = "";
    var title = "";
    var icon = "";

    fec_ini = $("#fec_ini").val();
    fec_fin = $(this).val();

    if(fec_fin < fec_ini){
        title = "Oppps se ha encontrado un error";
        mensaje = "La fecha final no debe ser menor a la incial";
        icon = "error";
    }
    if(mensaje != "" && title != "" && icon != ""){
        Swal.fire({
            title: title,
            text: mensaje,
            icon: icon
        });

    }
})
$(document).on('click', '#btn_con', function(e){
    createTable();
})
function createTable(){
    //Crear tabla con el resultado de la consulta de los movimientos contables
    $('#Con_Mov_Ctb').html('');
    var htmlTable = $('#Con_Mov_Ctb');
    //Caption
    //htmlTable.append('<caption>Resultados Consulta de Movimientos Contables</caption>')
    //Encabezado
    htmlTable.append('<thead>').children('thead')
    .append('<tr />').children('tr')
    .append('<th>Fecha</th>')
    .append('<th class="text-right">Número</th>')
    .append('<th>Auxiliar</th>')
    .append('<th>Descripción</th>')
    .append('<th class="text-right">Debe</th>')
    .append('<th class="text-right">Haber</th>')
    .append('<th class="text-right">Saldo</th>')
    .append('<th>Origen</th>');
    //Buscar registros a agregar
    const url = `${base_url}/CTBConsult/saldosCuentas_mov`;
    id_emp = $("#id_emp").val();
    fec_ini = $("#fec_ini").val();
    fec_fin = $("#fec_fin").val();
    cod_cta = $("#nom_ctb").val();
    cod_cta_sep = cod_cta.split(' - ');
    cod_aux = $("#nom_aux").val();
    cod_aux_sep = cod_aux.split(' - ');

    $.ajax({
        url: url,
        type: 'POST',
        data: {id_emp: id_emp, fec_ini: fec_ini, fec_fin: fec_fin, cod_cta: cod_cta_sep[0], cod_aux: cod_aux_sep[0]},
        processData: true,
    }).done(function(response){
        mov_rows = JSON.parse(response);
        //Cuerpo
        var tbody = htmlTable.append('<tbody />').children('tbody');
        var cod_cta_previa = '';
        var tsaldo;
        var acum_debe = 0;
        var acum_habe = 0;
        var acum_saldo = 0;
        var acum_cta_debe = 0;
        var acum_cta_habe = 0;
        var print_total_cta = false
        var id_cta_current;
        $.each(mov_rows, function(i, item){
            print_total_cta = false
            if(cod_cta_previa != item.cod_cta){
                id_cta_current = item.cod_cta
                //Cuenta y saldo anterios
                tsaldo = 0;
                var tcuenta = "CUENTA: " + item.cod_cta + " " + item.nombre_cta;
                var tsaldo_ant = item.sald_ant;
                tsaldo += tsaldo_ant;
                acum_saldo += tsaldo;
                tbody.append('<tr />').children('tr:last')
                .append('<td colspan="6"><b>'+tcuenta+'</b></td>')
                .append('<td class="text-right"><b>'+format_number_with_dec(tsaldo,2)+'</b></td>')
                .append(`<td></td>`);
                print_total_cta = true
            }
            //Acumular saldo
            var debe = item.debe;
            var haber = item.haber;
            if((debe) != 0){
                tsaldo += debe;
                acum_debe += debe;
                acum_saldo += debe;
                acum_cta_debe += debe;
                haber = '';
                debe = format_number_with_dec(debe);
            }else if((haber) != 0){
                tsaldo -= haber;
                acum_habe += haber;
                acum_saldo -= haber;
                debe = '';
                haber = format_number_with_dec(haber);
                acum_cta_habe += haber;
            }
            tbody.append('<tr />').children('tr:last')
            .append('<td">'+GetTodayDate(0,item.fecha_comp, '1')+'</td>')
            .append('<td class="text-right">'+item.num_comp+'</td>')
            .append('<td>'+item.cod_aux+'</td>')
            .append('<td>'+item.desc_comp+'</td>')
            .append('<td class="text-right">'+debe +'</td>')
            .append('<td class="text-right">'+haber +'</td>')
            .append('<td class="text-right">'+format_number_with_dec(tsaldo,2)+'</td>')
            .append(`<td>${item.ori_comp}</td>`);
            //Total cuenta
            console.log(id_cta_current);
            i++;
            console.log(item.cod_cta);
            if(id_cta_current != item.cod_cta){
                var tcuenta = "TOTAL CUENTA: " + item.cod_cta + " " + item.nombre_cta;
                tbody.append('<tr />').children('tr:last')
                .append('<td colspan="4"><b>'+tcuenta+'</b></td>')
                .append('<td class="text-right"><b>'+format_number_with_dec(acum_cta_debe,2)+'</b></td>')
                .append('<td class="text-right"><b>'+format_number_with_dec(acum_cta_habe,2)+'</b></td>')
                .append('<td class="text-right"><b>'+format_number_with_dec(acum_saldo,2)+'</b></td>')
                .append(`<td></td>`);
                i--;
            }
            cod_cta_previa = item.cod_cta;
        });
        //Añaadir Total
        tbody.append('<tr />').children('tr:last')
        .append('<td><b>TOTAL GENERAL<b/></td>')
        .append('<td></td>')
        .append('<td></td>')
        .append('<td></td>')
        .append('<td class="text-right"><b>'+format_number_with_dec(acum_debe,2) +'</b></td>')
        .append('<td class="text-right"><b>'+format_number_with_dec(acum_habe,2) +'</b></td>')
        .append('<td class="text-right"><b>'+format_number_with_dec(Math.abs(acum_saldo),2)+'</b></td>')
        .append('<td></td>');
        //Añadir al dom
        htmlTable.appendTo('#Con_Mov_Ctb')
        $('#dynamicTable table').addClass("display responsive nowrap table table-hover");
    })
}