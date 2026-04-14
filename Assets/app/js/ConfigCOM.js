//Validar campos
let item;
$().ready(function(){
    $.validator.setDefaults({
        ignore:[],
    });
    $("form[name='my_form']").validate({
        rules: {
            id_emp: "required",
            con_purcon: "required",
            tdoc_pur: "required",
            tdoc_purord: "required",
            tdoc_purcrenot: "required",
            tdoc_purdelnot: "required",
            tdoc_purretnot: "required",
            
            id_typmovinwar: "required",
            id_typmovoutwar: "required",
            id_alm: "required",
            id_ubi: "required",
            status: "required",
        },
        messages: {
            id_emp: "Debe especificar una Empresa",
            con_purcon: "Debe especificar un Concepto de Comrpas",
            tdoc_pur: "Debe especificar Tipo de Documento de Facturas",
            tdoc_purord: "Debe especificar un Tipo de Documento de Ordenes de Compras",
            tdoc_purcrenot: "Debe especificar un Tipo de Documento de Notas de Crédito",
            tdoc_purdelnot: "Debe especificar un Tipo de Documento Notas de Entregas",
            tdoc_purretnot: "Debe especificar un Tipo de Documento Notas de Devolución",
            
            id_typmovinwar: "Debe especificar un Tipo de Movimiento Entrada al Almacén",
            id_typmovoutwar: "Tipo de Movimiento Salida del Almacén",
            id_alm: "Debe especificar una Almacén",
            id_ubi: "Debe especificar una Ubicación",
            status: "Se requiere un status",
        },
        submitHandler: function(form) {
            form.submit();
        }
    })
})
//Validar formulario dependiendo de lo solicitado nuevo/editar
$(document).ready(function(){
    id = $("#id").val();
    if(id){
        dat_form(id);
    }else{
        listar_empresas(0);
        listar_conceptos_CXP(0, 'con_purcon');
        listar_status(1);
    }
})
//Cargar formulario con los datos
async function dat_form(id){
    const data_form = await dat_global(id);
    id_emp = data_form['id_emp'];
    listar_empresas(id_emp, true);
    con_purcon = data_form['con_purcon'];
    listar_conceptos_CXP(con_purcon, 'con_purcon');
    listar_status(data_form['status']);
    $("#con_purcon").val(data_form['con_purcon']);

   //Tipo de Documento Factura "M"
    tdoc_pur = data_form['tdoc_pur'];
    listar_tipos_documentos_CXP(id_emp, 'M', tdoc_pur, true, 'tdoc_pur');

    //Tipo de Documento Nota de Crédito "A"
    tdoc_purcrenot = data_form['tdoc_purcrenot'];
    listar_tipos_documentos_CXP(id_emp, 'A', tdoc_purcrenot, false, 'tdoc_purcrenot');

    //Tipo de Documento Orden de Compra "O"
    tdoc_purord = data_form['tdoc_purord'];
    listar_tipos_documentos_CXP(id_emp, 'O', tdoc_purord, true, 'tdoc_purord');

    //Tipo de Documento Nota de Entrega "T"
    tdoc_purdelnot = data_form['tdoc_purdelnot'];
    listar_tipos_documentos_CXP(id_emp, 'T', tdoc_purdelnot, true, 'tdoc_purdelnot');

    //Tipo de Documento Devolución "V"
    tdoc_purretnot = data_form['tdoc_purretnot'];
    listar_tipos_documentos_CXP(id_emp, 'V', tdoc_purretnot, true, 'tdoc_purretnot');

    //Tipo de Movimientos para Entradas
    tmov_pur = data_form['id_typmovinwar'];
    listar_InvTipoMov(id_emp, tmov_pur, 'tmov_pur', 'E');

    //Tipo de Movimientos para Salidas
    tmov_pur_sal = data_form['id_typmovoutwar'];
    listar_InvTipoMov(id_emp, tmov_pur_sal, 'tmov_pur_sal', 'S');

    //Almacen
    id_alm = data_form['id_alm'];
    listar_almacenes(id_emp, id_alm);

    //Ubicación por default
    id_ubi = data_form['id_ubi'];
    $("#id_ubi").val(id_ubi);
    $("#id_ubi").trigger("change");

}
//Seleccionar registro
var dat_global = async function (rid){
    if(rid>0){
        const datos = new FormData();
        datos.append('id', rid);
        try{
            const url = `${base_url}/ConfigCOM/show_config_fac`; 
            const resp = await fetch( url, {
                method: 'POST',
                body: datos,
            });
            var result = await resp.json();
            return new Promise((resolve, reject) => {
                setTimeout(() => {
                    resolve(result);
                }, 100);
            }) ;
        }catch(err){
            console.log(err);
        }
    }
}
//listar conceptos dependiendo de la empresa
$("#id_emp").on('change', function(){
    id_emp = $(this).val();
    if(id_emp){
        listar_conceptos_CXP(id_emp, 0, 'con_purcon'); 
        //Tipo de Documento Factura "M"
        listar_tipos_documentos_CXP(id_emp, 'M', '', true, 'tdoc_pur');
        //Tipo de Documento Nota de Crédito "A"
        listar_tipos_documentos_CXP(id_emp, 'A', '', true, 'tdoc_purcrenot');
        //Tipo de Documento Orden de Compra "O"
        listar_tipos_documentos_CXP(id_emp, 'O', '', true, 'tdoc_purord');
        //Tipo de Documento Nota de Entrega "T"
        listar_tipos_documentos_CXP(id_emp, 'T', '', true, 'tdoc_purdelnot');
        //Tipo de Documento Devolución "V"
        listar_tipos_documentos_CXP(id_emp, 'V', '', true, 'tdoc_purretnot');
        //Tipo de Movimientos de Entrada
        listar_InvTipoMov(id_emp, 0, 'tmov_pur', 'E');
        //Tipo de Movimientos de Salida
        listar_InvTipoMov(id_emp, 0, 'tmov_pur_sal', 'S');
        //Listar Almacenes
         listar_almacenes(id_emp);
    }
})
//Seleccionar monedas segun el cliente
$(document).on('change', '#fecha_comp', async function(e) {
    e.preventDefault();
    fecha_comp = $("#fecha_comp").val();
    id_moneda = $("#id_moneda").val();
    xTasaCambio = await xTasa(fecha_comp, id_moneda);
    if(xTasaCambio){
        $("#tasa_cambio").val(xTasaCambio);
    }
});