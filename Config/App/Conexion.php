<?php
class Conexion{
    private PDO $conect;
    public function __construct(){
        $connectionString = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        try {
            $options = [
                PDO::ATTR_EMULATE_PREPARES => true, // Requerido para múltiples consultas en una cadena
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];
            $this->conect = new PDO($connectionString, DB_USER, DB_PASSWORD, $options);            
            //echo "La conexion es exitosa";
        } catch (PDOException $e) {
            echo "ERROR: " . $e->getMessage() . '\n';
        }
    }
    public function conect(){
        return $this->conect;
    }
    public static function query(string $sql, array $params = []){
        $db = new Conexion();
        $link = (object)$db->conect();
        $link->beginTransaction(); //por cualquier error, checkpoint
        $query = $link->prepare($sql);
        if (!$query->execute($params)) {
            $link->rollback();
            $error = $query->errorInfo();
            throw new Exception($error[2]);
        }
        // SELECT | INSERT | UPDATE | DELETE | ALTER TABLE
        // Manejo del tipo de query
        //SELECT * FROM table       
        if (strpos($sql, 'SELECT') !== false) {
            return $query->rowCount() > 0 ? $query->fetchAll(PDO::FETCH_ASSOC) : false;
        }elseif(strpos($sql, 'INSERT') !== false){
            $id = $link->lastInsertId();
            $link->commit();
            return $id;
        }elseif(strpos($sql, 'UPDATE') !== false){
            $link->commit();
            return true;
        }elseif(strpos($sql, 'DELETE') !== false){
            if ($query->rowCount() > 0){
                $link->commit();
                return true;
            }
            $link->rollBack();
            return false; //no se borro nada
        }elseif(strpos($sql, 'CREATE') !== false){
            //$link->commit();
            return true;    
        }elseif(strpos($sql, 'DROP') !== false){            
            //$link->commit();
            return true;    
        }else {
            //alter table
            $link->commit();
            return true;
        }
    }
}
