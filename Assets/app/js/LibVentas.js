//Al ingresar a la aplicacion
$().ready(function(e){
   listar_empresas(0);
})
function fiscal_report (element){
    //Validar que se haya seleccionado una empresa
    let id = element;
    if( validarcampos()){
       generate_report(id);
    }
}
//Validar los campos del formulario
function validarcampos(){
    id_emp = $("#id_emp").val();
    if(!id_emp){
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Debe indicar una empresa para generar el reporte",
            didClose: () => { $("#id_emp").focus(); }
          });
        return false;
    }
    //Validar que se haya seleccionado una fecha de inicio
    fec_ini = $("#fec_ini").val();
    if(!fec_ini){
        Swal.fire({
            icon: "error",
            title: "Error...",
            text: "Debe indicar una fecha de inicio para generar el reporte",
            didClose: () => { $("#fec_ini").focus(); }
          });
        return false;
    }
    //Validar que se haya seleccionado una fecha de fin
    fec_fin = $("#fec_fin").val();
    if(!fec_fin){
        Swal.fire({
            icon: "error",
            title: "Error...",
            text: "Debe indicar una fecha de corte para generar el reporte",
            didClose: () => { $("#fec_fin").focus(); }
          });
        return false;
    }
    //Validar que la fecha final no sea menor a la fecha inciial
    if(fec_fin < fec_ini){
        Swal.fire({
            icon: "error",
            title: "Error...",
            text: "Fecha final no puede ser menor a la fecha incial",
            didClose: () => { $("#fec_fin").focus(); }
          });
        return false;
    }
    return true;
}

function generate_report(id){
    //Reporte en Excel
    id_emp = $("#id_emp").val();
    nom_empresa = $("#id_emp option:selected").text();
    fec_ini = $("#fec_ini").val();
    fec_fin = $("#fec_fin").val();
    fecha_ini = fec_ini.split('-');
    fecha_fin = fec_fin.split('-');
    if(id == 'C'){        
        title = "¿Está seguro que desea imprimir el Libro de Compras de la empresa " +  nom_empresa +  " correspondiente al período del " + fecha_ini[2] + '/' + fecha_ini[1] + '/' + fecha_ini[0] + "  al " + fecha_fin[2] + '/' + fecha_fin[1] + '/' + fecha_fin[0] + " en Excel?";
        url = `${base_url}/LibVentas/reportComprasExcel?id_emp=` + id_emp + `&fec_ini=` + fec_ini + `&fec_fin=` + fec_fin
    }else{
        title = "¿Está seguro que desea imprimir el Libro de Ventas de la empresa " +  nom_empresa +  " correspondiente al período del " + fecha_ini[2] + '/' + fecha_ini[1] + '/' + fecha_ini[0] + "  al " + fecha_fin[2] + '/' + fecha_fin[1] + '/' + fecha_fin[0] + " en Excel?";
        url = `${base_url}/LibVentas/reportSalesExcel?id_emp=` + id_emp + `&fec_ini=` + fec_ini + `&fec_fin=` + fec_fin
    }
    Swal.fire({
        icon: 'question',
        title: title,
        showCancelButton: true,
        confirmButtonText: "Imprimir",
    }).then((result) => {
        if (result.isConfirmed) {
            if(id="excel"){
                window.open(url, '_blank');
            }
        }
    });
}