//Validar campos
$(function(){
    $("form[name='my_form']").validate({
        rules:{
            id_emp: "required",
            show_doc: "required",
            over_charges: "required",
            fir_due_date : "required",
            sec_due_date: "required",
            thi_due_date: "required",
            fou_due_date: "required",
            status: "required",
        },
        messages:{
            id_emp: "Debe especificar una empresa",
            show_doc: "Debe indicar si se mostraran todos los documentos",
            over_charges: "Debe indicar si permite cobros en exceso",
            fir_due_date : "Debe indicar una cantidad de dias",
            sec_due_date: "Debe indicar una cantidad de dias",
            thi_due_date: "Debe indicar una cantidad de dias",
            fou_due_date: "Debe indicar una cantidad de dias",
            status: "Debe indicar un Status",
        },
    })
})
//Al ingresar a la apliacion
$(document).ready(function(){
    id = $('#id').val();
    if(id){
        dat_form();
    }else{
        listar_empresas(0);
        listar_agrupador(0, "show_doc");
        listar_agrupador(0, "over_charges");
        listar_status();
    }
})
//Cargar formulario con los datos
async function dat_form(){
    id = $('#id').val();
    const data_form = await dat_global(id);
    id_emp = data_form['id_emp'];
    listar_empresas(id_emp);
    listar_agrupador(data_form['show_doc'], "show_doc");
    listar_agrupador(data_form['over_charges'], "over_charges");
    $("#fir_due_date").val(data_form['fir_due_date']);
    $("#sec_due_date").val(data_form['sec_due_date']);
    $("#thi_due_date").val(data_form['thi_due_date']);
    $("#fou_due_date").val(data_form['fou_due_date']);
    //Bloquear campos
    $('#id_emp').attr("disabled", true);
    listar_status(data_form['status']);
}
//Seleccionar registro
var dat_global = async function (rid){
    if(rid>0){
        const datos = new FormData();
        datos.append('id', rid);
        try{
            const url = `${base_url}/ConfigCXC/showrow`;
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