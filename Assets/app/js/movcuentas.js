//Variables
let item;
let nom_tabla = 'tblBanmov_cuenta';
$().ready(function(){
    //Cargar empresas
   init();
})
//Al seleccionar empresa
$(document).on('change', '#id_emp', async function(e){
    id_emp = $(this).val();
    listar_cuentas_ban(id_emp);
})

$(document).on('change', '#id_bancon', async function(e){
    id_bancon = $(this).val();
    nombre_con = await nom_con_ban(id_bancon);
    $('#nom_con').val(nombre_con['nom_bancon']);

})
$(document).on('click', '#btn-clear', function(e){
    init()

    $("#fec_ini").val(getFirstDateofMonth());
    $('#fec_fin').val(getLastDateofMonth());
})
$(document).on('click', '#btn-excel', function(e){
    var table = $('#' + nom_tabla);
    if(table && table.length){
        var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
        $(table).table2excel({
            exclude: ".noExl",
            name: "Excel Document Name",
            filename: "Bancos_Mov" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true,
            preserveColors: preserveColors
        });
    }
})
$(document).on('click', '#btn-search', function(e){
    e.preventDefault();
    const url = `${base_url}/BanMovim/banmov_cuentas`;
    id_emp = $('#id_emp').val();
    fec_ini = $('#fec_ini').val();
    fec_fin = $('#fec_fin').val();
    id_bancue = $('#id_bancue').val();
    id_bancon = $('#id_bancon').val();

    $('#' + nom_tabla).empty();

     //buscar los registros solicitados
     $.ajax({
          url: url,
          method: 'POST',
          data: {id_emp:id_emp, fec_ini:fec_ini, fec_fin:fec_fin, id_bancue:id_bancue, id_bancon:id_bancon},
          dataSrc: '',
          beforeSend: function(){
               $('.loader').show();
          },
          success: function(data){
               response = JSON.parse(data);
               if(response){
                    //Selleccionar tabla
                    var tabla = $('#' + nom_tabla);
                    //Crear la etiqueta <thead>
                    var thead = $('<thead></thead>');
                    //Creamos Fila de Encabezado de Empresa
                    var tr1 = $('<tr></tr>');
                    tr1.append('<th colspan="8" class="text-center">'+response[0]['nombre_emp']+'</th>'); 
                    //Agregar la fila al thead
                    thead.append(tr1);
                    //crear la fila de Titulos
                    var tr = $('<tr></tr>');
                    tr.append('<th>Fecha</th>');
                    tr.append('<th>Tipo</th>');
                    tr.append('<th>Nombre</th>');
                    tr.append('<th class="text-right">Número</th>');
                    tr.append('<th>Descripción</th>');
                    tr.append('<th class="text-right">Debe</th>');
                    tr.append('<th class="text-right">Haber</th>');
                    tr.append('<th class="text-right">Saldo</th>');
                    //Agregar la fila al thead
                    thead.append(tr);
                    //Creamos Fila de Encabezado de Cuenta y saldo
                    var tr1 = $('<tr></tr>');
                    var tcuenta = 'CUENTA: ' + response[0]['cuenta_bancue'] + ' BANCO ' + response[0]['nombre_banco'];
                    tr1.append('<th colspan="6">'+tcuenta+'</th>'); 
                    tr1.append('<th class="text-right">Saldo al:</th>'); 
                    var tsaldo = parseFloat(response[0]['saldo']);
                    tr1.append('<th class="text-right">'+format_number_with_dec(tsaldo,2)+'</th>'); 
                    //Agregar la fila al thead
                    thead.append(tr1);
                    //Aagregar Thead a la tabla
                    tabla.append(thead);
                    var cuerpo = $('<tbody></tbody>')
                    tabla.append(cuerpo);
                    $.each(response, function(i,xitem){
                        var tdebe = 0;
                        var thabe = 0;                    
                        if(xitem.acc_bantmo == "A"){
                            tdebe = parseFloat(xitem.mon_mov_nac);
                            tsaldo += tdebe;
                        }else{
                            thabe = parseFloat(xitem.mon_mov_nac);
                            tsaldo -= thabe;
                        }
                        var fecha = xitem.fecha_comp.split('-');
                         var fila = $('<tr></tr>');
                         fila.append('<td>'+fecha[2]+'-'+fecha[1]+'-'+fecha[0]+'</td>');
                         fila.append('<td>'+xitem.cod_bantmo+'</td>');
                         fila.append('<td>'+xitem.nom_bantmo+'</td>');
                         fila.append('<td class="text-right">'+xitem.num_banmov+'</td>');
                         fila.append('<td>'+xitem.des_banmov+'</td>');
                         if(tdebe != 0){
                            fila.append('<td class="text-right">'+format_number_with_dec(tdebe,2)+'</td>');
                         }else{
                            fila.append('<td class="text-right"></td>');
                         }
                         if(thabe != 0){
                            fila.append('<td class="text-right">'+format_number_with_dec(thabe,2)+'</td>');
                         }else{
                            fila.append('<td class="text-right"></td>');
                         }

                         fila.append('<td class="text-right">'+format_number_with_dec(tsaldo,2)+'</td>');
                         cuerpo.append(fila);
                    });
                    var tpie = $('<tfoot></tfoot>');
                    tpie.append('<th>Fecha</th>');
                    tpie.append('<th>Tipo</th>');
                    tpie.append('<th>Nombre</th>');
                    tpie.append('<th class="text-right">Número</th>');
                    tpie.append('<th>Descripción</th>');
                    tpie.append('<th class="text-right">Debe</th>');
                    tpie.append('<th class="text-right">Haber</th>');
                    tpie.append('<th class="text-right">Saldo</th>');
                    tabla.append(tpie);
               }
          },
          complete: function(data){
               $('.loader').hide();
          },
          error: function (xhr, status, error) {
               $(".loader").hide();
               console.log(error);
          }
     })
})
function init(){
     //Cargar empresas
    $('form')[0].reset(); 
    listar_empresas(0);
    listar_cuentas_ban(0);
    $('#' + nom_tabla).empty();
    $("#fec_ini").val(getFirstDateofMonth());
    $('#fec_fin').val(getLastDateofMonth());
}