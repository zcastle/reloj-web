<?php

namespace Lib;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Xls {

    public function build($rows){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $ln = 1;
        $sheet->setCellValue('A'.$ln, 'LN');
        $sheet->setCellValue('B'.$ln, 'CODIGO');
        $sheet->setCellValue('C'.$ln, 'EMPLEADO');
        $sheet->setCellValue('D'.$ln++, 'MARCACION');

        $i = 1;
        foreach($rows AS $row){
            $sheet->setCellValue('A'.$ln, $i++);
            $sheet->setCellValue('B'.$ln, $row->codigo);
            $sheet->setCellValue('C'.$ln, $row->empleado);
            $sheet->setCellValue('D'.$ln++, $row->fecha_hora);
        }

        return IOFactory::createWriter($spreadsheet, 'Xlsx');
    }
}