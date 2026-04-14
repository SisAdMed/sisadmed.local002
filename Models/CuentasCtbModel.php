<?php class CuentasCtbModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT id_cta, CAST(cod_cta AS CHAR) cod_cta, nombre_cta, agrupa_cta, status, aux_cta, CASE tip_cta WHEN 'A' THEN 'ACTIVO' WHEN 'P' THEN 'PASIVO' WHEN 'C' THEN 'CAPITAL' WHEN 'I' THEN 'INGRESO' WHEN 'S' THEN 'COSTO' WHEN 'E' THEN 'EGRESO' WHEN 'T' THEN 'CONTRA' WHEN 'O' THEN 'PERCONTRA' END tip_cta FROM f0010 ORDER BY 2");
    }
    static function guardar($data){
        return $id = DB::insert('f0010', $data);
    }
     static function actualizar($id, $data){
        return $r = DB::update('f0010', $data, ['id_cta' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f0010 WHERE id_cta = {$id}");
        return $r[0];
    }
    static function validar_cod($codigo){
        return $r = DB::query("SELECT * FROM f0010 WHERE cod_cta = '".$codigo."'");
    }
    static function validar_tipo($codigo){
         return $r = DB::query("SELECT tip_cta FROM f0010 WHERE cod_cta = '".$codigo."'");
    }
    static function validaSelecCue_AuxSN($id){
        return $r = DB::query("SELECT aux_cta FROM f0010 WHERE id_cta = {$id}");
    }
    static function listar_ctas_ctbles(){
        return $r = DB::query("SELECT * FROM f0010 WHERE status = 1 AND agrupa_cta = 'N'"); 
    }
    static function modal_CuentasCtb(){
        return $r = DB::query("SELECT id_cta, cod_cta, nombre_cta, agrupa_cta, aux_cta, CASE tip_cta WHEN 'A' THEN 'Activo' WHEN 'P' THEN 'Pasivo' WHEN 'C' THEN 'Capital' WHEN 'I' THEN 'Ingreso' WHEN 'E' THEN 'Egreso' WHEN 'T' THEN 'Contra' WHEN 'O' THEN 'Percontra' END tip_cta FROM f0010 WHERE status = 1 AND (agrupa_cta = 'S' AND aux_cta = 'S') OR (agrupa_cta = 'N' AND aux_cta = 'N') ORDER BY 2");
    }
    static function nom_ctb($id){
        $r = DB::query("SELECT cod_cta, nombre_cta, aux_cta FROM f0010 WHERE id_cta = {$id}");
        return $r[0];
    }
    static function validar_cod_cta($codigo){
        $r = DB::query("SELECT * FROM f0010 WHERE cod_cta = '".$codigo."'");
        return $r[0];
    }
    static function listar_nivel_detalle(){
        $r = DB::query("SELECT (LENGTH(max(cod_cta)) - LENGTH(REPLACE(max(cod_cta), '.', '')))/LENGTH('.') + 1 AS Count
        FROM f0010 WHERE status = 1");
        return $r[0];
    }
    static function val_con($id){
        $id = substr($id, 0, -1);
        $sql = "SELECT * FROM f0010 WHERE cod_cta = '$id' AND agrupa_cta = 'S' LIMIT 1";
        $r = DB::query($sql);
        return $r;
    }
    static function destroy($id)    {
        $sql = "SELECT COUNT(*) tot_row FROM f0010 WHERE cod_cta LIKE '$id%'";
        $r = DB::query($sql);
        if ($r[0]['tot_row'] == 1) {
            return $r = DB::delete('f0010', ['cod_cta' => $id]);
        }
        return false;
    }
    //--- Otros Métodos paara Reportes ---
    //Comprobantes de Diario
    static function journal_vouchers($id_emp, $fec_ini, $fec_fin){
        $sql = "SELECT c.cod_emp, c.nombre_emp, e.nombre_tipcom, a. fecha_comp, a.num_comp, a.desc_comp, d.cod_cta, d.nombre_cta, IFNULL(z.cod_aux, ' ') cod_aux, IFNULL(z.nombre_aux, ' ') nombre_aux, CASE WHEN b.det_tipo = 'D' THEN b.det_monto ELSE ' ' END mon_debe, CASE WHEN b.det_tipo = 'H' THEN b.det_monto ELSE ' ' END mon_habe FROM f00121 a INNER JOIN f00122 b ON b.id_comp = a.id_comp INNER JOIN f0011 c ON c.id_emp = a.id_emp INNER JOIN f0010 d ON d.id_cta = b.id_cue INNER JOIN f0019 e ON e.id_tipcom = a.id_tipcom LEFT OUTER JOIN f0009 z ON z.id_aux = b.id_aux 
        WHERE a.id_emp = $id_emp AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' ORDER BY c.cod_emp, c.nombre_emp, e.nombre_tipcom, a. fecha_comp, a.num_comp, a.desc_comp, d.cod_cta, IFNULL(z.cod_aux, ' ')";
        return $r = DB::query($sql);    
    }
    //Libro Mayor Analítico
    static function analytical_ledger($id_emp, $fec_ini, $fec_fin, $id_ctb, $id_aux){
        $filter = "";
        if($id_ctb){
            $filter .= " AND b.id_cue = $id_ctb ";
        }
        if($id_aux){
            $filter .= " AND b.id_aux = $id_aux ";
        }   
        $sql = "SELECT c.cod_emp, c.nombre_emp, d.cod_cta, d.nombre_cta, a. fecha_comp, a.num_comp, IFNULL(z.cod_aux, ' ') cod_aux, a.desc_comp, CASE WHEN b.det_tipo = 'D' THEN b.det_monto ELSE ' ' END mon_debe, CASE WHEN b.det_tipo = 'H' THEN b.det_monto ELSE ' ' END mon_habe, LEFT(a.ori_comp,1) module, a.ori_comp,  CONCAT(SUBSTR(a.ori_comp, 2,4), '-', SUBSTR(a.ori_comp, 6, 2), '-', SUBSTR(a.ori_comp, 8,2)) fec_close FROM f00121 a INNER JOIN f00122 b ON b.id_comp = a.id_comp INNER JOIN f0011 c ON c.id_emp = a.id_emp INNER JOIN f0010 d ON d.id_cta = b.id_cue INNER JOIN f0019 e ON e.id_tipcom = a.id_tipcom LEFT OUTER JOIN f0009 z ON z.id_aux = b.id_aux WHERE a.id_emp = $id_emp AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' $filter ORDER BY c.cod_emp, c.nombre_emp, d.cod_cta, IFNULL(z.cod_aux, ' '), a. fecha_comp, a.num_comp, a.desc_comp";
        return $r = DB::query($sql);    
    }
    //Balance de Comprobación
    static function trial_balance($id_emp, $fec_ini, $fec_fin, $id_ctb = null, $id_aux = null){
        $filter = '';
        if ($id_ctb) {
            $filter .= " AND b.id_cue = $id_ctb ";
        }
        if ($id_aux) {
            $filter .= " AND b.id_aux = $id_aux ";
        }
        $sql = "SELECT c.cod_emp, c.nombre_emp, d.id_cta, d.cod_cta, d.nombre_cta, d.agrupa_cta, z.id_aux, IFNULL(z.nombre_aux, ' ') nombre_aux, fn_ctb_sal_ant(a.id_emp, '$fec_ini', d.id_cta, z.id_aux) sal_ant, CASE WHEN b.det_tipo = 'D' THEN SUM(b.det_monto) END mon_debe, CASE WHEN b.det_tipo = 'H' THEN SUM(b.det_monto) END mon_habe, fn_ctb_sal_ant(a.id_emp, '$fec_ini', d.id_cta, z.id_aux) + CASE WHEN b.det_tipo = 'D' THEN SUM(b.det_monto) ELSE SUM(b.det_monto * -1) END saldo FROM f00121 a INNER JOIN f00122 b ON b.id_comp = a.id_comp INNER JOIN f0011 c ON c.id_emp = a.id_emp INNER JOIN f0010 d ON d.id_cta = b.id_cue INNER JOIN f0019 e ON e.id_tipcom = a.id_tipcom LEFT OUTER JOIN f0009 z ON z.id_aux = b.id_aux WHERE a.id_emp = $id_emp AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND $filter GROUP BY c.cod_emp, c.nombre_emp, d.cod_cta, d.nombre_cta, d.agrupa_cta, IFNULL(z.nombre_aux, ' ') ORDER BY c.cod_emp, c.nombre_emp, d.cod_cta, d.agrupa_cta, IFNULL(z.nombre_aux, ' ')";
        return $r = DB::query($sql);    
    }
}