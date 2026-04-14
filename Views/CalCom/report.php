<?php
// Declaramos la libreria
require_once(SPREADEXCEL . "/vendor/autoload.php");
include_once VARTAX;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\View; // Asegúrate de importar View
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Number;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class Downloader
{
    public function DownExcel($r)
    {
        //Variables
        $filename = "Cálculo de Comisiones de Vendedores del " . $r[0]->fec_ini . ' al ' . $r[0]->fec_fin;
        $ext = ".xlsx";
        //Pprocedimiento
        //$spreadsheet = new Spreadsheet();
        //$spreadsheet->getProperties()
            //->setCreator($_SESSION['name_user'] . ' ' . $_SESSION['last_user'])->setTitle($filename);
        //$spreadsheet->getActiveSheetIndex(0);
        //Crear hoja
        //$sheet = $spreadsheet->getActiveSheet();
        // Obtener el objeto SheetView

        // Desactivar las líneas de cuadrícula
        //$sheetView->setSheetState(View::SHEETSTATE_HIDDEN); // Esto puede que no funcione para todas las versiones de PHPSpreadsheet
        //$sheetView->setShowGridLines(false); // Este es el método principal para ocultar la cuadrícula

        //$pageSetup = $sheet->getPageSetup();
        //$sheetView = $sheet->getSheetView();
        // Establecer el tamaño del papel a A4
        //$pageSetup->setPaperSize(PageSetup::PAPERSIZE_A4);

        //Contenido de columnas
        $totrows = count($r);
        $previous_vend = null;
        $worksheetIndex = 0;
        $spreadsheet = new Spreadsheet();
        foreach($r as $row){
            $current_vend = $row->vendedor;
            if($current_vend !== $previous_vend){
                $sheet = $spreadsheet->createSheet($worksheetIndex);
                $activeSheet = $spreadsheet->setActiveSheetIndex($worksheetIndex);
                $sheet->setTitle($current_vend);
                //Escribir contenido del Excel
                //Logo de la empresa
                $logocia = IMAGE_PATH . 'companies' . DS . $r[0]->logo;
                if (file_exists($logocia)) {
                    //echo '<br>existe imagen';
                    $drawing = new Drawing();
                    $drawing->setName('Logo de Empresa');
                    $drawing->setDescription('Logo de Emprsa');
                    $drawing->setPath($logocia);
                    $drawing->setHeight(80);
                    $drawing->setCoordinates('A1');
                    $drawing->setOffsetX(10);
                    $drawing->setWorksheet($spreadsheet->getActiveSheet());
                } else {
                    echo '<br>no existe la imagen';
                }

                //Encabezado de Cotizaciómn
                //Empresa
                $sheet->mergeCells('A1:L1')->setCellValue('A1', htmlentities($r[0]->nombre_emp))
                    ->getStyle('A1:L1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                //RIF
                $sheet->mergeCells('A2:L2')->setCellValue('A2', $r[0]->rif_empresa)
                    ->getStyle('A2:L2')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                //Titulo 01
                $data = htmlentities('Reporte Calculo de Comisiones de Vendedores');
                $sheet->mergeCells('A3:L3')->setCellValue('A3', $data)
                    ->getStyle('A3:L3')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                //Titulo 02
                $data = htmlentities('Desde ' . formatFecha($r[0]->fec_ini) . ' hasta ' . formatFecha($r[0]->fec_fin));
                $sheet->mergeCells('A4:L4')->setCellValue('A4', $data)
                    ->getStyle('A4:L4')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                //Titulo 03, solo si el Calculo es para un Vendedor
                $data = htmlentities('Vendedor ' . $r[0]->vendedor);
                $sheet->mergeCells('A5:L5')->setCellValue('A5', $data)
                    ->getStyle('A5:L5')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                //Titulos de Columnas
                $line = 7;
                $sheet->mergeCells('A' . $line . ':A' . $line)->setCellValue('A' . $line, 'TIPO')->getStyle('A' . $line)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->mergeCells('B' . $line . ':B' . $line)->setCellValue('B' . $line, 'NÚMERO')->getStyle('B' . $line)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue("C" . $line, 'FEC.DOC.')->getStyle('C' . $line)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue("D" . $line, 'FEC.PAG.')->getStyle('D' . $line)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue("E" . $line, 'CLIENTE')->getStyle('E' . $line)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue("F" . $line, 'SubTotal Doc')->getStyle('F' . $line)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue("G" . $line, '% Com')->getStyle('G' . $line)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue("H" . $line, 'Total Comisión')->getStyle('H' . $line)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue("I" . $line, 'Tasa Fact.')->getStyle('I' . $line)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue("J" . $line, 'Total Comisión Bs.')->getStyle('J' . $line)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . $line . ':M' . $line)->getFont()->setBold(true)->setSize(10);
                
                $previous_vend = $current_vend;
                $worksheetIndex++;
            }
            $line_item = $activeSheet->getHighestRow() + 1;
            $sheet->mergeCells("A$line_item:A$line_item")->setCellValue("A$line_item", html_entity_decode($row->nom_tdoc))
                ->getStyle("A$line_item:A$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $sheet->setCellValue("B$line_item", $row->num_tdo)->getStyle("B$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $sheet->setCellValue("C$line_item", formatFecha($row->fec_fact))->getStyle("C$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $sheet->setCellValue("D$line_item", formatFecha($row->fec_pag))->getStyle("D$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $sheet->setCellValue("E$line_item", $row->nom_ent)->getStyle("E$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $sheet->setCellValue("F$line_item", $row->sub_total)->getStyle("F$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $sheet->getCell("F$line_item")->getStyle()->getNumberFormat()->setFormatCode('#,###.00');
            $sheet->setCellValue("G$line_item", $row->porcentaje)->getStyle("G$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $sheet->getCell("G$line_item")->getStyle()->getNumberFormat()->setFormatCode('#,###.00');
            $sheet->setCellValue("H$line_item", $row->tot_comision)->getStyle("H$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $sheet->getCell("H$line_item")->getStyle()->getNumberFormat()->setFormatCode('#,###.00');
            $sheet->setCellValue("I$line_item", $row->tasa_cambio)->getStyle("I$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $sheet->getCell("I$line_item")->getStyle()->getNumberFormat()->setFormatCode('#,###.00');
            $sheet->setCellValue("J$line_item", "=H$line_item*I$line_item")->getStyle("J$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $sheet->getCell("J$line_item")->getStyle()->getNumberFormat()->setFormatCode('#,###.00');
        }
        //Eliminar hoja por defecto
        $spreadsheet->removeSheetByIndex($worksheetIndex);
        //REcorrer hojas para ajustar
        foreach ($spreadsheet->getAllSheets() as $sheet) {
            $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.75);
            $sheet->getPageMargins()->setBottom(0.75);
            $sheet->getPageMargins()->setLeft(0.7);
            $sheet->getPageMargins()->setRight(0.7);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);
        }
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
        $sheetCount = $spreadsheet->getSheetCount();
        for ($i = 0; $i < $sheetCount; $i++) {
            $spreadsheet->setActiveSheetIndex($i);
            $sheet = $spreadsheet->getActiveSheet();
            $lastDataRow = $sheet->getHighestDataRow();
            $line_item = $lastDataRow + 1;   
            $stratRow = 7;
            $sheet->setCellValue("G$line_item", 'TOTAL:')->getStyle("G$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            //Total Comision
            $sumRange = "H$stratRow:H$lastDataRow";
            $sheet->setCellValue("H$line_item", "=SUM($sumRange)")->getStyle("H$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $sheet->getStyle("G$line_item:H$line_item")->getFont()->setBold(true)->setSize(10);
            $sheet->getStyle("H$line_item:H$line_item")->applyFromArray($styleArray);
            //Total Comision Domestica
            $sumRange = "J$stratRow:J$lastDataRow";
            $sheet->setCellValue("J$line_item", "=SUM($sumRange)")->getStyle("J$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $sheet->getStyle("J$line_item:J$line_item")->getFont()->setBold(true)->setSize(10);
            $sheet->getCell("J$line_item")->getStyle()->getNumberFormat()->setFormatCode('#,###.00');
            $sheet->getStyle("J$line_item:J$line_item")->applyFromArray($styleArray);
           
        }
        

        //Nombre del archivo
        $filename = $filename . $ext;

        //Configurar cabeceras
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
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
if (isset($r)) {
    $excel = new Downloader();
    return $excel->DownExcel($r);
}
