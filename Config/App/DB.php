<?php
class DB extends Conexion{
    public static function consultarSQL(string $query){
        $link = new Conexion();
        $link = $link->conect();
        $resultado = $link->query($query);
        $array = [];
        while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)){
            $array[] = $registro;
        }
        $resultado->closeCursor();
        return $array;
    }
    /**
     * Consultar de forma plana un SQL
     *@param string
     */
    public static function SQL(string $query){
        $resultado = self::consultarSQL($query);
        return $resultado;
    }
    /*Listar registros desde la base de datos o un solo registro*/
    public static function listEqual(string $table, $params = [], $limit = null){
        $cols_Values = "";
        $limits = "";
        if (!empty($params)) {
            $cols_Values .= "WHERE "; //SELECT * FROM f0007 WHERE status_roll !=0
            foreach ($params as $key => $value) {
                $cols_Values .= "{$key} = :{$key} AND";
            }
            $cols_Values = substr($cols_Values, 0, -3);
        }
        if ($limit !== null) {
            $limits = " LIMIT {$limit}";
        }
        $stmt = "SELECT * FROM $table {$cols_Values}{$limits}";
        // llamar el query de la base de datos
        if (!$rows = parent::query($stmt, $params)) {
            return false;
        }
        return $limit === 1 ? $rows[0] : $rows;
    }
    /*JOIN*/
    public static function join(string $table1, string $table2, string $val1, string $val2, $params = [], $limit = null){
        $cols_Values = "";
        $limits = "";
        if (!empty($params)) {
            $cols_Values .= "WHERE "; //SELECT * FROM f0007 WHERE status_roll !=0
            foreach ($params as $key => $value) {
                $cols_Values .= "{$key} = :{$key} AND";
            }
            $cols_Values = substr($cols_Values, 0, -3);
        }
        if ($limit !== null) {
            $limits = " LIMIT {$limit}";
        }
        /*SELECT * FROM productos
            INNER JOIN categorias
            on productos.id_categoria_pro = categorias.id_cat
            WHERE producto.id_categoria_pro = 1 LIMIT 1;*/

        $stmt = "SELECT * FROM $table1 
                INNER JOIN $table2 
                ON $table1.$val1 = $table2.$val2
                {$cols_Values} {$limits}";

        // llamar el query de la base de datos
        if (!$rows = parent::query($stmt, $params)) {
            return false;
        }
        return $limit === 1 ? $rows[0] : $rows;
    }
    /*INSERTAR REGISTROS*/
    public static function insert( string $table, array $params){
        $cols = "";
        $placeholders = "";
        foreach ($params as $key => $values) { //INSERT INTO table (campo1, campo2) VALUES (:placeholder, :placeholder)
            $cols .= "{$key}, ";
            $placeholders .= ":{$key}, ";
        }
        $cols = substr($cols, 0, -2);
        $placeholders = substr($placeholders, 0, -2);
        $smtm = "INSERT INTO {$table} ({$cols}) VALUES ({$placeholders})";
        if ($id = parent::query($smtm, $params)) {
            return $id;
        } else {
            return false;
        }
    }
    /*UPDATE REGISTROS*/
    public static function update(string $table, $params = [], $id = []){
        //UPDATE $table SET campo1 = :campo1, campo2 = :campo2 WHERE idProducto = 1 AND status = 1
        $cols = "";
        $placeholders = "";
        foreach ($params as $key => $values) {
            $placeholders .= " {$key} = :{$key},";
        }
        $placeholders = substr($placeholders, 0, -1);
        if (count($id) > 1) {
            foreach ($id as $key => $value) {
                $cols .= " $key = :$key AND";
            }
            $cols = substr($cols, 0, -3);
        } else {
            foreach ($id as $key => $value) {
                $cols .= " $key = :$key";
            }
        }
        $smtm = "UPDATE $table SET $placeholders WHERE $cols";
        if (!parent::query($smtm, array_merge($params, $id))) {
            return false;
        }
        return true;
    }
    /*DELETE REGISTROS*/
    public static function delete(string $table, $params = [], $limit = 1){
        $cols_values = "";
        $limits = "";
        if(!empty($params)){
            $cols_values .= "WHERE";
            foreach ( $params as $key => $value) {
                $cols_values .= " {$key} = :{$key} AND";
            }
            $cols_values = substr($cols_values, 0, -3);
        }
        if($limit <> null){
            $limits = " LIMIT {$limit}";
        }
        //$stmt = "DELETE FROM $table {$cols_values} {$limits}";
        $stmt = "DELETE FROM $table {$cols_values}";
        if(!$row = parent::query($stmt, $params)) {
            return false;
        }
        return $row;
    }
}