<?php
class MotivoCambioModel extends DB{
   public function __construct() {
        parent::__construct();
    }
    static function all() {
        return $r = DB::query("SELECT * FROM f0012a");
    }
    static function guardar($data) {
        return $id = DB::insert('f0012a', $data);
    }
    static function actualizar($id, $data) {
        return $res = DB::update('f0012a', $data, ['id_motcam' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f0012a WHERE id_motcam = {$id}");
        return $r[0];
    }
    static function listar_motivo_cambio(){
        return $r = DB::query("SELECT * FROM f0012a WHERE status = 1");
    }
    static function destroy($id){
        return $id = DB::delete('f0012a', ['id_motcam' => $id], 1);
    }
    static function destroy_del_detmotcam($id){
        return $id = DB::delete('f0012a1', ['id_motcam' => $id]);
    }
    static function guardar_del_detmotcam($data){
        return $id = DB::insert('f0012a1', $data);
    }
    static function show_detalle($id){
        return $r = DB::query("SELECT * FROM f0012a1 WHERE id_motcam = {$id}");
    }
}