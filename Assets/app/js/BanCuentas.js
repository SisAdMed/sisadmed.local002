//Variables Globales
let xBanco = '';
let xSucur = '';
let xContr = '';
let xBancu = '';
let id_banco;
let id_cta = '';
id_aux = '';
let item;   
//Validar campos del formulario
$().ready(function(){
    $("form[name='my_form']").validate({
        rules: {
            id_emp: "required",
            id_banco: "required",
            suc_bancue: {
                required: true,
                minlength: 4,
                maxlength: 4,
            },
            con_bancue:{
                required: true,
                minlength: 2,
                maxlength: 2,
            },
            cue_bancue:{
                required: true,
                minlength: 5,
                maxlength: 10,
            },
            id_cta: "required",
            id_aux: "required",
            status: "required"
        },
        messages:{
            id_emp: "Debe especificar una empresa",
            id_banco: "Debe especificar una Institución Bancaria",
            suc_bancue: {
                required: "Debe especificar un Código de Sucursal de 4 dígitos",
                minlength: "Debe especificar al menos 4 caracteres",
                maxlength: "Debe especificar maxímo 4 carácteres",
            },
            con_bancue:{
                required: "Debe especificar el Número de control",
                minlength: "Debe especificar al menos 2 carácteres",
                maxlength: "Debe especificar maxímo 2 carácteres",
            },
            cue_bancue:{
                required: "Debe espeficicar el Número de cuenta corto",
                minlength: "Debe especificar al menos 5 caracteres",
                maxlength: "Debe especificar maxímo 10 carácteres",
            },
            id_cta: "Se debe especificar una Cuenta Contable",
            id_aux: "Debe especificar un Auxiliar Contable",
            status: "Debe espeficicar un status"
        },
        submitHandler: function(form){
            form.submit();
        }
    });
})
//Validar dependiendo de lo solicitado nuevo y/o editar
$(document).ready(function(){
    id = $("#id").val();
    if(id){
        showrow(id);
    }else{
        listar_empresas();
        listar_bancos();
        listar_status(9);
        listar_ctas_ctbles();
        listar_aux_ctbles(0, 'id_aux', false);
    }
})
//Mostrar los datos para modificación
async function showrow(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url = `${base_url}/BanCuentas/showrow`;
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos,
        });
        const resultado = await respuesta.json();
        if(resultado){
            id_emp = resultado[0]['id_emp'];
            listar_empresas(id_emp);
            id_banco = resultado[0]['id_banco'];
            listar_bancos(id_banco);
            xBanco = resultado[0]['cod_banco'];
            $("#suc_bancue").val(resultado[0]['suc_bancue']);
            xSucur = resultado[0]['suc_bancue'];
            $("#con_bancue").val(resultado[0]['con_bancue']);
            xContr = resultado[0]['con_bancue'];
            $("#cue_bancue").val(resultado[0]['cue_bancue']);
            xBancu = resultado[0]['cue_bancue'];
            $("#cuenta_bancue").val(xBanco+'-'+xSucur+'-'+xContr+'-'+xBancu);
            id_ctb = resultado[0]['id_ctb'];
            nom_ctb = resultado[0]['nombre_cta'];
            nom_aux = resultado[0]['nombre_aux'];
            $("#id_ctb").val(id_ctb);
            $("#nom_ctb").val(nom_ctb);
            id_aux = resultado[0]['id_aux'];
            $("#nom_aux").val(nom_aux);

            status = resultado[0]['status'];
            listar_status(status);
        }
    }catch(error){
        console.log(error);
    }
}
//Crear Cuenta a medida que se vaya cargando la información
$("#id_banco").change(async function(){
    id_banco = $(this).val();
    $("#cuenta_bancue").val('');
    const datos = new FormData();
    datos.append('id', id_banco);
    if(id_banco>0){
        try{
            const url = `${base_url}/BanCuentas/val_cod_banco`;
            const repuesta = await fetch(url, {
                method: 'POST',
                body: datos,
            });
            const resultado = await repuesta.json();
            if(resultado){
                xBanco = resultado[0]['cod_banco'];
                $("#cuenta_bancue").val(xBanco+'-'+xSucur+'-'+xContr+'-'+xBancu);
            }
        }catch(error){
            console.log(error);
        }
    }
})
//Continuar con el valor de la sucursal de la cuenta a medida que se vaya cargando la informacion
$("#suc_bancue").on('keyup', function(){
    xSucur =$(this).val();
    $("#cuenta_bancue").val(xBanco+'-'+xSucur+'-'+xContr+'-'+xBancu);
})
//Continuar con el valor del numero de control de la cuenta a medida que se vaya cargando la informacion
$("#con_bancue").on('keyup', function(){
    xContr = $(this).val();
    $("#cuenta_bancue").val(xBanco+'-'+xSucur+'-'+xContr+'-'+xBancu);
})
//Continuar con el valor del numero de cuenta corta de la cuenta a medida que se vaya cargando la informacion
$("#cue_bancue").on('keyup change', function(){
    xBancu = $(this).val();
    $("#cuenta_bancue").val(xBanco+'-'+xSucur+'-'+xContr+'-'+xBancu);
})
//Funcion Boton Eliminar
function eliminarBtn(element) {
    let id = element.dataset.id;
    let name = element.dataset.name;
    let code = element.dataset.code;
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
            borrar(id, code);
        };
    });
}
//Funcion para borrar
async function borrar(id, code){
    const datos = new FormData();
    datos.append('id', id);
    datos.append('cue_bancue', code);
    try{
        const url =  `${base_url}/BanCuentas/destroy`;
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
                window.location.href = `${base_url}/BanCuentas`;
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
$(document).on('change', '#id_cta', async function(){
    if(!id_cta){
        id_cta = $('#id_cta').val();
    }
    const datos = new FormData();
    datos.append("id_cue", id_cta);
    try{
        if(id_cta>0){
            const url = `${base_url}/BanCuentas/datosCue`;
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos,
            });
            const resultado = await respuesta.json();
            if(resultado && resultado['aux_cta'] == "S"){
               listar_aux_ctbles(id_aux, "id_aux", true);
            }else{
                listar_aux_ctbles(id_aux, "id_aux", false);
            }
        }else{
            if(id_aux){
                listar_aux_ctbles(id_aux, "id_aux", false);
            }
            listar_aux_ctbles('', "id_aux", false);
        }
    }catch(error){
        console.log(error);
    }
})