//Validar campos de formulario
$().ready(function(){
    $("form[name='my_form']").validate({
        rules:{
           cod_bantdo: {
                required: true,
                minlength: 2,
                maxlength: 10,
            },
             nom_bantdo: {
                required: true,
                minlength: 4,
                maxlength: 100,
            },
            status: "required",
        },
        messages:{
            cod_bantdo: {
                required: "Debe especificar un Código de Documento",
                minlength: "El código debe contener al menos 2 carácteres",
                maxlength: "El código de contener máximo 10 carácteres",
            },
            nom_bantdo: {
                required: "Debe especificar un Nombre de Documento",
                minlength: "El nombre debe contener al menos 4 carácteres",
                maxlength: "El nombre debe contener máximo 100 carácteres",
            },
            status: "Debe especificar un status",
        },
        submitHandler: function(form){
            form.submit()
        }
    });
});
//Validar el tipo de formulario para sus acciones
$(document).ready(function(){
    id = $("#id").val();
    if(id){
        showrow(id);
    }else{
        listar_status(1);
    }
});
//Mostrar valores del registro
async function showrow(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url  = `${base_url}/BanTipoDoc/showrow`;
        const repuesta = await fetch(url, {
            method: 'POST',
            body: datos,
        })
        const resultado = await repuesta.json();
        if(resultado){
            listar_empresas(id_emp);
            $('#id_emp').attr("disabled", true);
            $("#cod_bantdo").val(resultado[0]['cod_bantdo']);
            $('#cod_bantdo').prop('readonly', true);
            $("#nom_bantdo").val(resultado[0]['nom_bantdo']);
            status = resultado[0]['status'];
            listar_status(status);
        }
    }catch(error){
        console.log(error);
    }
}
//Funcion Boton Eliminar
function eliminarBtn(element) {
    let id = element.dataset.id;
    let name = element.dataset.name;
    Swal.fire({
        position: 'top-end',
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
            borrar(id);
        };
    });
}
//Funcion para borrar
async function borrar(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url =  `${base_url}/BanTipoDoc/destroy`;
        const repuesta = await fetch(url, {
            method:"POST",
            body: datos,
        });
        const resulta = await repuesta.json();
        Swal.fire({
            position: 'top-end',
            icon: `${resulta.icon}`,
            title: `${resulta.title}`,
            text: `${resulta.msg}`,
        }).then((result) => {
            if (result.isConfirmed){
                window.location.href = `${base_url}/BanTipoDoc`;
            };
        });
    }catch(error){
         Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Error.....',
            text: 'No se pudo eliminar el registro ya que se encuentra asociado'
        });
    }
}