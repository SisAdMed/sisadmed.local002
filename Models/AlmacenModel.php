<?php
class AlmacenModel extends DB{
	public function __construct(){ 
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f4002 a INNER JOIN f0011 b ON a.id_emp = b.id_emp");
    }
    static function next_codigo($id){
        return $r = DB::query("SELECT max(cod_alm) as codigo from f4002 WHERE id_emp = {$id}");
    }
    static function guardar($data){
        return $id = DB::insert('f4002', $data);
    }
    static function actualizar($id, $data){
        return $res = DB::update('f4002', $data, ['id_alm' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f4002 a INNER JOIN f0011 b ON a.id_emp = b.id_emp WHERE a.id_alm = {$id}");
        return $r[0];
    }
    static function borrar($id){
        return $id = DB::delete('f4002', ['id_alm' => $id], 1);
    }
    static function listar_almacenes($id){
        if($id){
            $r = DB::query("SELECT * FROM f4002 WHERE id_emp = {$id} AND status = 1");
        }else{
            $r = DB::query("SELECT a.id_alm, CONCAT(e.nombre_emp, ' - ', a.nom_alm) nom_alm FROM f4002 a INNER JOIN f0011 e ON e.id_emp = a.id_emp WHERE a.status = 1");
        }
        return $r;
    }
    static function listar_almacenes_ppal($id = null){
        if($id){
            $r = DB::query("SELECT * FROM f4002 WHERE id_emp = {$id} AND status = 1");
        }else{
            $r = DB::query("SELECT a.id_alm, CONCAT(e.nombre_emp, ' - ', a.nom_alm) nom_alm FROM f4002 a INNER JOIN f0011 e ON e.id_emp = a.id_emp INNER JOIN f4999 cfg ON cfg.id_emp = a.id_emp AND cfg.id_alm = a.id_alm WHERE a.status = 1");
        }
        return $r;
    }
    static function listar_entidad_modal($id){
        $r = DB::query("SELECT a.id_alm, b.nombre_emp, a.cod_alm, a.nom_alm FROM f4002 a INNER JOIN f0011 b ON b.id_emp = a.id_emp WHERE a.id_emp = {$id}");
        return $r;
    }
}