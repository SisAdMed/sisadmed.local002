//Declaración de variables
let item;

//Validación de campos
$().ready(function(){
    $('#my_form').validate({
        rules: {
            fecha_vigenc: "required",
            desc_retiva: "required",
            tasa_retiva: {
                required: true,
                min: 0.01,
                max: 100,
                step: 0.01
            },
            id_ctb: "requiered",
        },
        mesagges: {
            fecha_vigenc: "Debe especificar una fecha de vigencia",
            desc_retiva: "Debe esppecificar una descripción",
            tasa_retiva: {
                required: "Debe especificar una tasa mayor a 0 y menor o igual a 100",
                min: "El mínimo permitido es de 0,01",
                max: "El máximo permitido es de 100"
            },
            id_ctb: "Debe especificar una cuenta Contable"
        }
    });
})
//Al ingresar a la aplicacion
$().ready(function(e){
    id = $('#id').val();
    if(id){
        dat_form(id);
    }else{
        listar_status(1);
    }
    //Para el reporte
    listar_empresas(0);
    $("#fec_ini").val(getFirstDateofMonth());
    $("#fec_ini").trigger('change');
})
//Cargar pantalla del index
$().ready(function(e){
    load_screan_main()
});
//Datos para el index
function load_screan_main(){
    const url = `${base_url}/RetIva/load_data`;
    try {
        $.ajax({
            url: url,
            method: 'POSt',
            dataType: 'json',
            beforeSend: function(){
                $('.loader').show();
            },
            success: function(data){
                var table = $('#tblTable').DataTable({
                    destroy: true,
                    data: data,
                    columns:[
                        {data: 'id'},
                        {data: 'fecha_vigenc', render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN)},
                        {data: 'desc_retiva'},
                        {data: 'tasa_retiva',  render: $.fn.dataTable.render.number('.', ',', 2, '') },
                        {data: 'min_retiva',  render: $.fn.dataTable.render.number('.', ',', 2, '')},
                        {data: 'status', className: 'text-center',
                            render: function(data, type){
                                if(data == 1){
                                    return `<span class="badge badge-success">Activo</span>`;
                                }else{
                                    return `<span class="badge badge-danger">Inactivo</span>`;
                                }
                            },
                        },
                        {defaultContent: 
                            "<a type='button' class='btn btn-warning btn-xs editar' title='Editar'><i class='fa fa-edit'></i></a> " + " " + 
                             "<a type='button' class='btn btn-danger btn-xs' title='Eliminar'><i class='fa fa-trash'></i></a>",
                             className: "text-center"
                        },
                    ],
                    language: {
                        url :`${base_url}/Assets/json/es-ES.json`,
                    },
                    columnDefs: [
                        {targets: 0, visible: false},
                        {targets: 3, className: 'text-right'},
                        {targets: 4, className: 'text-right'},
                        {targets: 6},
                    ],
                });
                obtener_data("#tblTable tbody", table); 
            },
            complete: function(){
                $('.loader').hide();
            },
            error: function (xhr, status, error) {
                $(".loader").hide();
            }
        })
    } catch (error) {
        console.log(error);
    }
}
var obtener_data = function(tbody, table){
    $(tbody).on('click', "a.editar", function(){
        var data = table.row($(this).parents('tr')).data();
        window.location.href = `${base_url}/RetIva/edit/` + data.id;
    });
}
function dat_form(id){
    const url = `${base_url}/RetIva/show_row`;
    try {
        $.ajax({
            url: url,
            method: 'POST',
            data: {id: id},
            dataType: 'json',
            beforeSend: function(){
                $(".loader").show();
            },
            success: function(data){
                $("#fecha_vigenc").val(data.fecha_vigenc);
                $("#desc_retiva").val(data.desc_retiva);
                $("#tasa_retiva").val(data.tasa_retiva);
                $("#min_retiva").val(data.min_retiva);
                $("#id_ctb").val(data.id_ctb);
                $("#id_ctb").trigger('change');
                $("#id_aux").val(data.id_aux);
                $("#id_aux").trigger('change');
                status = data.status;
                listar_status(status);
            },
            complete: function(){
                $('.loader').hide();
            },
            error: function (xhr, status, error) {
                $(".loader").hide();
            }
        })
    } catch (error) {
        console.log(error);
    }
}
function report_retiva (element){
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
        title: "¿Está seguro que desea generar el Reporte de Retención de IVA de la empresa " +  nom_empresa +  " correspondiente al período del " + fecha_ini[2] + '/' + fecha_ini[1] + '/' + fecha_ini[0] + "  al " + fecha_fin[2] + '/' + fecha_fin[1] + '/' + fecha_fin[0] + " en " + id + "?",
        showCancelButton: true,
        confirmButtonText: "Imprimir",
    }).then((result) => {
        if (result.isConfirmed) {
            if(id=="excel"){
                window.open(`${base_url}/RetIva/report_retiva?id_emp=` + id_emp + `&fec_ini=` + fec_ini + `&fec_fin=` + fec_fin, '_blank');
            }else if(id=="txt"){
                window.open(`${base_url}/RetIva/report_retiva_text?id_emp=` + id_emp + `&fec_ini=` + fec_ini + `&fec_fin=` + fec_fin, '_blank');
            }
        }
    });
}