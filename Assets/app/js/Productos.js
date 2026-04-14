tcosto = 0;

$(function(){
    $("#my_form_products").validate({
		rules: {
			cod_prod: "required",
			cod2_prod: "required",
			nom_prod: "required",
			status: "required",
			origen: "required",
			alto: "required",
			ancho: "required",
			largo: "required",
			id_presen1: "required",
			id_presen2: "required",
			uni_com_prod: "required",
			uni_ven_prod: "required",
			con_cons_prod: "requiere",
			id_grupo: "requiere",
			id_sub_grupo: "requiere",
		},
		messages: {
			cod_prod: "Código es requerido",
			cod2_prod: "Código 2 es requerido",
			nom_prod: "Nombre es requerido",
			status: "Status es requerido",
			origen: "Debe especificar un origen del producto",
			alto: "Debe especificar un alto",
			ancho: "Debe especificar un ancho",
			largo: "Debe especificar un largo",
			id_presen1: "Debe especificar un empaque",
			id_presen2: "Debe especificar una presentación por empaque",
			uni_com_prod: "Debe especificar una Unidad de Comrpas",
			uni_ven_prod: "Debe espeficicar una Unidad de Ventas",
			con_cons_prod: "Debe especificar una Unidad de Consignación",
			id_grupo: "Debe especificar un grupo",
			id_sub_grupo: "Debe especificar un Sub Grupos",
		},
	});
})

$("#cod_prod").on('keyup change', function(e){
    var datos = new FormData();
    var cod_prod = $(this).val();
    var id = $("#id").val();
    cod_prod.replace(/ /g, "");
    datos.append('cod_prod', cod_prod);
    datos.append('id', id);
    var url =`${base_url}/Productos/val_cod_pro`;
    console.log(url);
    fetch(url, {
        method: 'POST',
        body: datos
    })
    .then(response => response.json())
    .then(data =>{
        if(data.success == 1){
            Swal.fire({
                icon: "warning",
                title: "Oops...",
                text: "Código "+ cod_prod +" ya existe en otro producto",
            });
        }
    })
    .catch(err => console.log(err))
});

$( "#product-photo" ).click(function() {
    cod_prod = $("#cod_prod").val();
    cod2_prod = $("#cod2_prod").val();
    nom_prod = $("#nom_prod").val();
    origen = $("#origen").val();
    alto = $("#alto").val();
    ancho = $("#ancho").val();
    largo = $("#largo").val();
    id_presen1 = $("#id_presen1").val();
    id_presen2 = $("#id_presen2").val();
    if(!cod_prod || !cod2_prod || !nom_prod || !origen || !alto || !ancho || !largo || !id_presen1 || !id_presen2 || !gen_prod){
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: 'Por favor complete los datos faltantes de la pestaña de "Producto", los mismos estan marcados con asterisco. Para poder continuar el proceso',
      });
        $('.tab-content a:first').tab('show')
        return false;
    }
});

$(document).ready(function(){
    //Cargar Index
    var tituloPagina = window.location.pathname;
    var metodo = Object.keys(tituloPagina.split('/')).length;
    if(metodo === 2){
        //cargar_screen_main();
    }
    id = $('#id').val();
    if(id){
        charge_history(id);
    }
    if ($('#door_prod').prop('checked') ) {
    }else{
        $("#door_costo").val(0);
        $("#door_costo").attr('readonly', 'readonly');;
    }
});

function charge_history(id){
    const url = `${base_url}/Productos/charge_history`;
    $.ajax({
        url: url,
        data: {id: id},
        method: 'POST',
        dataType: 'json',
        dataSrc: '',
        beforeSend: function(){
            $('.loder').show();
        }, 
        success: function(response){
            table = $('#tblTableHis').DataTable({
                aProcessing: true,
                aServerSide: true,
                destroy: true,
                data:response,
                responsive: true,
                processing:true,
                paging: false,
                info: false,
                sort:false,
                columns: [
                    {data: 'id', visible:false, orderable:true},
                    {data: 'fecha', render: $.fn.dataTable.render.moment(FROM_PATTERNHH, TO_PATTERNHH)},
                    {data: 'costo_prod', className: 'text-right', render: DataTable.render.number(".", ",", 4)},
                    {data: 'flete_prod', className: 'text-right', render: DataTable.render.number(".", ",", 4)},
                    {data: 'otros_prod', className: 'text-right', render: DataTable.render.number(".", ",", 4)},
                    {data: 'door_costo', className: 'text-right', render: DataTable.render.number(".", ",", 4)},
                    {data: 'costo1', className: 'text-right', render: DataTable.render.number(".", ",", 4)},
                    {data: 'recar_prod', className: 'text-right', render: DataTable.render.number(".", ",", 4)},
                    {data: 'ventas_prod', className: 'text-right', render: DataTable.render.number(".", ",", 4)},
                    {data: 'recar2_prod', className: 'text-right', render: DataTable.render.number(".", ",", 4)},
                    {data: 'venta2_prod', className: 'text-right', render: DataTable.render.number(".", ",", 4)},
                ], 
                columDefs: [{
                    targets: [1,2,3,4,5,6,7,8,9,10],
                    orderable: false,
                }],
            })
        }, 
        complete: function(){
            $('.loader').hide();
        },
        error: function (xhr, status, error) {
             $(".loader").hide();
       }
    })
}

