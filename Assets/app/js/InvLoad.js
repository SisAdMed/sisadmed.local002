//Variables 
let item = 0;
let nom_prod = '';
let nom_ubi = '';
let mensaje_error = '';
id_alm_res ='';
stock = '';
tipo_fac = 'M'
c_consig  = 0;
id_cli  = '';
//Validación de campos
$().ready(function(){
    $.validator.setDefaults({
        ignore: [],
    });
    $("form[name='my_form']").validate({
        rules: {
            id_emp: "required",
            id_tInvLoad: "required",
            fecha_comp: "required",
            id_moneda: "required",
            tasa_cambio: "required",
            id_alm: "required",
            status: "required",
            item: "required",
        },
        messages: {
            id_emp: "Debe seleccionar una Empresa",
            id_tInvLoad: "Debe seleccionar un Tipo de Movimiento",
            fecha_comp: "Debe seleccionar una Fecha",
            id_moneda: "Debe seleccionar una Moneda",
            tasa_cambio: "Debe indicar un Tipo de Cambio",
            id_alm: "Debe seleccionar un Almacén",
            status: "Debe seleccionar un Status",
            item: "Debe existir al menos un registro en el Detalle de Movimiento",
        },
        submitHandler: function(form){
            form.submit();
        }
    });
})
//Validar el tipo de formulario para sus acciones
$(document).ready(function(){
    id = $("#id").val();
    if(id){
        showrow(id);
    }else{
        listar_empresas(0);
        listar_InvTipoMov(0, '', 'id_tInvLoad');
        $("#fecha_comp").val(GetTodayDate(0));
        listar_monedas();
        listar_status(1);
        listar_almacenes();
    }
})
//Validar al seleccionar empresa
$(document).on('change', '#id_emp', function(e){
    id_emp = $(this).val();
    listar_InvTipoMov(id_emp, 0, 'id_tInvLoad');
    listar_almacenes(id_emp);
    //CArgar configuración de la Empresa
})
//Validar si el tipo de movimiento utiliza consecutivo
$(document).on('change', '#id_tInvLoad', async function(e){
    id_tInvLoad = $(this).val();
    const datos = new FormData();
    datos.append('id', id_tInvLoad);
    try{
        const url = `${base_url}/InvTipoMov/val_InvTipoMov`;
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();
        if(resultado){
            if(resultado['consecutiv__tmoinv'] != 1){
                $("#num_InvLoad").prop( "readonly", false );
                $("#num_InvLoad").prop( "required", true );
            }
        }
    }catch(err){
        console.log(err);
    }
})
//Validar el Tipo de Cambio dependiendo de la moneda seleccionada
$(document).on('change', '#id_moneda', async function(e){
    id_moneda = $(this).val();
    fecha_comp = $("#fecha_comp").val();
    xTasaCambio = await xTasa(fecha_comp, id_moneda);
    if(xTasaCambio){
        $("#tasa_cambio").val(xTasaCambio);
    }
})
//Detalles del Movimiento
$(document).on('click', '#btnAddRow', function(){
    addNewRowNew();
})
function addNewRowNew() {
    c_consig = 0;
    tipo_fac  = 'N';
    id_cli = 0;
    mod = 'C';
    nameid = "id_pro";
    item = item + 1;
    var htmlTags =
    '<tr id="fila'+item+'" class="text-xs">' +
    '<td class="text-right text-xs" style="width:5%">'+item+'</td>' +
    '<td><input type="hidden" name="id_prod[]" id="id_prod'+item+'" class="text-xs"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_prod'+item+'" name="nom_prod[]" readonly><div class="input-group-append"><span class="input-group-text text-xs"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search text-xs"></i></a></span></div></div></td>' +
    '<td><input type="hidden" name="id_ubi[]" id="id_ubi'+item+'" class="text-xs id_ubi"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_ubi'+item+'" name="nom_ubi[]" readonly><div class="input-group-append text-xs"><span class="input-group-text text-xs"><a href="#" data-toggle="modal" data-target="#modal-ubicaciones" title=" Buscar y seleccionar Ubicaciones"><i class="fas fa-search text-xs"></i></a></span></div></div></td>' +
    '<td><input type="text" name="lote[]" id="lote'+item+'" class="form-control text-xs"></td>' +
    '<td><input type="date" name="fec_ven[]" id="fec_ven'+item+'" class="form-control text-xs"></td>' +
    '<td><input type="number" name="cant[]" id="cant'+item+'" class="form-control text-right text-xs" required></td>' +
    '<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt"></i></button></div></td>' +
    '</tr>' ;
    $("#tblInvMovDet").append(htmlTags)
    $("#item").val(item);
}
function addNewRow() {
    nameid = "id_pro";
    item = item + 1;
    var htmlTags =
    '<tr id="fila'+item+'" class="text-xs">' +
    '<td class="text-right text-xs" style="width:5%">'+item+'</td>' +
    '<td><select name="id_pro[]" id="id_pro'+item+'" class="form-control select-search text-xs mi-select confirmar" required</select></td>' +
    '<td><select name="id_ubi[]" id="id_ubi'+item+'" class="form-control text-xs select-search" required</select></td>' +
    '<td><input type="text" name="lote[]" id="lote'+item+'" class="form-control text-xs"></td>' +
    '<td><input type="date" name="fec_ven[]" id="fec_ven'+item+'" class="form-control text-xs"></td>' +
    '<td><input type="number" name="cant[]" id="cant'+item+'" class="form-control text-right text-xs" required></td>' +
    '<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt"></i></button></div></td>' +
    '</tr>' ;
    $("#tblInvMovDet").append(htmlTags)
    $('.select-search').select2();
    $("#item").val(item);
    listar_ubicaciones('id_ubi'+item)
}
//funcion para confirmar una fila
$(document).on('click change', '.confirmar', function(event) {
    event.preventDefault();
    recuperar_selects()
});

