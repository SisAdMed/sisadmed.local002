//Funciones Generales JavaScript
//Usuarios Creado por José Vargas el 09-03-2026 a las 10:05:00
function initUsuarios() { 
    const title = "Usuarios";
    const origen = "Usuarios";
    const id_menu = 3;
    get_permiso(id_menu);
    IndexDataTable(origen, tblIndexMain, title, [
        {
            data: null,
            title: "Acciones",
            className: "text-center",
            render: function (data, type, row) {
                var t_menu = "";
                if (permisos_cre == 1 && permisos_cre == 1) {
                    t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id_user}"><i class="fa fa-edit"></i></a>     `;
                }
                if (permisos_del == 1) {
                    t_menu += `<button id="Data" data-id="${row.id_user}" data-name="${row.name_user + ' ' + row.last_user}" data-code = "${row.id_user}" type="button" class="btn btn-danger btn-xs btn-delete-index"><i class="fa fa-trash"></i></button>`;
                }
                return t_menu;
            },
        },
        { data: "id_user", title: "ID", className: "text-right" },
        { data: "code_user", title: "Usuario" },
        { data: "name_user", title: "Nombre" },
        { data: "last_user", title: "Apellido" },
        { data: "nombre_rol", title: "Rol" },
        {
            data: "last_login", title: "Último acceso", className: "text-center",
            render: function (data, type, row) {
                return row.last_login ? moment(data).format('DD-MM-YYYY HH:mm:ss') : 'N/A';
            }
        },
        { data: "email_user", title: "Correo" },
        { data: "photo_user", title: "Foto", className: "text-center",
            render: function (data, type, row) {
                if (data) {
                    return `<img src="${base_url}/Assets/img/users/${data}" alt="Foto" class="img-circle" width="20" height="20">`;
                } else {
                    return '<img src="' + base_url + '/Assets/img/users/default.png" alt="Foto" class="img-circle" width="20" height="20">';
                }
            }
        },  
        {
            data: null,
            title: "Status",
            className: "text-center",
            render: function (data, type, row) {
                if (row.status_user == 1) {
                    return '<span class="badge badge-success">Activo</span>';
                } else if (row.status_user == 0) {
                    return '<span class="badge badge-danger">Inactivo</span>';
                } else if (row.status_user == 9) {
                    return '<span class="badge badge-warning">Por aprobar</span>';
                }
            },
        },
    ]);
}
//Calendar
function initCalendar() { 
    const title = "Calendario";
    const origen = "Calendar";
    const id_menu = 194;
    get_permiso(id_menu);
    IndexDataTable(origen, tblIndexMain, title, [
        {
            data: null,
            title: "Acciones",
            className: "text-center",
            render: function (data, type, row) {
                var t_menu = "";
                if (permisos_cre == 1 && permisos_cre == 1) {
                    t_menu += `<a type="button" class="btn btn-warning btn-xs" href="${base_url}/${origen}/edit/${row.id}"><i class="fa fa-edit"></i></a>     `;
                }
                if (permisos_del == 1) {
                    t_menu += `<button id="Data" data-id="${row.id}" data-name="${row.title}" data-code = "${row.id}" type="button" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></button>`;
                }
                return t_menu;
            },
        },
        { data: "id", title: "ID", className: "text-cright" },
        { data: "title", title: "Título" },
        { data: "year", title: "Año", className: "text-center" },
        {
            data: null,
            title: "Todo el día.",
            className: "text-center",
            render: function (data, type, row) {
                if (row.all_day == 1) {
                    return '<input type="checkbox" checked disabled></input>';
                } else {
                    return '<input type="checkbox" unchecked disabled></input>';
                }
            },
        },
        {
            data: null,
            title: "Status",
            className: "text-center",
            render: function (data, type, row) {
                if (row.status == 1) {
                    return '<span class="badge badge-success">Activo</span>';
                } else if (row.status == 0) {
                    return '<span class="badge badge-danger">Inactivo</span>';
                } else if (row.status == 9) {
                    return '<span class="badge badge-warning">Por aprobar</span>';
                }
            },
        },
    ]);
}
