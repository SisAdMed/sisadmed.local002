//Declaración de variables
origen_COM = 1;
//Validación de campos
$().ready(function(){
    $('#my_form').validate({
        rules: {},
        mesagges: {}
    });
})
//Al ingresar a la aplicacion
$().ready(function(e){
    id = $('#id').val();
    if(id){
    }else{
        listar_empresas();
    }
})
//Cargar pantalla del index
$().ready(function(e){
    //load_screan_main()
});
$(document).on('change', '#id_cli', async function(event) {
    event.preventDefault();
    id_cli = $(this).val();
    const datosFetched = await tid_vend(id_cli);
    nom_cli = datosFetched['nom_ent'];
    $("#nom_cli").val(nom_cli);
});


