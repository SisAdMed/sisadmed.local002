//Variables globales
let file;
//Validar campos del formulario
$(function () {
    $("form[name='my_form']").validate({
        rules: {
            name_user: "required",
            last_user: "required",
            code_user: "required",
            email_user: "required",
            id_rol: "required",
            status_user: "required",
            password_user: {
                required: {
                    depends: function(element){
                        return $("#id").val() == "";
                    }
                }
            },
        },
        messages: {}
    });
    //Cargar el index
    form = $("form").attr("id");    
    if (form === undefined) {
        initUsuarios();
    } else {
        //Cuando es un registro nuevo
        id = $("#id").val();
        if (id) {
            show_row(id);
        } else {
            dat_form_new();
        }
    }   
});
//Nuevo Registro
function dat_form_new() {
    listar_status(1, "status_user");
    listar_roles(0, "id_rol");
}
//Consultar Registro
function show_row(id) {
    const url = `${base_url}/Usuarios/show_row`
    //Ajax para 
    $.ajax({
        url: url,
        method: 'POST',
        dataSrc: '',
        data: {id: id},
        dataType: 'json',
        beforeSend: function() {
            loader.show();
        },
        complete: function() {
            loader.hide();
        },
        error: function(PDOException) {
            loader.hide();
            console.log('Ha ocurrido el siguiente error:', PDOException.responseText)
        },
        success: function(data) {
            if (data) {
                $("#name_user").val(decodeEntities(data.name_user));
                $("#last_user").val(decodeEntities(data.last_user));
                $("#code_user").val(decodeEntities(data.code_user));
                $("#code_user").prop("readonly", true);
                $("#email_user").val(decodeEntities(data.email_user));
                $("#last_login").val(data.last_login ? moment(data.last_login).format('DD-MM-YYYY HH:mm:ss') : '');
                listar_roles(data.id_rol, "id_rol");
                listar_status(data.status_user, "status_user");
                if(data.appdis == 1){
                    $("#appdis").prop('checked', true);
                }
                if (data.administrator == 1) {
                    $("#administrator").prop('checked', true);
                }
                $("#imgPreview").remove();
                if (data.photo_user) { 
                    $("#cardimg1").append(`<img id="imgPreview" name="imgPreview" src="${base_url}/Assets/img/users/${data.photo_user}" width="200" height="200" id="imgPreview">`);
                }
            }
        },
    });
}
//Crear el Código de Usuario
$("#name_user, #last_user").on("keyup", function () {
    var name = $("#name_user").val().trim();
    var last = $("#last_user").val().trim();
    last = last.length > 0 ? last : ' ';
    if (name && last) {
        var code = (name + '.' + last).toLowerCase().replace(/\s+/g, '');
        $("#code_user").val(code);
    }else{
        $("#code_user").val("");
    }
});
//Función para recargar el datatable
$(".refresh-button").on("click", function () {
    tableIndex.ajax.reload(null, false);
});
//Eliminar un registro
$("#tblIndexMain").on("click", ".btn-delete-index", function () {
    var id = $(this).data("id"); // Obtiene el ID del registro
    var code = $(this).data("code");
    var name = $(this).data("name");
    const url = `${base_url}/Usuarios/destroy`;
    // Mostrar el cuadro de diálogo de confirmación
    Swal.fire({
        title: "¿Está usted seguro de eliminar este registro?",
        text: "¡No podrá revertir esta eliminación!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, borrar este registro!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url, // URL de tu script de eliminación en el servidor
                method: "POST",
                data: { id: id, code: code, name: name }, // Envía el ID del registro a eliminar
                dataType: "json",
                beforeSend: function () {
                    loader.show();
                },
                complete: function () {
                    loader.hide();
                },
                success: function (resulta) {
                    // La respuesta del servidor debe indicar si fue exitoso
                    console.log(resulta);
                    
                    Swal.fire({
                        icon: `${resulta.icon}`,
                        title: `${resulta.title}`,
                        text: `${resulta.msg}`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Recarga el DataTable
                            tableIndex.draw(); // El 'false' previene que se reajuste la paginación a la página 1.
                            tableIndex.ajax.reload(null, false);
                        }
                    });
                },
                error: function (xhr, status, error) {
                    loader.hide();
                    var errorMessage = xhr.status + ": " + xhr.statusText;
                    console.log(errorMessage, status, error);
                },
            });
        }
    });
});
//Guardar el formulario
$("#my_form").on("submit", function (e) {
    e.preventDefault();
    if ($(this).valid()) {
        $("#btnok").prop('disabled', true);
        var formData = new FormData(this);        
        const url = `${base_url}/Usuarios/store`;
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            contentType: false, // Importante: evita que jQuery establezca el tipo de contenido
            processData: false,
            dataType: "json",
            beforeSend: function () {
                loader.show();
            },
            success: function (data) {
                Swal.fire({
                    title: data.title,
                    text: data.msg,
                    icon: data.icon,
                }).then((result) => {
                    if (data.success != 0) {                       
                        window.location.href = `${base_url}/Usuarios`;
                    }
                });
            },
            complete: function () {
                loader.hide();
            },
            error: function (PDOException) {
                loader.hide();
                var errorMessage = PDOException;
                console.log(errorMessage);
            },
        });
    } else {
        $("btnok").prop('disabled', false);
        return false;
    }
});

