<?php
class RolesModel extends DB
{
    public function __construct()
    {
        parent::__construct();
    }
    static function allRols()
    {
        return $roles = DB::query("SELECT * FROM f0007");
    }
    static  function deleteRol($id){
        return $id = DB::delete('f0007', ['id_rol' => $id], 1);
    }
    static function guardarRol($data)
    {
        return $id = DB::insert('f0007', $data);
    }
    static function editRol($id)
    {
        $rol = DB::query("SELECT * FROM f0007 WHERE id_rol = {$id}");
        return $rol[0];
    }
    static function actualizarRol($id, $data)
    {
        return $res = DB::update('f0007', $data, ['id_rol' => $id]);
    }
    //Listar Roles. Creado por José Vargas el 09-03-2026 a las 11:26:00
    static function listar_roles(){
        $sql = "SELECT * FROM f0007 WHERE status_rol = 1 ORDER BY nombre_rol";
        $r = DB::query($sql);
        return $r;
    }
}
