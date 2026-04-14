<?php
class TipoClienteModel extends DB {
    public function __construct() {
        parent::__construct();
    }
    static function all() {
        return $r = DB::query('SELECT * FROM f4014');
    }
    static function guardar($data) {
        return $r = DB::insert('f4014', $data);
    }
    static function actualizar($id, $data) {
        return $r = DB::update('f4014', $data, ['id' => $id]);
    }
    static function borrar($id) {
        return $r = DB::delete('f4014', ['id' => $id], "1");
    }
    static function description($description){
        $sql = "SELECT count(*) total FROM f4014 WHERE description = '$description'";
        return $r = DB::query($sql);
    }
    static function edit($id) {
        $sql = "SELECT * FROM f4014 WHERE id = {$id}";
        $r = DB::query($sql);
        return $r[0];
    }
}