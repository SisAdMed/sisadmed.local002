<?php
class CalComModel extends DB{
   public function __construct(){
      parent::__construct();
   }
   static  function all(){
      $sql = "SELECT a.id, b.nombre_emp, a.fec_ini, a.fec_fin, IFNULL(CONCAT(c.nom_vend, ' ', c.ape_vend),'') vendedor, a.status FROM f4016 a INNER JOIN f0011 b ON b.id_emp = a.id_emp LEFT OUTER JOIN f0016 c ON c.id_vend = a.id_vend";
      $r = DB::query($sql);
      return $r;
   }
   static function listar_tabla($id_emp, $fec_ini, $fec_fin, $id_vend, $id=''){
      $filter = '';
      if($id_emp){
         $filter .= " AND a.id_emp = {$id_emp} ";
      }
      if($fec_ini && $fec_fin){
         $filter .= " AND a.fecha_comp BETWEEN '{$fec_ini}' AND '{$fec_fin}' ";
      }
      if($id_vend){
         $filter .= " AND e.id_vend = {$id_vend} ";
      }
      //$query = "SELECT e.id_vend, CONCAT(e.nom_vend, ' ' , e.ape_vend) vendedor, f.nom_tdoc, d.num_tdo, c.nom_ent, d.fecha_comp fec_fact, a.fecha_comp fec_pag, e.comi_vend, d.id_cot, c.id_ent, ROUND(SUM(b.monto_doc),2) sub_total, ROUND((SUM(b.monto_doc)) * (e.comi_vend / 100),2) tot_comision, d.tasa_cambio, ROUND(SUM(b.monto_doc) * (e.comi_vend / 100) * d.tasa_cambio,2) tot_comision_dom FROM f6006 a INNER JOIN f60061 b ON b.movem_id = a.id_movement INNER JOIN f0014 c ON c.id_ent = a.id_cli INNER JOIN f6003 d ON d.id_cot = b.id_cot INNER JOIN f0016 e ON e.id_vend = d.id_vend INNER JOIN f6001 f ON f.id_tdoc = d.id_tdo WHERE a.movem_origen != 'CXC' AND e.comi_vend > 0 $filter GROUP BY e.id_vend, CONCAT(e.nom_vend, ' ' , e.ape_vend), f.nom_tdoc, d.num_tdo, c.nom_ent, d.fecha_comp, a.fecha_comp, e.comi_vend, d.id_cot, c.id_ent ORDER BY e.id_vend, a.fecha_comp";
      $query = "SELECT DISTINCT e.id_vend, CONCAT(e.nom_vend, ' ' , e.ape_vend) vendedor, f.nom_tdoc, d.num_tdo, c.nom_ent, d.fecha_comp fec_fact, d.tasa_cambio, e.comi_vend, d.id_cot, c.id_ent, (SELECT MAX(aa.fecha_comp) FROM f6006 aa INNER JOIN f60061 bb ON bb.movem_id = aa.id_movement WHERE bb.id_cot = d.id_cot AND aa.status = 1 AND aa.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin') fec_pag, ROUND(CASE WHEN ISNULL(d.id_vend) THEN (SELECT SUM(x.monto) FROM f60032 x WHERE x.id_cot = d.id_cot) ELSE (SELECT SUM(x.sub_total) FROM f60031 x WHERE x.id_cot = d.id_cot) END, 2) sub_total, ROUND(CASE WHEN ISNULL(d.id_vend) THEN (SELECT SUM(x.monto) * (e.comi_vend / 100) FROM f60032 x WHERE x.id_cot = d.id_cot) ELSE (SELECT SUM(x.sub_total) * (e.comi_vend / 100) FROM f60031 x WHERE x.id_cot = d.id_cot) END, 2) tot_comision, ROUND(CASE WHEN ISNULL(d.id_vend) THEN (SELECT (SUM(x.monto) * (e.comi_vend / 100)) * d.tasa_cambio FROM f60032 x WHERE x.id_cot = d.id_cot) ELSE (SELECT (SUM(x.sub_total) * (e.comi_vend / 100)) FROM f60031 x WHERE x.id_cot = d.id_cot) END, 2) tot_comision_dom FROM f6006 a INNER JOIN f60061 b ON b.movem_id = a.id_movement INNER JOIN f0014 c ON c.id_ent = a.id_cli INNER JOIN f6003 d ON d.id_cot = b.id_cot INNER JOIN f0016 e ON e.id_vend = IFNULL(d.id_vend, c.id_vend) INNER JOIN f6001 f ON f.id_tdoc = d.id_tdo WHERE a.status = 1 AND e.comi_vend > 0 $filter ";
      if($id){
         $query = "SELECT c.id_vend, CONCAT(c.nom_vend, ' ', c.ape_vend) vendedor, e.nom_tdoc, d.num_tdo, f.nom_ent, b.fec_doc fec_fact, b.fec_pag fec_pag, b.porcentaje comi_vend, b.id_cot, f.id_ent, b.sub_total_doc sub_total, ROUND((b.sub_total_doc * (b.porcentaje/100)),2) tot_comision, b.tasa_cambio, ROUND((b.sub_total_com), 2) tot_comision_dom FROM f4016 a INNER JOIN f40161 b ON b.id_calcom = a.id INNER JOIN f0016 c ON c.id_vend = b.id_vend INNER JOIN f6003 d ON d.id_cot = b.id_cot INNER JOIN f6001 e ON e.id_tdoc = d.id_tdo INNER JOIN f0014 f ON f.id_ent = d.id_cli WHERE a.id = {$id}";
      }
      return $r = DB::query($query);
   }  
   static function guardar($data){
      return DB::insert('f4016', $data);
   }  
   static function actualizar($id, $data){
      return DB::update('f4016', $data, ["id" => $id]);
   }  
   static function guardar_detalle($data){
      return DB::insert('f40161', $data);
   }
   static function delete_details($id){
      return DB::delete('f40161', ['id_calcom' => $id]);
   }
   static function edit($id){
      $r = DB::query("SELECT * FROM f4016 WHERE id = {$id}");
      return $r[0];
   }
   static function destroy($id){
      $r = DB::delete('f40161', ['id_calcom' => $id]);      
      $r = DB::delete('f4016', ['id' => $id]);
      return $r;
   }
   static function report_data($id){
      $sql = "SELECT ROW_NUMBER() OVER (ORDER BY c.id_vend) item, c.id_vend, a.fec_ini, a.fec_fin, CONCAT(c.nom_vend, ' ', c.ape_vend) vendedor, e.nom_tdoc, d.num_tdo, f.nom_ent, b.fec_doc fec_fact, b.fec_pag fec_pag, b.porcentaje, b.id_cot, f.id_ent, b.sub_total_doc sub_total, (b.sub_total_doc * (b.porcentaje / 100)) tot_comision, g.logo, g.nombre_emp, g.rif_empresa, g.dir_emp, g.email_emp, b.tasa_cambio,  b.sub_total_com * b.tasa_cambio tot_comision_dom FROM f4016 a INNER JOIN f40161 b ON b.id_calcom = a.id INNER JOIN f0016 c ON c.id_vend = b.id_vend INNER JOIN f6003 d ON d.id_cot = b.id_cot INNER JOIN f6001 e ON e.id_tdoc = d.id_tdo INNER JOIN f0014 f ON f.id_ent = d.id_cli INNER JOIN f0011 g ON g.id_emp = a.id_emp WHERE a.id = {$id} ORDER by c.id_vend;";
      return $r = DB::query($sql);
   }
}
