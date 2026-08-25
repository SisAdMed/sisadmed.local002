<?php
class DelnotnotfisModel extends DB
{
    public function __construct()
    {
        parent::__construct();
    }
    static function all(string $tipo)
    {
        $sql = "SELECT c.id_cot, e.nombre_emp, t.nom_tdoc, c.num_tdo, a.nom_ent, c.fecha_comp, m.codigo_moneda, CASE WHEN c.imp_crcd = 0 THEN c.tasa_cambio ELSE c.imp_tasa_cambio END tasa_cambio, v.nom_vend, c.status, c.id_cont fuente, ap.status penapro, c.nro_control, a.print_special, c.id_moneda, CONCAT(uc.name_user, ' ', uc.last_user) creado_por, IFNULL(CONCAT(um.name_user, ' ', um.last_user), ' ') modificado_por FROM f6003 c INNER JOIN f0011 e ON e.id_emp = c.id_emp INNER JOIN f6001 t ON t.id_tdoc = c.id_tdo INNER JOIN f0014 a ON a.id_ent = c.id_cli INNER JOIN f0005 m ON m.id_moneda = c.id_moneda INNER JOIN f0016 v ON v.id_vend = c.id_vend LEFT OUTER JOIN f4008 co ON co.id_cot = SUBSTRING(c.id_cont,1,LOCATE('-', c.id_cont) - 1) LEFT OUTER JOIN f6001 td ON td.id_tdoc = co.id_tdo LEFT OUTER JOIN fgenmsg ap ON ap.id_cot = c.id_cot AND ap.status = 1 AND ap.tipo_fgenmsgcol = 1 INNER JOIN f4999 cfg ON cfg.id_emp = c.id_emp INNER JOIN f0002 uc ON uc.id_user = c.create_user LEFT OUTER JOIN f0002 um ON um.id_user = c.modify_user WHERE t.tipo_tdoc = '$tipo' ORDER BY c.fecha_comp";  
        return $r = DB::query($sql);
    }
    static function guardar($data)
    {
        return $r = DB::insert('f6003', $data);
    }
    static function actualizar($id, $data)
    {
        return $r = DB::update('f6003', $data, ['id_cot' => $id]);
    }
    static function nextNumber($id_emp, $id_tdoc)
    {
        $nextNumber = DB::query("SELECT * FROM f6001 WHERE id_emp = {$id_emp} AND id_tdoc = {$id_tdoc} LIMIT 1");
        return $nextNumber;
    }
    static function setNextNumber($id_emp, $id_tdoc, $data)
    {
        return $r = DB::update("f6001", $data, ['id_emp' => $id_emp, 'id_tdoc' => $id_tdoc]);
    }
    static function borrarDetfactura($id_cot)
    {
        return $id = DB::query("DELETE FROM f60031 WHERE id_cot = {$id_cot}");
    }
    static function guardarDetfactura($data)
    {
        return $id = DB::insert('f60031', $data);
    }
    static function guardarDetfactura_CXC($data)
    {
        return $id = DB::insert('f60032', $data);
    }
    static function edit($id)
    {
        $r = DB::query("SELECT c.id_cot id_cot, c.id_emp id_emp, c.id_tdo id_tdo, c.num_tdo num_tdo, c.id_cli id_cli, c.fecha_comp fecha_comp, c.id_moneda id_moneda, c.tasa_cambio tasa_cambio, c.id_vend id_vend, e.nombre_emp nombre_emp, en.nom_ent nom_ent, concat(v.nom_vend,' ',v.ape_vend) vendedor, dc.id_prod id_prod, dc.can_det can_det, dc.uni_vta uni_vta, dc.pre_unit pre_unit, dc.pre_vta pre_vta, dc.iva_prod iva_prod, dc.sub_total sub_total, dc.mon_iva mon_iva, dc.tota_prod tota_prod, pr.cod_prod cod_prod FROM f6003 c INNER JOIN f0011 e ON e.id_emp = c.id_emp INNER JOIN f0014 en ON en.id_ent = c.id_cli INNER JOIN f0005 m ON m.id_moneda = c.id_moneda INNER JOIN f0016 v ON v.id_vend = c.id_vend INNER JOIN f6001 t ON t.id_tdoc = c.id_tdo INNER JOIN f60031 dc ON dc.id_cot = c.id_cot INNER JOIN f4005 pr ON pr.id_prod = dc.id_prod WHERE c.id_cot = {$id}");
        return $r[0];
    }
    static function edit_deta($id)
    {
        return $r = DB::query("SELECT a.id_emp, a.id_tdo, a.num_tdo, a.fecha_comp, a.fecha_venci, a.id_cli, d.nom_ent, a.id_moneda, a.tasa_cambio, a.id_vend, e.id_prod, f.nom_prod, e.can_det, e.uni_vta, e.pre_unit, e.pre_vta,  e.iva_prod, e.sub_total FROM f6003 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f6001 c ON c.id_tdoc = a.id_tdo INNER JOIN f0014 d ON d.id_ent = a.id_cli INNER JOIN f60031 e ON e.id_cot = a.id_cot INNER JOIN f4005 f ON f.id_prod = e.id_prod LEFT OUTER JOIN f60032 g ON g.id_cot = a.id_cot WHERE a.id_cot = {$id}");
    }
    static function consulta_adic01($id_emp, $fecha_precio)
    {
        $r = DB::query("SELECT * FROM f0006 p INNER JOIN f0011 e ON e.id_emp = p.id_emp WHERE p.id_emp = {$id_emp} AND fecha_precio <= '" . $fecha_precio . "' ORDER BY fecha_precio DESC LIMIT 1");
        return $r[0];
    }
    static function consulta_adic02($id_cli)
    {
        $r = DB::query("SELECT m.adic_01, m.adic_02 FROM f0014 e INNER JOIN f0012a m ON m.id_motcam = e.id_motcam WHERE e.id_ent = {$id_cli} LIMIT 1");
        return $r[0];
    }
    static function print_factura($id_cot)
    {
        $r = DB::query("SELECT em.nombre_emp nombre_emp, em.rif_empresa rif_empresa, em.dir_emp dir_emp, em.tel_emp tel_emp, em.email_emp email_emp, c.num_tdo num_tdo, tdo.nom_tdoc nom_tdoc, c.fecha_comp fecha_comp, en.nom_ent nom_ent, en.rif_ent rif_ent, en.dir_ent dir_ent, en.postal_ent postal_ent, pa.nombre_pais nombre_pais, es.nombre_edo nombre_edo, ci.nombre_ciudad nombre_ciudad, ve.nom_vend nom_vend, ve.ape_vend ape_vend, pro.cod_prod cod_prod, pro.cod2_prod cod2_prod, pro.nom_prod nom_prod, dc.iva_prod iva_prod, dc.can_det can_det, dc.pre_vta pre_vta, dc.sub_total sub_total, c.id_cot id_cot, em.logo logo, moc.codigo_moneda codigo_moneda, moe.codigo_moneda moneda_emp, c.tasa_cambio tasa_cambio, cfg.id_tdoc_fac, cfg.note_fac, cfg.id_tdoc_cre, cfg.note_cre, cfg.id_tdoc_pre, cfg.note_pre, cfg.id_tdoc_not, cfg.note_not, cfg.id_tdoc_dev, cfg.note_dev, cfg.note_not_no_fis, en.note_fac note_fac_custom, fab.nom_fab, pro.ref_prod, c.oc_cliente, c.descrip_cot, fab_fac.nom_Fab  nom_fab_fac  FROM f6003 c INNER JOIN f6001 tdo ON tdo.id_tdoc = c.id_tdo INNER JOIN f60031 dc ON dc.id_cot = c.id_cot INNER JOIN f0011 em ON em.id_emp = c.id_emp INNER JOIN f0014 en ON en.id_ent = c.id_cli INNER JOIN f0004 pa ON pa.id_pais = en.id_pais INNER JOIN f00041 es ON es.id_edo = en.id_edo INNER JOIN f00042 ci ON ci.id_ciudad = en.id_ciudad INNER JOIN f0016 ve ON ve.id_vend = en.id_vend INNER JOIN f4005 pro ON pro.id_prod = dc.id_prod INNER JOIN f0005 moc ON moc.id_moneda = c.id_moneda INNER JOIN f0005 moe ON moe.id_moneda = em.id_moneda INNER JOIN f4003 fab ON fab.id_fab = pro.id_fab INNER JOIN f4999 cfg ON cfg.id_emp = c.id_emp LEFT OUTER JOIN f4003 fab_fac ON fab_fac.id_fab = pro.id_fab_fac WHERE c.id_cot = {$id_cot}");
        return $r;
    }
    static function borrar($id)
    {
        $r = DB::query("UPDATE f6003 SET status = 99 WHERE id_cot = {$id}");
        return $r;
    }
    static function selectEncyDetmovinv($id)
    {
        return $r = DB::query("SELECT c.id_movinv FROM f6003 a INNER JOIN f6001 b ON b.id_tdoc = a.id_tdo INNER JOIN f4009 c ON c.origen = CONCAT(a.id_cont, '-', b.tipo_codigo, '-', a.id_emp, '-', a.num_tdo ) WHERE a.id_cot = {$id}");
    }
    static function borrarEncyDetmovinv($id)
    {
        DB::delete('f40091', ['id_movinv' => $id], 1000);
        return $r = DB::delete('f4009', ['id_movinv' => $id], 1);
    }
    static function create_express($id_fab)
    {
        $r = DB::query("SELECT * FROM f4005 WHERE id_fab IN ($id_fab) AND status = 1");
        return $r;
    }
    static function listar_factura($id_emp)
    {
        $r = DB::query("SELECT id_cot, concat(num_tdo, ' - ', id_cli, ' - ', nom_ent) cliente FROM f6003 INNER JOIN f0014 on f0014.id_ent = f6003.id_cli WHERE f6003.id_emp = {$id_emp} AND f6003.status = 1");
        return $r;
    }
    static function con_ventas()
    {
        $r = DB::query("SELECT * FROM f6002 WHERE id = 7");
        return $r[0];
    }
    static function detalle_venta($id)
    {
        return $r = DB::query("SELECT a.id_cot, a.iva_prod, SUM(a.sub_total) monto, SUM(a.mon_iva) mon_iva FROM f60031 a WHERE a.id_cot = {$id} GROUP BY a.id_cot, a.iva_prod");
    }
    static function borrarDetCXCDocument($id)
    {
        return $r = DB::delete('f60032', ['id_cot' => $id], 100);
    }
    static function tip_doc_fac($id)
    {
        $r = DB::query("SELECT * FROM f4999 WHERE id_emp = {$id} AND status = 1");
        return $r[0];
    }
    static function set_cotiza($id, $data)
    {
        return $r = DB::update('f4008', $data, ['id_cot' => $id]);
        return $r;
    }
    static function getNextNumer($id)
    {
        $r = DB::query("SELECT proximo_tmoinv FROM f4006 WHERE id_tmoinv = {$id}");
        return $r[0];
    }
    static function guardar_mov_inv($data)
    {
        return $r = DB::insert('f4009', $data);
    }
    static function guardar_Det_Movin($data)
    {
        return $r = DB::insert('f40091', $data);
    }
    static function consult_mov_in_ppal($origen, $tipo_mov)
    {
        $r = DB::query("SELECT * FROM f4009 WHERE origen = '" . $origen . "' AND id_tmovinv = {$tipo_mov}");
        return $r;
    }
    static function borrarDetInvMov($id)
    {
        DB::delete('f40091', ['id_movinv' => $id], 1000);
        return $r = DB::delete('f4009', ['id_movinv' => $id]);
    }
    static function setNextNumber_Mov_Inv($id)
    {
        return $r = DB::query("UPDATE f4006 SET proximo_tmoinv = proximo_tmoinv + 1 WHERE id_tmoinv = {$id}");
    }
    static function consulta_prod($id)
    {
        $r = DB::query("SELECT * FROM f4010 WHERE id_prod = {$id}");
        return $r;
    }
    static function aprobacion($data)
    {
        return $r = DB::insert('fgenmsg', $data);
    }
    static function show_row_des($id)
    {
        $r = DB::query("SELECT * FROM f7001 WHERE id = {$id}");
        return $r[0];
    }
    static function origen($id)
    {
        $r = DB::query("SELECT a.num_tdo, b.tipo_codigo FROM f4008 a INNER JOIN f6001 b ON b.id_tdoc = a.id_tdo WHERE a.id_cot = {$id}");
        return $r[0];
    }
    static function consulta_vend($id_ent)
    {
        $r = DB::query("SELECT DISTINCT a.id_vend, a.id_moneda, case when ISNULL(b.cod_diascre) THEN 0 ELSE b.cod_diascre END cod_diascre, a.nom_ent, a.handling_conver, a.id_alm, a.id_ubi, a.c_consig FROM f0014 a LEFT OUTER JOIN f6005 b on b.id_diascre = a.id_diascre WHERE a.id_ent = {$id_ent}");
        return $r[0];
    }
    static function getNumberMovim($origen)
    {
        $r = DB::query("SELECT * FROM f4009 WHERE origen = '" . $origen . "'");
        return $r[0];
    }
    static function actualizar_mov_inv($id, $data)
    {
        return $r = DB::update('f4009', $data, ['id_movinv' => $id]);
    }
    static function consulta_prod_consig($id, $id_cli, $id_alm, $id_ubi)
    {
        $r = DB::query("SELECT * FROM f4010 WHERE id_prod = {$id} AND id_ent = {$id_cli} AND id_alm  = {$id_alm} AND id_ubi = {$id_ubi}");
        return $r;
    }
    static function consulta01($id)
    {
        $r = DB::query("SELECT * FROM f4005 WHERE id_prod = {$id}");
        return $r[0];
    }
    static function listar_notas($id_emp, $id_cli, $fuente)
    {
        if ($fuente == 0) {
            $sql0 = "SELECT a.id_cot, concat('Nota de Entrega: ', a.num_tdo, ' - Cliente: ', a.id_cli, ' - ', c.nom_ent) cliente FROM f6003 a INNER JOIN f4999 b ON b.id_emp = a.id_emp INNER JOIN f0014 c ON c.id_ent = a.id_cli WHERE a.id_emp = {$id_emp} AND a.id_tdo = b.id_tdoc_not_no_fis AND a.invoice IS NULL AND a.id_cli = {$id_cli} AND a.status = 1 ORDER BY a.id_cot";
        } elseif ($fuente == "N") {
            $sql0 = "SELECT a.id_cot, concat('Nota de Entrega: ', a.num_tdo, ' - Cliente: ', a.id_cli, ' - ', c.nom_ent) cliente FROM f6003 a INNER JOIN f4999 b ON b.id_emp = a.id_emp INNER JOIN f0014 c ON c.id_ent = a.id_cli WHERE a.id_emp = {$id_emp} AND a.id_tdo = b.id_tdoc_not_no_fis AND a.invoice IS NULL AND a.id_cli = {$id_cli} AND a.status = 1 ORDER BY a.id_cot";
        } else {
            $sql0 = "SELECT a.id_cot, concat('Nota de Entrega: ', a.num_tdo, ' - Cliente: ', a.id_cli, ' - ', c.nom_ent) cliente FROM f6003 a INNER JOIN f4999 b ON b.id_emp = a.id_emp INNER JOIN f0014 c ON c.id_ent = a.id_cli WHERE a.id_emp = {$id_emp} AND a.id_tdo = b.id_tdoc_not_no_fis AND a.invoice = '$fuente' AND a.id_cli = {$id_cli} AND a.status = 1 ORDER BY a.id_cot";
        }                
        $r = DB::query($sql0);
        return $r;
    }
    static function consultar_nota(int $id)
    {
        $x = DB::query("SELECT GROUP_CONCAT(id_alm) id_alm FROM f4999;");
        $id_alm = $x[0]['id_alm'];
        $sql = "SELECT a.id_cot, f.id_emp, a.id_tdo, a.num_tdo, a.id_cli, a.fecha_comp, g.id_moneda, a.tasa_cambio, h.id_vend, f.nombre_emp, concat(h.nom_vend,' ',h.ape_vend) vendedor, e.id_prod, b.can_det, b.uni_vta, b.pre_unit, b.pre_vta, b.iva_prod, b.sub_total, b.mon_iva, b.tota_prod, e.cod_prod, e.nom_prod, a.descrip_cot observa, fn_saldo_ant_inv(0, e.id_prod, 'id_alm', a.fecha_comp, 0) stock FROM f6003 a INNER JOIN f60031 b ON b.id_cot = a.id_cot INNER JOIN f0014 c ON c.id_ent = a.id_emp INNER JOIN f6001 d on d.id_tdoc = a.id_tdo INNER JOIN f4005 e ON e.id_prod = b.id_prod INNER JOIN f0011 f ON f.id_emp = a.id_emp INNER JOIN f0005 g ON g.id_moneda = a.id_moneda INNER JOIN f0016 h on h.id_vend = a.id_vend WHERE a.id_cot = {$id}";
        $r = DB::query($sql);
        return $r;
    }
    //Guardar con Transaccion la Nota de Entrega no Fiscal
    static function DocumentSaved($data_enca, $data_deta, $data_mov_exist, $data_mov_enca, $data_mov_deta, $row, $data_mov_existC, $data_mov_encaC, $data_mov_detaC, $consig, $cxc)
    {        
        $db = new Conexion(); //Obtener la instancia del PDO
        $link = (object)$db->conect();        
        try {
            //1. INICIAR LA TRANSACCIÓN           
            $link->beginTransaction();
            //Actualizar el proximo Numero de Documento
            if (empty($row)) {
                
                $id_tdoc = $data_enca['id_tdo'];
                $num_tdo =  $data_enca['num_tdo'] + 1;
                $sql01 = "UPDATE f6001 SET num_tdoc = :num_tdoc WHERE id_tdoc = :id_tdoc";
                $stmt01 = $link->prepare($sql01);
                $stmt01->execute([
                    ':num_tdoc' => $num_tdo,
                    ':id_tdoc'  => $id_tdoc
                ]);
            }

            // ===========================================================
            // PASO 1: INSERTAR EN EL MAESTRO (NOTAS DE ENTREGA NO FISCAL)
            // ENCABEZADO
            // ===========================================================
            $cols = "";
            $placeholders = "";
            $params = [];
            if (empty($row)) {
                foreach ($data_enca as $key => $values) { //INSERT INTO table (campo1, campo2) VALUES (:placeholder, :placeholder)
                    $cols .= "{$key}, ";
                    $placeholders .= ":{$key}, ";
                    $params[":{$key}"] =  $values;
                }
                $cols = substr($cols, 0, -2);
                $placeholders = substr($placeholders, 0, -2);
                $sql02 = "INSERT INTO f6003 ({$cols}) VALUES ({$placeholders})";
            } else {
                foreach ($data_enca as $key => $values) {
                    $placeholders .= " {$key} = :{$key},";
                    $params[":{$key}"] =  $values;
                }
                $placeholders = substr($placeholders, 0, -1);
                if (count($row) > 1) {
                    foreach ($row as $key => $value) {
                        $cols .= " $key = :$key AND";
                    }
                    $cols = substr($cols, 0, -3);
                } else {
                    foreach ($row as $key => $value) {
                        $cols .= " $key = :$key";
                        $params[":{$key}"] = $value;
                    }
                }

                $sql02 = "UPDATE f6003 SET $placeholders WHERE $cols";
            }
            $stmt02 = $link->prepare($sql02);
            $stmt02->execute(array_merge($params, $row));            
            if (empty($row)) {
                $idNE = $link->lastInsertId();
            } else {
                $idNE = $row['id_cot'];
            }
            
            // RELLENAR EL CAMPO EN EL DETALLE
            // Usamos el signo "&" antes de $fila para modificar el array original directamente
            foreach ($data_deta as &$fila) {
                $fila['id_cot'] = $idNE;
            }            
            unset($fila); // Rompemos la referencia por seguridad

            // ===========================================================
            // PASO 2: INSERTAR EN EL MAESTRO (NOTAS DE ENTREGA NO FISCAL)
            // DETALLE
            // ===========================================================
            if (!empty($data_deta)) {
                $cols = "";
                $placeholders = "";
                $params = [];
                if ($row) {
                    if (count($row) > 1) {
                        foreach ($row as $key => $value) {
                            $cols .= " $key = :$key AND";
                        }
                        $cols = substr($cols, 0, -3);
                    } else {
                        foreach ($row as $key => $value) {
                            $cols .= " $key = :$key";
                            $params[":{$key}"] = $value;
                        }
                    }
                    $sql03 = "DELETE FROM f60031 WHERE $cols";
                    $stmt03 = $link->prepare($sql03);
                    $stmt03->execute($params);                 
                }
                $cols = "";
                $placeholders = "";
                $params = [];
                foreach ($data_deta[0] as $key => $values) { //INSERT INTO table (campo1, campo2) VALUES (:placeholder, :placeholder)
                    $cols .= "{$key}, ";
                    $placeholders .= ":{$key}, ";
                }
                $cols = substr($cols, 0, -2);
                $placeholders = substr($placeholders, 0, -2);
                $sql03 = "INSERT INTO f60031 ({$cols}) VALUES ({$placeholders})";

                $stmt03 = $link->prepare($sql03);
                // LA CLAVE: Vinculamos los valores uno por uno explícitamente en cada iteración
                foreach ($data_deta as $producto) {
                    foreach ($producto as $key => $value) {
                        $params[":{$key}"] = $value;
                    }
                    // Ejecutamos pasando el array limpio. Esto fuerza a PDO a enviar los nuevos datos                    
                    $stmt03->execute($params);                    
                    // Opcional: Limpiamos el cursor para que quede libre para la siguiente vuelta
                    $stmt03->closeCursor();
                }
            }
            
            // ==============================================================
            // PASO 3: INSERTAR EN EL ENCABEZADO DEL MOVIMIENTO DE INVENTARIO
            // ==============================================================
            //If existe lo elimino

            if ($data_mov_exist) {
                // 1. Extraemos solo los IDs en un array plano y removemos los duplicados
                // 'array_column' saca solo los 'id_movinv' y 'array_unique' elimina los repetidos
                $ids_limpios = array_unique(array_column($data_mov_exist, 'id_movinv'));

                // 2. Preparamos los DELETE en tus dos tablas (una sola vez fuera del ciclo)
                // Suponiendo que tus tablas son f4111 y f41111 (Historial y Detalle del movimiento)
                $sqlDeleteDeta = "DELETE FROM f40091 WHERE id_movinv = :id_movinv";
                $stmtDeleteDeta   = $link->prepare($sqlDeleteDeta);

                $sqlDeleteEnca = "DELETE FROM f4009 WHERE id_movinv = :id_movinv";
                $stmtDeleteEnca   = $link->prepare($sqlDeleteEnca);

                // 3. Recorremos los IDs únicos y ejecutamos el borrado en cadena
                foreach ($ids_limpios as $id_mov) {

                    // Ejecutamos primero el detalle por integridad referencial (llaves foráneas)
                    $stmtDeleteDeta->execute([':id_movinv' => $id_mov]);

                    // Ejecutamos luego el encabezado
                    $stmtDeleteEnca->execute([':id_movinv' => $id_mov]);

                    // Limpiamos los cursores para la siguiente vuelta del ciclo
                    $stmtDeleteDeta->closeCursor();
                    $stmtDeleteEnca->closeCursor();
                }
            }
            //Actualizar el proximo Numero de Movimiento
            $id_tmoinv  = $data_mov_enca['id_tmovinv'];
            $proximo_tmoinv =  $data_mov_enca['num_movinv'] + 1;
            $sql04 = "UPDATE f4006 SET proximo_tmoinv = :proximo_tmoinv WHERE id_tmoinv = :id_tmoinv ";
            $stmt04 = $link->prepare($sql04);
            $stmt04->execute([
                ':proximo_tmoinv' => $proximo_tmoinv,
                ':id_tmoinv' => $id_tmoinv
            ]);
            //Guardar Encabezado de Movimiento de Salida
            $cols = "";
            $placeholders = "";
            $params = [];
            foreach ($data_mov_enca as $key => $values) { //INSERT INTO table (campo1, campo2) VALUES (:placeholder, :placeholder)
                $cols .= "{$key}, ";
                $placeholders .= ":{$key}, ";
                $params[":{$key}"] =  $values;
            }
            $params[":id_cot"] =  $idNE;
            $params[":id_vend"] =  $data_enca['id_vend'];
            $cols = substr($cols, 0, -2);
            $placeholders = substr($placeholders, 0, -2);
            $sql05 = "INSERT INTO f4009 ({$cols}) VALUES ({$placeholders})";                        
            $stmt05 = $link->prepare($sql05);
            $stmt05->execute($params);            
            $idMov = $link->lastInsertId();                          
            // RELLENAR EL CAMPO EN EL DETALLE
            // Usamos el signo "&" antes de $fila para modificar el array original directamente
            foreach ($data_mov_deta as &$fila) {
                $fila['id_movinv'] = $idMov;
            }
            unset($fila); // Rompemos la referencia por seguridad        
            
            // ===========================================================
            //Guardar Detalle de Movimiento de Salida
            if (!empty($data_mov_deta)) {
                $cols = "";
                $placeholders = "";
                foreach ($data_mov_deta[0] as $key => $values) { //INSERT INTO table (campo1, campo2) VALUES (:placeholder, :placeholder)
                    $cols .= "{$key}, ";
                    $placeholders .= ":{$key}, ";
                }
                $cols = substr($cols, 0, -2);
                $placeholders = substr($placeholders, 0, -2);
                $sql06 = "INSERT INTO f40091 ({$cols}) VALUES ({$placeholders})";

                $stmt06 = $link->prepare($sql06);
                // LA CLAVE: Vinculamos los valores uno por uno explícitamente en cada iteración
                foreach ($data_mov_deta as $producto) {
                    $params = [];
                    foreach ($producto as $key => $value) {
                        $params[":{$key}"] = $value;
                        $params[":flete"] = 0;
                        $params[":otros_cargos"] = 0;
                        $params[":door_cargos"] = 0;
                    }
                    // Ejecutamos pasando el array limpio. Esto fuerza a PDO a enviar los nuevos datos
                    $stmt06->execute($params);                    
                    // Opcional: Limpiamos el cursor para que quede libre para la siguiente vuelta
                    $stmt06->closeCursor();
                }
            }                        
            // ==========================================
            // CREAR MOVIMIENTO EN CASO DE QUE SEA UNA ENTREGA A CONSIGNADO OSEA NO ES UNA VENTA DIRECTA
            // ==========================================              
            if ($consig) {                
                if ($data_mov_existC) {
                    // 1. Extraemos solo los IDs en un array plano y removemos los duplicados
                    // 'array_column' saca solo los 'id_movinv' y 'array_unique' elimina los repetidos
                    $ids_limpios = array_unique(array_column($data_mov_existC, 'id_movinv'));

                    // 2. Preparamos los DELETE en tus dos tablas (una sola vez fuera del ciclo)
                    // Suponiendo que tus tablas son f4111 y f41111 (Historial y Detalle del movimiento)
                    $sqlDeleteDeta = "DELETE FROM f40091 WHERE id_movinv = :id_movinv";
                    $stmtDeleteDeta   = $link->prepare($sqlDeleteDeta);

                    $sqlDeleteEnca = "DELETE FROM f4009 WHERE id_movinv = :id_movinv";
                    $stmtDeleteEnca   = $link->prepare($sqlDeleteEnca);

                    // 3. Recorremos los IDs únicos y ejecutamos el borrado en cadena
                    foreach ($ids_limpios as $id_mov) {

                        // Ejecutamos primero el detalle por integridad referencial (llaves foráneas)
                        $stmtDeleteDeta->execute([':id_movinv' => $id_mov]);

                        // Ejecutamos luego el encabezado
                        $stmtDeleteEnca->execute([':id_movinv' => $id_mov]);

                        // Limpiamos los cursores para la siguiente vuelta del ciclo
                        $stmtDeleteDeta->closeCursor();
                        $stmtDeleteEnca->closeCursor();
                    }
                }
                //Actualizar el proximo Numero de Movimiento
                $id_tmoinv  = $data_mov_encaC['id_tmovinv'];
                $proximo_tmoinv =  $data_mov_encaC['num_movinv'] + 1;
                $sql04 = "UPDATE f4006 SET proximo_tmoinv = :proximo_tmoinv WHERE id_tmoinv = :id_tmoinv ";
                $stmt04 = $link->prepare($sql04);
                $stmt04->execute([
                    ':proximo_tmoinv' => $proximo_tmoinv,
                    ':id_tmoinv' => $id_tmoinv
                ]);
                //Guardar Encabezado de Movimiento de Salida
                $cols = "";
                $placeholders = "";
                $params = [];
                foreach ($data_mov_encaC as $key => $values) { //INSERT INTO table (campo1, campo2) VALUES (:placeholder, :placeholder)
                    $cols .= "{$key}, ";
                    $placeholders .= ":{$key}, ";
                    $params[":{$key}"] =  $values;
                }
                $cols = substr($cols, 0, -2);
                $placeholders = substr($placeholders, 0, -2);
                $sql05 = "INSERT INTO f4009 ({$cols}) VALUES ({$placeholders})";
                $stmt05 = $link->prepare($sql05);
                $stmt05->execute($params);
                $idMov = $link->lastInsertId();
                // RELLENAR EL CAMPO EN EL DETALLE
                // Usamos el signo "&" antes de $fila para modificar el array original directamente
                foreach ($data_mov_detaC as &$fila) {
                    $fila['id_movinv'] = $idMov;
                }
                unset($fila); // Rompemos la referencia por seguridad        

                // ===========================================================
                //Guardar Detalle de Movimiento de Salida
                if (!empty($data_mov_detaC)) {
                    $cols = "";
                    $placeholders = "";
                    foreach ($data_mov_detaC[0] as $key => $values) { //INSERT INTO table (campo1, campo2) VALUES (:placeholder, :placeholder)
                        $cols .= "{$key}, ";
                        $placeholders .= ":{$key}, ";
                    }
                    $cols = substr($cols, 0, -2);
                    $placeholders = substr($placeholders, 0, -2);
                    $sql06 = "INSERT INTO f40091 ({$cols}) VALUES ({$placeholders})";

                    $stmt06 = $link->prepare($sql06);
                    // LA CLAVE: Vinculamos los valores uno por uno explícitamente en cada iteración
                    foreach ($data_mov_detaC as $producto) {
                        $params = [];
                        foreach ($producto as $key => $value) {
                            $params[":{$key}"] = $value;
                        }
                        // Ejecutamos pasando el array limpio. Esto fuerza a PDO a enviar los nuevos datos
                        $stmt06->execute($params);
                        // Opcional: Limpiamos el cursor para que quede libre para la siguiente vuelta
                        $stmt06->closeCursor();
                    }
                }
            }
          
            // =================================================================================
            //Generar las cuentas por cobrar en caso de que sea Facturación y/o Nota de Credito.
            //Guardar  de CXC
            //Detalle de Ventas
            //Concepto de Ventas
            if($cxc){   
                      ;
                //Borrar detalles en caso de existir
                $sql_det_cxc  = "DELETE FROM f60032 WHERE id_cot = :id_cot";
                $stmtDelDetCxc = $link->prepare($sql_det_cxc);
                $stmtDelDetCxc->execute([':id_cot' => $idNE]);                                
                //Guardar Concepto de Detalle de Ventas
                $det_ventas = "SELECT a.id_cot, a.iva_prod, SUM(a.sub_total) monto, SUM(a.mon_iva) mon_iva FROM f60031 a WHERE a.id_cot = :id_cot GROUP BY a.id_cot, a.iva_prod;";
                $stmtDetCxc = $link->prepare($det_ventas);                                
                $stmtDetCxc->execute([':id_cot' => $idNE]);                
                $rowDetCxc = $stmtDetCxc->fetchAll(PDO::FETCH_ASSOC);                
                $sqlInsertdetCxc = "INSERT INTO f60032 (id_cot, id_concxc, iva, id_aux, monto, mon_iva, create_user, create_date) VALUES (:id_cot, :id_concxc, :iva, :id_aux, :monto, :mon_iva, :create_user, :create_date)";
                $stmtInsCxcDet = $link->prepare($sqlInsertdetCxc);
                //recorrer e insertar registros                                
                $create_user = $_SESSION['id_user'];
                $create_date = getAuditoria();
                //Buscar concepto de Ventas
                $sqlConVen = FacturacionModel::tip_doc_fac($_POST['id_emp']);;
                $id_concxc = $sqlConVen['id_con_sales'];
                $id_aux = $sqlConVen['id_ctbaux'] ?? '';

                foreach ($rowDetCxc as $row){
                    $paramsDet = [
                        ':id_cot' => $idNE, 
                        ':id_concxc' => $id_concxc, 
                        ':iva' => $row['iva_prod'], 
                        ':id_aux' => $id_aux, 
                        ':monto' => $row['monto'], 
                        ':mon_iva' => $row['mon_iva'], 
                        ':create_user' => $create_user, 
                        ':create_date'=> $create_date
                    ];
                    //Insertar registro
                    $stmtInsCxcDet->execute($paramsDet);
                }
                $stmtInsCxcDet->closeCursor();



            }
            // ==========================================
            // ULTIMO PASO: SI TODO SALIÓ BIEN, CONFIRMAR DATOS
            // ==========================================               
            $link->commit();
            return true;
        } catch (\PDOException $e) {                        
            $link->rollback();            
            throw new Exception($e->getMessage(), $e->getCode());
            return false;
        }
    }
    //Eliminar registro
    static function destroy(int $id){
        $db = new Conexion(); //Obtener la instancia del PDO
        $link = (object)$db->conect();
        try {            
            $link->beginTransaction();
            $r = DelnotnotfisModel::selectEncyDetmovinv($id);
            $delDeta = "DELETE FROM f40091 WHERE id_movinv = :id_movinv";
            $delEnca = "DELETE FROM f4009  WHERE id_movinv = :id_movinv";
            foreach($r as $row){
                debug($row);
                $id_movinv = $row['id_movinv'];     
                //Eliminar Detalle           
                $stmtDelDeta = $link->prepare($delDeta);
                $stmtDelDeta->execute([':id_movinv' => $id_movinv]);
                $stmtDelDeta->closeCursor();
                //Eliminar Encabezado
                $stmtDelEnca = $link->prepare($delEnca);
                $stmtDelEnca->execute([':id_movinv' => $id_movinv]);
                $stmtDelEnca->closeCursor();
            }
            //Eliminar Detalles en caso de que sea CXC
            $sqlDelDeta = "DELETE FROM f600032 WHERE id_cot = :id_cot";
            $stmtDelDeta = $link->prepare($sqlDelDeta);
            $stmtDelDeta->execute([':id_cot' => $id]);
            $stmtDelDeta->closeCursor();
            //Actualizar Encabezado
            $UpdEnca = "UPDATE f6003 SET status = 99 WHERE id_cot = :id_cot";
            $stmtEnca = $link->prepare($UpdEnca);
            $stmtEnca->execute([':id_cot' => $id]);
            $stmtEnca->closeCursor();    
            $link->commit();
        } catch (\PDOException $e) {
            $link->rollback();            
            throw new Exception($e->getMessage(), $e->getCode());
            return false;
        }
    }
}
