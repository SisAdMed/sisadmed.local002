<?php
class Roles extends Controller
{
    public function __construct()
    {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(ROLES);
    }
    public function index()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $roles = RolesModel::allRols();

        $this->views->getView($this, "index", [
            'page_name' => "Roles",
            'function_js' => "Roles.js",
            'roles' => to_obj($roles)
        ]);
    }
    public function nuevo()
    {
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo rol",
            'function_js' => "Roles.js"
        ]);
    }
    public function edit($id)
    {
        if (Permisos::read()) {
            $idRol = intval(limpiar($id));
            if ($idRol > 0) {
                $rol = RolesModel::editRol($idRol);
                if (empty($rol)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Roles');
                }
                //mostrar registro
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el registro " . $rol['nombre_rol'],
                    'function_js' => "Roles.js",
                    'rol' => to_obj($rol)
                ]);
            } else {
                header('Location:' . base_url . '/Roles');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Roles');
    }
    public function store()
    {
        if (empty($_POST['id'])) {
            if (Permisos::create()) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    try {
                        //validar formulario
                        $val = new Validations();
                        $val->name('Nombre del rol')->value(limpiar($_POST['nombre_rol']))->min(5)->required();
                        $val->name('Estado del rol')->value(limpiar($_POST['selstatus']))->required();
                        if ($val->isSuccess()) {
                            //guardar registro
                            $data = [
                                'nombre_rol' => limpiar($_POST['nombre_rol']),
                                'status_rol' => limpiar($_POST['selstatus']),
                                'create_user' => $_SESSION['id_user']
                            ];
                            $id = RolesModel::guardarRol($data);
                            Alertas::new(sprintf('El rol %s se ha creado exitosamente con el id %s', $data['nombre_rol'], $id));
                            header('Location:' . base_url . '/Roles');
                        } else {
                            Alertas::new($val->getErrors(), 'danger');
                            header('Location:' . base_url . '/Roles/nuevo');
                        }
                    } catch (\PDOException $e) {
                        Alertas::new($e->getMessage(), 'danger');
                        header('Location:' . base_url . '/Roles/nuevo');
                    }
                }
            }
        }else{
            if (Permisos::updater()) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    try {
                        //validar formulario
                        $val = new Validations();
                        $val->name('Nombre del rol')->value(limpiar($_POST['nombre_rol']))->min(5)->required();
                        $val->name('Estado del rol')->value(limpiar($_POST['selstatus']))->required();
                        if ($val->isSuccess()) {
                            //guardar registro
                            $data = [
                                'nombre_rol' => limpiar($_POST['nombre_rol']),
                                'status_rol' => limpiar($_POST['selstatus']),
                                'modify_user' => $_SESSION['id_user']
                            ];
                            $id = RolesModel::actualizarRol($_POST['id'], $data);
                            Alertas::new(sprintf('El rol %s se ha modificado exitosamente con el id %s', $data['nombre_rol'], $id));
                            header('Location:' . base_url . '/Roles');
                        } else {
                            Alertas::new($val->getErrors(), 'danger');
                            header('Location:' . base_url . '/Roles/edit');
                        }
                    } catch (\PDOException $e) {
                        Alertas::new($e->getMessage(), 'danger');
                        header('Location:' . base_url . '/Roles/edit');
                    }
                }
            }
        }
    }
    public function destroy()
    {
        $dataJson = [];
        if (empty($_POST['id'])) {
            $dataJson = [
                'status' => false,
                'msg' => 'No se recibieron los datos'
            ];
        } else {
            $id = intval(limpiar($_POST['id']));
            $ide = RolesModel::deleteRol($id);
            $dataJson = [
                'status' => true,
                'msg' => sprintf('El rol %s se ha eliminado correctamente', $_POST['name'])
            ];
        }        
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
    //Listar Roles
    public function listar_roles(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = RolesModel::listar_roles();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}
