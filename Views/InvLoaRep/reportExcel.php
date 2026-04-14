<?php
// Declaramos la libreria
require_once(SPREADEXCEL . "/vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Number;

Class Downloader{
    public function DownExcel($r, $u){
        //Variables
        $filename = "Inventario"; $ext = ".xlsx";
        //Pprocedimiento
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator($_SESSION['name_user'] . ' ' . $_SESSION['last_user'])->setTitle($filename);
        $spreadsheet->getActiveSheetIndex(0);
        //Crear hoja
        $sheet = $spreadsheet->getActiveSheet();
        $sheet ->setTitle('Inventario');
        //Escribir contenido del Excel
        //Titulos de Columnas
        $sheet -> setCellValue("A1", 'Id');
        $sheet -> setCellValue("B1", 'Código');
        $sheet -> setCellValue("C1", 'Código 2');
        $sheet -> setCellValue("D1", 'Descripción');
        $sheet -> setCellValue("E1", 'Referencia');
        $sheet -> setCellValue("F1", 'Marca');
        $sheet -> setCellValue("G1", 'Ubicación');
        $sheet -> setCellValue("H1", 'Cantidad');
        $sheet -> setCellValue("I1", 'Lote');
        $sheet -> setCellValue("J1", 'Fec.Venc.');
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        //$sheet->freezePane('J2');
        //Contenido de columnas
        $totrows = count($r);
        $line_item = 2;
        for($i = 0; $i < $totrows; $i++){
            $sheet -> setCellValue("A".$line_item, $r[$i]->id_prod);
            $sheet -> setCellValue("B".$line_item, $r[$i]->cod_prod);
            $sheet->getCell('B'.$line_item)
                ->getStyle()->getNumberFormat()
                ->setFormatCode((string) new Number(0, Number::WITHOUT_THOUSANDS_SEPARATOR));
            $sheet -> setCellValue("C".$line_item, $r[$i]->cod2_prod);
            $sheet -> setCellValue("D".$line_item, $r[$i]->nom_prod);
            $sheet -> setCellValue("E".$line_item, $r[$i]->ref_prod);
            $sheet -> setCellValue("F".$line_item, $r[$i]->nom_fab);
            $sheet -> setCellValue("G".$line_item, '');
            $sheet -> setCellValue("H".$line_item, '');
            $sheet -> setCellValue("I".$line_item, '');
            $sheet -> setCellValue("J".$line_item, '');
            $line_item++;
        }
        foreach (range('A', 'J') as $col) {
            $sheet ->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getStyle("A2:J{$line_item}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        //Crear Hoja de Ubicaciones
        // Create a new worksheet called "Ubicaciones"
        $myUbiSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Ubicaciones');
        // Attach the "My Data" worksheet as the first worksheet in the Spreadsheet object
        $spreadsheet->addSheet($myUbiSheet);
        //Titulos de Ubicaciones
        //Titulos de Columnas
        $myUbiSheet -> setCellValue("A1", 'Id');
        $myUbiSheet -> setCellValue("B1", 'Nombre');
        $myUbiSheet->getStyle('A1:B1')->getFont()->setBold(true);
        //Contenido de columnas
        $totrows = count($u);
        $line_item = 2;
        for($i = 0; $i < $totrows; $i++){
            $myUbiSheet -> setCellValue("A".$line_item, $u[$i]->id_ubi);
            $myUbiSheet -> setCellValue("B".$line_item, $u[$i]->nom_ubi);
            $line_item++;
        }
        //Nombre del archivo
        $filename = $filename . $ext;
        //Configurar cabeceras
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        //Escribir Excel
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
if(isset($r) && isset($u)){
    $excel = new Downloader();
    return $excel->DownExcel($r, $u);
}
