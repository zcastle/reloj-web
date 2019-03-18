<?php

use Slim\Http\Request;
use Slim\Http\Response;
//
use Lib\Db;
// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    //$this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    //return $this->renderer->render($response, 'index.phtml', $args);
    /*$rows = [];
    if (($gestor = fopen( __DIR__ . "/trabajadores.csv", "r")) !== FALSE) {
        while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
            $row = array(
                "codigo" => $datos[0],
                "trabajador" => $datos[1],
                "documento" => $datos[2],
                "tienda" => $datos[3]
            );
            array_push($rows, $row);
        }
        fclose($gestor);
    }*/

    $db = new Db($this->db, $this->logger);
    $rows = $db->getEmpleados();
    return $this->view->render($response, 'home.html', ['empleados' => $rows]);
    //return $response->withJson($rows);
});

$app->get('/marcaciones/{codigo}/{tienda}[/{del}[/{al}]]', function (Request $request, Response $response, array $args) {
    $result = array("success" => true, "data" => null);
    $db = new Db($this->db, $this->logger);
    $result["data"] = $db->getMarcaciones($args["codigo"], $args["tienda"], $args["del"], $args["al"]);
    return $response->withJson($result);
});

$app->get('/pdf/{codigo}/{tienda}[/{del}[/{al}]]', function (Request $request, Response $response, array $args) {
    class PDF extends FPDF {

        private $data;

        function setData($data){
            $this->data = $data;
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

    $pdf = new PDF();
    $pdf->AddPage();
    
    $db = new Db($this->db, $this->logger);
    $rows = $db->getMarcaciones($args["codigo"], $args["tienda"], $args["del"], $args["al"]);

    $i = 1;
    $pdf->SetFont('Arial', '' , 9);
    $w = array(10, 20, 120, 40);
    foreach($rows AS $row){
        $pdf->Cell($w[0], 5, $i++, 1);
        $pdf->Cell($w[1], 5, $row->codigo, 1);
        $pdf->Cell($w[2], 5, $row->empleado, 1);
        $pdf->Cell($w[3], 5, $row->fecha_hora, 1, 1);
    }

    $response = $response->withHeader('Content-type', 'application/pdf');
    return $response->write( $pdf->Output('My cool PDF', 'S') );
});