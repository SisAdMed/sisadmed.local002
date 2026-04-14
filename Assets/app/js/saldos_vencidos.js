//Variables
let tabla = "tbl_saldos_vencidos";
$().ready(function(){
    listar_empresas();
     $('#condi').hide();
     $('#btn-search').hide();
});
$(document).on('change', '#id_emp', function(){
     id_emp = $(this).val();
     $('#id_cli').val('');
     $('#nom_cli').val('');
     if(id_emp){
          $('#condi').show();
          $('#btn-search').show();
          $(tabla).empty();
     }else{
           $('#condi').hide();
     }
})
//Validar que se haya seleccionado un cliente
$(document).on('change', '#id_cli', async function(){
     const datosFetched = await tid_vend(id_cli);
     $("#nom_cli").val(datosFetched['nom_ent']);
     $(tabla).empty();
})
//Buscar registros
$(document).on('click', '#btn-search', function(){
     var url = `${base_url}/CXCDocument/saldos_vencidos_data`;
     nom_cli = $("#nom_cli").val();
     id_cli = $("#id_cli").val();
     $(tabla).empty();
     if(id_emp === ''){
          Swal.fire({
               icon: "error",
               title: "Oops...",
               text: `Debe indicar una Empresa`,
               footer: `<b class="text-danger">Estos datos son importantes para poder generar la consulta</b>`
          });
     }
     //buscar los registros solicitados
     if(id_emp != ''){
          var tenca = false;
          $.ajax({
               url: url,
               method: 'POST',
               data: {id_emp: id_emp, id_cli: id_cli},
               beforeSend: function(){
                    $('.loader').show();
               },
               success: function(data){
                    response = JSON.parse(data);
                    if(response){
                      var htmlTags = '';
                      var id_ent_previous = '';
                      var tenca = false;
                    //Encabezado Empresa
                     htmlTags += 
                         `<thead>` +
                              `<tr style="background-color:#00FFFF">` +
                                   `<th colspan="15" class="text-center" style="background-color:#00FFFF">`+response[0]["nombre_emp"]+`</th>`;
                              `</tr>` +
                         `</thead>`;
                         var total_00 = 0;
                         var total_01 = 0;
                         var total_02 = 0;
                         var total_03 = 0;
                         var total_04 = 0;
                         var total_05 = 0;
                      $.each(response, function(i, item){
                         xcount = i 
                          //Agregar Total por cliente
                         if(id_ent_previous != item.id_ent && tenca){
                              htmlTags +=
                              `<tfoot style="background-color:#808d92">` +
                                   `<tr>` +
                                        `<th class="text-center"></th>` +
                                        `<th colspan="5"></th>` + 
                                        `<th class="text-right"></th>` +
                                        `<th class="text-center"></th>` +
                                        `<th class="text-right">Totales</th>` +
                                        `<th class="text-right">`+format_number_with_dec(total_00, 2)+`</td>` +
                                        `<th class="text-right">`+format_number_with_dec(total_01, 2)+`</td>` + 
                                        `<th class="text-right">`+format_number_with_dec(total_02, 2)+`</td>` + 
                                        `<th class="text-right">`+format_number_with_dec(total_03, 2)+`</td>` + 
                                        `<th class="text-right">`+format_number_with_dec(total_04, 2)+`</td>` +
                                        `<th class="text-right">`+format_number_with_dec(total_05, 2)+`</td>` +
                                   `</tr>` +
                              `</tfoot>`;
                              total_00 = 0;
                              total_01 = 0;
                              total_02 = 0;
                              total_03 = 0;
                              total_04 = 0;
                              total_05 = 0;
                         }
                         //Encabezado de Cliente
                         if(id_ent_previous != item.id_ent){
                              tenca = true;
                              total_00 = 0;
                              total_01 = 0;
                              total_02 = 0;
                              total_03 = 0;
                              total_04 = 0;
                              total_05 = 0;
                              htmlTags += 
                              `<tr style="background-color:#00FFFF">` +
                                   `<th colspan="15" class="text-center" style="background-color:#808d92">`+item.nom_ent+`</th>`;
                              `</tr>` ;
                              //Titulo de columnas
                              htmlTags += 
                                   `<tr>` +
                                        `<th class="text-center">Tipo</th>` +
                                        `<th colspan="5">Descripcion</th>` +
                                        `<th class="text-right">Número</th>` +
                                        `<th class="text-center">Fecha</th>` +
                                        `<th class="text-center">Moneda</th>` +
                                        `<th class="text-right">Saldo</th>` +
                                        `<th class="text-right">Hasta `+item.fir_due_date+` días</th>` +
                                        `<th class="text-right">Entre `+item.fir_due_date+` y ` +item.sec_due_date+ ` días</th>` +
                                        `<th class="text-right">Entre `+item.sec_due_date+` y ` +item.thi_due_date+ ` días</th>` +
                                        `<th class="text-right">Entre `+item.thi_due_date+` y ` +item.fou_due_date+ ` días</th>` +
                                        `<th class="text-right">Mayor a `+item.fou_due_date+` días</th>` +
                                   `</tr>`
                         }
                         //Detalle de Documentos
                         var fecha ;
                         var x_sal_doc = 0;
                         var x_por_vencer = '';
                         var x_vencido_01 ='';
                         var x_vencido_02 ='';
                         var x_vencido_03 ='';
                         var x_vencido_04 ='';
                         if(item.fecha_comp){
                              fecha = item.fecha_comp.split('-')
                         }
                         x_sal_doc = item.sal_doc;
                         total_00 += x_sal_doc;
                         if(item.por_vencer != 0){
                              x_por_vencer = format_number_with_dec(item.por_vencer, 2)
                              total_01 += item.por_vencer
                         }
                         if(item.vencido_01 != 0){
                              x_vencido_01 = format_number_with_dec(item.vencido_01, 2)
                              total_02 += item.vencido_01
                         }
                         if(item.vencido_02 != 0){
                              x_vencido_02 = format_number_with_dec(item.vencido_02, 2)
                              total_03 += item.vencido_02
                         }
                         if(item.vencido_03 != 0){
                              x_vencido_03 = format_number_with_dec(item.vencido_03, 2)
                              total_04 += item.vencido_03
                         }
                         if(item.vencido_04 != 0){
                              x_vencido_04 = format_number_with_dec(item.vencido_04, 2)
                              total_05 += item.vencido_04
                         }
                         htmlTags += 
                              `<tr>` +
                                   `<td class="text-center">`+item.tipo_codigo+`</td>` + 
                                   `<td colspan="5">`+item.nom_tdoc+`</td>` + 
                                   `<td class="text-right">`+item.num_tdo+`</td>` + 
                                   `<td class="text-center">`+fecha[2]+'-'+fecha[1]+'-'+fecha[0]+`</td>` + 
                                   `<td class="text-center">`+item.codigo_moneda+`</td>` + 
                                   `<td class="text-right">`+format_number_with_dec(item.sal_doc,2)+`</td>` + 
                                   `<td class="text-right">`+x_por_vencer+`</td>` + 
                                   `<td class="text-right">`+x_vencido_01+`</td>` + 
                                   `<td class="text-right">`+x_vencido_02+`</td>` + 
                                   `<td class="text-right">`+x_vencido_03+`</td>` + 
                                   `<td class="text-right">`+x_vencido_04+`</td>` + 
                              `</tr>`
                              if(id_ent_previous != item.id_ent && tenca ){
                                   htmlTags +=
                                   `<tfoot style="background-color:#808d92">` +
                                        `<tr>` +
                                             `<th class="text-center"></th>` +
                                             `<th colspan="5"></th>` + 
                                             `<th class="text-right"></th>` +
                                             `<th class="text-center"></th>` +
                                             `<th class="text-right">Totales</th>` +
                                             `<th class="text-right">`+format_number_with_dec(total_00, 2)+`</td>` +
                                             `<th class="text-right">`+format_number_with_dec(total_01, 2)+`</td>` + 
                                             `<th class="text-right">`+format_number_with_dec(total_02, 2)+`</td>` + 
                                             `<th class="text-right">`+format_number_with_dec(total_03, 2)+`</td>` + 
                                             `<th class="text-right">`+format_number_with_dec(total_04, 2)+`</td>` +
                                             `<th class="text-right">`+format_number_with_dec(total_05, 2)+`</td>` +
                                        `</tr>` +
                                   `</tfoot>`;
                              }
                         id_ent_previous = item.id_ent;
                      });
                       if(htmlTags){
                         document.getElementById(tabla).innerHTML = htmlTags;
                         }
                    }else{
                    Swal.fire({
                              icon: "error",
                              title: "Oops...",
                              html: 'No existen registros para el cliente <b><span class="text-danger">' + nom_cli + '</span></b>',
                         });
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
     }
})