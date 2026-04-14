<?php
    class SubGruposModel extends DB {
        public function __construct() {
            parent::__construct();
        }
        static function all() {
            return $r = DB::query('SELECT b.id_grupo, b.grupo_codigo, b.grupo_nombre, a.id, a.sub_grupo_nombre, a.status FROM `f40071` a INNER JOIN f4007 b ON b.id_grupo = a.id_grupo');
        }
        static function guardar($data) {
            return $r = DB::insert('f40071', $data);
        }
        static function actualizar($id, $data) {
            return $r = DB::update('f40071', $data, ['id' => $id]);
        }
        static function borrar($id) {
            return $r = DB::delete('f40071', ['id' => $id]);
        }
        static function edit($id){
            $r = DB::query("SELECT * FROM f40071 WHERE id = {$id}");
            return $r[0];
        }
        static function show_row($id) {
            $sql = "SELECT b.id_grupo, b.grupo_codigo, b.grupo_nombre, a.id, a.sub_grupo_nombre, a.status FROM `f40071` a INNER JOIN f4007 b ON b.id_grupo = a.id_grupo WHERE a.id = {$id}";
            $r = DB::query($sql);
            return $r[0];
        }
        static function listar_sub_grupo($id){
            $sql = "SELECT * FROM f40071 WHERE id_grupo = {$id} AND status = 1";
            $r = DB::query($sql);
            return $r;
        }
    }
?>