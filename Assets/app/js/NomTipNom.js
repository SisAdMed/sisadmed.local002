//Validación de campos
jQuery.validator.setDefaults({
  debug: true,
  success: "valid"
});
$().ready(function(){
    $.validator.setDefaults({
        ignore: [],
        debug: true,
        success: "valid"
    });
    $("form[name='my_form']").validate({
        rules:{
            id_emp: "required",
            codigo: {
                required: true,
                minlength: 2,
                maxlength: 2,
            },
            nombre: {
                required: true,
                minlength: 4,
                maxlength: 50,
            },
            freq: "required",
            tipo: "required",
            nomcue: "required",
        },
        messages:{
            id_emp: "Debe especificar una Empresa",
            codigo: {
                required: "Debe especificar un Código",
                minlength: "Debe contener al menos 2 carácteres",
                maxlength: "Debe contener máximo 2 carácteres",
            },
            nombre: {
                required: "Debe especificar un nombre",
                minlength: "Debe contener al menos 4 carácteres",
                maxlength: "Debe contener máximo 50 carácteres",
            },
            freq: "Se requiere una frecuencia para el Tipo",
            tipo: "Se requiere un valor para el tipo",
            nomcue: "Debe especificar un concpeto de Sueldo"
        },
        submitHandler: function(form){
            form.submit();
        }
    });
})
//Al ingresar a la apliacion
$(document).ready(function(){
    id = $("#id").val();
    if(id){

    }else{
        listar_empresas();
        listar_frec_nom('', 'freq', false);
        listar_tipo_nom('', 'tipo', false);
        listar_si_no('', 'contrato');
    }
})