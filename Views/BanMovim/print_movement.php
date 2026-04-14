<?php
    require(FPDF1 . 'fpdf.php');
    //Variables
    $filemame = $r[0]->nombre_emp . ' - '. $r[0]->cod_bantmo . ' - '. $r[0]->nom_bantmo . ' - ' . $r[0]->num_banmov . '.pdf';
    $tman_letra = 7;
    class PDF extends FPDF{
        public $ruta_logo, $nombre_emp, $rif_empresa, $cod_bantmo, $nom_bantmo, $num_banmov, $nombre_banco, $cuenta_bancue, $fecha_comp, $codigo_moneda, $tasa_cambio, $des_banmov;
        public function __construct($r){
            parent::__construct();
            $this->ruta_logo = IMG .'companies/' . $r[0]->logo;
            $this->nombre_emp = $r[0]->nombre_emp;
            $this->rif_empresa = $r[0]->rif_empresa;
            $this->cod_bantmo = $r[0]->cod_bantmo;
            $this->nom_bantmo = $r[0]->nom_bantmo;
            $this->num_banmov = $r[0]->num_banmov;
            $this->nombre_banco = $r[0]->nombre_banco;
            $this->cuenta_bancue = $r[0]->cuenta_bancue;
            $this->fecha_comp = $r[0]->fecha_comp;
            $this->codigo_moneda = $r[0]->codigo_moneda;
            $this->tasa_cambio = $r[0]->tasa_cambio;
            $this->des_banmov = $r[0]->des_banmov;
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
            $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode($this->nom_bantmo));
            $this->Cell(100, 10, ('Tipo de Movimiento ' . $this->cod_bantmo . ' - ' . $cadena . ' - ' . $this->num_banmov), 0, 1, 'C');
            $this->SetFont('Arial','B', 7);
            $this->ln(10);
            $this->Cell(30, 3, htmlentities('Fecha: ' . $this->fecha_comp . ' - Moneda: ' . $this->codigo_moneda) . ' - Tasa de Cambio Bs. ' . number_format($this->tasa_cambio, 4, ",", "."), 0, 1, 'L');
            $this->ln(5);
            $this->Cell(30, 3, htmlentities('Banco: ' . $this->nombre_banco . ' - Cuenta Bancaria: ' . $this->cuenta_bancue), 0, 1, 'L');
            $this->ln(5);
            $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode('Descripción'));
            $this->Cell(30, 3, $cadena, 0, 1, 'L');
            $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode($this->des_banmov));
            $this->MultiCell(120, 3, $cadena, 0, 'L');
            $this->Ln(5);
            //Titulo
            $this->Cell(5,3, 'Item', 0, 0, 'R');
            $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode('Concepto     Descripción'));
            $this->Cell(60, 3, $cadena, 0, 0,'L');
            $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode('Auxiliar - Descripción'));
            $this->Cell(52, 3, $cadena, 0, 0,'L');
            $this->Cell(61);
            $this->Cell(18, 3, '<Monto>' , 0, 1, 'R');
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
    $total = 0;
    $pdf->Ln(2);
    $total_rows = count($r);
    for ($i = 0; $i < count($r); $i++) {
        $pdf->Cell(5, 3, $r[$i]->item, 0, 0, 'C');
        $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode($r[$i]->nom_bancon));
        $pdf->cell(15, 3, $r[$i]->cod_bancon, 0, 0,'L');
        $pdf->cell(10, 3, ($cadena), 0, 0,'L');
        if($r[$i]->cod_aux){
            $pdf->cell(80, 3, htmlentities($r[$i]->cod_aux . ' - ' . $r[$i]->nombre_aux), 0, 0,'L');
        }else{
            $pdf->cell(80, 3, '', 0, 0,'L');
        }
        if($i == ($total_rows-1)){
             $pdf->SetFont('Arial', 'U', $tman_letra);
        }
        $monto = $r[$i]->monto_for;
        $pdf->Cell(86, 3, number_format($monto, 2, ",", ".") , 0, 1, 'R');
        $total += $monto;
        
    }
    
    //Totales Debe/Haber
    $pdf->Ln(3);
    $pdf->Cell(158);
    $pdf->SetFont('Arial', 'B', $tman_letra);
    $pdf->Cell(18, 3, 'Total:', 0, 0, '');
    $pdf->SetFont('Arial', 'BU', $tman_letra);
    $pdf->Cell(2);
    $pdf->Cell(18, 3, number_format($total, 2, ",", ".") , 0, 1, 'R');
    //Imprimir documentos cancelados
    $id_banmov = $r[0]->id_banmov;
    $efe_bantmo = $r[0]->efe_bantmo;
    $doc_can = BanMovimModel::det_doc_can($id_banmov, $efe_bantmo);
    if($doc_can){
        $pdf->SetFont('Arial', 'B', $tman_letra);
        $title = "Cancelación de los documento(s) número(s): ". $doc_can[0]['cod_tmocxc'] . ' ' . $doc_can[0]['des_tmocxc'] . " " . $doc_can[0]['movem_number'];
        if($doc_can[0]['tasa_cambio'] != 1){
            $title .= ' con Tasa de Cambio de Bs. ' . number_format($doc_can[0]['tasa_cambio'], 8, ',', '.');
        }
        if($efe_bantmo == 'C'){
            $title .= PHP_EOL . "Cliente: " . $doc_can[0]['nom_ent'];    
        }else if($efe_bantmo == 'P'){
            $title .= PHP_EOL ."Proveedor: " . $doc_can[0]['nom_ent'];    
        }
        $cadena = iconv("UTF-8", "ISO-8859-1", $title);
        $pdf->Ln(5);
        //$pdf->Cell(0, 3, $cadena , 0, 1, 'C');
        $pdf->Cell(20);
        $pdf->MultiCell(150, 3, $cadena, 0, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', $tman_letra);
        $pdf->Cell(20, 3, 'Item', 0, 0, 'C');
        $pdf->Cell(10, 3, 'Tipo' , 0, 0, 'R'); 
        $cadena = iconv("UTF-8", "ISO-8859-1", 'Descripción');
        $pdf->Cell(40, 3, $cadena , 0, 0, 'C');
        $cadena = iconv("UTF-8", "ISO-8859-1", 'Número');
        $pdf->Cell(40, 3, $cadena, 0, 0, 'C');
        $pdf->Cell(20, 3, 'Monto', 0, $efe_bantmo == 'C' ? 0: 1, 'C');
        if($efe_bantmo == 'C'){
            $cadena = iconv("UTF-8", "ISO-8859-1", 'Retención');
            $pdf->Cell(20, 3, $cadena, 0, 0, 'C');
            $cadena = iconv("UTF-8", "ISO-8859-1", 'Número Retención');
            $pdf->Cell(20, 3, $cadena, 0, 1, 'C');
        }
        $pdf->SetFont('Arial', '', $tman_letra);
        $pdf->Ln(2);
        $mon_doc = 0;
        $mon_ret = 0;
        $total_rows = count($doc_can);        
        for($i= 0;$i<count($doc_can);$i++){
            $pdf->Cell(20, 3, $doc_can[$i]['item'] , 0, 0, 'C');
            $pdf->Cell(10, 3, $doc_can[$i]['tipo_codigo'] , 0, 0, 'C');
            $pdf->Cell(40, 3, $doc_can[$i]['nom_tdoc'] , 0, 0, 'R');
            $pdf->Cell(40, 3, $doc_can[$i]['num_tdo'] , 0, 0, 'C');
            if($i == ($total_rows-1)){
                $pdf->SetFont('Arial', 'U', $tman_letra);
            }
            $pdf->Cell(20, 3, number_format($doc_can[$i]['mon_can'] ,2,",", "."), 0, 0, 'C');
            if($efe_bantmo == 'C'){
                $pdf->Cell(20, 3, number_format($doc_can[$i]['mon_ret'] ?? 0 ,2,",", "."), 0, 0, 'C');
                $pdf->SetFont('Arial', '', $tman_letra);
                $pdf->Cell(20, 3, $doc_can[$i]['num_ret'], 0, 1, 'C');
                //Acumulador
                $mon_ret +=$doc_can[$i]['mon_ret'] ?? 0;
            }else{
                $pdf->Cell(20, 3, '', 0, 1, 'C');
            }
            
            //Acumulador
            $mon_doc += $doc_can[$i]['mon_can'];
            
        }
        $pdf->SetFont('Arial', 'BU', $tman_letra);
        $pdf->Ln(3);
        $pdf->Cell(20, 3, '', 0, 0, 'C');
        $pdf->Cell(10, 3, '', 0, 0, 'C');
        $pdf->Cell(40, 3, '' , 0, 0, 'R');
        $pdf->Cell(40, 3, '', 0, 0, 'C');
        $pdf->Cell(20, 3, number_format($mon_doc ,2,",", "."), 0, 0, 'C');
        if($efe_bantmo == 'C'){
            $pdf->Cell(20, 3, number_format($mon_ret ,2,",", "."), 0, 0, 'C');
        }
        $pdf->Cell(20, 3, '', 0, 1, 'C');
        $pdf->SetFont('Arial', '', $tman_letra);
    }
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