//Validación de campos en linea
const cod_preField = document.querySelector("[name=cod_pre]");
const nom_preField = document.querySelector("[name=nom_pre]");
const status_auxField = document.querySelector("[name=status]");

cod_preField?.addEventListener("blur", (e) => validateEmptyField("Código es requerido", e));
nom_preField?.addEventListener("blur", (e) => validateEmptyField("Nombre es requerido", e));
status_auxField?.addEventListener("blur", (e) => validateEmptyField("Status es requerido", e));


$(document).ready(function() { 
    $("#cod_pre").on("keyup", function(e) {
        var cod_cod_preaux = $("#cod_pre").val();
        var dataString = 'cod_pre=' + cod_pre;
        let url =  `${base_url}/Presentaciones/validar`; 
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
        })
    });
});
function eliminarBtn(element) {
    let idm = element.dataset.id;
    let namem = element.dataset.name;    
    let codm = element.dataset.code;        
    var n = new Noty({
        text: 'Está seguro de eliminar este registro?',
        type:"error",
        theme: "metroui",
        buttons: [
          Noty.button('Si', 
            'btn btn-success', 
            async function () {                 
             const datos = new FormData();
             datos.append('id', idm);
             datos.append('name', namem);    
             datos.append('cod', codm);                
             try {                                
                let url =  `${base_url}/Presentaciones/destroy`;                     
                let repuesta = await fetch(url, {
                    method:"POST",
                    body: datos,
                });                                      
                const resultado = await repuesta.json();                      
                new Noty({
                    type: `${resultado.type}`,
                    text: `${resultado.msg}`,
                    layout: "topRight",
                    theme: "metroui",
                    timeout: 2500,
                }).show();
                setTimeout(()=>{
                 window.location.href = `${base_url}/Presentaciones`;
             }, 2500)       
            } catch (error) {                
                new Noty({
                    type: "warning",
                    text: 'No se puede elimiar el registro motivado a que tienes registros hijos y/o posee movimientos',
                    layout: "topRight",
                    theme: "metroui",
                    timeout: 2500,
                }).show();
                setTimeout(()=>{
                 window.location.href = `${base_url}/Presentaciones`;
             }, 2500)   
            }
            n.close();
        }, 
        ),
          Noty.button('No', 
            'btn btn-error', 
            function () {              
              n.close();
          })
          ]
    });
    n.show();
}