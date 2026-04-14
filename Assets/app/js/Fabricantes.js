//Validación de campos en linea
const nom_fabField = document.querySelector("[name=nom_fab]");
const status_auxField = document.querySelector("[name=status]");

nom_fabField?.addEventListener("blur", (e) => validateEmptyField("Nombre es requerido", e));
status_auxField?.addEventListener("blur", (e) => validateEmptyField("Status es requerido", e));


$(document).ready(function(){
    id = $("#id").val();
    if(id){
        showrowfab(id);
    }else{
        listar_status(2);
    }
});

$(document).ready(function() {
    $("#nom_fab").on("keyup", function(e) {
        var cod_cod_preaux = $("#nom_fab").val();
        var dataString = 'nom_fab=' + nom_fab;
        let url =  `${base_url}/Fabricantes/validar`;
        $.ajax({
            url: url,
            type: "POST",
            data: dataString,
            dataType: "JSON",
            success: function(datos){
                if(datos.success == 1){
                    validateExists(datos.message, e);
                }else{
                    validateExists(datos.message, e);
                }
            }
        });
    });
});
function eliminarBtn(element) {
    let idm = element.dataset.id;
    let namem = element.dataset.name;
    Swal.fire({
        title: "¿Está seguro de querer eliminar el registro "+namem+"?",
        text: "No podrás revertir esto.!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            borrar(idm, namem);
        }
    });
}
//Borrar registro
async function borrar(idm, namem){
    const datos = new FormData();
    datos.append('id', idm);
    datos.append('name', namem);
    try{
        let url = `${base_url}/Fabricantes/destroy`;
        console.log(url);
        const repuesta = await fetch(url, {
        method: 'POST',
        body: datos,
    });
    const resultado = await repuesta.json() ;
    if(resultado){
        Swal.fire({
            title: `${resultado.title}`,
            text: `${resultado.msg}`,
            icon: `${resultado.icon}`,
            showConfirmButton: true,
        });
        window.location.href = `${base_url}/Fabricantes`;
    }
    }catch(error){
        Swal.fire({
            title: 'Oops',
            text: 'No se puede eliminar el registro ' + idm + ' con la descripción ' + namem + ' motivado a que se encuentra asociado a otro registro',
            icon: 'error',
        });
    }
}
//Mostrar datos del fabricante
async function showrowfab(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url = `${base_url}/Fabricantes/showrowfab`;
        const repuesta = await fetch(url, {
            method: 'POST',
            body: datos,
        });
        const resultado = await repuesta.json();
        if(resultado){
            $("#nom_fab").val(resultado[0]['nom_fab']);
            $("#observa").val(resultado[0]['observa']);
            if(resultado[0]['adicional01'] == 1){
                $('#adicional01').prop('checked', true);
            }
            status = resultado[0]['status'];
            listar_status(status);
        }
    }catch(err){
        console.log(err);
    }
}