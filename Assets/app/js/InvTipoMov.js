//Variables
var id;
var id_emp;
var item;
//Validar campos

$(function(){
    $("form[name='my_form']").validate({
        rules:{
            id_emp: "required",
            cod_tmoinv: {
                required: true,
                minlength: 2,
                maxlength: 2
            },
            nom__tmoinv: "required",
            tipo_tmoinv: "required",
            status: "required",
        },
        messages:{
            id_emp: "Debe especificar una Empresa",
            cod_tmoinv: {
                required: "Debe especificar un Código de Tipo de Movimiento",
                minlength: "Debe indicar al menos dos carácteres para el Código",
                maxlength: "Debe indicar máximo dos carácteres para el Código"
            },
            nom__tmoinv: "Debe ingresar un Nombre",
            tipo_tmoinv: "Debe indicar el Tipo de Movimiento",
            status: "Debe especificar un Status",
        },
    });
});
//Validar dependiendo de si es Nuevo o Edicion

$().ready(function(){
    id = $("#id").val();
    if(id){
        dat_form(id);
    }else{
        listar_empresas();
        listar_tipMovINV()
        $("#tmosal_tmoinv").css("pointer-events","none");
        $("#id_alm").css("pointer-events","none");
        $("#proximo_tmoinv").css("pointer-events","none");
        listar_status(1);
    }
})
$(document).on('change', '#id_emp', function(){
    id_emp = $(this).val();
})
$(document).on('change', '#tipo_tmoinv', function(){
    tipo_tmoinv = $(this).val();
    $("#tmosal_tmoinv").empty();
    $("#id_alm").empty();
    $("#tmosal_tmoinv").css("pointer-events","none");
    $("#id_alm").css("pointer-events","none");
    if(tipo_tmoinv == 'T'){
        $("#tmosal_tmoinv").css("pointer-events","auto");
        $("#id_alm").css("pointer-events","auto");
        listar_InvTipoMov(id_emp, '', 'tmosal_tmoinv', 'S');
        listar_almacenes(id_emp);
    }
})
$(document).on('change', '#consecutiv__tmoinv', function(){
    consecutiv__tmoinv = $(this);
    if(consecutiv__tmoinv.is(':checked')){
        $("#proximo_tmoinv").css("pointer-events","auto");
    }else{
        $("#proximo_tmoinv").val('');
        $("#proximo_tmoinv").css("pointer-events","none");
    }
})
async function dat_form(id){
    const datos = new FormData();
    datos.append('id', id)
    try {
        const url = `${base_url}/InvTipoMov/show_row`; 
        console.log(url);
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();
        if(resultado){
            id_emp = resultado['id_emp'];
            listar_empresas(id_emp, true);
            $("#cod_tmoinv").val(resultado['cod_tmoinv']);
            $("#cod_tmoinv").css("pointer-events","none");
            $("#nom__tmoinv").val(resultado['nom__tmoinv']);
            tipo_tmoinv = resultado['tipo_tmoinv'];
            listar_tipMovINV(tipo_tmoinv);
            $("#tipo_tmoinv").trigger("change");
            $("#tipo_tmoinv").css("pointer-events","none");
            tmosal_tmoinv = resultado['tmosal_tmoinv'];
            listar_InvTipoMov(id_emp, tmosal_tmoinv, 'tmosal_tmoinv');
            $("#tmosal_tmoinv").css("pointer-events","none");
            id_alm = resultado['id_alm'];
            listar_almacenes(id_emp, id_alm);
            $("#id_alm").css("pointer-events","none");
            status = resultado['status'];
            listar_status(status);
            consecutiv__tmoinv = resultado['consecutiv__tmoinv']
            if(consecutiv__tmoinv == 1){
                $("#consecutiv__tmoinv").prop("checked",true);
                $("#proximo_tmoinv").val(resultado['proximo_tmoinv']);
                $("#proximo_tmoinv").css("pointer-events","none");
            }
            id_ctb = resultado['id_cta'];
            $("#id_ctb").val(id_ctb);
            $("#nom_ctb").val(resultado['nombre_cta']);
            $("#id_ctb").trigger('change');
            id_aux = resultado['id_aux'];
            if(id_aux){
                $("id_aux").val(id_aux);
                $("#nom_aux").val(resultado['nombre_aux'])
            }
        }
    } catch (error) {
        console.log(error);
    }
}