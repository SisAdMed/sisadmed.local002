//Validar campos
$().ready(function(){
    $("form[name='my_form']").validate({
        rules:{
            id_emp: "required",
            id_alm: "required",
            id_ubi: "required",
            id_mov_ent: "required",
            id_mov_sal: "required",
            id_mov_tra_ent: "required",
            id_mov_tra_sal: "required",
            status: "required"
        },
        messages:{
            id_emp: "Debe especificar una Empresa",
            id_alm: "Debe especificar un Almacén",
            id_ubi: "Debe especificar una Ubicación",
            id_mov_ent: "Debe especificar un Tipo de Movimiento de Entreda",
            id_mov_sal: "Debe especificar un Tipo de Movimiento de Salida",
            id_mov_tra_ent: "Debe especificar un Tipo de Movimiento de Transferencia de Entrada",
            id_mov_tra_sal: "Debe especificar un Tipo de Movimiento de Transferencia de Salida",
            status: "Debe especifuir un Estatus"
        }
    });
}) // Fin Validación de campos
//Validar formulario dependiendo de lo solicitado nuevo/editar
$(document).ready(function(){
    id = $("#id").val();
    if(id){
        //If existe el registro solicitado, mostrar la inforamción del mismo
        dat_form(id);
    }else{
        //Llenar los respectivos selects con los datos respectivos
        listar_empresas(0);
        listar_almacenes();
        listar_status(1);
    }
}) // Fin Validar formulario dependiendo de lo solicitado nuevo/editar
//Llenar los diferentes combos al seleccionar una Empresa
$(document).on('change', '#id_emp', function(){
    id_emp = $("#id_emp").val();
    //Vaciar selects 
    $("#id_alm").empty();
    $("#id_ubi").empty();
    $("#id_mov_ent").empty();
    $("#id_mov_sal").empty();
    $("#id_mov_tra_ent").empty();
    $("#id_mov_tra_sal").empty();
    if(id_emp){
        //Listar Almacenes
        listar_almacenes(id_emp);
        //Listar Ubicaciones
        listar_ubicaciones('', '', 'N', id_emp);
        //Listar Tipos de Movimientos de Entrada
        listar_InvTipoMov(id_emp, 0, 'id_mov_ent', 'E');
        //Listar Tipos de Movimientos de  Salida
        listar_InvTipoMov(id_emp, 0, 'id_mov_sal', 'S');
        //Listar Tipos de Movimientos de  Transferencia de Entrada
        listar_InvTipoMov(id_emp, 0, 'id_mov_tra_ent', 'T');
        //Listar Tipos de Movimientos de  Transferencia de Salida
        listar_InvTipoMov(id_emp, 0, 'id_mov_tra_sal', 'T');
    }

})// Fin Llenar los diferentes combos al seleccionar una Empresa