function recuperar_selects () {
    let selects = $('.mi-select');

    selects.each(function () {
        let select = $(this);
        id_pro = select.val()
    });
    ConsultarProductoInvLoad(id_pro, item);
}
//Consultar productos añadidos a la tabla de detalles
async function ConsultarProductoInvLoad(id, item){
    let id_prod = id;
    const datos = new FormData();
    datos.append('id_prod', id_prod);
    try{
        const url = `${base_url}/Productos/consulta01`;
        const respuesta = await fetch (url, {
            method: "POST",
            body: datos,
        });
        const resultado = await respuesta.json();
        if(resultado){
            lote = resultado['lote_prod'];
            if(lote == 1){
                $("#lote"+item).prop( "readonly", false );
                $("#fec_ven"+item).prop( "readonly", false );
                if($("#lote"+item).val() == ' '){
                    mensaje_error +=  item + ', ';
                }
            }else{
                $("#lote"+item).prop( "readonly", true );
                $("#fec_ven"+item).prop( "readonly", true );
            }
            $("#nom_prod"+item).val(decodeHTMLEntities(resultado['nom_prod']));
        }
    }catch (err){
        console.log(err);
    }
}

function JSDateToExcelDate(date) { 

 return new Date(Date.UTC(0, 0, date - 1));
}

