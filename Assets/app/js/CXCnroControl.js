//Validar campos del formulario
$(function(){
    $("form[name='my_form']").validate({
        rules:{
            id_emp: "required",
            ini_nroControl: "required",
            fin_nroControl: "required",
            next_nroControl: "required",
            fec_asig: "required",
            status: "required"
        },
        messages:{
            id_emp: "Debe especificar una empresa.",
            ini_nroControl: "Debe especificar un número inicial.",
            fin_nroControl: "Debe especificar un número final.",
            next_nroControl: "Debe especificar un próximo número.",
            fec_asig: "Debe especificar una fecha de asignación.",
            status: "Debe especificar un status"
        }
    });
})
//Llenar combos al iniciar
$(document).ready(function(){
    id = $("#id").val();
    if(id){
        showrowupdate_nroContrl(id);
    }else{
        listar_empresas();
        listar_status(1);
    }
})
async function showrowupdate_nroContrl(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url = `${base_url}/CXCnroControl/showrowupdate_nroContrl`;
        const respuesta = await fetch( url, {
            method: 'POST',
            body: datos,
        })
        const resultado = await respuesta.json();
        id_emp = resultado[0].id_emp;
        listar_empresas(id_emp, true);
        $("#ini_nroControl").val(resultado[0].ini_nroControl);
        $("#fin_nroControl").val(resultado[0].fin_nroControl);
        $("#next_nroControl").val(resultado[0].next_nroControl);
        $("#fec_asig").val(resultado[0].fec_asig);
        status = resultado[0].status;
        listar_status(status);
    }catch(error){
        console.log(error);
    }
}