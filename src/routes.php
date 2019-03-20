<?php

use Slim\Http\Request;
use Slim\Http\Response;
//
use Lib\Db;
use Lib\Pdf;
use Lib\Xls;
// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    //$this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    //return $this->renderer->render($response, 'index.phtml', $args);

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

    $db = new Db($this->db, $this->logger);
    $rows = $db->getMarcaciones($args["codigo"], $args["tienda"], $args["del"], $args["al"]);

    $pdf = new Pdf();
    $pdf->AddPage();
    $pdf->setData($rows);

    return $response->withHeader('Content-type', 'application/pdf')
                    ->write($pdf->Output('Marcaciones', 'S'));
});

$app->get('/exportar/{codigo}/{tienda}[/{del}[/{al}]]', function (Request $request, Response $response, array $args) {
    $result = array("success" => true, "data" => null);

    $db = new Db($this->db, $this->logger);
    $rows = $db->getMarcaciones($args["codigo"], $args["tienda"], $args["del"], $args["al"]);
    
    $xls = new Xls();
    $build = $xls->build($rows);

    return $response->withHeader('Content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                    ->withHeader('Content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                    ->withHeader('Content-Disposition', 'attachment;filename="Marcaciones.xlsx"')
                    ->withHeader('Cache-Control', 'max-age=0')
                    ->withHeader('Cache-Control', 'max-age=1')
                    ->withHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT')
                    ->withHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT')
                    ->withHeader('Cache-Control', 'cache, must-revalidate')
                    ->withHeader('Pragma', 'public')
                    ->write($build->save('php://output'));
});