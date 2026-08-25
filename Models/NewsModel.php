<?php
class NewsModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function cargar_screen_main(){
        $sql = "SELECT * FROM f0026";
        $r = DB::query($sql);
        return $r;
    }
    static function guardar(array $data){
        return DB::insert('f0026', $data);
    }
    static function actualizar(array $data, int $id){
        return DB::update('f0026', $data, ['id' => $id]);
    }
    static function edit(int $id){
        $sql = "SELECT * FROM f0026 WHERE id = $id";
        $r = DB::query($sql);
        return $r[0];
    }
    static function show_row(int $id){        
        $sql = "SELECT * FROM f0026 WHERE id = $id";
        $r = DB::query($sql);        
        return $r[0];
    }
    static function getImageNew(int $id){
        $sql = "SELECT imagen FROM f0026 WHERE id = $id";
        $r = DB::query($sql);
        return $r[0];
    }
    static function destroy(int $id){
        $db = new Conexion(); //Obtener la instancia del PDO
        $link = (object)$db->conect();
        try {
            $link->beginTransaction();
            //Verificar si esta publicado
            $sql_sel = "SELECT * FROM f0026 WHERE id = :id";
            $stmt = $link->prepare($sql_sel);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $view_internet = $row['view_internet'];            
            if ($view_internet === 1) {
                return 0;
            }
            $stmt->closeCursor();
            //Eliminar Registro
            $sql_del = "DELETE FROM f0026 WHERE id = :id";
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