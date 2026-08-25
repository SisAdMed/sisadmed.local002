//Validar campos
$(document).ready(function () {
    loader.show();
    $("#my_form").validate({
        ignore: [],
        rules:{
            id_emp: "required",
            show_doc: "required",
            over_charges: "required",
            fir_due_date : "required",
            sec_due_date: "required",
            thi_due_date: "required",
            fou_due_date: "required",
            cant_dec: "required",
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
            cant_dec: "Debe indicar los decimales a trabajar en los documentos",
            status: "Debe indicar un Status",
        },
    })
    //Al ingresar a la apliacion
    /**Cargar el Index */    
    form = $("form").attr('id');
    if(form === undefined){
        initConfigCXCTable();
    }else{
        id = $('#id').val();      
        if(id){
            dat_form(id);
        }else{
            dat_form_new();
            $('form:first *:input[type!=hidden]:visible:first').focus();        
        }
    }
    loader.hide();
})
/**Nuevo registro */
function dat_form_new(){
    listar_empresas(0);
    listar_agrupador(0, "show_doc");
    listar_agrupador(0, "over_charges");
    listar_status(1);
}
/**Consultar registro */
function dat_form(id){    
    const url = `${base_url}/ConfigCXC/show_row`;
    //Mostrar datos
    $.ajax({
        url: url,
        method: 'POST',
        dataSrc: '',
        data: {id: id},
        dataType: 'json',
        beforeSend: function(){
            loader.show();
        },
        complete: function(){
            loader.hide();
        },
        error: function(PDOException){
            loader.hide();
            console.log('Ha ocurrido el siguiente error:', PDOException.responseText);
        },
        success: function(data){            
            id_emp = data.id_emp;
            listar_empresas(id_emp, true);
            show_doc = data.show_doc;
            listar_agrupador(show_doc, "show_doc");
            over_charges = data.over_charges;
            listar_agrupador(over_charges, "over_charges");                      
            $("#fir_due_date").val(data.fir_due_date);
            $("#sec_due_date").val(data.sec_due_date);
            $("#thi_due_date").val(data.thi_due_date);
            $("#fou_due_date").val(data.fou_due_date);
            $("#cant_dec").val(data.cant_dec);
            status = data.status;  
            listar_status(status);
        }
    });
}
/**Guardar y/o Actualizar */
$("#my_form").on("submit", function (e) {
    e.preventDefault();
    var boton = $("#btnok");
    boton.prop('disabled', true);
    if ($(this).valid()) {
        var formData = new FormData(this);
        const url = `${base_url}/ConfigCXC/store`;
        $.ajax({
            type: 'POST',
            url: url,
            dataSrc: '',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function () {
                $(".loader").show();
            },
            complete: function () {
                boton.prop('disabled', false);
                $(".loader").hide();
            },
            error: function (PDOException) {
                $(".loader").hide();
                console.log(PDOException);
            },
            success: function (data) {
                Swal.fire({
                    title: data.title,
                    text: data.msg,
                    icon: data.icon,
                }).then((result) => {
                    if (data.icon != 'error') {
                        window.location.href = `${base_url}/ConfigCXC`;
                    }
                });
            }
        });
    } else {
        boton.prop('disabled', false);
        return false
    }
});