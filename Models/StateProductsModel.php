<?php
class StateProductsModel extends DB{    
    public function __construct(){
        parent::__construct();
    }
    static function cargar_screen_main(){
        $sql = "SELECT a.id, a.estado, a.icono, a.status, CONCAT(b.name_user, ' ', b.last_user) create_for, CONCAT(c.name_user, ' ', c.last_user) modify_for FROM f4018 a INNER JOIN f0002 b ON b.id_user = a.create_user LEFT OUTER JOIN f0002 c ON c.id_user = a.modify_user";        
        $r = DB::query($sql);     
        return $r;   
    }
    static function new_row(array $data){                     
        $db = new Conexion(); //Obtener la instancia del PDO
        $link = (object)$db->conect();  
        //
        $cols = "";
        $placeholders = "";   
        $params = [];                 
        try {
            $link->beginTransaction();
            foreach ($data as $key => $values) { //INSERT INTO table (campo1, campo2) VALUES (:placeholder, :placeholder)
                $cols .= "{$key}, ";
                $placeholders .= ":{$key}, ";
                $params[":{$key}"] =  $values;
            }
            $cols = substr($cols, 0, -2);
            $placeholders = substr($placeholders, 0, -2);
            $sql01 = "INSERT INTO f4018 ({$cols}) VALUES ({$placeholders})";            
            $stmt = $link->prepare($sql01);            
            $stmt->execute($params);            
            $id = $link->lastInsertId();
            //Cargar imagen
            if(isset($_FILES['foto_producto']) && $_FILES['foto_producto']['error'] === UPLOAD_ERR_OK){
                $ruta = ROOT . DS. 'Assets' . DS . 'img/';
                $archivo = $_FILES['foto_producto'];
                $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
                $permitidas = ['jpg', 'jpeg', 'png', 'webp', 'ico'];
                if(!is_dir($ruta)){
                    mkdir($ruta, 0777, true);
                    chmod($ruta, 0777);
                }
                if(in_array($extension, $permitidas)){
                    $nombreUnico = 'estado_' . date('Ymd_His') . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
                    $rutaDestino = $ruta . DS . $nombreUnico;
                    if(move_uploaded_file($archivo['tmp_name'], $rutaDestino)){
                        $sql_up = "UPDATE f4018 SET icono = :icono WHERE id = :id";
                        $stmt_upd = $link->prepare($sql_up);
                        $stmt_upd->execute([':icono' => $nombreUnico, ':id' => $id]);
                    }
                }
            }
            $link->commit();  
            return true;
        } catch (\PDOException $e) {                                    
            $link->rollback();            
            throw new Exception($e->getMessage(), $e->getCode());
            return false;
        }        
    }
    static function upd_row(){
        return true;
    }
    static function edit(int $id){
        $sql = "SELECT id, estado, icono FROM f4018 WHERE id = {$id}";
        $r = DB::query($sql);
        return $r[0];
    }
    static function show_row(int $id){
        $sql = "SELECT * FROM f4018 WHERE id = {$id}";
        $r = DB::query($sql);
        return $r[0];
    }
    static function destroy(int $id){
        $db = new Conexion(); //Obtener la instancia del PDO
        $link = (object)$db->conect();  
        try {
            $link->beginTransaction();
            $sqldel = "DELETE FROM f4018 WHERE id = :id";
            $stmtdel = $link->prepare($sqldel);
            $stmtdel->execute([':id' => $id]);
            $link->commit();  
            return "ok";
        } catch (\PDOException $e) {             
            $link->rollback();            
            if($e->getCode() == '23000' || (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1451)){
                return $e->getCode();
            }
            throw new Exception($e->getMessage(), $e->getCode());
            return error;
        }
    }
    static function getStateProducts(){
        $sql = "SELECT id, estado FROM f4018 WHERE status = 1";
        return $r = DB::query($sql);
    }
}