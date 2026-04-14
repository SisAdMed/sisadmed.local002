//Variables Globales
let tipo;
let parametro;
let item = 0;
//Validación de campos
$().ready(function(){
    //Comentado, ya que no es obligatorio que posea un concepto de integración
    if(item >0){
        $.validator.setDefaults({
            ignore: [],
            debug: true,
            success: "valid"
        });
    }
    $("form[name='my_form']").validate({
        rules:{
            codigo: {
                required: true,
                minlength: 3,
                maxlength: 3,
            },
            nombre: {
                required: true,
                minlength: 4,
                maxlength: 50,
            },
            tipo: "required",
            parametro: "required",
            id_ctb: "required",
            status: "required",
        },
        messages:{
            codigo: {
                required: "Debe especificar un Código",
                minlength: "Debe contener al menos 3 carácteres",
                maxlength: "Debe contener máximo 3 carácteres",
            },
            nombre: {
                required: "Debe especificar un nombre",
                minlength: "Debe contener al menos 4 carácteres",
                maxlength: "Debe contener máximo 50 carácteres",
            },
            tipo: "Se requiere un valor para el Tipo",
            parametro: "Se requiere un valor para el Parámetro",
            id_ctb: "Debe especificar una Cuenta Contable",
            status: "Debe especificar un Status",
        },
    });
})
//Al ingresar a la apliacion
$(document).ready(function(e){
    id = $("#id").val();
    if(id){
        show_row_NomConcepto(id);
    }else{
        listar_tipo_asi_ded('', 'tipo');
        listar_param_nom('', 'parametro');
        listar_si_no('', 'nomfju');
        listar_status(1);
    }
})
$().ready(function(e){
    cargar_screen_main()
})
//Carga del Index, cargar los registros
async function cargar_screen_main(){
    const url = `${base_url}/NomCon/cargar_screen_main`;
    const records = await fetch(url, {
        method:'POST',
    });
    const data = await records.json();
    let tab = '';
    data.forEach(function(nomcon){
        //Status
        if(nomcon.status == 1){
            status = '<td class="text-center"><span class="badge badge-success">Activo</span></td>'
        }else{
            status = '<td class="text-center"><span class="badge badge-danger">Inactivo</span></td>'
        }
        //Acciones
        acciones = `
            <td class="text-center">
                <a type="button" class="btn btn-warning btn-xs" href="${base_url + '/NomCon/edit/' +  nomcon.id_nomcue}"><i class="fa fa-edit"></i></a>
                <button id="Data" data-id="${nomcon.id_nomcue}" data-name="${nomcon.nombre}" data-code = "${nomcon.codigo}" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)"><i class="fa fa-trash"></i></button>
        </td>
        `;
        tab += `<tr>
            <td>${nomcon.id_nomcue}</td>
            <td>${nomcon.codigo}</td>
            <td>${nomcon.nombre}</td>
            <td>${nomcon.tipo}</td>
            <td>${nomcon.parametro}</td>
            <td class="text-right">${format_number_with_dec(nomcon.factop,2)}</td>
            ${status}
            ${acciones}
        </tr>`;
    });
    if(tab){
        document.getElementById('tbody').innerHTML = tab;
        $("#tblTableNomCon").DataTable({
            language: {
                url :`${base_url}/Assets/json/es-ES.json`,
            },
        });
    }
};
//Mostarra el rgistro para edicion
async function show_row_NomConcepto($id){
    const datos = new FormData();
    datos.append('id', $id);
    try{
        const url = `${base_url}/NomCon/show_row_NomConcepto`;
        const respuesta = await fetch(url, {
            method: "POST",
            body: datos,
        });
        const data = await respuesta.json();
        if(data){
            item = 0;
            $("#codigo").val(data[0].codigo);
            $("#nombre").val(data[0].nombre);
            tipo = data[0].ttipo;
            listar_tipo_asi_ded(tipo, 'tipo');
            $("#tipo").trigger("change");
            parametro = data[0].tparametro;
            listar_param_nom(parametro, 'parametro');
            $("#parametro").trigger("change");
            factop = data[0].factop;
            $("#factop").val(format_number_with_dec(factop,4));
            $("#nomunim").val(data[0].nomuni);
            nomfju = data[0].nomfju;
            listar_si_no(nomfju, 'nomfju');
            status = data[0].status;
            listar_status(status);
            $("#id_ctb").val(data[0].id_ctb);
            $("#nom_ctb").val(data[0].nombre_cta);
            status = data[0].status;
            listar_status(status);
            for(x of data){
                if(x.id_nomcue_int){
                    item = item + 1;
                    var htmlTags =
                        '<tr id="'+item+'">' +
                            '<td class="text-right">'+item+'</td>' +
                            '<td><input type="text" class="form-control text-right" name="nomcue_codigo_int[]" id="nomcue_codigo_int'+item+'" readonly value="'+x.cod_int+'"></input></td>' +
                            '<td><input type="hidden" name="id_nomcue_int[]" id="id_nomcue_int'+item+'"  value="'+x.cod_int+'"><div class="input-group"><input type="text" class="form-control" id="nom_nomcue_int'+item+'" name="nom_nomcue_int" readonly  value="'+x.nom_int+'"><div class="input-group-append"><span class="input-group-text nom_conceptoNOM"><a href="#" data-toggle="modal" data-target="#modal_ConceptosNom" title="Buscar y seleccionar Conceptos de Nómina"><i class="fas fa-search"></i></a></span></div></div></td>' +
                            '<td><input type="text" class="form-control" name="nomcue_tipo_int[]" id="nomcue_tipo_int'+item+'" readonly  value="'+x.tipo_int+'"></input></td>' +
                            '<td><input type="text" class="form-control" name="nomcue_parametro_int[]" id="nomcue_parametro_int'+item+'" readonly  value="'+x.parametro_int+'"></input></td>' +
                            '<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></button></div></td>' +
                        '</tr>';
                    $("#tbl_nomdcu").append(htmlTags);
                }
            }
        }
    }catch(error){
        console.log(error);
    }
}
//Agregar Detalle Concepto
function agregarDetalleConcepto(){
    item = item + 1;
    var htmlTags =
        '<tr id="'+item+'">' +
            '<td class="text-right">'+item+'</td>' +
            '<td><input type="text" class="form-control text-right" name="nomcue_codigo_int[]" id="nomcue_codigo_int'+item+'" readonly></input></td>' +
            '<td><input type="hidden" name="id_nomcue_int[]" id="id_nomcue_int'+item+'"><div class="input-group"><input type="text" class="form-control" id="nom_nomcue_int'+item+'" name="nom_nomcue_int" readonly><div class="input-group-append"><span class="input-group-text nom_conceptoNOM"><a href="#" data-toggle="modal" data-target="#modal_ConceptosNom" title="Buscar y seleccionar Conceptos de Nómina"><i class="fas fa-search"></i></a></span></div></div></td>' +
            '<td><input type="text" class="form-control" name="nomcue_tipo_int[]" id="nomcue_tipo_int'+item+'" readonly></input></td>' +
            '<td><input type="text" class="form-control" name="nomcue_parametro_int[]" id="nomcue_parametro_int'+item+'" readonly></input></td>' +
            '<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-sm borrar" title="Eliminar item" ><i class="far fa-trash-alt"></i></button></div></td>' +
        '</tr>';
        $("#tbl_nomdcu").append(htmlTags);
}
$(document).on('change', '#tipo', function(e){
    e.preventDefault();
    tipo = $(this).val();
});
$(document).on('change', '#parametro', function(e){
    e.preventDefault();
    parametro = $(this).val();
    muestra_oculta("factopmo");
    muestra_oculta("nomfjumo");
    muestra_oculta("nomunimo");
    if(tipo == 'A' && (parametro == "D" || parametro == 'H')){
        muestra_oculta("factopmo");
        muestra_oculta("nomfjumo");
    }else if(tipo == 'A' && parametro == "C"){
        muestra_oculta("factopmo");
        muestra_oculta("nomfjumo");
        muestra_oculta("nomunimo");
    }
});
$(document).on('blur', '#factop', function(e){
    e.preventDefault();
    factop = format_number_with_dec($(this).val(),4)
    $("#factop").val(factop);
})
//funcion para elimnar una fila
$(document).on('click', '.borrar', function(event) {
	event.preventDefault();
	$(this).closest('tr').remove();
});
$(document).on('keyup', '#codigo', async function(e){
    e.preventDefault();
    let datos = new FormData();
    let cod_nomcue = $(this).val();
    cod_nomcue.replace(/ /g, "");
    datos.append('id', cod_nomcue);
    try {
        const url = `${base_url}/NomCon/val_codigo_nomcon`;
        const respuesta = await fetch(url, {
            method: "POST",
            body: datos,
        });
        const data = await respuesta.json();
        validateExists(data, e);
    } catch (error) {
        console.log(error);
    }
})
//Mensajes de transacciones
function msgSave(){
    //Guardar registro
    Swal.fire({
        title: "¿Quieres guardar los cambios??",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Gaurdar",
        denyButtonText: `No gaurdar`
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          Swal.fire("Saved!", "", "success");
        } else if (result.isDenied) {
          Swal.fire("Changes are not saved", "", "info");
        }
      });
}