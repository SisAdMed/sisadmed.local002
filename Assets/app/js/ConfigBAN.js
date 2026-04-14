//Variables
let item;
let tipo_banconcep;
//Validar campos
$().ready(function(){
    $.validator.setDefaults({
        ignore: [],
    });
    $("form[name='my_form']").validate({
        rules:{
            id_emp: 'required',
        },
        messages:{
            id_emp: 'Debe especificar una Empresa'
        }
    })
})
//Validar formulario dependiendo de lo solicitado nuevo/editar
$(document).ready(function(){
    id = $("#id").val();
    if(id){
        dat_form(id);
    }else{
        listar_empresas(0);
        listar_status(1);
    }
})
//Cargar formulario con los datos
function dat_form(id){
    const url = `${base_url}/ConfigBAN/show_row`;
    $.ajax({
        url: url,
        type: 'POST',
        data: {id:id},
        dataType: 'json',
        success: function(response){
            id_emp = response['id_emp']
            listar_empresas(id_emp);
            //
            id_bancon_CXC = response['id_bancon_CXC'];
            $("#id_bancon_CXC").val(id_bancon_CXC);
            nom_con_ban = response['nom_bancon_CXC'];
            $("#nom_bancon_CXC").val(nom_con_ban);
            //
            id_bancon_CXP = response['id_bancon_CXP'];
            $("#id_bancon_CXP").val(id_bancon_CXP);
            nom_con_ban = response['nom_bancon_CXP'];
            $("#nom_bancon_CXP").val(nom_con_ban);
            //
            id_bancon_RETIVA = response['id_bancon_RETIVA'];
            $("#id_bancon_RETIVA").val(id_bancon_RETIVA);
            nom_con_ban = response['nom_bancon_RETIVA'];
            $("#nom_bancon_RETIVA").val(nom_con_ban);
            //
            status = response['status'];
            listar_status(status);
        }
    })
}
//saber el tipo de concepto a cargar
$(document).on('click', '.tipo_banconcep', function(){
    tipo_banconcep = $(this).data('id');
})
//Seleccionar registro de Tabla Modal
//Listar dependiendo de la empresa
//
//Seleccionar registro marcado del Modal de Conceptos Bancarios y mostrarlo en el formulario
$('body').on('click', '#tblModal_BanConceptos tr', async function() {
    id_bancon = $(this).attr('id');
    var nom_con = await nom_con_ban01(id_bancon);
    if(tipo_banconcep == 'cob'){
        $("#id_bancon_CXC").val(id_bancon);
        $("#nom_bancon_CXC").val(nom_con['nom_bancon']);
    }else if(tipo_banconcep == 'pag'){
        $("#id_bancon_CXP").val(id_bancon);
        $("#nom_bancon_CXP").val(nom_con['nom_bancon']);
    }else{
        $("#id_bancon_RETIVA").val(id_bancon);
        $("#nom_bancon_RETIVA").val(nom_con['nom_bancon']);
    }
    $('#modal-BanConceptos').modal('hide')
    $("#tblModal_BanConceptos").DataTable().clear();
    $("#tblModal_BanConceptos").DataTable().destroy()
});
//Busqueda de Concepto Bancario
var nom_con_ban01 = async function (id_bancon){
    var datos = new FormData();
    datos.append('id_bancon', id_bancon);
    try{
        const url = `${base_url}/BanConceptos/nom_con_ban`;
        var respuesta = await fetch (url, {
            method: "POST",
            body: datos,
        });
        var resultado = await respuesta.json();
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                resolve(resultado);
            }, 200);
        }) ;
    }catch (err){
        console.log(err);
    }
}