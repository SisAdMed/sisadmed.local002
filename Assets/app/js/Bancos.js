//Validar campos de formulario
$().ready(function(){
    $("form[name='my_form']").validate({
        rules:{
           cod_banco: {
                required: true,
                minlength: 2,
                maxlength: 4,
            },
             nombre_banco : {
                required: true,
                minlength: 4,
                maxlength: 100,
            },
            extranjero_ban: "required",
            status: "required",
        },
        messages:{
            cod_banco : {
                required: "Debe especificar un Código para el Banco",
                minlength: "El código debe contener al menos 2 carácteres",
                maxlength: "El código de contener máximo 4 carácteres",
            },
            nombre_banco : {
                required: "Debe especificar un Nombre para el Banco",
                minlength: "El nombre debe contener al menos 4 carácteres",
                maxlength: "El nombre debe contener máximo 100 carácteres",
            },
            extranjero_ban: "Debe especificar si es extranjero o no",
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
         //Indica si es una Institucion extranjera o no
        listar_agrupador('', 'extranjero_ban');
        listar_status(9);
    }
});
//Mostrar valores del registro
async function showrow(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url  = `${base_url}/Bancos/showrow`;
        const repuesta = await fetch(url, {
            method: 'POST',
            body: datos,
        })
        const resultado = await repuesta.json();
        if(resultado){
            $("#cod_banco").val(resultado[0]['cod_banco']);
            $('#cod_banco').prop('readonly', true);
            $("#nombre_banco").val(resultado[0]['nombre_banco']);
            extranjero =resultado[0]['extranjero_ban'];
            listar_agrupador(extranjero, 'extranjero_ban');
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
        const url =  `${base_url}/Bancos/destroy`;
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
                window.location.href = `${base_url}/Bancos`;
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