function updateCost1(){
    var costo_prod = parseFloat($("#costo_prod").val());
    var flete_prod = parseFloat($("#flete_prod").val());
    var otros_prod = parseFloat($("#otros_prod").val());
    var door_costo = parseFloat($("#door_costo").val());
    if($("#door_prod").prop('checked')){
        $("#costo1").val(((costo_prod+flete_prod+otros_prod+door_costo)));
    }else{
        $("#costo1").val(((costo_prod+flete_prod+otros_prod)));
    }
    rechargeSale(1);
}
function rechargeSale(modo){
    var costo1 = parseFloat($("#costo1").val());
    var ventas_prod = parseFloat($("#ventas_prod").val());
    if(modo == 1){
        var recar_prod = parseFloat($("#recar_prod").val());
        $("#ventas_prod").val((costo1 / recar_prod).toFixed(4));
    }else if(modo == 2){
        $("#recar_prod").val((costo1 / ventas_prod ).toFixed(4));
    }else if(modo == 3){
        var recar2_prod = parseFloat($("#recar2_prod").val());
        $("#venta2_prod").val((costo1 / recar2_prod).toFixed(4));
    }else if(modo == 4){
        var venta2_prod = parseFloat($("#venta2_prod").val());
        $("#recar2_prod").val((costo1 / venta2_prod).toFixed(4));
    }else{
        $("#recar2_prod").val((costo1 / venta2_prod ).toFixed(4));
    }
}
//Calcular el valor del Door to Door por las dimensiones
$(document).on('keyup', '.dimension', function(e){
    var alto = parseFloat($("#alto").val());
    var ancho = parseFloat($("#ancho").val());
    var largo = parseFloat($("#largo").val());
    const tcosto = TCostoPieINV();
    var dimension = ((alto * ancho * largo) / `${factor}`) * `${costo}`;
    if ($('#door_prod').prop('checked')) {
        $("#door_costo").val(dimension);
    }
})
async function TCostoPieINV (){
    const costo = await getParam('INV');
    tcosto = costo['costo_pie3'];
    return tcosto;
}
//eliminar
function eliminarBtn(element) {
    //Capturar datos del producto
    let id = element.dataset.id;
    let name = element.dataset.name;
    let code = element.dataset.code;
    //Preguntar si esta seguro de eliminar el registro
    Swal.fire({
        icon: 'warning',
        title: 'Está seguro de eliminar este registro?',
        showConfirmButton: true,
        confirmButtonText: 'ELIMINAR',
        confirmButtonColor: '#3085d6',
        showCancelButton: true,
        cancelButtonText: 'CANCELAR',
        cancelButtonColor: '#d33',
        buttonsStyling: true,
    }).then((result) => {
        if (result.isConfirmed){
            borrarprod(id, name, code);
        };
    });
}
//Borrar registro de la base de datos
async function borrarprod(id, name, code){
    const datos = new FormData();
    datos.append('id', id);
    datos.append('name', name);
    datos.append('code', code);
    try{
        const url =  `${base_url}/Productos/destroy`;
        console.log(url)
        const repuesta = await fetch(url, {
            method:"POST",
            body: datos,
        });
        const resulta = await repuesta.json();
        console.log(resulta);
        Swal.fire({
            position: 'top-end',
            icon: `${resulta.icon}`,
            title: `${resulta.title}`,
            text: `${resulta.msg}`,
        }).then((result) => {
            if (result.isConfirmed){
                window.location.href = `${base_url}/Productos`;
            };
        });
    }catch(error){
        console.log(error);
         Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Error.....',
            text: 'No se pudo eliminar ya que se encuentra asociada a otros registros'
        });
    }
}
//Imprimir etiquetas
$(document).on('click','#btn_print',function(e){
   funcion = "print_labels";
});
$( document ).on( 'click', '.door_prod', function(){
    let val = $(this).val();
    if( $( this ).is( ':checked' ) ){
        $("#door_costo").removeAttr('readonly');
    }else{
        $("#door_costo").val(0);
        $("#door_costo").attr('readonly', 'readonly');
    }
});

