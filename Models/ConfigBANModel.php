<?php
/**
 * Clase para las transacciones de la Configuración de Bancos
 * Creado por José Vargas el 16-01-2025 a las 10:29:00
 */
class ConfigBANModel extends DB{
    function __construct()    {
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT a.id_config, b.nombre_emp nom_empresa, a.status from f5999 a INNER JOIN f0011 b ON b.id_emp = a.id_emp");
    }
    static function guardar($data){
        return $r = DB::insert('f5999', $data);
    }
    static function actualizar($data, $id){
        return $r = DB::update('f5999', $data, ['id_config' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT a.id_config, b.nombre_emp nom_empresa FROM f5999 a INNER JOIN f0011 b ON b.id_emp = a.id_emp WHERE id_config = {$id}");
        return $r[0];
    }
    static function show_row($id){
        $r = DB::query("SELECT a.id_emp, b.nombre_emp, a.id_bancon_CXC, c.nom_bancon nom_bancon_CXC, a.id_bancon_CXP, d.nom_bancon nom_bancon_CXP, a.id_bancon_RETIVA, e.nom_bancon nom_bancon_RETIVA, a.status FROM f5999 a INNER JOIN f0011 b ON b.id_emp = a.id_emp LEFT OUTER JOIN f5005 c ON c.id_bancon = a.id_bancon_CXC LEFT OUTER JOIN f5005 d ON d.id_bancon = a.id_bancon_CXP LEFT OUTER JOIN f5005 e ON e.id_bancon = a.id_bancon_RETIVA WHERE a.id_config = {$id}");
        return $r[0];
    }
    static function show_config($id){
        $r = DB::query("SELECT * FROM f5999 WHERE id_config = {$id}");
        return $r[0];
    }
    static function show_config_emp($id_emp){
        $r = DB::query("SELECT * FROM f5999 WHERE id_emp= {$id_emp}");
        return $r[0];
    }
}