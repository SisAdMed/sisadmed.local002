<?php
class TipoDocCXPModel extends DB {
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        $sql = "SELECT t.id_tdoc id_tdoc, t.id_emp id_emp, t.tipo_codigo tipo_codigo, t.nom_tdoc nom_tdoc, CASE t.tipo_tdoc WHEN 'M' THEN 'Factura' WHEN 'O' THEN 'Orden de Compra' WHEN 'T' THEN 'Nota de Entrega' WHEN  'X' THEN 'Recepción S.T.' WHEN 'A' THEN 'Nota de Crédito' WHEN 'B' THEN 'Nota de Débito' WHEN 'V' THEN 'Nota de Devolución' WHEN 'G' THEN 'Entrega S.T.' END AS tipo_tdoc, t.con_tdoc con_tdoc, t.num_tdoc num_tdoc, t.id_ctb id_ctb, t.id_aux id_aux, t.status status, e.nombre_emp nombre_emp, CONCAT(c.cod_cta, ' - ', c.nombre_cta) cod_cta, CASE WHEN a.cod_aux = '0' THEN ' ' else CONCAT(a.cod_aux, ' - ', a.nombre_aux) END cod_aux, t.sol_aprob FROM f3001 t INNER JOIN f0011 e ON e.id_emp = t.id_emp LEFT JOIN f0010 c ON c.id_cta = t.id_ctb LEFT JOIN  f0009 a ON a.id_aux = t.id_aux";
        return $r = DB::query($sql);
    }
    static function destroy($id){
        return $r = DB::delete("f3001", ["id_tdoc" => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT a.id_tdoc, a.id_emp, a.tipo_codigo, a.nom_tdoc, a.tipo_tdoc, a.con_tdoc, a.num_tdoc, a.id_ctb, CONCAT(c.cod_cta, ' - ', c.nombre_cta) nombre_cta, CASE WHEN ISNULL(a.id_aux) THEN ' ' ELSE a.id_aux END id_aux, CASE WHEN ISNULL(a.id_aux) THEN ' ' ELSE CONCAT( d.cod_aux, ' - ' , d.nombre_aux)  END nombre_aux, a.status FROM f3001 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f0010 c ON c.id_cta = a.id_ctb LEFT OUTER JOIN f0009 d ON d.id_aux = a.id_aux LEFT OUTER JOIN f4006 e ON e.id_tmoinv = a.id_tmoinv WHERE a.id_tdoc = {$id}");
        return $r[0];
    }
    static function guardar($data){
        return $id = DB::insert('f3001', $data);
    }
    static function actualizar($id, $data){
        return $r = DB::update('f3001', $data, ['id_tdoc' => $id]);
    }
    static function listar_tipos_documentos($id_emp, $tipo_tdoc){
        $filter = "";
        if(!empty($tipo_tdoc) || $tipo_tdoc != ''){
            $filter = " AND tipo_tdoc = '". $tipo_tdoc ."'";
        }else{
            $filter = " AND tipo_tdoc != 'P'";
        }
        $r = DB::query("SELECT * FROM f3001 WHERE id_emp = {$id_emp} AND status = 1 $filter ORDER BY nom_tdoc");
        return $r;
    }
    static function listar_tipos_documentos_fuente($id_emp){
        $r = DB::query("SELECT * FROM f3001 WHERE id_emp = {$id_emp} AND (tipo_tdoc = 'P' OR tipo_tdoc = 'N') AND status = 1");
        return $r;
    }
    static function show_row($id){
        $sql = "SELECT * FROM f3001 WHERE id_tdoc = {$id}";
        $r = DB::query($sql);
        return $r[0];
    }
}