//eliminar
function eliminarBtn(element) {
    
    let idm = element.dataset.id;
    let namem = element.dataset.name;    
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
                 try {
                    let url =  `${base_url}/Paises/destroy`;                    
                    let repuesta = await fetch(url, {
                        method:"POST",
                        body: datos,
                    });                    
                    const resultado = await repuesta.json();                    
                    new Noty({
                        type: "success",
                        text: `${resultado.msg}`,
                        layout: "topRight",
                        theme: "metroui",
                        timeout: 2500,
                    }).show();
                    setTimeout(()=>{
                        window.location.href = `${base_url}/Paises`;
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