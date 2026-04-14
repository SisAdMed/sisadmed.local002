$().ready(function(){
    listar_empresas(0);
    //Cargar fechas de inicio y fin del mes actual
    $("#fec_ini").val(getFirstDateofMonth());
    $("#fec_ini").trigger('change');
})
//Al seleccionar empresas llenar los combos respectivos
$(document).on('change', '#id_emp', function(e){
    e.preventDefault();
    id_emp = $("#id_emp").val()
    listar_tipos_documentos(id_emp);
    //Documentos para los Numero de Control
    listar_tipos_documentos(id_emp, '', '', false, 'id_tdo_ctrl');
    //$("#id_tdo").trigger('change');
    //listar_clientes('C', id_emp, '');
    $("#tblDetnrocontrol").html('');
})
//Limpiar campos del formulario de Actualizacion de numero de Control
$(document).on('click', '#btnClear', function(e){
    //Cargar Empresas
    id_emp = $("#id_emp").val()
    $("#id_tdo_ctrl").empty();
    listar_empresas(0);
    $("#tblDetnrocontrol").html('');
    //Cargar fechas de inicio y fin del mes actual
    $("#fec_ini").val(getFirstDateofMonth());
    $("#fec_ini").trigger('change');
})
//Ejecutar busquedas del formulario
$(document).on('click', '#btnSearch', async function(){
    CargarDatosTabla()
})
//Buscar data para llenar tables de documentos para el numero de control
function CargarDatosTabla(e){
    //Obetener datos de busqueda
    id_emp = $('#id_emp').val();
    id_tdo_ctrl = $("#id_tdo_ctrl").val();
    fec_ini = $('#fec_ini').val();
    fec_fin = $('#fec_fin').val();
    const url = `${base_url}/CXCDocument/DocCtrolCXC`;
    //Realizar busqueda de datos con ajax
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {id_emp: id_emp, id_tdo_ctrl: id_tdo_ctrl, fec_ini: fec_ini, fec_fin: fec_fin},
        success:function(response){
            $("#tblnrocontrol").DataTable().clear();
            $("#tblnrocontrol").DataTable().destroy()
            var tblControl = $("#tblnrocontrol").DataTable({
                data: response,
                columns: [
                    {"data" : "id_cot"},
                    {"data" : "nombre_emp"},
                    {"data" : "tipo_codigo"},
                    {"data" : "nom_tdoc"},
                    {"data" : "nom_ent"},
                    {"data" : "fecha_comp", render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN)},
                    {"data" : "num_tdo"},
                    {"data" : "nro_control"},
                    {defaultContent: '<a type="button" class="btn btn-primary btn-xs" title="Modificar Número de Control"> <i class="fa fa-edit"></i></a>'}
                ],
                columnDefs: [
                    {
                        targets: 0,
                        visible: false,
                    }
                ],
                fnCreatedRow: function(rowEl, response_new){
                    $(rowEl).attr('id', response_new[0]);
                },
                order: [[1, 'asc']],
                language: {
                    url: `${base_url}/Assets/json/es-ES.json`,
                }
            })
        }
    });
}
$("#tblnrocontrol").on('click', 'a', async function (e) {
    var table = new DataTable("#tblnrocontrol");
    let data = table.row(e.target.closest('tr')).data();
    //Asignarcion a variables
    let id_cot = data['id_cod']
    nombre_emp = data['nombre_emp'];
    tipo_codigo = data['tipo_codigo'];
    nom_tdoc = data['nom_tdoc']
    nom_ent = data['nom_ent'];
    fecha_comp = data['fecha_comp'];
    num_tdo = data['num_tdo'];
    nro_control = data['nro_control']
    //Swelaert, para solicitar el nuvo valor
    const resultado = await Swal
	    .fire({
		    title: "Cambio Número de Control de la " + tipo_codigo + " - " + nom_tdoc + " número " + num_tdo,
            input: "number",
            inputValue : nro_control,

            inputValidator: control => {
                if (!control) {
				    return "Debe indicar una número de control válido";
				} else {
				    return undefined;
				}
			},
			didOpen: async () => {
				const input = await Swal.getInput();
                const id_cot = data['id_cot'];
                $('#'+ input.id).focus()
                $('#'+ input.id).select()
			},
			showCancelButton: true,
			confirmButtonText: "Ok",
			cancelButtonText: "Cancelar",
	    });
	    if(resultado.value){
            id_cot = data['id_cot'];
			const url = `${base_url}/CXCDocument/nro_control`;
            console.log()
			try {
				var datos = new FormData();
				datos.append('id_cot', id_cot);
				datos.append('nro_control', resultado.value);
				let repuesta = await fetch(url, {
					method: "POST",
					body: datos,
				});
                const response = await repuesta.json();
                if(response){
                    Swal.fire("Nro de control actualizado sastisfactoriamente")
                    CargarDatosTabla();
                }else{
                    Swal.fire({
                        title: "Error",
                        text: "No se pudo actulizar el número de control, por favor intente luego",
                        icon: "error"
                      });
                }
			} catch (error) {
                Swal.fire({
                    title: "Error",
                    text: "No se pudo actulizar el número de control, debe tener una longitud maxima de 10 dígitos",
                    icon: "error"
                  });
			}
		}
});
