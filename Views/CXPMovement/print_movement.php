<?php
require(FPDF1 . 'fpdf.php');
//Variables
$total_pago = 0;
$total_saldo = 0;
//GLobales
$GLOBALS['total_pago'] = 0;
$GLOBALS['total_saldo'] = 0;
$filemame = $r[0]->nombre_emp . ' - Movimiento de Cuentas por Pagar '. $r[0]->des_tmocxc . ' - '. $r[0]->nom_ent . ' - ' . $r[0]->movem_number . '.pdf';
$GLOBALS['elaborado'] = $r[0]->creado;
$tman_letra = 7;
class PDF extends FPDF{
    public $ruta_logo, $nombre_emp, $rif_empresa, $fecha_comp, $des_mov, $movem_number, $cod_tmocxc, $nom_ent;
    public $movem_descrip;
     public function __construct($r){
        parent::__construct();
        $this->ruta_logo = IMG .'companies/' . $r[0]->logo;
        $this->nombre_emp = $r[0]->nombre_emp;
        $this->rif_empresa = $r[0]->rif_empresa;
        $this->fecha_comp = $r[0]->fecha_comp;
        $this->des_mov = $r[0]->des_tmocxc;
        $this->movem_number = $r[0]->movem_number;
        $this->cod_tmocxc = $r[0]->cod_tmocxc;
        $this->nom_ent = $r[0]->nom_ent;
        $this->movem_descrip = $r[0]->movem_descrip;
    }
    // Cabecera de página
    function Header(){
         // Logo
         $this->Image($this->ruta_logo,10, 8, 50, 0, 'PNG');
        // Arial bold 15
        $this->SetFont('Arial','B', 7);
        // Movernos a la derecha
        $this->Cell(100);
        // Nombre empresa
        $marca = ($this->fecha_comp);
        $this->SetFont('Arial','B', 14);
        $this->Cell(30,10, $this->nombre_emp,0,1,'C');
        $this->Cell(95);
        $this->Cell(30,10, 'RIF: '. $this->rif_empresa,0, 0,'C' );
        $this->Ln(10);
        $this->SetFont('Arial','',7, 0, 1);
        $this->cell(0, 4, 'Fecha: ' . formatFecha($marca), 0, 1, 'R');
        $this->Ln(5);
        $this->SetFont('Arial','B', 14, 0, 1);
        $this->Cell(0,10, $this->cod_tmocxc . ' ' . $this->des_mov . ' ' . $this->movem_number ,0, 0,'C' );
        $this->SetFont('Arial','',7, 0, 1);
        $this->Ln(10);
        $this->SetFont('Arial','B', 14, 0, 1);
        $this->Cell(0,10, 'Proveedor: ' . htmlentities($this->nom_ent), 0, 1,'C' );
        $this->SetFont('Arial','B',7, 0, 1);
        //Titulos
        $this->Ln(5);
        $this->Cell(10,3, 'ITEMS', 0, 0, 'R');
        $this->Cell(10,3, 'TIPO', 0, 0, 'L');
        $cadena = iconv("UTF-8", "ISO-8859-1", 'NÚMERO');
        $this->Cell(10,3, $cadena, 0, 0, 'R');
        $cadena = iconv("UTF-8", "ISO-8859-1", 'DESCRIPCIÓN');
        $this->Cell(50,3, $cadena, 0, 0, 'R');
        $this->Cell(50,3, 'ABONO', 0, 0, 'R');
        $this->Cell(50,3, 'SALDO', 0, 1, 'R');
        $this->SetFont('Arial','',7, 0, 1);
    }
    //Imprimir totales
    function PrintTotal(){
        // Posición: a 4 cm para los totales
        $this->SetFont('Arial','B', 7);
        $this->Cell(60);
        $this->cell(20, 10, html_entity_decode('TOTALES --->'), 0, 0, 'L');
        $this->Cell(50,10, number_format($GLOBALS['total_pago'], 2,",", "."), 0, 0, 'R');
        $this->Cell(50,10, number_format($GLOBALS['total_saldo'], 2,",", "."), 0, 1, 'R');
        $this->Ln(5);
        $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode($this->movem_descrip));
        $this->MultiCell(100, 3, $cadena, 0, 'L');

  }
    // Pie de página
    function Footer(){
        $this->Ln((10));
        $this->Cell(50);
        //Realizado por
        $this->Cell(0,0, 'Recibido: ',0,1,'C');
        $this->Cell(0,10, '________________________________________',0,1,'R');
        $this->Cell(335,1, $GLOBALS['elaborado'],0,1,'C');
         // Posición: a 1,5 cm del final
         $this->SetY(-15);
         // Arial italic 8
         $this->SetFont('Arial','I', 7);
         // Número de página
         $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode('Página '));
         $this->Cell(0,10, $cadena .$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Creación del objeto de la clase heredada
$pdf = new PDF($r);
$pdf->AliasNbPages();
$pdf->AddPage('P', 'Letter');
$pdf->SetFont('Arial','', $tman_letra);
$rows = 0;
for ($i = 0; $i < count($r); $i++) {
    $rows++;
    $pdf->Cell(10,3, $rows, 0, 0, 'R');
    $pdf->Cell(10,3, $r[$i]->tipo_codigo, 0, 0, 'L');
    $pdf->Cell(10,3, $r[$i]->num_tdo, 0, 0, 'R');
    $pdf->Cell(50,3, htmlentities($r[$i]->nom_tdoc), 0, 0, 'R');
    $pdf->Cell(50,3, number_format($r[$i]->monto_doc, 2,",", "."), 0, 0, 'R');
    $pdf->Cell(50,3, number_format($r[$i]->sal_doc ?? 0, 2,",", "."), 0, 1, 'R');
    //Acumulador
    $total_pago += $r[$i]->monto_doc;
    $total_saldo += $r[$i]->sal_doc;
}
//
//Asignación a Globales
$GLOBALS['total_pago'] = $total_pago;
$GLOBALS['total_saldo'] = $total_saldo;
//Imprimir totales del lado derecho
$pdf->PrintTotal();
//
$pdf->Output('', htmlentities($filemame));
?>