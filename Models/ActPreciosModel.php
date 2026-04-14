<?php
class ActPreciosModel extends DB{
	public function __construct(){
		parent::__construct();
	}
	static function all() {
        return $r = DB::query("SELECT * FROM view_ActPrecios");
    }
    static function guardar($data){
    	return $id = DB::insert('f40053', $data);
    }
    static function guardar_detalles_hisorico_precios($data){
    	return $id = DB::insert('f400531', $data);
    }
    static function borrar_detalles_hisorico_precios($id){
    	return $id = DB::query("DELETE FROM f400531 WHERE id_pro_his = {$id}");
    }
    static function edit($id){
		$r = DB::query("SELECT * FROM f40053 WHERE id_pro_his = {$id}");
        return to_obj($r[0]);
    }
    static function actualizar($id, $data){
        return $r = DB::update('f40053', $data, ['id_pro_his' => $id]);
    }
    static function detalle_productos_actualizar($id){
        $r = DB::query("SELECT * FROM f400531 WHERE id_pro_his = {$id} ");
        return to_obj($r);
    }
    static function productos_actualizar($id, $data){
      return $res = DB::update('f4005', $data, ['id_prod' => $id]);
    }
}