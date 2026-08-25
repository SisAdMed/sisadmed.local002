//Variables
id = $("#id").val();
//Validaciones del formulario
$(function(){
    $("#my_form").validate({
        rules:{
            fecha_comint: "required",
            status: "required",
        },
        messages:{
            fecha_comint: "Nombre de proveedor es requerido",
            status: "Debe especificar un status",
        }
    });
})
//Formato de  número de teléfono
jQuery(document).ready(function() {
    id = $("#id").val();
    if(id){
        cargar_data(id);
    }else{
        listar_status(1);
    }
    //Formato de  número de teléfono
    jQuery("#telf_provint").mask("+999 (999) 999-99-99");
});
async function cargar_data(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url = `${base_url}/ProveeInt/cargar_data`;
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos,
        });
        const resultado = await respuesta.json();
        if(resultado){
            $("#nombre_provint").val(resultado['nombre_provint']);
            $("#contacto_provint").val(resultado['contacto_provint']);
            $("#email_provint").val(resultado['email_provint']);
            $("#telf_provint").val(resultado['telf_provint']);
            $("#dir_provint").val(resultado['dir_provint']);
            listar_status(resultado['status']);
        }
    }catch(err){
        console.log(err);
    }
}
function eliminarBtnProveeInt(element){
    id = element.dataset.id;
    name = element.dataset.name;
    const datos = new FormData();
    datos.append('id', id);
    datos.append('nombre_provint', name);
    Swal.fire({
        icon: 'warning',
        title: 'Está seguro de eliminar este registro?',
        showConfirmButton: true,
        confirmButtonText: 'ELIMINAR',
        confirmButtonColor: '#3085d6',
        showCancelButton: true,
        cancelButtonText: 'CANCELAR',
        cancelButtonColor: '#d33',
        buttonsStyling: true,
    }).then((result) => {
        if (result.isConfirmed){
            borrar(datos);
        };
    });
}
async function borrar(datos){
    const url = `${base_url}/ProveeInt/destroy`;
    const repuesta = await fetch(url, {
        method: 'POST',
        body: datos,
    });
    const resultado = await repuesta.json();
    Swal.fire({
        icon: resultado['type'],
        title: resultado['msg'],
        showConfirmButton: true,
    }).then((result) => {
         window.location.href = `${base_url}/ProveeInt`;
    });
}