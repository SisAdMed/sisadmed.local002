//Variables
let id_emp = '';
$().ready(function(){
    muestra_oculta('Data');
    $("#id_alm").val('');
    $("#id_ubi").val('');
    $("#id_fab").val('');
    $("#id_prod").val('');
    $("#fec_ini").val(getFirstDateofMonth());
    $('#fec_fin').val(getLastDateofMonth());
    listar_empresas(0, false, 'Todas'); 
    listar_almacenes();
    listar_marcas(0,'id_fab');
    if(!id_emp){
         listar_almacenes_ppal('');
    }
})
//Cargar almacenes dependieno de la empresa
$(document).on('change', '#id_emp', async function(){
    id_emp = $(this).val();
    $('#tblTableConMovInvDet').empty();
    $('#id_alm').empty();
    $('#id_fab').empty();
    if (id_emp) {
        listar_almacenes(id_emp);
        config_fac = await tip_doc_fac(id_emp)
        id_alm_exc = config_fac['id_alm']
        //listar_almacenes_ppal(id_emp, id_alm_exc, id_alm_exc );
    }else{
        listar_almacenes_ppal('');
    }
   
    
})
//Cargar ubicaciones dependiendo del almacen
$(document).on('change', '#id_alm', function(){
    id_emp = $('#id_emp').val();
    $('#id_ubi').empty();
    listar_ubicaciones('id_ubi', 0, 'N', id_emp);
})
//Al seleccionar Ubicacion
$("#id_ubi").on("change", function(){
    $("#tblTableConMovInvDet").empty();
})
//Al seleccioanr marcas
$(document).on('change', '#id_fab', function(){
    $('#tblTableConMovInvDet').empty();
    $('#id_prod').val('');
    $('#nom_prod').val('');
})
//CArgar registros de movimientos
$(document).on('click', '#btn-search', async function(){
    $("#tblTableConMovInvDet").empty();
    validado = true;
    $('.loader').show();
    //Campos necesarios para la consulta
    id_emp = $('#id_emp').val();
    //Validar que los campos engas valroes de busqueda
    /*
    if(!id_emp){
        Swal.fire({
            title: 'Error',
            text: 'Debe especificar una Empresa',
            icon: 'error'
        })
        validado = false;
    }
    */
    id_alm = $('#id_alm').val();
    //Validar que los campos engas valroes de busqueda
    if(!id_alm){
        Swal.fire({
            title: 'Error',
            text: 'Debe especificar un Almacén',
            icon: 'error'
        })
        validado = false;
    }
    //
    fec_ini = $('#fec_ini').val(); 
    fec_fin = $('#fec_fin').val();
    id_ubi = $("#id_ubi").val();
    id_fab = $('#id_fab').val();
    id_prod = $('#id_prod').val();
    //Ya validado los datos necesarios
    const url = `${base_url}/MovInv/conmovinv` 

    const datos = new FormData;
    datos.append('id_emp', id_emp);
    datos.append('id_alm', id_alm);
    datos.append('fec_ini', fec_ini);
    datos.append('fec_fin', fec_fin);
    datos.append("id_ubi", id_ubi );
    datos.append('id_fab', id_fab);
    datos.append('id_prod', id_prod);

    if(validado){
        $('.loader').show();
        const records = await fetch(url, {
            method: 'POST',
            body: datos,
        })
        const data = await records.json();
        if(data){
            $('.loader').show();
            var htmlTags = '';
            var id_prod_previus = '';
            var tsaldo = 0;
            var tot_ent = 0;
            var tot_sal = 0;
            var tenca = false;
            var id_prod;
            var nom_prod;
           
            xfec_ini = new Date(fec_ini)
            //Di anteior a la fecha inciial
            var hr1 = moment(xfec_ini,'DD-MM-YYYY').format('DD-MM-YYYY');
            //new Date(fecha.setUTCDate(fecha.getUTCDate() + dias); 
            $.each(data, function(i, item){
                $('.loader').show();
                //agregar fila de total por producto
                if(id_prod_previus != item.id_prod && tenca && item.id_cot != 0){
                    tenca = false;
                    if (item.id_cot != 0) {
                        htmlTags +=
                            `<tr style="background-color:#00FFFF">` +
                            `<th colspan="2">Producto: ${id_prod}</td>` +
                            `<th colspan="9">${'TOTAL: ' + nom_prod} </td>` +
                            `<th>${nom_fab} </th>` +
                            `<th class="text-right">${tot_ent}</td>` +
                            `<th class="text-right">${tot_sal}</td>` +
                            `<th class="text-right">${tsaldo}</td>` +
                            `</tr>`;
                    }
                }
                //Encabezado de producto
                tfecha = item.fecha_comp.split('-');
                if(id_prod_previus != item.id_prod){
                    tenca = true;
                    tsaldo = parseFloat(item.saldo);
                    tot_ent = 0;
                    tot_sal = 0;
                    id_prod = item.id_prod;
                    nom_prod = item.nom_prod;
                    nom_fab = item.nom_fab;
                    if (item.id_cot == 0) {
                        htmlTags +=
                            `<tr style="background-color:#00FFFF">` +
                            `<th colspan="2">Producto: ${item.id_prod}</td>` +
                            `<th colspan="9">${item.nom_prod}</td>` +
                            `<th>${nom_fab} </th>` +
                            `<th colspan="2" class="text-right">${'SALDO AL: ' + hr1}  </td>` +
                            `<th class="text-right">${tsaldo}</td>` +
                            `</tr>`;
                    }
                }
                //Acumuladores
                tot_ent += parseFloat(item.entradas);
                tot_sal += parseFloat(item.salidas);
                tsaldo += parseFloat(item.entradas);
                tsaldo -= parseFloat(item.salidas);
                //Detalle de producto
                //if (item.num_movinv > 0) {
                if (item.id_cot != 0) {
                    htmlTags +=
                        `<tr>` +
                        `<td>${item.nombre_emp}</td>` +
                        `<td>${tfecha[2] + "-" + tfecha[1] + "-" + tfecha[0]}</td>` +
                        `<td>${item.cod_tmoinv}</td>` +
                        `<td>${item.nom_tmoinv}</td>` +
                        `<td>${item.num_movin}</td>` +
                        `<td>${item.nom_alm}</td>` +
                        `<td>${item.nom_prod}</td>` +
                        `<td>${item.cod_prod}</td>` +
                        `<td>${item.ref_prod}</td>` +
                        `<td>${item.origen}</td>` +
                        `<td>${item.nom_ent}</td>` +
                        `<th>${item.nom_fab} </th>` +
                        `<td class="text-right">${item.entradas}</td>` +
                        `<td class="text-right">${item.salidas}</td>` +
                        `<td class="text-right">${tsaldo}</td>` +
                        `</tr>`;
                }
                id_prod_previus = item.id_prod;
            })
            //Imrimir ultimo total
            //if (item.id_cot != undefined) {
                htmlTags +=
                    `<tr style="background-color:#00FFFF">` +
                    `<th colspan="2">Producto:${id_prod}</td>` +
                    `<th colspan="9">${'TOTAL: ' + nom_prod}</td>` +
                    `<th>${nom_fab} </th>` +
                    `<th class="text-right">${tot_ent}</td>` +
                    `<th class="text-right">${tot_sal}</td>` +
                    `<th class="text-right">${tsaldo}</td>` +
                    `</tr>`;
                if (htmlTags) {
                    document.getElementById('tblTableConMovInvDet').innerHTML = htmlTags;
                }
            //}
        }
    }
    $('.loader').hide();
    muestra_oculta('Data');
})