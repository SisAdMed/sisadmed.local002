<?php
class MovInvModel extends DB{
    #[Override]
    public function __construct(){
        parent::__construct();
    }
    static function cargar_screen_main(){
        $sql = "SELECT DISTINCT a.id_movinv, b.nombre_emp, c.cod_tmoinv, c.nom__tmoinv, a.fecha_comp, a.status, a.num_movinv, a.origen, e.nom_ent FROM f4009 a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f4006 c ON c.id_tmoinv = a.id_tmovinv INNER JOIN f40091 d ON d.id_movinv = a.id_movinv LEFT OUTER JOIN f0014 e ON e.id_ent = a.id_cli";        
        $r = DB::query($sql);
        return $r;
    }
    static function cons_producto(int $id){
        $r = DB::query("SELECT * FROM f4005 WHERE id_prod = {$id}");
        return $r[0];
    }
    static function guardar(array $enca, array $deta, $row = null){        
        $db = new Conexion(); //Obtener la instancia del PDO
        $link = (object)$db->conect();    
        $next_number_flag = false;   
        $id_tmoinv  = $enca['id_tmovinv'];
        $next_number = $enca['num_movinv'];
        try {
            $link->beginTransaction();           
            $sql_con = "SELECT * FROM f4006 WHERE id_tmoinv = :id_tmoinv";
            $stmt_con = $link->prepare($sql_con);
            $stmt_con->execute([':id_tmoinv' => $id_tmoinv]);
            $fila = $stmt_con->fetch(PDO::FETCH_ASSOC);
            $nom__tmoinv = $fila['nom__tmoinv'];
            if(empty($row)){
                 //Verificar si usa consecutivo                
                //Proximo consecutivo                               
                if($fila){
                    if($fila['consecutiv__tmoinv'] == "1"){
                        $next_number_flag = true;   
                        $next_number = floatval($fila['proximo_tmoinv']);
                        $enca['num_movinv'] = $next_number;
                    }
                }
            }
            // =======================================================================
            // PASO 1: INSERTAR Y/O ACTUALIZAREN EL MAESTRO (MOVIMEINTO DE INVENTARIO)
            // ENCABEZADO
            // =======================================================================
            $cols = "";
            $placeholders = "";
            $params = [];
            if(empty($row)){
                // 1. Obtener nombres de columnas
                $cols = array_keys($enca);     
                // 2. Crear placeholders (:campo1, :campo2, ...)
                $placeholders = array_map(function($col) {
                    return ":{$col}";
                }, $cols);
                // 3. Llenar el array de parámetros sin los dos puntos en la clave
                foreach ($enca as $key => $value) {
                    $params[$key] = $value;
                }
                // 4. Armar la sentencia SQL
                $colsStr         = implode(", ", $cols);
                $placeholdersStr = implode(", ", $placeholders);
                $sql             = "INSERT INTO f4009 ({$colsStr}) VALUES ({$placeholdersStr})";
                $stmt = $link->prepare($sql);                
                $stmt->execute($params);
                $idNE = $link->lastInsertId();
            }else{
                // Para el UPDATE
                $idNE = $row;
                $setClauses = [];
                foreach ($enca as $key => $value) {
                    if ($key !== 'id_tmovinv') { // Excluir la llave primaria del SET
                        $setClauses[] = "{$key} = :{$key}";
                        $params[$key] = $value;
                    }
                }    
                // Parámetro para la condición WHERE
                $params['id_movinv'] = $row;
                $sql = "UPDATE f4009 SET " . implode(", ", $setClauses) . " WHERE id_movinv = :id_movinv";                  
                $stmt = $link->prepare($sql);                
                $stmt->execute($params);                                
            }
            // =======================================================================
            // PASO 2: INSERTAR Y/O ACTUALIZAREN EL DETALLE (MOVIMEINTO DE INVENTARIO)
            // DETALLE
            // =======================================================================   
            // RELLENAR EL CAMPO EN EL DETALLE
            // Usamos el signo "&" antes de $fila para modificar el array original directamente            
            if(empty($row)){  
                if(is_array($deta)) {
                    foreach ($deta as &$fila) {
                        if(is_array($fila)){
                            $fila['id_movinv'] = $idNE;
                        } elseif (is_object($fila)){
                            $fila->id_movinv = $idNE;
                        }
                    }
                    unset($fila); // Rompemos la referencia por seguridad
                }
                
            }
            //Eliminar detalle en caso de que exista
            $params = [];
            $sql_del = "DELETE FROM f40091 WHERE id_movinv = :id_movinv";
            $stmt_del = $link->prepare($sql_del);
            $stmt_del->execute([':id_movinv' => $idNE]);
            // 1. Obtener nombres de columnas y limpiar espacios a partir de la primera fila
            $primeraFila = reset($deta);
            $cols    = array_map('trim', array_keys($primeraFila)); 
           // 2. Generar columnas y placeholders (:campo1, :campo2, ...)
            $colsStr         = implode(", ", $cols);
            $placeholdersStr = implode(", ", array_map(function($col) { return ":{$col}"; }, $cols));
            // 3. Llenar el array de parámetros sin los dos puntos en la clave
            foreach ($deta as $key => $value) {
                $params[$key] = $value;
            }
            // 3. Preparar la sentencia SQL una sola vez (más eficiente)
            $sql  = "INSERT INTO f40091 ({$colsStr}) VALUES ({$placeholdersStr})"; // Cambia f4010 por tu tabla de detalle
            $stmt = $link->prepare($sql);
            // 4. Recorrer cada registro, armar sus parámetros y ejecutar
            foreach ($deta as $fila) {
                $params = [];        
                foreach ($fila as $campo => $valor) {
                    $campoLimpio = trim($campo);
                    // Convertir cadenas vacías a null (útil para lote, fechas vacías, etc.)
                    $params[$campoLimpio] = ($valor === '') ? null : $valor;
                }
                // Ejecutar inserción para la fila actual
                $stmt->execute($params);
            }
             // 3. Actualizar proximo numero del Tipo de Movimiento
            if($next_number_flag){
                $sql = "UPDATE f4006 SET proximo_tmoinv = :proximo_tmoinv, modify_user = :modify_user, modify_date = :modify_date WHERE id_tmoinv = :id_tmoinv";
                $stmt_upd = $link->prepare($sql);
                $stmt_upd->execute([
                    ':proximo_tmoinv' => $next_number + 1,
                    ':modify_user' => $_SESSION['id_user'],
                    ':modify_date' => getAuditoria(),
                    ':id_tmoinv' => $id_tmoinv,
                ]);
                $stmt_upd->closeCursor();
            }                
            $link->commit();
            $title = "Movimiento de Inventario modificado existosamente.";
            $msg = sprintf('Se ha modificado el Movimiento de Inventario %s número %s, satisfactoriamente', $nom__tmoinv, $next_number);            
            if(empty($row)){
                $title = "Movimiento de Inventario agregado existosamente.";
                $msg = sprintf('Se ha agregado el Movimiento de Inventario %s número %s, satisfactoriamente', $nom__tmoinv, $next_number);
            }
            return [
                "title" => $title,
                "icon" => 'success',               
                "msg" => $msg
            ];
        } catch (\PDOException $e) {              
            $link->rollback();                        
            return [
                "title" => 'Se ha presentado un error',
                "icon" => 'error',
                "msg" => sprintf('Se ha presentado el error %s al registror el Movimiento de Inventario, por favor verifique.', $e->getMessage())
            ];
        }
    }
    static function edit(int $id){
        $sql = "SELECT a.id_movinv, b.nom__tmoinv, a.num_movinv FROM f4009 a INNER JOIN f4006 b on b.id_tmoinv = a.id_tmovinv WHERE a.id_movinv = {$id}";
        $r = DB::query($sql);
        return $r[0];
    }
    static function show_row (int $id){
        $sql = "SELECT a.id_movinv, b.id_tmoinv, a.id_emp, a.num_movinv, a.fecha_comp, a.id_alm, a.descrip_movinv, a.status, a.id_moneda, a.tasa_cambio, d.id_prod, d.id_ubi, d.lote, d.fec_venc, d.cantidad, e.nom_prod, f.nom_ubi, e.lote_prod FROM f4009 a INNER JOIN f4006 b ON b.id_tmoinv = a.id_tmovinv INNER JOIN f0011 c ON c.id_emp = a.id_emp INNER JOIN f40091 d ON d.id_movinv = a.id_movinv INNER JOIN f4005 e ON e.id_prod = d.id_prod INNER JOIN f4001 f ON f.id_ubi = d.id_ubi WHERE a.id_movinv = {$id}";        
        $r = DB::query($sql);
        return $r;
    }
    static function destroy (int $id){
        $db = new Conexion(); //Obtener la instancia del PDO
        $link = (object)$db->conect();    
        try {
            $link->beginTransaction();        
            //Query
            $sql_det = "DELETE FROM f40091 WHERE id_movinv = :id_movinv";
            $sql_enc = "DELETE FROM f4009  WHERE id_movinv = :id_movinv";
            //preparar query
            $stmt_det = $link->prepare($sql_det);
            $stmt_enc = $link->prepare($sql_enc);
            //Ejecutar
            $stmt_det->execute([':id_movinv' => $id]);
            $stmt_enc->execute([':id_movinv' => $id]);
            //Confirmar cambios           
            $link->commit();
            return ['status' => true];
        } catch (\PDOException $e) {              
            $link->rollback();                        
            return [
                "status" => false,
                "title" => 'Se ha presentado un error',
                "icon" => 'error',
                "msg" => sprintf('Se ha presentado el error %s al eliminar el Movimiento de Inventario, por favor verifique.', $e->getMessage())
            ];
        }
    }
    static function conmovinv($id_emp, $fec_ini, $fec_fin, $id_alm, $id_fab, $id_prod, $id_ubi ){
        $filter = '';
        $filter_prod = '';
        if(!$id_emp){            
            //$filter .= " AND b.id_emp = {$id_emp}";
            $id_emp = 0;
            $id_ubi = 0;
        }
        if($id_fab){
            $filter .= ' AND e.id_fab IN ('.$id_fab.') ';
        }
        if($id_prod){
            $filter .= " AND b.id_prod = '{$id_prod}'";
            $filter_prod .= " WHERE a.id_prod = '{$id_prod}' ";
        }       
        if($id_ubi){
            $filter .= " AND b.id_ubi = {$id_ubi}";
        }else{
            $id_ubi = 0;
        }
        if($id_ubi === null){
            $id_ubi = 0;
        }
        $sql = "SELECT $id_emp id_emp,  a.id_prod, ' ' fecha_comp, 0 num_movin, ' ' nombre_emp, CONCAT(a.nom_prod, ' Referencia: ', a.ref_prod) nom_prod, 0 id_alm, ' ' nom_alm, ' ' cod_tmoinv, ' ' nom_tmoinv, ' ' descrip_movinv, ' 'origen, 0 id_cot, 0 id_fab, nom_fab, 0 entradas, 0 salidas, ' ' nom_ent, fn_saldo_ant_inv($id_emp, a.id_prod, '$id_alm', '$fec_ini',  $id_ubi) saldo, ' ' cod_prod, ' ' ref_prod  FROM f4005 a INNER JOIN f4003 b ON b.id_fab = a.id_fab $filter_prod UNION SELECT d.id_emp, e.id_prod, a.fecha_comp, a.num_movinv, d.nombre_emp,  CONCAT(e.nom_prod, ' Referencia: ', e.ref_prod) nom_prod,  f.id_alm, f.nom_alm, c.cod_tmoinv, c.nom__tmoinv, a.descrip_movinv, IFNULL(a.origen, ' ') origen, h.id_cot, i.id_fab, i.nom_fab, CASE c.tipo_tmoinv WHEN 'E' THEN SUM(b.cantidad) ELSE 0 END entradas, CASE c.tipo_tmoinv WHEN 'S' THEN SUM(b.cantidad) ELSE 0 END salidas, IFNULL(j.nom_ent, IFNULL(LTRIM(RTRIM(z.nom_ent)), a.descrip_movinv)) nom_ent, fn_saldo_ant_inv($id_emp, b.id_prod, '$id_alm', '$fec_ini',  $id_ubi) saldo, e.cod_prod, e.ref_prod FROM f4009 a INNER JOIN f40091 b ON b.id_movinv = a.id_movinv INNER JOIN f4006 c ON c.id_tmoinv = a.id_tmovinv INNER JOIN f0011 d ON d.id_emp = a.id_emp INNER JOIN f4005 e ON e.id_prod = b.id_prod INNER JOIN f4002 f ON f.id_alm = a.id_alm  INNER JOIN f4003 i ON i.id_fab = e.id_fab LEFT OUTER JOIN f6001 g ON g.id_emp = substr(a.origen, 8,1) AND g.tipo_codigo = substr(a.origen, 5,2) LEFT OUTER JOIN f6003 h ON h.id_emp = g.id_emp AND h.id_tdo = g.id_tdoc AND h.num_tdo = substr(a.origen, 10) LEFT OUTER JOIN f0014 j ON j.id_ent = h.id_cli AND substr(a.origen, 1,3) != 'COM' LEFT OUTER JOIN f3001 x ON x.id_emp = substr(a.origen, 8,1) AND x.tipo_codigo = substr(a.origen, 5,2) AND substr(a.origen, 1,3) = 'COM' LEFT OUTER JOIN f8020 y ON y.id_emp = x.id_emp AND y.id_tdo = x.id_tdoc AND y.num_tdo = substr(a.origen, 10) AND substr(a.origen, 1,3) = 'COM' LEFT OUTER JOIN f0014 z ON z.id_ent = y.id_cli AND substr(a.origen, 1,3) = 'COM' WHERE a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.id_alm IN ($id_alm) " .  $filter . " GROUP BY d.id_emp, e.id_prod, a.fecha_comp, a.num_movinv, d.nombre_emp, CONCAT(e.nom_prod, ' Referencia: ', e.ref_prod), f.id_alm, f.nom_alm, c.cod_tmoinv, c.nom__tmoinv, a.descrip_movinv, IFNULL(a.origen, ' '), h.id_cot, i.id_fab, i.nom_fab, IFNULL(j.nom_ent, IFNULL(LTRIM(RTRIM(z.nom_ent)), a.descrip_movinv)), fn_saldo_ant_inv($id_emp, b.id_prod, '$id_alm', '$fec_ini', $id_ubi), e.cod_prod, e.ref_prod ORDER BY 2, 3, 4;";
        $r = DB::query($sql);
        return $r;
    }
    static function update_lotes($origen){
        $sql = "SELECT c.id_movinv FROM f6003 a INNER JOIN f6001 b ON b.id_tdoc = a.id_tdo INNER JOIN f4009 c ON c.origen = CONCAT(a.id_cont, '-', b.tipo_codigo, '-', a.id_emp, '-', a.num_tdo ) INNER JOIN f4999 d ON d.id_emp = a.id_emp AND d.tmov_fac = c.id_tmovinv INNER JOIN f0014 e ON e.id_ent = a.id_cli AND e.req_exc_rat = 1 WHERE c.origen = '$origen'";
        return $r = DB::query($sql);
    }
    static function print_movement(int $id){
        $sql = "SELECT d.nombre_emp, d.logo, c.cod_tmoinv, c.nom__tmoinv, a.num_movinv, a.fecha_comp, e.codigo_moneda, a.tasa_cambio, a.origen, a.descrip_movinv, h.cod_prod, h.recar_prod, h.nom_prod, h.ref_prod, b.cantidad, b.lote, b.fec_venc, b.costo, b.flete, b.otros_cargos, b.costo1, f.cod_alm, f.nom_alm, j.cod_ubi, j.nom_ubi, CONCAT(g.name_user, ' ', g.last_user) create_user, a.create_date, CONCAT(z.name_user, ' ', z.last_user) modify_user, a.modify_date FROM `f4009` a INNER JOIN `f40091` b ON b.id_movinv = a.id_movinv INNER JOIN `f4006` c ON c.id_tmoinv = a.id_tmovinv INNER JOIN `f0011` d ON d.id_emp = a.id_emp INNER JOIN `f0005` e ON e.id_moneda = a.id_moneda INNER JOIN `f4002` f ON f.id_alm = a.id_alm LEFT OUTER JOIN `f0002` g ON g.id_user = a.create_user INNER JOIN `f4005` h ON h.id_prod = b.id_prod INNER JOIN `f4003` i ON i.id_fab = h.id_fab INNER JOIN `f4001` j ON j.id_ubi = b.id_ubi LEFT OUTER JOIN `f0002` z ON z.id_user = a.modify_user WHERE a.id_movinv = {$id}" ;
        $r = DB::query($sql);
        return $r;
    }
}