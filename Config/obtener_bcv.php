<?php
//Archivo de Conexión
require_once('../Config/Config.php');
require_once('../Config/App/Conexion.php');
require_once('../Helpers/Helpers.php');
//
// Configuración de la URL objetivo
$url = 'https://www.bcv.org.ve/';

// Inicialización de cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignora errores de certificado SSL del BCV
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
//Moneda Dolar
$id_moneda = 2;
$id_user = 1;

if ($httpCode !== 200 || !$response) {
    error_log("[" . date('Y-m-d H:i:s') . "] Error al consultar el sitio del BCV. HTTP Code: " . $httpCode);
    exit("Error al conectar con el BCV.\n");
}

// Procesar el HTML mediante DOMDocument
libxml_use_internal_errors(true); // Desactiva advertencias de HTML mal formado
$dom = new DOMDocument();
$dom->loadHTML($response);
libxml_clear_errors();

$xpath = new DOMXPath($dom);

// El BCV ubica la tasa en el elemento con id="dolar" dentro de la etiqueta <strong>
$nodes = $xpath->query('//div[@id="dolar"]//strong');

if ($nodes->length > 0) {
    // Obtener texto, limpiar espacios extras y convertir coma a punto para formato numérico
    $rawRate = trim($nodes->item(0)->nodeValue);
    $rateFormatted = str_replace(',', '.', $rawRate);
    $tasaDolar = (float) $rateFormatted;
    $fechaActual = date('Y-m-d H:i:s');

    // 3. Conectar a la base de datos y guardar el registro
    $db = new Conexion(); //Obtener la instancia del PDO
    $link = (object)$db->conect();
    try {
        //
        $link->beginTransaction();
        // Validar si ya existe un registro para HOY con la MISMA TASA
        $sqlCheck = "SELECT COUNT(*) tot_wow FROM f0012 WHERE id_moneda  = :id_moneda AND DATE(fecha_cambio) = :fecha_cambio AND cambio_venta = :cambio_venta";
        //
        $stmtCheck = $link->prepare($sqlCheck);
        $stmtCheck->execute([
            ':id_moneda' => $id_moneda,
            ':fecha_cambio' => $fechaActual,
            ':cambio_venta'  => $tasaDolar
        ]);
        //
        $existeRegistro = $stmtCheck->fetchColumn();
        //
        if ($existeRegistro > 0) {
            echo "El registro para la fecha {$fechaActual} con la tasa {$tasaDolar} ya existe. Se omite la inserción.\n";
        } else {
            // Opción A: Guardar siempre un nuevo registro en el historial
            $sql = "INSERT INTO f0012 (fecha_cambio, id_moneda, cambio_compra, cambio_venta, create_user, create_date) VALUES (:fecha_cambio, :id_moneda, :cambio_compra, :cambio_venta, :create_user, :create_date)";
            $stmt = $link->prepare($sql);
            $stmt->execute([
                ':fecha_cambio'  => getAuditoria(),
                ':id_moneda' => $id_moneda,
                ':cambio_compra'   => $tasaDolar,
                ':cambio_venta'   => $tasaDolar,
                ':create_user'   => $id_user,
                ':create_date'   => getAuditoria(),
            ]);
            debug($stmt->debugDumpParams());
            echo "Tasa ({$tasaDolar} VES) guardada correctamente en la base de datos.\n";
        }
        //
        $link->commit();
        //                
    } catch (PDOException $e) {
        debug($e->getMessage());
        $link->rollback();
        error_log("[" . date('Y-m-d H:i:s') . "] Error BD: " . $e->getMessage());
        echo "Error al guardar en la base de datos.\n";
    }
    
} else {
    error_log("[" . date('Y-m-d H:i:s') . "] No se pudo extraer el contenedor id='dolar' del HTML.");
    echo "No se encontró la tasa en el DOM.\n";
}
