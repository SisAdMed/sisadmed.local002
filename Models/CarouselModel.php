<?php
class CarouselModel extends DB
{
    public function __construct()
    {
        parent::__construct();
    }
    static function cargar_screen_main()
    {
        $sql = "SELECT DISTINCT a.id, fecha, titulo, view_internet, status FROM f0028 a INNER JOIN f00281 b ON b.carrusel_id = a.id ORDER BY a.id DESC";
        $r = DB::query($sql);
        return $r;
    }
    static function guardar(array $data)
    {
        ob_start();
        header('Content-Type: application/json; charset=utf-8');
        $archivosSubidos = [];

        $db = new Conexion();
        $link = (object)$db->conect();

        try {
            $link->beginTransaction();

            // 1. Guardar Encabezado (f0028)
            $cols = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            $sql = "INSERT INTO f0028 ({$cols}) VALUES ({$placeholders})";

            $params = [];
            foreach ($data as $campo => $value) {
                $params[":{$campo}"] = $value;
            }

            $stmt = $link->prepare($sql);
            $stmt->execute($params);
            $id = $link->lastInsertId();
            $stmt->closeCursor();

            // 2. Guardar Imágenes / Videos asociados (f00281)
            if (isset($_POST['file_index']) && isset($_FILES['imagenes'])) {
                $directorio = ROOT . DS . 'Assets' . DS . 'img' . DS . 'carousel' . DS;

                $sqlImg = "INSERT INTO f00281 (carrusel_id, imagen, mensaje_izq, mensaje_der, orden, create_user, create_date) 
                       VALUES (:carrusel_id, :imagen, :mensaje_izq, :mensaje_der, :orden, :create_user, :create_date)";
                $stmtImg = $link->prepare($sqlImg);

                foreach ($_POST['file_index'] as $nuevoOrden => $originalIndex) {
                    // Guardar tanto imágenes como MP4
                    $nombreArchivo = validarYSanearMediaCarrusel($_FILES['imagenes'], (int)$originalIndex, $directorio);
                    $archivosSubidos[] = $directorio . $nombreArchivo;

                    $msgIzq = sanearMensajeOverlay($_POST['mensaje_izq'][$originalIndex] ?? '');
                    $msgDer = sanearMensajeOverlay($_POST['mensaje_der'][$originalIndex] ?? '');

                    $stmtImg->execute([
                        ':carrusel_id' => $id,
                        ':imagen'      => $nombreArchivo,
                        ':mensaje_izq' => $msgIzq,
                        ':mensaje_der' => $msgDer,
                        ':orden'       => $nuevoOrden + 1,
                        ':create_user' => $_SESSION['id_user'] ?? 1,
                        ':create_date' => getAuditoria()
                    ]);
                    $stmtImg->closeCursor();
                }
            }

            if ($link->inTransaction()) {
                $link->commit();
            }

            ob_end_clean();
            echo json_encode([
                'title' => 'Registro guardado satisfactoriamente',
                'icon'  => 'success',
                'msg'   => 'El carrusel y sus archivos multimedia se procesaron correctamente.'
            ]);
            exit;
        } catch (\Throwable $e) {
            if (isset($link) && $link->inTransaction()) {
                $link->rollBack();
            }

            ob_end_clean();

            // Eliminar archivos físicos subidos si hubo fallo
            foreach ($archivosSubidos as $rutaArchivo) {
                if (file_exists($rutaArchivo)) {
                    @unlink($rutaArchivo);
                }
            }

            http_response_code(400);
            echo json_encode([
                'title' => "Error al guardar registro",
                'icon'  => 'error',
                'msg'   => $e->getMessage()
            ]);
            exit;
        }
    }

