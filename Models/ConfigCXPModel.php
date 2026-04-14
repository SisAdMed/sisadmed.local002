<?php
class ConfigCXPModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT a.id_config, b.nombre_emp nom_empresa, a.status FROM f3999 a INNER JOIN f0011 b on b.id_emp = a.id_emp");
    }
    static function guardar($data){
        return $r = DB::insert('f3999', $data);
    }
     static function actualizar($data, $id){
        return $r = DB::update('f3999', $data, ['id_config' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT a.id_config, b.nombre_emp nom_empresa FROM f3999 a INNER JOIN f0011 b ON b.id_emp = a.id_emp WHERE id_config = {$id}");
        return $r[0];
    }
    static function showrow($id){
        $r = DB::query("SELECT * FROM f3999 a INNER JOIN f0011 b on b.id_emp = a.id_emp WHERE id_config = {$id}");
        return $r[0];
    }
    static function show_config_cxp($id_emp){
        $r = DB::query("SELECT * FROM f3999 WHERE id_emp = {$id_emp}");
        return $r[0];
    }
}