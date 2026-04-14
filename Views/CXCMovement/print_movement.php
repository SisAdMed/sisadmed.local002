<?php

use PhpOffice\PhpSpreadsheet\Cell\Cell;

    require(FPDF1 . 'fpdf.php');
    //Variables
    $filemame = $r[0]->nombre_emp . ' - '. $r[0]->cod_tmocxc . ' - '. $r[0]->des_tmocxc . ' - ' . $r[0]->movem_number . '.pdf';
    $tman_letra = 7;
    class PDF extends FPDF{
        public $ruta_logo, $nombre_emp, $rif_empresa, $cod_tmocxc, $des_tmocxc, $movem_number, $fecha_mov, $codigo_moneda, $tasa_cambio, $movem_descrip, $nom_ent;
        public function __construct($r){
            parent::__construct();
            $this->ruta_logo = IMG .'companies/' . $r[0]->logo;
            $this->nombre_emp = $r[0]->nombre_emp;
            $this->rif_empresa = $r[0]->rif_empresa;
            $this->cod_tmocxc = $r[0]->cod_tmocxc;
            $this->des_tmocxc = $r[0]->des_tmocxc;
            $this->movem_number = $r[0]->movem_number;
            $this->fecha_mov = $r[0]->fecha_mov;
            $this->codigo_moneda = $r[0]->codigo_moneda;
            $this->tasa_cambio = $r[0]->tasa_cambio;
            $this->movem_descrip = $r[0]->movem_descrip;
            $this->nom_ent = $r[0]->nom_ent;
        }
        function Header(){
            //Logo
            $this->Image($this->ruta_logo,10, 8, 50);
            // Arial bold 15
            // Movernos a la derecha
            $this->Cell(100);
            // Nombre empresa
            $this->SetFont('Arial','B', 14);
            $this->Cell(30,10, $this->nombre_emp,0,1,'C');
            $this->Cell(100);
            $this->Cell(30,10, 'RIF: '. $this->rif_empresa,0, 0, 'C' );
            $this->Ln(20);
            $this->Cell(50);
            $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode($this->des_tmocxc));
            $this->Cell(100, 10, ('Tipo de Movimiento ' . $this->cod_tmocxc . ' - ' . $cadena . ' - ' . $this->movem_number), 0, 1, 'C');
            $this->SetFont('Arial','B', 7);
            $this->ln(10);
            $this->Cell(30, 3, htmlentities('Fecha: ' . formatFecha($this->fecha_mov) . ' - Moneda: ' . $this->codigo_moneda) . ' - Tasa de Cambio Bs. ' . number_format($this->tasa_cambio, 4, ",", "."), 0, 1, 'L');
            $this->ln(4);
            $cadena = iconv("UTF-8", "ISO-8859-1", 'Cliente: ' . $this->nom_ent);
            $this->Cell(30, 3, $cadena, 0, 1, 'L');
            $this->ln(5);
            $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode('Descripción'));
            $this->Cell(30, 3, $cadena, 0, 1, 'L');
            $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode($this->movem_descrip));
            $this->MultiCell(120, 3, $cadena, 0, 'L');
            $this->Ln(5);
            //Titulo
            $this->Cell(5,3, 'Item', 0, 0, 'R');
            $this->Cell(10, 3,'Tipo' , 0, 0, 'C');
            $cadena = iconv("UTF-8", "ISO-8859-1", 'Descripción');
            $this->Cell(40, 3, $cadena , 0, 0, '');
            $cadena = iconv("UTF-8", "ISO-8859-1", 'Número');
            $this->Cell(20, 3, $cadena , 0, 0, 'R');
            $this->Cell(30, 3, 'Abono', 0, 0, 'R');
            $this->Cell(30, 3, 'Saldo', 0, 0, 'R');
            $cadena = iconv("UTF-8", "ISO-8859-1", 'Monto de Retención');
            $this->Cell(30, 3, $cadena, 0, 0, 'R');
            $cadena = iconv("UTF-8", "ISO-8859-1", 'Número de Retención');
            $this->Cell(30, 3, $cadena, 0, 1, 'R');
        }
        function footer(){

        }
    }
    // Creación del objeto de la clase heredada
    
    $pdf = new PDF($r);
    $pdf->AliasNbPages();
    $pdf->AddPage('P', 'Letter');
    $pdf->SetFont('Arial','', $tman_letra);
    $rows = 0;
    $total_abo = 0;
    $total_sal = 0;
    $total_ret = 0;
    $pdf->Ln(5);
    $total_rows = count($r);
    for ($i = 0; $i < count($r); $i++) {
        $pdf->Cell(5, 3, $r[$i]->item, 0, 0, 'C');
        $pdf->Cell(10, 3, $r[$i]->tipo_codigo , 0, 0, 'C');
        $pdf->Cell(40, 3, $r[$i]->nom_tdoc , 0, 0, '');
        $pdf->Cell(20, 3, $r[$i]->num_tdo , 0, 0, 'R');
        if(($total_rows -1) == $i){
            $pdf->SetFont('Arial','U', 7);            
            $pdf->Cell(30, 3, number_format($r[$i]->mon_doc ,2,",", "."), 0, 0, 'R');
            $pdf->Cell(30, 3, number_format($r[$i]->sal_doc ,2,",", "."), 0, 0, 'R');
            $pdf->Cell(30, 3, number_format($r[$i]->mon_ret ?? 0 ,2,",", "."), 0, 0, 'R');
        }else{       
            $pdf->Cell(30, 3, number_format($r[$i]->mon_doc ,2,",", "."), 0, 0, 'R');
            $pdf->Cell(30, 3, number_format($r[$i]->sal_doc ,2,",", "."), 0, 0, 'R');
            $pdf->Cell(30, 3, number_format($r[$i]->mon_ret ?? 0 ,2,",", "."), 0, 0, 'R');
        }
        $pdf->SetFont('Arial','', 7);     
        $pdf->Cell(30, 3, $r[$i]->num_ret, 0, 1, 'R');
        $total_abo += $r[$i]->mon_doc;
        $total_sal += $r[$i]->sal_doc;
        $total_ret += $r[$i]->mon_ret;
    }
    
    //Imprimir Totales
     $pdf->SetFont('Arial','BU', 7);      
    $pdf->Ln(3);
    $pdf->Cell(5, 3, '', 0, 0, 'C');
    $pdf->Cell(10, 3, '' , 0, 0, 'C');
    $pdf->Cell(40, 3, '' , 0, 0, '');
    $pdf->Cell(20, 3, '' , 0, 0, 'R');
    $pdf->Cell(30, 3, number_format($total_abo ,2,",", "."), 0, 0, 'R');
    $pdf->Cell(30, 3, number_format($total_sal ,2,",", "."), 0, 0, 'R');
    $pdf->Cell(30, 3, number_format($total_ret ?? 0 ,2,",", "."), 0, 0, 'R');
    //Emitido por:
    $pdf->SetFont('Arial', '', $tman_letra);
    $pdf->Ln(10);
    $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode('Realizado por:'));
    $pdf->Cell(40, 3, $cadena, 0, 1, 'L');
    // Creado por:
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'BU', $tman_letra);
    $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode($r[0]->user_create));
    $pdf->Cell(40, 3, $cadena, 0, 1, 'L');
    //Salida de archivo
    $pdf->Output('', htmlentities($filemame));
?>