<?php
Class Downloader{
    public function DownExcel($r){
        //Variables
        $filename = "file." . $ext = "txt";
        $fec_ini = $_GET['fec_ini'];
        $fec_fin = $_GET['fec_fin'];        
        $dateFormat = 'dd-mm-yyyy'; // You can modify this format as needed
        //Nombre del archivo
        $file = $r;
        $fp = fopen($filename, 'w');
        $tab = chr(9);
        for($i=0; $i < count($r); $i++){
            $cadena = '';
            $cadena = $r[$i]['rif_empresa'] . $tab . $r[$i]['periodo'] . $tab . $r[$i]['fecha_pago'] . $tab . $r[$i]['tipo_oper'] . $tab . $r[$i]['tipo_doc'] . $tab . $r[$i]['rif_ent'] . $tab . $r[$i]['nro_doc'] . $tab . $r[$i]['nro_ctrl'] . $tab . $r[$i]['mon_doc'] . $tab . $r[$i]['mon_bas'] . $tab . $r[$i]['mon_iva'] . $tab . $r[$i]['afectado'] . $tab . $r[$i]['num_ret'] . $tab . $r[$i]['mon_exe'] . $tab . $r[$i]['alicuota']. $tab . $r[$i]['expediente']; 
            fwrite($fp, $cadena . PHP_EOL);
            

        }
       
        fclose($fp);
        header("Content-Transfer-Encoding: binary");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename= $filename");
        readfile($filename);
        if (file_exists($filename)) {
            unlink($filename);
        }
    }
}
if(isset($r)){
    $excel = new Downloader();
    return $excel->DownExcel($r);
}
