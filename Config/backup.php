<?php
    include_once('Config.php');
    // --- CONFIGURACIÓN ---
    $db_host = DB_HOST;
    $db_name = DB_NAME;
    $db_user = DB_USER;
    $db_pass = DB_PASSWORD;
    try {
        echo PHP_EOL;
        // Carpeta donde se guardarán los respaldos (asegúrate de que tenga permisos de escritura 755 o 777)
        $backup_dir = '/home/cpuj4gbphmxn/sisadmed_backups';
        // Crear la carpeta de respaldos si no existe
        if (!is_dir($backup_dir)) {
            mkdir($backup_dir, 0755, true);
        }
        // Nombre del archivo con fecha y hora
        $fecha = date("Y-m-d_H-i-s");
        echo $fecha;
        $filename = $backup_dir . '/' . $db_name . '_' . $fecha . '.sql';
        $zipFile  = $backup_dir . '/' . $db_name . '_' . $fecha . '.zip';
        $zipName  = $db_name . '_' . $fecha . '.zip';
        // Comando mysqldump
        $command = "mysqldump --host=$db_host --user=$db_user --password=$db_pass $db_name > $filename";
        // Ejecutar el comando
        system($command, $output);
        // --- RESULTADO ---
        if ($output === 0) {
            $zip = new ZipArchive();
            if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
                $zip->addFile($filename);
                $zip->close();
                unlink($filename);
                echo PHP_EOL;
                echo "Respaldo exitoso: " . $zipFile;
                $nombre_archivo = basename($zipFile);
                // Eliminar archivos zip mayores a 7 minutos/dias
                // Directorio donde están los archivos ZIP
                $dias = 7;
                $tiempoLimite = time() - ($dias * 24 * 60 * 60);
                //$tiempoLimite = time() - (60 * $dias);
                // Buscar todos los archivos .zip
                foreach (glob($backup_dir . '/*.zip') as $file) {
                    // Verificar si el archivo tiene más de 1 minuto de antigüedad
                    if (filemtime($file) < $tiempoLimite) {
                        // Eliminar el tiempoLimite
                        echo PHP_EOL;
                        if (unlink($file)) {
                            echo "Archivo eliminado: " . $file ;
                        } else {
                            echo "No se pudo eliminar: " . $file;
                        }
                        echo PHP_EOL;
                    }
                }
                $to      = "jose.vargas@josvarsistemas.com.ve, gerencia@corp-mmq.com";
                $subject = 'Respaldo de SisAdMed al ' . $fecha;
                $mensaje = 'Buen día' . PHP_EOL . PHP_EOL; 
                $mensaje .= 'Adjunto le hacemos llegar el respaldo de SisAdMed al ' . $fecha . PHP_EOL . PHP_EOL; 
                $mensaje .= 'Saludos cordiales' . PHP_EOL . PHP_EOL;
                $mensaje .= 'Sistema Administrativo Médico' . PHP_EOL . PHP_EOL;
                $mensaje .= 'PD: Cuenta de correo no monitoreado, por favor no responda este correo.' . PHP_EOL . PHP_EOL;
                // Crear un límite único (boundary)
                $boundary = md5(time());
                $headers = "From: sisadmed@corp-mmq.com\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: multipart/mixed; boundary=\"".$boundary."\"$nombre_archivo";
                // Construir el cuerpo del mensaje (multipart)
                $message  = "--".$boundary."\r\n";
                $message .= 'Content-type: text/plain; charset=\"iso-8859-1\"'."\r\n";
                $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
                $message .= $mensaje."\r\n\r\n";
                // Añadir el archivo adjunto
                if(file_exists($zipFile)){
                    // Leer el archivo y codificarlo
                    $archivo = $zipFile;
                   
                    $contenido = chunk_split(base64_encode(file_get_contents($zipFile)));
                  
                    //
                    $message .= "--".$boundary . "\r\n";
                    $message .= "Content-Type: application/zip; name=\"".$nombre_archivo."\"\r\n";
                    $message .= "Content-Transfer-Encoding: base64\r\n";
                    $message .= "Content-Disposition: attachment; filename=\"".$nombre_archivo."\"\r\n\r\n";
                    $message .= $contenido .PHP_EOL;
                    $message .= "--".$boundary."--";
                }
               
            }
        }
        echo PHP_EOL;
        if(mail($to, $subject, $message, $headers)){
            echo 'Correo enviado exitosamente desde mail()';    
        }else{
            echo 'Error enviado correo desde mail()';
        }
    } catch (Exception $e) {
        echo PHP_EOL;
        echo "Error al enviar: {$mail->ErrorInfo}";
    } finally {
        echo PHP_EOL;
    }
?>
