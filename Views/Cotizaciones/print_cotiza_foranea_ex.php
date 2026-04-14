<?php
//
include_once VARTAX;
// Declaramos la libreria
require_once(SPREADEXCEL . "/vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Number;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use \PhpOffice\PhpSpreadsheet\Shared\Date;
use \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter;

Class Downloader{
    public function DownExcel($r){
        //Variables
        $filename = "Cotizacion " . $r[0]->num_tdo . '-' . $r[0]->nom_ent; $ext = ".xlsx";
        
        //Pprocedimiento
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator($r[0]->nombre_emp)
            ->setLastModifiedBy($_SESSION['name_user'] . ' ' . $_SESSION['last_user'])
            ->setTitle($filename)
            ->setSubject('Reporte de Cotización')
            ->setDescription('Para mostrar la cotización en formato Excel y asi agregar linea de detalles por item')
            ->setKeywords('cotizacion productos inventarios')
            ->setCategory('Ventas')
            ->setCustomProperty('Editor', 'José Vargas')
            ->setCustomProperty('Version', 1.0)
            ->setCustomProperty('Tested', true);
        $spreadsheet->getActiveSheetIndex(0);
        //Crear hoja
        $sheet = $spreadsheet->getActiveSheet();
        //Set default font
        $spreadsheet->getDefaultStyle()
                    ->getFont()
                    ->setName('Arial')
                    ->setSize(8);
        //Escribir contenido del Excel
        //Logo de la empresa
        $logocia = IMAGE_PATH .'companies' . DS . $r[0]->logo;
        if(file_exists($logocia)){
            // Initiate new HeaderFooterDrawing instance
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing();
            // Set the name of the logo
            $drawing->setName($r[0]->nombre_emp . ' Logo');
            // Set the path of the logo
            $drawing->setPath($logocia);
            //Size
            $drawing ->setHeight(50);
            // Add the image to the header of the sheet
            $sheet->getHeaderFooter()->addImage($drawing, HeaderFooter::IMAGE_HEADER_CENTER);
            //Empresa
            /*
            $titlecenter = $r[0]->nombre_emp . PHP_EOL;
            $titlecenter .= 'RIF ' . $r[0]->rif_empresa . PHP_EOL;
            $titlecenter .= $r[0]->dir_emp . PHP_EOL;
            $titlecenter .= $r[0]->email_emp;
            $sheet->getHeaderFooter()->setOddHeader('&C&B'. $titlecenter );
            */
            //$sheet->getHeaderFooter()->setOddFooter('&CPágina &P de &N');
        }
        //Titulo del Reporte
        /*
        $sheet->mergeCells('A2:E2')->setCellValue('A2', $r[0]->nombre_emp);
        $sheet->getStyle('A2:E5')->getFont()->setBold(true)->setSize(14);
        $sheet->mergeCells('A3:E3')->setCellValue('A3', 'RIF: ' . $r[0]->rif_empresa);
        $sheet->mergeCells('A4:E4')->setCellValue('A4', $r[0]->dir_emp);
        $sheet->mergeCells('A5:E5')->setCellValue('A5', $r[0]->email_emp);
        $sheet->getStyle('A4:E5')->getFont()->setBold(true)->setSize(10);
        $sheet->getStyle('A2:E5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        */
        //Datos de la Cotización
        $sheet -> setCellValue("A7", 'Cliente:');
        $sheet -> setCellValue("A8", 'RIF:');
        $sheet -> setCellValue("A9", 'Dirección:');
        $sheet -> setCellValue("A10", 'Condición de Pago:');    

        $sheet -> setCellValue("B7",  $r[0]->nom_ent);
        $sheet -> setCellValue("B8", $r[0]->rif_ent);
        $dir = $r[0]->dir_ent . ' ' . strtoupper($r[0]->nombre_ciudad) . ' ' .strtoupper($r[0]->nombre_edo) . ' ' . strtoupper($r[0]->nombre_pais);
        $sheet -> setCellValue("B9", $dir);
        $sheet -> setCellValue("B10", $r[0]->des_diascre);

        $sheet -> setCellValue("E7", 'Fecha:');
        $sheet -> setCellValue("E8", 'Moneda:');
        $sheet -> setCellValue("E9", 'Cotización:');
        
        $sheet -> setCellValue("F7", $r[0]->fecha_comp);
        $sheet -> setCellValue("F8", $r[0]->codigo_moneda);
        $sheet -> setCellValue("F9", $r[0]->id_cot);


        //Encabezado de Columnas
        $line_item = 12;
        $sheet -> setCellValue("A{$line_item}", 'DESCRIPCION');
        $sheet -> setCellValue("B{$line_item}", 'IVA');
        $sheet -> setCellValue("C{$line_item}", 'CANT');
        $sheet -> setCellValue("D{$line_item}", 'PRECIO');
        $sheet -> setCellValue("E{$line_item}", 'TOTAL USD');
        $sheet->getStyle("A{$line_item}:E1{$line_item}")->getFont()->setBold(true)->setSize(8);
        //Contenido de columnas
        //Tasa de IVA
        $tasa_iva_cfg = VatTaxModel::ratevatTax($r[0]->fecha_comp, 'IVA');
        $tasa_iva = $tasa_iva_cfg[0]['txr1_iva'];
        $totrows = count($r);
        $line_item ++;
        $first_i = $line_item;
        /*
        for($i = 0; $i < $totrows; $i++){
            $nom_prod =$r[$i]->nom_prod . ' ' . $r[$i]->nom_fab . ' ' . $r[$i]->ref_prod;
            if($r[$i]->lote_prod == 1 || $r[$i]->lote_prod == 'S'){
                if($r[$i]->fec_ven >= date("Y-m-d")){
                     $nom_prod = $nom_prod . ' Fec.Venc. ' . formatFecha($r[$i]->fec_ven);
                 }
             }
            $sheet->setCellValue("A".$line_item, html_entity_decode($nom_prod));
            if($r[$i]->iva_prod == 'S'){
                $sheet->setCellValue("B".$line_item, $tasa_iva);
                $sheet->getCell('B'.$line_item)
                    ->getStyle()->getNumberFormat()
                    ->setFormatCode('#,###.00');
            }else {
                $sheet->setCellValue("B".$line_item, "(E)");
            }
            $sheet->setCellValue('C'.$line_item, $r[$i]->can_det);
            $sheet->setCellValue("D".$line_item, $r[$i]->pre_vta);
            $sheet->getCell('D'.$line_item)
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.0000');
                $sheet->setCellValue("E".$line_item, $r[$i]->sub_total);
                $sheet->getCell('E'.$line_item)
                    ->getStyle()->getNumberFormat()
                    ->setFormatCode('#,###.0000');
            $line_item++;
        }
        */
        //
        /*
        $last_i = $line_item - 1;
        //
        $sumrange = 'L' . $first_i . ':L' . $last_i;
        $sheet->setCellValue('L' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('L'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //
        $sumrange = 'O' . $first_i . ':O' . $last_i;
        $sheet->setCellValue('O' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('O'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //
        $sumrange = 'M' . $first_i . ':M' . $last_i;
        $sheet->setCellValue('M' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('M'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //
        $sumrange = 'N' . $first_i . ':N' . $last_i;
        $sheet->setCellValue('N' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('N'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //
        $sumrange = 'O' . $first_i . ':O' . $last_i;
        $sheet->setCellValue('O' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('O'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //
        $sumrange = 'Q' . $first_i . ':Q' . $last_i;
        $sheet->setCellValue('Q' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('Q'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //Border
        $styleArray = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('L' . $line_item . ':Q' . $line_item)->applyFromArray($styleArray);
        //
        $sheet->getStyle('A'.$line_item.':Q'. $line_item)->getFont()->setBold(true);
        
        $sheet->getStyle("A10:K{$line_item}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->setCellValue('K' . $line_item, 'Total:');
        $sheet->getStyle("L10:Q{$line_item}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("A10:A{$line_item}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        //
        */
        $sheet->getStyle("A{$first_i}:F{$line_item}")->getFont()->setSize(8);
        $sheet->getStyle("B{$first_i}:B{$line_item}")->getAlignment()->setHorizontal('center');
        foreach (range("A1", "F{$line_item}") as $col) {
            $sheet ->getColumnDimension($col)->setAutoSize(true);
        }
        //Nombre del archivo
        $filename = $filename . $ext;
        //Configurar cabeceras
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        //Escribir Excel
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        if (file_exists($filename)) {
            unlink($filename);
        }
        $writer->save('php://output');
        exit;
    }
}
if(isset($r)){
    $excel = new Downloader();
    return $excel->DownExcel($r);
}