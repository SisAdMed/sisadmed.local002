<?php
class ConfigPrecioModel extends DB{
    public function __construct(){ 
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f0006 p INNER JOIN f0011 e ON e.id_emp = p.id_emp"); 
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f0006 p INNER JOIN f0011 e ON e.id_emp = p.id_emp WHERE id_precio = {$id}");
        return $r[0];
    }
    static function guardar($data){
        return $id = DB::insert('f0006', $data);
    }
    static function actualizar($id, $data){
        return $res = DB::update('f0006', $data, ['id_precio' => $id]);
    }   
}