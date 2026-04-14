<?php
class EquivaleModel extends DB {
    public function __construct() {
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT row_number() OVER (ORDER BY a.fecha) item, b.nombre_emp, c.nom_ent, a.fecha, a.format, a.status, a.id_emp, a.id_ent FROM f4013 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f0014 c ON c.id_ent = a.id_ent  GROUP BY 2, 3, 4");
    }
    static function guardar($data) {
        return $r = DB::insert('f4013', $data);
    }
    static function edit($id_emp, $id_ent, $fecha){
        $r = DB::query("SELECT * FROM f4013 WHERE id_emp = {$id_emp} AND id_ent = {$id_ent} AND fecha = '$fecha'");
        return $r;
    }
    static function show_row($id_emp, $id_ent, $fecha){
        $r = DB::query("SELECT row_number() OVER (ORDER BY a.fecha) item, b.nombre_emp, c.nom_ent, a.fecha, a.format, a.status, a.id_emp, a.id_ent, a.cod_prod_ent, a.id_prod, CONCAT( d.cod2_prod, ' - ', d.nom_prod) nom_prod FROM f4013 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f0014 c ON c.id_ent = a.id_ent INNER JOIN f4005 d ON d.id_prod = a.id_prod WHERE a.id_emp = {$id_emp} AND a.id_ent = {$id_ent} AND a.fecha = '$fecha'");
        return $r;
    }
    static function delete_row($id_emp, $id_ent, $fecha){
        return $r = DB::delete('f4013', ['id_emp' => $id_emp, 'id_ent' => $id_ent, 'fecha' => $fecha]);
    }
    static function con_equivale($id_emp, $id_ent, $codigo, $id_prod){
        $sql = "SELECT e.id_prod FROM f4013 e WHERE e.id_emp = {$id_emp} AND e.id_ent = {$id_ent} AND e.format = '$codigo' AND e.cod_prod_ent = '$id_prod' AND e.status = 1";
        $r = db::query($sql);
        return $r;
    }
}