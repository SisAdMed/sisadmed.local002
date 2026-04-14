let tblPermiso;
Doocument.addEventListener(
   "DOMContentLoaded",
   function () {
      tblTable = new DataTable("#tblPermiso", {
      aProcessing: true,
      aServerSide: true,
      //opcionesnes de lenguaje
      language: {
          url :`${base_url}/Assets/json/es-ES.json`,
      },
      // ocultar columnas
      columnDefs: [
      {
         targets: [0],
         visible: false,
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
         text: "<i class='fa fa-file-excel'></i>",
         titleAttr: "Exportar a Excel",
         className: "btn btn-warning"
      },
      {
         extend: "pdfHtml5",
         text: "<i class='fa fa-file-pdf'></i>",
         titleAttr: "Exportar a PDF",
         className: "btn btn-danger"
      },
      {
         extend: "csvHtml5",
         text: "<i class='fa fa-file-text'></i>",
         titleAttr: "Exportar a CSV",
         className: "btn btn-primary",
      },
      ],
      lengthMenu: [
         [5, 10, 25, 50, -1],
         [5, 10, 25, 50, "Todos"],
         ],
      iDisplayLength: 5,
      order: [[1, "asc"]],
   });
}, false);