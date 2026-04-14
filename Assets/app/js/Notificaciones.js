$(document).ready(function() {
    //Llenar Tabla del Index
    populatetableindex();
})
function populatetableindex(){
    try{
        var table = $("#tblTable");
        //Vaciar registros en caso de que hayan
        table.find('tbody').html('');
        var url = `${base_url}/Notificaciones/all_index`;
        console.log(url);
        setTimeout(() => {
            $.ajax({
                url: url,
                dataType: 'json',
                success:function(resp){
                    if(resp.length > 0){
                        var i = 1;
                        Object.keys(resp).map(k =>{
                            var tr = $('<tr>');
                            tr.append('<td>'+resp[k].id_fgenmsg+'</td>');
                            tr.append('<td>'+resp[k].tipo_fgenmsgcol+'</td>');
                            tr.append('<td>'+resp[k].fecha_genmsgcol+'</td>');
                            tr.append('<td>'+resp[k].title+'</td>');
                            tr.append('<td>'+resp[k].message+'</td>');
                            tr.append('<td>'+resp[k].status+'</td>');
                            tr.append('<td>'+resp[k].status+'</td>');
                            table.find('tbody').append(tr);
                        });
                    }
                }
            });
        }, 500);
    }catch(error){
        console.log(error);
    }
}