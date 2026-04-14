<?php
class Menu extends Controller
{
    public function __construct()
    {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(14);
    }
    public function index()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $menu = MenuModel::all();

        $this->views->getView($this, "index", [
            'page_name' => "Menú",
            'function_js' => "Menu.js",
            'menu' => to_obj($menu)
        ]);
    }
    public function nuevo()
    {
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo menú",
            'function_js' => "Menu.js"
        ]);
    }
    public function edit($id)
    {
        if (Permisos::read()) {
            $idR = intval(limpiar($id));
            if ($idR > 0) {
                $r = MenuModel::edit($idR);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Menu');
                }
                //mostrar registro
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el menú " . $r['nombre_menu'],
                    'function_js' => "Menu.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Menu');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Menu');
    }
    public function store()
    {
        if (empty($_POST['id'])) {
            if (Permisos::create()) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    try {
                        //validar formulario
                        $val = new Validations();
                        $val->name('Nombre de menú')->value(limpiar($_POST['nombre_menu']))->required();
                        $val->name('Descripción de menú')->value(limpiar($_POST['desc_menu']))->min(5)->required();
                        $val->name('Padre menú')->value(limpiar($_POST['padre_menu']));
                        $val->name('Page menú')->value(limpiar($_POST['page_menu']));
                        $val->name('Icono menú')->value(limpiar($_POST['icono_menu']));
                        $val->name('Orden menú')->value(limpiar($_POST['orden_menu']));
                        $val->name('Estado menú')->value(limpiar($_POST['status_menu']))->required();
                        if ($val->isSuccess()) {
                            //guardar registro
                            $data = [
                                'nombre_menu' => limpiar($_POST['nombre_menu']),
                                'desc_menu' => limpiar($_POST['desc_menu']),
                                'padre_menu' => limpiar($_POST['padre_menu']),
                                'page_menu' => limpiar($_POST['page_menu']),
                                'icono_menu' => limpiar($_POST['icono_menu']),
                                'orden_menu' => limpiar($_POST['orden_menu']),
                                'status_menu' => limpiar($_POST['status_menu']),
                                'create_user' => $_SESSION['id_user']
                            ];
                            $id = MenuModel::guardar($data);
                            Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['nombre_menu'], $id));
                            header('Location:' . base_url . '/Menu');
                        } else {
                            Alertas::new($val->getErrors(), 'danger');
                            header('Location:' . base_url . '/Menu/nuevo');
                        }
                    } catch (\PDOException $e) {
                        Alertas::new($e->getMessage(), 'danger');
                        header('Location:' . base_url . '/Menu/nuevo');
                    }
                }
            }
        }else{
            if (Permisos::updater()) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    try {
                        //validar formulario
                        $val = new Validations();
                        $val->name('Nombre de menú')->value(limpiar($_POST['nombre_menu']))->required();
                        $val->name('Descripción de menú')->value(limpiar($_POST['desc_menu']))->min(5)->required();
                        $val->name('Padre menú')->value(limpiar($_POST['padre_menu']));
                        $val->name('Page menú')->value(limpiar($_POST['page_menu']));
                        $val->name('Icono menú')->value(limpiar($_POST['icono_menu']));
                        $val->name('Orden menú')->value(limpiar($_POST['orden_menu']));
                        $val->name('Estado menú')->value(limpiar($_POST['status_menu']))->required();
                        if ($val->isSuccess()) {
                            //guardar registro
                           $data = [
                                'nombre_menu' => limpiar($_POST['nombre_menu']),
                                'desc_menu' => limpiar($_POST['desc_menu']),
                                'padre_menu' => limpiar($_POST['padre_menu']),
                                'page_menu' => limpiar($_POST['page_menu']),
                                'icono_menu' => limpiar($_POST['icono_menu']),
                                'orden_menu' => limpiar($_POST['orden_menu']),
                                'status_menu' => limpiar($_POST['status_menu']),
                                'modify_user' => $_SESSION['id_user']
                            ];
                            $id = MenuModel::actualizar($_POST['id'], $data);
                            Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['nombre_menu'], $_POST['id']));
                            header('Location:' . base_url . '/Menu');
                        } else {
                            Alertas::new($val->getErrors(), 'danger');
                            header('Location:' . base_url . '/Menu/edit');
                        }
                    } catch (\PDOException $e) {
                        Alertas::new($e->getMessage(), 'danger');
                        header('Location:' . base_url . '/Menu/edit');
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
            $ide = MenuModel::borrar($id);
            $dataJson = [
                'status' => true,
                'msg' => sprintf('El registro %s se ha eliminado correctamente', $_POST['name'])
            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
}
