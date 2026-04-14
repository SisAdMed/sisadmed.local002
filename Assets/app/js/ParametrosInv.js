//Validar campos necesarios
(function(){
    $("form[name='my_form']").validate({
        rules:{
            id_emp: "required",
        },
        messages:{
            id_emp: "Debe especificar una empresa.",
        }
    });
})
//Llenar combos al iniciar
var id_emp = $("#id_empr").val();
var costo_pie3 = $("#costo_pie3r").val();
$(document).ready(function(){
   listar_empresas(id_emp);
   if(costo_pie3){
        $("#costo_pie3").val(costo_pie3);
   }
})