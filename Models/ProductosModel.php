<?php
class ProductosModel extends DB {
    public function __construct() {
        parent::__construct();
    }
    static function all() {
        $x = DB::query("SELECT GROUP_CONCAT(id_alm) id_alm FROM f4999;");
        $id_alm = $x[0]['id_alm'];
        $fecha = Date('Y-m-d');        
        $sql = "SELECT p.id_prod, p.cod_prod, p.cod2_prod, p.nom_prod, p.ref_prod, ma.nom_fab, p.costo_prod,p.flete_prod, p.otros_prod, p.door_costo, p.recar_prod, p.ventas_prod, p.recar2_prod, p.venta2_prod, p.status, g.grupo_nombre, p.uni_ven_prod, p.origen, p.alto, p.ancho, p.largo, pre1.nom_pre empaque, pre2.nom_pre bultos, pre.nom_pre, p.gen_prod, p.des_prod, p.uni_com_prod, p.con_cons_prod, p.conv_prod_cons, p.iva_prod, p.lote_prod, p.interno_prod, p.door_prod, fn_saldo_act_inv(0, p.id_prod, '$id_alm', CURDATE()) stock, (SELECT COUNT(*) FROM f40051 WHERE id_prod = p.id_prod) fotos, CASE WHEN ma.adicional01 = 0 THEN  'Si' ELSE 'No' END adicional, us.administrator admin, CONCAT(b.name_user, ' ', b.last_user) creado_por, DATE_FORMAT(p.create_date, '%d-%m-%Y %H:%i:%s') create_date, CONCAT(v.name_user, ' ', v.last_user) modificado_por, DATE_FORMAT(p.modify_date, '%d-%m-%Y %H:%i:%s') modify_date FROM f4005 p INNER JOIN f0002 b ON b.id_user = p.create_user LEFT OUTER JOIN f0002 v ON v.id_user = p.modify_user LEFT OUTER JOIN f4004 pre on pre.id_pre = p.id_pre LEFT OUTER JOIN f4007 g on g.id_grupo = p.id_grupo LEFT OUTER JOIN f4003 ma on ma.id_fab = p.id_fab LEFT OUTER JOIN f4004 pre1 on pre1.id_pre = p.id_presen1 LEFT OUTER JOIN f4004 pre2 ON pre2.id_pre = p.id_presen2 LEFT OUTER JOIN f0002 us ON us.id_user = " . $_SESSION['id_user'] . " ORDER BY p.nom_prod";
        //debug($sql);
        return $r = DB::query($sql);
    }
    static function guardar($data){
        return $id = DB::insert('f4005', $data);
    }
    static function actualizar($id, $data){
        return $res = DB::update('f4005', $data, ['id_prod' => $id]);
    }
    static function edit($id){
        $fecha = Date('Y-m-d');
        $x = DB::query("SELECT GROUP_CONCAT(id_alm) id_alm FROM f4999;");
        $id_alm = $x[0]['id_alm'];
        $r = DB::query("SELECT p.id_prod id_prod, p.cod_prod cod_prod, p.cod2_prod cod2_prod, p.nom_prod nom_prod, p.id_pre id_pre, p.id_grupo id_grupo, p.id_subfam id_subfam, p.id_fab id_fab,p.ref_prod ref_prod, p.gen_prod gen_prod, p.des_prod des_prod, p.uni_com_prod uni_com_prod, p.uni_ven_prod uni_ven_prod, p.con_cons_prod con_cons_prod, p.conv_prod_cons conv_prod_cons, p.iva_prod iva_prod, p.lote_prod lote_prod, p.interno_prod interno_prod, p.costo_prod costo_prod, p.flete_prod flete_prod, p.otros_prod otros_prod, p.recar_prod recar_prod, p.ventas_prod ventas_prod, p.status status, p.recar2_prod recar2_prod, p.venta2_prod venta2_prod, e.id_eti id_eti, e.regsan_prod regsan_prod, e.cpe_prod cpe_prod, e.nomcor_prod nomcor_prod, e.marcom_prod marcom_prod, e.fabpor_prod fabpor_prod, e.connetpro_prod connetpro_prod, e.connetcaj_prod connetcaj_prod, e.uso_prod uso_prod, p.door_prod door_prod, p.door_costo door_costo, p.commet_prod commet_prod, p.origen origen, p.alto alto, p.ancho ancho, p.largo largo, p.id_presen1 id_presen1, p.id_presen2 id_presen2, p.stock_minimo, fn_saldo_act_inv(0, p.id_prod, '$id_alm', '$fecha') stock, id_fab_fac, p.id_sub_grupo
	    FROM f4005 p LEFT JOIN f40052 e ON e.id_prod = p.id_prod WHERE p.id_prod = {$id}");
        return $r[0];
    }
    static function borrarimg($id){
        return $id = DB::query("DELETE FROM f40051 WHERE id_photo = {$id}");
    } 
    static function guardarimg($data){
        return $id = DB::insert('f40051', $data);
    }
    static function showImg(int $id){
        return $r = DB::query("SELECT * FROM f40051 WHERE id_prod = {$id}");
    }
    static function listar_productos(){
        return $r = DB::query("SELECT * FROM f4005 ORDER BY nom_prod");
    }
    static function borrar(int $id){
        //return $id = DB::delete('f4005', ['id_prod' => $id], 1);
        return $id = DB::update('f4005', ['status' => 0], ['id_prod' => $id]);
    }
    static function guardar_etiqueta($data){
        return $ide = DB::insert('f40052', $data);
    }
    static function editar_etiqueta($id, $data){
        return $ide = DB::update('f40052', $data, ['id_prod' => $id]);
    }
    static function borrar_etiqueta($id){
        return $id = DB::delete('f40052', ['id_prod' => $id], 1);
    }
    static function consultar_etiqueta($id){
        return $id = DB::query("SELECT * FROM f40052 WHERE id_prod = {$id}");
    }
    static function printer_labels($id){
        return $r = DB::query("SELECT * FROM f40052 WHERE id_prod = {$id}");
    }
    //static function consulta($id, $origen='', $id_emp, $fecha, $id_alm = 0){
    static function consulta($id, $origen, $id_alm = '', $id_ubi = ''){
        $fecha_new = Date('Y-m-d');
        $fecha_next = strtotime($fecha_new . "+ 1 day");
        $fecha = date('Y-m-d', $fecha_next);
        //$filter  = 'con.id_alm';
        $filter = '';
        //if($id_alm > 0){
        //    $filter = $id_alm;
        //}
        //if($id_fab){
        //    $filter = ' AND (SELECT IFNULL(SUM(stock), 0) FROM f4010 WHERE id_prod = p.id_prod) > 0';
        //}
        //$r = DB::query("SELECT p.id_prod, p.cod_prod, p.cod2_prod, p.nom_prod, p.ref_prod, ma.nom_fab, p.costo_prod,p.flete_prod, p.otros_prod, p.door_costo, p.recar_prod, p.ventas_prod, p.recar2_prod, p.venta2_prod, p.status, g.grupo_nombre, p.uni_ven_prod, p.origen, p.alto, p.ancho, p.largo, pre1.nom_pre empaque, pre2.nom_pre bultos, pre.nom_pre, p.gen_prod, p.des_prod, p.uni_com_prod, p.con_cons_prod, p.conv_prod_cons, p.iva_prod, p.lote_prod, p.interno_prod, p.door_prod, (SELECT IFNULL(SUM(stock), 0) FROM f4010 WHERE id_prod = p.id_prod) as stock, IFNULL(ma.adicional01, 0) noadic FROM f4005 p INNER JOIN f4004 pre on pre.id_pre = p.id_pre INNER JOIN f4007 g on g.id_grupo = p.id_grupo INNER JOIN f4003 ma on ma.id_fab = p.id_fab LEFT OUTER JOIN f4004 pre1 on pre1.id_pre = p.id_presen1 LEFT OUTER JOIN f4004 pre2 ON pre2.id_pre = p.id_presen2 WHERE p.id_prod = {$id} " . $filter);
        //$r = DB::query("SELECT p.id_prod, p.cod_prod, p.cod2_prod, p.nom_prod, p.ref_prod, ma.nom_fab, p.costo_prod,p.flete_prod, p.otros_prod, p.door_costo, p.recar_prod, p.ventas_prod, p.recar2_prod, p.venta2_prod, p.status, g.grupo_nombre, p.uni_ven_prod, p.origen, p.alto, p.ancho, p.largo, pre1.nom_pre empaque, pre2.nom_pre bultos, pre.nom_pre, p.gen_prod, p.des_prod, p.uni_com_prod, p.con_cons_prod, p.conv_prod_cons, p.iva_prod, p.lote_prod, p.interno_prod, p.door_prod, IFNULL(ma.adicional01, 0) noadic, fn_saldo_ant_inv({$id_emp}, p.id_prod, '".$filter."', '".$fecha."') stock FROM f4005 p INNER JOIN f4004 pre on pre.id_pre = p.id_pre INNER JOIN f4007 g on g.id_grupo = p.id_grupo INNER JOIN f4003 ma on ma.id_fab = p.id_fab LEFT OUTER JOIN f4004 pre1 on pre1.id_pre = p.id_presen1 LEFT OUTER JOIN f4004 pre2 ON pre2.id_pre = p.id_presen2 INNER JOIN f4999 con ON con.id_emp = {$id_emp} WHERE p.id_prod = {$id} AND fn_saldo_ant_inv({$id_emp}, p.id_prod, '".$filter."', '".$fecha."') > 0;");
        
        if($id_alm){
            //$x = DB::query("SELECT GROUP_CONCAT(id_alm) id_alm FROM f4999 WHERE id_alm = $id_alm;");
            //$id_alm = $x[0]['id_alm'];
        }else{
            $x = DB::query("SELECT GROUP_CONCAT(id_alm) id_alm FROM f4999;");
            $id_alm = $x[0]['id_alm'];
        }
        $saldo = " fn_saldo_act_inv(0, p.id_prod, '$id_alm', '$fecha') ";
        if($id_ubi){
            $saldo = " fn_saldo_ant_inv_consig(p.id_prod, '$id_alm', $id_ubi, '$fecha') ";
        }
        $sql = "SELECT p.id_prod, p.cod_prod, p.cod2_prod, p.nom_prod, p.ref_prod, ma.nom_fab, p.costo_prod,p.flete_prod, p.otros_prod, p.door_costo, p.recar_prod, p.ventas_prod, p.recar2_prod, p.venta2_prod, p.status, g.grupo_nombre, p.uni_ven_prod, p.origen, p.alto, p.ancho, p.largo, pre1.nom_pre empaque, pre2.nom_pre bultos, pre.nom_pre, p.gen_prod, p.des_prod, p.uni_com_prod, p.con_cons_prod, p.conv_prod_cons, p.iva_prod, p.lote_prod, p.interno_prod, p.door_prod, $saldo as stock, IFNULL(ma.adicional01, 0) noadic, p.id_fab FROM f4005 p INNER JOIN f4004 pre on pre.id_pre = p.id_pre INNER JOIN f4007 g on g.id_grupo = p.id_grupo INNER JOIN f4003 ma on ma.id_fab = p.id_fab LEFT OUTER JOIN f4004 pre1 on pre1.id_pre = p.id_presen1 LEFT OUTER JOIN f4004 pre2 ON pre2.id_pre = p.id_presen2 WHERE p.id_prod = {$id} " . $filter;
        $r = DB::query($sql);
        return $r[0];
    }
    static function consulta_presu($id)
    {
        $fecha = Date('Y-m-d');
        $filter = '';
        $x = DB::query("SELECT GROUP_CONCAT(id_alm) id_alm FROM f4999;");
        $id_alm = $x[0]['id_alm'];
        $saldo = " fn_saldo_act_inv(0, p.id_prod, '$id_alm', '$fecha') ";
        
        $sql = "SELECT p.id_prod, p.cod_prod, p.cod2_prod, p.nom_prod, p.ref_prod, ma.nom_fab, p.costo_prod,p.flete_prod, p.otros_prod, p.door_costo, p.recar_prod, p.ventas_prod, p.recar2_prod, p.venta2_prod, p.status, g.grupo_nombre, p.uni_ven_prod, p.origen, p.alto, p.ancho, p.largo, pre1.nom_pre empaque, pre2.nom_pre bultos, pre.nom_pre, p.gen_prod, p.des_prod, p.uni_com_prod, p.con_cons_prod, p.conv_prod_cons, p.iva_prod, p.lote_prod, p.interno_prod, p.door_prod, $saldo as stock, IFNULL(ma.adicional01, 0) noadic, p.id_fab FROM f4005 p INNER JOIN f4004 pre on pre.id_pre = p.id_pre INNER JOIN f4007 g on g.id_grupo = p.id_grupo INNER JOIN f4003 ma on ma.id_fab = p.id_fab LEFT OUTER JOIN f4004 pre1 on pre1.id_pre = p.id_presen1 LEFT OUTER JOIN f4004 pre2 ON pre2.id_pre = p.id_presen2 WHERE p.id_prod = {$id} " . $filter;
        $r = DB::query($sql);
        return $r[0];
    }
    static function consulta01($id_prod, $id_fab, $id_emp, $fecha){
        $r = DB::query("SELECT * FROM f4005 WHERE id_prod = {$id_prod}");
        return $r[0];
    }
    static function consulta99($id_prod, $id_emp, $id_fab){
        $filter = '';
        $fecha = Date('Y-m-d');
        if($id_fab > 0){
            $filter = " AND b.id_fab IN ($id_fab) ";
        }
        if($id_prod > 0){
            $filter = " AND a.id_prod = {$id_prod} ";
        }
        $r = DB::query("SELECT a.id_prod, a.cod_prod, a.cod2_prod, a.nom_prod, IFNULL(a.ref_prod, ' ') ref_prod, b.nom_fab, a.ventas_prod, IFNULL(b.adicional01, 0) noadic, CASE WHEN a.iva_prod = 1 THEN 'S' ELSE 'N' END iva_prod, a.uni_ven_prod, a.ventas_prod, fn_saldo_ant_inv(c.id_emp, a.id_prod, c.id_alm, '".$fecha. "', 0) stock FROM f4005 a INNER JOIN f4003 b on b.id_fab = a.id_fab INNER JOIN f4999 c WHERE a.status = 1 AND fn_saldo_act_inv(c.id_emp, a.id_prod, c.id_alm, '".$fecha."') > 0 " . $filter);
        if($id_prod > 0){
            return $r[0];
        }else{
            return $r;
        }
    }
    static function tot_prod(){
        $r = DB::query("SELECT count(*) tot_prod FROM f4005");
        return $r[0];
    }
    static function stockProducto($id, $id_ent){
        $r = DB::query("SELECT IFNULL(SUM(stock),0) stock FROM f4010 WHERE id_prod = {$id} AND id_ent = {$id_ent}");
        return $r[0];
    }
    //Llenar listado productos modal
    static function listar_productos_modal($with_stock, $id_emp, $id_alm, $id_fab){
        $fecha = date("Y-m-d");
        $sql = '';
        $filter = '';
        if($id_fab){
            $filter .= ' AND a.id_fab IN ('.$id_fab.') ';
        }
        if($with_stock != 0){
            $filter .= " AND fn_saldo_act_inv(0, a.id_prod, '$id_alm', '$fecha') > 0";
        }
        //$sql = "SELECT a.id_prod, a.cod_prod, a.cod2_prod, a.nom_prod, a.ref_prod, b.nom_fab, 0 stock FROM f4005 a INNER JOIN f4003 b ON b.id_fab = a.id_fab WHERE a.status = 1 ". $filter ." ORDER BY a.nom_prod";
        $sql = "SELECT a.id_prod, a.cod_prod, a.cod2_prod, a.nom_prod, a.ref_prod, b.nom_fab, fn_saldo_act_inv(0, a.id_prod, '$id_alm', '$fecha') stock FROM f4005 a INNER JOIN f4003 b ON b.id_fab = a.id_fab WHERE a.status = 1 AND a.uni_ven_prod > 0 AND a.costo_prod > 0 ". $filter ." ORDER BY a.nom_prod";
        $r = DB::query($sql);
        return $r;
    }
    static function listar_productos_modal_consig($id_emp, $fec_fin, $id_alm, $id_ubi, $id_fab, $id_prod){
        $filter = ''; 
        $fecha = date("Y-m-d");
         if($id_ubi === ''){
            $id_ubi = 0;
         } 
         if($fec_fin){
            $fecha = $fec_fin;
         }
         if($id_fab){
            $filter += " AND b.id_fab = {$id_fab} ";
         }
         if($id_prod){
            $filter += " AND a.id_prod = {$id_prod}";
         }
        //$query = "SELECT b.id_prod, b.cod_prod, b.cod2_prod, b.nom_prod, b.ref_prod, c.nom_fab, (SELECT IFNULL(SUM(stock), 0) FROM f4010 WHERE id_prod = b.id_prod AND id_ent = {$id_alm}) stock FROM f4010 a INNER JOIN f4005 b ON b.id_prod = a.id_prod INNER JOIN f4003 c ON c.id_fab = b.id_fab WHERE a.id_alm = {$id_alm} AND CASE WHEN $id_ubi THEN a.id_ubi = {$id_ubi} ELSE TRUE END GROUP BY 1, 2, 3, 4, 5, 6";
        $query = "SELECT a.id_prod, a.cod_prod, a.cod2_prod, a.nom_prod, a.ref_prod, b.nom_fab, fn_saldo_act_inv_consig(a.id_prod, '$id_alm', '$id_ubi', '$fecha') stock FROM f4005 a INNER JOIN f4003 b ON b.id_fab = a.id_fab WHERE a.status = 1 AND fn_saldo_act_inv_consig(a.id_prod, '$id_alm', '$id_ubi', '$fecha') != 0 " . $filter;
        
        $r = DB::query($query);
        return $r;
    }
    static function listar_productos_modal_reserva($id_alm, $id_ent){
        $r = DB::query("SELECT a.id_prod, b.cod_prod, b.cod2_prod, b.nom_prod, b.ref_prod, c.nom_fab, sum(stock) stock FROM f4010 a INNER JOIN f4005 b ON b.id_prod = a.id_prod INNER JOIN f4003 c ON c.id_fab = b.id_fab WHERE a.id_ent = {$id_ent} AND a.id_alm = {$id_alm} GROUP BY a.id_prod, b.cod_prod, b.cod2_prod, b.nom_prod, b.ref_prod, c.nom_fab HAVING SUM(stock) > 0");
        return $r;
    }
    static function val_cod_prod($id, $cod){
        if($cod){
            $r = DB::query("SELECT count(*) totrows FROM f4005 WHERE id_prod <> {$cod} AND cod_prod = '".$id."'");
        }else{
            $r = DB::query("SELECT count(*) totrows FROM f4005 WHERE cod_prod = '".$id."'");
        }
        return $r;
    }
    static function charge_history(int $id){
        $sql = "SELECT a.id, a.fecha, a.costo_prod, a.flete_prod, a.otros_prod, a.door_costo, a.costo1, a.recar_prod, a.ventas_prod, recar2_prod, venta2_prod, CONCAT(b.name_user, ' ', b.last_user) usuario FROM f40054 a INNER JOIN f0002 b ON b.id_user = a.create_user WHERE a.id_prod = {$id} ORDER BY a.id DESC";
        return $r = DB::query($sql);
    }
    static function cargar_screen_main() {
           $x = DB::query("SELECT GROUP_CONCAT(id_alm) id_alm FROM f4999;");
        $id_alm = $x[0]['id_alm'];
        $fecha = Date('Y-m-d');        
        $sql = "SELECT p.id_prod, p.cod_prod, p.cod2_prod, p.nom_prod, p.ref_prod, ma.nom_fab, p.costo_prod,p.flete_prod, p.otros_prod, p.door_costo, p.recar_prod, p.ventas_prod, p.recar2_prod, p.venta2_prod, p.status, g.grupo_nombre, p.uni_ven_prod, p.origen, p.alto, p.ancho, p.largo, pre1.nom_pre empaque, pre2.nom_pre bultos, pre.nom_pre, p.gen_prod, p.des_prod, p.uni_com_prod, p.con_cons_prod, p.conv_prod_cons, p.iva_prod, p.lote_prod, p.interno_prod, p.door_prod, fn_saldo_act_inv(0, p.id_prod, '$id_alm', CURDATE()) stock, (SELECT COUNT(*) FROM f40051 WHERE id_prod = p.id_prod) fotos, CASE WHEN ma.adicional01 = 0 THEN  'Si' ELSE 'No' END adicional, us.administrator admin, CONCAT(b.name_user, ' ', b.last_user) creado_por, DATE_FORMAT(p.create_date, '%d-%m-%Y %H:%i:%s') create_date, CONCAT(v.name_user, ' ', v.last_user) modificado_por, DATE_FORMAT(p.modify_date, '%d-%m-%Y %H:%i:%s') modify_date FROM f4005 p INNER JOIN f0002 b ON b.id_user = p.create_user LEFT OUTER JOIN f0002 v ON v.id_user = p.modify_user LEFT OUTER JOIN f4004 pre on pre.id_pre = p.id_pre LEFT OUTER JOIN f4007 g on g.id_grupo = p.id_grupo LEFT OUTER JOIN f4003 ma on ma.id_fab = p.id_fab LEFT OUTER JOIN f4004 pre1 on pre1.id_pre = p.id_presen1 LEFT OUTER JOIN f4004 pre2 ON pre2.id_pre = p.id_presen2 LEFT OUTER JOIN f0002 us ON us.id_user = " . $_SESSION['id_user'] . " ORDER BY p.nom_prod";
        //debug($sql);
        return $r = DB::query($sql);
    }
    static function stock_consig($id_emp, $id_alm, $id_ubi, $fec_fin, $id_fab, $id_prod){
        $filter = '';
        if($id_prod){
            $filter = " AND a.id_prod = {$id_prod} ";
        }
        if(empty($id_ubi)){
            $id_ubi = 0;
        }
        $usuario = $_SESSION['full_name'];
        $sql = "SELECT a.id_prod, a.nom_prod, a.cod_prod, a.cod2_prod, a.ref_prod, a.id_fab, b.nom_fab, '$fec_fin' fec_fin, fn_saldo_ant_inv_consig( a.id_prod, '$id_alm', '$id_ubi', '$fec_fin') stock, (IFNULL(a.costo_prod, 0) + IFNULL(a.flete_prod, 0) + IFNULL(a.otros_prod, 0) +  IFNULL(a.door_costo, 0)) costo1, (fn_saldo_ant_inv_consig( a.id_prod, '$id_alm', '$id_ubi', '$fec_fin') * ((IFNULL(a.costo_prod, 0) + IFNULL(a.flete_prod, 0) + IFNULL(a.otros_prod, 0) +  IFNULL(a.door_costo, 0))) ) valor, b.nom_fab, IFNULL(d.cod_prod_ent, ' ') cod_prod_ent, '$usuario' usuario, a.uni_ven_prod, (SELECT COUNT(*) FROM f4013 WHERE cod_prod_ent = d.cod_prod_ent) tot_cod_ent FROM f4005 a INNER JOIN f4003 b ON b.id_fab = a.id_fab INNER JOIN f4002 c ON c.id_alm = {$id_alm} LEFT OUTER JOIN f4013 d ON d.id_ent = c.id_cli AND d.format = 1 AND a.id_prod = d.id_prod WHERE a.status = 1 AND fn_saldo_ant_inv_consig( a.id_prod, '$id_alm', '$id_ubi', '$fec_fin') != 0 " . $filter . " ORDER BY  b.nom_fab, a.nom_prod";        
        return $r = DB::query($sql);
    }
    static function stock_ppal($id_emp, $id_alm, $id_ubi, $fec_fin, $id_fab){
        if(empty($id_emp)){
            $id_emp = 0;
        }
        if(!$id_ubi){
            $id_ubi = 0;
        }
        $sql = "SELECT a.id_prod, a.nom_prod, a.cod_prod, a.cod2_prod, a.ref_prod, a.id_fab, b.nom_fab, '$fec_fin' fec_fin, fn_saldo_act_inv( $id_emp, a.id_prod, '$id_alm', '$fec_fin') stock, (IFNULL(a.costo_prod, 0) + IFNULL(a.flete_prod, 0) + IFNULL(a.otros_prod, 0) +  IFNULL(a.door_costo, 0)) costo1, (fn_saldo_act_inv( $id_emp, a.id_prod, '$id_alm', '$fec_fin') * ((IFNULL(a.costo_prod, 0) + IFNULL(a.flete_prod, 0) + IFNULL(a.otros_prod, 0) +  IFNULL(a.door_costo, 0)))) valor, b.nom_fab FROM f4005 a INNER JOIN f4003 b ON b.id_fab = a.id_fab WHERE a.status = 1 AND fn_saldo_act_inv( $id_emp, a.id_prod, '$id_alm', '$fec_fin') != 0 ORDER BY  b.nom_fab, a.nom_prod";
        return $r = DB::query($sql);
    }
    static function repxconsumo_data($id_emp, $id_ent, $id_prod, $id_tipocliente, $id_fab, $fec_ini, $fec_fin){
        $filter = '';
        if($id_ent){
            $filter .= " AND c.id_ent = {$id_ent} ";
        }
        /*
        if($id_prod){
            $filter = " AND d.id_prod = {$id_prod} ";
        }
        */
        if($id_tipocliente){
            $filter .= " AND e.id = {$id_tipocliente} ";
        }
        if($id_fab){
            $filter .= " AND f.id_fab = '$id_fab' ";
        }

        $x = DB::query("SELECT GROUP_CONCAT(id_alm) id_alm FROM f4999;");
        $id_alm = $x[0]['id_alm'];

        $sql = "SELECT d.id_prod, d.cod_prod, d.nom_prod, CONCAT(LPAD(MONTH(a.fecha_comp),2, '0'), '-', YEAR(a.fecha_comp)) mesano, IFNULL(SUM(b.can_det), 0) tot_caj, f.nom_fab, d.ref_prod, IFNULL(SUM(b.can_det * b.uni_vta),0) tot_uni, fn_saldo_act_inv(0, d.id_prod, '$id_alm', '$fec_fin') stock FROM f6003 a INNER JOIN f60031 b ON b.id_cot = a.id_cot INNER JOIN f0014 c ON c.id_ent = a.id_cli INNER JOIN f4005 d ON d.id_prod = b.id_prod INNER JOIN f4014 e ON e.id = c.id_tipocliente INNER JOIN f4003 f ON f.id_fab = d.id_fab INNER JOIN f0011 g ON g.id_emp = a.id_emp WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' $filter GROUP BY d.id_prod, d.cod_prod, d.nom_prod, CONCAT(LPAD(MONTH(a.fecha_comp),2, '0'), '-', YEAR(a.fecha_comp)), f.nom_fab, d.ref_prod ORDER BY CONCAT(MONTH(a.fecha_comp), '-', YEAR(a.fecha_comp))";
        return $r = DB::query($sql);
    }
    static function listado_por_lotes(){
        //Creado el 28/10/2025 a las 14:35:00 por José vargas a solicitud de Nelson Guerra'
        $fecha = Date('Y-m-d');
        $x = DB::query("SELECT GROUP_CONCAT(id_alm) id_alm FROM f4999;");
        $id_alm = $x[0]['id_alm'];
        $sql = "SELECT a.id_prod, a.cod_prod, a.cod2_prod, a.ref_prod, a.nom_prod, a.id_fab, b.nom_fab, fn_saldo_act_inv(0, a.id_prod, '$id_alm', '$fecha') saldo, c.lote, c.fec_ven, SUM(c.stock) stock FROM f4005 a INNER JOIN f4003 b ON b.id_fab = a.id_fab INNER JOIN f4010 c ON c.id_prod = a.id_prod AND c.id_alm IN ($id_alm) WHERE a.status = 1 AND fn_saldo_act_inv(0, a.id_prod, '19,25,31', '2025-10-28') != 0 GROUP BY a.id_prod, a.cod_prod, a.cod2_prod, a.ref_prod, a.nom_prod, a.id_fab, b.nom_fab, fn_saldo_act_inv(0, a.id_prod, '$id_alm', '$fecha'), c.lote, c.fec_ven HAVING SUM(c.stock) != 0";
        return $r = DB::query($sql);
    }
    /** Mostro registros de un producto */
    static function show_row(int $id){
        $fecha = Date('Y-m-d');
        $x = DB::query("SELECT GROUP_CONCAT(id_alm) id_alm FROM f4999;");
        $id_alm = $x[0]['id_alm'];
        $sql = "SELECT a.cod_prod, a.cod2_prod, a.nom_prod, a.gen_prod, a.ref_prod, a.id_presen1, a.id_presen2, a.id_pre, a.id_fab, a.id_grupo, a.id_sub_grupo, a.origen, a.ancho, a.largo, a.alto, a.iva_prod, a.lote_prod, a.interno_prod, a.uni_com_prod, a.uni_ven_prod, a.con_cons_prod, a.conv_prod_cons, a.costo_prod, a.flete_prod, a.otros_prod, a.door_costo,  a.costo1, a.recar_prod, a.ventas_prod, a.recar2_prod, a.venta2_prod, a.status, a.stock_minimo, fn_saldo_act_inv(0, a.id_prod, '$id_alm', '$fecha') stock, a.id_fab_fac, a.des_prod, a.commet_prod, y.regsan_prod, y.cpe_prod, y.nomcor_prod, y.marcom_prod, y.fabpor_prod, y.connetpro_prod, y.connetcaj_prod, y.uso_prod, CONCAT(b.name_user, ' ', b.last_user) creado_por, DATE_FORMAT(a.create_date, '%d-%m-%Y %H:%i:%s') create_date, CONCAT(v.name_user, ' ', v.last_user) modificado_por, DATE_FORMAT(a.modify_date, '%d-%m-%Y %H:%i:%s') modify_date FROM f4005 a INNER JOIN f0002 b ON b.id_user = a.create_user LEFT OUTER JOIN f40051 z ON z.id_prod = a.id_prod LEFT OUTER JOIN f40052 y ON y.id_prod = a.id_prod LEFT OUTER JOIN f40054 x ON x.id_prod = a.id_prod LEFT OUTER JOIN f0002 v ON v.id_user = a.modify_user WHERE a.id_prod = {$id}";            
        return $r = DB::query($sql);
    }
}