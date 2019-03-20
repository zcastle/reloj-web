<?php

namespace Lib;


class Pdf extends \FPDF {

    /*public function __construct(){
        $this->AddPage();
    }*/

    function setData($rows){
        $i = 1;
        $this->SetFont('Arial', '' , 9);
        $w = array(10, 20, 120, 40);
        foreach($rows AS $row){
            $this->Cell($w[0], 5, $i++, 1);
            $this->Cell($w[1], 5, $row->codigo, 1);
            $this->Cell($w[2], 5, $row->empleado, 1);
            $this->Cell($w[3], 5, $row->fecha_hora, 1, 1);
        }
    }

    function Header(){
        $this->SetFont('Arial', 'B' , 26);
        $this->Cell(190, 10, 'GIANFRANCO CAFFE', 0, 1, "C");
        $this->SetFont('Arial', '' , 12);
        $this->Cell(190, 5, 'REPORTE DE MARCACIONES', 0, 1, "C");
        $this->ln(5);

        $w = array(10, 20, 120, 40);
        $this->SetFont('Arial', 'B' , 10);
        $this->Cell($w[0], 5, 'LN', 1);
        $this->Cell($w[1], 5, 'CODIGO', 1);
        $this->Cell($w[2], 5, 'EMPLEADO', 1);
        $this->Cell($w[3], 5, 'MARCACION', 1, 1);
    }

    function Footer(){
        // Posición a 1,5 cm del final
        $this->SetY(-15);
        // Arial itálica 8
        $this->SetFont('Arial', 'I', 8);
        // Color del texto en gris
        $this->SetTextColor(128);
        // Número de página
        $this->Cell(0, 10, 'Pagina '.$this->PageNo(), 0, 0, 'C');
    }
}

?>