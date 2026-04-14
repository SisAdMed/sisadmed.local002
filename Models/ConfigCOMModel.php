<?php
class ConfigCOMModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT a.id_comcfg, b.nombre_emp nom_empresa, a.status FROM f8999 a INNER JOIN f0011 b on b.id_emp = a.id_emp");
    }
    static function guardar($data){
        return $r = DB::insert('f8999', $data);
    }
     static function actualizar($data, $id){
        return $r = DB::update('f8999', $data, ['id_comcfg' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT a.id_comcfg, b.nombre_emp nom_empresa FROM f8999 a INNER JOIN f0011 b ON b.id_emp = a.id_emp WHERE id_comcfg = {$id}");
        return $r[0];
    }
    static function showrow($id){
        $r = DB::query("SELECT * FROM f8999 a INNER JOIN f0011 b on b.id_emp = a.id_emp WHERE id_comcfg = {$id}");
        return $r[0];
    }
    static function show_config_fac($id){
        $r = DB::query("SELECT * FROM f8999 WHERE id_comcfg = {$id}");
        return $r[0];
    }
}