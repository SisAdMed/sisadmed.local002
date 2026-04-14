//Variables
$(document).ready(function(){
});
//Borrar - Se cambia es el status a inactivo para no perder el consecutivo
function eliminarBtn(element) {
    let id = element.dataset.id;
    let name = element.dataset.name;
    let codigo = element.dataset.code;
    const datos = new FormData();
    datos.append('id', id);
    datos.append('name', name);
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
    let url =  `${base_url}/TipoComprobante/destroy`;
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
        window.location.href = `${base_url}/TipoComprobante`;
    }
}