var tblMain = $("#tblTable_prod").DataTable({
    aProcessing: true,
    aServerSide: true,
    order: [[3, 'asc']],
    language: {
        url :`${base_url}/Assets/json/es-ES.json`,
    },
    columnDefs: [
        {
            targets: 0,
            visible: false,
            searchable: false,
        }
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
});
function cargar_screen_main(){
    div_loading();
    const url = `${base_url}/Productos/`;
    $.ajax({
        url: url,
        method: 'POST',
        dataSrc: '',
        data: {},
        beforeSend: function(){
            $('.loader').show();
        },
        success: function(resultado){
        tab = '';
        if(resultado){
            response = JSON.parse(resultado);
           $.each(data, function(i, main) {
                //Status
                if(main.status == 1){
                    status = '<td class="text-center"><span class="badge badge-success">Activo</span></td>';
                }else{
                    status = '<td class="text-center"><span class="badge badge-danger">Inactivo</span></td>';
                }
                //Acciones
                acciones = `
                    <td class="text-center">
                        <a type="button" class="btn btn-warning btn-xs" href="${base_url + '/BanMovim/edit/' +  main.id_banmov}"><i class="fa fa-edit"></i></a>
                        <button id="Data" data-id="${main.id_banmov}" data-name="${main.nombre_emp}" data-code = "${main.nombre_emp}" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)"><i class="fa fa-trash"></i></button>
                        <button id="Data" data-id="${main.id_banmov}" data-code = "${main.num_banmov}" type="button" class="btn btn-primary btn-xs" onclick="print_mov(this)" title="Imprimir"><i class="fa-solid fa-print"></i></button>
                    </td>`;
                xfecha = main.fecha_comp.split('-')
                tab += `<tr>
                    <td>${main.id_banmov}</td>
                    <td>${main.nombre_emp}</td>
                    <td>${main.nom_bantmo}</td>
                    <td>${main.bancue}</td>
                    <td class="text-right">${main.num_banmov}</td>
                    <td>${xfecha[2]+'-'+xfecha[1]+'-'+xfecha[0]}</td>
                    <td>${main.id_moneda}</td>
                    ${status}
                    ${acciones}
                </tr>`;
            });
        }
        if(tab){
            document.getElementById('tbody').innerHTML = tab;
        }
        var tblMain = $("#tblTablebanMovim").DataTable({
            aProcessing: true,
            aServerSide: true,
            language: {
                url :`${base_url}/Assets/json/es-ES.json`,
            },
            columnDefs: [
                {
                    targets: 0,
                    visible: false,
                    searchable: false,
                },
            ],
        });
    },
    complete: function(){
        $('.loader').hide();    
    },
    error: function (xhr, status, error) {
        $(".loader").hide();
    }
    })
}
//Copiar producto crear un nuevo producto dfe uno ya existente
function copiarBtn(e){
    let id = e.dataset.id;
    let name = e.dataset.name;
    Swal.fire({
        title: "Desea copiar el producto " + name + " como un nuevo producto?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Copiar",
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            window.location.href = `${base_url}/Productos/edit/`+id+'-N';
             $('#id').val('');
        }
    });
}
//Al seleccionar un Grupo actualizar el  Seletc del Sub-Grupo
$('#id_grupo').on('change', function(e){
    div_loading();
    $("#id_sub_grupo").empty();
    id_grupo = $(this).val();
    const url = `${base_url}/SubGrupos/listar_sub_grupo`;
    $.ajax({
        type: 'POST',
        url: url,
        data: {id_grupo:id_grupo},
        dataSrc: '',
        dataType: 'json',
        beforeSend: function(){
            $('.loader').show();
        },
        success: function(data){
             listar_sub_grupo(id_grupo);
        },
        complete: function(){
            $('.loader').hide();
        },
        error: function(xhr){
            $('.loader').hide();
            console.log(xhr.statusText + ' ' + xhr.responseText);
        }
    })
})