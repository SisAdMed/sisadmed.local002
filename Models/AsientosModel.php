<?php class AsientosModel extends DB
{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f00121 a INNER JOIN f0011 e on a.id_emp = e.id_emp INNER JOIN f0005 m ON a.id_moneda = m.id_moneda INNER JOIN f0019 t on t.id_tipcom = a.id_tipcom");
    }
    static function guardar($data){
        return $id = DB::insert('f00121', $data);
    }
    static function actualizar($id, $data){
        return $res = DB::update('f00121', $data, ['id_comp' => $id]);
    }
    static function borrar($id){
        return $id = DB::delete('f00121', ['id_comp' => $id], 1);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f00121 e INNER JOIN f00122 d ON e.id_comp = d.id_comp INNER JOIN f0010 c ON d.id_cue = c.id_cta  LEFT OUTER JOIN f0009 a ON d.id_aux = a.id_aux INNER JOIN f0019 t on t.id_tipcom = e.id_tipcom INNER JOIN f0005 m ON m.id_moneda = e.id_moneda WHERE e.id_comp = {$id}");
        return $r[0];
    }
    static function var_consec($id){
        $r = DB::query("SELECT * FROM f0013 WHERE id_emp = {$id} LIMIT 1");
        return $r[0];
    }
    static function nextNumber($id, $num_dia, $fecha){
        if($num_dia == "N"){
            $r = DB::query("SELECT MAX(num_comp) num_comp FROM f00121 WHERE id_emp = {$id} LIMIT 1");
        }else{
            $r = DB::query("SELECT MAX(num_comp) num_comp FROM f00121 WHERE id_emp = {$id} AND fecha_comp = $fecha LIMIT 1");
        }
        return $r[0];
    }
    static function borrardet($id){
        return $r = DB::delete('f00122', ['id_comp' => $id]);
    }
    static function guardardet($data){
        return DB::insert('f00122', $data);
    }
    static function updatedet($id, $data){
        return DB::update('f00122', $data, ['id_det' => $id]);
    }
    static function comprobante_por_defecto_CTB($id){
        return DB::query("SELECT * FROM f0013 WHERE id_emp = {$id}");
    }
    static function consultar_asiento($id){
        $r = DB::query("SELECT @i := @i + 1 item, a.det_monto, a.det_tipo, b.id_cta, a.id_aux, CASE WHEN NOT ISNULL(c.nombre_aux) THEN c.nombre_aux ELSE ' ' END nombre_aux, a.det_observa, a.det_tipo, b.nombre_cta, b.aux_cta FROM f00122 a INNER JOIN f0010 b on b.id_cta = a.id_cue LEFT OUTER JOIN f0009 c ON c.id_aux = a.id_aux WHERE a.id_comp = {$id}");
        return $r;
    }
    static function codigo_moneda($id){
        return $r = DB::query("SELECT * FROM f0005 WHERE id_moneda = {$id}");
    }
    static function print($id){
        return $r = DB::query("SELECT * FROM view_print_asiento WHERE id_comp = {$id} ORDER BY cod_cta, cod_aux, det_tipo");
    }
    static function comp_x_defecto_CTB($id){
        $r = DB::query("SELECT * FROM f0013 a INNER JOIN f0019 b on b.id_tipcom = a.id_tipcom WHERE a.id_emp = {$id}");
      return $r[0];
    }
    static function datosCue($id){
        $r = DB::query("SELECT * FROM f0010 WHERE id_cta = {$id}");
        return $r[0];
    }
    static function show_row($id){
        $sql = "SELECT a.id_emp, a.id_tipcom, a.num_comp, a.fecha_comp, a.id_moneda, a.tasa_cambio, a.desc_comp, a.status, b.id_cue, CONCAT(f.cod_cta, ' - ', f.nombre_cta) as nom_cue, b.id_aux, CASE WHEN b.id_aux > 0 THEN CONCAT(g.cod_aux, ' - ', g.nombre_aux) ELSE ' ' END nom_aux, b.det_observa, b.det_tipo, CASE WHEN b.det_tipo = 'D' THEN b.det_monto ELSE 0 END mon_debe, CASE WHEN b.det_tipo = 'H' THEN b.det_monto ELSE 0 END mon_habe FROM f00121 a INNER JOIN f00122 b ON b.id_comp = a.id_comp INNER JOIN f0011 c ON c.id_emp = a.id_emp  INNER JOIN f0019 d ON d.id_tipcom = a.id_tipcom INNER JOIN f0005 e on e.id_moneda = a.id_moneda INNER JOIN f0010 f ON f.id_cta = b.id_cue LEFT OUTER JOIN f0009 g ON g.id_aux = b.id_aux WHERE a.id_comp = {$id} ORDER BY 10, 12, 14, 15";
        $r = DB::query($sql);
        return $r;
    }
}