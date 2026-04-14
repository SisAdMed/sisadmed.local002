<?php
// Declaramos la libreria
require_once(SPREADEXCEL . "/vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet;
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
use \PhpOffice\PhpSpreadsheet\Writer\Pdf;
use \PhpOffice\PhpSpreadsheet\Writer\Csv;

Class Downloader{
    public function DownExcel($r){
        //Variables
        $filename = "Reporte Retenciones de IVA " . $r[0]->nombre_emp; $ext = ".xlsx";
        $fec_ini = $_GET['fec_ini'];
        $fec_fin = $_GET['fec_fin'];
        //Pprocedimiento
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator($_SESSION['name_user'] . ' ' . $_SESSION['last_user'])->setTitle($filename);
        $spreadsheet->getActiveSheetIndex(0);
        //Crear hoja
        $sheet = $spreadsheet->getActiveSheet();
        $sheet ->setTitle('Retenciones de IVA');
        //Escribir contenido del Excel
        //Logo de la empresa
        $logocia = IMAGE_PATH .'companies' . DS . $r[0]->logo;
        if(file_exists($logocia)){
            //echo '<br>existe imagen';
            $drawing = new Drawing();
            $drawing->setName('Logo de Empresa');
            $drawing->setDescription('Logo de Emprsa');
            $drawing->setPath($logocia);
            $drawing->setHeight(100);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(10);
            $drawing->setWorksheet($spreadsheet->getActiveSheet());
        }else{
            echo '<br>no existe la imagen';
        }
        //Titulo del Reporte
        if(count($r) > 0 ){
            $sheet->mergeCells('A3:M3')->setCellValue('A3', $r[0]->nombre_emp);    
        }else{
            $sheet->mergeCells('A3:M3')->setCellValue('A3', 'NO EXISTE DATA PARA LA EMPRESA Y PERÍODO SOLICITADO');
        }
        
        $sheet->mergeCells('A4:M4')->setCellValue('A4', 'RETENCIONES DE IVA');
        $sheet->mergeCells('A5:M5')->setCellValue('A5', 'Período desde: ' . formatFechaSlash($fec_ini) . ' hasta: ' . formatFechaSlash($fec_fin));
        $sheet->getStyle('A3:M5')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3:M5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        //Titulos de Columnas
        $sheet -> setCellValue("A10", 'Nro.Oper.');
        $sheet -> setCellValue("B10", 'Fecha');
        $sheet -> setCellValue("C10", 'RIF');
        $sheet -> setCellValue("D10", 'Nombre o Razón Social');
        $sheet -> setCellValue("E10", 'Documento');
        $sheet -> setCellValue("F10", 'Control');
        $sheet -> setCellValue("G10", 'Nro. Retención');
        $sheet -> setCellValue("H10", 'Total Compras');
        $sheet -> setCellValue("I10", 'Monto No Gravable');
        $sheet -> setCellValue("J10", 'Base Imponible');
        $sheet -> setCellValue("K10", 'Impuesto IVA');
        $sheet -> setCellValue("L10", '% Retencíon');
        $sheet -> setCellValue("M10", 'Retenido');
        

        $sheet->getStyle('A10:M10')->getFont()->setBold(true);
        //$sheet->freezePane('J2');
        //Contenido de columnas
        
        $totrows = count($r);
        $line_item = 11;
        $first_i = 11;
        $item = 1;
        for($i = 0; $i < $totrows; $i++){
            $sheet -> setCellValue("A".$line_item, $item++);
            $sheet -> setCellValue("B".$line_item, formatFechaSlash($r[$i]->fecha_pago));
            $sheet -> setCellValue("C".$line_item, $r[$i]->rif_ent);
            $sheet -> setCellValue("D".$line_item, $r[$i]->nom_ent);
            $sheet -> setCellValue("E".$line_item, $r[$i]->num_tdo);
            $sheet -> setCellValue("F".$line_item, $r[$i]->num_control);
            $sheet -> setCellValue("G".$line_item, $r[$i]->num_retiva);
            $sheet->getCell('G'.$line_item)
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#####');
            $sheet -> setCellValue("H".$line_item, $r[$i]->tot_compras);
            $sheet->getCell('H'.$line_item)
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.00');
            $sheet -> setCellValue('I'.$line_item, $r[$i]->tot_exento);
            $sheet->getCell('I'.$line_item)
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.00');
            $sheet -> setCellValue('J'.$line_item, $r[$i]->tot_base);
            $sheet->getCell('J'.$line_item)
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.00');
            $sheet -> setCellValue('K'.$line_item, $r[$i]->tot_iva);
            $sheet->getCell('K'.$line_item)
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.00');
            $sheet -> setCellValue('L'.$line_item, $r[$i]->tasa_retiva);
            $sheet->getCell('L'.$line_item)
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.00');
            $sheet -> setCellValue('M'.$line_item, $r[$i]->tot_ret);
            $sheet->getCell('M'.$line_item)
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.00');
            $line_item++;
        }
        $dateFormat = 'dd-mm-yyyy'; // You can modify this format as needed
        $sheet->getStyle("B11:B{$line_item}")
            ->getNumberFormat()
            ->setFormatCode($dateFormat);
        $sheet->getStyle("E10:M{$line_item}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    //
        $last_i = $line_item - 1;
                //
        $sumrange = 'H' . $first_i . ':H' . $last_i;
        $sheet->setCellValue('H' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('H'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //
        $sumrange = 'I' . $first_i . ':I' . $last_i;
        $sheet->setCellValue('I' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('I'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //
        $sumrange = 'J' . $first_i . ':J' . $last_i;
        $sheet->setCellValue('J' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('J'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //
        $sumrange = 'K' . $first_i . ':K' . $last_i;
        $sheet->setCellValue('K' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('K'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //
        $sumrange = 'M' . $first_i . ':M' . $last_i;
        $sheet->setCellValue('M' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('M'.$line_item)
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
        $sheet->getStyle('H' . $line_item . ':M' . $line_item)->applyFromArray($styleArray);
        //
        $sheet->getStyle('A'.$line_item.':M'. $line_item)->getFont()->setBold(true);
        
        $sheet->getStyle("A10:M{$line_item}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->setCellValue('G' . $line_item, 'Total:');
        $sheet->getStyle("H10:M{$line_item}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("A10:A{$line_item}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        //
        foreach (range("A1", "M{$line_item}") as $col) {
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