//Cargar archivo de Excel y llenar la tabla con los detalles del inventario
$(document).on('change', '#archivo_car_invent', async function(e){
    const content = await readXlsxFile(e.target.files[0]);
    item = 0;
    lote = '';
    currentDate='';
    $("#tblInvMovDet").html('');
    $.each(content, async function(i, xitem) {
        if(i>0){
            var currentDate = "";
            var lote = "";
            var tfecha;
            //Id Fecha de vencimiento Columna 8
            tfecha = '';
            tfecha = xitem[9];
            if(tfecha){
                var xfecha = new Date(tfecha);
                dias = 1;
                xfecha.setDate(xfecha.getDate()+1);
                dd = xfecha.getDate();
                MM = xfecha.getMonth() +1;
                yyyy = xfecha.getFullYear();
                /*
                tfecha +=1
                var dd = tfecha.getDate(); //yields day
                var MM = tfecha.getMonth(); //yields month
                var yyyy = tfecha.getFullYear(); //yields year
                MM = MM+1;
                */
                dd = dd;
                var n = MM.toString();
                var d = dd.toString();
                if(n.length < 2){
                    MM = '0'+MM;
                }
                if(d.length < 2){
                    dd = '0'+dd;
                }
                currentDate= yyyy + "-" + MM + "-" + dd;
            }
            if(xitem[8]){
                //Lote Columna 9
                lote = xitem[8];
                if(!lote){
                    lote = ' ';
                }
            }
            item++;
            nameid = "id_pro";
            //Id Prodcuto Columna 0
            id_prod = xitem[0];
            //Nombre Producto columna 3
            nom_prod = xitem[3];
            //Codigo de Ubicación columna
            id_ubi = xitem[6]
            //Nombre Ubicacion
            nom_ubi = xitem[10];
            //Cantidad columna 6
            cant = xitem[7];
            var htmlTags =
            '<tr id="fila'+item+'" class="text-xs">' +
            '<td class="text-right text-xs" style="width:5%">'+item+'</td>' +
            '<td><input type="hidden" name="id_prod[]" id="id_prod'+item+'" class="text-xs" value="'+id_prod+'"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_prod'+item+'" name="nom_prod[]" readonly value="'+nom_prod+'"><div class="input-group-append"><span class="input-group-text text-xs"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search text-xs"></i></a></span></div></div></td>' +
            '<td><input type="hidden" name="id_ubi[]" id="id_ubi'+item+'" class="text-xs id_ubi" value="'+id_ubi+'"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_ubi'+item+'" name="nom_ubi[]" readonly value="'+nom_ubi+'"><div class="input-group-append text-xs"><span class="input-group-text text-xs"><a href="#" data-toggle="modal" data-target="#modal-ubicaciones" title=" Buscar y seleccionar Ubicaciones"><i class="fas fa-search text-xs"></i></a></span></div></div></td>' +
            //'<td><input type="number" name="id_pro[]" id="id_pro'+item+'" class="form-control text-xs" required value="'+id_prod+'" hidden>'+nom_prod+'</td>' +
            //'<td><input type="number" name="id_ubi[]" id="id_ubi'+item+'" class="form-control text-xs" required value="'+id_ubi+'" hidden>'+nom_ubi+'</td>' +
            '<td style="width:10%"><input type="text" name="lote[]" id="lote'+item+'" class="form-control text-xs" value="'+lote+'"></td>' +
            '<td><input type="date" name="fec_ven[]" id="fec_ven'+item+'" class="form-control text-xs" value="'+currentDate+'"></td>' +
            '<td style="width:10%"><input type="number" name="cant[]" id="cant'+item+'" class="form-control text-right text-xs" required value="'+cant+'"></td>' +
            '<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt"></i></button></div></td>' +
            '</tr>' ;
            $("#tblInvMovDet").append(htmlTags)
            $('.select-search').select2();
            $("#item").val(item);
            ConsultarProductoInvLoad(xitem[0], item);
        }
    });
    console.log(mensaje_error);
});
//Mostrar valores del registro
async function showrow(id){
    const datos = new FormData();
    datos.append('id', id);
    try{
        const url = `${base_url}/InvLoad/showrow`; 

        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();
        if(resultado){
            //Encabezado de Movimiento
            id_emp = resultado[0]['id_emp'];
            listar_empresas(id_emp);
            listar_InvTipoMov(id_emp, resultado[0]['id_tInvLoad'], 'id_tInvLoad');
            $("#num_InvLoad").val(resultado[0]['num_InvLoad']);
            $("#fecha_comp").val(resultado[0]['fecha_comp']);
            listar_monedas(resultado[0]['id_moneda']);
            xTasaCambio = number_format(resultado[0]['tasa_cambio'], 8);
            xTasaCambio = xTasaCambio.replace(".", ",");
            $("#tasa_cambio").val(xTasaCambio);
            listar_almacenes(id_emp, resultado[0]['id_alm']);
            $("#descrip_InvLoad").val(resultado[0]['descrip_InvLoad']);
            status = resultado[0]['status'];
            listar_status(status);
            //Detalle de Movimiento
            item = 0;
            nameid = "id_pro";
            $.each(resultado, async function(i, xitem){
                cantidad = xitem.cantidad;
                fec_venc = xitem.fec_venc;
                lote = xitem.lote;
                id_ubi = xitem.id_ubi
                id_prod = xitem.id_prod
                nom_prod = xitem.nom_prod;
                nom_ubi = xitem.nom_ubi;
                item = i + 1;
                var htmlTags =
                '<tr id="fila'+item+'" class="text-xs">' +
                '<td class="text-right text-xs" style="width:5%">'+item+'</td>' +
                '<td><input type="hidden" name="id_prod[]" id="id_prod'+item+'" class="text-xs" value = "'+id_prod+'"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_prod'+item+'" name="nom_prod[]" readonly value="'+nom_prod+'"><div class="input-group-append"><span class="input-group-text text-xs"><a href="#" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search text-xs"></i></a></span></div></div></td>' +
                '<td><input type="hidden" name="id_ubi[]" id="id_ubi'+item+'" class="text-xs id_ubi" value="'+id_ubi+'"><div class="input-group"><input type="text" class="form-control text-xs" id="nom_ubi'+item+'" name="nom_ubi[]" readonly value="'+nom_ubi+'"><div class="input-group-append text-xs"><span class="input-group-text text-xs"><a href="#" data-toggle="modal" data-target="#modal-ubicaciones" title=" Buscar y seleccionar Ubicaciones"><i class="fas fa-search text-xs"></i></a></span></div></div></td>' +                
                '<td><input type="text" name="lote[]" id="lote'+item+'" class="form-control text-xs" value="'+lote+'"></td>' +
                '<td><input type="date" name="fec_ven[]" id="fec_ven'+item+'" class="form-control text-xs" value="'+fec_venc+'"></td>' +
                '<td><input type="number" name="cant[]" id="cant'+item+'" class="form-control text-right text-xs" required value="'+cantidad+'"></td>' +
                '<td class="text-center"><div class="btn-group"><button type="button" class="btn btn-danger btn-xs borrar text-xs" title="Eliminar item" ><i class="far fa-trash-alt"></i></button></div></td>' +
                '</tr>' ;
                $("#tblInvMovDet").append(htmlTags)
                $("#item").val(item);
                $(".id_ubi").trigger('change');
            })
        }
    }catch(err){
        console.log(err)
    }
}

