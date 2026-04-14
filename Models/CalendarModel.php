<?php
class CalendarModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function cargar_screen_main(){
        return $r = DB::query("SELECT * FROM f0024");
    }
    static function guardar($data){
        return $id = DB::insert('f0024', $data);
    }
    static function actualizar($data, $id){
        return $r = DB::update('f0024', $data, ['id' => $id]);
    }
    static function show_row($id){
        $r = DB::query("SELECT * FROM f0024 WHERE id = {$id}");
        return $r[0];
    }
}
