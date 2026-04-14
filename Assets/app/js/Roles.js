
//eliminar
function eliminarFnt(element) {
    
    let idRol = element.dataset.idrol;
    let nameRol = element.dataset.namerol;
    //console.log(idRol);
    //console.log(nameRol);
    var n = new Noty({
        text: 'Está seguro de eliminar este registro?',
        type:"error",
        theme: "metroui",
        buttons: [
          Noty.button('Si', 
            'btn btn-success', 
            async function () {                 
                 const datos = new FormData();
                 datos.append('id', idRol);
                 datos.append('name', nameRol);
                 try {
                    const url =  `${base_url}/Roles/destroy`;
                    const repuesta = await fetch(url, {
                        method:"POST",
                        body: datos,
                    });
                    const resultado = await repuesta.json();
                    if(resultado){
                       //console.log(resultado);
                    }
                    new Noty({
                        type: "success",
                        text: `${resultado.msg}`,
                        layout: "topRight",
                        theme: "metroui",
                        timeout: 2500,
                    }).show();
                    setTimeout(()=>{
                        window.location.href = `${base_url}/Roles`;
                    }, 2500)                    
                 } catch (error) {
                    console.log(error);
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