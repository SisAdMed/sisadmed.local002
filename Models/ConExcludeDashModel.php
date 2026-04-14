<?php
class ConExcludeDashModel extends DB {
    public function __construct() {
        parent::__construct();
    }
    static function all() {
        $sql = 'SELECT a.id, a.module, a.id_concept, a.status, a.create_user, b.nombre, IFNULL(c.cod_bancon, d.codigo_con) cod_con, IFNULL(c.nom_bancon, d.nombre_con) nom_con FROM f4015 a INNER JOIN f0022 b ON b.module = a.module LEFT OUTER JOIN f5005 c ON a.module = "B" AND c.id_bancon = a.id_concept LEfT OUTER JOIN f3003 d ON a.module = "P" AND d.id = a.id_concept';
        return $r = DB::query($sql);
    }
    static function guardar($data) {
        return $r = DB::insert('f4015', $data);
    }
    static function actualizar($id, $data) {
        return $r = DB::update('f4015', $data, ['id' => $id]);
    }
    static function borrar($id) {
        return $r = DB::delete('f4015', ['id' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT a.id, a.module, a.id_concept, a.status, a.create_user, b.nombre, IFNULL(c.cod_bancon, d.codigo_con) cod_con, IFNULL(c.nom_bancon, d.nombre_con) nom_con FROM f4015 a INNER JOIN f0022 b ON b.module = a.module LEFT OUTER JOIN f5005 c ON a.module = 'B' AND c.id_bancon = a.id_concept LEfT OUTER JOIN f3003 d ON a.module = 'P' AND d.id = a.id_concept WHERE a.id = {$id}");
        return $r[0];
    }
}