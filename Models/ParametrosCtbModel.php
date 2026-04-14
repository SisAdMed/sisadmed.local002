<?php
class ParametrosCTBModel extends DB {
	public function __construct() {
		parent::__construct();
	}
	static public function all() {
		return $r = DB::query("SELECT * FROM f0013 p INNER JOIN f0011 e ON e.id_emp = p.id_emp ");
	}
	static public function guardar($datos) {
		return $id = DB::insert('f0013', $datos);
	}
	static function edit($id){
        $r = DB::query("SELECT * FROM f0013 p INNER JOIN f0011 e ON e.id_emp = p.id_emp WHERE p.id_config = {$id}");
        return $r[0];
    }
     static function actualizar($id, $data) {
        return $res = DB::update('f0013', $data, ['id_config' => $id]);
    }
    static function listar_tipos_comprobantes(){
    	return $r = DB::query("SELECT * FROM f0019 WHERE status = 1");
    }
    static function showrow($id){
       return $r = DB::query("SELECT * FROM f0013 WHERE id_config = {$id}");
    }

}