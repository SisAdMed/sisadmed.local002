<?php
Class Downloader{
    public function DownXML($r){
        //Variables
        $rifAgente = '';
        $periodo = explode('-', $_GET['fec_fin']);
        $periodo = $periodo[0] . $periodo[1];
        $ext = '.xml';
        $filename = 'XML_relacionRetencionesISLR_' . $periodo . $ext;
        $totrows = count($r);
        //Cabecera
        header('Content-type: text/xml');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        //Crear XML
        $xw = xmlwriter_open_memory();
        xmlwriter_set_indent($xw, 1);
        $res = xmlwriter_set_indent_string($xw, ' ');

        xmlwriter_start_document($xw, '1.0', 'ISO-8859-1');

        // A first element
        $tag = 'RelacionRetencionesISLR';
        xmlwriter_start_element($xw, $tag);

        // Attribute 'att' for element '$tag'
        $att = 'RifAgente';
        $val = $r[0]->rif_empresa;
        xmlwriter_start_attribute($xw, $att);
        xmlwriter_text($xw, $val);

        // Attribute 'att' for element '$tag'
        $att = 'Periodo';
        $val = $periodo;
        xmlwriter_start_attribute($xw, $att);
        xmlwriter_text($xw, $val);

        //CDATA Registros
        $tag = 'DetalleRetencion';
        for($i = 0; $i < $totrows; $i++){
            xmlwriter_start_element($xw, $tag);
                $tag1 = 'RifRetenido';
                    xmlwriter_start_element($xw, $tag1);
                    xmlwriter_text($xw, $r[$i]->rif_ent);
                    xmlwriter_end_element($xw);
                $tag1 = 'NumeroFactura';
                    xmlwriter_start_element($xw, $tag1);
                    xmlwriter_text($xw, $r[$i]->num_tdo);
                    xmlwriter_end_element($xw);
                $tag1 = 'NumeroControl';
                    xmlwriter_start_element($xw, $tag1);
                    xmlwriter_text($xw, $r[$i]->num_control);
                    xmlwriter_end_element($xw);
                $tag1 = 'FechaOperacion';
                    xmlwriter_start_element($xw, $tag1);
                    xmlwriter_text($xw, formatFechaSlash($r[$i]->fecha_comp));
                    xmlwriter_end_element($xw);
                $tag1 = 'CodigoConcepto';
                    xmlwriter_start_element($xw, $tag1);
                    xmlwriter_text($xw, $r[$i]->code_seniat);
                    xmlwriter_end_element($xw);
                $tag1 = 'MontoOperacion';
                    xmlwriter_start_element($xw, $tag1);
                    xmlwriter_text($xw, $r[$i]->total_base);
                    xmlwriter_end_element($xw);
                $tag1 = 'PorcentajeRetencion';
                    xmlwriter_start_element($xw, $tag1);
                    xmlwriter_text($xw, $r[$i]->por_reten);
                    xmlwriter_end_element($xw);
            xmlwriter_end_element($xw); 
        }

        xmlwriter_end_attribute($xw);

        xmlwriter_end_document($xw);
        if (file_exists($filename)) {
            unlink($filename);
        }
        echo xmlwriter_output_memory($xw);
        exit;
    }
}
if(isset($r)){
    $xml = new Downloader();
    return $xml->DownXML($r);
}
