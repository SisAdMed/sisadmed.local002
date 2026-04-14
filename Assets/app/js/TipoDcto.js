//Obtener valores
id = '';
//Validar campos
$(function(){
    $("form[name='my_form']").validate({
        rules:{
            codigo_tipdes: "required",
            valor_tipdes: "required",
            status: "required"
        },
        messages:{
            codigo_tipdes: "Debe especificar una valor de código",
            valor_tipdes: "Debe especificar un valor de descuento",
            status: "Debe especificar un Status"
        },
    })
})
//Asignar valores all iniciar
$(document).ready(function(){
    id = $("#id").val();
    if(id){
        show_row(id);
    }else{
        listar_status(1);
    }
})
async function show_row(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url = `${base_url}/TipoDcto/show_row`;
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos,
        });
        const resultado = await respuesta.json();
        if(resultado){
            $("#codigo_tipdes").val(resultado['codigo_tipdes'])
            $("#codigo_tipdes ").attr("readonly", true);
            $("#valor_tipdes").val(financial(resultado['valor_tipdes'],2));
            $("#valor_tipdes ").attr("readonly", true);
            if(resultado['appreq'] == 1){
                $('#appreq').prop('checked', true);
            }
            status = resultado['status']
            listar_status(status);
        }
    }catch(error){
        console.log(error);
    }
}
function eliminarBtn(element) {
    let idr = element.dataset.id;
    let name = element.dataset.name;
    let codigo = element.dataset.code;
    const datos = new FormData();
    datos.append('id', idr);
    datos.append('descrip', codigo);
    datos.append('codigo', codigo);
    Swal.fire({
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
            borrar(datos);
        };
    });
}
async function borrar(datos){
    let url =  `${base_url}/TipoDcto/destroy`;
    let repuesta = await fetch(url, {
        method:"POST",
        body: datos,
    });
    const resultado = await repuesta.json();
    if (resultado){
        Swal.fire({
        icon: 'success',
        title: 'Registro eliminado satisfactoriamente',
        showConfirmButton: true,
        });
        window.location.href = `${base_url}/TipoDcto`;
    }
}
