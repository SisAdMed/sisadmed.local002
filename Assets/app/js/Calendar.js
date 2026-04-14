/*
* Funciones de Calendar
* Copyright 2026
* 02-02-2026 Creación de Archivo José Vargas 10:40:00
*/

// AL Iniciar la aplicación
$().ready(function () {
    //Validaciones
    $("form#my_form").validate({
        ignore: null,
        rules: {
            title: "required",
            year: {
                required: true,
                minlength: 4,
                maxlength: 4,
            },
            background: "required",
            text: "required",
            description: "required",
            status: "required",
        },
        messages: {
            title: "Debe especificar un título",
            year: {
                required: "Debe especificar un año",
                minlength: "Debe especificar al menos 4 carácteres",
                maxlength: "Debe especificar máximo 4 carácteres",
            },
            background: "Debe especificar un color de fondo",
            text: "Debe especificar un color de texto",
            description: "Debe especificar una descripción",
            status: "Debe especificar un Status",
        },
    })
    //Cargar el Index   
    form = $("form").attr("id");
    if (form === undefined) {
        initCalendar();
    } else {
        id = $("#id").val();
        if (id) {
            show_row(id);
        } else {
            dat_form_new();
        }
    }
    //    
    ini_events($('#external-events div.external-event'))
});
function ini_events(ele) {
    ele.each(function () {

        // create an Event Object (https://fullcalendar.io/docs/event-object)
        // it doesn't need to have a start or end
        var eventObject = {
            title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 1070,
            revert: true, // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        })
    });
}
/* ADDING EVENTS */
let currColor = '#3c8dbc' //Red by default
// Color chooser button
$('#color-chooser > li > a').click(function (e) {
    e.preventDefault()
    // Save color
    currColor = $(this).css('color')
    // Add color effect to button
    $('#add-new-event').css({
        'background-color': currColor,
        'border-color': currColor
    })
})
$("#add-new-event").on("click", function (e) { 
    // Create events
    e.preventDefault()
    // Get value and make sure it is not null
    var val = $('#new-event').val()
    if (val.length == 0) {
        return
    }

    // Create events
    var event = $('<div />')
    event.css({
        'background-color': currColor,
        'border-color': currColor,
        'color': '#fff'
    }).addClass('external-event text-xs')
    event.text(val)
    $('#external-events').prepend(event)

    // Add draggable funtionality
    ini_events(event)

    // Remove event from text input
    $('#new-event').val('')
})
//Nuevo Registro
function dat_form_new() {
    $("#all_day").prop("checked", false);
    listar_status("1", "status");
}
//Consultar registro
function show_row(id) {
    const url = `${base_url}/Calendar/show_row`;
    var formData = $(this).serialize();
    //Ajax para 
    $.ajax({
        url: url,
        method: 'POST',
        dataSrc: '',
        data: { id: id },
        dataType: 'json',
        beforeSend: function () {
            loader.show();
        },
        complete: function () {
            loader.hide();
        },
        error: function (PDOException) {
            loader.hide();
            console.log('Ha ocurrido el siguiente error:', PDOException.responseText)
        },
        success: function (data) {
            console.log(data);
            $("#title").val(decodeHTMLEntities(data.title));
            $("#year").val(data.year);
            $("#background").val(data.background);
            $("#text").val(data.text);
            $("#description").val(decodeHTMLEntities(data.description));    
            if (data.all_day == 1) {
                $("#all_day").prop("checked", true);
            } else {
                $("#all_day").prop("checked", false);
            }
            listar_status(data.status, "status", true);
        },
    });
}
//Función para recargar el datatable
$(".refresh-button").on("click", function () {
    tableIndex.ajax.reload(null, false);
});
//Eliminar un registro
$("#tblIndexMain").on("click", ".btn-delete-index", function () {
    var recordId = $(this).data("id"); // Obtiene el ID del registro
    var recordCode = $(this).data("code"); // Obtine el Tipo Doc
    var recordName = $(this).data("name"); // Obtine el nombre
    var descrip = `¿Está seguro de eliminar el Tipo de Documento ${recordCode} ${recordName}?.`
    Swal.fire({
        title: descrip,
        text: "¡No podrá revertir esta eliminación!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, borrar este registro!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            const url = `${base_url}/ConcepCXC/destroy`;
            $.ajax({
                url: url, // URL de tu script de eliminación en el servidor
                type: "POST",
                data: { id: recordId, recordCode: recordCode, recordName: recordName },
                dataType: "json",
                beforeSend: function () {
                    loader.show();
                },
                complete: function () {
                    loader.hide();
                },
                success: function (resulta) {
                    // La respuesta del servidor debe indicar si fue exitoso
                    Swal.fire({
                        icon: `${resulta.icon}`,
                        title: `${resulta.title}`,
                        text: `${resulta.msg}`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Recarga el DataTable
                            //tableIndex.draw(false); // El 'false' previene que se reajuste la paginación a la página 1.
                            tableIndex.ajax.reload(null, false);
                        }
                    });
                },
                error: function (xhr, status, error) {
                    loader.hide();
                    alert("Hubo un error en la solicitud.");
                    console.error(xhr.responseText);
                },
            });
        }
    });
});
//Guardar y/o Actualizarregistro
$("#my_form").on("submit", function (e) {
    e.preventDefault();
    if ($(this).valid()) {
        var formData = $(this).serialize();
        const url = `${base_url}/Calendar/store`;
        //Ajax para Guardar y/o Actualizar
        $.ajax({
            url: url,
            method: 'POST',
            dataSrc: '',
            data: formData,
            dataType: 'json',
            beforeSend: function () {
                loader.show();
            },
            complete: function () {
                loader.hide();
            },
            error: function (PDOException) {
                loader.hide();
                console.log('Ha ocurrido el siguiente error:', PDOException.responseText)
            },
            success: function (data) {
                Swal.fire({
                    title: data.title,
                    text: data.msg,
                    icon: data.icon,
                }).then((result) => {
                    if (data.icon != "error") {
                        window.location.href = `${base_url}/Calendar`;
                    }
                })
            },
        });
    } else {
        return false;
    }
})
$("#year").on("keyup", function () {

    /* initialize the external events
    -----------------------------------------------------------------*/
    function ini_events(ele) {
        ele.each(function () {

            // create an Event Object (https://fullcalendar.io/docs/event-object)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()) // use the element's text as the event title
            }

            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject)

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 1070,
                revert: true, // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            })

        })
    }

    ini_events($('#external-events div.external-event'))

    var year = $(this).val();
    var calendar = FullCalendar.Calendar;
    var Draggable = FullCalendar.Draggable;

    var containerEl = document.getElementById('external-events');
    var calendarEl = document.getElementById('calendar');

    var fecha = new Date();
    var dd = String(fecha.getDate()).padStart(2, '0');
    var mm = String(fecha.getMonth() + 1).padStart(2, '0'); 
    var strFecha = `${year}-${mm}-${dd}`;
    $(".calendar-view").hide();
    calendarEl.innerHTML = ''; // Limpia el contenido previo
    if (year.length === 4) {        
        $(".calendar-view").show();       

        new Draggable(containerEl, {
            itemSelector: '.external-event',
            eventData: function (eventEl) {
                return {
                    title: eventEl.innerText,
                    backgroundColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                    borderColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                    textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color'),
                };
            }
        });

        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridYear, dayGridMonth, timeGridWeek, timeGridDay, listMonth',
            },
            locale: 'es',
            initialDate: strFecha,            
            themeSystem: 'bootstrap',
            initialView: 'dayGridMonth',
            navLinks: true, // Permite hacer clic en los días para navegar
            dayMaxEvents: true, // Permite mostrar un enlace "más" si hay demasiados eventos
            nowIndicator: true, // Muestra un indicador para la fecha actual
            businessHours: {
                // Define el horario laboral (opcional)
                daysOfWeek: [1, 2, 3, 4, 5], // Lunes a Viernes
                startTime: '08:00', // Hora de inicio
                endTime: '16:30', // Hora de fin
            },
            editable: true,
            selectable: true,
            droppable: true, // this allows things to be dropped onto the calendar !!!
            // Evento al soltar un evento externo
            drop: function (info) {
                // Si quieres eliminar el evento externo después de soltarlo, descomenta la siguiente línea
                info.draggedEl.parentNode.removeChild(info.draggedEl);
                const evento = info.draggedEl.innerText;
                const fecha = info.dateStr;
                //Guardar el evento en la base de datos
                guardarCumpleanos(evento, fecha);
            },
            
        })
        calendar.render();
        //calendar.updateSize(); // Asegura el tamaño correcto    
    }
});
//Funcion para guardar el evento en la base de datos
function guardarCumpleanos(evento, fecha) {
    const url = `${base_url}/Calendar/store`;
    id = $("#id").val() ;
    $.ajax({
        url: url,   
        method: 'POST',
        dataSrc: '',
        data: { evento: evento, fecha: fecha }, 
        dataType: 'json',
        beforeSend: function () {
            loader.show();
        },
        complete: function () {
            loader.hide();  
        },
        error: function (PDOException) {
            loader.hide();
            console.log('Ha ocurrido el siguiente error:', PDOException.responseText)
        },
        success: function (data) {
            Swal.fire({     
                title: data.title,
                text: data.msg,
                icon: data.icon,
            })
        }   
    });
}

