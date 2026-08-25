<?php
class ConfigCXCModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT a.id_config, b.nombre_emp nom_empresa, a.status FROM f6999 a INNER JOIN f0011 b on b.id_emp = a.id_emp");
    }
    static function guardar(array $data){
        return $r = DB::insert('f6999', $data);
    }
     static function actualizar(array $data, int $id){
        return $r = DB::update('f6999', $data, ['id_config' => $id]);
    }
    static function edit(int $id){
        $r = DB::query("SELECT a.id_config, b.nombre_emp nom_empresa FROM f6999 a INNER JOIN f0011 b ON b.id_emp = a.id_emp WHERE id_config = {$id}");
        return $r[0];
    }
    static function show_row(int $id){
        $r = DB::query("SELECT * FROM f6999 a INNER JOIN f0011 b on b.id_emp = a.id_emp WHERE id_config = {$id}");
        return $r[0];
    }
    static function show_config_cxc(int $id_emp){
        $r = DB::query("SELECT * FROM f6999 WHERE id_emp = {$id_emp}");
        return $r[0];
    }
}