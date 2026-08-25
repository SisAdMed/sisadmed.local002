<?php 
    class GruposModel extends DB {
        public function __construct() {
            parent::__construct();
        }
        static function all() {
            return $r = DB::query('SELECT * FROM f4007');
        }
        static function guardar($data) {
            return $r = DB::insert('f4007', $data);
        }
        static function actualizar($id, $data) {
            return $r = DB::update('f4007', $data, ['id_grupo' => $id]);
        }
        static function borrar($id) {
            return $r = DB::delete('f4007', ['id_grupo' => $id]);
        }
        static function next_codigo(){
            $sql = 'SELECT MAX(grupo_codigo) + 1 grupo_codigo FROM f4007';
            $r = DB::query($sql);
            return $r[0];
        }
        static function edit($id){
            $sql = "SELECT * FROM f4007 WHERE id_grupo = {$id}";
            $r = DB::query($sql);
            return $r[0];
        }    
        static function getGrupos(){
            $sql = "SELECT id_grupo, grupo_nombre FROM f4007 WHERE status = 1 ORDER BY grupo_nombre";
            return $r = DB::query($sql);
        }
        static function getDocumentNew(int $id){
            $sql = "SELECT ruta_catalogo FROM f4007 WHERE id_grupo = {$id}";
            $r = DB::query($sql);
            return $r[0];
        }
    }
?>