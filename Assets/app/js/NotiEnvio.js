let item = 0 ;
function agregarDetalleNotiEnvio(){
    item = item + 1;
    var tagHtml =
    '<tr id="fila'+item+'">' +
        '<td><input type="date" class="form-control"></td>' +
        '<td><input type="text" class="form-control"></td>' +
        '<td><select class="form-control"></select></td>' +
    '</tr>';
    $("#TblDetNotEnvio").append(tagHtml);
}