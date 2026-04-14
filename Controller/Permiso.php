<?php
class Permiso extends Controller
{
    public function __construct()
    {
        Auth::noAuth();
        parent::__construct();
    }
    public function index($id)
    {
        $rolId = intval(limpiar($id));
        if ($rolId > 0) {
            $paginas = PermisoModel::paginas();
            $permisosbyRoles = PermisoModel::permisosbyRoles($rolId);
            $roles = PermisoModel::roles($rolId);
            $permisos = ['c' => 0, 'r' => 0, 'u' => 0, 'd' => 0];
            $accesosbyRoles = ['id_rol' => $rolId, 'rol' => $roles['nombre_rol']];

            if (empty($permisosbyRoles)) {
                for ($i = 0; $i < count($paginas); $i++) {
                    $paginas[$i]['accesos'] = $permisos;
                }
            } else {
                for ($i = 0; $i < count($paginas); $i++) {
                    $permisos = ['c' => 0, 'r' => 0, 'u' => 0, 'd' => 0,];
                    if (isset($permisosbyRoles[$i])) {
                        $permisos = ['c' => $permisosbyRoles[$i]['c'], 'r' => $permisosbyRoles[$i]['r'], 'u' => $permisosbyRoles[$i]['u'], 'd' => $permisosbyRoles[$i]['d']];
                    }
                    $paginas[$i]['accesos'] = $permisos;
                }
            }
            $accesosbyRoles['paginas'] = $paginas;
        }
        $this->views->getView($this, "index", [
            'page_name' => "Accesos por rol",
            'function_js' => "Permiso.js",
            'accesobyRole' => $accesosbyRoles
        ]);
    }
    public function store()
    {
        if ($_SESSION['user_data']['id_rol'] == 1) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $idRol = intval($_POST['idRol']);
                $paginas = $_POST['paginas'];
                PermisoModel::deletePermisos($idRol);
                foreach ($paginas as $page) {
                    $idpage = $page['id_menu'];
                    $c = empty($page['c']) ? 0 : 1;
                    $r = empty($page['r']) ? 0 : 1;
                    $u = empty($page['u']) ? 0 : 1;
                    $d = empty($page['d']) ? 0 : 1;
                    $nuevoCambio = PermisoModel::insertPermisos([
                        'id_rol' => $idRol,
                        'id_menu' => $idpage,
                        'c' => $c,
                        'r' => $r,
                        'u' => $u,
                        'd' => $d,
                        'create_user' => $_SESSION['id_user'],
                    ]);
                }
                if ($nuevoCambio > 0) {
                    Alertas::new('Registro(s) guardado(s) correctamente.', 'success');
                    header('Location:' . base_url . '/Permiso/index/' . $idRol);
                } else {
                    Alertas::new('Error al guardar los permisos.', 'danger');
                    header('Location:' . base_url . '/Permiso/index/' . $idRol);
                }
            }
        } else {
            Alertas::new('No tiene permiso para realizar esta acción.', 'warning');
            header('Location:' . base_url . '/Roles');
        }
    }
}
