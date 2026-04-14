<?php
class TipoDocCXCModel extends DB {
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        $sql = "SELECT t.id_tdoc id_tdoc, t.id_emp id_emp, t.tipo_codigo tipo_codigo, t.nom_tdoc nom_tdoc, CASE WHEN t.tipo_tdoc = 'P' THEN 'Presupuesto' WHEN t.tipo_tdoc = 'F' THEN 'Factura' WHEN t.tipo_tdoc = 'C' THEN 'Nota de Crédito' WHEN t.tipo_tdoc = 'B' THEN 'Nota de Debito' WHEN t.tipo_tdoc = 'N' THEN 'Nota de Entrega' WHEN t.tipo_tdoc = 'Z' THEN 'Nota de Entrega no Fiscal' WHEN t.tipo_tdoc = 'D' THEN 'Nota de Devolución' END AS tipo_tdoc, t.con_tdoc con_tdoc, t.num_tdoc num_tdoc, t.id_cta id_cta, t.id_aux id_aux, t.status status, e.nombre_emp nombre_emp, CONCAT(c.cod_cta, ' - ', c.nombre_cta) cod_cta, CASE WHEN a.cod_aux = '0' THEN ' ' ELSE CONCAT(a.cod_aux, ' - ', nombre_aux) END cod_aux, t.sol_aprob FROM f6001 t INNER JOIN f0011 e ON e.id_emp = t.id_emp LEFT JOIN f0010 c ON c.id_cta = t.id_cta LEFT JOIN f0009 a ON a.id_aux = t.id_aux";
        return $r = DB::query($sql);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f6001 WHERE id_tdoc = {$id}");
        return $r[0];
    }
    static function edit_deta($id){
        $r = DB::query("SELECT * FROM f6001 WHERE id_tdoc = {$id}");
        return $r[0];
    }
    static function guardar($data){
        return $id = DB::insert('f6001', $data);
    }
    static function actualizar($id, $data){
        return $r = DB::update('f6001', $data, ['id_tdoc' => $id]);
    }
    static function listar_tipos_documentos($id_emp, $tipo_tdoc){
        $filter = "";
        
        if(!empty($tipo_tdoc) || $tipo_tdoc != ''){
            $tipo = explode(",", $tipo_tdoc);
            $det_det = '';
            for($i = 0; $i < count($tipo); $i++ ){
                $det_det .= '\''.$tipo[$i].'\'';
                if($i != (count($tipo) -1)) $det_det .= ',';
            }
            $filter = " AND tipo_tdoc IN ($det_det)";
        }else{
            //$filter = " AND tipo_tdoc != 'F'";
        }
        $r = DB::query("SELECT * FROM f6001 WHERE id_emp = {$id_emp} AND status = 1" . $filter);
        return $r;
    }
    static function listar_tipos_documentos_fuente($id_emp){
        $r = DB::query("SELECT * FROM f6001 WHERE id_emp = {$id_emp} AND (tipo_tdoc = 'P' OR tipo_tdoc = 'N') AND status = 1");
        return $r;
    }
    static function name_tip_doc($id){
        $r = DB::query("SELECT tipo_tdoc FROM f6001 WHERE id_tdoc = {$id}");
        return $r[0];
    }
    static function delete_row($id){
        $r = DB::delete('f6001', ['id_tdoc' => $id]);
        return $r;
    }
    static function get_id_tipo_doc($tipo_codigo){
        $sql = "SELECT * FROM f6001 WHERE tipo_codigo = '$tipo_codigo' LIMIT 1";
        $r = DB::query($sql);
        return $r[0];
    }
}