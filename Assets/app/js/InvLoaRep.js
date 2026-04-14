//Variables
//Validar Formulario
$(function(){
    $("form[name='my_form']").validate({
        rules:{
            id_emp: "required",
        },
        messages:{
            id_emp: "Debe especificar una empresa",
        },
    })
})
//Eventos al inciar la app
$(document).ready(function(){
    //Cargar combos
    listar_empresas();
    listar_marcas('');
    listar_grupos('');
})
function reportExcel(){
    Swal.fire({
        icon: 'question',
        title: "¿Está seguro que desea imprimir?",
        showCancelButton: true,
        confirmButtonText: "Imprimir",
    }).then((result) => {
        if (result.isConfirmed) {
            window.open(`${base_url}/InvLoaRep/reportExcel/?id_emp=.id_emp`, '_blank');
        }
    });
}