<?php

namespace Lib;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;
//use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Xls {

    public function build($rows){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $ln = 1;
        $sheet->setCellValue('A'.$ln, 'LN');
        $sheet->setCellValue('B'.$ln, 'CODIGO');
        $sheet->setCellValue('C'.$ln, 'EMPLEADO');
        $sheet->setCellValue('D'.$ln, 'MARCACION');
        //$sheet->setCellValue('E'.$ln, 'MARCACION');
        $ln++;

        $i = 1;
        foreach($rows AS $row){
            $sheet->setCellValue('A'.$ln, $i);
            $sheet->setCellValue('B'.$ln, $row->codigo);
            $sheet->setCellValue('C'.$ln, $row->empleado);
            $sheet->setCellValue('D'.$ln, Date::PHPToExcel($row->fecha_hora));
            //$sheet->setCellValue('D'.$ln, Date::PHPToExcel(gmmktime(0, 0, 0, date('m'), date('d'), date('Y'))));
            //NumberFormat::FORMAT_DATE_DATETIME
            $sheet->getStyle('D'.$ln)->getNumberFormat()->setFormatCode("dd/mm/yyyy h:mm:ss");
            //$sheet->setCellValue('E'.$ln, $row->fecha_hora);

            $i++;
            $ln++;
        }

        return IOFactory::createWriter($spreadsheet, 'Xlsx');
    }
}