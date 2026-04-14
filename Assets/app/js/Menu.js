let tblMenu;
document.addEventListener(
  "DOMContentLoaded",
  function () {
    tblMenu = new DataTable("#tblMenu", {
        //aProcessing: true,
        //aServerSide: true,
        //opciones de lenguaje
        language: {
             url :`${base_url}/Assets/json/es-ES.json`,
        },
      // ocultar columnas
      columnDefs: [
        {
          targets: [0],
          visible: true,
          serchable: false,
        },
      ],
      // mostrar botones de exportacion
      dom: "lBfrtip",
      buttons: [
        {
          extend: "copyHtml5",
          text: "<i class='fa fa-copy'></i>",
          titleAttr: "Copiar",
          className: "btn btn-secondary"
        },
        {
          extend: "excelHtml5",
          text: "<i class='fa fa-file-excel-o'></i>",
          titleAttr: "Exportar a Excel",
          className: "btn btn-warning"
        },
        {
          extend: "pdfHtml5",
          text: "<i class='fa fa-file-pdf-o'></i>",
          titleAttr: "Exportar a PDF",
          className: "btn btn-danger"
        },
        {
          extend: "csvHtml5",
          text: "<i class='fa fa-file-text-o'></i>",
          titleAttr: "Exportar a CSV",
          className: "btn btn-primary",
        },
      ],
      lengthMenu: [
        [5, 10, 25, 50, -1],
        [5, 10, 25, 50, "Todos"],
      ],
      iDisplayLength: 10,
      order: [[0, "asc"]],
    });
  },
  false
);

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
                    let url =  `${base_url}/Menu/destroy`;                    
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
                        window.location.href = `${base_url}/Menu`;
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