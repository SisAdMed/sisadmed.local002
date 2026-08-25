<?php
class ChangeUtilityModel extends DB
{
    public function __construct()
    {
        parent::__construct();
    }
    static function cargar_screen_main()
    {
        $sql = "SELECT a.id, a.fecha, CONCAT(b.name_user, ' ', b.last_user) creado_por, a.create_date, CONCAT(IFNULL(c.name_user, ''), ' ', IFNULL(c.last_user,'')) modificado_por, a.modify_date, status, aprobado FROM f4017 a INNER JOIN f0002 b ON b.id_user = a.create_user LEFT OUTER JOIN f0002 c on c.id_user = a.modify_user";
        return $r = DB::query($sql);
    }
    static function edit(int $id)
    {
        $sql = "SELECT id, fecha FROM f4017 WHERE id = {$id}";
        return $r = DB::query($sql);
    }
    static function store_utilidad($id, $registros = "")
    {
        if (is_string($registros)) {
            $registros = json_decode($registros, true);
        }
        $enca = $registros[0];
        $fecha = $enca['fecha'];
        $id_prod = 0;
        if (!empty($enca['id_prod_enca'])) {
            $id_prod = $enca['id_prod_enca'];
        }
        $id_fab = '';
        if (!empty($enca['id_fab_emca'])) {
            $id_fab = implode(',', $enca['id_fab_emca']);
        }
        $utilidad = $enca['util_enca'];
        $status = $enca['status'];

        $db = new Conexion();
        $link = $db->conect();
        try {
            $link->beginTransaction();
            $total_row = 0;
            $sql_enca = "INSERT INTO f4017 (fecha, id_prod, id_fab, utilidad, status, create_user, create_date) VALUES (:fecha, :id_prod, :id_fab, :utilidad, :status, :create_user, :create_date)";
            if ($id) {
                $sql_enca = "UPDATE f4017 SET fecha = :fecha, id_prod = :id_prod, id_fab = :id_fab, utilidad = :utilidad, status = :status, modify_user = :modify_user, modify_date = :modify_date WHERE id = :id";
            }
            $paramsEnca = [
                ':fecha'    => $fecha,
                ':id_prod'  => $id_prod,
                ':id_fab'   => $id_fab,
                ':utilidad' => $utilidad,
                ':status'   => $status,
            ];
            if (!$id) {
                $paramsEnca += [
                    ':create_user' => $_SESSION['id_user'],
                    ':create_date' => getAuditoria(),
                ];
            } else {
                $paramsEnca += [
                    ':modify_user'  => $_SESSION['id_user'],
                    ':modify_date'  => getAuditoria(),
                    ':id'            => $id
                ];
            }
            $stmtEnca = $link->prepare($sql_enca);
            $stmtEnca->execute($paramsEnca);
            if (!$id) {
                $id = $link->lastInsertId();
            }
            $stmtEnca->closeCursor(); // Limpiamos el cursor de actualización           
            //
            $sqlDetaD = 'DELETE FROM f40171 WHERE id_change_utility = :id';
            $stmtDetaD = $link->prepare($sqlDetaD);
            $stmtDetaD->execute([':id' => $id]);
            $stmtDetaD->closeCursor();
            //  
            $sqlDeta = "INSERT INTO f40171 (id_change_utility, id_prod, costo, utility_cur, price_cur, utility_new, price_new, create_user, create_date) VALUES (:id_change_utility, :id_prod, :costo, :utility_cur, :price_cur, :utility_new, :price_new, :create_user, :create_date)";
            //            
            $create_date = getAuditoria();
            foreach ($registros as $registro) {
                $id_prod = $registro['id_prod'];
                $id_fab = $registro['id_fab'] ?? null;
                $costo = $registro['costo1'];
                $recar_curr = $registro['util_cur'];
                $prec_curr = $registro['prec_cur'];
                $recar_prod = $registro['util_new'];
                $ventas_prod = $registro['prec_new'];
                $params01 = [
                    ':id_change_utility' => $id,
                    ':id_prod' => $id_prod,
                    ':costo' => $costo,
                    ':utility_cur' => $recar_curr,
                    ':price_cur' => $prec_curr,
                    ':utility_new' => $recar_prod,
                    ':price_new' => $ventas_prod,
                    ':create_user' => $_SESSION['id_user'],
                    ':create_date' => $create_date,

                ];
                //Ejecutar el Update del prodcuto
                $stmtDeta = $link->prepare($sqlDeta);
                $stmtDeta->execute($params01);
                $stmtDeta->closeCursor(); // Limpiamos el cursor de actualización                                              
            }
            $link->commit();
            return true;
        } catch (Exception $e) {
            $link->rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
            return false;
        }
    }
    static function show_row(int $id)
    {
        $sql = "SELECT * FROM f4017 WHERE id = {$id}";
        return $r = DB::query($sql);
    }
    static function show_row_det(int $id)
    {
        $sql = "SELECT  c.id_prod , c.cod_prod, c.ref_prod, c.nom_prod, c.costo1, b.utility_cur recar_prod, b.price_cur ventas_prod, utility_new util_new, price_new pre_new FROM f4017 a INNER JOIN f40171 b ON b.id_change_utility = a.id INNER JOIN f4005 c ON c.id_prod = b.id_prod WHERE a.id = {$id}";
        return $r = DB::query($sql);
    }
    static function destroy(int $id)
    {
        $db = new Conexion();
        $link = $db->conect();
        try {
            $link->beginTransaction();
            //Preapara query de Elimincación            
            //Procesar eliminación Detalle
            $sqldelDeta = "DELETE FROM f40171 WHERE id_change_utility = :id";
            $stmtdelDeta = $link->prepare($sqldelDeta);
            $stmtdelDeta->execute([':id' => $id]);
            $stmtdelDeta->closeCursor();
            //Procesar eliminación Encabezado
            $sqldelEnca = "DELETE FROM f4017  WHERE id = :id";
            $stmtdelEnca = $link->prepare($sqldelEnca);
            $stmtdelEnca->execute([':id' => $id]);
            $stmtdelEnca->closeCursor();
            $link->commit();
            return true;
        } catch (\PDOException $e) {
            $link->rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
            return false;
        }
    }
    static function approve(int $id)    
    {
        $db = new Conexion();
        $link = $db->conect();        
        try {
            // 1.- Iniciamos la transaccion MYSQL
            $link->beginTransaction();
            // 2.- Consultamos los registros de la Tabla de Origen
            $sql_por_apro = "SELECT utility_new, price_new, id_prod FROM f40171 WHERE id_change_utility = :id";            
            $stmtSelect = $link->prepare($sql_por_apro);
            $stmtSelect->execute([':id' => $id]);            
            $reg_sel = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);            
            // 3. Preparamos la consulta UPDATE
            $sqlUpdate = "UPDATE f4005 SET recar_prod = :recar_prod, ventas_prod = :ventas_prod, modify_user = :modify_user, modify_date = :modify_date WHERE id_prod = :id_prod";            
            $stmtUpdate = $link->prepare($sqlUpdate);
            // Datos de Auditoria
            $modify_user = $_SESSION['id_user'];
            $modify_date = getAuditoria();
            //  4. Recorrer los registros del selectet
            foreach ($reg_sel as $row) {                
                $paramsUpdate2 = [
                    ':recar_prod'  => $row['utility_new'],
                    ':ventas_prod' => $row['price_new'],
                    ':modify_user' => $modify_user,
                    ':modify_date' => $modify_date,
                    ':id_prod'     => $row['id_prod']
                ];                
                $stmtUpdate->execute($paramsUpdate2);                
            }
            $stmtUpdate->closeCursor();
            // 5.- Actualizar Tabla f4017 como aprobada            
            $sqUpdEnca = "UPDATE f4017 SET aprobado = :aprobado, modify_user = :modify_user, modify_date = :modify_date WHERE id = :id";
            $stmtEnca3 = $link->prepare($sqUpdEnca);
            $paramsUpdate3 = [
                ':aprobado'  => 1,                
                ':modify_user' => $modify_user,
                ':modify_date' => $modify_date,
                ':id'     => $id
            ];
            $stmtEnca3->execute($paramsUpdate3);            
            $stmtEnca3->closeCursor();            
            $link->commit();
            return true;
        } catch (\PDOException $e) {            
            $link->rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
            return false;
        }
    }
}