    static function actualizar(array $data, int $id)
    {
        $db = new Conexion();
        $link = (object)$db->conect();
        $archivosSubidos = [];

        try {
            $link->beginTransaction();

            // 1. ACTUALIZAR ENCABEZADO (f0028)
            $setClauses = [];
            $params = [':id' => $id];
            foreach ($data as $campo => $value) {
                $setClauses[] = "{$campo} = :{$campo}";
                $params[":{$campo}"] = $value;
            }
            $sql = "UPDATE f0028 SET " . implode(', ', $setClauses) . " WHERE id = :id";
            $stmt = $link->prepare($sql);
            $stmt->execute($params);
            $stmt->closeCursor();

            $directorio = ROOT . DS . 'Assets' . DS . 'img' . DS . 'carousel' . DS;

            // 2. ELIMINAR REGISTROS Y ARCHIVOS MARCADOS
            if (!empty($_POST['eliminar_existentes_ids'])) {
                $stmtSelFot = $link->prepare("SELECT imagen FROM f00281 WHERE id = :id");
                $stmmtDelFot = $link->prepare("DELETE FROM f00281 WHERE id = :id");

                foreach ($_POST['eliminar_existentes_ids'] as $idDel) {
                    $stmtSelFot->execute([':id' => $idDel]);
                    $namImg = $stmtSelFot->fetchColumn();
                    $stmtSelFot->closeCursor();

                    $stmmtDelFot->execute([':id' => $idDel]);
                    $stmmtDelFot->closeCursor();

                    if (!empty($namImg) && file_exists($directorio . $namImg)) {
                        @unlink($directorio . $namImg);
                    }
                }
            }

            // 3. ACTUALIZAR MENSAJES DE ELEMENTOS EXISTENTES CONSERVADOS
            if (!empty($_POST['existente_id'])) {
                $sqlUpdFot = "UPDATE f00281 SET mensaje_izq = :mensaje_izq, mensaje_der = :mensaje_der, orden = :orden, create_user = :create_user, create_date = :create_date WHERE id = :id";
                $stmtUpdFot = $link->prepare($sqlUpdFot);

                foreach ($_POST['existente_id'] as $nuevoOrden => $idExistente) {
                    $msgIzq = sanearMensajeOverlay($_POST['existente_mensaje_izq'][$idExistente] ?? '');
                    $msgDer = sanearMensajeOverlay($_POST['existente_mensaje_der'][$idExistente] ?? '');

                    $stmtUpdFot->execute([
                        ':mensaje_izq' => $msgIzq,
                        ':mensaje_der' => $msgDer,
                        ':orden'       => $nuevoOrden + 1,
                        ':create_user' => $_SESSION['id_user'] ?? 1,
                        ':create_date' => getAuditoria(),
                        ':id'          => $idExistente
                    ]);
                    $stmtUpdFot->closeCursor();
                }
            }

            // 4. INSERTAR NUEVOS ARCHIVOS (IMÁGENES O VIDEOS MP4)
            if (isset($_POST['file_index']) && isset($_FILES['imagenes'])) {
                $sqlInsertFoto = "INSERT INTO f00281 (carrusel_id, imagen, mensaje_izq, mensaje_der, orden, create_user, create_date) 
                              VALUES (:carrusel_id, :imagen, :mensaje_izq, :mensaje_der, :orden, :create_user, :create_date)";
                $stmtInsertFoto = $link->prepare($sqlInsertFoto);

                $ordenInicial = count($_POST['existente_id'] ?? []);

                foreach ($_POST['file_index'] as $indexLocal => $originalIndex) {
                    $nombreArchivo = validarYSanearMediaCarrusel($_FILES['imagenes'], (int)$originalIndex, $directorio);
                    $archivosSubidos[] = $directorio . $nombreArchivo;

                    $msgIzq = sanearMensajeOverlay($_POST['mensaje_izq'][$originalIndex] ?? '');
                    $msgDer = sanearMensajeOverlay($_POST['mensaje_der'][$originalIndex] ?? '');

                    $stmtInsertFoto->execute([
                        ':carrusel_id' => $id,
                        ':imagen'      => $nombreArchivo,
                        ':mensaje_izq' => $msgIzq,
                        ':mensaje_der' => $msgDer,
                        ':orden'       => $ordenInicial + $indexLocal + 1,
                        ':create_user' => $_SESSION['id_user'] ?? 1,
                        ':create_date' => getAuditoria()
                    ]);
                    $stmtInsertFoto->closeCursor();
                }
            }

            if ($link->inTransaction()) {
                $link->commit();
            }
        } catch (\Throwable $e) {
            if (isset($link) && $link->inTransaction()) {
                $link->rollback();
            }

            // Limpiar archivos físicos de la petición actual si falló
            foreach ($archivosSubidos as $rutaArchivo) {
                if (file_exists($rutaArchivo)) {
                    @unlink($rutaArchivo);
                }
            }

            throw new \Exception($e->getMessage(), (int)$e->getCode());
        }
    }
    static function edit(int $id)
    {
        $sql = "SELECT * FROM f0028 WHERE id = $id";
        $r = DB::query($sql);
        return $r[0];
    }
    static function show_row(int $id)
    {
        $sql = "SELECT a.id, a.titulo, a.fecha, a.status, a.view_internet, b.imagen, b.mensaje_izq, b.mensaje_der, b.orden FROM f0028 a INNER JOIN f00281 b ON b.carrusel_id = a.id WHERE a.id = {$id} ORDER BY b.orden";
        $r = DB::query($sql);
        return $r;
    }
    static function getImageNew(int $id)
    {
        $sql = "SELECT * FROM f00281 WHERE carrusel_id = $id";
        $r = DB::query($sql);
        return $r;
    }
    static function destroy(int $id)
    {
        $db = new Conexion(); //Obtener la instancia del PDO
        $link = (object)$db->conect();
        try {
            $link->beginTransaction();
            //Verificar si esta publicado
            $sql_sel = "SELECT * FROM f0028 WHERE id = :id";
            $stmt = $link->prepare($sql_sel);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $view_internet = $row['view_internet'];
            if ($view_internet === 1) {
                return 0;
            }
            $stmt->closeCursor();
            //Buscar imagenes asociadas al encabezado            
            $sqlFotos = "SELECT imagen FROM f00281 WHERE carrusel_id = :id";
            $stmtFotos = $link->prepare($sqlFotos);
            $stmtFotos->execute([':id' => $id]);
            $imagenes = $stmtFotos->fetchAll(PDO::FETCH_COLUMN);
            //            
            //Eliminar Registro            
            $sql_Maestro = "DELETE FROM f0028 WHERE id = :id";
            $stmt_del = $link->prepare($sql_Maestro);
            $stmt_del->execute([':id' => $id]);
            $stmt_del->closeCursor();
            $link->commit();
            //Eliminar Archivos fisicos
            $directorio = ROOT . DS .  'Assets' . DS . 'img' . DS . 'carousel' . DS;
            foreach ($imagenes as $nombreFoto) {
                if (!empty($nombreFoto)) {
                    $rutaArchivo = $directorio . $nombreFoto;
                    if (file_exists($rutaArchivo)) {
                        @unlink($rutaArchivo);
                    }
                }
            }
            //
            return true;
        } catch (\PDOException $e) {
            $link->rollback();
            throw new Exception($e->getMessage(), $e->getCode());
            return false;
        }
    }
}
