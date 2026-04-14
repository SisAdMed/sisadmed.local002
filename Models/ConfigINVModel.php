<?php
/**
 * Clase para el manejo de las transacciones de la tabla de Configuración de Inventarios
 * Tabla: f49999
 * Creado por José Vargas
 * El 12-05-2024
 */
class ConfigINVModel extends DB {
    function __construct() {
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT a.id_config, b.nombre_emp, a.status FROM f49999 a INNER JOIN f0011 b on b.id_emp = a.id_emp");
    }
    static function guardar($data){
        return $r = DB::insert('f49999', $data);
    }
    static function actualizar($data, $id){
        return $r = DB::update('f49999', $data, ['id_config' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT a.id_config, b.nombre_emp, a.status FROM f49999 a INNER JOIN f0011 b on b.id_emp = a.id_emp WHERE id_config = {$id}");
        return $r[0];
    }
    static function showrow($id){
        $r = DB::query("SELECT * FROM f49999 a INNER JOIN f0011 b on b.id_emp = a.id_emp WHERE id_config = {$id}");
        return $r[0];
    }
    static function show_config_inv($id){
        $r = DB::query("SELECT * FROM f4999 WHERE id_config = {$id}");
        return $r[0];
    }
}