<?php class CambiosModel extends DB
{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f0012 c INNER join f0005 m ON c.id_moneda = m.id_moneda ORDER BY c.fecha_cambio DESC, c.id_moneda");
    }
    static function guardar($data)
    {
        return $id = DB::insert('f0012', $data);
    }
    static function guardar1($data)
    {
        return $id = DB::insert('f0012a', $data);
    }
    static function actualizar($id, $data)
    {
        return $res = DB::update('f0012', $data, ['id_cambio' => $id]);
    }
    static function borrar($id){
        return $id = DB::delete('f0012', ['id_cambio' => $id], 1);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f0012 WHERE id_cambio = {$id}");
        return $r[0];
    }
    static function rateExchange($moneda, $fecha){
        $cambio = 1;
        if($moneda){
            $sql = "SELECT * FROM f0012 WHERE fecha_cambio <= '". $fecha ."' AND id_moneda = {$moneda} ORDER BY fecha_cambio DESC";
            $rows = to_obj(DB::query($sql));
            if(is_iterable($rows)){
                $cambio = $rows[0]->cambio_venta;
            }else{
                $cambio = 1;
            }
        }
        return formatNumber($cambio, 4);
    }
    static function exist_rate_change($id_moneda, $fecha_cambio){
        return $r = DB::query("SELECT * FROM f0012 WHERE id_moneda = {$id_moneda} AND fecha_cambio = '".$fecha_cambio."' ORDER BY fecha_cambio DESC LIMIT 1");
    }
    static function getIdMoneda($id){
        $r = DB::query("SELECT id_moneda FROM f0005 WHERE codigo_moneda = '$id'");
        return $r[0];
    }
}