<?php
class RetISLRModel extends DB {
    public function __construct() {
        parent::__construct();
    }
    static function all() {
        return $r = DB::query('SELECT * FROM f0021');
    }
    static function guardar($data) {
        return $r = DB::insert('f0021', $data);
    }
    static function actualizar($id, $data) {
        return $r = DB::update('f0021', $data, ['id' => $id]);
    }
    static function borrar($id) {
        return $r = DB::delete('f0021', ['id' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f0021 WHERE id = {$id}");
        return $r[0];
    }
    static function listar_retislr(){
        return $r = DB::query("SELECT id, CONCAT(descrip, ' ', FORMAT(por_reten, '2', 'de_DE'), ' %.') descrip FROM f0021 WHERE status = 1");
    }
    static function report_retislr($id_emp, $fecha_ini, $fecha_fin){
        return $r = DB::query("SELECT d.id_emp, d.cod_emp, d.nombre_emp, d.rif_empresa, e.rif_ent, e.nom_ent, b.id_cot, b.num_tdo, b.num_control, b.fecha_comp, a.total_monto, a.total_base, a.por_reten, a.total_retenido, d.logo, IFNULL(f.code_seniat, 'Por Definir') code_seniat FROM f3007 a INNER JOIN f3004 b ON b.id_cot = a.id_cot INNER JOIN f0011 d ON d.id_emp = a.id_emp INNER JOIN f0014 e ON e.id_ent = b.id_cli LEFT OUTER JOIN f0021 f ON f.id = a.id_retislr WHERE d.id_emp = {$id_emp} AND b.fecha_comp BETWEEN '$fecha_ini' AND '$fecha_fin'");
    }
}