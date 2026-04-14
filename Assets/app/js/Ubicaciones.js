//Validaciones de campos en linea

$(document).ready(function() {
    $("#cod_ubi").on("keyup", function(e) {
        var cod_ubi = $("#cod_ubi").val();
        var dataString = 'cod_ubi=' + cod_ubi;
        let url =  `${base_url}/Ubicaciones/validar`;  
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
    let codigo = element.dataset.code;      
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
               datos.append('cod_ubi', codigo);                
               try {                                
                let url =  `${base_url}/Ubicaciones/destroy`;                     
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
                   window.location.href = `${base_url}/Ubicaciones`;
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
                   window.location.href = `${base_url}/Ubicaciones`;
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