//funcion para confirmar una fila
$(document).on('click', '#aprovee', async function(event) {
    aprovee = $(this);
    if ($('#aprovee').prop('checked')){
        approveBtn(event);
    }

});


async function approveBtn(e){
    let id = e.dataset.id;
    let name = e.dataset.name;
    let code = e.dataset.code
    let number = e.dataset.number
    const url =  `${base_url}/InvLoad/approve`;

    var message = "¿Está seguro de Aprobar la Carga de Inventario " + code + ' - ' + name + " Número " + number + "?";

    Swal.fire({
        icon: 'question',
        title: message,
        showConfirmButton: true,
        confirmButtonText: 'APROBAR',
        confirmButtonColor: '#3085d6',
        showCancelButton: true,
        cancelButtonText: 'CANCELAR',
        cancelButtonColor: '#d33',
        buttonsStyling: true,
        showLoaderOnConfirm: true,
        preConfirm: function(){
            return new Promise(function(resolve){
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {id: id, mov_ent: 3, code: code, name: name, number: number},
                    dataType: 'json'
                }).done(function(response){
                     Swal.fire({
                        icon: `${response.icon}`,
                        title: `${response.title}`,
                        text: `${response.msg}`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `${base_url}/InvLoad`;
                        }
                    });
                }).fail(function(e){
                   Swal.fire({
                        icon: 'error',
                        title: 'Error.....',
                        text: 'No se pudo Aprobar el Movimiento de Carga de Inventario, por favor intente luego',
                        timer: 5000
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.href = `${base_url}/InvLoad`;
                        }
                    });

                })
            })
        }
    });
    allowOutsideClick: false
}
//Funcion Boton Eliminar
function eliminarBtn(e) {
    let id = e.dataset.id;
    let name = e.dataset.name;
    let code = e.dataset.code
    let number = e.dataset.number
    const url =  `${base_url}/InvLoad/destroy`;
    var message = "¿Está seguro de eliminar la Carga de Inventario " + code + ' - ' + name + " Número " + number + "?";

    Swal.fire({
        icon: 'question',
        title: message,
        showConfirmButton: true,
        confirmButtonText: 'ELIMINAR',
        confirmButtonColor: '#3085d6',
        showCancelButton: true,
        cancelButtonText: 'CANCELAR',
        cancelButtonColor: '#d33',
        buttonsStyling: true,
        showLoaderOnConfirm: true,
        preConfirm: function(){
            return new Promise(function(resolve){
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {id: id, name: name, code: code, number: number},
                    dataType: 'json'
                }).done(function(response){
                     Swal.fire({
                        icon: `${response.icon}`,
                        title: `${response.title}`,
                        text: `${response.msg}`,
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            window.location.href = `${base_url}/InvLoad`;
                        }
                    });
;
                }).fail(function(e){
                     Swal.fire({
                        icon: 'error',
                        title: 'Error.....',
                        text: 'No se pudo eliminar el registro, por favor intente luego',
                        timer: 5000
                    });
                })
            })
        }
    });
}
//funcion para elimnar una fila
$(document).on('click', '.borrar', function(event) {
    Swal.fire({
        title: "¡Está seguro de eliminar este item?",
        text: "Este cambio no se puede revertir",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar"
      }).then((result) => {
        if (result.isConfirmed) {
            event.preventDefault();
            $(this).closest('tr').remove();
          Swal.fire({
            title: "Eliminado!",
            text: "El item ha sido eliminado.",
            icon: "success"
          });
        }
      });
});