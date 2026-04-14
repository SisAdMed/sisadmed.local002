<?php
class Paises extends Controller
{
    public function __construct()
    {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(26);
    }
    public function index()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = PaisesModel::all();

        $this->views->getView($this, "index", [
            'page_name' => "Paises",
            'function_js' => "Paises.js",
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo()
    {
        $this->views->getView($this, "nuevo", [
            'page_name' => "Paises",
            'function_js' => "Paises.js"
        ]);
    }
    public function edit($id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = PaisesModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Paises');
                }
                //mostrar registro
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el registro " . $r['nombre_pais'],
                    'function_js' => "Paises.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Paises');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Paises');
    }
    public function store()
    {
        if (empty($_POST['id'])) {
            if (Permisos::create()) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    try {
                        //validar formulario
                        $val = new Validations();
                        $val->name('Código')->value(limpiar($_POST['codigo_pais']))->min(2)->max(2)->required();
                        $val->name('Nombre')->value(limpiar($_POST['nombre_pais']))->required();
                        $val->name('Código ISO')->value(limpiar($_POST['iso_pais']))->min(3)->max(3)->required();
                        if ($val->isSuccess()) {
                            //guardar registro
                            $data = [
                                'codigo_pais' => limpiar($_POST['codigo_pais']),
                                'nombre_pais' => limpiar($_POST['nombre_pais']),
                                'iso_pais' => limpiar($_POST['iso_pais']),                                
                                'create_user' => $_SESSION['id_user']
                            ];
                            $id = PaisesModel::guardar($data);
                            Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['nombre_pais'], $id));
                            header('Location:' . base_url . '/Paises');
                        } else {
                            Alertas::new($val->getErrors(), 'danger');
                            header('Location:' . base_url . '/Paises/nuevo');
                        }
                    } catch (\PDOException $e) {
                        Alertas::new($e->getMessage(), 'danger');
                        header('Location:' . base_url . '/Paises/nuevo');
                    }
                }
            }
        }else{
            if (Permisos::updater()) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    try {
                        //validar formulario
                     $val = new Validations();
                     $val->name('Código')->value(limpiar($_POST['codigo_pais']))->min(2)->max(2)->required();
                        $val->name('Nombre')->value(limpiar($_POST['nombre_pais']))->required();
                        $val->name('Código ISO')->value(limpiar($_POST['iso_pais']))->min(3)->max(3)->required();               
                     if ($val->isSuccess()) {
                            //guardar registro
                        $data = [
                            'codigo_pais' => limpiar($_POST['codigo_pais']),
                            'nombre_pais' => limpiar($_POST['nombre_pais']),
                            'iso_pais' => limpiar($_POST['iso_pais']),                                
                            'create_user' => $_SESSION['id_user']
                        ];
                        $id = PaisesModel::actualizar($_POST['id'], $data);
                        Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['nombre_pais'], $_POST['id']));
                        header('Location:' . base_url . '/Paises');
                    } else {
                        Alertas::new($val->getErrors(), 'danger');
                        header('Location:' . base_url . '/Paises/edit');
                    }
                } catch (\PDOException $e) {
                    Alertas::new($e->getMessage(), 'danger');
                    header('Location:' . base_url . '/Paises/edit');
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
        $ide = PaisesModel::borrar($id);
        $dataJson = [
            'status' => true,
            'msg' => sprintf('El rol %s se ha eliminado correctamente', $_POST['name'])
        ];
    }
    echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
}
