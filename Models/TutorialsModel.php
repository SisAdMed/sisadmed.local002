<?php
class TutorialsModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function cargar_screen_main(){
        $sql = "SELECT * FROM f0027";
        $r = DB::query($sql);
        return $r;
    }
    static function guardar(array $data){
        $db = new Conexion(); //Obtener la instancia del PDO
        $link = (object)$db->conect();   
        try {
            $link->beginTransaction();
            $cols = "";
            $values = "";
            foreach ($data as $campo => $value) {
                $cols .= "{$campo}, ";
                $values .= ":{$campo}, ";
            }
            $cols = substr($cols, 0, -2);
            $values = substr($values, 0, -2);
            $sql = "INSERT INTO f0027 ({$cols}) VALUES ({$values})";   
            $params = [];
            foreach ($data as $campo => $value) {
                $params[":{$campo}"] = $value;
            }                  
            $stmt = $link->prepare($sql);               
            $stmt->execute($params);
            $id = $link->lastInsertId();      
            $stmt->closeCursor();                  
            $link->commit();
            return $id;
        } catch (\PDOException $e) {                             
            $link->rollback();            
            throw new Exception($e->getMessage(), $e->getCode());
            return false;
        }
    }
    static function actualizar(array $data, int $id){        
        $db = new Conexion(); //Obtener la instancia del PDO
        $link = (object)$db->conect();           
        try {
            $link->beginTransaction();   
            $cols = "";
            $values = "";
            foreach ($data as $campo => $value) {            
                $values .= " {$campo} = :{$campo},";
            }
            $values = substr($values, 0, -1);                    
            foreach ($data as $campo => $value) {            
                $cols .= " $campo = :$campo";
            }
            $cols = substr($cols, 0, -2);            
            $sql = "UPDATE f0027 SET $values WHERE id = :id";                                                   
            $stmt = $link->prepare($sql);               
            $params = array_merge($data, ['id' => $id]);            
            $stmt->execute($params);              
            $stmt->closeCursor();                  
            $link->commit();            
        } catch (\PDOException $e) {              
            $link->rollback();            
            throw new Exception($e->getMessage(), $e->getCode());
            return false;
        }
    }
    static function edit(int $id){
        $sql = "SELECT * FROM f0027 WHERE id = $id";
        $r = DB::query($sql);
        return $r[0];
    }
    static function show_row(int $id){        
        $sql = "SELECT * FROM f0027 WHERE id = $id";
        $r = DB::query($sql);        
        return $r[0];
    }
    static function getImageNew(int $id){
        $sql = "SELECT imagen FROM f0027 WHERE id = $id";
        $r = DB::query($sql);
        return $r[0];
    }
    static function destroy(int $id){
        $db = new Conexion(); //Obtener la instancia del PDO
        $link = (object)$db->conect();
        try {
            $link->beginTransaction();
            //Verificar si esta publicado
            $sql_sel = "SELECT * FROM f0027 WHERE id = :id";
            $stmt = $link->prepare($sql_sel);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $view_internet = $row['view_internet'];            
            if ($view_internet === 1) {
                return 0;
            }
            $stmt->closeCursor();
            //Eliminar Registro
            $sql_del = "DELETE FROM f0027 WHERE id = :id";
            $stmt_del = $link->prepare($sql_del);
            $stmt_del->execute([':id' => $id]);
            $stmt_del->closeCursor();
            $link->commit();     
            return true;            
        } catch (\PDOException $e) {
            $link->rollback();            
            throw new Exception($e->getMessage(), $e->getCode());
            return false;
        }
    }
}