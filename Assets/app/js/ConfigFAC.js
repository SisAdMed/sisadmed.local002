//Validar campos 
$().ready(function(){
    $.validator.setDefaults({
        ignore:[],
    });
    $("form[name='my_form']").validate({
        rules: {
            id_emp: "required",
            id_con_sales: "required",
            status: "required",
            id_tdoc_fac: "required",
            id_tdoc_cre: "required",
            id_tdoc_pre: "required",
            id_tdoc_not: "required",
            id_tdoc_dev: "required",
        },
        messages: {
            id_emp: "Debe especificar una Empresa",
            id_con_sales: "Debe especificar un concepto para las Ventas",
            status: "Se requiere un status",
            id_tdoc_fac: "Debe especificar un Tipo de Documento Factura",
            id_tdoc_cre: "Debe especificar un Tipo de Documento Crédito",
            id_tdoc_pre: "Debe especificar un Tipo de Documento Presupuesto",
            id_tdoc_not: "Debe especificar un Tipo de Documento para Nota de Entrega",
            id_tdoc_dev: "Debe especificar un Tipo de Documento para Nota de DEvolución",
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
        listar_status(1);
    }
})
//Cargar formulario con los datos
async function dat_form(){
    id = $('#id').val();
    const data_form = await dat_global(id);
    id_emp = data_form['id_emp'];
    listar_empresas(id_emp, true);
    id_con_sales = data_form['id_con_sales'];
    listar_conceptos_CXC(id_emp, id_con_sales, 'id_con_sales');
    listar_status(data_form['status']);
    $("#id_con_sales").val(data_form['id_con_sales']);
   //Tipo de Documento Factura "F"
    id_tdoc_fac = data_form['id_tdoc_fac'];
    listar_tipos_documentos(id_emp, 'F', id_tdoc_fac, true, 'id_tdoc_fac');
    //Tipo de Documento Nota de Crédito "C"
    id_tdoc_cre = data_form['id_tdoc_cre'];
    listar_tipos_documentos(id_emp, 'C', id_tdoc_cre, true, 'id_tdoc_cre');
    //Tipo de Documento Presupuesto "P"
    id_tdoc_pre = data_form['id_tdoc_pre'];
    listar_tipos_documentos(id_emp, 'P', id_tdoc_pre, true, 'id_tdoc_pre');
    //Tipo de Documento Nota de Entrega "N"
    id_tdoc_not = data_form['id_tdoc_not'];
    listar_tipos_documentos(id_emp, 'N', id_tdoc_not, true, 'id_tdoc_not');
    //Tipo de Documento Nota de Entrega no Fiscal "Z"
    id_tdoc_not_no_fis = data_form['id_tdoc_not_no_fis'];
    listar_tipos_documentos(id_emp, 'Z', id_tdoc_not_no_fis, true, 'id_tdoc_not_no_fis'); 
    //Tipo de Documento Devolución "D"
    id_tdoc_not = data_form['id_tdoc_dev'];
    listar_tipos_documentos(id_emp, 'D', id_tdoc_not, true, 'id_tdoc_dev');
    //Llenar campos de notas
    $("#note_fac").val(data_form['note_fac']);
    $("#note_cre").val(data_form['note_cre']);
    $("#note_pre").val(data_form['note_pre']);
    $("#note_not").val(data_form['note_not']);
    $("#note_not_no_fis").val(data_form['note_not_no_fis']);
    $("#note_dev").val(data_form['note_dev']);
    //Tipo de Movimientos para Ventas
    tmov_fac = data_form['tmov_fac'];
    listar_InvTipoMov(id_emp, tmov_fac, 'tmov_fac', 'S');
    //Tipo de Movimientos para Notas de Creditos
    tmov_noc = data_form['tmov_noc'];
    listar_InvTipoMov(id_emp, tmov_noc, 'tmov_noc', 'E');
    //Almacen
    id_alm = data_form['id_alm'];
    listar_almacenes(id_emp, id_alm, 0);
    //Ubicacion por default
    id_ubi = data_form['id_ubi'];
    listar_ubicaciones('', id_ubi, 'N', id_emp);
    //Facturar con stock
    fac_stock = data_form['fac_stock'];
    $('#fac_stock').prop('checked', false);
    if(fac_stock == 1){
        $('#fac_stock').prop('checked', true);
    }
    //Cotizar con stock
    cot_stock = data_form['cot_stock'];
    $('#cot_stock').prop('checked', false);
    if(cot_stock == 1){
        $('#cot_stock').prop('checked', true);
    }
    //Bloquear precios en la cotización
    loc_pri_cot = data_form['loc_pri_cot'];
    $('#loc_pri_cot').prop('checked', false);
    if(loc_pri_cot == 1){
        $('#loc_pri_cot').prop('checked', true);
    }
    //Cargar Bloqueo de Facturación y Cotización
    locked_invoice = data_form['locked_invoice'];
    $('#locked_invoice').prop('checked', false);
    if(locked_invoice == 1){
        $('#locked_invoice').prop('checked', true);
    }
    //Bloquear precios en la Facturacion, Notas de Entrega, etc.
    loc_pri_inv = data_form['loc_pri_inv'];
    $('#loc_pri_inv').prop('checked', false);
    if(loc_pri_inv == 1){
        $('#loc_pri_inv').prop('checked', true);
    }
}
//Seleccionar registro
var dat_global = async function (rid){
    if(rid>0){
        const datos = new FormData(); 
        datos.append('id', rid);
        try{
            const url = `${base_url}/ConfigFAC/show_config_fac`;
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
//listar dependiendo de la empresa
$("#id_emp").on('change', function(){
    id_emp = $("#id_emp").val();
    if(id_emp){
        listar_conceptos_CXC(id_emp, 0, 'id_con_sales');
        //Tipo de Documento Factura "F"
        listar_tipos_documentos(id_emp, 'F', '', true, 'id_tdoc_fac');
        //Tipo de Documento Nota de Crédito "C"
        listar_tipos_documentos(id_emp, 'C', '', true, 'id_tdoc_cre');
        //Tipo de Documento Presupuesto "P"
        listar_tipos_documentos(id_emp, 'P', '', true, 'id_tdoc_pre');
        //Tipo de Documento Nota de Entrega "N"
        listar_tipos_documentos(id_emp, 'N', '', true, 'id_tdoc_not');
        //Tipo de Documento Nota de Entrega no Fiscal "N"
        listar_tipos_documentos(id_emp, 'Z', '', true, 'id_tdoc_not_no_fis');
        //Tipo de Documento Devolución "D"
        listar_tipos_documentos(id_emp, 'D', '', true, 'id_tdoc_dev');
        //Tipo de Movimientos de Salida
        listar_InvTipoMov(id_emp, 0, 'tmov_fac', 'S');
        //Tipo de Movimientos de Entrada
        listar_InvTipoMov(id_emp, 0, 'tmov_noc', 'E');
        //Listar Almacenes
        listar_almacenes(id_emp);
        //Listar Ubicaciones
        listar_ubicaciones('', 0, 'N', id_emp);
    }
})