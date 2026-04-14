<?php
class PermisoModel extends DB
{
    public function __construct()
    {
        parent::__construct();
    }
    static function paginas()
    {
        return $paginas = DB::query("SELECT * FROM f0001 WHERE status_menu <> 0");
    }
    static function permisosbyRoles($idRol)
    {
        return $permisoByRole = DB::query("SELECT * FROM f0008 WHERE id_rol = {$idRol}");
    }
    static function roles($idRol)
    {
        $roles = DB::query("SELECT * FROM f0007 WHERE id_rol = {$idRol}");
        return $roles[0];
    }
    static function deletePermisos($idRol)
    {
        return $roles = DB::query("DELETE FROM f0008 WHERE id_rol = {$idRol}");
    }
    static function insertPermisos($data)
    {
        return DB::insert('f0008', $data);
    }
}
