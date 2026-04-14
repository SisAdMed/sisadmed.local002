//Validar campos necesarios
(function(){
    $("form[name='my_form']").validate({
        rules:{
            id_emp: "required",
            id_tipcom: "required",
        },
        messages:{
            id_emp: "Debe especificar una empresa.",
            id_tipcom: "Debe especificar un Tipo de Comprobante Contable"
        }
    });
})
//Llenar combos al iniciar
$(document).ready(function(){
    id = $("#id").val();
    if(id){
        showrow(id)
    }else{
        listar_empresas();
    }
})
async function showrow(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url  = `${base_url}/ParametrosCtb/showrow`;
        const repuesta = await fetch(url, {
            method: 'POST',
            body: datos,
        })
        const resultado = await repuesta.json();
        if(resultado){
            id_emp = resultado[0]['id_emp'];
            listar_empresas(id_emp);
            $('#id_emp').attr("disabled", true);
        }
    }catch(error){
        console.log(error);
    }
}