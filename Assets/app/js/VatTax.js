//Variables
let fecha = new Date();
//Validar campos del formulario
$().ready(function(){
    $("form[name='my_form']").validate({
        rules:{
            cod_iva: "required",
            des_iva: "required",
            fec_iva: "required",
            status: "required",
            txr1_iva: "required"
        },
        messages:{
            cod_iva: "Debe especificar un Código para el Impuesto",
            des_iva: "Debe especificar una Descripción para el Impuesto",
            fec_iva: "Debe especificar una Fecha de Vigencia para el Impuesto",
            status: "Debe especificar un Status",
            txr1_iva: "Debe especificar al menos un Impuesto"
        }
    })
})
//Validar el formulario dependiendo de la acción
$(document).ready(function(){
    id = $("#id").val();
    $('#total_impto').prop("disabled", true);
    if(id){
        showrow(id);
    }else{
        $("#fec_iva").val(GetTodayDate())
        listar_status(1);
    }
})
//Mostrar el registro pra consulta y/o modificacion
async function showrow(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url = `${base_url}/VatTax/showrow`;
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        })
        const resultado = await respuesta.json();
        if(resultado){
            $("#cod_iva").val(resultado[0]['cod_iva']);
            $('#cod_iva').prop('readonly', true);
            $("#des_iva").val(resultado[0]['des_iva']);
            $("#fec_iva").val(resultado[0]['fec_iva']);
            $("#txr1_iva").val(resultado[0]['txr1_iva']);
            $("#txr2_iva").val(resultado[0]['txr2_iva']);
            $("#txr3_iva").val(resultado[0]['txr3_iva']);
            $("#txr4_iva").val(resultado[0]['txr4_iva']);
            $("#txr5_iva").val(resultado[0]['txr5_iva']);
            $(".txr1_iva").trigger( "keyup" );
            status = resultado[0]['status'];
            listar_status(status);
        }
    }catch(error){
        console.log(error);
    }
}
//Calcular el total de Impuestos
$('.txr1_iva').on('keyup', function(e){
    e.preventDefault();
    total_impto = 0.00;
    $(".txr1_iva").each(function(){
        console.log(this.value);
        if(!isNaN(this.value) && this.value != ''){
            total_impto += parseFloat(this.value);
        }
    });
    $("#total_impto").val(total_impto);
})