//Variables
let item;
//Validar campos del formulario
$().ready(function(){
    $("form[name='my_form']").validate({
        rules:{
            id_emp: "required",
            cod_bancon: {
                required: true,
                minlength: 2,
                maxlength: 20
            },
            nom_bancon:{
                required: true,
                minlength: 4,
                maxlength: 100
            },
            agr_bancon: "required",
            /*nom_ctb: "required",*/
            status: "required"
        },
        messages:{
            id_emp: "Debe especificar una empresa",
            cod_bancon:{
                required: "Debe espicicar un Código de concepto",
                minlength: "Debe especificar al menos 2 carácteres",
                maxlength: "Debe especificar máximo 20 carácteres"
            },
            nom_bancon:{
                required: "Debe especificar una Descripción para el concepto",
                minlength: "Debe especificar al menos 4 carácteres",
                maxlength: "Debe especificar maxímo 100 carácteres"
            },
            agr_bancon: "Debe especificar si el concepto Agrupa o no",
           /* nom_ctb: {
                required: function(e){
                    if($("#agr_bancon").val() != "S"){
                        return "Debe especificar una cuenta contable";
                    }
                }
            },*/
            status: "Debe especificar un status"
        }

    })
})
//Validar dependinedo del tipo de transacción nuevo y/o editar
$(document).ready(function(){
    id = $("#id").val()
    if(id){
        showrow(id);
    }else{
        listar_empresas();
        //Agrupador
        listar_agrupador('', 'agr_bancon');
        //Afecta documento
        listar_agrupador('', 'id_bantdo');
        listar_retislr('', 'id_retislr')
        listar_status(1);
    }
})
//Validar si usa o no Auxiliars la cuenta contable
/*
$(document).on('change', '#id_ctb', async function(){
    id_ctb = $(this).val();
    const datos = new FormData();
    datos.append("id_ctb", id_ctb);
    try{
        if(id_ctb>0){
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
    */
$(document).on('change', '#agr_bancon', function(){
    agrupa = $(this).val();
    if(agrupa == 'S'){
        $('#id_bantdo').prop('disabled', true);
        $('#id_ctb').prop('disabled', true);
        $('#id_aux').prop('disabled', true);
    }else{
        $('#id_bantdo').prop('disabled', false);
        $('#id_ctb').prop('disabled', false);
        $('#id_aux').prop('disabled', false);
    }
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
            borrar(id, name);
        };
    });
}
//Funcion para borrar
async function borrar(id, name){
    const datos = new FormData();
    datos.append('id', id);
    datos.append('nom_bancon', name);
    try{
        const url =  `${base_url}/BanConceptos/destroy`;
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
                window.location.href = `${base_url}/BanConceptos`;
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
//Mostrar los datos para modificación
async function showrow(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url = `${base_url}/BanConceptos/showrow`;
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos,
        });
        const resultado = await respuesta.json();
        if(resultado){
            id_emp = resultado[0]['id_emp'];
            listar_empresas(id_emp);
            $("#cod_bancon").val(resultado[0]['cod_bancon']);
            $("#nom_bancon").val(resultado[0]['nom_bancon']); 
            //Agrupador
            listar_agrupador(resultado[0]['agr_bancon'], 'agr_bancon');
            $("#agr_bancon").trigger('change');
            //Afecta documento
            listar_agrupador(resultado[0]['id_bantdo'], 'id_bantdo');
            id_ctb = resultado[0]['id_ctb'];
            nom_ctb = resultado[0]['nombre_cta'];
            $("#id_ctb").val(id_ctb);
            $("#nom_ctb").val(nom_ctb);
            id_aux = resultado[0]['id_aux'];
            nom_aux = resultado[0]['nombre_aux'];
            $("#id_aux").val(id_aux);
            $("#nom_aux").val(nom_aux);
            status = resultado[0]['status'];
            id_retislr = resultado[0]['id_retislr'];
            listar_retislr(id_retislr, 'id_retislr');
            listar_status(status);
        }
    }catch(error){
        console.log(error);
    }
}