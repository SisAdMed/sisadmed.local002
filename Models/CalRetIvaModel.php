<?php
class CalRetIvaModel extends DB {
    public function __construct() {
        parent::__construct();
    }
    static function all() {
        return $r = DB::query('SELECT * FROM f3006');
    }
    static function guardar($data) {
        return $r = DB::insert('', $data);
    }
    static function actualizar($id, $data) {
        return $r = DB::update('', $data, ['' => $id]);
    }
    static function borrar($id) {
        return $r = DB::delete('', ['' => $id]);
    }
}