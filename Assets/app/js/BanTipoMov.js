//Validar campos de formulario
$().ready(function(){
    $("form[name='my_form']").validate({
        rules:{
            cod_bantmo: {
                required: true,
                minlength: 2,
                maxlength: 2,
            },
             nom_bantmo: {
                required: true,
                minlength: 4,
                maxlength: 100,
            },
            acc_bantmo: "required",
            idb_bantmo: "required",
            con_bantmo: "required",
            cash_bantmo: "required",
            che_bantmo: "required",
            tra_bantmo: "required",
            status: "required",
        },
        messages:{
            cod_bantmo: {
                required: "Debe especificar un Código de Tipo de Movimiento",
                minlength: "El código debe contener al menos 2 carácteres",
                maxlength: "El código de contener máximo 2 carácteres",
            },
            nom_bantmo: {
                required: "Debe especificar un Nombre de Tipo de Movimiento",
                minlength: "El nombre debe contener al menos 4 carácteres",
                maxlength: "El nombre debe contener máximo 100 carácteres",
            },
            acc_bantmo: "Debe especificar una acción",
            idb_bantmo: "Debe especificar si genera el IGTF",
            con_bantmo: "Debe especificar si usa consecutivo",
            cash_bantmo: "Debe especificar si es Efectivo o no",
            che_bantmo: "Debe especificar si es Cheque o no",
            tra_bantmo: "Deve especificar si es Transferencia o no",
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
        listar_accion();
        //Genera IGTF
        listar_agrupador('', 'idb_bantmo');
        //Consecutivo
        listar_agrupador('N', 'con_bantmo');
        //Efectivo
        listar_agrupador('N', 'cash_bantmo');
        //Cheque
        listar_agrupador('N', 'che_bantmo');
        //Transferencia
        listar_agrupador('S', 'tra_bantmo');
        listar_efecto('');
    }
});
//Mostrar valores del registro
async function showrow(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url  = `${base_url}/BanTipoMov/showrow`;
        console.log(url);
        const repuesta = await fetch(url, {
            method: 'POST',
            body: datos,
        })
        const resultado = await repuesta.json();
        if(resultado){
            id_emp = resultado[0]['id_emp'];
            $("#cod_bantmo").val(resultado[0]['cod_bantmo']);
            $('#cod_bantmo').prop('readonly', true);
            $("#nom_bantmo").val(resultado[0]['nom_bantmo']);
            $("#nom_bantmo").val(resultado[0]['nom_bantmo']);
            acc_bantmo = resultado[0]['acc_bantmo'];
            listar_accion(acc_bantmo);
            idb_bantmo = resultado[0]['idb_bantmo'];
            listar_agrupador(idb_bantmo, 'idb_bantmo');
            con_bantmo = resultado[0]['con_bantmo'];
            listar_agrupador(con_bantmo, 'con_bantmo');
            cash_bantmo = resultado[0]['cash_bantmo'];
            listar_agrupador(cash_bantmo, 'cash_bantmo')
            che_bantmo = resultado[0]['che_bantmo'];
            listar_agrupador(che_bantmo, 'che_bantmo')
            tra_bantmo = resultado[0]['tra_bantmo'];
            listar_agrupador(tra_bantmo, 'tra_bantmo')
            efe_bantmo = resultado[0]['efe_bantmo'];
            listar_efecto(efe_bantmo);
            id_cxtmo = resultado[0]['id_cxtmo'];
            if(efe_bantmo == 'C'){
                listar_tipos_mov_CXC(id_cxtmo, 'id_cxtmo', '', 'D');
            }else if(efe_bantmo == 'P'){
                listar_tipos_mov_CXP(id_emp, id_cxtmo, 'id_cxtmo', '', 'D');

            }
            $("#id_cxtmo").val(id_cxtmo);
            status = resultado[0]['status'];
            listar_status(status);
        }
    }catch(error){
        console.log(error);
    }
}
//Listar Tipos de Movimientos, dependiendo del Efecto si es en CXC o CXP
$(document).on('change', '#efe_bantmo', function(e){
    e.preventDefault();
    efe_bantmo = $(this).val();
    //Limpiar efecto
    $("#id_cxtmo").empty();
    if(efe_bantmo == 'C'){
        //Cuentas por Cobrar
        listar_tipos_mov_CXC(0, 'id_cxtmo', '', 'D');
        //Cuentas por Pagar
    }else if(efe_bantmo == 'P'){
        listar_tipos_mov_CXP(0, 'id_cxtmo', '', 'D');
    }
})
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
        const url =  `${base_url}/BanTipoMov/destroy`;
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
                window.location.href = `${base_url}/BanTipoMov`;
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
