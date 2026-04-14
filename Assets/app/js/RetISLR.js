//Declaración de variables
id = '';
//Validación de campos
let table;
$().ready(function(){
    //esperas
    //Carga inicial del index
    //Validacion del formulario
    const url  = `${base_url}/RetISLR/load_screan_main`;
    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        beforeSend: function(){
            $('.loader').show();
        },
        success: function(data){
            table = $('#tblTable').DataTable({
                destroy: true,
                data: data,
                columns:[
                    {data: 'id'},
                    {data: 'fecha_vigencia', render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN)},
                    {data: 'descrip'},
                    {data: 'minimo',  render: $.fn.dataTable.render.number('.', ',', 2, '') },
                    {data: 'maximo',  render: $.fn.dataTable.render.number('.', ',', 2, '')},
                    {data: 'por_reten',  render: $.fn.dataTable.render.number('.', ',', 2, '')},
                    {data: 'por_imp_suj_ret',  render: $.fn.dataTable.render.number('.', ',', 2, '')},
                    {data: 'fac_reten',  render: $.fn.dataTable.render.number('.', ',', 2, '')},
                    {data: 'status', className: 'text-center',
                        render: function(data, type){
                            if(data == 1){
                                return `<span class="badge badge-success">Activo</span>`;
                            }else{
                                return `<span class="badge badge-danger">Inactivo</span>`;
                            }
                        },
                    },
                    {data: null, className: "text-center",
                        render: function(data, type){
                            return "<a href="+`${base_url}/RetISLR/edit/`+data.id+" type='button' class='btn btn-warning btn-xs modify' title='Editar'><i class='fa fa-edit'></i></a> " + " " +  
                            "<a type='button' class='btn btn-danger btn-xs destroy' title='Eliminar' data-regid="+data.id+"><i class='fa fa-trash'></i></a>"
                        },
                
                    },
                ],
                language: {
                    url :`${base_url}/Assets/json/es-ES.json`,
                },
                columnDefs: [
                    {targets: 0, visible: false},
                    {targets: 3, className: 'text-right'},
                    {targets: 4, className: 'text-right'},
                    {targets: 5, className: 'text-right'},
                    {targets: 6, className: 'text-right'},
                    {targets: 7, className: 'text-right'},
                ],
                fnCreatedRow: function( rowEl, data) {
                    $(rowEl).attr('id', data['id']);
                  }
            });
        },
        complete: function(){
            $('.loader').hide();
        },
        error: function (xhr, status, error) {
            $(".loader").hide();
        }
    })
        
    $("form[name='my_form']").validate({
        rules: {
            fecha_vigencia: "required",
            descrip: "required",
            por_reten: "required",
            code_seniat: {
                required: true,
                minlength: 3,
                maxlength: 3,
            }
        },
        mesagges: {
            fecha_vigencia: "Debe especificar una fecha",
            descrip: "Debe especificar una descripción",
            por_reten: "Debe especificar un porcentaje de retención",
            code_seniat: {
                required: "Debe especificar el Código Seniat según tabla",
                minlength: "Debe especificar mínimo 3 caracteres",
                maxlength: "Debe especificar máximo 3 caracteres",
            }
        }
    });
})
//Al ingresar a la aplicacion
$().ready(function(e){
    id = $("#id").val();
    if(id){
        dat_form(id);
        
    }else{
        //Valores por Defecto
        $('.myNumberFormatDom').each(function(index){
            inputid = $(this).attr('id');
            idname= "#"+inputid;
            AutoNumeric.set(idname, 0);
            $(idname+'_for').val(0);
        });
        $("#fecha_vigencia").val(GetTodayDate(0));
        listar_status(1);
        //Para el reporte
        listar_empresas(0);
        $("#fec_ini").val(getFirstDateofMonth());
        $("#fec_ini").trigger('change');
    }
})
function dat_form(id){
    const url = `${base_url}/RetISLR/show_row`;
    $("#id").val(id);
    try {
        $.ajax({
            url: url,
            type: 'POST',
            data: {id: id},
            typeData: 'json',
            success: function(data01){
                data = JSON.parse(data01);
                $("#fecha_vigencia").val(data.fecha_vigencia);
                $("#descrip").val(data.descrip);
                //minimo
                $("#minimo_for").val(format_number_with_dec_new(data.minimo,2));
                //maximo                
                $("#maximo_for").val(format_number_with_dec_new(data.maximo,2));
                //por_imp_suj_ret
                $("#por_imp_suj_ret_for").val(format_number_with_dec_new(data.por_imp_suj_ret,2)    );
                //por_reten                
                $("#por_reten_for").val(format_number_with_dec_new(data.por_reten,2));
                //fac_reten                
                $("#fac_reten_for").val(format_number_with_dec_new(data.fac_reten,2));
                $("#code_seniat").val(data.code_seniat);
                listar_status(data.status);
            },
            fail: function(){
                Swal.fire({
                    title: "Error!",
                    text: "ERROR. Favor informar al Adminstrador del Sistema " ,
                    icon: "error"
                })
            }, 
            error: function(xhr, textStatus, errorMessage){
                Swal.fire({
                    title: "Error!",
                    text: "ERROR " + errorMessage + textStatus + xhr + ". Favor informar al Adminstrador del Sistema " ,
                    icon: "error"
                })
            }
        })
    } catch (error) {
        console.log(error);
    }

}
$('.myNumberFormatDom').on('propertychange change keyup paste input lostfocus', function(){
    inputid = $(this).attr('id');
    idname= "#"+inputid+'_for'
    var price  = AutoNumeric.getNumber('#'+inputid);
    $(idname).val(price);
})
$('#tblTable').on('click', '.modify', function () {
    var regID = $(this).data('regid');
    $("#id").val(regID);
});
$('#tblTable').on('click', '.destroy', function () {
    var regID = $(this).data('regid');
    xmsg = "¿Está usted seguro de eliminar este registro?"; 
    Swal.fire({
        title: xmsg,
        text: "Está acción no se podrá revertir!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si eliminar registro",
        cancelButtonText: "Cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
            const url = `${base_url}/RetISLR/borrar`
            $.ajax({
                url: url,
                type: 'POST',
                data: {regID: regID},
                typeData: 'json',
                success: function(data){
                    response = JSON.parse(data);
                    if(response.status){
                        Swal.fire({
                            title: "Borrado!",
                            text: "El registro ha sido eliminado satisfactoriamente.",
                            icon: "success"
                        }).then(function() {
                            location.reload();
                          });
                    }else{
                        Swal.fire({
                            title: "Error!",
                            text: "El registro no se pudo elimianr ya que posee movimientos.",
                            icon: "error"
                        })
                    }
                   
                },
                fail: function(){
                    Swal.fire({
                        title: "Error!",
                        text: "ERROR. Favor informar al Adminstrador del Sistema " ,
                        icon: "error"
                    })
                }, 
                error: function(xhr, textStatus, errorMessage){
                    Swal.fire({
                        title: "Error!",
                        text: "ERROR " + errorMessage + textStatus + xhr + ". Favor informar al Adminstrador del Sistema " ,
                        icon: "error"
                    })
                }
            });
        }
      });
  });
  function report_retislr (element){
    //Validar que se haya seleccionado una empresa
    let id = element.dataset.id;
    if( validarcampos()){
       generate_report(id);
    }
}
//Validar los campos del formulario
function validarcampos(){
    id_emp = $("#id_emp").val();
    if(!id_emp){
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Debe indicar una empresa para generar el reporte",
            didClose: () => { $("#id_emp").focus(); }
          });
        return false;
    }
    //Validar que se haya seleccionado una fecha de inicio
    fec_ini = $("#fec_ini").val();
    if(!fec_ini){
        Swal.fire({
            icon: "error",
            title: "Error...",
            text: "Debe indicar una fecha de inicio para generar el reporte",
            didClose: () => { $("#fec_ini").focus(); }
          });
        return false;
    }
    //Validar que se haya seleccionado una fecha de fin
    fec_fin = $("#fec_fin").val();
    if(!fec_fin){
        Swal.fire({
            icon: "error",
            title: "Error...",
            text: "Debe indicar una fecha de corte para generar el reporte",
            didClose: () => { $("#fec_fin").focus(); }
          });
        return false;
    }
    //Validar que la fecha final no sea menor a la fecha inciial
    if(fec_fin < fec_ini){
        Swal.fire({
            icon: "error",
            title: "Error...",
            text: "Fecha final no puede ser menor a la fecha incial",
            didClose: () => { $("#fec_fin").focus(); }
          });
        return false;
    }
    return true;
}
function generate_report(id){
    //Reporte en Excel
    id_emp = $("#id_emp").val();
    nom_empresa = $("#id_emp option:selected").text();
    fec_ini = $("#fec_ini").val();
    fec_fin = $("#fec_fin").val();
    fecha_ini = fec_ini.split('-');
    fecha_fin = fec_fin.split('-');
    Swal.fire({
        icon: 'question',
        title: "¿Está seguro que desea generar el Reporte de Retención de I.S.L.R. de la empresa " +  nom_empresa +  " correspondiente al período del " + fecha_ini[2] + '/' + fecha_ini[1] + '/' + fecha_ini[0] + "  al " + fecha_fin[2] + '/' + fecha_fin[1] + '/' + fecha_fin[0] + " en " + id + "?",
        showCancelButton: true,
        confirmButtonText: "Imprimir",
    }).then((result) => {
        if (result.isConfirmed) {
            if(id=="excel"){
                window.open(`${base_url}/RetISLR/report_retiSLR?id_emp=` + id_emp + `&fec_ini=` + fec_ini + `&fec_fin=` + fec_fin, '_blank');
            }else if(id=="xml"){
                window.open(`${base_url}/RetISLR/report_retiSLR_xml?id_emp=` + id_emp + `&fec_ini=` + fec_ini + `&fec_fin=` + fec_fin, '_blank');
            }
        }
    });
}