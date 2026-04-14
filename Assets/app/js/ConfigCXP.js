//Variables
let tab;
var tmodo = '';
let item;
//Validar campos
$(function(){
    $("form[name='my_form']").validate({
        rules:{
            id_emp: "required",
            show_doc: "required",
            over_charges: "required",
            fir_due_date : "required",
            sec_due_date: "required",
            thi_due_date: "required",
            fou_due_date: "required",
            status: "required",
        },
        messages:{
            id_emp: "Debe especificar una empresa",
            show_doc: "Debe indicar si se mostraran todos los documentos",
            over_charges: "Debe indicar si permite cobros en exceso",
            fir_due_date : "Debe indicar una cantidad de dias",
            sec_due_date: "Debe indicar una cantidad de dias",
            thi_due_date: "Debe indicar una cantidad de dias",
            fou_due_date: "Debe indicar una cantidad de dias",
            status: "Debe indicar un Status",
        },
    })
})
//Al ingresar a la apliacion
$(document).ready(function(){
    form = $("form").attr("id")
    id = $('#id').val();
    //Ver y/o Modificar
    if(id){
        dat_form();
    }else if(form != undefined){
         //Nuevo registro
        dat_form_new();
    }else if(form == undefined){
        //Cargar el index
        cargar_screen_main();
    }
})
//Cargar formulario para registro nuevo
function dat_form_new(){
    listar_empresas(0);
    listar_agrupador(0, "show_doc");
    listar_agrupador(0, "over_charges");
    listar_conceptos_CXP(0, 'id_retiva');
    listar_conceptos_CXP(0, 'id_retislr');
    listar_status(1);
}
//Cargar formulario con los datos
async function dat_form(){
    id = $('#id').val();
    const data_form = await dat_global(id);
    id_emp = data_form['id_emp'];
    listar_empresas(id_emp);
    listar_agrupador(data_form['show_doc'], "show_doc");
    listar_agrupador(data_form['over_charges'], "over_charges");
    $("#fir_due_date").val(data_form['fir_due_date']);
    $("#sec_due_date").val(data_form['sec_due_date']);
    $("#thi_due_date").val(data_form['thi_due_date']);
    $("#fou_due_date").val(data_form['fou_due_date']);
    id_tdo = data_form['id_tdo'];
    listar_tipos_documentos_cxp(id_emp, 'M', id_tdo, false);
    $("#con_ret_iva").val(data_form['con_ret_iva']);
    //Bloquear campos
    $('#id_emp').attr("disabled", true);
    id_retiva = data_form['id_retiva']
    listar_conceptos_CXP(id_retiva, 'id_retiva');
    id_retislr = data_form['id_retislr']
    listar_conceptos_CXP(id_retislr, 'id_retislr');
    listar_status(data_form['status']);
}
//Seleccionar registro
var dat_global = async function (rid){
    if(rid>0){
        const datos = new FormData();
        datos.append('id', rid);
        try{
            const url = `${base_url}/ConfigCXP/showrow`;
            const resp = await fetch( url, {
                method: 'POST',
                body: datos,
            });
            var result = await resp.json();
            return new Promise((resolve, reject) => {
                setTimeout(() => {
                    resolve(result);
                }, 100);
            }) ;
        }catch(err){
            console.log(err);
        }
    }
}
//Tipos de Documentos Factura por Empresa
$(document).on('change', '#id_emp', function(){
    id_emp = $(this).val();
    listar_tipos_documentos_cxp(id_emp, 'M', id_tdo, false); 
})
//Carga del Index, cargar los registros
async function cargar_screen_main(){
    const url = `${base_url}/ConfigCXP/cargar_screen_main`;
    $.ajax({
        url: url,
        method: 'POST',
        dataSrc: '',
        data: {},
        beforeSend: function(){
            $('.loader').show();
        },
        success: function(resultado){
            response = JSON.parse(resultado);
            tab = '';
            response.forEach(function(ConfigCXP){
                //Status
                if(ConfigCXP.status == 1){
                    status = '<td class="text-center"><span class="badge badge-success">Activo</span></td>'
                }else{
                    status = '<td class="text-center"><span class="badge badge-danger">Inactivo</span></td>'
                }
                //Acciones
                acciones = `
                    <td class="text-center">
                        <a type="button" class="btn btn-warning btn-xs" href="${base_url + '/ConfigCXP/edit/' +  ConfigCXP.id_config}"><i class="fa fa-edit"></i></a>
                        <button id="Data" data-id="${ConfigCXP.id_config}" data-name="${ConfigCXP.nom_empresa}" data-code = "${ConfigCXP.nom_empresa}" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)"><i class="fa fa-trash"></i></button>
                    </td>`;
                tab += `<tr>
                    <td>${ConfigCXP.id_config}</td>
                    <td>${ConfigCXP.nom_empresa}</td>
                    ${status}
                    ${acciones}
                </tr>`;
            });
             $("#tblTableConfigCXP").DataTable().clear();
             $("#tblTableConfigCXP").DataTable().destroy();
            document.getElementById('tbody').innerHTML = tab;
            var tblMain = $("#tblTableConfigCXP").DataTable({
                language: {
                url :`${base_url}/Assets/json/es-ES.json`,
                },
                columnDefs: [
                    {
                        targets: 0,
                        visible: false,
                        searchable: false,
                    },
                ],
            });
        },
        complete: function(){
             $('.loader').hide();
        },
        error: function (xhr, status, error) {
            $(".loader").hide();
        }
    })
}
